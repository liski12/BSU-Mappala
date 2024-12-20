<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bsumappala";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses input dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $no_telepon = trim($_POST['phone']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi data
    if ($password !== $confirm_password) {
        header("Location: index.php?error=Password tidak cocok");
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Periksa apakah username atau nomor telepon sudah digunakan
    $checkQuery = "SELECT * FROM pengelola WHERE username = ? OR no_telepon = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ss", $username, $no_telepon);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: index.php?error=Username atau nomor telepon sudah terdaftar");
        exit();
    }

    // Masukkan data ke database
    $insertQuery = "INSERT INTO pengelola (no_telepon, username, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sss", $no_telepon, $username, $hashed_password);

    if ($stmt->execute()) {
        // Mulai sesi dan arahkan ke dashboard
        session_start();
        $_SESSION['username'] = $username; // Simpan username ke sesi
        $_SESSION['loggedin'] = true; // Tandai bahwa pengguna telah login
        header("Location: dashboard.php"); // Arahkan ke halaman dashboard
        exit();
    } else {
        header("Location: index.php?error=Terjadi kesalahan, silakan coba lagi.");
        exit();
    }
}

// Tutup koneksi
$conn->close();
?>
