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

			$OrdinaryWages     = (int) $form_data['ow'];
			$AdditionalWages   = (int) $form_data['aw'];
			$citizenship       = $form_data['citizenship'];
			$CPFDT             = $form_data['CPFDT'];
			$totalWages        = $OrdinaryWages + (int) $AdditionalWages;
			$sdl               = ( ( $totalWages * .25 ) / 100 );
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
		if ( $age <= 55 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = 0;
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * 4 ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * 4 ) / 100 ) + 0.15 * ( $totalWages - 500 ) );
				$EmployeesShare        = 0.15 * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * 9 ) / 100 ) + ( ( $AdditionalWages * 9 ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * 5 ) / 100 ) + ( ( $AdditionalWages * 5 ) / 100 ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}
		} elseif ( $age > 55 && $age < 60 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * 4 ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * 4 ) / 100 ) + 0.15 * ( $totalWages - 500 ) );
				$EmployeesShare        = 0.15 * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * 9 ) / 100 ) + ( ( $AdditionalWages * 9 ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * 5 ) / 100 ) + ( ( $AdditionalWages * 5 ) / 100 ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}
		} elseif ( $age > 60 && $age < 65 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * 3.5 ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * 3.5 ) / 100 ) + 0.15 * ( $totalWages - 500 ) );
				$EmployeesShare        = 0.15 * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * 8.5 ) / 100 ) + ( ( $AdditionalWages * 8.5 ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * 5 ) / 100 ) + ( ( $AdditionalWages * 5 ) / 100 ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}
		} elseif ( $age >= 65 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * 3.5 ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * 3.5 ) / 100 ) + 0.15 * ( $totalWages - 500 ) );
				$EmployeesShare        = 0.15 * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * 8.5 ) / 100 ) + ( ( $AdditionalWages * 8.5 ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * 5 ) / 100 ) + ( ( $AdditionalWages * 5 ) / 100 ) );
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
		if ( $age <= 55 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = 0;
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * 17 ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * 17 ) / 100 ) + 0.6 * ( $totalWages - 500 ) );
				$EmployeesShare        = 0.6 * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * 37 ) / 100 ) + ( ( $AdditionalWages * 37 ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * 20 ) / 100 ) + ( ( $AdditionalWages * 20 ) / 100 ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}
		} elseif ( $age > 55 && $age < 60 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * 13 ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * 13 ) / 100 ) + 0.39 * ( $totalWages - 500 ) );
				$EmployeesShare        = 0.39 * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * 26 ) / 100 ) + ( ( $AdditionalWages * 26 ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * 13 ) / 100 ) + ( ( $AdditionalWages * 13 ) / 100 ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}
		} elseif ( $age > 60 && $age < 65 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * 9 ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * 9 ) / 100 ) + 0.225 * ( $totalWages - 500 ) );
				$EmployeesShare        = 0.225 * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * 16.5 ) / 100 ) + ( ( $AdditionalWages * 16.5 ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * 7.5 ) / 100 ) + ( ( $AdditionalWages * 7.5 ) / 100 ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}
		} elseif ( $age >= 65 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * 7.5 ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * 7.5 ) / 100 ) + 0.15 * ( $totalWages - 500 ) );
				$EmployeesShare        = 0.15 * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * 12.5 ) / 100 ) + ( ( $AdditionalWages * 12.5 ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * 5 ) / 100 ) + ( ( $AdditionalWages * 5 ) / 100 ) );
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

		if ( $age <= 55 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = 0;
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * 17 ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * 17 ) / 100 ) + 0.6 * ( $totalWages - 500 ) );
				$EmployeesShare        = 0.6 * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * 37 ) / 100 ) + ( ( $AdditionalWages * 37 ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * 20 ) / 100 ) + ( ( $AdditionalWages * 20 ) / 100 ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}
		} elseif ( $age > 55 && $age < 60 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * 13 ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * 13 ) / 100 ) + 0.39 * ( $totalWages - 500 ) );
				$EmployeesShare        = 0.39 * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * 26 ) / 100 ) + ( ( $AdditionalWages * 26 ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * 13 ) / 100 ) + ( ( $AdditionalWages * 13 ) / 100 ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}
		} elseif ( $age > 60 && $age < 65 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * 9 ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * 9 ) / 100 ) + 0.225 * ( $totalWages - 500 ) );
				$EmployeesShare        = 0.225 * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * 16.5 ) / 100 ) + ( ( $AdditionalWages * 16.5 ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * 7.5 ) / 100 ) + ( ( $AdditionalWages * 7.5 ) / 100 ) );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			}
		} elseif ( $age >= 65 ) {
			if ( $totalWages <= 50 ) {
				$TotalCPFContributions = 0;
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 50 && $totalWages < 500 ) {
				$TotalCPFContributions = ( ( $totalWages * 7.5 ) / 100 );
				$EmployeesShare        = 0;
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages > 500 && $totalWages < 750 ) {
				$TotalCPFContributions = ( ( ( $totalWages * 7.5 ) / 100 ) + 0.15 * ( $totalWages - 500 ) );
				$EmployeesShare        = 0.15 * ( $totalWages - 500 );
				$EmployerShare         = ( $TotalCPFContributions - $EmployeesShare );
			} elseif ( $totalWages >= 750 ) {
				$TotalCPFContributions = ( ( ( $OrdinaryWages * 12.5 ) / 100 ) + ( ( $AdditionalWages * 12.5 ) / 100 ) );
				$EmployeesShare        = ( ( ( $OrdinaryWages * 5 ) / 100 ) + ( ( $AdditionalWages * 5 ) / 100 ) );
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

		switch ( $cpf_donation_type ) {

			case 'CDAC':
				if ( $tw <= 2000 ) {
					$cpf_donation = .50;
				} elseif ( $tw > 2000 && $tw <= 3500 ) {
					$cpf_donation = 1.00;
				} elseif ( $tw > 3500 && $tw <= 5000 ) {
					$cpf_donation = 1.50;;
				} elseif ( $tw > 5000 && $tw <= 7500 ) {
					$cpf_donation = 2.00;
				} elseif ( $tw > 7500 ) {
					$cpf_donation = 3.00;
				}
				break;

			case 'SINDA':
				if ( $tw <= 1000 ) {
					$cpf_donation = 1.0;
				} elseif ( $tw > 1000 && $tw <= 1500 ) {
					$cpf_donation = 3.0;
				} elseif ( $tw > 1500 && $tw <= 2500 ) {
					$cpf_donation = 5.0;
				} elseif ( $tw > 2500 && $tw <= 4500 ) {
					$cpf_donation = 7.0;
				} elseif ( $tw > 4500 && $tw <= 7500 ) {
					$cpf_donation = 9.0;
				} elseif ( $tw > 7500 && $tw <= 10000 ) {
					$cpf_donation = 12.0;
				} elseif ( $tw > 10000 && $tw <= 15000 ) {
					$cpf_donation = 18.0;
				} elseif ( $tw > 15000 ) {
					$cpf_donation = 30.0;
				}
				break;

			case 'ECF':
				if ( $tw <= 1000 ) {
					$cpf_donation = 2.0;
				} elseif ( $tw > 1000 && $tw <= 1500 ) {
					$cpf_donation = 4.0;
				} elseif ( $tw > 1500 && $tw <= 2500 ) {
					$cpf_donation = 6.0;
				} elseif ( $tw > 2500 && $tw <= 4000 ) {
					$cpf_donation = 9.0;
				} elseif ( $tw > 4000 && $tw <= 7000 ) {
					$cpf_donation = 12.0;
				} elseif ( $tw > 7000 && $tw <= 10000 ) {
					$cpf_donation = 16.0;
				} elseif ( $tw > 10000 ) {
					$cpf_donation = 20.0;
				}
				break;

			case 'MBMF':
				if ( $tw <= 1000 ) {
					$cpf_donation = 3.0;
				} elseif ( $tw > 1000 && $tw <= 2000 ) {
					$cpf_donation = 4.50;
				} elseif ( $tw > 2000 && $tw <= 3000 ) {
					$cpf_donation = 6.50;
				} elseif ( $tw > 3000 && $tw <= 4000 ) {
					$cpf_donation = 15.00;
				} elseif ( $tw > 4000 && $tw <= 6000 ) {
					$cpf_donation = 19.50;
				} elseif ( $tw > 6000 && $tw <= 8000 ) {
					$cpf_donation = 22.0;
				} elseif ( $tw > 8000 && $tw <= 10000 ) {
					$cpf_donation = 24.0;
				} elseif ( $tw > 10000 ) {
					$cpf_donation = 26.0;
				}
				break;
			default :
				$cpf_donation = 0;
		}

		return $cpf_donation;

	}
}

new CPF_Formula_Ajax();