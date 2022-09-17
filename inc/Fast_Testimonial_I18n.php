<?php

class Fast_Testimonial_I18n
{
    /**
     * Intialize text domain.
     *
     * @since 1.0.0
     */
    function __construct(){
        add_action( 'plugins_loaded', [ $this, 'load_plugin_textdomain' ] );
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since 1.0.0
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'fast-testimonial',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );

    }
}

new Fast_Testimonial_I18n();
