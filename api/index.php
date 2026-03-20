<?php
$jsonData = file_get_contents(__DIR__ . '/../data/content.json');
$data = json_decode($jsonData, true);
$page = $_GET['page'] ?? 'home';
$articleSlug = $_GET['article'] ?? null;
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web-Edu | Technisch Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --accent: #2563eb; --dark: #0f172a; }
        body { background-color: #f8fafc; font-family: 'Segoe UI', system-ui, sans-serif; }
        .navbar { background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); border-bottom: 1px solid #e2e8f0; }
        .hero { background: var(--dark); color: white; padding: 120px 0 80px; clip-path: ellipse(150% 100% at 50% 0%); }
        .card-blog { border: none; border-radius: 20px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); transition: 0.4s; }
        .card-blog:hover { transform: translateY(-10px); }
        .code-block { background: #1e293b; color: #f8fafc; padding: 20px; border-radius: 12px; font-family: monospace; overflow-x: auto; }
        .badge-tech { background: #e2e8f0; color: #475569; border-radius: 6px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="?page=home">WEB-EDU</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="?page=home">Blog</a>
            <a class="nav-link" href="?page=tech">Techniek</a>
            <a class="nav-link" href="?page=author">Over Mij</a>
        </div>
    </div>
</nav>

<?php if ($page === 'home' && !$articleSlug): ?>
<header class="hero text-center mb-5">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Educatief Webblog</h1>
        <p class="lead opacity-75">Gerealiseerd met PHP, GitHub & Vercel CI/CD</p>
    </div>
</header>
<?php endif; ?>

<div class="container" style="margin-top: <?php echo ($page === 'home' && !$articleSlug) ? '40px' : '120px'; ?>;">
    
    <?php if ($page === 'home' && !$articleSlug): ?>
        <div class="row g-4">
            <?php foreach ($data['articles'] as $post): ?>
            <div class="col-md-6 col-lg-3">
                <div class="card card-blog h-100 p-3">
                    <div class="card-body d-flex flex-column">
                        <h5 class="fw-bold"><?php echo $post['title']; ?></h5>
                        <p class="text-muted small"><?php echo substr($post['content'], 0, 100); ?>...</p>
                        <a href="?page=home&article=<?php echo $post['slug']; ?>" class="mt-auto btn btn-primary rounded-pill">Lees Artikel</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    <?php elseif ($page === 'tech'): ?>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="fw-bold mb-4">Technisch Dossier</h1>
                <div class="bg-white p-4 rounded-4 shadow-sm mb-4">
                    <h4>Bestandsstructuur</h4>
                    <pre class="code-block">
root/
├── api/
│   └── index.php       <-- De PHP Logic Engine
├── data/
│   └── content.json    <-- De Flat-file Database
└── vercel.json         <-- Infrastructure Configuration</pre>
                </div>
                <div class="bg-white p-4 rounded-4 shadow-sm">
                    <h4>CI/CD Workflow</h4>
                    <p>Dit project gebruikt een automatische pijplijn. Elke <strong>git push</strong> naar GitHub triggert een build op Vercel. De PHP-runtime (versie 0.7.2) zorgt voor de server-side uitvoering in een serverless omgeving.</p>
                </div>
            </div>
        </div>

    <?php elseif ($articleSlug): ?>
        <?php 
        $post = null;
        foreach($data['articles'] as $a) { if($a['slug'] === $articleSlug) { $post = $a; break; } }
        if ($post): ?>
            <div class="row justify-content-center">
                <div class="col-lg-8 bg-white p-5 rounded-4 shadow-sm">
                    <h1 class="fw-bold mb-4"><?php echo $post['title']; ?></h1>
                    <div class="lh-lg text-secondary" style="white-space: pre-line;">
                        <?php echo $post['content']; ?>
                    </div>
                    <a href="?page=home" class="btn btn-outline-primary mt-5 rounded-pill">← Terug</a>
                </div>
            </div>
        <?php endif; ?>

    <?php elseif ($page === 'author'): ?>
        <div class="row justify-content-center text-center">
            <div class="col-md-6 bg-white p-5 rounded-4 shadow-sm">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($data['author']['name']); ?>&size=100&background=2563eb&color=fff" class="rounded-circle mb-3">
                <h2 class="fw-bold"><?php echo $data['author']['name']; ?></h2>
                <p class="text-muted"><?php echo $data['author']['bio']; ?></p>
            </div>
        </div>
    <?php endif; ?>
</div>

<footer class="py-5 text-center text-muted small">
    &copy; <?php echo date('Y'); ?> - MBO Niveau 4 Webdevelopment Opdracht
</footer>

</body>
</html>