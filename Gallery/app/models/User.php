<?php

class User {
    private $conn;
    private $table_name = "user";
    public $UserID;
    public $Username;
    public $Password;
    public $Email;
    public $NamaLengkap;
    public $Alamat;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fungsi untuk register
    public function register() {
        $query = "INSERT INTO " . $this->table_name . " (Username, Password, Email, NamaLengkap, Alamat) 
                  VALUES (:Username, :Password, :Email, :NamaLengkap, :Alamat)";

        $stmt = $this->conn->prepare($query);

        // Hash password sebelum bind
        $hashedPassword = password_hash($this->Password, PASSWORD_DEFAULT);

        // Bind parameter
        $stmt->bindParam(":Username", $this->Username);
        $stmt->bindParam(":Password", $hashedPassword); // Gunakan password yang sudah di-hash
        $stmt->bindParam(":Email", $this->Email);
        $stmt->bindParam(":NamaLengkap", $this->NamaLengkap);
        $stmt->bindParam(":Alamat", $this->Alamat);

        // Eksekusi query dan kembalikan hasilnya (true jika sukses, false jika gagal)
        return $stmt->execute();
    }


    public function checkUsernameExists() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE Username = :Username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':Username', $this->Username);
        $stmt->execute();

        return $stmt->rowCount() > 0; // Kembalikan true jika username ada, false jika tidak
    }

    // Fungsi untuk login
    public function login($password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE Username = :Username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":Username", $this->Username);
        $stmt->execute();
    
        // Jika baris ditemukan
        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Verifikasi password
            if (password_verify($password, $user['Password'])) {
                // Simpan data pengguna
                $this->UserID = $user['UserID'];
                $this->NamaLengkap = $user['NamaLengkap'];
                return true; // Login berhasil
            } else {
                return false; // Password salah
            }
        }
    
        return false; // Username tidak ditemukan
    }


    // Metode untuk mendapatkan album yang dimiliki oleh user ini
    public function getAlbums() {
        $query = "SELECT * FROM album WHERE UserID = :UserID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':UserID', $this->UserID);
        $stmt->execute();
        
        $albums = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $album = new Album($this->conn);
            $album->setProperties($row);
            $albums[] = $album;
        }

        return $albums;
    }
    public function setProperties($data) {
        $this->UserID = $data['UserID'];
        $this->Username = $data['Username'];
        // Tetapkan properti lainnya sesuai kebutuhan
    }
}
?>
