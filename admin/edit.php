<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id = $_GET['id'];

$query_show = "SELECT * FROM articles WHERE id = '$id'";
$result_show = mysqli_query($conn, $query_show);
$data = mysqli_fetch_assoc($result_show);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit();
}

if (isset($_POST['update'])) {
    $title    = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $image    = mysqli_real_escape_string($conn, $_POST['image']);
    $content  = mysqli_real_escape_string($conn, $_POST['content']);

    $query_update = "UPDATE articles SET
                     title = '$title',
                     category = '$category',
                     image = '$image',
                     content = '$content'
                     WHERE id = '$id'";

    if (mysqli_query($conn, $query_update)) {
        echo "<script>alert('Artikel berhasil diperbarui!'); window.location='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel - Key UI Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen pb-20">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="index.php" class="flex items-center gap-2 text-gray-500 hover:text-black transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span class="font-medium">Kembali</span>
                </a>
                <span class="font-bold text-lg">Edit Artikel</span>
                <div class="w-20"></div>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 mt-10">
        <form action="" method="POST" class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Judul Artikel</label>
                <input type="text" name="title" value="<?php echo $data['title']; ?>" class="w-full px-4 py-3 text-lg font-bold rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:border-black transition" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-600 text-sm font-semibold mb-2">Kategori</label>
                    <select name="category" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:border-black transition">
                        <option value="Uncategorized" <?php if($data['category'] == 'Uncategorized') echo 'selected'; ?>>Pilih Kategori...</option>
                        <option value="Technology" <?php if($data['category'] == 'Technology') echo 'selected'; ?>>Technology</option>
                        <option value="Design" <?php if($data['category'] == 'Design') echo 'selected'; ?>>UI/UX Design</option>
                        <option value="Business" <?php if($data['category'] == 'Business') echo 'selected'; ?>>Business</option>
                        <option value="Life" <?php if($data['category'] == 'Life') echo 'selected'; ?>>Life & Productivity</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-600 text-sm font-semibold mb-2">Link Gambar (URL)</label>
                    <input type="url" name="image" value="<?php echo $data['image']; ?>" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:border-black transition" required>
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-gray-700 font-bold mb-2">Isi Konten</label>
                <textarea name="content" rows="12" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:border-black transition"><?php echo $data['content']; ?></textarea>
            </div>

            <div class="flex items-center justify-end gap-4 border-t pt-6">
                <a href="index.php" class="px-6 py-3 text-gray-500 font-medium hover:text-gray-800 transition">Batal</a>
                <button type="submit" name="update" class="px-8 py-3 bg-black text-white font-bold rounded-full hover:bg-gray-800 transition shadow-lg">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </main>

</body>
</html>
