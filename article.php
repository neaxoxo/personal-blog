<?php
$path = "";
include 'config/database.php';

// Cek ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id = mysqli_real_escape_string($conn, $_GET['id']);

$from = isset($_GET['from']) ? $_GET['from'] : '';
switch ($from) {
    case 'dashboard': $back_link = 'admin/index.php'; $back_text = 'Kembali ke Dashboard'; break;
    case 'blog':      $back_link = 'blog.php';        $back_text = 'Kembali ke Blog';      break;
    default:          $back_link = 'index.php';       $back_text = 'Kembali ke Home';      break;
}

$query = "SELECT articles.*, users.full_name, users.avatar AS user_avatar
          FROM articles
          LEFT JOIN users ON articles.author = users.username
          WHERE articles.id = '$id'";

$result = mysqli_query($conn, $query);
$article = mysqli_fetch_assoc($result);

if (!$article) { header("Location: index.php"); exit(); }

// --- LOGIKA PENENTUAN NAMA & FOTO PENULIS ---
// Jika full_name ditemukan di tabel users, pakai itu. Jika tidak, pakai author asli (username).
$display_author = !empty($article['full_name']) ? $article['full_name'] : $article['author'];

// Jika user_avatar ditemukan, pakai itu. Jika tidak, kosong.
$display_avatar = !empty($article['user_avatar']) ? 'assets/uploads/' . $article['user_avatar'] : null;


// Hitung Waktu Baca
$word_count = str_word_count(strip_tags($article['content']));
$read_time = ceil($word_count / 200);

// Query Sidebar & Bottom (Tetap sama)
$query_sidebar = "SELECT * FROM articles WHERE id != '$id' ORDER BY RAND() LIMIT 2";
$result_sidebar = mysqli_query($conn, $query_sidebar);

// Query Bottom (JOIN juga agar nama penulis di bagian bawah ikut berubah)
$query_bottom = "SELECT articles.*, users.full_name
                 FROM articles
                 LEFT JOIN users ON articles.author = users.username
                 WHERE articles.id != '$id'
                 ORDER BY created_at DESC LIMIT 4";
$result_bottom = mysqli_query($conn, $query_bottom);
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

    <?php include 'includes/navbar.php'; ?>

    <main class="pt-8 pb-20">

        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <a href="<?php echo $back_link; ?>" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-black transition group">
                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-black group-hover:text-white transition">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path></svg>
                </div>
                <?php echo $back_text; ?>
            </a>
        </div>

        <div class="max-w-4xl mx-auto px-4 text-center mb-10">
            <h1 class="font-serif text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                <?php echo htmlspecialchars($article['title']); ?>
            </h1>
            <p class="text-lg md:text-xl text-gray-500 max-w-2xl mx-auto leading-relaxed mb-6 font-serif italic">
                <?php echo substr(strip_tags($article['content']), 0, 150); ?>...
            </p>
            <div class="flex items-center justify-center gap-3 text-sm font-bold tracking-wider uppercase">
                <span class="text-red-600"><?php echo htmlspecialchars($article['category']); ?></span>
                <span class="text-gray-300">|</span>
                <span class="text-gray-500"><?php echo $read_time; ?> minute read</span>
            </div>
        </div>

        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <div class="w-full aspect-[21/9] md:aspect-[16/7] overflow-hidden rounded-xl bg-gray-100 shadow-sm">
                <img src="<?php echo $article['image']; ?>" class="w-full h-full object-cover" alt="Hero Image">
            </div>
            <p class="text-center text-xs text-gray-400 mt-3">Picture by Unsplash / <?php echo htmlspecialchars($display_author); ?></p>
        </div>

        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-12 lg:gap-20">

            <article class="lg:w-2/3 prose prose-lg prose-headings:font-sans font-serif max-w-none text-gray-800">
                <span class="float-left text-7xl font-bold font-serif leading-[0.8] mr-3 mt-2 text-black">
                    <?php echo substr(strip_tags($article['content']), 0, 1); ?>
                </span>
                <?php echo nl2br(substr(strip_tags($article['content']), 1)); ?>
            </article>

            <aside class="lg:w-1/3 space-y-10 border-t lg:border-t-0 lg:border-l border-gray-100 pt-10 lg:pt-0 lg:pl-10">
                <div class="space-y-6">
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Published</h4>
                        <p class="font-medium text-gray-900 text-lg">
                            <?php echo date('d F Y', strtotime($article['created_at'])); ?> <br>
                            <span class="text-sm text-gray-500 font-normal"><?php echo date('H:i', strtotime($article['created_at'])); ?> GMT+7</span>
                        </p>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Author</h4>
                        <div class="flex items-center gap-3">
                             <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden border border-gray-100">
                                <?php if($display_avatar): ?>
                                    <img src="<?php echo $display_avatar; ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full bg-black text-white flex items-center justify-center font-bold">
                                        <?php echo substr($display_author, 0, 1); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <p class="font-bold text-gray-900 text-lg"><?php echo htmlspecialchars($display_author); ?></p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h4 class="text-sm font-bold text-black border-b border-black pb-2 mb-6 inline-block">Related Content</h4>
                        <div class="space-y-6">
                            <?php while($row_side = mysqli_fetch_assoc($result_sidebar)): ?>
                            <a href="article.php?id=<?php echo $row_side['id']; ?>&from=<?php echo $from; ?>" class="group flex gap-4 items-start">
                                <div class="w-24 h-16 shrink-0 rounded bg-gray-200 overflow-hidden">
                                    <img src="<?php echo $row_side['image']; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                </div>
                                <div>
                                    <h5 class="font-bold text-gray-900 leading-snug group-hover:text-red-600 transition line-clamp-2">
                                        <?php echo htmlspecialchars($row_side['title']); ?>
                                    </h5>
                                    <span class="text-xs text-gray-400 mt-1 block"><?php echo htmlspecialchars($row_side['category']); ?></span>
                                </div>
                            </a>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </aside>
        </div>

        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 mt-24 pt-12 border-t border-gray-100">
            <div class="flex justify-between items-end mb-8">
                <h3 class="text-2xl font-bold font-sans text-gray-900">More About <?php echo htmlspecialchars($article['category']); ?></h3>
                <a href="blog.php?category=<?php echo urlencode($article['category']); ?>" class="text-red-600 text-sm font-bold hover:underline flex items-center gap-1">
                    Show More <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php while($row_bot = mysqli_fetch_assoc($result_bottom)): ?>

                <?php $bot_author = !empty($row_bot['full_name']) ? $row_bot['full_name'] : $row_bot['author']; ?>

                <a href="article.php?id=<?php echo $row_bot['id']; ?>&from=<?php echo $from; ?>" class="group block">
                    <div class="w-full aspect-[4/3] rounded-lg overflow-hidden bg-gray-100 mb-4">
                        <img src="<?php echo $row_bot['image']; ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                         <span class="text-xs font-bold text-gray-500"><?php echo htmlspecialchars($bot_author); ?></span>
                         <span class="text-xs text-gray-300">|</span>
                         <span class="text-xs text-gray-400"><?php echo date('d M Y', strtotime($row_bot['created_at'])); ?></span>
                    </div>
                    <h4 class="font-bold text-gray-900 leading-tight group-hover:text-red-600 transition">
                        <?php echo htmlspecialchars($row_bot['title']); ?>
                    </h4>
                </a>
                <?php endwhile; ?>
            </div>
        </div>

    </main>

    <?php include 'includes/footer.php'; ?>

</body>
</html>
