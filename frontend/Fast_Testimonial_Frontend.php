<?php

/**
 * Class Fast_Testimonial_Frontend
 */
class Fast_Testimonial_Frontend
{

    private static $instance = null;
    public static function get_instance() {
        if ( ! self::$instance)
            self::$instance = new self();
        return self::$instance;
    }

    /**
     * Initialize global hooks.
     */
    public function init() {

        // Load shortcode page.
        add_shortcode('fast-testimonial', array( $this, 'fast_testminial_load_shortcode_callback' ));

        // Load style and script. 
        add_action('wp_enqueue_scripts', array( $this, 'fast_testimonial_frontend_script_callback' ));
    }


    public function fast_testminial_load_shortcode_callback() {


        ob_start(); 
        wp_enqueue_style('fast-testimonial-dynamic-style')
        ?>

        <div class="fast-testimonial-area">
            <?php
            $args = array(
                'post_type' => 'fasttestimonial',
                'post_status' => 'publish',
                'posts_per_page' => 8,
                'order' => 'ASC',
            );

            $loop = new WP_Query($args);

            while ( $loop->have_posts() ) : $loop->the_post(); ?>
                <div class="fast-testimonial-single">
                    <?php echo the_post_thumbnail(); ?>
                    <h2><?php the_title(); ?></h2>
                    <?php if ( get_option('show_author') == 'on' ) : ?>
                        <h5>
                            <?php
                            esc_html_e(get_post_meta(get_the_ID(), '_fasttestimonial_author_value', true), 'fast-testimonial');
                            ?>
                        </h5>
                    <?php endif; ?>
                    <?php if ( get_option('show_ratings') == 'on' ) : ?>
                        <?php
                        $fast_testimonial = get_post_meta(get_the_ID(), '_fasttestimonial_star_value', true);

                        if ( 5 == $fast_testimonial ) : ?>
                            <ul>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                            </ul>
                        <?php elseif ( 4 == $fast_testimonial ) : ?>
                            <ul>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li class="withoutstar"><i class="fa fa-star"></i></li>
                            </ul>
                        <?php elseif ( 3 == $fast_testimonial ) : ?>
                            <ul>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li class="withoutstar"><i class="fa fa-star"></i></li>
                                <li class="withoutstar"><i class="fa fa-star"></i></li>
                            </ul>
                        <?php elseif ( 2 == $fast_testimonial ) : ?>
                            <ul>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li class="withoutstar"><i class="fa fa-star"></i></li>
                                <li class="withoutstar"><i class="fa fa-star"></i></li>
                                <li class="withoutstar"><i class="fa fa-star"></i></li>
                            </ul>
                        <?php elseif ( 1 == $fast_testimonial ) : ?>
                            <ul>
                                <li><i class="fa fa-star"></i></li>
                                <li class="withoutstar"><i class="fa fa-star"></i></li>
                                <li class="withoutstar"><i class="fa fa-star"></i></li>
                                <li class="withoutstar"><i class="fa fa-star"></i></li>
                                <li class="withoutstar"><i class="fa fa-star"></i></li>
                            </ul>
                        <?php endif; ?>

                    <?php endif; ?>
                    <p><?php echo wp_trim_words(get_the_content(), 60, '...'); ?></p>
                </div>
            <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>

        <?php $allcontents = ob_get_contents(); ?>
<?php ob_get_clean();
        return $allcontents;
    }


    /**
     * Load frontend style and script.
     *
     * @return void
     */
    function fast_testimonial_frontend_script_callback() {

        // fonts
        wp_enqueue_style('fastwp-google-fonts', 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700;800&display=swap', array(), FASTM_PLUGIN_VERSION);

        // css
        wp_enqueue_style('font-awesome');
        wp_enqueue_style('fast-testimonial_slick', plugin_dir_url(__DIR__) . 'assets/css/slick.css', array(), FASTM_PLUGIN_VERSION);
        wp_enqueue_style('fast-testimonial_slicktheme', plugin_dir_url(__DIR__) . 'assets/css/slick-theme.css', array(), FASTM_PLUGIN_VERSION);
        wp_enqueue_style('fast-testimonial_fontawesome', plugin_dir_url(__DIR__) . 'assets/css/font-awesome.min.css', array(), FASTM_PLUGIN_VERSION);
        wp_enqueue_style('fast-testimonial_customcss', plugin_dir_url(__DIR__) . 'assets/css/fastwp_custom.css', array(), FASTM_PLUGIN_VERSION);

        /**
         * Add color styling from theme
         */
        $fast_testimonialcolor = get_option('color_theme');
        if ( $fast_testimonialcolor ) {
            wp_register_style('fast-testimonial-dynamic-style', plugin_dir_url(__DIR__) . 'assets/css/dynamic.css', array(), FASTM_PLUGIN_VERSION);

            $custom_css = "
            .fast-testimonial-single h2 {
                color: #{$fast_testimonialcolor};
            }

            .fast-testimonial-single h5 {
                color: #{$fast_testimonialcolor};
            }

            .fast-testimonial-single::before {
                background: #{$fast_testimonialcolor};
            }

            .fast-testimonial-single::after {
                background: #{$fast_testimonialcolor};
            }

            .fast-testimonial-area .slick-dots li button {
                background: #{$fast_testimonialcolor};
            }

            .fast-testimonial-single ul li {
                color: #{$fast_testimonialcolor};
            }";  
            wp_add_inline_style( 'fast-testimonial-dynamic-style', $custom_css );
        }      
        
        // js
        
        wp_enqueue_script('jquery');
        wp_enqueue_script('fast_testimonialjs', plugin_dir_url(__DIR__) . 'assets/js/slick.min.js', array( 'jquery' ), FASTM_PLUGIN_VERSION, true);
        wp_enqueue_script('fast_testimonial_customscript', plugin_dir_url(__DIR__) . 'assets/js/custom-script.js', array( 'jquery' ), FASTM_PLUGIN_VERSION, true);
    }
}

Fast_Testimonial_Frontend::get_instance()->init();
