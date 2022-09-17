<?php

/**
 * Class Fast_Testimonial_Admin
 */
class Fast_Testimonial_Admin
{

    private static $instance = null;

    /**
     * Make instance of the admin class.
     */
    public static function get_instance() {
        if ( ! self::$instance)
            self::$instance = new self();
        return self::$instance;
    }


    public function init() {

        /**
         * Register Post Type.
         */
        add_action('init', array( $this, 'fast_testimonial_register_post_type_callback' ), 0);

        /**
         * Load Post Meta.
         */
        add_action('add_meta_boxes', array( $this, 'add_meta_box' ));
        add_action('save_post',      array( $this, 'save' ));

        /**
         * Adds a submenu page under a custom post type parent.
         */
        add_action('admin_menu', array( $this, 'fast_testimonial_admin_menu_callback' ));



        // Load css and js.
        add_action('admin_enqueue_scripts', array( $this, 'fast_testimonial_admin_script_callback' ));
    }

    /**
     * Enqueue a script in the WordPress admin on edit.php.
     *
     * @param int $hook Hook suffix for the current admin page.
     */
    function fast_testimonial_admin_script_callback() {
        // css
        wp_register_style('fast-testimonial_jqueryui', plugin_dir_url(__DIR__) . 'assets/css/jquery-ui.css', array(), FASTM_PLUGIN_VERSION);
        wp_enqueue_style('fast-testimonial_jqueryui');
        wp_enqueue_style('fast-testimonial_colorpicker', plugin_dir_url(__DIR__) . 'assets/css/jquery.colorpicker.css', array(), FASTM_PLUGIN_VERSION);
        wp_enqueue_style('fast_testimonialadmin', plugin_dir_url(__DIR__) . 'assets/css/adminstyle.css', array(), FASTM_PLUGIN_VERSION);

        // js
        wp_register_script('plugin-jquery', plugin_dir_url(__DIR__) . 'assets/js/plugin-jquery.js', array(), FASTM_PLUGIN_VERSION, false);
        wp_enqueue_script('plugin-jquery');
        wp_register_script('plugin-jquery-ui', plugin_dir_url(__DIR__) . 'assets/js/jquery-ui.js', array(), FASTM_PLUGIN_VERSION, false);
        wp_enqueue_script('plugin-jquery-ui');
        wp_enqueue_script('jquery-uicolorpicker', plugin_dir_url(__DIR__) . 'assets/js/jquery.colorpicker.js', array( 'jquery' ), FASTM_PLUGIN_VERSION, false);
        wp_enqueue_script('admin-script', plugin_dir_url(__DIR__) . 'assets/js/admin.js', array( 'jquery' ), FASTM_PLUGIN_VERSION, true);

    }




    function fast_testimonial_admin_menu_callback() {
        add_submenu_page(
            'edit.php?post_type=fasttestimonial',
            __('Settings', 'fast-testimonial'),
            __('Settings', 'fast-testimonial'),
            'manage_options',
            'fasttestimonial-page-settings',
            array( $this, 'fast_testimonial_settings_callback' )
        );
    }

    /**
     * Display callback for the submenu page.
     */
    function fast_testimonial_settings_callback() {
        include_once(dirname(__FILE__) . '/Display_Setting_Template.php');
    }


