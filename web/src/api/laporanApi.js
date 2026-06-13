const API_URL = 'http://localhost/smartcampus_facility/server/api.php'
const LAPORAN_URL = `${API_URL}?path=laporan`

export function saveToken(token) {
  localStorage.setItem('smartcampus_token', token)
}

export function getToken() {
  return localStorage.getItem('smartcampus_token') || ''
}

export function clearToken() {
  localStorage.removeItem('smartcampus_token')
  localStorage.removeItem('smartcampus_user')
}

export function saveUser(user) {
  localStorage.setItem('smartcampus_user', JSON.stringify(user))
}

export function getSavedUser() {
  const raw = localStorage.getItem('smartcampus_user')
  return raw ? JSON.parse(raw) : null
}

function authHeader() {
  return { Authorization: `Bearer ${getToken()}` }
}

export async function loginUser(data) {
  const res = await fetch(`${API_URL}?path=login`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  })
  return res.json()
}

export async function registerUser(data) {
  const res = await fetch(`${API_URL}?path=register`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  })
  return res.json()
}

export async function getMe() {
  const res = await fetch(`${API_URL}?path=me`, { headers: authHeader() })
  return res.json()
}

export async function getLaporan() {
  const res = await fetch(LAPORAN_URL, { headers: authHeader() })
  return res.json()
}

export async function getDetailLaporan(id) {
  const res = await fetch(`${LAPORAN_URL}&id=${id}`, { headers: authHeader() })
  return res.json()
}

export async function createLaporan(formData) {
  const res = await fetch(LAPORAN_URL, { method: 'POST', headers: authHeader(), body: formData })
  return res.json()
}

export async function updateLaporan(id, formData) {
  formData.append('_method', 'PUT')
  const res = await fetch(`${LAPORAN_URL}&id=${id}`, { method: 'POST', headers: authHeader(), body: formData })
  return res.json()
}

export async function deleteLaporan(id) {
  const res = await fetch(`${LAPORAN_URL}&id=${id}`, { method: 'DELETE', headers: authHeader() })
  return res.json()
}
