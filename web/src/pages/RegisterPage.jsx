import { useState } from 'react'
import { registerUser, saveToken, saveUser } from '../api/laporanApi.js'

export default function RegisterPage({ onLogin, setPage }) {
  const [error, setError] = useState('')

  async function submit(e) {
    e.preventDefault()
    setError('')
    const form = new FormData(e.currentTarget)
    const result = await registerUser({
      nama: form.get('nama'),
      email: form.get('email'),
      password: form.get('password')
    })

    if (result.status !== 'success') {
      setError(result.message || 'Register gagal')
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
          <h1>Register Operator</h1>
          <p>Buat akun operator baru. Setelah register berhasil, kamu langsung masuk dashboard.</p>
        </div>

        {error && <div className="alert-error">{error}</div>}

        <form className="react-form" onSubmit={submit}>
          <label>Nama</label>
          <input name="nama" placeholder="Nama operator" required />

          <label>Email</label>
          <input type="email" name="email" placeholder="operator@email.com" required />

          <label>Password</label>
          <input type="password" name="password" minLength="6" placeholder="Minimal 6 karakter" required />

          <button>Register</button>
        </form>

        <p className="auth-switch">
          Sudah punya akun? <button onClick={() => setPage('login')}>Login</button>
        </p>
      </section>
    </main>
  )
}
