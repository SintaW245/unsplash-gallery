<?php
// public/detail.php
require_once __DIR__ . '/../api/UnsplashAPI.php';

$api = new UnsplashAPI();
$photoId = isset($_GET['id']) ? trim($_GET['id']) : '';

if (empty($photoId)) {
    header('Location: index.php');
    exit;
}

$photo = $api->getPhotoDetail($photoId);

if (isset($photo['error'])) {
    header('Location: index.php');
    exit;
}

$pageTitle = "Photo by " . $photo['user']['name'];
include __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="detail-page">
        <div class="detail-header">
            <a href="javascript:history.back()" class="back-button">← Back</a>
            <h1>Photo Details</h1>
        </div>

        <div class="detail-content">
            <div class="detail-image">
                <img 
                    src="<?php echo $photo['urls']['regular']; ?>" 
                    alt="<?php echo htmlspecialchars($photo['alt_description'] ?? 'Photo'); ?>"
                >
            </div>

            <div class="detail-info">
                <div class="photographer-info">
                    <img 
                        src="<?php echo $photo['user']['profile_image']['medium']; ?>" 
                        alt="<?php echo htmlspecialchars($photo['user']['name']); ?>"
                        class="profile-pic"
                    >
                    <div>
                        <h2><?php echo htmlspecialchars($photo['user']['name']); ?></h2>
                        <?php if (!empty($photo['user']['username'])): ?>
                            <p class="username">@<?php echo htmlspecialchars($photo['user']['username']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($photo['description']) || !empty($photo['alt_description'])): ?>
                    <div class="photo-description">
                        <h3>Description</h3>
                        <p><?php echo htmlspecialchars($photo['description'] ?? $photo['alt_description']); ?></p>
                    </div>
                <?php endif; ?>

                <div class="photo-stats">
                    <h3>Statistics</h3>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <span class="stat-icon">❤️</span>
                            <span class="stat-label">Likes</span>
                            <span class="stat-value"><?php echo number_format($photo['likes']); ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-icon">👁️</span>
                            <span class="stat-label">Views</span>
                            <span class="stat-value"><?php echo number_format($photo['views']); ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-icon">⬇️</span>
                            <span class="stat-label">Downloads</span>
                            <span class="stat-value"><?php echo number_format($photo['downloads']); ?></span>
                        </div>
                    </div>
                </div>

                <div class="photo-metadata">
                    <h3>Camera Info</h3>
                    <?php if (!empty($photo['exif'])): ?>
                        <ul>
                            <?php if (!empty($photo['exif']['make'])): ?>
                                <li><strong>Camera:</strong> <?php echo htmlspecialchars($photo['exif']['make'] . ' ' . $photo['exif']['model']); ?></li>
                            <?php endif; ?>
                            <?php if (!empty($photo['exif']['focal_length'])): ?>
                                <li><strong>Focal Length:</strong> <?php echo htmlspecialchars($photo['exif']['focal_length']); ?>mm</li>
                            <?php endif; ?>
                            <?php if (!empty($photo['exif']['aperture'])): ?>
                                <li><strong>Aperture:</strong> f/<?php echo htmlspecialchars($photo['exif']['aperture']); ?></li>
                            <?php endif; ?>
                            <?php if (!empty($photo['exif']['exposure_time'])): ?>
                                <li><strong>Shutter Speed:</strong> <?php echo htmlspecialchars($photo['exif']['exposure_time']); ?>s</li>
                            <?php endif; ?>
                            <?php if (!empty($photo['exif']['iso'])): ?>
                                <li><strong>ISO:</strong> <?php echo htmlspecialchars($photo['exif']['iso']); ?></li>
                            <?php endif; ?>
                        </ul>
                    <?php else: ?>
                        <p>No camera information available</p>
                    <?php endif; ?>
                </div>

                <div class="photo-actions">
                    <a 
                        href="<?php echo $photo['links']['download']; ?>" 
                        class="btn-download"
                        target="_blank"
                        onclick="trackDownload('<?php echo $photo['links']['download_location']; ?>')"
                    >
                        ⬇️ Download Photo
                    </a>
                    <a 
                        href="<?php echo $photo['links']['html']; ?>" 
                        class="btn-unsplash"
                        target="_blank"
                    >
                        View on Unsplash
                    </a>
                </div>

                <div class="photo-date">
                    <p>📅 Published: <?php echo date('F j, Y', strtotime($photo['created_at'])); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function trackDownload(url) {
    // Track download for Unsplash API guidelines
    fetch('track_download.php?url=' + encodeURIComponent(url));
}
</script>

<?php include __DIR__ . ''; ?>