<?php
class Album {
    private $conn;
    private $table_name = "album";
    private $AlbumID;
    private $NamaAlbum;
    private $Deskripsi;
    private $TanggalDibuat;
    private $UserID;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Metode untuk mendapatkan user (hanya Username dan UserID) yang memiliki album ini
    public function getUser() {
        $query = "SELECT UserID, Username FROM user WHERE UserID = :UserID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':UserID', $this->UserID);
        $stmt->execute();
        
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user_data) {
            return [
                'UserID' => $user_data['UserID'],
                'Username' => $user_data['Username']
            ];
        }

        return null;
    }

    // Metode untuk menetapkan properti album (dengan data dalam array asosiatif)
    public function setProperties($data) {
        $this->AlbumID = $data['AlbumID'];
        $this->NamaAlbum = $data['NamaAlbum'];
        $this->Deskripsi = $data['Deskripsi'];
        $this->TanggalDibuat = $data['TanggalDibuat'];
        $this->UserID = $data['UserID'];
    }

    public function getAllAlbumsByUser($userID) {
        $query = "SELECT AlbumID, NamaAlbum, Deskripsi, TanggalDibuat FROM " . $this->table_name . " WHERE UserID = :userID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAlbumByID($albumID) {
        $query = "SELECT AlbumID, NamaAlbum, Deskripsi, TanggalDibuat FROM " . $this->table_name . " WHERE AlbumID = :albumID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':albumID', $albumID);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // Mengembalikan satu album
    }
    
    // Tambahkan album baru
    public function createAlbum($namaAlbum, $deskripsi, $userID) {
        $query = "INSERT INTO album (NamaAlbum, Deskripsi, TanggalDibuat, UserID) VALUES (:NamaAlbum, :Deskripsi, :TanggalDibuat, :UserID)";
        $stmt = $this->conn->prepare($query);

        $tanggalDibuat = date('Y-m-d H:i:s');
        
        $stmt->bindParam(':NamaAlbum', $namaAlbum);
        $stmt->bindParam(':Deskripsi', $deskripsi);
        $stmt->bindParam(':TanggalDibuat', $tanggalDibuat);
        $stmt->bindParam(':UserID', $userID);

        return $stmt->execute();
    }

      // Edit album
      public function editAlbum($albumID, $namaAlbum, $deskripsi) {
        $query = "UPDATE album SET NamaAlbum = :NamaAlbum, Deskripsi = :Deskripsi WHERE AlbumID = :AlbumID";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':NamaAlbum', $namaAlbum);
        $stmt->bindParam(':Deskripsi', $deskripsi);
        $stmt->bindParam(':AlbumID', $albumID);

        return $stmt->execute();
    }

      // Hapus album
      public function deleteAlbum($albumID) {
        $query = "DELETE FROM album WHERE AlbumID = :AlbumID";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':AlbumID', $albumID);
        
        return $stmt->execute();
    }
}
?>