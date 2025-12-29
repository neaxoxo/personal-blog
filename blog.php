<?php
session_start();
include 'config/database.php';

$query_categories = "SELECT DISTINCT category FROM articles";
$result_categories = mysqli_query($conn, $query_categories);

$sql = "SELECT * FROM articles WHERE 1=1";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql .= " AND (title LIKE '%$search%' OR content LIKE '%$search%')";
}
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $cat_filter = mysqli_real_escape_string($conn, $_GET['category']);
    $sql .= " AND category = '$cat_filter'";
}
$sql .= " ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
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
<body class="bg-white text-gray-900 flex flex-col min-h-screen">

    <?php
        $path = "";
        include 'includes/navbar.php';
    ?>

    <div class="flex-grow w-full">

        <header class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8">
            <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-8">
                <div class="max-w-2xl">
                    <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-gray-900 mb-4 leading-tight">Wawasan Terbaru.</h1>
                    <p class="text-lg text-gray-500">Kumpulan artikel seputar teknologi, desain, dan produktivitas.</p>
                </div>
                <form action="" method="GET" class="w-full md:w-auto min-w-[300px]">
                    <div class="relative group">
                        <input type="text" name="search" placeholder="Cari artikel..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" class="w-full pl-5 pr-12 py-3.5 rounded-full border border-gray-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-black transition shadow-sm text-sm">
                        <button type="submit" class="absolute right-2 top-2 bg-black text-white p-2 rounded-full hover:bg-gray-800 transition shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>
                </form>
            </div>

            <div class="border-b border-gray-100 pb-8">
                <div class="flex flex-wrap gap-2">
                    <a href="blog.php" class="px-5 py-2 text-sm font-medium rounded-full transition border <?php echo !isset($_GET['category']) ? 'bg-black text-white border-black' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300'; ?>">All Posts</a>
                    <?php while($cat = mysqli_fetch_assoc($result_categories)): ?>
                        <?php
                            $isActive = (isset($_GET['category']) && $_GET['category'] == $cat['category']);
                            $activeClass = "bg-black text-white border-black";
                            $inactiveClass = "bg-white text-gray-600 border-gray-200 hover:border-gray-300";
                        ?>
                        <a href="blog.php?category=<?php echo urlencode($cat['category']); ?>" class="px-5 py-2 text-sm font-medium rounded-full transition border <?php echo $isActive ? $activeClass : $inactiveClass; ?>">
                           <?php echo htmlspecialchars($cat['category']); ?>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        </header>

        <main class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <?php if(mysqli_num_rows($result) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-10">
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <a href="article.php?id=<?php echo $row['id']; ?>" class="group flex flex-col h-full bg-white rounded-2xl overflow-hidden hover:shadow-xl hover:-translate-y-1 transition duration-300 border border-gray-100">
                        <div class="w-full aspect-[16/9] overflow-hidden bg-gray-100 relative">
                            <img src="<?php echo $row['image']; ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-105" alt="Cover">
                            <span class="absolute top-4 left-4 bg-white/95 backdrop-blur px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md shadow-sm border border-gray-100">
                                <?php echo htmlspecialchars($row['category']); ?>
                            </span>
                        </div>
                        <div class="p-5 flex flex-col flex-grow">
                            <div class="text-xs font-medium text-gray-400 mb-2 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
                                <?php echo date('d M Y', strtotime($row['created_at'])); ?>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 mb-2 leading-snug group-hover:text-blue-600 transition line-clamp-2">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </h2>
                            <p class="text-gray-500 text-sm leading-relaxed line-clamp-2 mb-4">
                                <?php echo substr(strip_tags($row['content']), 0, 90); ?>...
                            </p>
                            <div class="mt-auto pt-4 border-t border-gray-50 flex items-center gap-3">
                                <div class="w-7 h-7 rounded-full bg-gray-900 text-white flex items-center justify-center text-[10px] font-bold uppercase">
                                    <?php echo substr($row['author'], 0, 1); ?>
                                </div>
                                <span class="text-xs font-bold text-gray-700"><?php echo htmlspecialchars($row['author']); ?></span>
                            </div>
                        </div>
                    </a>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-20 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                    <p class="text-gray-500 font-medium">Tidak ada artikel yang ditemukan.</p>
                    <a href="blog.php" class="inline-block mt-2 text-black font-bold hover:underline">Reset Filter</a>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <?php include 'includes/footer.php'; ?>

</body>
</html>
