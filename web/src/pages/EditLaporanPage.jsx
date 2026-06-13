import { useEffect, useState } from "react";
import { getDetailLaporan, updateLaporan } from "../api/laporanApi.js";
import LaporanForm from "../components/LaporanForm.jsx";

export default function EditLaporanPage({ id, setPage }) {
  const [data, setData] = useState(null);

  useEffect(() => {
    if (id) getDetailLaporan(id).then((res) => setData(res.data));
  }, [id]);

  async function save(formData) {
    await updateLaporan(id, formData);
    setPage("laporan");
  }

  if (!data)
    return (
      <section className="page-card">
        <p>Memuat data...</p>
      </section>
    );

  return (
    <section className="page-card form-width">
      <h2>Edit Laporan</h2>
      <p>Ubah data laporan yang sudah dipilih.</p>
      <LaporanForm initial={data} onSubmit={save} buttonText="Update Laporan" />
    </section>
  );
}
