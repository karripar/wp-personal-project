<footer>
    <p>Componental - PC Components - &copy; 2025 Karripar</p>
    <ul class="footer-links">
        <li><a href="<?php echo home_url(); ?>">Home</a></li>
        <li><a href="<?php echo site_url('/category/components'); ?>">Components</a></li>
        <li><a href="<?php echo site_url('/about-us'); ?>">About Us</a></li>
        <li><a href="<?php echo site_url('/contact-us'); ?>">Contact Us</a></li>
    </ul>
</footer>
</div>
<dialog id="single-post">
    <button id="close">X</button>
    <article class="single" id="modal-content">
    </article>
</dialog>
<!-- Cart Modal -->
<div id="cartModal" class="cart-modal">
  <div class="cart-modal-content">
    <span class="close-cart-btn">&times;</span>
    <h2>Your Cart</h2>
    <div id="cartContent">
      <?php echo do_shortcode('[view_cart]'); ?>
    </div>
  </div>
</div>

<?php wp_footer(); ?>
</body>

</html>