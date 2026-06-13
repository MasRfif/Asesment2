<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $user;

    public function __construct($conn) {
        $this->user = new User($conn);
    }

    public function loginPage() {
        $title = "Login";
        include __DIR__ . '/../views/auth/login.php';
    }

    public function registerPage() {
        $title = "Register";
        include __DIR__ . '/../views/auth/register.php';
    }

    public function login() {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
            $_SESSION['error'] = "Email atau password tidak valid.";
            header("Location: index.php?page=login");
            exit;
        }

        $user = $this->user->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = ["id"=>$user['id'], "nama"=>$user['nama'], "email"=>$user['email']];
            header("Location: index.php?page=dashboard");
            exit;
        }

        $_SESSION['error'] = "Login gagal. Periksa email dan password.";
        header("Location: index.php?page=login");
        exit;
    }

    public function register() {
        $nama = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($nama == '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
            $_SESSION['error'] = "Data belum valid. Password minimal 6 karakter.";
            header("Location: index.php?page=register");
            exit;
        }

        if ($this->user->findByEmail($email)) {
            $_SESSION['error'] = "Email sudah terdaftar.";
            header("Location: index.php?page=register");
            exit;
        }

        $this->user->create($nama, $email, $password);
        $_SESSION['success'] = "Akun berhasil dibuat. Silakan login.";
        header("Location: index.php?page=login");
        exit;
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?page=login");
        exit;
    }
}
?>
