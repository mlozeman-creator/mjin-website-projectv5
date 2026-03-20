<?php
// 1. Data inladen
$jsonData = file_get_contents(__DIR__ . '/../data/content.json');
$data = json_decode($jsonData, true);

// 2. Beheer van de status
$page = $_GET['page'] ?? 'home';
$articleSlug = $_GET['article'] ?? null;
$filter = $_GET['filter'] ?? 'alles';

// 3. NIEUW: Rollenbeheer (Admin check)
$isAdmin = (isset($_GET['role']) && $_GET['role'] === 'admin');

// 4. Dynamische Filter Logica
$filteredArticles = $data['articles'];
if ($filter !== 'alles') {
    $filteredArticles = array_filter($data['articles'], function($a) use ($filter) {
        return strtolower($a['category']) === strtolower($filter);
    });
}
$categories = array_unique(array_column($data['articles'], 'category'));
function berekenLeestijd($t) { return ceil(str_word_count($t) / 200); }
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB.EDU | Mark Lozeman Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --accent: #6366f1; --dark: #0f172a; --admin-color: #ef4444; }
        body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; color: #334155; }
        .navbar { background: rgba(255,255,255,0.85); backdrop-filter: blur(15px); border-bottom: 1px solid #e2e8f0; }
        .hero { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; padding: 160px 0 120px; clip-path: polygon(0 0, 100% 0, 100% 90%, 0% 100%); }
        .filter-btn { border-radius: 50px; padding: 8px 24px; transition: 0.3s; border: 1px solid #e2e8f0; background: white; text-decoration: none; color: #64748b; font-weight: 700; }
        .filter-btn.active { background: var(--accent); color: white; border-color: var(--accent); }
        .card-blog { border: none; border-radius: 28px; transition: 0.4s; background: white; border: 1px solid #f1f5f9; overflow: hidden; }
        .card-blog:hover { transform: translateY(-10px); box-shadow: 0 30px 60px rgba(0,0,0,0.08); }
        .badge-admin { background-color: var(--admin-color); color: white; font-size: 0.65rem; padding: 3px 8px; border-radius: 5px; vertical-align: middle; margin-left: 8px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
        .flow-step { text-align: center; flex: 1; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-extrabold text-dark fs-3" href="?page=home">WEB<span class="text-primary">.EDU</span></a>
        <div class="navbar-nav ms-auto align-items-center">
            <a class="nav-link fw-bold px-3 text-dark" href="?page=home">Artikelen</a>
            <a class="nav-link fw-bold px-3 text-dark" href="?page=tech">De Techniek</a>
            <a class="nav-link fw-bold px-3 text-dark" href="?page=author">
                Mark Lozeman <?php if($isAdmin): ?><span class="badge-admin shadow-sm">Admin</span><?php endif; ?>
            </a>
        </div>
    </div>
</nav>

<?php if ($page === 'home' && !$articleSlug): ?>
<header class="hero text-center text-white">
    <div class="container">
        <span class="badge bg-white text-primary px-4 py-2 mb-4 shadow-lg rounded-pill fw-bold">Vibe Coding Elite v2.0</span>
        <h1 class="display-3 fw-extrabold mb-3 text-white">Modern Webonderwijs</h1>
        <p class="lead opacity-90 mx-auto fs-4 text-white" style="max-width: 700px;">Project van Mark Lozeman over cloud-architectuur.</p>
    </div>
</header>
<section class="container mb-5 text-center">
    <div class="d-flex justify-content-center gap-2 flex-wrap" style="margin-top: -30px;">
        <a href="?page=home&filter=alles" class="filter-btn <?php echo $filter === 'alles' ? 'active' : ''; ?>">Alles</a>
        <?php foreach($categories as $cat): ?>
            <a href="?page=home&filter=<?php echo $cat; ?>" class="filter-btn <?php echo strtolower($filter) === strtolower($cat) ? 'active' : ''; ?>"><?php echo $cat; ?></a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<div class="container" style="margin-top: <?php echo ($page === 'home' && !$articleSlug) ? '20px' : '140px'; ?>;">
    <?php if ($page === 'home' && !$articleSlug): ?>
        <div class="row g-4 mb-5 text-dark">
            <?php foreach ($filteredArticles as $post): ?>
            <div class="col-md-6 col-lg-4 text-dark">
                <div class="card card-blog h-100 shadow-sm text-dark">
                    <img src="<?php echo $post['image']; ?>" class="card-img-top" style="height:200px; object-fit:cover;" alt="beeld">
                    <div class="card-body p-4 d-flex flex-column text-dark">
                        <small class="text-primary fw-bold mb-2"><?php echo $post['category']; ?></small>
                        <h3 class="fw-bold mb-3 text-dark"><?php echo $post['title']; ?></h3>
                        <p class="text-muted mb-4"><?php echo substr($post['content'], 0, 110); ?>...</p>
                        <a href="?page=home&article=<?php echo $post['slug']; ?>" class="mt-auto btn btn-dark rounded-pill py-3 fw-bold">Lees Artikel</a>
                        
                        <?php if($isAdmin): ?>
                            <div class="mt-3 pt-2 border-top">
                                <a href="https://github.com/mlozeman-creator/mijn-website-projectv5/edit/main/data/content.json" target="_blank" class="text-danger small fw-bold text-decoration-none">⚙️ Bewerk JSON</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    <?php elseif ($page === 'tech'): ?>
        <div class="row justify-content-center mb-5 text-dark">
            <div class="col-lg-10">
                <h1 class="fw-extrabold mb-5 display-4 text-center">De Architectuur</h1>
                <div class="row g-4 text-dark">
                    <div class="col-md-8">
                        <div class="p-5 rounded-4 shadow-sm border bg-white h-100">
                            <h4 class="fw-bold mb-4 text-primary">Cloud Publicatie Flow</h4>
                            <p class="fs-5">Dit project gebruikt een geautomatiseerde pijplijn. De techniek is onzichtbaar, het resultaat is direct.</p>
                            <div class="d-flex justify-content-between align-items-center mt-5 p-4 bg-light rounded-4 border">
                                <div class="flow-step"><div>💻</div><small class="fw-bold">Mark</small></div>
                                <div class="text-primary fs-3">➜</div>
                                <div class="flow-step"><div>🐙</div><small class="fw-bold">GitHub</small></div>
                                <div class="text-primary fs-3">➜</div>
                                <div class="flow-step"><div>⚡</div><small class="fw-bold">Vercel</small></div>
                                <div class="text-primary fs-3">➜</div>
                                <div class="flow-step"><div>🌍</div><small class="fw-bold">Live</small></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-5 rounded-4 shadow-sm border bg-white h-100 text-center">
                            <h4 class="fw-bold mb-4 text-primary">Systeem Status</h4>
                            <ul class="list-unstyled fw-bold text-muted fs-5">
                                <li class="mb-3 text-dark">🔥 Status: Online</li>
                                <li class="mb-3 text-dark">📦 Data: JSON 2.0</li>
                                <li class="mb-3 text-dark">🚀 Engine: PHP 8.x</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif ($articleSlug): ?>
        <?php 
        $post = null;
        foreach($data['articles'] as $a) { if($a['slug'] === $articleSlug) { $post = $a; break; } }
        if ($post): ?>
            <div class="row justify-content-center mb-5 text-dark">
                <div class="col-lg-9 text-dark">
                    <nav class="mb-4"><a href="?page=home" class="btn btn-link text-decoration-none fw-bold text-muted p-0">← TERUG</a></nav>
                    <div class="bg-white p-5 rounded-5 shadow-lg border">
                        <img src="<?php echo $post['image']; ?>" class="w-100 rounded-4 mb-5 shadow-sm" style="height: 400px; object-fit: cover;">
                        <h1 class="display-4 fw-extrabold mb-4 text-dark"><?php echo $post['title']; ?></h1>
                        <p class="text-primary fw-bold mb-4">Leestijd: <?php echo berekenLeestijd($post['content']); ?> minuten</p>
                        <div class="lh-lg fs-5 text-secondary" style="white-space: pre-line;"><?php echo $post['content']; ?></div>
                        <div class="mt-5 pt-4 border-top">
                            <a href="?page=home" class="btn btn-dark rounded-pill px-5 py-3 fw-bold">Vorige Pagina</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <?php elseif ($page === 'author'): ?>
        <div class="row justify-content-center text-center text-dark">
            <div class="col-md-6 bg-white p-5 rounded-5 shadow-lg border">
                <img src="https://ui-avatars.com/api/?name=Mark+Lozeman&size=150&background=6366f1&color=fff" class="rounded-circle mb-4 border border-5 border-white shadow">
                <h2 class="fw-extrabold text-dark">Mark Lozeman</h2>
                <p class="text-primary fw-bold fs-5">Software Developer & Architect</p>
                <p class="text-muted fs-5 mb-5"><?php echo $data['author']['bio']; ?></p>
                <div class="d-grid gap-2 d-md-block">
                    <a href="mailto:mark.lozeman@windesheim.nl" class="btn btn-primary px-5 py-3 rounded-pill fw-bold me-md-2 shadow-sm">Email</a>
                    <a href="https://github.com/mlozeman-creator/mijn-website-projectv5" class="btn btn-outline-dark px-5 py-3 rounded-pill fw-bold shadow-sm">GitHub</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<footer class="py-5 text-center mt-5 bg-dark text-white text-center">
    <p class="opacity-50 small mb-0 text-white">WEB.EDU ELITE | Mark Lozeman | Windesheim © 2026</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
