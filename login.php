<?php
session_start();
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Atur username dan password sesuai keinginan Anda di sini
    if ($username == "admin" && $password == "12345") {
        $_SESSION['user'] = "admin";
        header("Location: index.php");
        exit;
    } else {
        $error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Monitoring Sensor</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-md w-80">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6"> Login Admin</h2>
        <?php if(isset($error)): ?>
            <p class="text-red-500 text-sm text-center mb-4">Username atau Password salah!</p>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                <input type="text" name="username" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" name="login" class="w-full bg-blue-600 text-white font-bold py-2 rounded-lg hover:bg-blue-700">Masuk</button>
        </form>
    </div>
</body>
</html>