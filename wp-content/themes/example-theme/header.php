<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body>
    <div class="container">
        <header class="page-header">
            <div class="header-top-left">
                <?php the_custom_logo(); ?>
                <h1 class="company-name">Componental</h1>
                <button class="open-cart-btn" id="open-cart">View Cart</button>
            </div>

            <div class="header-top-right">
                <nav class="main-nav">
                    <?php

                    wp_nav_menu([
                        "theme_location" => "main-menu",
                        "container" => false,
                        "menu_class" => ""
                    ]);
                    ?>
                </nav>
            </div>
        </header>
        <section class="breadcrumbs">
            <?php if (function_exists('bcn_display')) {
                bcn_display();
            } ?>
        </section>