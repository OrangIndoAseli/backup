<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '../../models/Album.php'; // Menghubungkan model Album

// Periksa apakah user sudah login
if (!isset($_SESSION['UserID'])) {
    header("Location: ../../index.php"); // Arahkan kembali ke halaman login jika belum login
    exit();
}

// Class AlbumController
class C_album {
    private $album;

    // Konstruktor untuk menginisialisasi objek Album
    public function __construct($db) {
        $this->album = new Album($db->getConnection());
    }

    public function getAllByUser($userID) {
        return $this->album->getAllAlbumsByUser($userID);
    }

    // Fungsi untuk mendapatkan album berdasarkan AlbumID
    public function getAlbumByID($albumID) {
        return $this->album->getAlbumByID($albumID);
    }   

    // Fungsi untuk membuat album baru
    public function create($namaAlbum, $deskripsi, $userID) {
            if (empty($namaAlbum)) {
                // Simpan pesan kesalahan dalam session dan redirect
                $_SESSION['message'] = "Nama album tidak boleh kosong.";
                header("Location: ../views/albumKU_views.php"); // Redirect ke halaman album
                exit; // Hentikan eksekusi script
            }
        
            // Coba buat album
            if ($this->album->createAlbum($namaAlbum, $deskripsi, $userID)) {
                // Redirect with success message
                header("Location: ../views/albumKU_views.php?message=" . urlencode("Album berhasil dibuat!"));
                exit;  // Stop further execution
            } else {
                // Redirect with error message
                header("Location: ../views/albumKU_views.php?message=" . urlencode("Gagal membuat album."));
                exit;  // Stop further execution
            }
        }

    // Fungsi untuk mengedit album
    public function edit($albumID, $namaAlbum, $deskripsi) {
        // Check for empty Album ID and Name
        if (empty($albumID) || empty($namaAlbum)) {
            return "Album ID dan Nama Album harus diisi.";
        }
    
        // Call the model's edit method to update the album
        if ($this->album->editAlbum($albumID, $namaAlbum, $deskripsi)) {
            // If successful, redirect with a success message
            header("Location: ../views/albumKU_views.php?message=" . urlencode("Album berhasil diubah!"));
            exit; // Stop further execution after redirect
        } else {
            return "Gagal mengubah album.";
        }
    }

    // Fungsi untuk menghapus album
    public function delete($albumID) {
        if (empty($albumID)) {
            return "Album ID harus diisi.";
        }

        if ($this->album->deleteAlbum($albumID)) {
            header("Location: ../views/albumKU_views.php?message=" . urlencode("Album berhasil dihapus!"));
            exit;  // Stop further execution
        } else {
            // Redirect with error message
            header("Location: ../views/albumKU_views.php?message=" . urlencode("Gagal hapus album."));
            exit;  // Stop further execution
        }
    }
}

// Inisialisasi Database dan AlbumController
$db = new Database();  // Pastikan class Database sudah ada
$albumController = new C_album($db);

// Ambil aksi dari parameter POST
$action = $_POST['action'] ?? '';
$response = '';

// Eksekusi fungsi berdasarkan aksi yang dipilih
if ($action === 'create') {
    $namaAlbum = $_POST['namaAlbum'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $userID = $_SESSION['UserID'];
    $response = $albumController->create($namaAlbum, $deskripsi, $userID);
} elseif ($action === 'edit') {
    $albumID = $_POST['albumID'] ?? '';
    $namaAlbum = $_POST['namaAlbum'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $response = $albumController->edit($albumID, $namaAlbum, $deskripsi);
} elseif ($action === 'delete') {
    $albumID = $_POST['albumID'] ?? '';
    $response = $albumController->delete($albumID);
}

// Ambil semua album berdasarkan UserID
$userID = $_SESSION['UserID']; // Ambil UserID dari session
$albums = $albumController->getAllByUser($userID); // Ambil album berdasarkan UserID

// Tampilkan respons
echo $response;

?>
