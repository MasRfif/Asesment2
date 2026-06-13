import { Edit3, Trash2 } from 'lucide-react'

export default function LaporanCard({ item, onEdit, onDelete }) {
  const imageUrl = item.foto_bukti && item.foto_bukti !== 'tanpa_foto.jpg'
    ? `http://localhost/smartcampus_facility/server/uploads/${item.foto_bukti}`
    : null

  return (
    <article className="laporan-card">
      <div className="laporan-content">
        <div className="laporan-info">
          <div className="card-top">
            <div>
              <h3>{item.lokasi_fasilitas}</h3>
              <p>{item.waktu_laporan}</p>
            </div>
            <span className={`pill ${item.status_kerusakan?.toLowerCase()}`}>{item.status_kerusakan}</span>
          </div>

          <p className="desc">{item.deskripsi}</p>

          <div className="meta-row">
            <span>Jumlah rusak: <b>{item.jumlah_fasilitas_rusak}</b></span>
            <span>File: <b>{item.foto_bukti}</b></span>
          </div>

          <div className="card-actions">
            <button onClick={() => onEdit(item.id)}><Edit3 size={16} /> Edit</button>
            <button className="danger" onClick={() => onDelete(item.id)}><Trash2 size={16} /> Hapus</button>
          </div>
        </div>

        <div className="laporan-image-box">
          {imageUrl ? (
            <img src={imageUrl} alt={`Bukti ${item.lokasi_fasilitas}`} className="laporan-image" />
          ) : (
            <div className="no-image">Tidak ada foto</div>
          )}
        </div>
      </div>
    </article>
  )
}
