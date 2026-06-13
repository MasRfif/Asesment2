export default function LaporanForm({ initial = {}, onSubmit, buttonText }) {
  function submit(e) {
    e.preventDefault()
    const formData = new FormData(e.currentTarget)
    onSubmit(formData)
  }

  const waktu = initial.waktu_laporan ? initial.waktu_laporan.replace(' ', 'T').slice(0, 16) : ''

  return (
    <form className="react-form" onSubmit={submit}>
      <label>Lokasi Fasilitas</label>
      <input name="lokasi_fasilitas" defaultValue={initial.lokasi_fasilitas || ''} required />

      <label>Waktu Laporan</label>
      <input type="datetime-local" name="waktu_laporan" defaultValue={waktu} required />

      <label>Jumlah Fasilitas Rusak</label>
      <input type="number" min="1" name="jumlah_fasilitas_rusak" defaultValue={initial.jumlah_fasilitas_rusak || ''} required />

      <label>Deskripsi</label>
      <textarea name="deskripsi" rows="5" defaultValue={initial.deskripsi || ''} required />

      <label>Foto Bukti</label>
      <input type="file" name="foto_bukti" accept=".jpg,.jpeg,.png" />

      <button>{buttonText}</button>
    </form>
  )
}
