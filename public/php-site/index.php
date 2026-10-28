
<?php
$pageTitle = 'Home';
include 'includes/header.php';
?>

<main>
    <p class="eyebrow">
        UX/UI DESIGN • WEB DEVELOPMENT • CREATIVITY
    </p>

    <h1>Welcome to My Creative Studio!</h1>

    <p>
        I'm Tetyana, a UX/UI Design student at
        San José City College with a professional
        background in business analysis and
        product development.
    </p>

    <p>
        I enjoy combining creativity, technology,
        and user-centered thinking to design
        meaningful digital experiences.
    </p>

    <section class="expertise" aria-labelledby="explore-title">
        <h2 id="explore-title">Explore My Studio</h2>

        <div class="expertise-grid">
            <article class="expertise-card">
                <h3>Creative Design</h3>
                <p>
                    Exploring visual design,
                    typography, and color.
                </p>
            </article>

            <article class="expertise-card">
                <h3>User Experience</h3>
                <p>
                    Creating intuitive and
                    accessible interfaces.
                </p>
            </article>

            <article class="expertise-card">
                <h3>Web Development</h3>
                <p>
                    Building responsive
                    and interactive websites.
                </p>
            </article>
        </div>
    </section>

    <p class="current-time">
        Today is <?= date('l, F j, Y') ?>
        at <?= date('g:i a T') ?>.
    </p>
</main>

<?php include 'includes/footer.php'; ?>

