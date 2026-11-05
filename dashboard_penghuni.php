<?php
session_start();
if (!isset($_SESSION['penghuni'])) {
    header("Location: login_penghuni.php");
    exit;
}

$nama_penghuni = $_SESSION['penghuni'];
$nomor_kamar = $_SESSION['nomor_kamar'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Denah & Monitoring - Kamar <?= $nomor_kamar; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">

    <!-- SIDEBAR / DRAWER -->
    <aside class="w-64 bg-white border-r shadow-sm hidden md:flex flex-col justify-between p-6">
        <div>
            <!-- Logo / Judul Aplikasi -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-blue-600"> Kos Monitoring</h2>
                <p class="text-xs text-gray-500 mt-1">Sistem Keamanan Pintar</p>
            </div>

            <!-- Menu Navigasi -->
            <nav class="space-y-2">
                <a href="dashboard_penghuni.php" class="flex items-center gap-3 px-4 py-2.5 bg-blue-50 text-blue-600 rounded-xl font-semibold transition">
                    Home
                </a>
                <a href="#denah-section" class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-50 rounded-xl font-medium transition">
                     Denah Ruangan
                </a>
            </nav>
        </div>

        <!-- Tombol Logout di Bawah Drawer -->
        <div>
            <a href="logout_penghuni.php" class="flex items-center justify-center gap-2 w-full bg-red-50 hover:bg-red-100 text-red-600 font-semibold py-2.5 rounded-xl transition">
                 Logout
            </a>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 p-4 md:p-8 overflow-y-auto">
        
        <!-- Header Atas (Mobile & Desktop) -->
        <header class="mb-6 flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Halo, <?= $nama_penghuni; ?></h1>
                <p class="text-sm text-gray-600">Kamar Anda: <b class="text-blue-600">Lokasi / Kamar <?= $nomor_kamar; ?></b></p>
            </div>
            <!-- Tombol Logout versi Mobile -->
            <a href="logout_penghuni.php" class="md:hidden bg-red-500 hover:bg-red-600 text-white font-semibold px-3 py-2 rounded-xl text-sm transition">
                Logout
            </a>
        </header>

        <!-- Status Sensor Kamar Sendiri -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border-t-4 border-blue-500">
                <span class="text-sm font-semibold text-gray-400 block">Status Flame Sensor</span>
                <div id="flame-status" class="text-xl font-bold mt-2 text-gray-400">Memuat...</div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border-t-4 border-blue-500">
                <span class="text-sm font-semibold text-gray-400 block">Sensor Gas & Asap</span>
                <div id="gas-status" class="text-2xl font-bold mt-1 text-gray-800">0 <span class="text-sm">PPM</span></div>
            </div>
        </div>

        <!-- BAGIAN DENAH RUANGAN -->
        <div id="denah-section" class="bg-white p-6 rounded-2xl shadow-sm">
            <h2 class="text-lg font-bold text-gray-800 mb-4">  Lokasi Kamar Kos</h2>
            <p class="text-sm text-gray-500 mb-6">Berikut adalah denah posisi ruangan Anda. Status sensor tiap kamar akan ditandai secara visual di sini.</p>

            <!-- Area Denah (Bisa Anda ganti dengan gambar denah / layout grid ruangan) -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <!-- Contoh Kotak Denah Kamar 1 -->
                <div class="border-2 border-dashed border-gray-300 p-4 rounded-xl text-center hover:border-blue-500 transition">
                    <span class="font-bold text-gray-700 block">Kamar / Lokasi 1</span>
                    <span class="text-xs text-gray-400">Status: Aman</span>
                </div>
                <!-- Contoh Kotak Denah Kamar 2 (Kamar Anda) -->
                <div class="border-2 border-solid border-blue-500 bg-blue-50 p-4 rounded-xl text-center shadow-inner">
                    <span class="font-bold text-blue-700 block">Kamar 2 (Anda)</span>
                    <span id="denah-status-kamar2" class="text-xs font-semibold text-green-600">🟢 Terpantau Aman</span>
                </div>
                <!-- Tambahkan ruangan lain jika ada -->
                <div class="border-2 border-dashed border-gray-300 p-4 rounded-xl text-center hover:border-blue-500 transition">
                    <span class="font-bold text-gray-700 block">Lokasi 3</span>
                    <span class="text-xs text-gray-400">Status: Aman</span>
                </div>
            </div>
        </div>

    </main>

    <!-- Script JavaScript Real-time -->
    <script>
        const nomorKamar = "<?= $nomor_kamar; ?>";

        function loadKamarData() {
            fetch('get_data.php')
                .then(response => response.json())
                .then(data => {
                    let targetData = null;
                    if (data.lokasi1 && data.lokasi1.lokasi == nomorKamar) targetData = data.lokasi1;
                    if (data.lokasi2 && data.lokasi2.lokasi == nomorKamar) targetData = data.lokasi2;

                    if (targetData) {
                        const flame = document.getElementById('flame-status');
                        const statusDenah = document.getElementById('denah-status-kamar2');

                        if (targetData.flame_status == 1 || targetData.nilai_gas_asap > 300) {
                            flame.textContent = '🔥 BAHAYA (ADA API/GAS)!';
                            flame.className = 'text-xl font-bold mt-2 text-red-600 animate-pulse';
                            
                            if(statusDenah) {
                                statusDenah.textContent = '🚨 BAHAYA!';
                                statusDenah.className = 'text-xs font-bold text-red-600 animate-bounce';
                            }
                        } else {
                            flame.textContent = '✅ AMAN';
                            flame.className = 'text-xl font-bold mt-2 text-green-600';
                            
                            if(statusDenah) {
                                statusDenah.textContent = '🟢 Normal';
                                statusDenah.className = 'text-xs font-semibold text-green-600';
                            }
                        }
                        document.getElementById('gas-status').innerHTML = `${targetData.nilai_gas_asap} <span class="text-sm">PPM</span>`;
                    }
                })
                .catch(err => console.error("Gagal memuat data:", err));
        }

        setInterval(loadKamarData, 2000);
        loadKamarData();
    </script>
</body>
</html>