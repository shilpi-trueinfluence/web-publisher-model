<?php
/*
Plugin Name: Dilli Email Validator
Plugin URI: https://www.dillilabs.com/products/email-validation-api/dilli-email-validator-wordpress-plugin/
Description: Adds advanced email address validation to forms using <a href="https://www.dillilabs.com/products/email-validation-api/" target="_blank">Dilli Email Validation</a> service. Prevents typos in email address field and eliminates spam submissions with fake email addresses.
Author: Dilli Labs LLC
Version: 1.3.5.0
Author URI: https://www.dillilabs.com/
Text Domain: dilli-email-validator
Domain Path: /languages
*/

if ( ! function_exists( 'json_decode' ) ) {
	function json_decode( $string, $assoc = FALSE ) {
		$json = new Services_JSON();

		if ( $assoc )
			return (array) $json->decode( $string );
		else
			return $json->decode( $string );
	}
}

if ( ! class_exists( 'Email_Validation_Dilli' ) ) {
	class Email_Validation_Dilli {
		private $options = NULL;
		var $slug;
		var $basename;

		public function __construct() {
			$this->options = get_option( 'dilli_labs_email_validator' );
			$this->basename = plugin_basename( __FILE__ );
			$this->slug = str_replace( array( basename( __FILE__ ), '/' ), '', $this->basename );

			add_action( 'init', array( &$this, 'plugin_init' ) );
		}

		public function plugin_init() {
			load_plugin_textdomain( $this->slug, FALSE, $this->slug . '/languages' );
			add_filter( 'is_email', array( $this, 'validate_email' ) );
			add_filter( 'ninja_forms_submit_data', array( $this, 'my_ninja_forms_submit_data'));
		}

		public function my_ninja_forms_submit_data( $form_data ) {
		    foreach( $form_data[ 'fields' ] as $key=>$field ) { // Field settigns, including the field key and value.
		    	$value = $field['value'];
		    	if(preg_match('/@.+\./', $value)){
		    		if(!$this->validate_email($value)){
						// This is the email field
		    			$field_id = $field['id'];
		    			// validate
			    		$form_data['errors']['fields'][$field_id] = __( 'Please enter a valid email address.', $this->slug );
		    		}		    		
		    	}
			}
			return $form_data;
		}

		//Function which sends the email to Dilli to check it
		public function validate_email( $emailID ) {
			global $pagenow, $wp;
			//If the format of the email itself is wrong return false without further checking
			if( ! filter_var( $emailID, FILTER_VALIDATE_EMAIL ) )
				return FALSE;

			//If no API was entered don't do anything
			if( ! isset( $this->options['dilli_pubkey_api'] ) || empty( $this->options['dilli_pubkey_api'] ) )
				return TRUE;

			if ( "edit.php" == $pagenow && "shop_order" == $wp->query_vars['post_type'] ) {
				return true;
			}

			// skip login
			if("wp-login.php" == $pagenow){
				return true;
			}

			// if coming from TML login, don't validate
			if($wp->request == 'login'){
				return true;
			}

			$args = array(
				'sslverify' => FALSE,
				'timeout' => 15
			);
			//Send the email to Dilli's email validation service
			$response = wp_remote_request( "https://deva.dillilabs.com/api/".$this->options['dilli_pubkey_api']	."/email/".urlencode($emailID)."?pagenow=".urlencode($pagenow), $args );

			if(is_array($response) && $response["body"] ==  "false") {
				return FALSE;
			}
			if(is_wp_error($response)){
				return TRUE; // Allow emails to be treated as valid if there is an error reaching API or for other reasons.
			}

			return TRUE;
		}
	}

	$email_validation_dilli = new Email_Validation_Dilli();
}

if ( is_admin() ) require_once dirname( __FILE__ ) . '/admin_options.php';