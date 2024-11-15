<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Album</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../assets/styleHome.css">
</head>
<body>
    <?php include '../../../assets/navbar/navbar.php'; ?>

    <div class="container mt-5">
        <h2>Tambah Album Baru</h2>

        <?php if (isset($response)): ?>
            <div class="alert alert-info"><?= htmlspecialchars($response); ?></div>
        <?php endif; ?>

        <form action="../../controllers/C_album.php" method="POST">
            <input type="hidden" name="action" value="create">
            <div class="form-group">
                <label for="namaAlbum">Nama Album:</label>
                <input type="text" id="namaAlbum" name="namaAlbum" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Buat Album</button>
            <a href="../albumKU_views.PHP" class="btn btn-secondary">Kembali ke Daftar Album</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
