<?php
/*
    Plugin Name: AAA New Plugin
    Description: A new Plugin
    Version: 1.0
    Author: Fazle Bari
    Author URI: https://chitabd.com
*/

if( file_exists( dirname(__FILE__) . '/vendor/autoload.php') ){
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

use Inc\Activate;
use Inc\Deactivate;
use Inc\Test;


// if( class_exists( 'WordCountAndTimePlugin' ) ){
//     $wordCountAndTimePlugin = new WordCountAndTimePlugin;
//     $wordCountAndTimePlugin->register();
// }

if( !class_exists( 'Test' ) ){
    $wordCountAndTimePlugin = new Test;
    $wordCountAndTimePlugin->register();
}

// Activate plugin 

function activate_word_count_plugin(){
    Activate::activate();
}
register_activation_hook( __FILE__, 'activate_word_count_plugin' );

// Deactivate plugin 	
function deactivate_word_count_plugin(){
    Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_word_count_plugin' );


