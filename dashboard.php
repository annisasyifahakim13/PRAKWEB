<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['nama'])) {
    header("Location: auth.php");
    exit();
}

if ($_SESSION['nama'] === 'admin' && isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id_hapus);
    if ($stmt->execute()) {
        header("Location: dashboard.php?pesan=Data berhasil dihapus");
        exit();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</h2>
    <a href="logout.php"><button>Logout</button></a>
    
    <hr> 

    <?php if ($_SESSION['nama'] === 'admin'): ?>
        <h3>Menu Admin: Kelola Pengguna</h3>
        <?php if (isset($_GET['pesan'])) echo "<p><i>{$_GET['pesan']}</i></p>"; ?>

        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Aksi</th>
            </tr>
            <?php
            $result = $conn->query("SELECT id, nama FROM users ORDER BY id ASC");
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                <td>
                    <a href="edit.php?id=<?php echo $row['id']; ?>"><button>Edit</button></a>
                    <a href="dashboard.php?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus?')"><button>Hapus</button></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
    <?php endif; ?>
</body>
</html>