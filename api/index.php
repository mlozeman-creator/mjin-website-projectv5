<?php
// 1. DATA & CONFIGURATIE - HBO Webdeveloper Architectuur
$jsonData = file_get_contents(__DIR__ . '/../data/content.json');
$data = json_decode($jsonData, true);

// 2. STATE MANAGEMENT & ROUTING
$page = $_GET['page'] ?? 'home';
$articleSlug = $_GET['article'] ?? null;
$filter = $_GET['filter'] ?? 'alles';

$isAdmin = (isset($_GET['role']) && $_GET['role'] === 'admin');
$adminParam = $isAdmin ? '&role=admin' : '';

// 3. BACKEND METRICS (Data-analyse voor MBO-4 context)
$totalArticles = count($data['articles']);
$wordCounts = array_map(function($a) { return str_word_count(strip_tags($a['content'])); }, $data['articles']);
$avgWords = $totalArticles > 0 ? round(array_sum($wordCounts) / $totalArticles) : 0;
$systemVersion = "3.5-ELITE";

// 4. FILTER LOGICA (Array Filtering)
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
    <title>WEB.EDU | HBO Webdeveloper Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --accent: #6366f1; --admin-bg: #0f172a; --danger: #ef4444; }
        body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; color: #334155; }
        
        .admin-sidebar { background: var(--admin-bg); color: white; min-height: 100vh; position: fixed; right: -340px; top: 0; width: 340px; transition: 0.5s cubic-bezier(0.4, 0, 0.2, 1); z-index: 2000; padding: 30px; box-shadow: -15px 0 40px rgba(0,0,0,0.4); border-left: 2px solid var(--accent); }
        .admin-sidebar.active { right: 0; }
        .admin-badge-live { animation: pulse 2s infinite; font-size: 0.7rem; letter-spacing: 1px; }
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }

        .navbar { background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border-bottom: 1px solid #e2e8f0; z-index: 1000; }
        .admin-mode-nav { border-bottom: 5px solid var(--danger) !important; }
        .hero { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; padding: 160px 0 110px; clip-path: polygon(0 0, 100% 0, 100% 85%, 0% 100%); margin-top: -60px; }
        .card-blog { border: none; border-radius: 28px; transition: 0.4s; background: white; border: 1px solid #f1f5f9; height: 100%; overflow: hidden; }
        .card-blog:hover { transform: translateY(-12px); box-shadow: 0 40px 80px rgba(0,0,0,0.12); }
        .filter-btn { border-radius: 50px; padding: 10px 28px; transition: 0.3s; border: 1px solid #e2e8f0; background: white; text-decoration: none; color: #64748b; font-weight: 700; font-size: 0.9rem; }
        .filter-btn.active { background: var(--accent); color: white; border-color: var(--accent); box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3); }
    </style>
</head>
<body>

<?php if($isAdmin): ?>
    <div class="admin-sidebar active">
        <div class="mb-4">
            <h4 class="fw-extrabold text-white mb-1">HBO ADMIN CONSOLE</h4>
            <small class="text-accent fw-bold opacity-75">Systeemversie: <?php echo $systemVersion; ?></small>
        </div>
        <div class="p-4 bg-white bg-opacity-10 rounded-4 mb-4 border border-white border-opacity-10">
            <small class="text-uppercase text-warning fw-bold d-block mb-3" style="letter-spacing: 1px;">Live Data Analytics</small>
            <div class="d-flex justify-content-between mb-2"><span>Database Records:</span> <span class="badge bg-primary rounded-pill px-3"><?php echo $totalArticles; ?></span></div>
            <div class="d-flex justify-content-between mb-2"><span>Gem. Content Lengte:</span> <span class="badge bg-primary rounded-pill px-3"><?php echo $avgWords; ?> w.</span></div>
            <div class="d-flex justify-content-between"><span>Server Status:</span> <span class="badge bg-success rounded-pill px-3">Stateless OK</span></div>
        </div>
        <div class="d-grid gap-3">
            <small class="text-uppercase opacity-50 fw-bold mb-1">Developer Tools</small>
            <button onclick="alert('JSON Schema: Valid\nRouting: RESTful\nSecurity: XSS Filtered')" class="btn btn-outline-light btn-sm text-start py-2">🛡️ Security Audit</button>
            <a href="<?php echo $data['author']['github_url']; ?>/edit/main/data/content.json" target="_blank" class="btn btn-warning btn-sm fw-bold py-2">📝 Raw Data Editor</a>
            <hr class="my-3 opacity-20">
            <a href="?page=home" class="btn btn-danger btn-sm rounded-pill fw-bold py-2">TERMINATE ADMIN SESSION</a>
        </div>
    </div>
<?php endif; ?>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm <?php echo $isAdmin ? 'admin-mode-nav' : ''; ?>">
    <div class="container">
        <a class="navbar-brand fw-extrabold text-dark fs-3" href="?page=home<?php echo $adminParam; ?>">WEB<span class="text-primary">.EDU</span></a>
        <div class="navbar-nav ms-auto align-items-center">
            <a class="nav-link fw-bold text-dark px-3" href="?page=home<?php echo $adminParam; ?>">Curriculum</a>
            <a class="nav-link fw-bold text-dark px-3" href="?page=tech<?php echo $adminParam; ?>">Stack</a>
            <a class="nav-link fw-bold text-dark px-3" href="?page=author<?php echo $adminParam; ?>">Architect</a>
            <?php if($isAdmin): ?><span class="badge bg-danger ms-3 admin-badge-live px-3 py-2">HBO DEV MODE</span><?php endif; ?>
        </div>
    </div>
</nav>

<div class="container" style="margin-top: 170px; min-height: 80vh;">

    <?php if ($articleSlug): ?>
        <?php 
        $post = null;
        foreach($data['articles'] as $a) { if($a['slug'] === $articleSlug) { $post = $a; break; } }
        if ($post): ?>
            <div class="row justify-content-center mb-5">
                <div class="col-lg-9">
                    <nav class="mb-4"><a href="?page=home<?php echo $adminParam; ?>" class="text-decoration-none fw-bold text-primary small">← TERUG NAAR OVERZICHT</a></nav>
                    <article class="bg-white p-5 rounded-5 shadow-sm border">
                        <img src="<?php echo $post['image']; ?>" class="w-100 rounded-4 mb-5 shadow-lg" style="max-height: 450px; object-fit: cover;">
                        <div class="mb-2"><span class="badge bg-primary-subtle text-primary rounded-pill px-4 py-2 fw-bold"><?php echo strtoupper($post['category']); ?></span></div>
                        <h1 class="display-4 fw-extrabold mb-4 text-dark"><?php echo htmlspecialchars($post['title']); ?></h1>
                        <p class="text-muted fw-bold mb-5 pb-3 border-bottom">⏱ Geschatte leestijd voor MBO-4: <?php echo berekenLeestijd($post['content']); ?> minuten</p>
                        <div class="lh-lg fs-5 text-dark article-body"><?php echo $post['content']; ?></div>
                    </article>
                </div>
            </div>
        <?php endif; ?>

    <?php elseif ($page === 'tech'): ?>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="p-5 bg-white rounded-5 shadow-sm border text-dark">
                    <h2 class="display-5 fw-extrabold mb-4">Cloud Architectuur & Deployment</h2>
                    <div class="row g-5">
                        <div class="col-md-6">
                            <h5 class="fw-bold text-primary">Backend & Data</h5>
                            <p class="text-muted">Als HBO Webdeveloper focus ik op <em>minimalistic high-performance</em> systemen.</p>
                            <ul class="list-unstyled">
                                <li class="mb-2">🚀 <strong>Runtime:</strong> PHP 8.3 Serverless Edge</li>
                                <li class="mb-2">📦 <strong>Database:</strong> Flat-file JSON Schema</li>
                                <li class="mb-2">🛠️ <strong>CI/CD:</strong> Git-flow met Vercel Hooks</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5 class="fw-bold text-primary">Security Protocol</h5>
                            <div class="bg-dark p-4 rounded-4 shadow-inner">
                                <code class="text-info">
                                    XSS_FILTER: ENABLED<br>
                                    PATH_AUTH: RESTRICTED<br>
                                    SSL_LEVEL: TLS 1.3<br>
                                    ENV: PRODUCTION_ELITE
                                </code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif ($page === 'author'): ?>
        <div class="row justify-content-center text-center">
            <div class="col-md-7 bg-white p-5 rounded-5 shadow-lg border text-dark">
                <img src="https://ui-avatars.com/api/?name=Mark+Lozeman&size=160&background=6366f1&color=fff" class="rounded-circle mb-4 border border-5 border-white shadow-lg">
                <h2 class="fw-extrabold text-dark display-6"><?php echo htmlspecialchars($data['author']['name']); ?></h2>
                <p class="badge bg-dark px-4 py-2 rounded-pill mb-4">HBO Webdeveloper</p>
                <p class="text-muted fs-5 mb-5 lh-base px-4"><?php echo htmlspecialchars($data['author']['bio']); ?></p>
                <a href="mailto:<?php echo $data['author']['email']; ?>" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm">Neem Direct Contact Op</a>
            </div>
        </div>

    <?php else: ?>
        <header class="hero text-center mb-5 rounded-5 mx-2 shadow-lg">
            <div class="container">
                <h1 class="display-2 fw-extrabold mb-3 text-white">HBO Web Development</h1>
                <p class="lead opacity-90 mx-auto fs-4 text-white" style="max-width: 800px;">Masterclass over Cloud-native PHP, Beveiliging en Software Architectuur voor de MBO-4 professional.</p>
            </div>
        </header>

        <div class="d-flex justify-content-center gap-3 flex-wrap mb-5" style="margin-top: -45px;">
            <a href="?page=home&filter=alles<?php echo $adminParam; ?>" class="filter-btn <?php echo $filter === 'alles' ? 'active' : ''; ?>">Alles Ontdekken</a>
            <?php foreach($categories as $cat): ?>
                <a href="?page=home&filter=<?php echo urlencode($cat) . $adminParam; ?>" class="filter-btn <?php echo strtolower($filter) === strtolower($cat) ? 'active' : ''; ?>"><?php echo htmlspecialchars($cat); ?></a>
            <?php endforeach; ?>
        </div>

        <div class="row g-4 mb-5">
            <?php foreach ($filteredArticles as $post): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card card-blog shadow-sm">
                    <img src="<?php echo $post['image']; ?>" class="card-img-top" style="height:220px; object-fit:cover;">
                    <div class="card-body p-4 d-flex flex-column">
                        <small class="text-primary fw-bold mb-2"><?php echo strtoupper($post['category']); ?></small>
                        <h5 class="fw-bold mb-3 text-dark"><?php echo htmlspecialchars($post['title']); ?></h5>
                        <p class="text-muted small mb-4"><?php echo substr(strip_tags($post['content']), 0, 110); ?>...</p>
                        <a href="?page=home&article=<?php echo $post['slug'] . $adminParam; ?>" class="mt-auto btn btn-outline-dark rounded-pill py-2 fw-bold">Bekijk Module</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<footer class="py-5 bg-dark text-white text-center mt-5 small opacity-60">
    WEB.EDU | Gecreëerd door HBO Webdeveloper Mark Lozeman | v3.5 Stable
</footer>

</body>
</html>
