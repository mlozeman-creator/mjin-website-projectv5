<?php
// 1. DATA & CONFIGURATIE
$jsonData = file_get_contents(__DIR__ . '/../data/content.json');
$data = json_decode($jsonData, true);

// 2. STATE MANAGEMENT (Routering & Rollen)
$page = $_GET['page'] ?? 'home';
$articleSlug = $_GET['article'] ?? null; // De 'slug' van het artikel uit de URL
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
        .navbar { background: rgba(255,255,255,0.85); backdrop-filter: blur(15px); border-bottom: 1px solid #e2e8f0; transition: 0.3s; }
        .admin-border { border-bottom: 3px solid var(--admin) !important; }
        .hero { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; padding: 140px 0 100px; clip-path: polygon(0 0, 100% 0, 100% 90%, 0% 100%); }
        .filter-btn { border-radius: 50px; padding: 8px 24px; transition: 0.3s; border: 1px solid #e2e8f0; background: white; text-decoration: none; color: #64748b; font-weight: 700; }
        .filter-btn.active { background: var(--accent); color: white; border-color: var(--accent); }
        .card-blog { border: none; border-radius: 24px; transition: 0.4s; background: white; border: 1px solid #f1f5f9; }
        .card-blog:hover { transform: translateY(-10px); box-shadow: 0 30px 60px rgba(0,0,0,0.08); }
    </style>
</head>
<body>

<?php if($isAdmin): ?>
<div class="bg-dark text-white py-2 small text-center fw-bold fixed-top" style="z-index: 1050;">
    🚀 PARTNERHUB ACTIEF | 
    <a href="https://github.com/mlozeman-creator/mijn-website-projectv5/edit/main/data/content.json" target="_blank" class="text-warning text-decoration-none">BEWERK JSON</a> | 
    <a href="?page=home" class="text-danger text-decoration-none">LOGOUT</a>
</div>
<?php endif; ?>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm <?php echo $isAdmin ? 'admin-border mt-4' : ''; ?>">
    <div class="container">
        <a class="navbar-brand fw-extrabold text-dark fs-3" href="?page=home<?php echo $adminParam; ?>">WEB<span class="text-primary">.EDU</span></a>
        <div class="navbar-nav ms-auto align-items-center">
            <a class="nav-link fw-bold px-3 text-dark" href="?page=home<?php echo $adminParam; ?>">Artikelen</a>
            <a class="nav-link fw-bold px-3 text-dark" href="?page=tech<?php echo $adminParam; ?>">Techniek</a>
            <a class="nav-link fw-bold px-3 text-dark" href="?page=author<?php echo $adminParam; ?>">
                <?php echo htmlspecialchars($data['author']['name']); ?> <?php if($isAdmin): ?><span class="badge bg-danger ms-1">Admin</span><?php endif; ?>
            </a>
        </div>
    </div>
</nav>

<div class="container" style="margin-top: <?php echo ($page === 'home' && !$articleSlug) ? '40px' : '160px'; ?>;">

    <?php if ($page === 'home' && !$articleSlug): ?>
        <header class="hero text-center mb-5 rounded-5">
            <div class="container">
                <span class="badge bg-white text-primary px-4 py-2 mb-4 shadow rounded-pill fw-bold">Elite Portfolio v2.0</span>
                <h1 class="display-3 fw-extrabold mb-3 text-white">Modern Webonderwijs</h1>
                <p class="lead opacity-90 mx-auto fs-4 text-white" style="max-width: 700px;">Project van Mark Lozeman over cloud-architectuur.</p>
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

        <div class="row g-4 mb-5">
            <?php foreach ($filteredArticles as $post): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card card-blog h-100">
                    <img src="<?php echo $post['image']; ?>" class="card-img-top" style="height:200px; object-fit:cover;" alt="beeld">
                    <div class="card-body p-4 d-flex flex-column">
                        <small class="text-primary fw-bold mb-2"><?php echo strtoupper($post['category']); ?></small>
                        <h4 class="fw-bold mb-3"><?php echo htmlspecialchars($post['title']); ?></h4>
                        <p class="text-muted small"><?php echo substr(strip_tags($post['content']), 0, 100); ?>...</p>
                        
                        <a href="?page=home&article=<?php echo $post['slug'] . $adminParam; ?>" class="mt-auto btn btn-dark rounded-pill py-2 fw-bold">Lees Artikel</a>
                        
                        <?php if($isAdmin): ?>
                            <div class="mt-3 pt-2 border-top text-center">
                                <small class="text-danger fw-bold">ID: #<?php echo $post['id']; ?> | <a href="https://github.com/..." class="text-danger">Edit</a></small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    <?php elseif ($articleSlug): ?>
        <?php 
            $post = null;
            foreach($data['articles'] as $a) { 
                if($a['slug'] === $articleSlug) { 
                    $post = $a; 
                    break; 
                } 
            }
            if ($post): 
        ?>
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8">
                    <nav class="mb-4"><a href="?page=home<?php echo $adminParam; ?>" class="text-decoration-none fw-bold text-muted">← TERUG NAAR OVERZICHT</a></nav>
                    <div class="bg-white p-5 rounded-5 shadow-sm border">
                        <img src="<?php echo $post['image']; ?>" class="w-100 rounded-4 mb-5 shadow-sm" style="max-height: 400px; object-fit: cover;">
                        <h1 class="display-4 fw-extrabold mb-4 text-dark"><?php echo htmlspecialchars($post['title']); ?></h1>
                        <p class="text-primary fw-bold mb-4">Leestijd: <?php echo berekenLeestijd($post['content']); ?> minuten</p>
                        <div class="lh-lg fs-5 text-secondary" style="white-space: pre-line;"><?php echo htmlspecialchars($post['content']); ?></div>
                        <div class="mt-5 pt-4 border-top">
                            <a href="?page=home<?php echo $adminParam; ?>" class="btn btn-dark rounded-pill px-5 py-3 fw-bold shadow">Terug naar Home</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <h2>Oeps! Artikel niet gevonden.</h2>
                <a href="?page=home<?php echo $adminParam; ?>" class="btn btn-primary mt-3">Terug naar de start</a>
            </div>
        <?php endif; ?>

    <?php elseif ($page === 'tech'): ?>
        <div class="row g-4 justify-content-center mb-5">
            <div class="col-lg-<?php echo $isAdmin ? '8' : '10'; ?>">
                <div class="p-5 rounded-5 shadow-sm border bg-white">
                    <h2 class="fw-extrabold mb-4">De Architectuur</h2>
                    <p class="fs-5">Workflow beschreven in <code>README.md</code>:</p>
                    <div class="bg-light p-4 rounded-4 border font-monospace text-primary mb-4">
                        git add . && git commit -m "Update" && git push origin main
                    </div>
                    <p>Dit triggert een automatische build op <strong>Vercel Edge</strong>.</p>
                </div>
            </div>
            <?php if($isAdmin): ?>
            <div class="col-lg-4">
                <div class="p-5 rounded-5 shadow-sm border bg-dark text-white">
                    <h4 class="fw-bold text-warning mb-4">Partnerhub Console</h4>
                    <ul class="list-unstyled fw-bold opacity-75">
                        <li class="mb-3">🔥 Status: Online</li>
                        <li class="mb-3">📦 Data: <?php echo count($data['articles']); ?> items</li>
                        <li class="mb-3">🚀 PHP: <?php echo phpversion(); ?></li>
                    </ul>
                    <a href="https://github.com/..." class="btn btn-warning w-100 fw-bold rounded-pill">Manage JSON</a>
                </div>
            </div>
            <?php endif; ?>
        </div>

    <?php elseif ($page === 'author'): ?>
        <div class="row justify-content-center text-center">
            <div class="col-md-6 bg-white p-5 rounded-5 shadow-lg border">
                <img src="https://ui-avatars.com/api/?name=<?php echo $data['author']['avatar_name']; ?>&size=150&background=6366f1&color=fff" class="rounded-circle mb-4 border border-5 border-white shadow">
                <h2 class="fw-extrabold text-dark"><?php echo htmlspecialchars($data['author']['name']); ?></h2>
                <p class="text-primary fw-bold fs-5">Software Developer & Architect</p>
                <p class="text-muted fs-5 mb-5"><?php echo htmlspecialchars($data['author']['bio']); ?></p>
                <div class="d-grid gap-2 d-md-block">
                    <a href="mailto:<?php echo $data['author']['email']; ?>" class="btn btn-primary px-5 py-3 rounded-pill fw-bold me-md-2 shadow-sm">Email</a>
                    <a href="<?php echo $data['author']['github_url']; ?>" target="_blank" class="btn btn-outline-dark px-5 py-3 rounded-pill fw-bold shadow-sm">GitHub</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<footer class="py-5 text-center mt-5 bg-dark text-white">
    <p class="opacity-50 small mb-0">WEB.EDU ELITE | <?php echo htmlspecialchars($data['author']['name']); ?> | Windesheim © 2026</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
