<?php
require_once '../../config/database.php';
require_once '../models/Komentar.php'; // Assuming your model is named Komentar

class C_komentar {
    private $db;
    private $komentar;

    // Constructor
    public function __construct($db) {
        $this->db = $db;
        $this->komentar = new Komentar($this->db->getConnection()); // Initialize the Komentar model
    }

    // Function to create a comment
    public function createComment($fotoID, $isiKomentar) {
        // Validate input
        if (empty($fotoID) || empty($isiKomentar)) {
            return "FotoID and comment content are required.";
        }

        // Call the createKomen method from the model
        $result = $this->komentar->createKomen($fotoID, $isiKomentar);

        if ($result) {
            return "Comment created successfully!";
        } else {
            return "Failed to create comment.";
        }
    }

    // Function to get comments for a specific photo
    public function getCommentsByPhoto($fotoID) {
        // Validate input
        if (empty($fotoID)) {
            return "FotoID is required.";
        }

        // Call the getCommentsByPhoto method from the model
        $comments = $this->komentar->getCommentsByPhoto($fotoID);

        if ($comments) {
            return $comments;
        } else {
            return "No comments found for this photo.";
        }
    }

    // Function to edit a comment
    public function editComment($komentarID, $isiKomentar) {
        // Validate input
        if (empty($komentarID) || empty($isiKomentar)) {
            return "Comment ID and new content are required.";
        }

        // Call the editKomen method from the model
        $result = $this->komentar->editKomen($komentarID, $isiKomentar);

        if ($result) {
            return "Comment updated successfully!";
        } else {
            return "Failed to update comment.";
        }
    }

    // Function to delete a comment
    public function deleteComment($komentarID) {
        // Validate input
        if (empty($komentarID)) {
            return "Comment ID is required.";
        }

        // Call the deleteKomen method from the model
        $result = $this->komentar->deleteKomen($komentarID);

        if ($result) {
            return "Comment deleted successfully!";
        } else {
            return "Failed to delete comment.";
        }
    }
}

// Action Handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    session_start(); // Start session if not already started

    // Initialize database and comment controller
    $db = new Database();
    $komentarController = new C_komentar($db);

    $action = $_POST['action'];

    // Handle different actions using if-else
    if ($action === 'create') {
        $fotoID = $_POST['fotoID'] ?? '';
        $isiKomentar = $_POST['isiKomentar'] ?? '';
        $message = $komentarController->createComment($fotoID, $isiKomentar);
    } elseif ($action === 'edit') {
        $komentarID = $_POST['komentarID'] ?? '';
        $isiKomentar = $_POST['isiKomentar'] ?? '';
        $message = $komentarController->editComment($komentarID, $isiKomentar);
    } elseif ($action === 'delete') {
        $komentarID = $_POST['komentarID'] ?? '';
        $message = $komentarController->deleteComment($komentarID);
    } elseif ($action === 'get') {
        $fotoID = $_POST['fotoID'] ?? '';
        $comments = $komentarController->getCommentsByPhoto($fotoID);
        $message = is_array($comments) ? $comments : $comments;
    } else {
        $message = "Invalid action.";
    }

    // Redirect with a message (this could be adjusted based on your structure)
    header("Location: ../photo_view.php?fotoID=" . $_POST['fotoID'] . "&message=" . urlencode($message));
    exit();
}
?>
