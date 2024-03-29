<?php
/**
 * Class Google\Site_Kit\Modules\Tag_Manager
 *
 * @package   Google\Site_Kit
 * @copyright 2019 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://sitekit.withgoogle.com
 */

namespace Google\Site_Kit\Modules;

use Google\Site_Kit\Core\Modules\Module;
use Google\Site_Kit\Core\Modules\Module_With_Scopes;
use Google\Site_Kit\Core\Modules\Module_With_Scopes_Trait;
use Google\Site_Kit\Core\REST_API\Data_Request;
use Google\Site_Kit_Dependencies\Google_Client;
use Google\Site_Kit_Dependencies\Google_Service_Exception;
use Google\Site_Kit_Dependencies\Google_Service_TagManager;
use Google\Site_Kit_Dependencies\Google_Service_TagManager_Container;
use Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface;
use WP_Error;
use Exception;

/**
 * Class representing the Tag Manager module.
 *
 * @since 1.0.0
 * @access private
 * @ignore
 */
final class Tag_Manager extends Module implements Module_With_Scopes {
	use Module_With_Scopes_Trait;

	const OPTION = 'googlesitekit_tagmanager_settings';

	/**
	 * Registers functionality through WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$this->register_scopes_hook();

		add_action( // For non-AMP.
			'wp_head',
			function() {
				$this->print_gtm_js();
			}
		);

		add_action( // For non-AMP.
			'wp_footer',
			function() {
				$this->print_gtm_no_js();
			}
		);

		$print_amp_gtm = function() {
			// This hook is only available in AMP plugin version >=1.3, so if it
			// has already completed, do nothing.
			if ( ! doing_action( 'amp_print_analytics' ) && did_action( 'amp_print_analytics' ) ) {
				return;
			}

			$this->print_amp_gtm();
		};
		// Which actions are run depends on the version of the AMP Plugin
		// (https://amp-wp.org/) available. Version >=1.3 exposes a
		// new, `amp_print_analytics` action.
		// For all AMP modes, AMP plugin version >=1.3.
		add_action( 'amp_print_analytics', $print_amp_gtm );
		// For AMP Standard and Transitional, AMP plugin version <1.3.
		add_action( 'wp_footer', $print_amp_gtm, 20 );
		// For AMP Reader, AMP plugin version <1.3.
		add_action( 'amp_post_template_footer', $print_amp_gtm, 20 );

		add_filter( // Load amp-analytics component for AMP Reader.
			'amp_post_template_data',
			function( $data ) {
				return $this->amp_data_load_analytics_component( $data );
			}
		);
	}

	/**
	 * Gets required Google OAuth scopes for the module.
	 *
	 * @since 1.0.0
	 *
	 * @return array List of Google OAuth scopes.
	 */
	public function get_scopes() {
		return array(
			'https://www.googleapis.com/auth/tagmanager.readonly',
			'https://www.googleapis.com/auth/tagmanager.edit.containers',
			'https://www.googleapis.com/auth/tagmanager.manage.accounts',
		);
	}

	/**
	 * Returns all module information data for passing it to JavaScript.
	 *
	 * @since 1.0.0
	 *
	 * @return array Module information data.
	 */
	public function prepare_info_for_js() {
		$info = parent::prepare_info_for_js();

		$info['provides'] = array(
			__( 'Create tags without updating code', 'google-site-kit' ),
		);

		$info['settings'] = $this->get_data( 'connection' );

		return $info;
	}

	/**
	 * Checks whether the module is connected.
	 *
	 * A module being connected means that all steps required as part of its activation are completed.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if module is connected, false otherwise.
	 */
	public function is_connected() {
		$container_id = $this->get_data( 'container-id' );
		if ( is_wp_error( $container_id ) || ! $container_id ) {
			return false;
		}

		return parent::is_connected();
	}

	/**
	 * Cleans up when the module is deactivated.
	 *
	 * @since 1.0.0
	 */
	public function on_deactivation() {
		$this->options->delete( self::OPTION );
	}

	/**
	 * Outputs Tag Manager script.
	 *
	 * @since 1.0.0
	 */
	protected function print_gtm_js() {
		// On AMP, do not print the script tag, falling back to 'amp_analytics_entries' below.
		if ( $this->context->is_amp() ) {
			return;
		}

		$container_id = $this->get_data( 'container-id' );
		if ( is_wp_error( $container_id ) || ! $container_id ) {
			return;
		}

		?>
		<!-- Google Tag Manager added by Site Kit -->
		<script>( function( w, d, s, l, i ) {
				w[l] = w[l] || [];
				w[l].push( {'gtm.start': new Date().getTime(), event: 'gtm.js'} );
				var f = d.getElementsByTagName( s )[0],
					j = d.createElement( s ), dl = l != 'dataLayer' ? '&l=' + l : '';
				j.async = true;
				j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
				f.parentNode.insertBefore( j, f );
			} )( window, document, 'script', 'dataLayer', '<?php echo esc_js( $container_id ); ?>' );
		</script>
		<!-- End Google Tag Manager -->
		<?php
	}

