<?php
session_start();
include 'koneksi.php';

$error = "";

if (isset($_POST['login'])) {
    $nomor_kamar = mysqli_real_escape_string($koneksi, $_POST['nomor_kamar']);
    
    // Cek apakah nomor kamar terdaftar di tabel penghuni_kos
    $query = mysqli_query($koneksi, "SELECT * FROM penghuni_kos WHERE nomor_kamar = '$nomor_kamar'");
    
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        // Simpan sesi login khusus penghuni
        $_SESSION['penghuni'] = $data['nama_penghuni'];
        $_SESSION['nomor_kamar'] = $data['nomor_kamar'];
        
        header("Location: dashboard_penghuni.php");
        exit;
    } else {
        $error = "Nomor kamar tidak terdaftar!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Penghuni Kos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-sm">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Login Penghuni Kos</h2>
        
        <?php if (!empty($error)) : ?>
            <div class="bg-red-100 text-red-600 p-3 rounded-lg text-sm mb-4 text-center"><?= $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Nomor Kamar / Lokasi</label>
                <input type="text" name="nomor_kamar" placeholder="Contoh: 2" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" name="login" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition duration-200">
                Masuk Kamar
            </button>
        </form>
    </div>
</body>
</html>