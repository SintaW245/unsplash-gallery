<?php
// public/index.php
require_once __DIR__ . '/../api/UnsplashAPI.php';

$api = new UnsplashAPI();
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$photos = $api->getNaturePhotos($page, 20);

$pageTitle = "Nature Photos Gallery";
include __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="hero">
        <h1>🌿 Explore Beautiful Nature</h1>
        <p>Discover stunning nature photography from around the world</p>
    </div>

    <?php if (isset($photos['error'])): ?>
        <div class="error-message">
            <p>Error loading photos: <?php echo htmlspecialchars($photos['message']); ?></p>
        </div>
    <?php else: ?>
        <div class="photo-grid">
            <?php foreach ($photos['results'] as $photo): ?>
                <div class="photo-card">
                    <a href="detail.php?id=<?php echo $photo['id']; ?>">
                        <img 
                            src="<?php echo $photo['urls']['small']; ?>" 
                            alt="<?php echo htmlspecialchars($photo['alt_description'] ?? 'Nature photo'); ?>"
                            loading="lazy"
                        >
                        <div class="photo-overlay">
                            <div class="photo-info">
                                <p class="photographer">
                                    📷 <?php echo htmlspecialchars($photo['user']['name']); ?>
                                </p>
                                <p class="stats">
                                    ❤️ <?php echo number_format($photo['likes']); ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>" class="btn-prev">← Previous</a>
            <?php endif; ?>
            
            <span class="page-number">Page <?php echo $page; ?></span>
            
            <?php if (count($photos['results']) === 20): ?>
                <a href="?page=<?php echo $page + 1; ?>" class="btn-next">Next →</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>