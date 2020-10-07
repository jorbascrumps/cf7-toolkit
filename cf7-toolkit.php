<?php
/*
    Plugin Name: CF7 Toolkit
    Version: 1.0.0
    Description: TODO
 */

namespace CF7_Toolkit;

require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

function init () {
    new \CF7_Toolkit\Panels\Recaptcha();
}

add_action( 'init', __NAMESPACE__ . '\init' );
