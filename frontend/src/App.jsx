import { useEffect, useState } from 'react'
import reactLogo from './assets/react.svg'
import viteLogo from '/vite.svg'
import axios from "axios";
// import './App.css'

function App() {
  const [posts, setPosts] = useState([]);

  useEffect(() => {
    axios.get(import.meta.env.VITE_API_END_POINT)
      .then(response => {
        setPosts(response.data);
        console.log(response.data)
      })
      .catch(() => {
        console.log('通信に失敗しました');
      });
  }, [])
  return (
    <>
    </>
  )
}

export default App
