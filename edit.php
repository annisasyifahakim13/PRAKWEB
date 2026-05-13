<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['nama']) || $_SESSION['nama'] !== 'admin' || !isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];
$pesan = "";

$stmt = $conn->prepare("SELECT nama FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_baru = trim($_POST['nama']);
    $password_baru = $_POST['password'];

    if (!empty($nama_baru) && !empty($password_baru)) {
        $hashed_password = password_hash($password_baru, PASSWORD_BCRYPT);
        
        $update_stmt = $conn->prepare("UPDATE users SET nama = ?, password = ? WHERE id = ?");
        $update_stmt->bind_param("ssi", $nama_baru, $hashed_password, $id);
        
        if ($update_stmt->execute()) {
            header("Location: dashboard.php?pesan=Data berhasil diperbarui");
            exit();
        } else {
            $pesan = "Gagal memperbarui data.";
        }
        $update_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Pengguna</title>
</head>
<body>
    <h2>Edit Data Pengguna</h2> <?php if ($pesan != "") echo "<p style='color:red;'>$pesan</p>"; ?>

    <form method="POST" action="">
        <label>Nama Pengguna:</label><br>
        <input type="text" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required><br><br>
        
        <label>Password Baru:</label><br>
        <input type="password" name="password" placeholder="Masukkan password baru" required><br><br>
        
        <button type="submit">Simpan Perubahan</button> <a href="dashboard.php"><button type="button">Batal</button></a>
    </form>
</body>
</html>