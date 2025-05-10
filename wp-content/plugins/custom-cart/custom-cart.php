<?php
/*
Plugin Name: Custom Cart Plugin
Description: Adds a custom shopping cart system with AJAX
Version: 1.0
Author: karripar
*/

if (!defined('ABSPATH')) exit;

// Start session if not already started
if (!session_id()) {
    session_start();
}

// Create DB table on activation
function custom_cart_create_table() {
    global $wpdb;
    $table = $wpdb->prefix . 'custom_cart';
    $sql = "CREATE TABLE $table (
        id BIGINT NOT NULL AUTO_INCREMENT,
        user_id BIGINT NOT NULL,
        product_id BIGINT NOT NULL,
        quantity INT DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) " . $wpdb->get_charset_collate();

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'custom_cart_create_table');

// Add item to cart (AJAX)
function custom_cart_add_item() {
    global $wpdb;

    if (!is_user_logged_in()) {
        wp_send_json_error('Please log in');
    }

    $user_id = get_current_user_id();
    $product_id = absint($_POST['product_id']);
    $quantity = absint($_POST['quantity']);

    if ($product_id <= 0 || $quantity <= 0) {
        wp_send_json_error('Invalid product ID or quantity');
    }

    $table = $wpdb->prefix . 'custom_cart';

    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table WHERE user_id = %d AND product_id = %d",
        $user_id, $product_id
    ));

    if ($exists) {
        $wpdb->query($wpdb->prepare(
            "UPDATE $table SET quantity = quantity + %d WHERE id = %d",
            $quantity, $exists
        ));
    } else {
        $wpdb->insert($table, [
            'user_id' => $user_id,
            'product_id' => $product_id,
            'quantity' => $quantity
        ], ['%d', '%d', '%d']);
    }

    wp_send_json_success('Item added');
}
add_action('wp_ajax_add_to_cart', 'custom_cart_add_item');

// Remove item from cart (AJAX)
function custom_cart_remove_item() {
    global $wpdb;
    if (!is_user_logged_in()) {
        wp_send_json_error('Please log in');
    }

    $user_id = get_current_user_id();
    $cart_item_id = absint($_POST['cart_item_id']);

    $table = $wpdb->prefix . 'custom_cart';

    $item = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table WHERE id = %d AND user_id = %d", $cart_item_id, $user_id
    ));

    if (!$item) {
        wp_send_json_error('Item not found in your cart');
    }

    $wpdb->delete($table, ['id' => $cart_item_id]);

    wp_send_json_success('Item removed from cart');
}
add_action('wp_ajax_remove_from_cart', 'custom_cart_remove_item');

// Enqueue JS
function custom_cart_enqueue_scripts() {
    wp_enqueue_script(
        'custom-cart-js',
        plugin_dir_url(__FILE__) . '/custom-cart.js',
        [], 
        '1.0',
        true
    );

    wp_localize_script('custom-cart-js', 'customCart', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
}
add_action('wp_enqueue_scripts', 'custom_cart_enqueue_scripts');


// Shortcode to render "Add to Cart" button
function custom_cart_add_button($atts) {
    $product_id = intval($atts['id']);
    if (!$product_id) return 'Invalid product ID!';

    return '<button class="add-to-cart-btn" data-product-id="' . esc_attr($product_id) . '">Add to Cart</button>';
}
add_shortcode('add_to_cart', 'custom_cart_add_button');


// Shortcode to view cart
function custom_cart_view() {
    global $wpdb;
    $user_id = get_current_user_id();
    if (!$user_id) return '<p>Please log in to view your cart.</p>';

    $table = $wpdb->prefix . 'custom_cart';
    $items = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table WHERE user_id = %d", $user_id
    ));

    if (!$items) return '<p>Your cart is empty.</p>';

    $output = '<ul>';
    foreach ($items as $item) {
        $title = get_the_title($item->product_id);
        $output .= "<li>$title x $item->quantity 
                    <button class='remove-from-cart-btn' data-cart-item-id='{$item->id}'>Remove</button></li>";
    }
    $output .= '</ul>';

    return $output;
}
add_shortcode('view_cart', 'custom_cart_view');

// AJAX: Get updated cart HTML
function custom_cart_get_updated_view() {
    echo custom_cart_view(); // outputs HTML directly
    wp_die();
}
add_action('wp_ajax_get_updated_cart', 'custom_cart_get_updated_view');

