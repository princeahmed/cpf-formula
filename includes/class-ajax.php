<?php

// exit if file is called directly
defined( 'ABSPATH' ) || exit();

class CPF_Formula_Ajax {

	function __construct() {
		add_action( 'wp_ajax_calculate_contribution', array( $this, 'calculate_contribution' ) );
		add_action( 'wp_ajax_nopriv_calculate_contribution', array( $this, 'calculate_contribution' ) );
	}

	function calculate_contribution() {

		if ( empty( $_REQUEST['form_data'] ) ) {
			return;
		}

		//parsed the serialized form data
		parse_str( urldecode( $_REQUEST['form_data'] ), $form_data );

		//verify nonce
		if ( ! wp_verify_nonce( $form_data['_nonce'], 'calculate_contribution' ) ) {
			return;
		}

		if ( isset( $form_data['birthday'] ) && ! empty( $form_data['birthday'] ) ) {

			$sdl_percentage = cpf_formula_get_settings( 'sdl', .25 );

			$OrdinaryWages     = (int) $form_data['ow'];
			$AdditionalWages   = (int) $form_data['aw'];
			$citizenship       = $form_data['citizenship'];
			$CPFDT             = $form_data['CPFDT'];
			$totalWages        = $OrdinaryWages + (int) $AdditionalWages;
			$sdl               = ( ( $totalWages * $sdl_percentage ) / 100 );
			$sdl               = $sdl > 11.25 ? 11.25 : $sdl;
			$birthdayParam     = $form_data['birthday'];
			$age               = $this->age_calculate( $birthdayParam );
			$pr_effective_date = $form_data['pr_effective_date'];
			$prage             = $this->age_calculate( $pr_effective_date );
			$cpf_donation      = 0;

			if ( isset( $CPFDT ) && ! empty( $CPFDT ) ) {
				$cpf_donation_type_param = [
					'cpf_donation_type' => $CPFDT,
					'tw'                => $totalWages
				];

				$cpf_donation = $this->cpf_donation_type( $cpf_donation_type_param );

			}

			$cpfParam = [
				'OrdinaryWages'   => $OrdinaryWages,
				'AdditionalWages' => $AdditionalWages,
				'totalWages'      => $totalWages,
				'sdl'             => $sdl,
				'age'             => $age,
				'prage'           => $prage,
				'citizenship'     => $citizenship,
				'CPFDT'           => $CPFDT,
				'cpf_donation'    => $cpf_donation,
			];

			if ( $citizenship == 'SC' || ( $citizenship == 'SPR' && $prage >= 2 ) ) {
				$feedback = $this->third_year_onwords( $cpfParam );
			} elseif ( $citizenship == 'SPR' && $prage >= 1 ) {
				$feedback = $this->second_year_onwords( $cpfParam );
			} elseif ( $citizenship == 'SPR' && $prage < 1 ) {
				$feedback = $this->first_year_onwords( $cpfParam );
			}

			//send feedback
			wp_send_json_success( $feedback );
		}

	}

	function age_calculate( $birthdayParam ) {
		//get age from date or birthdate
		$age = ( date( "md", date( "U", mktime( 0, 0, 0, $birthdayParam['month'], $birthdayParam['day'], $birthdayParam['year'] ) ) ) > date( "md" ) ? ( ( date( "Y" ) - $birthdayParam['year'] ) - 1 ) : ( date( "Y" ) - $birthdayParam['year'] ) );

		return $age;
	}

