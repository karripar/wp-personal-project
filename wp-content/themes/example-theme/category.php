<?php
get_header();
?>
<section class="hero">
    <div class="hero-text">
        <?php
        echo '<h1>' . single_cat_title('', false) . '</h1>';
        echo '<p>' . category_description() . '</p>';
        ?>
    </div>
    <img src="<?php echo get_random_post_image(get_queried_object_id()); ?>" alt="Random Image">
</section>
<main>
    <section class="products">
        <?php
        $subcategories = get_categories([
            'child_of' => get_queried_object_id(),
            'hide_empty' => false
        ]);

        if (!empty($subcategories)) {
            foreach ($subcategories as $subcategory) {
                echo '<h2>' . esc_html($subcategory->name) . '</h2>';

                $args = [
                    'post_type' => 'post',
                    'cat' => $subcategory->term_id,
                    'posts_per_page' => 3
                ];
                $subcat_query = new WP_Query($args);

                generate_articles($subcat_query);

                echo '<div class="all">';
                echo '<a href="' . get_category_link($subcategory->term_id) . '">View all ' . esc_html($subcategory->name) . '</a>';
                echo '</div>';

                wp_reset_postdata();
            }
        } else {
            echo '<h2>' . single_cat_title('', false) . '</h2>';
            $args = [
                'post_type' => 'post',
                'cat' => get_queried_object_id(),
                'posts_per_page' => 3
            ];
            $cat_query = new WP_Query($args);
            generate_articles($cat_query);
        }
        ?>
    </section>
</main>
<?php
get_footer();
