import { useEffect, useState } from "react";
import { deleteLaporan, getLaporan } from "../api/laporanApi.js";
import LaporanCard from "../components/LaporanCard.jsx";

export default function LaporanPage({ openEdit }) {
  const [items, setItems] = useState([]);

  function load() {
    getLaporan().then((res) => setItems(res.data || []));
  }
  useEffect(load, []);

  async function remove(id) {
    if (!confirm("Hapus laporan ini?")) return;
    await deleteLaporan(id);
    load();
  }

  return (
    <section className="page-card">
      <div className="page-title">
        <div>
          <h2>Data Laporan</h2>
          <p>Daftar laporan kerusakan fasilitas kampus dari API.</p>
        </div>
      </div>
      <div className="card-grid">
        {items.map((item) => (
          <LaporanCard
            key={item.id}
            item={item}
            onEdit={openEdit}
            onDelete={remove}
          />
        ))}
      </div>
    </section>
  );
}