	function first_year_onwords( $data ) {
		$OrdinaryWages   = $data['OrdinaryWages'];
		$AdditionalWages = $data['AdditionalWages'];
		$citizenship     = $data['citizenship'];
		$CPFDT           = $data['CPFDT'];
		$totalWages      = $data['totalWages'];
		$sdl             = $data['sdl'];
		$age             = $data['age'];
		$prage           = $data['prage'];
		$cpf_donation    = $data['cpf_donation'];

		$v = array(
			'55_tw_50_e_e_shares'       => cpf_formula_get_settings( '55_tw_50_e_e_shares', 0, 'cpf_formula_1st_spr' ),
			'55_tw_50_e_shares'         => cpf_formula_get_settings( '55_tw_50_e_shares', 0, 'cpf_formula_1st_spr' ),
			'55_tw_50_500_e_e_shares'   => cpf_formula_get_settings( '55_tw_50_500_e_e_shares', 4, 'cpf_formula_1st_spr' ),
			'55_tw_50_500_e_shares'     => cpf_formula_get_settings( '55_tw_50_500_e_shares', 0, 'cpf_formula_1st_spr' ),
			'55_tw_500_750_e_e_shares'  => cpf_formula_get_settings( '55_tw_500_750_e_e_shares', 4, 'cpf_formula_1st_spr' ),
			'55_tw_500_750_e_e_shares2' => cpf_formula_get_settings( '55_tw_500_750_e_e_shares2', 0.15, 'cpf_formula_1st_spr' ),
			'55_tw_500_750_e_shares'    => cpf_formula_get_settings( '55_tw_500_750_e_shares', 0.15, 'cpf_formula_1st_spr' ),
			'55_tw_750_e_e_shares'      => cpf_formula_get_settings( '55_tw_50_e_e_shares', 9, 'cpf_formula_1st_spr' ),
			'55_tw_750_e_e_shares2'     => cpf_formula_get_settings( '55_tw_50_e_e_shares2', 9, 'cpf_formula_1st_spr' ),
			'55_tw_750_e_shares'        => cpf_formula_get_settings( '55_tw_50_e_shares', 5, 'cpf_formula_1st_spr' ),
			'55_tw_750_e_shares2'       => cpf_formula_get_settings( '55_tw_50_e_shares2', 5, 'cpf_formula_1st_spr' ),

			'50_60_tw_50_e_e_shares'       => cpf_formula_get_settings( '50_60_tw_50_e_e_shares', 0, 'cpf_formula_1st_spr' ),
			'50_60_tw_50_e_shares'         => cpf_formula_get_settings( '50_60_tw_50_e_shares', 0, 'cpf_formula_1st_spr' ),
			'50_60_tw_50_500_e_e_shares'   => cpf_formula_get_settings( '50_60_tw_50_500_e_e_shares', 4, 'cpf_formula_1st_spr' ),
			'50_60_tw_50_500_e_shares'     => cpf_formula_get_settings( '50_60_tw_50_500_e_shares', 0, 'cpf_formula_1st_spr' ),
			'50_60_tw_500_750_e_e_shares'  => cpf_formula_get_settings( '50_60_tw_500_750_e_e_shares', 4, 'cpf_formula_1st_spr' ),
			'50_60_tw_500_750_e_e_shares2' => cpf_formula_get_settings( '50_60_tw_500_750_e_e_shares2', 0.15, 'cpf_formula_1st_spr' ),
			'50_60_tw_500_750_e_shares'    => cpf_formula_get_settings( '50_60_tw_500_750_e_shares', 0.15, 'cpf_formula_1st_spr' ),
			'50_60_tw_750_e_e_shares'      => cpf_formula_get_settings( '50_60_tw_750_e_e_shares', 9, 'cpf_formula_1st_spr' ),
			'50_60_tw_750_e_e_shares2'     => cpf_formula_get_settings( '50_60_tw_750_e_e_shares2', 9, 'cpf_formula_1st_spr' ),
			'50_60_tw_750_e_shares'        => cpf_formula_get_settings( '50_60_tw_750_e_shares', 5, 'cpf_formula_1st_spr' ),
			'50_60_tw_750_e_shares2'       => cpf_formula_get_settings( '50_60_tw_750_e_shares2', 5, 'cpf_formula_1st_spr' ),

			'60_65_tw_50_e_e_shares'       => cpf_formula_get_settings( '60_65_tw_50_e_e_shares', 0, 'cpf_formula_1st_spr' ),
			'60_65_tw_50_e_shares'         => cpf_formula_get_settings( '60_65_tw_50_e_shares', 0, 'cpf_formula_1st_spr' ),
			'60_65_tw_50_500_e_e_shares'   => cpf_formula_get_settings( '60_65_tw_50_500_e_e_shares', 3.5, 'cpf_formula_1st_spr' ),
			'60_65_tw_50_500_e_shares'     => cpf_formula_get_settings( '60_65_tw_50_500_e_shares', 0, 'cpf_formula_1st_spr' ),
			'60_65_tw_500_750_e_e_shares'  => cpf_formula_get_settings( '60_65_tw_500_750_e_e_shares', 3.5, 'cpf_formula_1st_spr' ),
			'60_65_tw_500_750_e_e_shares2' => cpf_formula_get_settings( '60_65_tw_500_750_e_e_shares2', 0.15, 'cpf_formula_1st_spr' ),
			'60_65_tw_500_750_e_shares'    => cpf_formula_get_settings( '60_65_tw_500_750_e_shares', 0.15, 'cpf_formula_1st_spr' ),
			'60_65_tw_750_e_e_shares'      => cpf_formula_get_settings( '60_65_tw_750_e_e_shares', 8.5, 'cpf_formula_1st_spr' ),
			'60_65_tw_750_e_e_shares2'     => cpf_formula_get_settings( '60_65_tw_750_e_e_shares2', 8.5, 'cpf_formula_1st_spr' ),
			'60_65_tw_750_e_shares'        => cpf_formula_get_settings( '60_65_tw_750_e_shares', 5, 'cpf_formula_1st_spr' ),
			'60_65_tw_750_e_shares2'       => cpf_formula_get_settings( '60_65_tw_750_e_shares2', 5, 'cpf_formula_1st_spr' ),

			'65_tw_50_e_e_shares'       => cpf_formula_get_settings( '65_tw_50_e_e_shares', 0, 'cpf_formula_1st_spr' ),
			'65_tw_50_e_shares'         => cpf_formula_get_settings( '65_tw_50_e_shares', 0, 'cpf_formula_1st_spr' ),
			'65_tw_50_500_e_e_shares'   => cpf_formula_get_settings( '65_tw_50_500_e_e_shares', 3.5, 'cpf_formula_1st_spr' ),
			'65_tw_50_500_e_shares'     => cpf_formula_get_settings( '65_tw_50_500_e_shares', 0, 'cpf_formula_1st_spr' ),
			'65_tw_500_750_e_e_shares'  => cpf_formula_get_settings( '65_tw_500_750_e_e_shares', 3.5, 'cpf_formula_1st_spr' ),
			'65_tw_500_750_e_e_shares2' => cpf_formula_get_settings( '65_tw_500_750_e_e_shares2', 0.15, 'cpf_formula_1st_spr' ),
			'65_tw_500_750_e_shares'    => cpf_formula_get_settings( '65_tw_500_750_e_shares', 0.15, 'cpf_formula_1st_spr' ),
			'65_tw_750_e_e_shares'      => cpf_formula_get_settings( '65_tw_750_e_e_shares', 8.5, 'cpf_formula_1st_spr' ),
			'65_tw_750_e_e_shares2'     => cpf_formula_get_settings( '65_tw_750_e_e_shares2', 8.5, 'cpf_formula_1st_spr' ),
			'65_tw_750_e_shares'        => cpf_formula_get_settings( '65_tw_750_e_shares', 5, 'cpf_formula_1st_spr' ),
			'65_tw_750_e_shares2'       => cpf_formula_get_settings( '65_tw_750_e_shares2', 5, 'cpf_formula_1st_spr' ),
		);

		if ( $age <= 55 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = 0;
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * $v['55_tw_50_500_e_e_shares'] ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * $v['55_tw_500_750_e_e_shares'] ) / 100 ) + $v['55_tw_500_750_e_e_shares2'] * ( $totalWages - 500 ) );
				$EmployeesShare        = $v['55_tw_500_750_e_shares'] * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * $v['55_tw_750_e_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['55_tw_750_e_e_shares2'] ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * $v['55_tw_750_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['55_tw_750_e_shares'] ) / 100 ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}

		} elseif ( $age > 55 && $age < 60 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * $v['55_60_tw_50_500_e_e_shares'] ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * $v['55_60_tw_500_750_e_e_shares'] ) / 100 ) + $v['55_60_tw_500_750_e_e_shares2'] * ( $totalWages - 500 ) );
				$EmployeesShare        = $v['55_60_tw_500_750_e_shares'] * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * $v['55_60_tw_750_e_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['55_60_tw_750_e_e_shares2'] ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * $v['55_60_tw_750_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['55_60_tw_750_e_shares2'] ) / 100 ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}
		} elseif ( $age > 60 && $age < 65 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * $v['60_65_tw_50_500_e_e_shares'] ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * $v['60_65_tw_500_750_e_e_shares'] ) / 100 ) + $v['60_65_tw_500_750_e_e_shares2'] * ( $totalWages - 500 ) );
				$EmployeesShare        = $v['60_65_tw_500_750_e_shares'] * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * $v['60_65_tw_750_e_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['60_65_tw_750_e_e_shares2'] ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * $v['60_65_tw_750_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['60_65_tw_750_e_shares2'] ) / 100 ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}
		} elseif ( $age >= 65 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * $v['65_tw_50_500_e_e_shares'] ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * $v['65_tw_500_750_e_e_shares'] ) / 100 ) + $v['65_tw_500_750_e_e_shares2'] * ( $totalWages - 500 ) );
				$EmployeesShare        = $v['65_tw_500_750_e_shares'] * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * $v['65_tw_750_e_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['65_tw_750_e_e_shares2'] ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * $v['65_tw_750_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['65_tw_750_e_shares2'] ) / 100 ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}
		}
		$netSal = $totalWages - ( $EmployeesShare + $cpf_donation );

		// amount formated:
		$TotalCPFContributions = ceil( $TotalCPFContributions );
		$EmployeesShare        = ceil( $EmployeesShare );
		$EmployerShare         = ceil( $EmployerShare );
		$sdl                   = number_format( $sdl, 2 );
		$netSal                = number_format( $netSal, 2 );
		$cpf_donation          = number_format( $cpf_donation, 2 );

		return $feedback = [
			'status'                => 'success',
			'TotalCPFContributions' => '$' . number_format( $TotalCPFContributions, 2 ),
			'EmployeesShare'        => '$' . number_format( $EmployeesShare, 2 ),
			'EmployerShare'         => '$' . number_format( $EmployerShare, 2 ),
			'sdl'                   => '$' . $sdl,
			'netSal'                => '$' . $netSal,
			'cpf_donation'          => $CPFDT . ' $' . $cpf_donation,
		];
	}

	function second_year_onwords( $data ) {
		$OrdinaryWages   = $data['OrdinaryWages'];
		$AdditionalWages = $data['AdditionalWages'];
		$citizenship     = $data['citizenship'];
		$CPFDT           = $data['CPFDT'];
		$totalWages      = $data['totalWages'];
		$sdl             = $data['sdl'];
		$age             = $data['age'];
		$prage           = $data['prage'];
		$cpf_donation    = $data['cpf_donation'];

		$v = array(
			'55_tw_50_e_e_shares'       => cpf_formula_get_settings( '55_tw_50_e_e_shares', 0, 'cpf_formula_2nd_spr' ),
			'55_tw_50_e_shares'         => cpf_formula_get_settings( '55_tw_50_e_shares', 0, 'cpf_formula_2nd_spr' ),
			'55_tw_50_500_e_e_shares'   => cpf_formula_get_settings( '55_tw_50_500_e_e_shares', 9, 'cpf_formula_2nd_spr' ),
			'55_tw_50_500_e_shares'     => cpf_formula_get_settings( '55_tw_50_500_e_shares', 0, 'cpf_formula_2nd_spr' ),
			'55_tw_500_750_e_e_shares'  => cpf_formula_get_settings( '55_tw_500_750_e_e_shares', 9, 'cpf_formula_2nd_spr' ),
			'55_tw_500_750_e_e_shares2' => cpf_formula_get_settings( '55_tw_500_750_e_e_shares2', 0.45, 'cpf_formula_2nd_spr' ),
			'55_tw_500_750_e_shares'    => cpf_formula_get_settings( '55_tw_500_750_e_shares', 0.45, 'cpf_formula_2nd_spr' ),
			'55_tw_750_e_e_shares'      => cpf_formula_get_settings( '55_tw_50_e_e_shares', 24, 'cpf_formula_2nd_spr' ),
			'55_tw_750_e_e_shares2'     => cpf_formula_get_settings( '55_tw_50_e_e_shares2', 24, 'cpf_formula_2nd_spr' ),
			'55_tw_750_e_shares'        => cpf_formula_get_settings( '55_tw_50_e_shares', 15, 'cpf_formula_2nd_spr' ),
			'55_tw_750_e_shares2'       => cpf_formula_get_settings( '55_tw_50_e_shares2', 15, 'cpf_formula_2nd_spr' ),

			'50_60_tw_50_e_e_shares'       => cpf_formula_get_settings( '50_60_tw_50_e_e_shares', 0, 'cpf_formula_2nd_spr' ),
			'50_60_tw_50_e_shares'         => cpf_formula_get_settings( '50_60_tw_50_e_shares', 0, 'cpf_formula_2nd_spr' ),
			'50_60_tw_50_500_e_e_shares'   => cpf_formula_get_settings( '50_60_tw_50_500_e_e_shares', 6, 'cpf_formula_2nd_spr' ),
			'50_60_tw_50_500_e_shares'     => cpf_formula_get_settings( '50_60_tw_50_500_e_shares', 0, 'cpf_formula_2nd_spr' ),
			'50_60_tw_500_750_e_e_shares'  => cpf_formula_get_settings( '50_60_tw_500_750_e_e_shares', 6, 'cpf_formula_2nd_spr' ),
			'50_60_tw_500_750_e_e_shares2' => cpf_formula_get_settings( '50_60_tw_500_750_e_e_shares2', 0.375, 'cpf_formula_2nd_spr' ),
			'50_60_tw_500_750_e_shares'    => cpf_formula_get_settings( '50_60_tw_500_750_e_shares', 0.375, 'cpf_formula_2nd_spr' ),
			'50_60_tw_750_e_e_shares'      => cpf_formula_get_settings( '50_60_tw_750_e_e_shares', 18.5, 'cpf_formula_2nd_spr' ),
			'50_60_tw_750_e_e_shares2'     => cpf_formula_get_settings( '50_60_tw_750_e_e_shares2', 18.5, 'cpf_formula_2nd_spr' ),
			'50_60_tw_750_e_shares'        => cpf_formula_get_settings( '50_60_tw_750_e_shares', 12.5, 'cpf_formula_2nd_spr' ),
			'50_60_tw_750_e_shares2'       => cpf_formula_get_settings( '50_60_tw_750_e_shares2', 12.5, 'cpf_formula_2nd_spr' ),

			'60_65_tw_50_e_e_shares'       => cpf_formula_get_settings( '60_65_tw_50_e_e_shares', 0, 'cpf_formula_2nd_spr' ),
			'60_65_tw_50_e_shares'         => cpf_formula_get_settings( '60_65_tw_50_e_shares', 0, 'cpf_formula_2nd_spr' ),
			'60_65_tw_50_500_e_e_shares'   => cpf_formula_get_settings( '60_65_tw_50_500_e_e_shares', 3.5, 'cpf_formula_2nd_spr' ),
			'60_65_tw_50_500_e_shares'     => cpf_formula_get_settings( '60_65_tw_50_500_e_shares', 0, 'cpf_formula_2nd_spr' ),
			'60_65_tw_500_750_e_e_shares'  => cpf_formula_get_settings( '60_65_tw_500_750_e_e_shares', 3.5, 'cpf_formula_2nd_spr' ),
			'60_65_tw_500_750_e_e_shares2' => cpf_formula_get_settings( '60_65_tw_500_750_e_e_shares2', 0.225, 'cpf_formula_2nd_spr' ),
			'60_65_tw_500_750_e_shares'    => cpf_formula_get_settings( '60_65_tw_500_750_e_shares', 0.225, 'cpf_formula_2nd_spr' ),
			'60_65_tw_750_e_e_shares'      => cpf_formula_get_settings( '60_65_tw_750_e_e_shares', 11, 'cpf_formula_2nd_spr' ),
			'60_65_tw_750_e_e_shares2'     => cpf_formula_get_settings( '60_65_tw_750_e_e_shares2', 11, 'cpf_formula_2nd_spr' ),
			'60_65_tw_750_e_shares'        => cpf_formula_get_settings( '60_65_tw_750_e_shares', 7.5, 'cpf_formula_2nd_spr' ),
			'60_65_tw_750_e_shares2'       => cpf_formula_get_settings( '60_65_tw_750_e_shares2', 7.5, 'cpf_formula_2nd_spr' ),

			'65_tw_50_e_e_shares'       => cpf_formula_get_settings( '65_tw_50_e_e_shares', 0, 'cpf_formula_2nd_spr' ),
			'65_tw_50_e_shares'         => cpf_formula_get_settings( '65_tw_50_e_shares', 0, 'cpf_formula_2nd_spr' ),
			'65_tw_50_500_e_e_shares'   => cpf_formula_get_settings( '65_tw_50_500_e_e_shares', 3.5, 'cpf_formula_2nd_spr' ),
			'65_tw_50_500_e_shares'     => cpf_formula_get_settings( '65_tw_50_500_e_shares', 0, 'cpf_formula_2nd_spr' ),
			'65_tw_500_750_e_e_shares'  => cpf_formula_get_settings( '65_tw_500_750_e_e_shares', 3.5, 'cpf_formula_2nd_spr' ),
			'65_tw_500_750_e_e_shares2' => cpf_formula_get_settings( '65_tw_500_750_e_e_shares2', 0.15, 'cpf_formula_2nd_spr' ),
			'65_tw_500_750_e_shares'    => cpf_formula_get_settings( '65_tw_500_750_e_shares', 0.15, 'cpf_formula_2nd_spr' ),
			'65_tw_750_e_e_shares'      => cpf_formula_get_settings( '65_tw_750_e_e_shares', 8.5, 'cpf_formula_2nd_spr' ),
			'65_tw_750_e_e_shares2'     => cpf_formula_get_settings( '65_tw_750_e_e_shares2', 8.5, 'cpf_formula_2nd_spr' ),
			'65_tw_750_e_shares'        => cpf_formula_get_settings( '65_tw_750_e_shares', 5, 'cpf_formula_2nd_spr' ),
			'65_tw_750_e_shares2'       => cpf_formula_get_settings( '65_tw_750_e_shares2', 5, 'cpf_formula_2nd_spr' ),
		);

		if ( $age <= 55 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = 0;
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * $v['55_tw_50_500_e_e_shares'] ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * $v['55_tw_500_750_e_e_shares'] ) / 100 ) + $v['55_tw_500_750_e_e_shares2'] * ( $totalWages - 500 ) );
				$EmployeesShare        = $v['55_tw_500_750_e_shares'] * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * $v['55_tw_750_e_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['55_tw_750_e_e_shares2'] ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * $v['55_tw_750_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['55_tw_750_e_shares2'] ) / 100 ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}
		} elseif ( $age > 55 && $age < 60 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * $v['50_60_tw_50_500_e_e_shares'] ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * $v['50_60_tw_500_750_e_e_shares'] ) / 100 ) + $v['50_60_tw_500_750_e_e_shares2'] * ( $totalWages - 500 ) );
				$EmployeesShare        = $v['50_60_tw_500_750_e_shares'] * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * $v['50_60_tw_750_e_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['50_60_tw_750_e_e_shares2'] ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * $v['50_60_tw_750_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['50_60_tw_750_e_shares2'] ) / 100 ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}
		} elseif ( $age > 60 && $age < 65 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * $v['60_65_tw_50_500_e_e_shares'] ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * $v['60_65_tw_500_750_e_e_shares'] ) / 100 ) + $v['60_65_tw_500_750_e_e_shares2'] * ( $totalWages - 500 ) );
				$EmployeesShare        = $v['60_65_tw_500_750_e_shares'] * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * $v['60_65_tw_750_e_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['60_65_tw_750_e_e_shares2'] ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * $v['60_65_tw_750_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['60_65_tw_750_e_shares2'] ) / 100 ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}
		} elseif ( $age >= 65 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * $v['65_tw_50_500_e_e_shares'] ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * $v['65_tw_500_750_e_e_shares'] ) / 100 ) + $v['65_tw_500_750_e_e_shares2'] * ( $totalWages - 500 ) );
				$EmployeesShare        = $v['65_tw_500_750_e_shares'] * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * $v['65_tw_750_e_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['65_tw_750_e_e_shares2'] ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * $v['65_tw_750_e_shares'] ) / 100 ) + ( ( $AdditionalWages * 5 ) / $v['65_tw_750_e_shares2'] ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}
		}

		$netSal = $totalWages - ( $EmployeesShare + $cpf_donation );
		// amount formated:
		$TotalCPFContributions = ceil( $TotalCPFContributions );
		$EmployeesShare        = ceil( $EmployeesShare );
		$EmployerShare         = ceil( $EmployerShare );
		$sdl                   = number_format( $sdl, 2 );
		$netSal                = number_format( $netSal, 2 );
		$cpf_donation          = number_format( $cpf_donation, 2 );

		return $feedback = [
			'status'                => 'success',
			'TotalCPFContributions' => '$' . number_format( $TotalCPFContributions, 2 ),
			'EmployeesShare'        => '$' . number_format( $EmployeesShare, 2 ),
			'EmployerShare'         => '$' . number_format( $EmployerShare, 2 ),
			'sdl'                   => '$' . $sdl,
			'netSal'                => '$' . $netSal,
			'cpf_donation'          => $CPFDT . ' $' . $cpf_donation,
		];
	}

	function third_year_onwords( $data ) {
		$OrdinaryWages   = $data['OrdinaryWages'];
		$AdditionalWages = $data['AdditionalWages'];
		$citizenship     = $data['citizenship'];
		$CPFDT           = $data['CPFDT'];
		$totalWages      = $data['totalWages'];
		$sdl             = $data['sdl'];
		$age             = $data['age'];
		$prage           = $data['prage'];
		$cpf_donation    = $data['cpf_donation'];

		$v = array(
			'55_tw_50_e_e_shares'       => cpf_formula_get_settings( '55_tw_50_e_e_shares', 0, 'cpf_formula_3rd_spr' ),
			'55_tw_50_e_shares'         => cpf_formula_get_settings( '55_tw_50_e_shares', 0, 'cpf_formula_3rd_spr' ),
			'55_tw_50_500_e_e_shares'   => cpf_formula_get_settings( '55_tw_50_500_e_e_shares', 17, 'cpf_formula_3rd_spr' ),
			'55_tw_50_500_e_shares'     => cpf_formula_get_settings( '55_tw_50_500_e_shares', 0, 'cpf_formula_3rd_spr' ),
			'55_tw_500_750_e_e_shares'  => cpf_formula_get_settings( '55_tw_500_750_e_e_shares', 17, 'cpf_formula_3rd_spr' ),
			'55_tw_500_750_e_e_shares2' => cpf_formula_get_settings( '55_tw_500_750_e_e_shares2', 0.6, 'cpf_formula_3rd_spr' ),
			'55_tw_500_750_e_shares'    => cpf_formula_get_settings( '55_tw_500_750_e_shares', 0.6, 'cpf_formula_3rd_spr' ),
			'55_tw_750_e_e_shares'      => cpf_formula_get_settings( '55_tw_50_e_e_shares', 37, 'cpf_formula_3rd_spr' ),
			'55_tw_750_e_e_shares2'     => cpf_formula_get_settings( '55_tw_50_e_e_shares2', 37, 'cpf_formula_3rd_spr' ),
			'55_tw_750_e_shares'        => cpf_formula_get_settings( '55_tw_50_e_shares', 20, 'cpf_formula_3rd_spr' ),
			'55_tw_750_e_shares2'       => cpf_formula_get_settings( '55_tw_50_e_shares2', 20, 'cpf_formula_3rd_spr' ),

			'50_60_tw_50_e_e_shares'       => cpf_formula_get_settings( '50_60_tw_50_e_e_shares', 0, 'cpf_formula_3rd_spr' ),
			'50_60_tw_50_e_shares'         => cpf_formula_get_settings( '50_60_tw_50_e_shares', 0, 'cpf_formula_3rd_spr' ),
			'50_60_tw_50_500_e_e_shares'   => cpf_formula_get_settings( '50_60_tw_50_500_e_e_shares', 13, 'cpf_formula_3rd_spr' ),
			'50_60_tw_50_500_e_shares'     => cpf_formula_get_settings( '50_60_tw_50_500_e_shares', 0, 'cpf_formula_3rd_spr' ),
			'50_60_tw_500_750_e_e_shares'  => cpf_formula_get_settings( '50_60_tw_500_750_e_e_shares', 13, 'cpf_formula_3rd_spr' ),
			'50_60_tw_500_750_e_e_shares2' => cpf_formula_get_settings( '50_60_tw_500_750_e_e_shares2', 0.39, 'cpf_formula_3rd_spr' ),
			'50_60_tw_500_750_e_shares'    => cpf_formula_get_settings( '50_60_tw_500_750_e_shares', 0.39, 'cpf_formula_3rd_spr' ),
			'50_60_tw_750_e_e_shares'      => cpf_formula_get_settings( '50_60_tw_750_e_e_shares', 26, 'cpf_formula_3rd_spr' ),
			'50_60_tw_750_e_e_shares2'     => cpf_formula_get_settings( '50_60_tw_750_e_e_shares2', 26, 'cpf_formula_3rd_spr' ),
			'50_60_tw_750_e_shares'        => cpf_formula_get_settings( '50_60_tw_750_e_shares', 13, 'cpf_formula_3rd_spr' ),
			'50_60_tw_750_e_shares2'       => cpf_formula_get_settings( '50_60_tw_750_e_shares2', 13, 'cpf_formula_3rd_spr' ),

			'60_65_tw_50_e_e_shares'       => cpf_formula_get_settings( '60_65_tw_50_e_e_shares', 0, 'cpf_formula_3rd_spr' ),
			'60_65_tw_50_e_shares'         => cpf_formula_get_settings( '60_65_tw_50_e_shares', 0, 'cpf_formula_3rd_spr' ),
			'60_65_tw_50_500_e_e_shares'   => cpf_formula_get_settings( '60_65_tw_50_500_e_e_shares', 19, 'cpf_formula_3rd_spr' ),
			'60_65_tw_50_500_e_shares'     => cpf_formula_get_settings( '60_65_tw_50_500_e_shares', 0, 'cpf_formula_3rd_spr' ),
			'60_65_tw_500_750_e_e_shares'  => cpf_formula_get_settings( '60_65_tw_500_750_e_e_shares', 19, 'cpf_formula_3rd_spr' ),
			'60_65_tw_500_750_e_e_shares2' => cpf_formula_get_settings( '60_65_tw_500_750_e_e_shares2', 0.225, 'cpf_formula_3rd_spr' ),
			'60_65_tw_500_750_e_shares'    => cpf_formula_get_settings( '60_65_tw_500_750_e_shares', 0.225, 'cpf_formula_3rd_spr' ),
			'60_65_tw_750_e_e_shares'      => cpf_formula_get_settings( '60_65_tw_750_e_e_shares', 16.5, 'cpf_formula_3rd_spr' ),
			'60_65_tw_750_e_e_shares2'     => cpf_formula_get_settings( '60_65_tw_750_e_e_shares2', 16.5, 'cpf_formula_3rd_spr' ),
			'60_65_tw_750_e_shares'        => cpf_formula_get_settings( '60_65_tw_750_e_shares', 7.5, 'cpf_formula_3rd_spr' ),
			'60_65_tw_750_e_shares2'       => cpf_formula_get_settings( '60_65_tw_750_e_shares2', 7.5, 'cpf_formula_3rd_spr' ),

			'65_tw_50_e_e_shares'       => cpf_formula_get_settings( '65_tw_50_e_e_shares', 0, 'cpf_formula_3rd_spr' ),
			'65_tw_50_e_shares'         => cpf_formula_get_settings( '65_tw_50_e_shares', 0, 'cpf_formula_3rd_spr' ),
			'65_tw_50_500_e_e_shares'   => cpf_formula_get_settings( '65_tw_50_500_e_e_shares', 7.5, 'cpf_formula_3rd_spr' ),
			'65_tw_50_500_e_shares'     => cpf_formula_get_settings( '65_tw_50_500_e_shares', 0, 'cpf_formula_3rd_spr' ),
			'65_tw_500_750_e_e_shares'  => cpf_formula_get_settings( '65_tw_500_750_e_e_shares', 7.5, 'cpf_formula_3rd_spr' ),
			'65_tw_500_750_e_e_shares2' => cpf_formula_get_settings( '65_tw_500_750_e_e_shares2', 0.15, 'cpf_formula_3rd_spr' ),
			'65_tw_500_750_e_shares'    => cpf_formula_get_settings( '65_tw_500_750_e_shares', 0.15, 'cpf_formula_3rd_spr' ),
			'65_tw_750_e_e_shares'      => cpf_formula_get_settings( '65_tw_750_e_e_shares', 12.5, 'cpf_formula_3rd_spr' ),
			'65_tw_750_e_e_shares2'     => cpf_formula_get_settings( '65_tw_750_e_e_shares2', 12.5, 'cpf_formula_3rd_spr' ),
			'65_tw_750_e_shares'        => cpf_formula_get_settings( '65_tw_750_e_shares', 5, 'cpf_formula_3rd_spr' ),
			'65_tw_750_e_shares2'       => cpf_formula_get_settings( '65_tw_750_e_shares2', 5, 'cpf_formula_3rd_spr' ),
		);

		if ( $age <= 55 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = 0;
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * $v['55_tw_50_500_e_e_shares'] ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * $v['55_tw_500_750_e_e_shares'] ) / 100 ) + $v['55_tw_500_750_e_e_shares2'] * ( $totalWages - 500 ) );
				$EmployeesShare        = $v['55_tw_500_750_e_shares'] * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * $v['55_tw_750_e_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['55_tw_750_e_e_shares2'] ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * $v['55_tw_750_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['55_tw_750_e_shares2'] ) / 100 ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}
		} elseif ( $age > 55 && $age < 60 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * $v['50_60_tw_50_500_e_e_shares'] ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * $v['50_60_tw_500_750_e_e_shares'] ) / 100 ) + $v['50_60_tw_500_750_e_e_shares2'] * ( $totalWages - 500 ) );
				$EmployeesShare        = $v['50_60_tw_500_750_e_shares'] * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * $v['50_60_tw_750_e_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['50_60_tw_750_e_e_shares2'] ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * $v['50_60_tw_750_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['50_60_tw_750_e_shares2'] ) / 100 ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}
		} elseif ( $age > 60 && $age < 65 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * $v['60_65_tw_50_500_e_e_shares'] ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * $v['60_65_tw_500_750_e_e_shares'] ) / 100 ) + $v['60_65_tw_500_750_e_e_shares2'] * ( $totalWages - 500 ) );
				$EmployeesShare        = $v['60_65_tw_500_750_e_shares'] * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * $v['60_65_tw_750_e_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['60_65_tw_750_e_e_shares2'] ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * $v['60_65_tw_750_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['60_65_tw_750_e_shares2'] ) / 100 ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}
		} elseif ( $age >= 65 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * $v['65_tw_50_500_e_e_shares'] ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * $v['65_tw_500_750_e_e_shares'] ) / 100 ) + $v['65_tw_500_750_e_e_shares2'] * ( $totalWages - 500 ) );
				$EmployeesShare        = $v['65_tw_500_750_e_shares'] * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * $v['65_tw_750_e_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['65_tw_750_e_e_shares2'] ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * $v['65_tw_750_e_shares'] ) / 100 ) + ( ( $AdditionalWages * $v['65_tw_750_e_shares2'] ) / 100 ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}
		}

		$netSal = $totalWages - ( $EmployeesShare + $cpf_donation );

		//amount formated:
		$TotalCPFContributions = ceil( $TotalCPFContributions );
		$EmployeesShare        = ceil( $EmployeesShare );
		$EmployerShare         = ceil( $EmployerShare );
		$sdl                   = number_format( $sdl, 2 );
		$netSal                = number_format( $netSal, 2 );
		$cpf_donation          = number_format( $cpf_donation, 2 );

		return $feedback = [
			'status'                => 'success',
			'TotalCPFContributions' => '$' . number_format( $TotalCPFContributions, 2 ),
			'EmployeesShare'        => '$' . number_format( $EmployeesShare, 2 ),
			'EmployerShare'         => '$' . number_format( $EmployerShare, 2 ),
			'sdl'                   => '$' . $sdl,
			'netSal'                => '$' . $netSal,
			'cpf_donation'          => $CPFDT . ' $' . $cpf_donation,
		];
	}

	function cpf_donation_type( $data ) {
		$cpf_donation_type = $data['cpf_donation_type'];
		$tw                = $data['tw'];
		$cpf_donation      = 0;

		$cdac_settings = array(
			'tw_less_than_2'      => cpf_formula_get_settings( 'tw_less_than_2', '.50', 'cpf_formula_cdac_settings' ),
			'tw_2_to_3.5'         => cpf_formula_get_settings( 'tw_2_to_3.5', '1.00', 'cpf_formula_cdac_settings' ),
			'tw_3.5_to_5'         => cpf_formula_get_settings( 'tw_3.5_to_5', '1.50', 'cpf_formula_cdac_settings' ),
			'tw_5_to_7.5'         => cpf_formula_get_settings( 'tw_5_to_7.5', '2.00', 'cpf_formula_cdac_settings' ),
			'tw_greater_than_7.5' => cpf_formula_get_settings( 'tw_greater_than_7.5', '3.00', 'cpf_formula_cdac_settings' ),
		);

		$sinda_settings = array(
			'tw_less_than_1'     => cpf_formula_get_settings( 'tw_less_than_1', '1.0', 'cpf_formula_sinda_settings' ),
			'tw_1_to_1.5'        => cpf_formula_get_settings( 'tw_1_to_1.5', '3.0', 'cpf_formula_sinda_settings' ),
			'tw_1.5_to_2.5'      => cpf_formula_get_settings( 'tw_1.5_to_2.5', '5.0', 'cpf_formula_sinda_settings' ),
			'tw_2.5_to_4.5'      => cpf_formula_get_settings( 'tw_2.5_to_4.5', '7.0', 'cpf_formula_sinda_settings' ),
			'tw_4.5_to_7.5'      => cpf_formula_get_settings( 'tw_4.5_to_7.5', '9.0', 'cpf_formula_sinda_settings' ),
			'tw_7.5_to_10'       => cpf_formula_get_settings( 'tw_7.5_to_10', '12.0', 'cpf_formula_sinda_settings' ),
			'tw_10_to_15'        => cpf_formula_get_settings( 'tw_10_to_15', '18.0', 'cpf_formula_sinda_settings' ),
			'tw_greater_than_15' => cpf_formula_get_settings( 'tw_greater_than_15', '30.0', 'cpf_formula_sinda_settings' ),
		);

		$ecf_settings = array(
			'tw_less_than_1'     => cpf_formula_get_settings( 'tw_less_than_1', '2.0', 'cpf_formula_ecf_settings' ),
			'tw_1_to_1.5'        => cpf_formula_get_settings( 'tw_1_to_1.5', '4.0', 'cpf_formula_ecf_settings' ),
			'tw_1.5_to_2.5'      => cpf_formula_get_settings( 'tw_1.5_to_2.5', '6.0', 'cpf_formula_ecf_settings' ),
			'tw_2.5_to_4'        => cpf_formula_get_settings( 'tw_2.5_to_4', '9.0', 'cpf_formula_ecf_settings' ),
			'tw_4_to_7'          => cpf_formula_get_settings( 'tw_4_to_7', '12.0', 'cpf_formula_ecf_settings' ),
			'tw_7_to_10'         => cpf_formula_get_settings( 'tw_7_to_10', '16.0', 'cpf_formula_ecf_settings' ),
			'tw_greater_than_10' => cpf_formula_get_settings( 'tw_greater_than_10', '20.0', 'cpf_formula_ecf_settings' ),
		);

		$mbmf_settings = array(
			'tw_less_than_1'     => cpf_formula_get_settings( 'tw_less_than_1', '3.0', 'cpf_formula_mbmf_settings' ),
			'tw_1_to_2'          => cpf_formula_get_settings( 'tw_1_to_2', '4.5', 'cpf_formula_mbmf_settings' ),
			'tw_2_to_3'          => cpf_formula_get_settings( 'tw_2_to_3', '6.5', 'cpf_formula_mbmf_settings' ),
			'tw_3_to_4'          => cpf_formula_get_settings( 'tw_3_to_4', '15.0', 'cpf_formula_mbmf_settings' ),
			'tw_4_to_6'          => cpf_formula_get_settings( 'tw_4_to_6', '19.5', 'cpf_formula_mbmf_settings' ),
			'tw_6_to_8'          => cpf_formula_get_settings( 'tw_6_to_8', '22.0', 'cpf_formula_mbmf_settings' ),
			'tw_8_to_10'         => cpf_formula_get_settings( 'tw_8_to_10', '24.0', 'cpf_formula_mbmf_settings' ),
			'tw_greater_than_10' => cpf_formula_get_settings( 'tw_greater_than_10', '26.0', 'cpf_formula_mbmf_settings' ),
		);


		switch ( $cpf_donation_type ) {

			case 'CDAC':
				if ( $tw <= 2000 ) {
					$cpf_donation = $cdac_settings['tw_less_than_2'];
				} elseif ( $tw > 2000 && $tw <= 3500 ) {
					$cpf_donation = $cdac_settings['tw_2_to_3.5'];
				} elseif ( $tw > 3500 && $tw <= 5000 ) {
					$cpf_donation = $cdac_settings['tw_3.5_to_5'];
				} elseif ( $tw > 5000 && $tw <= 7500 ) {
					$cpf_donation = $cdac_settings['tw_5_to_7.5'];
				} elseif ( $tw > 7500 ) {
					$cpf_donation = $cdac_settings['tw_greater_than_7.5'];
				}
				break;

			case 'SINDA':
				if ( $tw <= 1000 ) {
					$cpf_donation = $sinda_settings['tw_less_than_1'];
				} elseif ( $tw > 1000 && $tw <= 1500 ) {
					$cpf_donation = $sinda_settings['tw_1_to_1.5'];
				} elseif ( $tw > 1500 && $tw <= 2500 ) {
					$cpf_donation = $sinda_settings['tw_1.5_to_2.5'];
				} elseif ( $tw > 2500 && $tw <= 4500 ) {
					$cpf_donation = $sinda_settings['tw_2.5_to_4.5'];
				} elseif ( $tw > 4500 && $tw <= 7500 ) {
					$cpf_donation = $sinda_settings['tw_4.5_to_7.5'];
				} elseif ( $tw > 7500 && $tw <= 10000 ) {
					$cpf_donation = $sinda_settings['tw_7.5_to_10'];
				} elseif ( $tw > 10000 && $tw <= 15000 ) {
					$cpf_donation = $sinda_settings['tw_10_to_15'];
				} elseif ( $tw > 15000 ) {
					$cpf_donation = $sinda_settings['tw_greater_than_15'];
				}
				break;

			case 'ECF':
				if ( $tw <= 1000 ) {
					$cpf_donation = $ecf_settings['tw_less_than_1'];
				} elseif ( $tw > 1000 && $tw <= 1500 ) {
					$cpf_donation = $ecf_settings['tw_1_to_1.5'];
				} elseif ( $tw > 1500 && $tw <= 2500 ) {
					$cpf_donation = $ecf_settings['tw_1.5_to_2.5'];
				} elseif ( $tw > 2500 && $tw <= 4000 ) {
					$cpf_donation = $ecf_settings['tw_2.5_to_4'];
				} elseif ( $tw > 4000 && $tw <= 7000 ) {
					$cpf_donation = $ecf_settings['tw_4_to_7'];
				} elseif ( $tw > 7000 && $tw <= 10000 ) {
					$cpf_donation = $ecf_settings['tw_7_to_10'];
				} elseif ( $tw > 10000 ) {
					$cpf_donation = $ecf_settings['tw_greater_than_10'];
				}
				break;

			case 'MBMF':
				if ( $tw <= 1000 ) {
					$cpf_donation = $mbmf_settings['tw_less_than_1'];
				} elseif ( $tw > 1000 && $tw <= 2000 ) {
					$cpf_donation = $mbmf_settings['tw_1_to_2'];
				} elseif ( $tw > 2000 && $tw <= 3000 ) {
					$cpf_donation = $mbmf_settings['tw_2_to_3'];
				} elseif ( $tw > 3000 && $tw <= 4000 ) {
					$cpf_donation = $mbmf_settings['tw_3_to_4'];
				} elseif ( $tw > 4000 && $tw <= 6000 ) {
					$cpf_donation = $mbmf_settings['tw_4_to_6'];
				} elseif ( $tw > 6000 && $tw <= 8000 ) {
					$cpf_donation = $mbmf_settings['tw_6_to_8'];
				} elseif ( $tw > 8000 && $tw <= 10000 ) {
					$cpf_donation = $mbmf_settings['tw_8_to_10'];
				} elseif ( $tw > 10000 ) {
					$cpf_donation = $mbmf_settings['tw_greater_than_10'];
				}
				break;
			default :
				$cpf_donation = 0;
		}

		return $cpf_donation;

	}
}

new CPF_Formula_Ajax();