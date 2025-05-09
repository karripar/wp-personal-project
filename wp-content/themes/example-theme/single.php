<?php
get_header();
?>
    <main class="full-width">
        <section class="products">
            <article class="single">
            <?php
            if ( have_posts() ) :
                while ( have_posts() ) :
                    the_post();
                    the_title('<h2>', '</h2>');
                    the_content();
                endwhile;
            else :
                _e( 'Sorry, no posts matched your criteria.', 'textdomain' );
            endif;

            echo do_shortcode('[like_button post_id="'.get_the_ID().'"]');
            ?>
            </article>
        </section>
    </main>
<?php
get_footer();