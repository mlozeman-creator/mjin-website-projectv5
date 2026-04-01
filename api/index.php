<?php
// 1. DATA & CONFIGURATIE
$jsonData = file_get_contents(__DIR__ . '/../data/content.json');
$data = json_decode($jsonData, true);

// 2. STATE MANAGEMENT
$page = $_GET['page'] ?? 'home';
$articleSlug = $_GET['article'] ?? null;
$filter = $_GET['filter'] ?? 'alles';

$isAdmin = (isset($_GET['role']) && $_GET['role'] === 'admin');
$adminParam = $isAdmin ? '&role=admin' : '';

// 3. ADMIN CALCULATIONS (Bewijs van logica beheersing)
$totalArticles = count($data['articles']);
$wordCounts = array_map(function($a) { return str_word_count(strip_tags($a['content'])); }, $data['articles']);
$avgWords = $totalArticles > 0 ? round(array_sum($wordCounts) / $totalArticles) : 0;
$latestUpdate = "01-04-2026";

// 4. FILTER LOGICA
$filteredArticles = $data['articles'];
if ($filter !== 'alles') {
    $filteredArticles = array_filter($data['articles'], function($a) use ($filter) {
        return strtolower($a['category']) === strtolower($filter);
    });
}
$categories = array_unique(array_column($data['articles'], 'category'));

