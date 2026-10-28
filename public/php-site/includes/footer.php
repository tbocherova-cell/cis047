
<?php
// Get the current hour in Pacific time.
$hour = date('G');

if ($hour < 12) {
    $greeting = 'Good morning!';
} elseif ($hour < 16) {
    $greeting = 'Good afternoon!';
} elseif ($hour < 20) {
    $greeting = 'Good evening!';
} else {
    $greeting = 'Good night!';
}
?>
    <footer class="site-footer">
        <p><?= $greeting ?></p>
        <p>Copyright &copy; <?= date('Y') ?> Tetyana Bocherova</p>
        <p>Last updated: <?= date('F j, Y', getlastmod()) ?></p>
    </footer>
</body>
</html>
