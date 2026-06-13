import { useState } from 'react'
import { loginUser, saveToken, saveUser } from '../api/laporanApi.js'

export default function LoginPage({ onLogin, setPage }) {
  const [error, setError] = useState('')

  async function submit(e) {
    e.preventDefault()
    setError('')
    const form = new FormData(e.currentTarget)
    const result = await loginUser({
      email: form.get('email'),
      password: form.get('password')
    })

    if (result.status !== 'success') {
      setError(result.message || 'Login gagal')
      return
    }

    saveToken(result.data.api_token)
    saveUser(result.data)
    onLogin(result.data)
  }

  return (
    <main className="auth-page">
      <section className="auth-panel">
        <div>
          <span className="auth-label">SmartCampus Facility Report</span>
          <h1>Login Operator</h1>
          <p>Masuk untuk mengelola laporan kerusakan fasilitas kampus.</p>
        </div>

        {error && <div className="alert-error">{error}</div>}

        <form className="react-form" onSubmit={submit}>
          <label>Email</label>
          <input type="email" name="email" defaultValue="operator@gmail.com" required />

          <label>Password</label>
          <input type="password" name="password" defaultValue="123456" minLength="6" required />

          <button>Login</button>
        </form>

        <p className="auth-switch">
          Belum punya akun? <button onClick={() => setPage('register')}>Register</button>
        </p>
      </section>
    </main>
  )
}
