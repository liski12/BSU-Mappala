
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #cce1ff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Header untuk tulisan di atas */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            color: #3b5998;
            font-family: 'Concert One', cursive;
            margin: 0;
            font-size: 1.5em;
        }

        /* Logo */
        .logo {
            margin-bottom: 20px;
        }

        .logo img {
            width: 100px;
        }

        /* Kontainer untuk form */
        .container {
            background-color: #d4f5c7;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 300px;
        }

        .container input[type="text"], 
        .container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #b0c4de;
            border-radius: 5px;
            box-sizing: border-box;
            text-align: center;
        }

        .container button {
            width: 40%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1em;
            cursor: pointer;
            margin-top: 12px;
        }

        .container button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 10px;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #f5c6cb;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header dengan tulisan -->
    <div class="header">
        <h2>Bank Sampah <br> Sejahtera Abadi Mappala</h2>
    </div>

    <!-- Logo -->
    <div class="logo">
        <img src="img/logo.png" alt="Logo Bank Sampah"> <!-- Ganti dengan path logo -->
    </div>

    <!-- Form login -->
    <div class="container">
        <form method="POST" action="proses_login.php">
            <input type="text" name="username" placeholder="Masukkan Username Anda" required>
            <input type="password" name="password" placeholder="Masukkan Password Anda" required>
            <button type="submit">Masuk</button>
        </form>

        <!-- Tampilkan pesan error jika ada -->
        <?php
        if (isset($_GET['error'])) {
            echo '<div class="error">' . htmlspecialchars($_GET['error']) . '</div>';
        }
        ?>
    </div>
</body>
</html>
