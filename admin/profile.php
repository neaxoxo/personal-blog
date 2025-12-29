<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("Location: login.php");
    exit();
}

$username_sess = $_SESSION['username'];
$query = "SELECT * FROM users WHERE username = '$username_sess'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if (isset($_POST['update_profile'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username  = mysqli_real_escape_string($conn, $_POST['username']);
    $id_user   = $user['id'];

    $avatar_query = "";
    if (!empty($_FILES['avatar']['name'])) {
        $filename = $_FILES['avatar']['name'];
        $filesize = $_FILES['avatar']['size'];
        $tmp_name = $_FILES['avatar']['tmp_name'];

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $new_filename = "user_" . $id_user . "_" . time() . "." . $ext;
        $destination = "../assets/uploads/" . $new_filename;

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($ext), $allowed)) {
            if (move_uploaded_file($tmp_name, $destination)) {
                if ($user['avatar'] && file_exists("../assets/uploads/" . $user['avatar'])) {
                    unlink("../assets/uploads/" . $user['avatar']);
                }
                $avatar_query = ", avatar = '$new_filename'";
            }
        } else {
            echo "<script>alert('Format file tidak didukung!');</script>";
        }
    }

    $update_sql = "UPDATE users SET full_name = '$full_name', username = '$username' $avatar_query WHERE id = '$id_user'";

    if (mysqli_query($conn, $update_sql)) {
        $_SESSION['username'] = $username;
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='profile.php';</script>";
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
    <title>Edit Profil - Key UI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="index.php" class="flex items-center gap-2 text-gray-500 hover:text-black transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span class="font-medium">Kembali ke Dashboard</span>
                </a>
                <span class="font-bold text-lg">Pengaturan Profil</span>
                <div class="w-20"></div>
            </div>
        </div>
    </nav>

    <main class="max-w-xl mx-auto px-4 mt-10 pb-20">

        <form action="" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">

            <div class="text-center mb-8">
                <div class="relative w-32 h-32 mx-auto mb-4">
                    <?php if($user['avatar']): ?>
                        <img src="../assets/uploads/<?php echo $user['avatar']; ?>" class="w-full h-full object-cover rounded-full border-4 border-gray-100 shadow-sm">
                    <?php else: ?>
                        <div class="w-full h-full bg-gray-200 rounded-full flex items-center justify-center text-gray-400 border-4 border-gray-50">
                            <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                    <?php endif; ?>

                    <label for="avatar" class="absolute bottom-0 right-0 bg-black text-white p-2 rounded-full cursor-pointer hover:bg-gray-800 transition shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </label>
                    <input type="file" name="avatar" id="avatar" class="hidden" accept="image/*">
                </div>
                <h2 class="text-xl font-bold"><?php echo htmlspecialchars($user['full_name']); ?></h2>
                <p class="text-gray-500 text-sm">@<?php echo htmlspecialchars($user['username']); ?></p>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap / Penulis</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:border-black transition" required>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Username (untuk Login)</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:border-black transition" required>
                </div>

                <div class="pt-4">
                    <button type="submit" name="update_profile" class="w-full bg-black text-white font-bold py-3 rounded-full hover:bg-gray-800 transition shadow-lg">
                        Simpan Perubahan
                    </button>
                </div>
            </div>

        </form>
    </main>

</body>
</html>
