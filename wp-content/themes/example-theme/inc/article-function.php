<?php

function generate_articles($products) {
    if ($products->have_posts()):
        while ($products->have_posts()):
            $products->the_post();
            ?>
            <article class="product">
                <?php
                if (has_post_thumbnail()) :
                    the_post_thumbnail(); 
                endif;
                the_title('<h3>', '</h3>');
                ?>
                <div class="product-links">
                    <a href="<?php echo get_permalink(); ?>">Read more</a>
                    <a href="#" class="open-modal" data-id="<?php echo get_the_ID(); ?>">Preview</a>
                </div>
            </article>
            <?php
        endwhile;
    else :
        echo '<p>No products found.</p>';
    endif;

    wp_reset_postdata(); // Ensure global $post is restored after custom query
}


function generate_all_articles($products)
{
    if ($products->have_posts()):
        // Create an empty array to store posts grouped by categories
        $grouped_by_category = [];

        // Loop through all the products
        while ($products->have_posts()): 
            $products->the_post();

            // Get the categories of the current product
            $categories = get_the_category();

            // Group products by categories
            foreach ($categories as $category) {
                if (!isset($grouped_by_category[$category->term_id])) {
                    $grouped_by_category[$category->term_id] = [
                        'category_name' => $category->name,
                        'products' => []
                    ];
                }

                // Add the product to the corresponding category group
                $grouped_by_category[$category->term_id]['products'][] = [
                    'title' => get_the_title(),
                    'permalink' => get_permalink(),
                    'thumbnail' => get_the_post_thumbnail(),
                    'id' => get_the_ID()
                ];
            }
        endwhile;

        // Now display the products grouped by category
        foreach ($grouped_by_category as $category_id => $group) {
            echo '<h2>' . esc_html($group['category_name']) . '</h2>'; // Category name

            foreach ($group['products'] as $product) {
                ?>
                <article class="product">
                    <?php echo $product['thumbnail']; ?>
                    <h3><?php echo $product['title']; ?></h3>
                    <div class="product-links">
                        <a href="<?php echo $product['permalink']; ?>">Read more</a>
                        <a href="#" class="open-modal" data-id="<?php echo $product['id']; ?>">Preview</a>
                    </div>
                </article>
                <?php
            }
        }

    endif;
} // end generate_articles


