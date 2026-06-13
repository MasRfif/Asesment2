import {
  LayoutDashboard,
  FileText,
  PlusCircle,
  Building2,
  LogOut,
  UserRound,
} from "lucide-react";

export default function Navbar({ page, setPage, user, onLogout }) {
  const menus = [
    { id: "dashboard", label: "Dashboard", icon: LayoutDashboard },
    { id: "laporan", label: "Data Laporan", icon: FileText },
    { id: "add", label: "Tambah Laporan", icon: PlusCircle },
  ];

  return (
    <aside className="react-sidebar">
      <div className="logo-box">
        <div>
          <b>SmartCampus</b>
          <span>Facility Report</span>
        </div>
      </div>

      <div className="user-box">
        <UserRound size={18} />
        <div>
          <b>{user?.nama || "Operator"}</b>
          <span>{user?.email}</span>
        </div>
      </div>

      <nav>
        {menus.map((item) => {
          const Icon = item.icon;
          return (
            <button
              key={item.id}
              onClick={() => setPage(item.id)}
              className={page === item.id ? "active" : ""}
            >
              <Icon size={18} /> {item.label}
            </button>
          );
        })}
        <button className="logout-btn" onClick={onLogout}>
          <LogOut size={18} /> Logout
        </button>
      </nav>

      <div className="side-note">
        React sudah memakai login, register, dan token dari API PHP.
      </div>
    </aside>
  );
}
