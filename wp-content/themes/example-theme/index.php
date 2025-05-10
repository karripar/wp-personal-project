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
if (is_page('contact-us')) {
    ?>
    <div class="map">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1981.3874123785617!2d24.757164216883904!3d60.223966481847015!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x468df69f8c81ee65%3A0x153943742aa326e8!2sKaraportti%202%2C%2002610%20Espoo!5e0!3m2!1sfi!2sfi!4v1746882435597!5m2!1sfi!2sfi"
            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <?php
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