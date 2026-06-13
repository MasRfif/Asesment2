import { useEffect, useState } from "react";
import { getLaporan } from "../api/laporanApi.js";

export default function DashboardPage({ setPage, user }) {
  const [items, setItems] = useState([]);

  useEffect(() => {
    getLaporan().then((res) => setItems(res.data || []));
  }, []);

  const ringan = items.filter((i) => i.status_kerusakan === "Ringan").length;
  const sedang = items.filter((i) => i.status_kerusakan === "Sedang").length;
  const berat = items.filter((i) => i.status_kerusakan === "Berat").length;

  return (
    <>
      <section className="react-hero">
        <div>
          <span>SmartCampus Facility Report</span>
          <h1>Dashboard Laporan Kerusakan Fasilitas</h1>
          <p>
            Halo, {user?.nama}. Data diambil dari RESTful API PHP memakai token
            login.
          </p>
        </div>
        <button onClick={() => setPage("add")}>Tambah Laporan</button>
      </section>
      <section className="chart-panel">
        <div className="fake-line-chart">
          <i></i>
          <b></b>
          <em></em>
        </div>
        <div className="bar-box">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>
        </div>
      </section>
      <section className="react-stats">
        <div>
          <span>Total</span>
          <strong>{items.length}</strong>
          <small>Semua laporan</small>
        </div>
        <div>
          <span>Ringan</span>
          <strong>{ringan}</strong>
          <small>1 sampai 2 rusak</small>
        </div>
        <div>
          <span>Sedang</span>
          <strong>{sedang}</strong>
          <small>3 sampai 5 rusak</small>
        </div>
        <div>
          <span>Berat</span>
          <strong>{berat}</strong>
          <small>Lebih dari 5 rusak</small>
        </div>
      </section>
    </>
  );
}
