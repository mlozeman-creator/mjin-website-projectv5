<nav class="navbar navbar-expand-lg fixed-top shadow-sm <?php echo $isAdmin ? 'border-bottom border-danger border-3' : ''; ?>">
    <div class="container">
        <a class="navbar-brand fw-extrabold text-dark" href="?page=home<?php echo $adminParam; ?>">
            WEB.EDU <?php if($isAdmin): ?><span class="badge bg-danger ms-2">PARTNERHUB</span><?php endif; ?>
        </a>
        </div>
</nav>

<?php if ($page === 'tech'): ?>
    <div class="row g-4">
        <div class="col-md-<?php echo $isAdmin ? '8' : '12'; ?>">
            <div class="p-5 rounded-4 shadow-sm border bg-white h-100">
                <h4 class="fw-bold text-primary">Systeem Architectuur</h4>
                <p>Dit platform draait op een serverless PHP-runtime via Vercel Edge.</p>
                <div class="bg-light p-3 rounded font-monospace small">
                    git add . && git commit -m "Update" && git push
                </div>
            </div>
        </div>

        <?php if($isAdmin): ?>
        <div class="col-md-4">
            <div class="p-4 rounded-4 shadow-sm border bg-dark text-white h-100">
                <h5 class="fw-bold text-warning mb-3">🛠 Admin Console</h5>
                <ul class="list-unstyled small">
                    <li class="mb-2">📄 Artikelen: <strong><?php echo count($data['articles']); ?></strong></li>
                    <li class="mb-2">⏱ Gem. Leestijd: <strong>5.2 min</strong></li>
                    <li class="mb-2">📦 Runtime: <strong>PHP <?php echo phpversion(); ?></strong></li>
                </ul>
                <hr class="border-secondary">
                <div class="d-grid gap-2">
                    <a href="https://github.com/mlozeman-creator/mijn-website-projectv5/edit/main/data/content.json" target="_blank" class="btn btn-warning btn-sm fw-bold">Bewerk JSON Database</a>
                    <a href="/api/index.php?format=json" class="btn btn-outline-light btn-sm">Bekijk Raw API Output</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

<?php elseif ($page === 'home' && !$articleSlug): ?>
    <div class="row g-4">
        <?php foreach ($filteredArticles as $post): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                    <img src="<?php echo $post['image']; ?>" class="card-img-top" style="height:180px; object-fit:cover;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold"><?php echo $post['title']; ?></h5>
                        <a href="?page=home&article=<?php echo $post['slug'] . $adminParam; ?>" class="btn btn-outline-primary btn-sm rounded-pill mt-3">Lezen</a>
                        
                        <?php if($isAdmin): ?>
                            <div class="mt-3 pt-3 border-top">
                                <a href="https://github.com/..." class="text-danger small fw-bold text-decoration-none">⚙️ Beheer Artikel #<?php echo $post['id']; ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
