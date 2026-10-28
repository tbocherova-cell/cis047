
<?php
// Set the time zone to Pacific time.
date_default_timezone_set('America/Los_Angeles');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?? 'Welcome' ?> | My Website</title>
    <link rel="stylesheet" href="/php-site/styles.css">
</head>
<body>
    <header class="site-header">
        <p class="site-name">Tetyana's Creative Studio</p>
        <nav aria-label="Main">
            <ul>
               
<li>
    <a href="/php-site/index.php"<?= $pageTitle === 'Home' ? ' aria-current="page"' : '' ?>>Home</a>
</li>
<li>
    <a href="/php-site/about.php"<?= $pageTitle === 'About' ? ' aria-current="page"' : '' ?>>About</a>
</li>

            </ul>
        </nav>
    </header>
