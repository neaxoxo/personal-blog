<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "DELETE FROM articles WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Artikel berhasil dihapus!'); window.location='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Jika user buka file ini tanpa ID
    header("Location: index.php");
}
?>