    /**
     * Register post type.
     *
     * @return void
     */
    public function fast_testimonial_register_post_type_callback() {

        $labels = array(
            'name'                  => _x('Testimonials', 'Post Type General Name', 'fast-testimonial'),
            'singular_name'         => _x('Testimonial', 'Post Type Singular Name', 'fast-testimonial'),
            'menu_name'             => __('Testimonial', 'fast-testimonial'),
            'all_items'             => __('All Items', 'fast-testimonial'),
            'add_new_item'          => __('Add New Item', 'fast-testimonial'),
            'add_new'               => __('Add New', 'fast-testimonial'),
            'new_item'              => __('New Item', 'fast-testimonial'),
            'edit_item'             => __('Edit Item', 'fast-testimonial'),
            'update_item'           => __('Update Item', 'fast-testimonial'),
            'featured_image'        => __('Client Image', 'fast-testimonial'),
            'set_featured_image'    => __('Set Client Image', 'fast-testimonial'),
            'remove_featured_image' => __('Remove Client Image', 'fast-testimonial'),
        );
        $args = array(
            'label'                 => __('Testimonial', 'fast-testimonial'),
            'description'           => __('Post Type Description', 'fast-testimonial'),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_in_menu'          => true,
            'menu_icon'             => 'dashicons-testimonial',
            'menu_position'         => 5,
        );
        register_post_type('fasttestimonial', $args);
    }


    /**
     * Adds the meta box container.
     */
    public function add_meta_box( $post_type ) {
        // Limit meta box to certain post types.
        $post_types = array( 'fasttestimonial' );

        if ( in_array($post_type, $post_types) ) {
            add_meta_box(
                'some_meta_box_name',
                __('Client Name & Rating Option', 'fast-testimonial'),
                array( $this, 'render_meta_box_content' ),
                $post_type,
                'advanced',
                'high'
            );
        }
    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id ) {

        /*
	         * We need to verify this came from the our screen and with proper authorization,
	         * because save_post can be triggered at other times.
	         */

        // Check if our nonce is set.
        if ( ! isset($_POST['fasttestimonial_inner_custom_box_nonce']) ) {
            return $post_id;
        }

        $nonce = sanitize_text_field(wp_unslash($_POST['fasttestimonial_inner_custom_box_nonce'] ?? ''));

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce($nonce, 'fasttestimonial_inner_custom_box') ) {
            return $post_id;
        }

        /*
	         * If this is an autosave, our form has not been submitted,
	         * so we don't want to do anything.
	         */
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
            return $post_id;
        }

        /* OK, it's safe for us to save the data now. */

        // Sanitize the user input.
        $author_value = sanitize_text_field(wp_unslash($_POST['myplugin_new_field'] ?? ''));
        // Sanitize the user input.
        $star_value = sanitize_text_field(wp_unslash($_POST['fast_testimonialstar'] ?? ''));

        // Update the meta field.
        update_post_meta($post_id, '_fasttestimonial_author_value', $author_value);
        update_post_meta($post_id, '_fasttestimonial_star_value', $star_value);
    }


    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content( $post ) {

        // Add an nonce field so we can check for it later.
        wp_nonce_field('fasttestimonial_inner_custom_box', 'fasttestimonial_inner_custom_box_nonce');

        // Use get_post_meta to retrieve an existing value from the database.
        $value = get_post_meta($post->ID, '_fasttestimonial_author_value', true);
        $value2 = get_post_meta($post->ID, '_fasttestimonial_star_value', true);

        // Display the form, using the current value.
?>
        <label for="myplugin_new_field">
            <?php esc_html_e('Author Name', 'fast-testimonial'); ?>
        </label>
        <input class="widefat" type="text" id="myplugin_new_field" name="myplugin_new_field" value="<?php echo esc_attr($value); ?>" size="25" />

        <br><br>

        <label for="fast_testimonialstar">
            <?php esc_html_e('Author Star', 'fast-testimonial'); ?>
        </label>

        <select class="widefat" name="fast_testimonialstar">
            <option <?php if ( 1 == $value2 ) echo "selected='selected'"; ?>>1</option>
            <option <?php if ( 2 == $value2 ) echo "selected='selected'"; ?>>2</option>
            <option <?php if ( 3 == $value2 ) echo "selected='selected'"; ?>>3</option>
            <option <?php if ( 4 == $value2 ) echo "selected='selected'"; ?>>4</option>
            <option <?php if ( 5 == $value2) echo "selected='selected'"; ?>>5</option>
        </select>

<?php
    }
}

Fast_Testimonial_Admin::get_instance()->init();
