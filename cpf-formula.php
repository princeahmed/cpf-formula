<?php

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
define( 'CPF_FORMULA_URL', plugins_url( '', __FILE__ ) );
define( 'CPF_FORMULA_ADMIN', dirname( __FILE__ ) . '/admin' );
define( 'CPF_FORMULA_INCLUDES', dirname( __FILE__ ) . '/includes' );
define( 'CPF_FORMULA_PUBLIC', dirname( __FILE__ ) . '/public' );
define( 'CPF_FORMULA_PUBLIC_URL', plugins_url( 'public', __FILE__ ) );

//includes files
require_once CPF_FORMULA_INCLUDES . '/class-shortcode.php';

// includes admin dependencies:
if ( is_admin() ) {
	require_once CPF_FORMULA_ADMIN . '/class-settings-api.php';
	require_once CPF_FORMULA_ADMIN . '/class-settings.php';
}


// register and enqueue scripts
add_action( 'wp_enqueue_scripts', 'wcf_formula_scripts' );

function wcf_formula_scripts() {
	//register scripts
	wp_register_style( 'formula-bootstrap', CPF_FORMULA_PUBLIC_URL . '/css/bootstrap.min.css', false, 'CPF_FORMULA_VERSION', 'all' );
	wp_register_style( 'formula-public', CPF_FORMULA_PUBLIC_URL . '/css/public.css', false, 'CPF_FORMULA_VERSION', 'all' );

	wp_register_script( 'formula-birthday', CPF_FORMULA_PUBLIC_URL . '/js/bootstrap-birthday.min.js', [ 'jquery' ], CPF_FORMULA_VERSION, true );
	wp_register_script( 'formula-public', CPF_FORMULA_PUBLIC_URL . '/js/public.js', [ 'jquery' ], CPF_FORMULA_VERSION, true );

	//enqueue scripts
	wp_enqueue_style( 'formula-bootstrap' );
	wp_enqueue_style( 'formula-public' );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'formula-birthday' );
	wp_enqueue_script( 'formula-public' );
}

/**
 * get settings saved options
 *
 * @param        $key
 * @param string $default
 * @param string $section
 *
 * @since 1.0.0
 *
 * @return string|array
 */
function wcf_formula_get_settings( $key, $default = '', $section = 'wcf_formula_general_settings' ) {

	$option = get_option( $section, [] );

	return ! empty( $option[ $key ] ) ? $option[ $key ] : $default;
}