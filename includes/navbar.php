<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($path)) { $path = ""; }

$current_page = basename($_SERVER['PHP_SELF']);

$admin_avatar = null;
$admin_name = "Admin";

if (isset($_SESSION['status']) && $_SESSION['status'] == 'login') {
    include_once $path . 'config/database.php';
    $u_sess = $_SESSION['username'];
    $q_nav = "SELECT * FROM users WHERE username = '$u_sess'";
    $r_nav = mysqli_query($conn, $q_nav);
    if ($r_nav && mysqli_num_rows($r_nav) > 0) {
        $d_nav = mysqli_fetch_assoc($r_nav);
        $admin_avatar = $d_nav['avatar'];
        $admin_name = $d_nav['full_name'];
    }
}
?>

<nav class="border-b border-gray-100 sticky top-0 bg-white/90 backdrop-blur-md z-50 w-full">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex justify-between items-center">

            <div class="flex items-center gap-2">
                <span class="text-xl font-bold tracking-tight">Key UI</span>
            </div>

            <div class="hidden md:flex gap-8 text-sm font-medium text-gray-600">
                <a href="<?php echo $path; ?>index.php" class="transition hover:text-black <?php echo ($current_page == 'index.php') ? 'text-black font-bold' : ''; ?>">
                    Homepage
                </a>
                <a href="<?php echo $path; ?>blog.php" class="transition hover:text-black <?php echo ($current_page == 'blog.php') ? 'text-black font-bold' : ''; ?>">
                    Blog
                </a>
            </div>

            <div class="flex items-center gap-4">
                <?php if (isset($_SESSION['status']) && $_SESSION['status'] == 'login'): ?>

                    <a href="<?php echo $path; ?>admin/index.php" class="flex items-center gap-3 hover:opacity-80 transition group">
                        <div class="text-right hidden sm:block">
                            <span class="block text-sm font-bold text-gray-900 leading-none">
                                <?php echo htmlspecialchars($admin_name); ?>
                            </span>
                            <span class="text-xs text-gray-500 group-hover:text-black transition">Dashboard &rarr;</span>
                        </div>

                        <div class="w-9 h-9 rounded-full overflow-hidden bg-gray-200 border border-gray-200">
                            <?php if($admin_avatar): ?>
                                <img src="<?php echo $path; ?>assets/uploads/<?php echo $admin_avatar; ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-black text-white text-xs font-bold">
                                    <?php echo substr($admin_name, 0, 1); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </a>

                <?php else: ?>

                    <a href="<?php echo $path; ?>admin/login.php" class="px-5 py-2 text-sm font-medium text-white bg-black rounded-full hover:bg-gray-800 transition">
                        Login Admin
                    </a>

                <?php endif; ?>
            </div>

        </div>
    </div>
</nav>
