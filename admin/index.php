<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("Location: login.php");
    exit();
}

$current_user = $_SESSION['username'];
$query_user = "SELECT * FROM users WHERE username = '$current_user'";
$result_user = mysqli_query($conn, $query_user);
$admin_data = mysqli_fetch_assoc($result_user);

$query = "SELECT * FROM articles ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Key UI</title>

    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">

                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-black rounded-full flex items-center justify-center text-white font-bold">
                        <svg width="20" height="20" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    </div>
                    <div>
                        <span class="text-lg font-bold tracking-tight block leading-none">Key UI</span>
                        <span class="text-xs text-gray-500 font-medium">Admin Panel</span>
                    </div>
                </div>

                <div class="flex items-center gap-6">

                    <a href="../index.php" class="flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-black transition group">
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-black transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        <span class="hidden md:inline">Lihat Website</span>
                    </a>

                    <div class="h-6 w-px bg-gray-200 hidden md:block"></div>

                    <div class="flex items-center gap-4">
                        <a href="profile.php" class="flex items-center gap-3 hover:opacity-80 transition">
                            <div class="text-right hidden md:block">
                                <span class="block text-sm font-bold text-gray-900 leading-none">
                                    <?php echo htmlspecialchars($admin_data['full_name']); ?>
                                </span>
                                <span class="text-xs text-gray-500">Editor</span>
                            </div>

                            <div class="w-9 h-9 rounded-full overflow-hidden bg-gray-200 border border-gray-200">
                                <?php if($admin_data['avatar']): ?>
                                    <img src="../assets/uploads/<?php echo $admin_data['avatar']; ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-black text-white text-xs font-bold">
                                        <?php echo substr($admin_data['username'], 0, 1); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </a>

                        <a href="logout.php" class="text-gray-400 hover:text-red-600 transition" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Artikel Saya</h1>
                <p class="text-gray-500 text-sm mt-1">Kelola semua tulisan blog kamu di sini.</p>
            </div>

            <a href="create.php" class="inline-flex items-center gap-2 bg-black text-white px-5 py-2.5 rounded-full font-medium hover:bg-gray-800 transition shadow-lg shadow-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tulis Artikel Baru
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <?php if(mysqli_num_rows($result) > 0) { ?>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                            <th class="px-6 py-4">Cover</th>
                            <th class="px-6 py-4">Judul & Kategori</th>
                            <th class="px-6 py-4">Penulis</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">

                        <?php while($row = mysqli_fetch_assoc($result)) { ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="w-16 h-12 rounded-lg overflow-hidden bg-gray-200 border border-gray-100">
                                    <img src="<?php echo $row['image']; ?>" class="w-full h-full object-cover" alt="Cover">
                                </div>
                            </td>

                            <td class="px-6 py-4 max-w-xs">
                                <h3 class="font-bold text-gray-900 truncate"><?php echo $row['title']; ?></h3>
                                <span class="text-xs text-blue-600 font-medium bg-blue-50 px-2 py-0.5 rounded-md mt-1 inline-block">
                                    <?php echo $row['category']; ?>
                                </span>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                <?php echo $row['author']; ?>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500">
                                <?php echo date('d M Y', strtotime($row['created_at'])); ?>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">

                                    <a href="../article.php?id=<?php echo $row['id']; ?>&from=dashboard" class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition" title="Lihat">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>

                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>

                                    <a href="delete.php?id=<?php echo $row['id']; ?>" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus" onclick="return confirm('Yakin ingin menghapus artikel ini?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </a>

                                </div>
                            </td>
                        </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>

            <?php } else { ?>

                <div class="text-center py-20">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Belum ada artikel</h3>
                    <p class="text-gray-500 mt-1">Mulai menulis dengan menekan tombol di pojok kanan atas.</p>
                </div>

            <?php } ?>

        </div>
    </main>

</body>
</html>
