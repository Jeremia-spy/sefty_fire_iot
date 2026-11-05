<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lokasi         = $_POST['lokasi'];         // Contoh: "Lokasi 1"
    $flame_status   = $_POST['flame_status'];   // 0 (Aman) atau 1 (Api Terdeteksi)
    $nilai_gas_asap = $_POST['nilai_gas_asap']; // Nilai PPM

    if (!empty($lokasi) && isset($flame_status) && isset($nilai_gas_asap)) {
        $query = "INSERT INTO sensor_data (lokasi, flame_status, nilai_gas_asap) VALUES ('$lokasi', '$flame_status', '$nilai_gas_asap')";
        
        if (mysqli_query($koneksi, $query)) {
            
            // Jika terdeteksi bahaya (ada api atau gas tinggi > 300 PPM)
            if ($flame_status == 1 || $nilai_gas_asap > 300) {
                
                // Cari Chat ID penghuni berdasarkan lokasi/kamar dari database
                $query_user = mysqli_query($koneksi, "SELECT chat_id, nama_penghuni FROM penghuni_kos WHERE nomor_kamar = '$lokasi'");
                $data_user = mysqli_fetch_assoc($query_user);
                
                if (!empty($data_user['chat_id'])) {
                    $token = "9259196579:AAEWhVmyPyKkxWaI2v0-OSsBt-00a67nMKY";
                    $chat_id_tujuan = $data_user['chat_id'];
                    $nama = $data_user['nama_penghuni'];
                    
                    $pesan = "🚨 PERINGATAN BAHAYA UNTUK $nama! 🚨\n";
                    $pesan .= "Kamar/Lokasi: $lokasi\n";
                    $pesan .= "Status Api: " . ($flame_status == 1 ? "ADA API! 🔥" : "Aman") . "\n";
                    $pesan .= "Kadar Gas/Asap: $nilai_gas_asap PPM\n";
                    $pesan .= "Segera periksa ruangan Anda!";

                    $url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id_tujuan&text=" . urlencode($pesan);
                    @file_get_contents($url);
                }
            }

            echo json_encode(["status" => "success", "message" => "Data berhasil disimpan & notifikasi dikirim"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal menyimpan data"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
    }
}
?>