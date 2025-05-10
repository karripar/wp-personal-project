<?php

/*
Plugin Name: Like Button
Description: Adds a like button to posts
Version: 1.0
Author: ILE
*/

if (!defined('ABSPATH')) {
	exit;
}
// Create table

use JetBrains\PhpStorm\NoReturn;

function create_table(): void
{
	global $wpdb;

	$table_name = $wpdb->prefix . 'likes';

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
        id bigint NOT NULL AUTO_INCREMENT,
        post_id bigint NOT NULL,
        user_id bigint NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}

register_activation_hook(__FILE__, 'create_table');

// Add like button

function like_button($atts): string
{
	global $wpdb;

	$table_name = $wpdb->prefix . 'likes';

	if (isset($atts['post_id']) && intval($atts['post_id']) > 0) {
		$post_id = intval($atts['post_id']);
	} elseif (get_the_ID()) {
		$post_id = get_the_ID();
	} else {
		global $post;
		$post_id = isset($post->ID) ? $post->ID : 0;
	}


	$results = $wpdb->get_results("SELECT * FROM $table_name WHERE post_id = $post_id");

	$likes = count($results);

	// tarkista onko käyttäjä jo tykännyt
	$user_id = get_current_user_id();

	$data = [
		'post_id' => $post_id,
		'user_id' => $user_id,
	];

	$preparedQuery = $wpdb->prepare(
		"SELECT id FROM $table_name WHERE post_id = %d and user_id = %d",
		$data
	);
	$user_results = $wpdb->get_results($preparedQuery);

	$icon = 'thumbs-up';

	if (count($user_results) == 0) {
		$icon = 'thumbs-up-outline';
	}

	$nonce = wp_create_nonce('like_form_nonce');
	$output = '<form class="like-form" method="post">';
	$output .= '<input type="hidden" name="like_form_nonce" value="' . $nonce . '">';
	$output .= '<input type="hidden" name="action" value="add_like">';
	$output .= '<input type="hidden" name="post_id" value="' . $post_id . '">';
	$output .= '<button type="submit" class="like-button"><ion-icon name="' . $icon . '"></ion-icon></button>';
	$output .= '<span class="like-count">' . $likes . '</span>';
	$output .= '</form>';


	return $output;
}

add_shortcode('like_button', 'like_button');

// Add like to database

#[NoReturn] function add_like(): void
{
	global $wpdb;

	if (!isset($_POST['like_form_nonce']) || !wp_verify_nonce($_POST['like_form_nonce'], 'like_form_nonce')) {
		wp_send_json_error('Invalid nonce');
	}

	$table_name = $wpdb->prefix . 'likes';
	$post_id = intval($_POST['post_id']);
	$user_id = get_current_user_id();

	$already_liked = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM $table_name WHERE post_id = %d AND user_id = %d",
			$post_id,
			$user_id
		)
	);

	if ($already_liked) {
		$wpdb->delete($table_name, ['post_id' => $post_id, 'user_id' => $user_id], ['%d', '%d']);
		$liked = false;
	} else {
		$wpdb->insert($table_name, ['post_id' => $post_id, 'user_id' => $user_id], ['%d', '%d']);
		$liked = true;
	}

	$total_likes = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE post_id = %d", $post_id));

	$response = [
		'success' => true,
		'liked' => $liked,
		'likes' => $total_likes,
	];

	if (defined('DOING_AJAX') && DOING_AJAX) {
		wp_send_json($response);
	} else {
		wp_redirect($_SERVER['HTTP_REFERER']);
		exit;
	}
}


add_action('admin_post_add_like', 'add_like');

// enqueue icons
function my_theme_load_ionicons_font(): void
{
	// Load Ionicons font from CDN
	wp_enqueue_script('my-theme-ionicons', 'https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js', array(), '7.1.0', true);
}

add_action('wp_enqueue_scripts', 'my_theme_load_ionicons_font');

// ajax toiminnallisuus
// lisää action: add_like(), ks. single-post-ajax.php
add_action('wp_ajax_add_like', 'add_like');

// enqueue skripti, ks. functions.php
function like_button_enqueue_scripts(): void
{
	wp_register_script('like-button', plugin_dir_url(__FILE__) . '/like-button.js', [], '1.0', true);
	$script_data = array(
		'ajax_url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('like_form_nonce'),
	);
	wp_localize_script('like-button', 'likeButton', $script_data);
	wp_enqueue_script('like-button');
}

add_action('wp_enqueue_scripts', 'like_button_enqueue_scripts');