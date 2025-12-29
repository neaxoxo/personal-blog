<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['simpan'])) {
    // Ambil data dari form
    $title    = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $image    = mysqli_real_escape_string($conn, $_POST['image']);
    $content  = mysqli_real_escape_string($conn, $_POST['content']);
    $author   = $_SESSION['username'];

    // Masukkan ke Database
    $query = "INSERT INTO articles (title, category, image, content, author)
              VALUES ('$title', '$category', '$image', '$content', '$author')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Artikel berhasil diterbitkan!'); window.location='index.php';</script>";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tulis Artikel - Key UI Admin</title>
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
                    <span class="font-medium">Kembali ke Dashboard</span>
                </a>
                <span class="font-bold text-lg">Editor Artikel</span>
                <div class="w-20"></div> </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 mt-10">

        <form action="" method="POST" class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Judul Artikel</label>
                <input type="text" name="title" class="w-full px-4 py-3 text-lg font-bold rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition placeholder-gray-400" placeholder="Tulis judul yang menarik..." required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-600 text-sm font-semibold mb-2">Kategori</label>
                    <select name="category" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:border-black transition">
                        <option value="Uncategorized">Pilih Kategori...</option>
                        <option value="Technology">Technology</option>
                        <option value="Design">UI/UX Design</option>
                        <option value="Business">Business</option>
                        <option value="Life">Life & Productivity</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-600 text-sm font-semibold mb-2">Link Gambar (URL)</label>
                    <input type="url" name="image" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:border-black transition" placeholder="https://..." value="https://images.unsplash.com/photo-1499750310159-52f0f834631e" required>
                    <p class="text-xs text-gray-400 mt-1">*Copy link gambar dari Unsplash/Google Images</p>
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-gray-700 font-bold mb-2">Isi Konten</label>
                <textarea name="content" rows="12" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:border-black focus:ring-1 focus:ring-black transition" placeholder="Mulai menulis ceritamu di sini..." required></textarea>
            </div>

            <div class="flex items-center justify-end gap-4 border-t pt-6">
                <a href="index.php" class="px-6 py-3 text-gray-500 font-medium hover:text-gray-800 transition">Batal</a>
                <button type="submit" name="simpan" class="px-8 py-3 bg-black text-white font-bold rounded-full hover:bg-gray-800 transition shadow-lg">
                    Terbitkan Artikel
                </button>
            </div>

        </form>

    </main>

</body>
</html>
