<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Monitoring Sensor Gas & Api</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; text-align: center; padding: 20px; }
        .container { display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; margin-top: 20px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); width: 300px; }
        .status-aman { color: green; font-weight: bold; }
        .status-bahaya { color: red; font-weight: bold; }
    </style>
</head>
<body>

    <h1>Dashboard Pemantauan Sensor (ESP32 + Firebase)</h1>
    <p style="color: gray; font-size: 14px;">Status Server XAMPP: Aktif (Terhubung ke Cloud Firebase)</p>
    
    <div class="container">
        <!-- Kotak Ruangan 1 -->
        <div class="card">
            <h2>Ruangan 1</h2>
            <p>Kadar Gas: <span id="gas-r1">Memuat...</span></p>
            <p>Sensor Api: <span id="flame-r1">Memuat...</span></p>
            <p>Status: <span id="status-r1" class="status-aman">Memuat...</span></p>
        </div>

        <!-- Kotak Ruangan 2 -->
        <div class="card">
            <h2>Ruangan 2</h2>
            <p>Kadar Gas: <span id="gas-r2">Memuat...</span></p>
            <p>Sensor Api: <span id="flame-r2">Memuat...</span></p>
            <p>Status: <span id="status-r2" class="status-aman">Memuat...</span></p>
        </div>
    </div>

    <!-- Script JavaScript untuk mengambil data dari Firebase secara Real-time -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-app.js";
        import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-database.js";

        // Konfigurasi Database Firebase Anda
        const firebaseConfig = {
            databaseURL: "https://sefty-fire-iot-default-rtdb.asia-southeast1.firebasedatabase.app"
        };

        // Inisialisasi Firebase
        const app = initializeApp(firebaseConfig);
        const db = getDatabase(app);

        // Ambil data Ruangan 1 secara real-time
        const refR1 = ref(db, 'sensor/ruangan_1');
        onValue(refR1, (snapshot) => {
            const data = snapshot.val();
            if (data) {
                document.getElementById('gas-r1').innerText = data.gas;
                document.getElementById('flame-r1').innerText = data.flame === 1 ? 'Ada Api!' : 'Aman';
                
                const statusElem = document.getElementById('status-r1');
                statusElem.innerText = data.status;
                statusElem.className = data.status === 'BAHAYA' ? 'status-bahaya' : 'status-aman';
            }
        });

        // Ambil data Ruangan 2 secara real-time
        const refR2 = ref(db, 'sensor/ruangan_2');
        onValue(refR2, (snapshot) => {
            const data = snapshot.val();
            if (data) {
                document.getElementById('gas-r2').innerText = data.gas;
                document.getElementById('flame-r2').innerText = data.flame === 1 ? 'Ada Api!' : 'Aman';
                
                const statusElem = document.getElementById('status-r2');
                statusElem.innerText = data.status;
                statusElem.className = data.status === 'BAHAYA' ? 'status-bahaya' : 'status-aman';
            }
        });
    </script>

</body>
</html>