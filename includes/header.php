<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>Unsplash Gallery</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="main-header">
        <nav class="navbar">
            <div class="nav-container">
                <a href="index.php" class="logo">
                    🖼️ <span>Unsplash Gallery</span>
                </a>
                <ul class="nav-menu">
                    <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="search.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'search.php' ? 'active' : ''; ?>">Search</a></li>
                </ul>
            </div>
        </nav>
    </header>
    
    <main class="main-content">