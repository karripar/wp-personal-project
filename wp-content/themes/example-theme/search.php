<?php
global $wp_query;
get_header();
?>

<main>
    <section class="products">
        <h2>Search results for: <?php echo get_search_query(); ?></h2>

        <?php
        if (have_posts()) :
            generate_articles($wp_query);
        else :
            echo '<p>No results found.</p>';
        endif;
        ?>
    </section>
</main>

<?php
get_footer();
?>
