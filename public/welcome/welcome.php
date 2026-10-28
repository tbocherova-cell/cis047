
<?php
// Read form data
$name = trim($_GET['name'] ?? '');
$class = trim($_GET['class'] ?? '');
$teacher = trim($_GET['teacher'] ?? '');
$school = trim($_GET['school'] ?? '');
$goal = trim($_GET['goal'] ?? '');

$fonts = [
    'sans'  => 'Arial, Helvetica, sans-serif',
    'serif' => 'Georgia, "Times New Roman", serif',
    'mono'  => '"Courier New", Courier, monospace',
];

$font = $fonts[$_GET['font'] ?? ''] ?? $fonts['sans'];


$bg = $_GET['background'] ?? '#ffffff';
$text = $_GET['text'] ?? '#1a1a1a';

// Validate colors
$validColor = '/^#[0-9a-fA-F]{6}$/';

if (!preg_match($validColor, $bg) || !preg_match($validColor, $text)) {
    $bg = '#ffffff';
    $text = '#1a1a1a';
}

// Default name
if ($name === '') {
    $name = 'Guest';
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome, <?= htmlspecialchars($name) ?>!</title>
    <style>
        body {
            margin: 2rem;    
            font-family: <?= $font ?>;
            font-size: 18px;
            line-height: 1.7;
            background-color: <?= $bg ?>;
            color: <?= $text ?>;
        }

h1 {
    font-size: 2.2rem;
    line-height: 1.2;
}


        a {
            color: inherit;
        }
    </style>
</head>
<body>
    <h1>Welcome to <?= htmlspecialchars($name) ?>'s website!</h1>

    <p>
        I am currently enrolled in
        <strong><?= htmlspecialchars($class) ?></strong>
        with <strong><?= htmlspecialchars($teacher) ?></strong>
        at <strong><?= htmlspecialchars($school) ?></strong>.
    </p>
    
    <p>
    My career goal is to become a
    <strong><?= htmlspecialchars($goal) ?></strong>.
    </p>

    <p><a href="welcome.html">Try different colors</a></p>
</body>
</html>
