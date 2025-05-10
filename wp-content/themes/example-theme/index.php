<?php

get_header();
?>
<section class="hero">
    <div class="hero-text">
        <?php
        if (have_posts()):
            while (have_posts()):
                the_post();
                the_title('<h1>', '</h1>');
                the_content();
            endwhile;
        else:
            _e('Sorry, no posts matched your criteria.', 'textdomain');
        endif;
        ?>
    </div>
    <?php
    the_custom_header_markup();
    ?>
</section>
<?php
if ( is_page('contact-us') ) {
    include get_template_directory() . '/contact-form.php';
}
?>
<main>
    <section class="search">
        <?php
        get_search_form()
            ?>
    </section>
    <section class="products">
        <h2>Featured Products</h2>
        <?php
        $args = ['tag' => 'featured', 'posts_per_page' => 3];
        $products = new WP_Query($args);
        generate_articles($products);
        ?>
    </section>
</main>
<?php
get_footer();