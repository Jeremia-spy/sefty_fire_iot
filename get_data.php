<?php
header('Content-Type: application/json');
include 'koneksi.php';

// Ambil data terbaru untuk Lokasi 1
$q1 = mysqli_query($koneksi, "SELECT * FROM sensor_data WHERE lokasi='Lokasi 1' ORDER BY id DESC LIMIT 1");
$lokasi1 = mysqli_fetch_assoc($q1);

// Ambil data terbaru untuk Lokasi 2
$q2 = mysqli_query($koneksi, "SELECT * FROM sensor_data WHERE lokasi='Lokasi 2' ORDER BY id DESC LIMIT 1");
$lokasi2 = mysqli_fetch_assoc($q2);

// Ambil 10 data riwayat gas terbaru untuk grafik
$q_graph_1 = mysqli_query($koneksi, "SELECT nilai_gas_asap, DATE_FORMAT(waktu, '%H:%i:%s') as jam FROM sensor_data WHERE lokasi='Lokasi 1' ORDER BY id DESC LIMIT 10");
$q_graph_2 = mysqli_query($koneksi, "SELECT nilai_gas_asap, DATE_FORMAT(waktu, '%H:%i:%s') as jam FROM sensor_data WHERE lokasi='Lokasi 2' ORDER BY id DESC LIMIT 10");

$graph_l1 = [];
while ($row = mysqli_fetch_assoc($q_graph_1)) {
    $graph_l1[] = $row;
}

$graph_l2 = [];
while ($row = mysqli_fetch_assoc($q_graph_2)) {
    $graph_l2[] = $row;
}

echo json_encode([
    'lokasi1' => $lokasi1,
    'lokasi2' => $lokasi2,
    'graph1'  => array_reverse($graph_l1),
    'graph2'  => array_reverse($graph_l2)
]);
?>