	/**
	 * Outputs Tag Manager iframe for when the browser has JavaScript disabled.
	 *
	 * @since 1.0.0
	 */
	protected function print_gtm_no_js() {
		// On AMP, do not print the script tag.
		if ( $this->context->is_amp() ) {
			return;
		}

		$container_id = $this->get_data( 'container-id' );
		if ( is_wp_error( $container_id ) || ! $container_id ) {
			return;
		}

		?>
		<!-- Google Tag Manager (noscript) added by Site Kit -->
		<noscript>
			<iframe src="<?php echo esc_url( "https://www.googletagmanager.com/ns.html?id=$container_id" ); ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe>
		</noscript>
		<!-- End Google Tag Manager (noscript) -->
		<?php
	}

	/**
	 * Outputs Tag Manager <amp-analytics> tag.
	 *
	 * @since 1.0.0
	 */
	protected function print_amp_gtm() {
		if ( ! $this->context->is_amp() ) {
			return;
		}

		$container_id = $this->get_data( 'container-id' );
		if ( is_wp_error( $container_id ) || ! $container_id ) {
			return;
		}

		?>
		<!-- Google Tag Manager added by Site Kit -->
		<amp-analytics config="<?php echo esc_url( "https://www.googletagmanager.com/amp.json?id=$container_id" ); ?>" data-credentials="include"></amp-analytics>
		<!-- End Google Tag Manager -->
		<?php
	}

	/**
	 * Loads AMP analytics script if opted in.
	 *
	 * This only affects AMP Reader mode, the others are automatically covered.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data AMP template data.
	 * @return array Filtered $data.
	 */
	protected function amp_data_load_analytics_component( $data ) {
		if ( isset( $data['amp_component_scripts']['amp-analytics'] ) ) {
			return $data;
		}

		$container_id = $this->get_data( 'container-id' );
		if ( is_wp_error( $container_id ) || ! $container_id ) {
			return $data;
		}

		$data['amp_component_scripts']['amp-analytics'] = 'https://cdn.ampproject.org/v0/amp-analytics-0.1.js';
		return $data;
	}

	/**
	 * Returns the mapping between available datapoints and their services.
	 *
	 * @since 1.0.0
	 *
	 * @return array Associative array of $datapoint => $service_identifier pairs.
	 */
	protected function get_datapoint_services() {
		return array(
			// GET / POST.
			'connection'          => '',
			'account-id'          => '',
			'container-id'        => '',
			// GET.
			'accounts-containers' => 'tagmanager',
			'containers'          => 'tagmanager',
			// POST.
			'settings'            => '',
		);
	}

