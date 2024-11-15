<?php
require_once '../../controllers/C_album.php'; // Pastikan jalur ini sesuai

// session_start();
$db = new Database();
$albumController = new C_album($db);

// Ambil ID album dari URL atau request
$albumID = $_GET['albumID'] ?? null;

if ($albumID) {
    // Ambil data album dari controller menggunakan metode getAlbumByID
    $album = $albumController->getAlbumByID($albumID);
}


if (!$album) {
    // Jika album tidak ditemukan, arahkan kembali ke halaman daftar album
    header("Location: ../albumKU_views.php?message=" . urlencode("Album tidak ditemukan."));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Album</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../assets/styleHome.css">
</head>
<body>
    
    <div class="container mt-5">
        <h2>Edit Album</h2>

        <!-- Display success message -->
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <form action="../../controllers/C_album.php" method="POST">
        <!-- Hidden inputs to specify action and albumID -->
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="albumID" value="<?php echo htmlspecialchars($albumID); ?>">

        <div class="form-group">
            <label for="namaAlbum">Nama Album:</label>
            <input type="text" name="namaAlbum" id="namaAlbum" class="form-control" value="<?php echo htmlspecialchars($album['NamaAlbum']); ?>" required>
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi:</label>
            <textarea name="deskripsi" id="Deskripsi" class="form-control" rows="4" required><?php echo htmlspecialchars($album['Deskripsi']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="../albumKU_views.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
