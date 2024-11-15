<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '../../models/Foto.php'; // Menghubungkan model M_foto

// Periksa apakah user sudah login
if (!isset($_SESSION['UserID'])) {
    header("Location: ../../index.php"); // Arahkan kembali ke halaman login jika belum login
    exit();
}

// Class C_upload
class C_upload {
    private $foto;

    // Konstruktor untuk menginisialisasi objek M_foto
    public function __construct($db) {
        $this->foto = new Foto($db->getConnection());
    }

    // Menambahkan metode untuk mendapatkan album berdasarkan UserID
    public function getAlbumsByUser($userID) {
    return $this->foto->getAlbumsByUser($userID); // Memanggil method di model Foto
    }

// Menambahkan metode untuk mendapatkan foto berdasarkan FotoID dan UserID
    public function getAllPhotosByUser($fotoID, $userID) {
    return $this->foto->getAllPhotosByUser($fotoID, $userID); // Memanggil method di model Foto
    }

    public function getAllPhotos($fotoID, $userID) {
    return $this->foto->getAllPhotos($fotoID, $userID); // Memanggil method di model Foto
    }


    // Fungsi untuk mengupload foto
    public function upload($judul, $deskripsi, $albumID, $userID) {
        // Validasi input
        if (empty($judul) || empty($deskripsi) || empty($albumID)) {
            $_SESSION['message'] = "Judul, deskripsi, dan album tidak boleh kosong.";
            header("Location: ../views/upload_views.php"); // Kembali ke halaman upload
            exit();
        }

        // Menentukan direktori untuk menyimpan file
        $targetDir = "../../assets/img/";
        $fileName = basename($_FILES['file']['name']);
        $targetFilePath = $targetDir . $fileName;

        // Upload file
        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) {
            // Simpan informasi foto ke dalam database
            if ($this->foto->uploadFoto($judul, $deskripsi, $targetFilePath, $albumID, $userID)) {
                header("Location: ../views/upload_views.php?message=" . urlencode("Foto berhasil diupload!"));
            } else {
                header("Location: ../views/upload_views.php?message=" . urlencode("Gagal mengupload foto."));
            }
        } else {
            header("Location: ../views/upload_views.php?message=" . urlencode("Gagal memindahkan file."));
        }
    }

    // Fungsi untuk mengedit foto
    public function edit() {
        // Memeriksa apakah permintaan adalah POST dan action adalah 'edit'
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
            // Mengambil data dari form
            $fotoID = $_POST['fotoID'] ?? '';
            $judul = $_POST['judul'] ?? '';
            $deskripsi = $_POST['deskripsi'] ?? '';
            $albumID = $_POST['albumID'] ?? ''; // Pastikan ini sesuai dengan name attribute pada form album
    
            // Validasi input, pastikan semua field terisi
            if (empty($fotoID) || empty($judul) || empty($deskripsi) || empty($albumID)) {
                // Mengarahkan ke halaman upload dengan pesan error jika ada field yang kosong
                header("Location: ../views/upload_views.php?message=" . urlencode("Semua field harus diisi."));
                exit();
            }
    
            // Memanggil metode editFoto untuk mengupdate foto
            if ($this->foto->editFoto($fotoID, $judul, $deskripsi, $albumID)) {
                // Jika berhasil, mengarahkan kembali dengan pesan sukses
                header("Location: ../views/upload_views.php?message=" . urlencode("Caption berhasil diubah!"));
                exit();
            } else {
                // Jika gagal, mengarahkan kembali dengan pesan gagal
                header("Location: ../views/upload_views.php?message=" . urlencode("Gagal mengubah Caption"));
                exit();
            }
        }
    }
    
    

    // Fungsi untuk menghapus foto
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
            session_start(); // Ensure session is started to access $_SESSION variables
            
            // Check if user is logged in and has a valid UserID
            if (isset($_SESSION['UserID'])) {
                $userID = $_SESSION['UserID']; // Retrieve the logged-in user’s UserID
                $fotoID = $_POST['fotoID'] ?? ''; // Retrieve fotoID from the form
                
                // Call deleteFoto with both fotoID and userID to validate ownership
                if ($this->foto->deleteFoto($fotoID, $userID)) {
                    header("Location: ../views/upload_views.php?message=" . urlencode("Foto berhasil dihapus!"));
                } else {
                    header("Location: ../views/upload_views.php?message=" . urlencode("Gagal menghapus foto. Anda tidak memiliki izin."));
                }
            } else {
                header("Location: ../../index.php"); // Redirect to login page if not logged in
            }
        }
    }
}

// Inisialisasi Database dan C_upload
$db = new Database();  // Pastikan class Database sudah ada
$uploadController = new C_upload($db);

// Ambil aksi dari parameter POST
$action = $_POST['action'] ?? '';
$response = '';

// Eksekusi fungsi berdasarkan aksi yang dipilih
if ($action === 'upload') {
    $judul = $_POST['judul'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $albumID = $_POST['album'] ?? '';
    $userID = $_SESSION['UserID']; // Ambil UserID dari session
    $uploadController->upload($judul, $deskripsi, $albumID, $userID);
} elseif ($action === 'edit') {
    $fotoID = $_POST['fotoID'] ?? '';
    $judul = $_POST['judul'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $albumID = $_POST['album'] ?? '';
    $uploadController->edit($fotoID, $judul, $deskripsi, $albumID);
} elseif ($action === 'delete') {
    $fotoID = $_POST['fotoID'] ?? '';
    $uploadController->delete($fotoID);
}

?>