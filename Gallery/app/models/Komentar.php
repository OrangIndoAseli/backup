<?php
class Komentar {
    private $conn;
    private $table_name = "komentarfoto";

    private $KomentarID;

    private $FotoID;

    private $IsiKomentar;

    private $TanggalKomentar;


public function __construct($db) {
    $this->conn = $db;
}

public function getCommentsByPhoto($fotoID) {
    // Prepare SQL query to select all comments for the specified photo
    $query = "SELECT KomentarID, FotoID, IsiKomentar, TanggalKomentar 
              FROM komentarfoto 
              WHERE FotoID = :fotoID 
              ORDER BY TanggalKomentar DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':fotoID', $fotoID, PDO::PARAM_INT);
    $stmt->execute();
    
    // Fetch all comments for the specified photo
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function createKomen($fotoID, $isiKomentar) {
    // Set TanggalKomentar to the current date and time
    $tanggalKomentar = date('Y-m-d H:i:s');

    // Prepare the SQL query to insert a new comment
    $query = "INSERT INTO komentarfoto (FotoID, IsiKomentar, TanggalKomentar)
              VALUES (:fotoID, :isiKomentar, :tanggalKomentar)";
    $stmt = $this->conn->prepare($query);

    // Bind parameters
    $stmt->bindParam(':fotoID', $fotoID, PDO::PARAM_INT);
    $stmt->bindParam(':isiKomentar', $isiKomentar, PDO::PARAM_STR);
    $stmt->bindParam(':tanggalKomentar', $tanggalKomentar, PDO::PARAM_STR);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        // Return the ID of the newly created comment
        return $this->conn->lastInsertId();
    } else {
        // Handle error (optional: log it or throw an exception)
        return false;
    }
}

public function editKomen($komentarID, $isiKomentar) {
    // Prepare SQL query to update the comment's content
    $query = "UPDATE komentarfoto 
              SET IsiKomentar = :isiKomentar 
              WHERE KomentarID = :komentarID";
    $stmt = $this->conn->prepare($query);
    
    // Bind parameters
    $stmt->bindParam(':isiKomentar', $isiKomentar, PDO::PARAM_STR);
    $stmt->bindParam(':komentarID', $komentarID, PDO::PARAM_INT);
    
    // Execute the query and return true if successful
    return $stmt->execute();
}

public function deleteKomen($komentarID) {
    // Prepare SQL query to delete the comment
    $query = "DELETE FROM komentarfoto 
              WHERE KomentarID = :komentarID";
    $stmt = $this->conn->prepare($query);
    
    // Bind the KomentarID parameter
    $stmt->bindParam(':komentarID', $komentarID, PDO::PARAM_INT);
    
    // Execute the query and return true if successful
    return $stmt->execute();
}


}