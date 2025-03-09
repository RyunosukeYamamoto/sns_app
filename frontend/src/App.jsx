import { useEffect, useState } from 'react'
import reactLogo from './assets/react.svg'
import viteLogo from '/vite.svg'
import axios from "axios";
// import './App.css'

function App() {
  const [posts, setPosts] = useState([]);
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [users, setUser] = useState([]);

  const apiEndPoint = import.meta.env.VITE_API_END_POINT;

  useEffect(() => {
    axios.get(apiEndPoint + '/api/test')
      .then(response => {
        setPosts(response.data);
        console.log(response.data)
      })
      .catch(() => {
        console.log('通信に失敗しました');
      });
  }, []);

  axios.defaults.withCredentials = true;
  const http = axios.create({
    baseURL: apiEndPoint,
    withXSRFToken: true,
    withCredentials: true,
    xsrfHeaderName: "X-XSRF-TOKEN",
    headers: {
      'Content-Type': 'application/json',
    },
  });

  const login = () => {
    http.get('/sanctum/csrf-cookie').then((res) => {
      // ログイン処理
      http.post('/api/spa/login', {email, password}).then((res) => {
        console.log(res);
      })
    })
  }
  const logout = () => {
    http.post('/api/spa/logout').then((res) => {
    console.log(res);
  })}
  const getUser = () => {
    http.get(apiEndPoint + '/api/user').then((res) => {
      // setUser(res.data);
      console.log(res.data);
    })
  }
  const reset = () => {setUser([])}
  const onChangeEmail = (e) => setEmail(e.target.value);
  const onChangePassword = (e) => setPassword(e.target.value);

  return (
    <div className="App">
      <nav>
        <button onClick={login}>ログイン</button>
        <button onClick={logout}>ログアウト</button>
        <button onClick={getUser}>User 一覧</button>
        <button onClick={reset}>リセット</button>
      </nav>
        <br />
      <div>
        <label>email</label>
        <input type="text" value={email} onChange={onChangeEmail}/>
        <label>password</label>
        <input type="password" value={password} onChange={onChangePassword}/>
      </div>
      <div>
        {
          users.map((user) => {
            return (
              <p key={user.email}>{user.name}</p>
            )
          })
        }
      </div>
    </div>
  )
}

export default App
