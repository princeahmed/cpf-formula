<?php
// exit if file is called directly
defined('ABSPATH') or die('ABSPATH is not defined!');
// add sub-level administrative menu
function cpf_formula_add_sublevel_menu() {
	
	/*
	
	add_submenu_page(
		string   $parent_slug,
		string   $page_title,
		string   $menu_title,
		string   $capability,
		string   $menu_slug,
		callable $function = ''
	);
	
	*/
	
	add_submenu_page(
		'options-general.php',
		'CPF Formula Settings',
		'CPF Formula',
		'manage_options',
		'cpf_formula',
		'cpf_formula_display_settings_page'
	);
	
}
add_action( 'admin_menu', 'cpf_formula_add_sublevel_menu' );