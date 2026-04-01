<?php
// 1. DATA INLADEN
$jsonData = file_get_contents(__DIR__ . '/../data/content.json');
$data = json_decode($jsonData, true);

// 2. STATE MANAGEMENT (Routering & Rollen)
$page = $_GET['page'] ?? 'home';
$articleSlug = $_GET['article'] ?? null;
$filter = $_GET['filter'] ?? 'alles';

// Check voor Admin-rol (Adminmode) via URL parameter
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
        body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; color: #334155; overflow-x: hidden; }
        
        .navbar { background: rgba(255,255,255,0.85); backdrop-filter: blur(15px); border-bottom: 1px solid #e2e8f0; z-index: 1000; }
        .admin-border { border-bottom: 3px solid var(--admin) !important; }
        
        .hero { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; padding: 140px 0 100px; clip-path: polygon(0 0, 100% 0, 100% 90%, 0% 100%); margin-top: -50px; }
        .card-blog { border: none; border-radius: 24px; transition: 0.4s; background: white; border: 1px solid #f1f5f9; height: 100%; }
        .card-blog:hover { transform: translateY(-10px); box-shadow: 0 30px 60px rgba(0,0,0,0.08); }
        
        .filter-btn { border-radius: 50px; padding: 8px 24px; transition: 0.3s; border: 1px solid #e2e8f0; background: white; text-decoration: none; color: #64748b; font-weight: 700; }
        .filter-btn.active { background: var(--accent); color: white; border-color: var(--accent); }
        .badge-step { width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 0.8rem; }
    </style>
</head>
<body>

<?php if($isAdmin): ?>
<div class="bg-dark text-white py-2 small text-center fw-bold fixed-top" style="z-index: 2000;">
    🚀 ADMINMODE ACTIEF | <a href="?page=home" class="text-danger text-decoration-none ms-2 text-uppercase">Sessie Beëindigen</a>
</div>
<?php endif; ?>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm <?php echo $isAdmin ? 'admin-border mt-4' : ''; ?>">
    <div class="container">
        <a class="navbar-brand fw-extrabold text-dark fs-3" href="?page=home<?php echo $adminParam; ?>">WEB<span class="text-primary">.EDU</span></a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link fw-bold text-dark px-3" href="?page=home<?php echo $adminParam; ?>">Artikelen</a>
            <a class="nav-link fw-bold text-dark px-3" href="?page=tech<?php echo $adminParam; ?>">Techniek</a>
            <a class="nav-link fw-bold text-dark px-3 d-flex align-items-center" href="?page=author<?php echo $adminParam; ?>">
                <?php echo htmlspecialchars($data['author']['name']); ?> 
                <?php if($isAdmin): ?><span class="badge bg-danger ms-2" style="font-size: 0.6rem;">ADMIN</span><?php endif; ?>
            </a>
        </div>
    </div>
</nav>

<div class="container" style="margin-top: 160px; min-height: 80vh;">

    <?php if ($articleSlug): ?>
        <?php 
        $post = null;
        foreach($data['articles'] as $a) { if($a['slug'] === $articleSlug) { $post = $a; break; } }
        if ($post): ?>
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8">
                    <nav class="mb-4 small"><a href="?page=home<?php echo $adminParam; ?>" class="text-decoration-none fw-bold text-muted">← OVERZICHT</a></nav>
                    <div class="bg-white p-4 p-md-5 rounded-5 shadow-sm border">
                        <img src="<?php echo $post['image']; ?>" class="w-100 rounded-4 mb-5 shadow-sm" style="max-height: 400px; object-fit: cover;">
                        <h1 class="display-4 fw-extrabold mb-3 text-dark"><?php echo htmlspecialchars($post['title']); ?></h1>
                        <div class="d-flex align-items-center mb-4">
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 me-3 small"><?php echo strtoupper($post['category']); ?></span>
                            <span class="text-muted fw-bold small">⏱ <?php echo berekenLeestijd($post['content']); ?> min leestijd</span>
                        </div>
                        <div class="lh-lg fs-5 text-secondary" style="white-space: pre-line;"><?php echo htmlspecialchars($post['content']); ?></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <?php elseif ($page === 'tech'): ?>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-extrabold text-dark">Systeem Architectuur</h1>
                    <p class="lead text-muted">Serverless PHP stack aangedreven door CI/CD</p>
                </div>
                <div class="row g-4 text-dark">
                    <div class="col-md-7">
                        <div class="p-5 bg-white rounded-5 shadow-sm border h-100">
                            <h4 class="fw-bold mb-4 d-flex align-items-center">
                                <span class="badge bg-primary-subtle text-primary me-3 badge-step">01</span> Deployment Pipeline
                            </h4>
                            <p class="text-muted small">Workflow uit <code>README.md</code>:</p>
                            <div class="bg-dark text-info p-4 rounded-4 font-monospace shadow-sm mt-3" style="font-size: 0.85rem;">
                                <span class="text-secondary opacity-50"># Live publicatie</span><br>
                                git add . && git commit -m "Update" && git push origin main
                            </div>
                            <div class="mt-5 d-flex justify-content-between text-center opacity-75">
                                <div><h3 class="mb-0">💻</h3><small class="fw-bold">Mark</small></div>
                                <div class="align-self-center text-primary">➔</div>
                                <div><h3 class="mb-0">🐙</h3><small class="fw-bold">GitHub</small></div>
                                <div class="align-self-center text-primary">➔</div>
                                <div><h3 class="mb-0">⚡</h3><small class="fw-bold">Vercel</small></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="p-5 bg-white rounded-5 shadow-sm border h-100 text-dark">
                            <h4 class="fw-bold mb-4 d-flex align-items-center">
                                <span class="badge bg-success-subtle text-success me-3 badge-step">02</span> Systeem Status
                            </h4>
                            <div class="list-group list-group-flush mb-4">
                                <div class="list-group-item border-0 px-0 d-flex justify-content-between align-items-center text-dark">
                                    <span class="text-muted small">Runtime</span><span class="badge bg-dark rounded-pill">PHP 8.3</span>
                                </div>
                                <div class="list-group-item border-0 px-0 d-flex justify-content-between align-items-center text-dark">
                                    <span class="text-muted small">Database</span><span class="badge bg-dark rounded-pill">JSON v2</span>
                                </div>
                            </div>
                            <?php if($isAdmin): ?>
                                <div class="p-4 bg-primary rounded-4 text-white shadow-sm mt-3 text-center text-dark">
                                    <h6 class="fw-bold mb-2 text-warning small">ADMINMODE DASHBOARD</h6>
                                    <hr class="opacity-25">
                                    <a href="<?php echo $data['author']['github_url']; ?>/edit/main/data/content.json" target="_blank" class="btn btn-light btn-sm w-100 fw-bold text-primary shadow-sm">Manage JSON</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif ($page === 'author'): ?>
        <div class="row justify-content-center text-center">
            <div class="col-md-6 bg-white p-5 rounded-5 shadow-lg border">
                <img src="https://ui-avatars.com/api/?name=<?php echo $data['author']['avatar_name']; ?>&size=150&background=6366f1&color=fff" class="rounded-circle mb-4 border border-5 border-white shadow">
                <h2 class="fw-extrabold text-dark"><?php echo htmlspecialchars($data['author']['name']); ?></h2>
                <p class="text-primary fw-bold fs-5">Software Developer & Architect</p>
                <p class="text-muted fs-5 mb-5 px-3"><?php echo htmlspecialchars($data['author']['bio']); ?></p>
                <a href="mailto:<?php echo $data['author']['email']; ?>" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm">Contact</a>
            </div>
        </div>

    <?php else: ?>
        <header class="hero text-center mb-5 rounded-5 mx-2 shadow-sm">
            <div class="container">
                <span class="badge bg-white text-primary px-4 py-2 mb-4 shadow rounded-pill fw-bold">Portfolio v2.0</span>
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

        <div class="row g-4 mb-5 text-dark">
            <?php foreach ($filteredArticles as $post): ?>
            <div class="col-md-6 col-lg-4 text-dark">
                <div class="card card-blog shadow-sm text-dark">
                    <img src="<?php echo $post['image']; ?>" class="card-img-top" style="height:200px; object-fit:cover;">
                    <div class="card-body p-4 d-flex flex-column text-dark">
                        <small class="text-primary fw-bold mb-2"><?php echo strtoupper($post['category']); ?></small>
                        <h5 class="fw-bold mb-3 text-dark"><?php echo htmlspecialchars($post['title']); ?></h5>
                        <p class="text-muted small mb-4"><?php echo substr(strip_tags($post['content']), 0, 100); ?>...</p>
                        <a href="?page=home&article=<?php echo $post['slug'] . $adminParam; ?>" class="mt-auto btn btn-dark rounded-pill py-2 fw-bold shadow-sm">Lees Artikel</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<footer class="py-5 bg-dark text-white text-center mt-5">
    <div class="container small opacity-50">
        WEB.EDU ELITE | <?php echo htmlspecialchars($data['author']['name']); ?> | 2026
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
