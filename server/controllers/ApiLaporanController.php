<?php
require_once __DIR__ . '/../models/Laporan.php';
require_once __DIR__ . '/../models/User.php';

class ApiLaporanController {
    private $laporan;
    private $user;

    public function __construct($conn) {
        $this->laporan = new Laporan($conn);
        $this->user = new User($conn);
    }

    private function json($status, $message, $data = null, $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode(["status"=>$status, "message"=>$message, "data"=>$data]);
        exit;
    }

    private function inputData() {
        $raw = file_get_contents("php://input");
        $json = json_decode($raw, true);
        if (is_array($json)) return $json;
        parse_str($raw, $data);
        return $data ?: $_POST;
    }

    private function getToken() {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
        if (stripos($header, 'Bearer ') === 0) return trim(substr($header, 7));
        return $_GET['token'] ?? '';
    }

    private function authUser() {
        $token = $this->getToken();
        if ($token == '') $this->json('error', 'Token belum dikirim. Silakan login dulu.', null, 401);
        $user = $this->user->findByToken($token);
        if (!$user) $this->json('error', 'Token tidak valid. Silakan login ulang.', null, 401);
        return $user;
    }

    private function uploadApi() {
        if (!isset($_FILES['foto_bukti']) || $_FILES['foto_bukti']['error'] == 4) return null;
        $file = $_FILES['foto_bukti'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png'])) return false;
        $nama = 'api_' . date('YmdHis') . '_' . rand(100,999) . '.' . $ext;
        $tujuan = __DIR__ . '/../uploads/' . $nama;
        return move_uploaded_file($file['tmp_name'], $tujuan) ? $nama : false;
    }

    public function handle() {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') exit;

        $path = $_GET['path'] ?? '';
        $method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
        $method = strtoupper($method);

        if ($path == 'register' && $method == 'POST') {
            $data = $this->inputData();
            $nama = trim($data['nama'] ?? '');
            $email = trim($data['email'] ?? '');
            $password = $data['password'] ?? '';

            if ($nama == '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
                $this->json('error', 'Nama, email, atau password belum valid. Password minimal 6 karakter.', null, 422);
            }
            if ($this->user->findByEmail($email)) {
                $this->json('error', 'Email sudah terdaftar.', null, 409);
            }
            $user = $this->user->createForApi($nama, $email, $password);
            if ($user) $this->json('success', 'Register berhasil.', $user, 201);
            $this->json('error', 'Register gagal.', null, 500);
        }

        if ($path == 'login' && $method == 'POST') {
            $data = $this->inputData();
            $email = trim($data['email'] ?? '');
            $password = $data['password'] ?? '';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
                $this->json('error', 'Email atau password belum valid.', null, 422);
            }
            $user = $this->user->findByEmail($email);
            if (!$user || !password_verify($password, $user['password'])) {
                $this->json('error', 'Login gagal. Email atau password salah.', null, 401);
            }
            $token = $this->user->refreshToken((int)$user['id']);
            $this->json('success', 'Login berhasil.', [
                'id' => (int)$user['id'],
                'nama' => $user['nama'],
                'email' => $user['email'],
                'api_token' => $token
            ]);
        }

        if ($path == 'me' && $method == 'GET') {
            $user = $this->authUser();
            $this->json('success', 'Data user berhasil diambil.', $user);
        }

        if ($path != 'laporan') $this->json('error', 'Endpoint tidak ditemukan', null, 404);

        $loginUser = $this->authUser();
        $userId = (int)$loginUser['id'];
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($method == 'GET') {
            if ($id > 0) {
                $data = $this->laporan->findByUser($id, $userId);
                $data ? $this->json('success', 'Detail laporan berhasil diambil', $data) : $this->json('error', 'Data tidak ditemukan', null, 404);
            }
            $this->json('success', 'Data laporan berhasil diambil', $this->laporan->allByUser($userId));
        }

        if ($method == 'POST') {
            $data = $_POST;
            $foto = $this->uploadApi();
            if ($foto === false) $this->json('error', 'File foto harus jpg, jpeg, atau png', null, 422);
            $data['foto_bukti'] = $foto ?: 'tanpa_foto.jpg';
            $data['user_id'] = $userId;
            if ($this->laporan->apiCreate($data)) $this->json('success', 'Laporan berhasil ditambahkan', null, 201);
            $this->json('error', 'Laporan gagal ditambahkan', null, 500);
        }

        if ($method == 'PUT' || $method == 'PATCH') {
            if ($id < 1) $this->json('error', 'ID laporan belum ada', null, 422);
            $data = $this->inputData();
            $data['user_id'] = $userId;
            $foto = $this->uploadApi();
            if ($foto === false) $this->json('error', 'File foto harus jpg, jpeg, atau png', null, 422);
            if ($foto) $data['foto_bukti'] = $foto;
            if ($this->laporan->apiUpdateByUser($id, $userId, $data)) $this->json('success', 'Laporan berhasil diubah');
            $this->json('error', 'Laporan gagal diubah atau data bukan milik akun ini', null, 404);
        }

        if ($method == 'DELETE') {
            if ($id < 1) $this->json('error', 'ID laporan belum ada', null, 422);
            if ($this->laporan->delete($id, $userId)) $this->json('success', 'Laporan berhasil dihapus');
            $this->json('error', 'Laporan gagal dihapus atau data bukan milik akun ini', null, 404);
        }

        $this->json('error', 'Method tidak sesuai', null, 405);
    }
}
?>
