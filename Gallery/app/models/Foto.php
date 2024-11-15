<?php
class Foto {
    private $conn;
    private $table_name = "foto";
    private $FotoID;
    private $JudulFoto;
    private $DeskripsiFoto;
    private $TanggalUnggah;
    private $LokasiFile;
    private $AlbumID;
    private $UserID;

// Konstruktor untuk menginisialisasi koneksi database
    public function __construct($db) {
    $this->conn = $db;
}

  // Fungsi untuk mengupload foto
  public function uploadFoto($judul, $deskripsi, $lokasiFile, $albumID, $userID) {
    $query = "INSERT INTO foto (JudulFoto, DeskripsiFoto, LokasiFile, AlbumID, UserID, TanggalUnggah) VALUES (:JudulFoto, :DeskripsiFoto, :LokasiFile, :AlbumID, :UserID, :TanggalUnggah)";
    $stmt = $this->conn->prepare($query);

    // Ambil tanggal unggah saat ini
    $tanggalUnggah = date('Y-m-d H:i:s');

    // Binding parameter
    $stmt->bindParam(':JudulFoto', $judul);
    $stmt->bindParam(':DeskripsiFoto', $deskripsi);
    $stmt->bindParam(':LokasiFile', $lokasiFile);
    $stmt->bindParam(':AlbumID', $albumID);
    $stmt->bindParam(':UserID', $userID);
    $stmt->bindParam(':TanggalUnggah', $tanggalUnggah);

    // Eksekusi dan kembalikan hasilnya
    return $stmt->execute();
}

// Fungsi untuk mengedit foto
public function editFoto($fotoID, $judul, $deskripsi, $albumID) {
    // Validasi AlbumID
    if (!$this->isAlbumIDValid($albumID)) {
        echo "Error: Album ID tidak valid.";
        return false;
    }

    try {
        // Update query dengan kondisi WHERE untuk FotoID
        $query = "UPDATE foto SET JudulFoto = :judul, DeskripsiFoto = :deskripsi, AlbumID = :albumID WHERE FotoID = :fotoID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":judul", $judul);
        $stmt->bindParam(":deskripsi", $deskripsi);
        $stmt->bindParam(":albumID", $albumID, PDO::PARAM_INT);
        $stmt->bindParam(":fotoID", $fotoID, PDO::PARAM_INT); // Bind FotoID
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        // Tangkap dan tampilkan pesan kesalahan
        echo "Error: " . $e->getMessage();
        return false;
    }
}

// Fungsi untuk memvalidasi AlbumID
private function isAlbumIDValid($albumID) {
    $query = "SELECT COUNT(*) FROM album WHERE AlbumID = :albumID";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":albumID", $albumID, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn() > 0; // Mengembalikan true jika AlbumID valid
}


// Fungsi untuk menghapus foto
public function deletefoto($fotoID, $userID) {
    // Query to delete only if the FotoID and UserID match the logged-in user
    $query = "DELETE FROM " . $this->table_name . " WHERE FotoID = :fotoID AND UserID = :userID";
    $stmt = $this->conn->prepare($query);
    
    if ($stmt) {
        // Bind both fotoID and userID to ensure the user owns the photo
        $stmt->bindParam(":fotoID", $fotoID, PDO::PARAM_INT);
        $stmt->bindParam(":userID", $userID, PDO::PARAM_INT);
        
        // Execute and return the result
        return $stmt->execute();
    } else {
        echo "Failed to prepare statement: " . implode(", ", $this->conn->errorInfo());
        return false;
    }
}


public function getAllPhotos() {
    $query = "SELECT f.FotoID, f.JudulFoto, f.DeskripsiFoto, f.TanggalUnggah, f.LokasiFile, 
                     a.NamaAlbum, u.Username
              FROM foto AS f
              JOIN album AS a ON f.AlbumID = a.AlbumID
              JOIN user AS u ON f.UserID = u.UserID";
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Mengambil semua foto tanpa filter user
}


public function getAllPhotosByUser($userID) {
    // Query to get all photos based on userID
    $query = "SELECT f.FotoID, f.JudulFoto, f.DeskripsiFoto, f.TanggalUnggah, f.LokasiFile, 
                     a.NamaAlbum, u.Username
              FROM foto AS f
              JOIN album AS a ON f.AlbumID = a.AlbumID
              JOIN user AS u ON f.UserID = u.UserID
              WHERE f.UserID = :userID";
    $stmt = $this->conn->prepare($query);
    // Bind parameter userID
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();
    // Fetch all photos for the given userID
    return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Retrieve all photos
}

public function getPhotosByUser($userID, $fotoID) {
    $query = "SELECT f.FotoID, f.JudulFoto, f.DeskripsiFoto, f.TanggalUnggah, f.LokasiFile, 
                     a.NamaAlbum, u.Username
              FROM foto AS f
              JOIN album AS a ON f.AlbumID = a.AlbumID
              JOIN user AS u ON f.UserID = u.UserID
              WHERE f.FotoID = :fotoID AND f.UserID = :userID";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':fotoID', $fotoID, PDO::PARAM_INT);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC); // Fetch single photo data
}



    // Fungsi untuk mengambil album berdasarkan UserID
    public function getAlbumsByUser($userID) {
        $query = "SELECT * FROM album WHERE UserID = :userID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":userID", $userID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


// Fungsi lain yang diperlukan (seperti mendapatkan foto) bisa ditambahkan di sini
}
?>