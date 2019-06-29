<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit();

class CPF_Formula_Settings {

	private $settings_api;

	function __construct() {

		$this->settings_api = new Prince_Settings_API();
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 99 );
	}

	function admin_init() {

		//set the settings
		$this->settings_api->set_sections( $this->get_settings_sections() );
		$this->settings_api->set_fields( $this->get_settings_fields() );

		//initialize settings
		$this->settings_api->admin_init();
	}

	function get_settings_sections() {

		$sections = array(
			array(
				'id'    => 'wcf_formula_general_settings',
				'title' => __( 'General Settings', 'wcf-formula' )
			),
		);

		return apply_filters( 'wcf_formula_settings_sections', $sections );
	}

	/**
	 * Returns all the settings fields
	 *
	 * @return array settings fields
	 */

	function get_settings_fields() {

		$settings_fields = array(
			'wcf_formula_general_settings' => array(

				array(
					'name'    => '',
					'label'   => __( '', 'wcf-formula' ),
					'desc'    => __( '', 'wcf-formula' ),
					'class'   => 'prince-field-inline',
					'default' => 10,
					'type'    => 'number',
					'min'     => 1,
				),

			),
		);

		return apply_filters( 'wcf_formula_settings_fields', $settings_fields );
	}

	function admin_menu() {
		add_submenu_page(
			'options-general.php',
			'CPF Formula Settings',
			'CPF Formula',
			'manage_options',
			'cpf-formula-settings',
			array(
				$this,
				'settings_page'
			)
		);
	}

	function settings_page() {

		echo '<div class="wrap">';
		echo sprintf( "<h2>%s</h2>", __( 'CPF Formula Settings', 'wcf-formula' ) );
		$this->settings_api->show_settings();
		echo '</div>';

	}

}

new CPF_Formula_Settings();
