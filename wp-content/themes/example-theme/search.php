<?php
get_header();
?>
  <main>
        <section class="products">
            <h2>Results for: <?php echo get_search_query(); ?>
            </h2>
            <?php 
            generate_articles( $wp_query );
            ?>
        </section>
    </main>
<?php
get_footer();