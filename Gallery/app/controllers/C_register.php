<?php

include_once '../../config/database.php';
include_once '../models/User.php';

class C_register {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function registerUser($username, $password, $email, $namaLengkap, $alamat) {
        // Set data user pada properti model User
        $this->user->Username = $username;
        $this->user->Password = $password; // Tidak perlu hashing di sini
        $this->user->Email = $email;
        $this->user->NamaLengkap = $namaLengkap;
        $this->user->Alamat = $alamat;

        // Coba registrasi dan cek hasil
        if ($this->user->register()) {
            return "Registrasi berhasil!";
        } else {
            return "Registrasi gagal.";
        }
    }
}
