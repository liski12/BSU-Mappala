<?php
include 'config.php'; // Koneksi ke database

// Menangani proses pengeditan profil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nama_pengguna = $_POST['username'];
    $no_hp = $_POST['no_telepon'];
    $foto = $_FILES['foto']['name'];
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    // Proses perubahan password
    if (!empty($password_lama) && !empty($password_baru) && !empty($konfirmasi_password)) {
        // Ambil password lama dari database
        $sql = "SELECT password FROM pengelola WHERE id_pengelola = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verifikasi password lama
            if (password_verify($password_lama, $user['password'])) {
                if ($password_baru === $konfirmasi_password) {
                    // Hash password baru
                    $password_hashed = password_hash($password_baru, PASSWORD_DEFAULT);

                    // Update password di database
                    $sql = "UPDATE pengelola SET password = ? WHERE id_pengelola = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("si", $password_hashed, $id);

                    if ($stmt->execute()) {
                        echo "Password berhasil diperbarui.";
                    } else {
                        echo "Gagal memperbarui password: " . $stmt->error;
                    }
                } else {
                    echo "Password baru dan konfirmasi tidak cocok.";
                }
            } else {
                echo "Password lama salah.";
            }
        } else {
            echo "Pengguna tidak ditemukan.";
        }
    }

    // Update data profil (termasuk foto profil)
    if ($foto) {
        $target_dir = "img/"; // Folder untuk menyimpan foto
        $target_file = $target_dir . basename($foto);

        // Memindahkan file foto ke folder uploads
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            // Update foto baru di database
            $sql = "UPDATE pengelola SET username = ?, no_telepon = ?, foto = ? WHERE id_pengelola = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $nama_pengguna, $no_hp, $target_file, $id);

            if ($stmt->execute()) {
                header('Location: profil.php?id_pengelola=' . $id);
                exit;
            } else {
                echo "Error saat memperbarui data: " . $stmt->error;
            }
        } else {
            echo "Gagal mengupload foto.";
        }
    } else {
        // Jika tidak ada foto baru, hanya update nama dan no_hp
        $sql = "UPDATE pengelola SET username = ?, no_telepon = ? WHERE id_pengelola = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nama_pengguna, $no_hp, $id);

        if ($stmt->execute()) {
            header('Location: profil.php?id_pengelola=' . $id);
            exit;
        } else {
            echo "Error saat memperbarui data: " . $stmt->error;
        }
    }
}

// Menampilkan data pengguna berdasarkan ID
$id = isset($_GET['id_pengelola']) ? $_GET['id_pengelola'] : 1; // Ganti 1 dengan ID pengguna yang sesuai
$sql = "SELECT * FROM pengelola WHERE id_pengelola = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

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
  <title>Edit Profil Pengelola</title>
  <link rel="stylesheet" href="editprofil.css">
</head>
<body>
  <header>
    <a href="profil.php?id_pengelola=<?php echo $user['id_pengelola']; ?>" class="back">&#8592;</a>
    <span class="title">BSU Sejahtera Abadi Mappala</span>
    <img src="img/logo.png" alt="Logo">
  </header>
  <div class="container">
    <div class="profile-card">
      <!-- Formulir untuk mengedit data profil -->
      <form action="edit_profil.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $user['id_pengelola']; ?>"> <!-- Menyembunyikan ID pengguna -->
        
        <label for="username">Nama Pengguna:</label>
        <input type="text" id="username" name="username" value="<?php echo isset($user['username']) ? htmlspecialchars($user['username']) : ''; ?>" required><br>
        
        <label for="no_telepon">No. HP:</label>
        <input type="text" id="no_telepon" name="no_telepon" value="<?php echo isset($user['no_telepon']) ? htmlspecialchars($user['no_telepon']) : ''; ?>" required><br>
        
        <label for="foto">Foto Profil:</label>
        <input type="file" id="foto" name="foto" accept="image/*"><br>

        <!-- Bagian ubah password -->
        <h3>Ubah Password</h3>
        <label for="password_lama">Password Lama:</label>
        <input type="password" id="password_lama" name="password_lama"><br>

        <label for="password_baru">Password Baru:</label>
        <input type="password" id="password_baru" name="password_baru"><br>

        <label for="konfirmasi_password">Konfirmasi Password Baru:</label>
        <input type="password" id="konfirmasi_password" name="konfirmasi_password"><br>

        <!-- Tombol Simpan Perubahan -->
        <button type="submit" class="edit-profile">Simpan Perubahan</button>

        <!-- Checkbox untuk menampilkan/mengembunyikan password -->
        <div style="margin-top: 10px;">
          <input type="checkbox" id="show_password" onclick="togglePasswordVisibility()"> Tampilkan Password
        </div>
      </form>
    </div>
  </div>
  <script>
    function togglePasswordVisibility() {
      // Ambil elemen input password
      const passwordFields = [
        document.getElementById('password_lama'),
        document.getElementById('password_baru'),
        document.getElementById('konfirmasi_password')
      ];

      // Periksa status checkbox
      const showPassword = document.getElementById('show_password').checked;

      // Ubah type input berdasarkan status checkbox
      passwordFields.forEach(field => {
        field.type = showPassword ? 'text' : 'password';
      });
    }
  </script>
</body>
</html>
