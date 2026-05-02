<?php
include 'koneksi.php';
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $npm  = mysqli_real_escape_string($conn, $_POST['npm']);
    
    mysqli_query($conn, "INSERT INTO mahasiswa (nama, npm) VALUES ('$nama', '$npm')");
    header("Location: index.php");
    exit;
}
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM mahasiswa WHERE id=$id");
    header("Location: index.php");
    exit;
}
if (isset($_POST['update'])) {
    $id   = (int) $_POST['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $npm  = mysqli_real_escape_string($conn, $_POST['npm']);
    
    mysqli_query($conn, "UPDATE mahasiswa SET nama='$nama', npm='$npm' WHERE id=$id");
    header("Location: index.php");
    exit;
}
$data = mysqli_query($conn, "SELECT * FROM mahasiswa ORDER BY id DESC");
$edit_data = null;
if (isset($_GET['edit'])) {
    $id_edit = (int) $_GET['edit'];
    $result  = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id=$id_edit");
    $edit_data = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>CRUD Mahasiswa - Universitas Lampung</title>
</head>
<body>
    <h2>Data Mahasiswa</h2>
    <h3><?= $edit_data ? "Edit Data" : "Tambah Data"; ?></h3>
    <form action="" method="POST">
        <?php if ($edit_data): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id']; ?>">
        <?php endif; ?>
        
        <input type="text" name="nama" placeholder="Nama Mahasiswa" value="<?= $edit_data ? $edit_data['nama'] : ''; ?>" required>
        <input type="text" name="npm" placeholder="NPM" value="<?= $edit_data ? $edit_data['npm'] : ''; ?>" required>
        
        <?php if ($edit_data): ?>
            <button type="submit" name="update">Simpan Perubahan</button>
            <a href="index.php">Batal</a>
        <?php else: ?>
            <button type="submit" name="tambah">Tambah</button>
        <?php endif; ?>
    </form>
    <br>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>NPM</th>
        <th>Aksi</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($data)) : ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= $row['nama']; ?></td>
            <td><?= $row['npm']; ?></td>
            <td>
                <a href="index.php?edit=<?= $row['id']; ?>">Edit</a> | 
                <a href="index.php?hapus=<?= $row['id']; ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>