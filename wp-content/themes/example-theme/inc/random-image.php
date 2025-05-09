
<?php 
function get_random_post_image($category_id) {
    $args = array(
        'post_type'      => 'post',
        'cat'            => $category_id,
        'posts_per_page' => 1,
        'orderby'        => 'rand',
      );
      $random_post = new WP_Query( $args );
      if ( ! $random_post->have_posts() ) {
        return '';
      }
      $random_post->the_post();
      
      $image_url = wp_get_attachment_url( get_post_thumbnail_id() );

      if (!$image_url) {
        return '';
      }
      wp_reset_postdata();
      return $image_url;

}

