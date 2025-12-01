<?php
// config/config.php
// PENTING: File ini harus masuk .gitignore untuk keamanan!

define('UNSPLASH_ACCESS_KEY', 'Ug6CEzsD8B4qtTSw0D91HfcGscccS7hcNas0UP4LEMs');
define('UNSPLASH_API_URL', 'https://api.unsplash.com');
define('PHOTOS_PER_PAGE', 20);

// Database config (jika diperlukan nanti)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'unsplash_gallery');

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Error reporting (development mode)
error_reporting(E_ALL);
ini_set('display_errors', 1);