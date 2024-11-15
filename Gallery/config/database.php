<?php

class database {
    private $host = "localhost"; // Nama host, biasanya localhost
    private $db_name = "gallery"; // Nama database, sesuai dengan yang Anda gunakan
    private $username = "root"; // Nama pengguna database
    private $password = ""; // Password pengguna database
    public $conn;

    // Fungsi untuk membuat koneksi ke database
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "";
        } catch (PDOException $exception) {
            echo "Koneksi gagal: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
