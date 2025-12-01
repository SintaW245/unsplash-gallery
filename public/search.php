<?php
// public/search.php
require_once __DIR__ . '/../api/UnsplashAPI.php';

$api = new UnsplashAPI();
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$photos = null;

if (!empty($query)) {
    $photos = $api->searchPhotos($query, $page, 20);
}

$pageTitle = "Search Photos";
include __DIR__ . ' ';
?>

<div class="container">
    <div class="search-section">
        <h1>🔍 Search Photos</h1>
        
        <form method="GET" action="search.php" class="search-form">
            <input 
                type="text" 
                name="q" 
                placeholder="Search for photos (e.g., mountains, ocean, forest...)" 
                value="<?php echo htmlspecialchars($query); ?>"
                required
            >
            <button type="submit">Search</button>
        </form>

        <?php if (!empty($query)): ?>
            <div class="search-info">
                <p>Search results for: <strong>"<?php echo htmlspecialchars($query); ?>"</strong></p>
                <?php if ($photos && !isset($photos['error'])): ?>
                    <p>Found <?php echo number_format($photos['total']); ?> photos</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($query)): ?>
        <?php if (isset($photos['error'])): ?>
            <div class="error-message">
                <p>Error: <?php echo htmlspecialchars($photos['message']); ?></p>
            </div>
        <?php elseif (empty($photos['results'])): ?>
            <div class="no-results">
                <p>😕 No photos found for "<?php echo htmlspecialchars($query); ?>"</p>
                <p>Try different keywords!</p>
            </div>
        <?php else: ?>
            <div class="photo-grid">
                <?php foreach ($photos['results'] as $photo): ?>
                    <div class="photo-card">
                        <a href="detail.php?id=<?php echo $photo['id']; ?>">
                            <img 
                                src="<?php echo $photo['urls']['small']; ?>" 
                                alt="<?php echo htmlspecialchars($photo['alt_description'] ?? $query); ?>"
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
                    <a href="?q=<?php echo urlencode($query); ?>&page=<?php echo $page - 1; ?>" class="btn-prev">← Previous</a>
                <?php endif; ?>
                
                <span class="page-number">Page <?php echo $page; ?></span>
                
                <?php if (count($photos['results']) === 20): ?>
                    <a href="?q=<?php echo urlencode($query); ?>&page=<?php echo $page + 1; ?>" class="btn-next">Next →</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include __DIR__ . ' '; ?>