	/**
	 * Creates a request object for the given datapoint.
	 *
	 * @since 1.0.0
	 *
	 * @param Data_Request $data Data request object.
	 *
	 * @return RequestInterface|callable|WP_Error Request object or callable on success, or WP_Error on failure.
	 */
	protected function create_data_request( Data_Request $data ) {
		$method    = $data->method;
		$datapoint = $data->datapoint;

		if ( 'GET' === $method ) {
			switch ( $datapoint ) {
				case 'connection':
					return function() {
						$option = (array) $this->options->get( self::OPTION );
						// TODO: Remove this at some point (migration of old options).
						if ( isset( $option['account_id'] ) || isset( $option['container_id'] ) ) {
							if ( isset( $option['account_id'] ) ) {
								if ( ! isset( $option['accountID'] ) ) {
									$option['accountID'] = $option['account_id'];
								}
								unset( $option['account_id'] );
							}
							if ( isset( $option['container_id'] ) ) {
								if ( ! isset( $option['containerID'] ) ) {
									$option['containerID'] = $option['container_id'];
								}
								unset( $option['container_id'] );
							}
							$this->options->set( self::OPTION, $option );
						}

						// TODO: Remove this at some point (migration of old 'accountId' option).
						if ( isset( $option['accountId'] ) ) {
							if ( ! isset( $option['accountID'] ) ) {
								$option['accountID'] = $option['accountId'];
							}
							unset( $option['accountId'] );
						}

						// TODO: Remove this at some point (migration of old 'containerId' option).
						if ( isset( $option['containerId'] ) ) {
							if ( ! isset( $option['containerID'] ) ) {
								$option['containerID'] = $option['containerId'];
							}
							unset( $option['containerId'] );
						}

						$defaults = array(
							'accountID'   => '',
							'containerID' => '',
						);
						return array_intersect_key( array_merge( $defaults, $option ), $defaults );
					};
				case 'account-id':
					return function() {
						$option = (array) $this->options->get( self::OPTION );
						// TODO: Remove this at some point (migration of old option).
						if ( isset( $option['account_id'] ) ) {
							if ( ! isset( $option['accountID'] ) ) {
								$option['accountID'] = $option['account_id'];
							}
							unset( $option['account_id'] );
							$this->options->set( self::OPTION, $option );
						}

						// TODO: Remove this at some point (migration of old 'accountId' option).
						if ( isset( $option['accountId'] ) ) {
							if ( ! isset( $option['accountID'] ) ) {
								$option['accountID'] = $option['accountId'];
							}
							unset( $option['accountId'] );
						}

						if ( empty( $option['accountID'] ) ) {
							return new WP_Error( 'account_id_not_set', __( 'Tag Manager account ID not set.', 'google-site-kit' ), array( 'status' => 404 ) );
						}
						return $option['accountID'];
					};
				case 'container-id':
					return function() {
						$option = (array) $this->options->get( self::OPTION );
						// TODO: Remove this at some point (migration of old option).
						if ( isset( $option['container_id'] ) ) {
							if ( ! isset( $option['containerID'] ) ) {
								$option['containerID'] = $option['container_id'];
							}
							unset( $option['container_id'] );
							$this->options->set( self::OPTION, $option );
						}

						// TODO: Remove this at some point (migration of old 'containerId' option).
						if ( isset( $option['containerId'] ) ) {
							if ( ! isset( $option['containerID'] ) ) {
								$option['containerID'] = $option['containerId'];
							}
							unset( $option['containerId'] );
						}

						if ( empty( $option['containerID'] ) ) {
							return new WP_Error( 'container_id_not_set', __( 'Tag Manager container ID not set.', 'google-site-kit' ), array( 'status' => 404 ) );
						}
						return $option['containerID'];
					};
				case 'accounts-containers':
					$service = $this->get_service( 'tagmanager' );
					return $service->accounts->listAccounts();
				case 'containers':
					if ( ! isset( $data['accountID'] ) ) {
						/* translators: %s: Missing parameter name */
						return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'accountID' ), array( 'status' => 400 ) );
					}
					$service = $this->get_service( 'tagmanager' );
					return $service->accounts_containers->listAccountsContainers( "accounts/{$data['accountID']}" );
			}
		} elseif ( 'POST' === $method ) {
			switch ( $datapoint ) {
				case 'connection':
					return function() use ( $data ) {
						$option = (array) $this->options->get( self::OPTION );
						$keys   = array( 'accountID', 'containerID' );
						foreach ( $keys as $key ) {
							if ( isset( $data[ $key ] ) ) {
								$option[ $key ] = $data[ $key ];
							}
						}
						$this->options->set( self::OPTION, $option );
						return true;
					};
				case 'account-id':
					if ( ! isset( $data['accountID'] ) ) {
						/* translators: %s: Missing parameter name */
						return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'accountID' ), array( 'status' => 400 ) );
					}
					return function() use ( $data ) {
						$option              = (array) $this->options->get( self::OPTION );
						$option['accountID'] = $data['accountID'];
						$this->options->set( self::OPTION, $option );
						return true;
					};
				case 'container-id':
					if ( ! isset( $data['containerID'] ) ) {
						/* translators: %s: Missing parameter name */
						return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'containerID' ), array( 'status' => 400 ) );
					}
					return function() use ( $data ) {
						$option                = (array) $this->options->get( self::OPTION );
						$option['containerID'] = $data['containerID'];
						$this->options->set( self::OPTION, $option );
						return true;
					};
				case 'settings':
					if ( ! isset( $data['accountID'] ) ) {
						/* translators: %s: Missing parameter name */
						return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'accountID' ), array( 'status' => 400 ) );
					}
					if ( ! isset( $data['containerID'] ) ) {
						/* translators: %s: Missing parameter name */
						return new WP_Error( 'missing_required_param', sprintf( __( 'Request parameter is empty: %s.', 'google-site-kit' ), 'containerID' ), array( 'status' => 400 ) );
					}
					return function() use ( $data ) {
						if ( '0' === $data['containerID'] ) {
							$response = $this->create_container( $data['accountID'] );
							if ( is_wp_error( $response ) ) {
								return $response;
							}

							$data['containerID'] = $response;
						}
						$option = array(
							'accountID'   => $data['accountID'],
							'containerID' => $data['containerID'],
						);
						$this->options->set( self::OPTION, $option );
						return $option;
					};
			}
		}

		return new WP_Error( 'invalid_datapoint', __( 'Invalid datapoint.', 'google-site-kit' ) );
	}

	/**
	 * Creates GTM Container.
	 *
	 * @since 1.0.0
	 *
	 * @param string $account_id  The account ID.
	 * @return mixed Container ID on success, or WP_Error on failure.
	 */
	protected function create_container( $account_id ) {
		$client     = $this->get_client();
		$orig_defer = $client->shouldDefer();

		$client->setDefer( false );

		$container = new Google_Service_TagManager_Container();
		$container->setName( remove_accents( get_bloginfo( 'name' ) ) );
		$container->setUsageContext( array( 'web' ) );

		try {
			$container = $this->get_service( 'tagmanager' )->accounts_containers->create( "accounts/{$account_id}", $container );
		} catch ( Google_Service_Exception $e ) {
			$client->setDefer( $orig_defer );
			$message = $e->getErrors();
			if ( isset( $message[0]['message'] ) ) {
				$message = $message[0]['message'];
			}
			return new WP_Error( $e->getCode(), $message );
		} catch ( Exception $e ) {
			$client->setDefer( $orig_defer );
			return new WP_Error( $e->getCode(), $e->getMessage() );
		}

		$client->setDefer( $orig_defer );
		return $container->getPublicId();
	}

	/**
	 * Parses a response for the given datapoint.
	 *
	 * @since 1.0.0
	 *
	 * @param Data_Request $data Data request object.
	 * @param mixed        $response Request response.
	 *
	 * @return mixed Parsed response data on success, or WP_Error on failure.
	 */
	protected function parse_data_response( Data_Request $data, $response ) {
		$method    = $data->method;
		$datapoint = $data->datapoint;

		if ( 'GET' === $method ) {
			switch ( $datapoint ) {
				case 'accounts-containers':
					$response = array(
						// TODO: Parse this response to a regular array.
						'accounts'   => $response->getAccount(),
						'containers' => array(),
					);
					if ( 0 === count( $response['accounts'] ) ) {
						return $response;
					}
					if ( $data['accountID'] ) {
						$account_id = $data['accountID'];
					} else {
						$account_id = $response['accounts'][0]->getAccountId();
					}

					$containers = $this->get_data( 'containers', array( 'accountID' => $account_id ) );

					if ( is_wp_error( $containers ) ) {
						return $response;
					}

					return array_merge( $response, compact( 'containers' ) );
				case 'containers':
					$account_id = $data['accountID'];
					$response   = $response->getContainer();

					if ( empty( $response ) && ! empty( $account_id ) ) {
						// If empty containers, attempt to create a new container.
						$new_container = $this->create_container( $account_id );
						if ( is_wp_error( $new_container ) ) {
							return new WP_Error( 'google_tagmanager_container_empty', __( 'No Google Tag Manager Containers Found.', 'google-site-kit' ), array( 'status' => 500 ) );
						}
						return $this->get_data( 'containers', array( 'accountID' => $account_id ) );
					}
					return $response;
			}
		}

		return $response;
	}

	/**
	 * Sets up information about the module.
	 *
	 * @since 1.0.0
	 *
	 * @return array Associative array of module info.
	 */
	protected function setup_info() {
		return array(
			'slug'        => 'tagmanager',
			'name'        => __( 'Tag Manager', 'google-site-kit' ),
			'description' => __( 'Tag Manager creates an easy to manage way to create tags on your site without updating code.', 'google-site-kit' ),
			'cta'         => __( 'Tag management made simple.', 'google-site-kit' ),
			'order'       => 6,
			'homepage'    => __( 'https://tagmanager.google.com/', 'google-site-kit' ),
			'learn_more'  => __( 'https://marketingplatform.google.com/about/tag-manager/', 'google-site-kit' ),
			'group'       => __( 'Marketing Platform', 'google-site-kit' ),
			'tags'        => array( 'marketing' ),
			'depends_on'  => array( 'analytics' ),
		);
	}

	/**
	 * Sets up the Google services the module should use.
	 *
	 * This method is invoked once by {@see Module::get_service()} to lazily set up the services when one is requested
	 * for the first time.
	 *
	 * @since 1.0.0
	 *
	 * @param Google_Client $client Google client instance.
	 * @return array Google services as $identifier => $service_instance pairs. Every $service_instance must be an
	 *               instance of Google_Service.
	 */
	protected function setup_services( Google_Client $client ) {
		return array(
			'tagmanager' => new Google_Service_TagManager( $client ),
		);
	}
}
