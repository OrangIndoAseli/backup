<?php
session_start();
include_once './config/database.php'; // Pastikan path ini benar
include_once './app/models/User.php'; // Pastikan path ini benar

class C_login {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function loginUser($username, $password) {
        // Atur username untuk objek user
        $this->user->Username = $username;
    
        // Cek apakah username tersedia
        if (!$this->user->checkUsernameExists()) {
            return "Username atau Password Salah"; // Jika username tidak ada
        }
    
        // Verifikasi login
        if ($this->user->login($password)) {
            // Set session variables
            $_SESSION['UserID'] = $this->user->UserID; // Menyimpan UserID di session
            $_SESSION['NamaLengkap'] = $this->user->NamaLengkap; // Menyimpan Nama Lengkap
    
            // Redirect ke halaman home
            header("Location: app/views/home_views.php");
            exit(); // Pastikan untuk menghentikan eksekusi script setelah redirect
        } else {
            return "Username atau Password salah."; // Jika password salah
        }
    }
    
}
