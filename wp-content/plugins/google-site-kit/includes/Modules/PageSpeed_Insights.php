<?php
/**
 * Class Google\Site_Kit\Modules\PageSpeed_Insights
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
use Google\Site_Kit_Dependencies\Google_Service_Pagespeedonline;
use Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface;
use WP_Error;

/**
 * Class representing the PageSpeed Insights module.
 *
 * @since 1.0.0
 * @access private
 * @ignore
 */
final class PageSpeed_Insights extends Module implements Module_With_Scopes {
	use Module_With_Scopes_Trait;

	const OPTION = 'googlesitekit_pagespeed_insights_settings';

	/**
	 * Registers functionality through WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function register() {}

	/**
	 * Cleans up when the module is deactivated.
	 *
	 * @since 1.0.0
	 */
	public function on_deactivation() {
		$this->options->delete( self::OPTION );
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
			// GET.
			'pagespeed' => 'pagespeedonline',
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
				case 'pagespeed':
					if ( empty( $data['strategy'] ) ) {
						return new WP_Error(
							'missing_required_param',
							sprintf(
								/* translators: %s: Missing parameter name */
								__( 'Request parameter is empty: %s.', 'google-site-kit' ),
								'strategy'
							),
							array( 'status' => 400 )
						);
					}

					$valid_strategies = array( 'mobile', 'desktop' );

					if ( ! in_array( $data['strategy'], $valid_strategies, true ) ) {
						return new WP_Error(
							'invalid_param',
							sprintf(
								/* translators: 1: Invalid parameter name, 2: list of valid values */
								__( 'Request parameter %1$s is not one of %2$s', 'google-site-kit' ),
								'strategy',
								implode( ', ', $valid_strategies )
							),
							array( 'status' => 400 )
						);
					}

					if ( ! empty( $data['url'] ) ) {
						$page_url = $data['url'];
					} else {
						$page_url = $this->context->get_reference_site_url();
					}

					$service = $this->get_service( 'pagespeedonline' );

					return $service->pagespeedapi->runpagespeed(
						$page_url,
						array(
							'locale'   => substr( get_locale(), 0, 2 ),
							'strategy' => $data['strategy'],
						)
					);
			}
		}

		return new WP_Error( 'invalid_datapoint', __( 'Invalid datapoint.', 'google-site-kit' ) );
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
				case 'pagespeed':
					// TODO: Parse this response to a regular array.
					return $response->getLighthouseResult();
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
			'slug'        => 'pagespeed-insights',
			'name'        => __( 'PageSpeed Insights', 'google-site-kit' ),
			'description' => __( 'Google PageSpeed Insights gives you metrics about performance, accessibility, SEO and PWA.', 'google-site-kit' ),
			'cta'         => __( 'Learn more about your website’s performance.', 'google-site-kit' ),
			'order'       => 4,
			'homepage'    => __( 'https://developers.google.com/speed/pagespeed/insights/', 'google-site-kit' ),
			'learn_more'  => __( 'https://developers.google.com/speed/docs/insights/v5/about', 'google-site-kit' ),
			'group'       => __( 'Additional Google Services', 'google-site-kit' ),
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
			'pagespeedonline' => new Google_Service_Pagespeedonline( $client ),
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
			__( 'Website performance reports for mobile and desktop', 'google-site-kit' ),
		);

		return $info;
	}

	/**
	 * Gets required Google OAuth scopes for the module.
	 *
	 * @return array List of Google OAuth scopes.
	 * @since 1.0.0
	 */
	public function get_scopes() {
		return array(
			'openid',
		);
	}
}