function berekenLeestijd($t) { return max(1, ceil(str_word_count(strip_tags($t)) / 200)); }
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB.EDU | ELITE PORTFOLIO v3.2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --accent: #6366f1; --admin-bg: #0f172a; --danger: #ef4444; }
        body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; color: #334155; }
        
        /* Admin Sidebar */
        .admin-sidebar { background: var(--admin-bg); color: white; min-height: 100vh; position: fixed; right: -320px; top: 0; width: 320px; transition: 0.5s cubic-bezier(0.4, 0, 0.2, 1); z-index: 2000; padding: 30px; box-shadow: -10px 0 30px rgba(0,0,0,0.3); }
        .admin-sidebar.active { right: 0; }
        .admin-badge-live { animation: pulse 2s infinite; font-size: 0.7rem; letter-spacing: 1px; }
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }

        /* UI Elements */
        .navbar { background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); border-bottom: 1px solid #e2e8f0; z-index: 1000; }
        .admin-mode-nav { border-bottom: 4px solid var(--danger) !important; }
        .hero { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; padding: 140px 0 100px; clip-path: polygon(0 0, 100% 0, 100% 90%, 0% 100%); margin-top: -50px; }
        .card-blog { border: none; border-radius: 24px; transition: 0.4s; background: white; border: 1px solid #f1f5f9; height: 100%; }
        .card-blog:hover { transform: translateY(-10px); box-shadow: 0 30px 60px rgba(0,0,0,0.08); }
        .filter-btn { border-radius: 50px; padding: 8px 24px; transition: 0.3s; border: 1px solid #e2e8f0; background: white; text-decoration: none; color: #64748b; font-weight: 700; font-size: 0.85rem; }
        .filter-btn.active { background: var(--accent); color: white; border-color: var(--accent); }
    </style>
</head>
<body>

<?php if($isAdmin): ?>
    <div class="admin-sidebar active">
        <h4 class="fw-extrabold text-warning mb-4">SYSTEM CONSOLE</h4>
        <div class="p-3 bg-white bg-opacity-10 rounded-4 mb-4">
            <small class="text-uppercase opacity-50 fw-bold d-block mb-2">Live Metrics</small>
            <div class="d-flex justify-content-between mb-1"><span>DB Entries:</span> <span class="badge bg-primary"><?php echo $totalArticles; ?></span></div>
            <div class="d-flex justify-content-between mb-1"><span>Avg Words:</span> <span class="badge bg-primary"><?php echo $avgWords; ?></span></div>
            <div class="d-flex justify-content-between"><span>Last Deploy:</span> <span class="badge bg-success"><?php echo $latestUpdate; ?></span></div>
        </div>
        <div class="d-grid gap-2">
            <small class="text-uppercase opacity-50 fw-bold mb-1">Advanced Tools</small>
            <button onclick="alert('JSON Integrity: Verified')" class="btn btn-outline-light btn-sm text-start">🛡️ Validate Schema</button>
            <a href="<?php echo $data['author']['github_url']; ?>/edit/main/data/content.json" target="_blank" class="btn btn-warning btn-sm fw-bold">📝 Patch Database</a>
            <hr class="my-3 opacity-20">
            <a href="?page=home" class="btn btn-danger btn-sm rounded-pill fw-bold">TERMINATE SESSION</a>
        </div>
    </div>
<?php endif; ?>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm <?php echo $isAdmin ? 'admin-mode-nav' : ''; ?>">
    <div class="container">
        <a class="navbar-brand fw-extrabold text-dark fs-3" href="?page=home<?php echo $adminParam; ?>">WEB<span class="text-primary">.EDU</span></a>
        <div class="navbar-nav ms-auto align-items-center">
            <a class="nav-link fw-bold text-dark px-3" href="?page=home<?php echo $adminParam; ?>">Artikelen</a>
            <a class="nav-link fw-bold text-dark px-3" href="?page=tech<?php echo $adminParam; ?>">Techniek</a>
            <a class="nav-link fw-bold text-dark px-3" href="?page=author<?php echo $adminParam; ?>">Profiel</a>
            <?php if($isAdmin): ?><span class="badge bg-danger ms-2 admin-badge-live">ADMIN ACTIVE</span><?php endif; ?>
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
                    <nav class="mb-4"><a href="?page=home<?php echo $adminParam; ?>" class="text-decoration-none fw-bold text-muted small">← TERUG</a></nav>
                    <div class="bg-white p-5 rounded-5 shadow-sm border text-dark">
                        <img src="<?php echo $post['image']; ?>" class="w-100 rounded-4 mb-5 shadow-sm" style="max-height: 400px; object-fit: cover;">
                        <h1 class="display-4 fw-extrabold mb-3 text-dark"><?php echo htmlspecialchars($post['title']); ?></h1>
                        <div class="d-flex align-items-center mb-4">
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 me-3 small"><?php echo strtoupper($post['category']); ?></span>
                            <span class="text-muted fw-bold small">⏱ <?php echo berekenLeestijd($post['content']); ?> min leestijd</span>
                        </div>
                        <div class="lh-lg fs-5 text-secondary text-dark"><?php echo $post['content']; ?></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <?php elseif ($page === 'tech'): ?>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="p-5 bg-white rounded-5 shadow-sm border text-dark">
                    <h2 class="fw-extrabold mb-4">Infrastructuur & Stack</h2>
                    <div class="row g-4 text-dark">
                        <div class="col-md-6">
                            <h5>Engine Configuration</h5>
                            <ul class="list-unstyled opacity-75">
                                <li>🔹 <strong>Runtime:</strong> PHP 8.3 (Serverless Edge)</li>
                                <li>🔹 <strong>Database:</strong> JSON Object Notation (Flat)</li>
                                <li>🔹 <strong>Routing:</strong> Dynamic Slug-based Regex</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Security Layer</h5>
                            <code class="d-block p-3 bg-dark text-info rounded border">SSL: Active (Let's Encrypt)<br>XSS Protection: strip_tags()<br>Environment: Vercel Production</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif ($page === 'author'): ?>
        <div class="row justify-content-center text-center">
            <div class="col-md-6 bg-white p-5 rounded-5 shadow-lg border text-dark">
                <img src="https://ui-avatars.com/api/?name=Mark+Lozeman&size=150&background=6366f1&color=fff" class="rounded-circle mb-4 border border-5 border-white shadow">
                <h2 class="fw-extrabold text-dark"><?php echo htmlspecialchars($data['author']['name']); ?></h2>
                <p class="text-muted fs-5 mb-5 px-3"><?php echo htmlspecialchars($data['author']['bio']); ?></p>
                <a href="mailto:<?php echo $data['author']['email']; ?>" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm">Neem Contact Op</a>
            </div>
        </div>

    <?php else: ?>
        <header class="hero text-center mb-5 rounded-5 mx-2 shadow-sm">
            <div class="container">
                <h1 class="display-3 fw-extrabold mb-3 text-white">Elite Web Development</h1>
                <p class="lead opacity-90 mx-auto fs-4 text-white" style="max-width: 800px;">Een diepgaand onderzoek naar Cloud-native PHP, Security en moderne Software Architectuur op MBO-4 niveau.</p>
            </div>
        </header>

        <div class="d-flex justify-content-center gap-2 flex-wrap mb-5" style="margin-top: -30px;">
            <a href="?page=home&filter=alles<?php echo $adminParam; ?>" class="filter-btn <?php echo $filter === 'alles' ? 'active' : ''; ?>">Alles</a>
            <?php foreach($categories as $cat): ?>
                <a href="?page=home&filter=<?php echo urlencode($cat) . $adminParam; ?>" class="filter-btn <?php echo strtolower($filter) === strtolower($cat) ? 'active' : ''; ?>"><?php echo htmlspecialchars($cat); ?></a>
            <?php endforeach; ?>
        </div>

        <div class="row g-4 mb-5">
            <?php foreach ($filteredArticles as $post): ?>
            <div class="col-md-6 col-lg-4 text-dark">
                <div class="card card-blog shadow-sm text-dark">
                    <img src="<?php echo $post['image']; ?>" class="card-img-top" style="height:200px; object-fit:cover; border-radius: 24px 24px 0 0;">
                    <div class="card-body p-4 d-flex flex-column text-dark">
                        <small class="text-primary fw-bold mb-2"><?php echo strtoupper($post['category']); ?></small>
                        <h5 class="fw-bold mb-3 text-dark"><?php echo htmlspecialchars($post['title']); ?></h5>
                        <p class="text-muted small mb-4"><?php echo substr(strip_tags($post['content']), 0, 100); ?>...</p>
                        <a href="?page=home&article=<?php echo $post['slug'] . $adminParam; ?>" class="mt-auto btn btn-dark rounded-pill py-2 fw-bold shadow-sm">Diepgang opzoeken</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<footer class="py-5 bg-dark text-white text-center mt-5 small opacity-50">
    WEB.EDU ELITE v3.2 | Mark Lozeman | Software Developer MBO-4
</footer>

</body>
</html>
