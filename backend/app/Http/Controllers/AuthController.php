<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignupFormRequest;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //  ログイン時はsanctumそんな関係ない (?)
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            /** @var \App\Models\Account $account **/
            // $account = Auth::guard('web')->user(); エラーにならない
            $account = Auth::guard()->user(); // web guardからユーザーを取得してる
            $token = $account->createToken('AccessToken')->plainTextToken; // sanctum のメソッドcreateTokenでpersonal_access_tokensテーブルにトークンを作成する
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => '認証に失敗しました'], 401);
        }
    }

    // routeに書いた [auth:sanctum] のおかげでユーザーが特定できてる。webは関係ない
    public function user(Request $request)
    {
        // Log::alert(Auth::guard('api')->user()); エラーになる。api guard ではaccountsテーブル内のapi_tokenを見て認証してる。
        // accountsテーブルにapi_tokenカラムは作ってないから認証できない。その辺の面倒なことを sanctum がやってくれてる。
        // Log::alert(Auth::guard('web')->user()); 何にも入ってない。web guard はそもそもトークンで認証できない。
        return response()->json(
            [
                'name' => $request->user()->name,
                // 'name' => Auth::guard('web')->user()->name, エラーになる
                'email' => $request->user()->email,
                'birthday' => $request->user()->birthday,
            ]
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'ログアウトしました。'], 200);
    }

    public function signup(SignupFormRequest $request)
    {
        $safe = $request->safe()->all();
        Log::alert($safe);
        $user = Account::create([...$safe, "password" => Hash::make($request->password)]);
    }
}
