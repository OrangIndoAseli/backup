<?php 
// Mengaktifkan session
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['UserID'])) {
    header("Location: ../../index.php"); // Arahkan kembali ke halaman login jika belum login
    exit();
}

// Cek apakah pengguna ingin logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Menghandle logout
    session_unset(); // Hapus semua sesi
    session_destroy(); // Hancurkan sesi
    header("Location: ../../index.php"); // Redirect ke halaman login
    exit();
}

// Jika pengguna sudah login, tampilkan halaman home
$namaLengkap = $_SESSION['NamaLengkap'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Link Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/styleAbout.css">
    <link rel="stylesheet" href="../../assets/styleNav.css">
</head>
<body>

    <!-- Navbar -->
    <?php include __DIR__ . '/../../assets/navbar/navbar.php'; ?>

    <div class="container-about">
        <h1>Tentang Kami</h1>
        <p><b>Selamat datang di Galeri Kami! Kami adalah platform yang didedikasikan untuk menampilkan karya seni dan fotografi dari berbagai seniman berbakat. Misi kami adalah untuk memberikan ruang bagi seniman untuk menampilkan karya mereka dan bagi pengunjung untuk menemukan keindahan dalam setiap karya seni.</b></p>

        <h2>Misi Kami</h2>
        <p>Kami berkomitmen untuk:</p>
        <ul>
            <li>Menjadi platform yang inklusif bagi semua seniman.</li>
            <li>Mendukung seniman lokal dan mempromosikan karya mereka.</li>
            <li>Menyediakan pengalaman yang menyenangkan dan mendidik bagi pengunjung galeri kami.</li>
        </ul>

        <h2>Visi Kami</h2>
        <p>Visi kami adalah untuk menjadi salah satu galeri seni terkemuka yang dikenal karena kualitas karya yang ditampilkan dan dukungan kami terhadap seniman.</p>

        <h2>Kontak Kami</h2>
        <p>Jika Anda memiliki pertanyaan atau ingin berkolaborasi, silakan hubungi kami di <a href="mailto:info@kelompok 5.com">info@kelompok 5</a>.</p>
        
        <a href="home_views.php" class="btn btn-primary">Kembali ke Home</a> <!-- Tombol untuk kembali -->
    </div>
</body>
</html>
