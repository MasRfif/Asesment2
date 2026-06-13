import { useEffect, useState } from 'react'
import Navbar from './components/Navbar.jsx'
import DashboardPage from './pages/DashboardPage.jsx'
import LaporanPage from './pages/LaporanPage.jsx'
import AddLaporanPage from './pages/AddLaporanPage.jsx'
import EditLaporanPage from './pages/EditLaporanPage.jsx'
import LoginPage from './pages/LoginPage.jsx'
import RegisterPage from './pages/RegisterPage.jsx'
import { clearToken, getSavedUser, getToken } from './api/laporanApi.js'

export default function App() {
  const [page, setPage] = useState('dashboard')
  const [editId, setEditId] = useState(null)
  const [user, setUser] = useState(null)

  useEffect(() => {
    const token = getToken()
    const savedUser = getSavedUser()
    if (token && savedUser) setUser(savedUser)
    else setPage('login')
  }, [])

  function openEdit(id) {
    setEditId(id)
    setPage('edit')
  }

  function logout() {
    clearToken()
    setUser(null)
    setPage('login')
  }

  if (!user) {
    if (page === 'register') return <RegisterPage onLogin={setUser} setPage={setPage} />
    return <LoginPage onLogin={setUser} setPage={setPage} />
  }

  return (
    <div className="react-shell">
      <Navbar page={page} setPage={setPage} user={user} onLogout={logout} />
      <main className="react-main">
        {page === 'dashboard' && <DashboardPage setPage={setPage} user={user} />}
        {page === 'laporan' && <LaporanPage openEdit={openEdit} />}
        {page === 'add' && <AddLaporanPage setPage={setPage} />}
        {page === 'edit' && <EditLaporanPage id={editId} setPage={setPage} />}
      </main>
    </div>
  )
}
