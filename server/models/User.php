<?php
class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function findByEmail($email) {
        $stmt = mysqli_prepare($this->conn, "SELECT * FROM users WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    }

    public function findByToken($token) {
        $stmt = mysqli_prepare($this->conn, "SELECT id, nama, email, api_token FROM users WHERE api_token = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $token);
        mysqli_stmt_execute($stmt);
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    }

    public function create($nama, $email, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($this->conn, "INSERT INTO users (nama, email, password) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $nama, $email, $hash);
        return mysqli_stmt_execute($stmt);
    }

    public function createForApi($nama, $email, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(32));
        $stmt = mysqli_prepare($this->conn, "INSERT INTO users (nama, email, password, api_token) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $nama, $email, $hash, $token);
        $ok = mysqli_stmt_execute($stmt);
        if (!$ok) return false;
        return [
            "id" => mysqli_insert_id($this->conn),
            "nama" => $nama,
            "email" => $email,
            "api_token" => $token
        ];
    }

    public function refreshToken($id) {
        $token = bin2hex(random_bytes(32));
        $stmt = mysqli_prepare($this->conn, "UPDATE users SET api_token = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $token, $id);
        if (!mysqli_stmt_execute($stmt)) return false;
        return $token;
    }
}
?>
