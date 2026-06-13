import { createLaporan } from "../api/laporanApi.js";
import LaporanForm from "../components/LaporanForm.jsx";

export default function AddLaporanPage({ setPage }) {
  async function save(formData) {
    await createLaporan(formData);
    setPage("laporan");
  }
  return (
    <section className="page-card form-width">
      <h2>Tambah Laporan</h2>
      <p>Isi data laporan kerusakan fasilitas kampus.</p>
      <LaporanForm onSubmit={save} buttonText="Simpan Laporan" />
    </section>
  );
}
