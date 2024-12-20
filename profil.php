<?php
include 'config.php'; // Koneksi ke database

// Menampilkan data pengguna berdasarkan ID
$id = isset($_GET['id_pengelola']) ? $_GET['id_pengelola'] : 1; // Ganti 1 dengan ID pengguna yang sesuai
$sql = "SELECT * FROM pengelola WHERE id_pengelola = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Cek apakah data ditemukan
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Pengelola</title>
  <link rel="stylesheet" href="profil.css">
</head>
<body>
  <header>
    <a href="dashboard.php" class="back">&#8592;</a>
    <span class="title">BSU Sejahtera Abadi Mappala</span>
    <img src="img/logo.png" alt="Logo">
  </header>
  <div class="container">
  <div class="profile-card">
    <!-- Menampilkan foto profil -->
    <img src="<?php echo isset($user['foto']) && $user['foto'] ? $user['foto'] : 'img/default-profile.jpg'; ?>" alt="Profile Image" class="profile-image">
    <h2 class="name"><?php echo isset($user['username']) ? htmlspecialchars($user['username']) : 'No name set'; ?></h2>
    <p class="phone-number"><?php echo isset($user['no_telepon']) ? htmlspecialchars($user['no_telepon']) : 'No phone number set'; ?></p>
  </div>
</div>

<!-- Tautan tombol edit profil di luar container -->
<div class="edit-profile-wrapper">
  <a href="edit_profil.php?id_pengelola=<?php echo $user['id_pengelola']; ?>" class="edit-profile">Edit Profil</a>
</div>
</body>
</html>
