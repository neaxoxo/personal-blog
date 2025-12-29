<?php
session_start();
include 'config/database.php';

$query = "SELECT articles.*, users.full_name
          FROM articles
          LEFT JOIN users ON articles.author = users.username
          ORDER BY articles.created_at DESC";

$result = mysqli_query($conn, $query);

$articles = [];
while ($row = mysqli_fetch_assoc($result)) {
    $articles[] = $row;
}

$featured_post = null;
$side_posts = [];
$recent_posts = [];

if (!empty($articles)) {
    $featured_post = $articles[0];

    $side_posts = array_slice($articles, 1, 4);

    $recent_posts = array_slice($articles, 1);
}
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
<body class="bg-white text-gray-900">

    <?php
    $path = "";
    include 'includes/navbar.php';
    ?>

    <main class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <?php if (!$featured_post): ?>
            <div class="text-center py-20 bg-gray-50 rounded-3xl border border-dashed border-gray-300">
                <h2 class="text-2xl font-bold text-gray-800">Belum ada artikel</h2>
                <p class="text-gray-500 mt-2">Masuk ke dashboard admin untuk mulai menulis.</p>
            </div>
        <?php else: ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">

                <a href="article.php?id=<?php echo $featured_post['id']; ?>&from=home" class="lg:col-span-2 group block relative w-full h-[400px] lg:h-[500px] rounded-3xl overflow-hidden shadow-md">
                    <img src="<?php echo $featured_post['image']; ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-105" alt="Featured">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6 md:p-10 w-full md:w-3/4">
                        <span class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider text-white border border-white/30 mb-4 inline-block">
                            <?php echo htmlspecialchars($featured_post['category']); ?>
                        </span>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-2 group-hover:underline decoration-white/50 underline-offset-4">
                            <?php echo htmlspecialchars($featured_post['title']); ?>
                        </h2>
                    </div>
                </a>

                <div class="flex flex-col h-full">
                    <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-black rounded-full"></span>
                        Topik Pilihan
                    </h3>

                    <div class="flex flex-col gap-5 overflow-y-auto pr-2">
                        <?php if(empty($side_posts)): ?>
                            <p class="text-gray-400 text-sm italic">Belum ada artikel tambahan.</p>
                        <?php else: ?>
                            <?php foreach($side_posts as $post): ?>
                            <a href="article.php?id=<?php echo $post['id']; ?>&from=home" class="flex gap-4 group items-start">
                                <div class="w-24 h-20 shrink-0 rounded-xl overflow-hidden bg-gray-200">
                                    <img src="<?php echo $post['image']; ?>" class="w-full h-full object-cover transition duration-500 group-hover:scale-110" alt="Thumb">
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 leading-snug group-hover:text-blue-600 transition line-clamp-2 mb-1">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </h4>
                                    <span class="text-xs text-gray-400 block">
                                        <?php echo date('d M Y', strtotime($post['created_at'])); ?>
                                    </span>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>


            <div class="mb-20">
                <div class="flex justify-between items-end mb-8 border-b border-gray-100 pb-4">
                    <h3 class="text-2xl font-bold text-gray-900">Postingan Terbaru</h3>
                    <a href="blog.php" class="text-sm font-medium text-blue-600 hover:text-blue-800">Lihat Semua &rarr;</a>
                </div>

                <?php if(empty($recent_posts)): ?>
                    <p class="text-gray-500 italic text-center py-10 bg-gray-50 rounded-xl">Belum ada artikel tambahan.</p>
                <?php else: ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach($recent_posts as $post): ?>

                        <?php
                            // Jika ada full_name, pakai itu. Jika tidak, pakai author (username).
                            $display_author = !empty($post['full_name']) ? $post['full_name'] : $post['author'];
                        ?>

                        <a href="article.php?id=<?php echo $post['id']; ?>&from=home" class="group flex flex-col h-full bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition duration-300">

                            <div class="w-full aspect-[16/9] overflow-hidden bg-gray-100 relative">
                                <img src="<?php echo $post['image']; ?>" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" alt="Thumbnail">
                                <span class="absolute top-3 left-3 bg-white/90 backdrop-blur px-2 py-1 text-[10px] font-bold uppercase tracking-wider rounded text-black">
                                    <?php echo htmlspecialchars($post['category']); ?>
                                </span>
                            </div>

                            <div class="p-5 flex flex-col flex-grow">
                                <div class="flex items-center gap-2 text-xs text-gray-400 mb-3 font-medium">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                    <?php echo date('d M Y', strtotime($post['created_at'])); ?>
                                </div>

                                <h4 class="text-lg font-bold text-gray-900 mb-2 leading-snug group-hover:text-blue-600 transition line-clamp-2">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </h4>

                                <p class="text-gray-500 text-sm leading-relaxed mb-4 line-clamp-2">
                                    <?php echo substr(strip_tags($post['content']), 0, 90); ?>...
                                </p>

                                <div class="mt-auto pt-4 border-t border-gray-50 flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gray-900 text-white flex items-center justify-center text-[10px] font-bold">
                                        <?php echo substr($display_author, 0, 1); ?>
                                    </div>
                                    <span class="text-xs font-bold text-gray-700">
                                        <?php echo htmlspecialchars($display_author); ?>
                                    </span>
                                </div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>

                <?php endif; ?>
            </div>

        <?php endif; ?>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>
</html>
