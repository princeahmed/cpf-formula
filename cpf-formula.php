<?php
/**
* @package CPFFormula
*/
/*
Plugin Name: CPF Formula
Plugin URI: 
Description: Singapore Employee and Emplyeer Salary Calculator
Version: 1.0.0
Author: Tanveer Qureshee
Author URI:
License: GPL
*/


// exit if file is called directly
defined('ABSPATH') or die('ABSPATH is not defined!');

ob_start();
// if admin area:
if(is_admin() ){
    // include dependencies
    require_once plugin_dir_path(__FILE__).'admin/class-settings-api.php';
    require_once plugin_dir_path(__FILE__).'admin/class-settings.php';
}

// register jquery and style on initialization
add_action('init', 'cpf_formula_custom_styles');

function cpf_formula_custom_styles() {
    wp_register_style( 'formula_bootstrap_css',      plugins_url('public/css/bootstrap.min.css', __FILE__), false, '1.0.0', 'all');
    wp_register_style( 'formula_style',          plugins_url('public/css/style.css', __FILE__), false, '1.0.0', 'all');
    
    wp_register_script( 'formula_jquery',         plugins_url('public/js/jquery-3.4.1.min.js', __FILE__));
    wp_register_script( 'formula_birthday',         plugins_url('public/js/bootstrap-birthday.min.js', __FILE__));
    wp_register_script( 'formula_sitejs',         plugins_url('public/js/site_js.js', __FILE__));

}
// use the registered jquery and style above
add_action('wp_enqueue_scripts', 'enqueue_style');

function enqueue_style(){
   wp_enqueue_style( 'formula_bootstrap_css' );
   wp_enqueue_style( 'formula_style' );
   wp_enqueue_script('formula_jquery');
   wp_enqueue_script('formula_birthday');
   wp_enqueue_script('formula_sitejs');
}

require_once plugin_dir_path(__FILE__).'public/formula_1.php';
ob_clean();