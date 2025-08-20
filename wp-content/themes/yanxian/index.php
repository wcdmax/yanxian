<?php get_header(); ?>

<main id="site-content" role="main">
    <div class="container">
        <h1><?php the_title(); ?></h1>
        <div class="post-content">
            <?php the_content(); ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>