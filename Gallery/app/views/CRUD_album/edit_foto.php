<?php
require_once '../../controllers/C_upload.php';
require_once '../../models/Foto.php';


// Pastikan koneksi database dan inisialisasi kelas
$db = new Database();
$uploadController = new C_upload($db);
$fotoModel = new Foto($db->getConnection());

// Cek apakah `fotoID` ada di URL dan `userID` tersimpan di sesi
// session_start();
$fotoID = $_GET['fotoID'] ?? null;
$userID = $_SESSION['UserID'] ?? null;

if ($fotoID && $userID) {
    // Ambil detail foto menggunakan model Foto
    $foto = $fotoModel->getPhotosByUser($userID,$fotoID);

    // Ambil daftar album pengguna jika foto ditemukan
    $albums = $uploadController->getAlbumsByUser($userID);

    // Cek apakah foto ditemukan dan milik pengguna yang benar
    if (!$foto) {
        header("Location: ../upload_views.php?message=" . urlencode("Foto tidak ditemukan atau Anda tidak memiliki akses."));
        exit();
    }
} else {
    // Redirect jika tidak ada fotoID atau userID
    header("Location: ../upload_views.php?message=" . urlencode("Foto tidak ditemukan."));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Foto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container mt-4">
    <h2>Edit Foto</h2>
    <!-- Form for editing a single photo -->
    <?php if (isset($foto) && $foto): ?>
        <form action="../../controllers/C_upload.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="fotoID" value="<?= htmlspecialchars($foto['FotoID']) ?>">

            <div class="form-group">
                <label for="judul">Judul Foto</label>
                <input type="text" class="form-control" id="judul" name="judul" value="<?= htmlspecialchars($foto['JudulFoto']) ?>" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi Foto</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required><?= htmlspecialchars($foto['DeskripsiFoto']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="album">Pilih Album</label>
                <select class="form-control" id="album" name="albumID" required>
                    <option value="">Pilih Album</option>
                    <!-- Loop through albums from the database -->
                    <?php foreach ($albums as $album): ?>
                        <option value="<?= htmlspecialchars($album['AlbumID']) ?>" <?= isset($foto['AlbumID']) && $album['AlbumID'] == $foto['AlbumID'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($album['NamaAlbum']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="../upload_views.php" class="btn btn-secondary">Batal</a>
        </form>
    <?php else: ?>
        <div class="alert alert-warning">Foto tidak ditemukan atau Anda tidak memiliki akses.</div>
        <a href="../upload_views.php" class="btn btn-secondary">Kembali</a>
    <?php endif; ?>
</div>

    <script>
        // Validasi input untuk form edit foto
        $(document).ready(function() {
            $('form').on('submit', function(event) {
                const judul = $('#judul').val().trim();
                const deskripsi = $('#deskripsi').val().trim();

                if (judul === '' || deskripsi === '') {
                    alert('Judul dan Deskripsi Foto harus diisi.');
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>