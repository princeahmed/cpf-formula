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
defined( 'ABSPATH' ) || die( 'ABSPATH is not defined!' );

//Define Constants
define( 'CPF_FORMULA_VERSION', 'CPF_FORMULA_VERSION' );
define( 'CPF_FORMULA_ADMIN', dirname( __FILE__ ) . '/admin' );
define( 'CPF_FORMULA_INCLUDES', dirname( __FILE__ ) . '/includes' );
define( 'CPF_FORMULA_PUBLIC', dirname( __FILE__ ) . '/public' );
define( 'CPF_FORMULA_PUBLIC_URL', plugins_url( 'public', __FILE__ ) );

// if admin area, includes amin files:
if ( is_admin() ) {
	// include dependencies
	require_once CPF_FORMULA_ADMIN . '/class-settings-api.php';
	require_once CPF_FORMULA_ADMIN . '/class-settings.php';
}

require_once CPF_FORMULA_INCLUDES . '/class-shortcode.php';

// register jquery and style on initialization
add_action( 'init', 'cpf_formula_custom_styles' );

function cpf_formula_custom_styles() {
	wp_register_style( 'formula_bootstrap', CPF_FORMULA_PUBLIC_URL . '/css/bootstrap.min.css', false, 'CPF_FORMULA_VERSION', 'all' );
	wp_register_style( 'formula_style', CPF_FORMULA_PUBLIC_URL . '/css/style.css', false, 'CPF_FORMULA_VERSION', 'all' );

	wp_register_script( 'formula_jquery', CPF_FORMULA_PUBLIC_URL . '/js/jquery-3.4.1.min.js' );
	wp_register_script( 'formula_birthday', CPF_FORMULA_PUBLIC_URL . '/js/bootstrap-birthday.min.js' );
	wp_register_script( 'formula_site', CPF_FORMULA_PUBLIC_URL . '/js/site_js.js' );
}

// use the registered jquery and style above
add_action( 'wp_enqueue_scripts', 'wcf_formula_enqueue_style' );

function wcf_formula_enqueue_style() {
	wp_enqueue_style( 'formula_bootstrap' );
	wp_enqueue_style( 'formula_style' );
	wp_enqueue_script( 'formula_jquery' );
	wp_enqueue_script( 'formula_birthday' );
	wp_enqueue_script( 'formula_site' );
}