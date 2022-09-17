<?php

/**
 * Class Uta_helpers
 */
class Fast_Testimonial_Helpers{

    private static $instance = null;
    public static function get_instance() {
        if ( ! self::$instance )
            self::$instance = new self();
        return self::$instance;
    }

    /**
     * Initialize global hooks.
     */
    public function init(){
       
        // Write stuff here 
    }



}

Fast_Testimonial_Helpers::get_instance()->init();


