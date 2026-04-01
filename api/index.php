<?php
// 1. DATA INLADEN
$jsonData = file_get_contents(__DIR__ . '/../data/content.json');
$data = json_decode($jsonData, true);

// 2. STATE MANAGEMENT (Eerst definiëren om errors te voorkomen!)
$page = $_GET['page'] ?? 'home';
$articleSlug = $_GET['article'] ?? null;
$filter = $_GET['filter'] ?? 'alles';

// Check voor Admin-rol (Partnerhub)
$isAdmin = (isset($_GET['role']) && $_GET['role'] === 'admin');
$adminParam = $isAdmin ? '&role=admin' : '';

// 3. LOGICA: ARTIKELEN FILTEREN
$filteredArticles = $data['articles'];
if ($filter !== 'alles') {
    $filteredArticles = array_filter($data['articles'], function($a) use ($filter) {
        return strtolower($a['category']) === strtolower($filter);
    });
}
$categories = array_unique(array_column($data['articles'], 'category'));

// 4. HELPER FUNCTIES
function berekenLeestijd($t) { 
    return max(1, ceil(str_word_count(strip_tags($t)) / 200)); 
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB.EDU | <?php echo htmlspecialchars($data['author']['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --accent: #6366f1; --dark: #0f172a; --admin: #ef4444; }
        body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; color: #334155; }
        .navbar { background: rgba(255,255,255,0.85); backdrop-filter: blur(15px); border-bottom: 1px solid #e2e8f0; }
        .admin-border { border-bottom: 3px solid var(--admin) !important; }
        .hero { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; padding: 140px 0 100px; clip-path: polygon(0 0, 100% 0, 100% 90%, 0% 100%); margin-top: -50px; }
        .card-blog { border: none; border-radius: 24px; transition: 0.4s; background: white; border: 1px solid #f1f5f9; height: 100%; }
        .card-blog:hover { transform: translateY(-10px); box-shadow: 0 30px 60px rgba(0,0,0,0.08); }
        .filter-btn { border-radius: 50px; padding: 8px 24px; transition: 0.3s; border: 1px solid #e2e8f0; background: white; text-decoration: none; color: #64748b; font-weight: 700; }
        .filter-btn.active { background: var(--accent); color: white; border-color: var(--accent); }
    </style>
</head>
<body>

<?php if($isAdmin): ?>
<div class="bg-dark text-white py-2 small text-center fw-bold fixed-top" style="z-index: 2000;">
    🚀 PARTNERHUB ACTIEF | <a href="?page=home" class="text-danger text-decoration-none">LOGOUT</a>
</div>
<?php endif; ?>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm <?php echo $isAdmin ? 'admin-border mt-4' : ''; ?>">
    <div class="container">
        <a class="navbar-brand fw-extrabold text-dark fs-3" href="?page=home<?php echo $adminParam; ?>">WEB<span class="text-primary">.EDU</span></a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link fw-bold text-dark" href="?page=home<?php echo $adminParam; ?>">Artikelen</a>
            <a class="nav-link fw-bold text-dark" href="?page=tech<?php echo $adminParam; ?>">Techniek</a>
            <a class="nav-link fw-bold text-dark" href="?page=author<?php echo $adminParam; ?>">Over Mark</a>
        </div>
    </div>
</nav>

<div class="container" style="margin-top: 140px;">

    <?php if ($articleSlug): ?>
        <?php 
        $post = null;
        foreach($data['articles'] as $a) { if($a['slug'] === $articleSlug) { $post = $a; break; } }
        if ($post): ?>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <nav class="mb-4"><a href="?page=home<?php echo $adminParam; ?>" class="text-decoration-none fw-bold text-muted">← TERUG</a></nav>
                    <div class="bg-white p-5 rounded-5 shadow-sm border">
                        <img src="<?php echo $post['image']; ?>" class="w-100 rounded-4 mb-5 shadow-sm" style="max-height: 400px; object-fit: cover;">
                        <h1 class="display-4 fw-extrabold mb-3"><?php echo htmlspecialchars($post['title']); ?></h1>
                        <p class="text-primary fw-bold mb-4">Leestijd: <?php echo berekenLeestijd($post['content']); ?> min</p>
                        <div class="lh-lg fs-5 text-secondary" style="white-space: pre-line;"><?php echo htmlspecialchars($post['content']); ?></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <?php elseif ($page === 'tech'): ?>
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="fw-extrabold mb-4">De Architectuur</h1>
                <div class="p-5 bg-white rounded-5 shadow-sm border">
                    <p class="fs-5">Workflow uit <code>README.md</code>:</p>
                    <div class="bg-light p-3 rounded font-monospace text-primary mb-3">git add . && git commit -m "Update" && git push</div>
                    <?php if($isAdmin): ?>
                        <div class="mt-4 p-3 bg-dark text-warning rounded-4">
                            <h5 class="fw-bold">Admin Console</h5>
                            <p class="small mb-0">PHP Version: <?php echo phpversion(); ?> | Items: <?php echo count($data['articles']); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <?php elseif ($page === 'author'): ?>
        <div class="row justify-content-center text-center">
            <div class="col-md-6 bg-white p-5 rounded-5 shadow-lg border">
                <img src="https://ui-avatars.com/api/?name=<?php echo $data['author']['avatar_name']; ?>&size=150&background=6366f1&color=fff" class="rounded-circle mb-4 border border-5 border-white shadow">
                <h2 class="fw-extrabold"><?php echo htmlspecialchars($data['author']['name']); ?></h2>
                <p class="text-muted fs-5"><?php echo htmlspecialchars($data['author']['bio']); ?></p>
            </div>
        </div>

    <?php else: ?>
        <header class="hero text-center mb-5 rounded-5">
            <div class="container">
                <h1 class="display-3 fw-extrabold text-white">Modern Webonderwijs</h1>
                <p class="lead text-white opacity-75">Vibe Coding Elite v2.0</p>
            </div>
        </header>

        <section class="mb-5 text-center">
            <div class="d-flex justify-content-center gap-2 flex-wrap" style="margin-top: -30px;">
                <a href="?page=home&filter=alles<?php echo $adminParam; ?>" class="filter-btn <?php echo $filter === 'alles' ? 'active' : ''; ?>">Alles</a>
                <?php foreach($categories as $cat): ?>
                    <a href="?page=home&filter=<?php echo urlencode($cat) . $adminParam; ?>" class="filter-btn <?php echo strtolower($filter) === strtolower($cat) ? 'active' : ''; ?>"><?php echo htmlspecialchars($cat); ?></a>
                <?php endforeach; ?>
            </div>
        </section>

        <div class="row g-4">
            <?php foreach ($filteredArticles as $post): ?>
            <div class="col-md-4">
                <div class="card card-blog shadow-sm">
                    <img src="<?php echo $post['image']; ?>" class="card-img-top" style="height:200px; object-fit:cover;">
                    <div class="card-body p-4 d-flex flex-column text-dark">
                        <small class="text-primary fw-bold"><?php echo strtoupper($post['category']); ?></small>
                        <h5 class="fw-bold my-2"><?php echo htmlspecialchars($post['title']); ?></h5>
                        <a href="?page=home&article=<?php echo $post['slug'] . $adminParam; ?>" class="mt-auto btn btn-dark rounded-pill fw-bold">Lees Artikel</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<footer class="py-5 mt-5 bg-dark text-white text-center">
    <p class="opacity-50 small mb-0">WEB.EDU | <?php echo htmlspecialchars($data['author']['name']); ?> | 2026</p>
</footer>

</body>
</html>
