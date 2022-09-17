<div class="wrap fasttestimonial-wrap">
    <h1><?php esc_html_e('Testimonial Options', 'fast-testimonial'); ?></h1>

    <div class="copy_shortcode">
        <input id="shortcodename" type="text" value="[fast-testimonial]" readonly>
        <p id="copyText">Copy Shortcode</p>
        <p style="color: green; background: none; display: block; padding: 0px;" id="successMessage"></p>
    </div>

    <form action="options.php" method="post">
        <?php wp_nonce_field('update-options'); ?>
        <label for="color_theme"><?php esc_html_e('Testimonial Primary Color Code(example: #111111)', 'fast-testimonial'); ?></label>
        <input type="text" class="widefat cp-basic" id="color_theme" name="color_theme" value="<?php echo esc_attr(get_option('color_theme')); ?>" readonly>
        <label for="show_author"><?php esc_html_e('Show Author Name', 'fast-testimonial'); ?></label><br>


        <label class="switch">
            <input type="checkbox" name="show_author" <?php echo get_option('show_author') == 'on' ? 'checked' : '';?>>
            <span class="slider"></span>
        </label> <br><br>

        <label for="show_ratingss" style="padding-top: 18px;"><?php esc_html_e('Show Ratings', 'fast-testimonial'); ?></label><br>

        <label class="switch">
            <input type="checkbox" name="show_ratings" <?php echo get_option('show_ratings') == 'on' ? 'checked' : '';?>>
            <span class="slider"></span>
        </label> <br><br>

        <input type="hidden" name="action" value="update">
        <input type="hidden" name="page_options" value="color_theme, show_author, show_ratings">
        <input type="submit" name="submit" value="<?php esc_html_e('Save Changes', 'fast-testimonial') ?>">
    </form>
</div>