<?php
/*
    Plugin Name: CF7 Toolkit
    Plugin URI: https://github.com/jorbascrumps/cf7-toolkit
    Version: 1.0.0
    Author: Chris Wright
    Author URI: https://chriswright.dev
    Description: An extensible suite of integrations for Contact Form 7
 */

namespace CF7_Toolkit;

require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

function init () {
    (new \CF7_Toolkit\Panels\Recaptcha())->init();
}

add_action( 'init', __NAMESPACE__ . '\init' );
