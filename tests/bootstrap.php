<?php

WP_Mock::bootstrap();

WP_Mock::userFunction( 'plugin_dir_path', [
    'return' => './',
] );

require_once './cf7-toolkit.php';
