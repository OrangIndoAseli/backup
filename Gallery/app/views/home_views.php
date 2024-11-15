<?php
// Mengaktifkan session
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/.././controllers/C_upload.php';
require_once __DIR__ . '/.././controllers/C_komentar.php';
require_once __DIR__ . '/../models/Foto.php';
require_once __DIR__ . '/../models/Komentar.php'; // Import the Komentar model to handle comments

// Cek apakah pengguna sudah login
if (!isset($_SESSION['UserID'])) {
    header("Location: ../../index.php"); // Arahkan kembali ke halaman login jika belum login
    exit();
}

// Cek jika ada permintaan untuk logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Hapus semua data sesi
    session_unset();
    session_destroy(); // Hancurkan sesi

    // Redirect ke halaman login
    header("Location: ../../index.php");
    exit(); // Pastikan untuk menghentikan eksekusi setelah redirect
}

// Inisialisasi database dan model Foto
$db = new Database();
$fotoModel = new Foto($db->getConnection());
$komentarModel = new Komentar($db->getConnection()); // Initialize the Komentar model

// Ambil semua foto yang diupload oleh user yang sedang login
$userID = $_SESSION['UserID']; // Ambil UserID dari session
$uploadedPhotos = $fotoModel->getAllPhotos($userID); // Ambil foto berdasarkan UserID

// Jika pengguna sudah login, tampilkan halaman home
$namaLengkap = $_SESSION['NamaLengkap'];
?>


<!-- home.php -->
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Link Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/styleNav.css"> 
</head>
<body>

    <!-- Navbar -->
    <?php include __DIR__ . '/../../assets/navbar/navbar.php'; ?>

   <!-- Konten Utama -->
<div class="home-content mt-5">
    <center><h1>Welcome, <b><?php echo $namaLengkap; ?></b></h1></center>
    <center><p>Ini adalah halaman utama MyGallery</p></center>
</div>
<div class="home-content mt-5">
    <div class="card">
        <div class="card-body">
            <div class="scrollable-row">
                <div class="row">
                    <?php if (!empty($uploadedPhotos)): ?>
                        <?php foreach ($uploadedPhotos as $foto): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <!-- Use Bootstrap classes to limit image size and make it responsive -->
                                    <img src="<?php echo htmlspecialchars($foto['LokasiFile']); ?>" 
                                         class="card-img-top img-fluid" 
                                         alt="Foto" 
                                         style="max-height: 300px; object-fit: cover;"
                                         data-toggle="modal" 
                                         data-target="#photoModal<?php echo $foto['FotoID']; ?>"> <!-- Modal Trigger -->
                                    <div class="card-body">
                                        <!-- Display photo title -->
                                        <h5 class="card-title"><?php echo htmlspecialchars($foto['JudulFoto']); ?></h5>
                                        <!-- Display description of the photo -->
                                        <p class="card-text"><?php echo htmlspecialchars($foto['DeskripsiFoto']); ?></p>

                                        <!-- Display the user who uploaded the photo and the upload date -->
                                        <p class="card-text">
                                            <small class="text-muted">Diupload oleh: <?php echo htmlspecialchars($foto['Username']); ?></small>
                                        </p>
                                        <p class="card-text">
                                            <small class="text-muted">Tanggal Unggah: <?php echo htmlspecialchars($foto['TanggalUnggah']); ?></small>
                                        </p>

                                        <!-- Add a comment section with an icon and the number of comments -->
                                        <p class="card-text">
                                            <a href="comments.php?fotoID=<?php echo $foto['FotoID']; ?>" class="text-primary">
                                                <i class="fas fa-comment-alt"></i> 
                                                <?php
                                                    // Get comments for the photo using the existing method
                                                    $comments = $komentarModel->getCommentsByPhoto($foto['FotoID']);
                                                    $commentCount = count($comments);
                                                    echo $commentCount . ' Comments';
                                                ?>
                                            </a>
                                        </p>

                                        <!-- Add like icon (optional, you can connect it to a like system later) -->
                                        <p class="card-text">
                                            <a href="#" class="text-primary">
                                                <i class="fas fa-thumbs-up"></i> Like
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal for showing original image -->
                            <div class="modal fade" id="photoModal<?php echo $foto['FotoID']; ?>" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel<?php echo $foto['FotoID']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="photoModalLabel<?php echo $foto['FotoID']; ?>"><?php echo htmlspecialchars($foto['JudulFoto']); ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="<?php echo htmlspecialchars($foto['LokasiFile']); ?>" class="img-fluid" alt="Original Foto">
                                            <p><?php echo htmlspecialchars($foto['DeskripsiFoto']); ?></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No photos available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


     <!-- Link Bootstrap JS dan jQuery untuk interaksi -->
     <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</body>
</html>


