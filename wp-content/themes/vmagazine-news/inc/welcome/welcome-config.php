<?php
	/**
	 * Welcome Page Initiation
	*/

	include get_stylesheet_directory() . '/inc/welcome/welcome.php';

	/** Plugins **/
	$plugins = array(
		// *** Companion Plugins
		'companion_plugins' => array(

		),

		//Displays on Required Plugins tab
		'req_plugins' => array(

			// Free Plugins
			'free_plug' => array(

				'accesspress-twitter-feed' => array(
					'slug' => 'accesspress-twitter-feed',
					'filename' => 'accesspress-twitter-feed.php',
					'class' => 'APTF_Class'
				),
				'siteorigin-panels' => array(
					'slug' => 'siteorigin-panels',
					'filename' => 'siteorigin-panels.php',
					'class' => 'SiteOrigin_Panels'
				),
				'contact-form-7' => array(
					'slug' => 'contact-form-7',
					'filename' => 'wp-contact-form-7.php',
					'class' => 'WPCF7'
				)
			),
			'pro_plug' => array(

			),
		),

		// *** Displays on Import Demo section
		'required_plugins' => array(
			'access-demo-importer' => array(
					'slug' 		=> 'access-demo-importer',
					'name' 		=> esc_html__('Access Demo Importer', 'vmagazine-news'),
					'filename' 	=>'access-demo-importer.php',
					'host_type' => 'wordpress', // Use either bundled, remote, wordpress
					'class' 	=> 'Access_Demo_Importer',
					'info' 		=> esc_html__('Access Demo Importer adds the feature to Import the Demo Conent with a single click.', 'vmagazine-news'),
			),
		

		),

		// *** Recommended Plugins
		'recommended_plugins' => array(
			// Free Plugins
			'free_plugins' => array(
				
				'accesspress-social-share' => array(
					'slug'      => 'accesspress-social-share',
					'filename' 	=> 'accesspress-social-share.php',
					'class' 	=> 'APSS_Class',
					'info' 		=> esc_html__('Social booster for your site! A FREE plugin with premium features.', 'vmagazine-news'),
				),

				'accesspress-social-icons' => array(
					'slug'      => 'accesspress-social-icons',
					'filename' 	=> 'accesspress-social-icons.php',
					'class' 	=> 'APS_Class',
					'info' 		=> esc_html__('Connect your website visitors to your social community in an easy way! Link up your social media profiles via great looking social buttons.', 'vmagazine-news'),
				),

				'accesspress-twitter-feed' => array(
					'slug'      => 'accesspress-twitter-feed',
					'filename' 	=> 'accesspress-twitter-feed.php',
					'class' 	=> 'APTF_Class',
					'info' 		=> esc_html__('Showcase your Tweets (Twitter Feeds) right on the site.', 'vmagazine-news'),
				),

				'contact-form-7' => array(
				'slug'      => 'contact-form-7',
				'filename' 	=> 'contact-form-7.php',
				'class' 	=> 'WPCF7',
				'info' => esc_html__('Contact Form 7 can manage multiple contact forms, plus you can customize the form and the mail contents flexibly with simple markup.', 'vmagazine-news'),

			),

			),

			// Pro Plugins
			'pro_plugins' => array(

				'woo-product-grid-list-design' 	=> array(
					'slug' 		=> 'woo-product-grid-list-design',
					'name' 		=> esc_html__('WOO Product Grid/List Design- Responsive Products Showcase Extension for Woocommerce', 'vmagazine-news'),
					'version' 	=> esc_html__( '1.0.3', 'vmagazine-news' ),
					'author' 	=> 'AccessPress Themes',
					'filename' 	=> 'woo-product-grid-list-design.php',
					'host_type' => 'remote', // Use either bundled, remote, wordpress
					'link' 		=> 'https://1.envato.market/c/1302794/275988/4415?u=https%3A%2F%2Fcodecanyon.net%2Fitem%2Fwoo-product-gridlist-design-responsive-products-showcase-extension-for-woocommerce%2F23167226',
					'screenshot' => 'https://accesspressthemes.com/plugin-repo/woo-product-grid/woo-product-grid.jpg',
					'class' 	=> 'WOPGLD_Class',
					'info' 		=> esc_html__('Design your WooCommerce shop like never before! A complete package for your Woo shop designer.', 'vmagazine-news'),
				),

				'woo-badge-designer' => array(
					'slug' 			=> 'woo-badge-designer',
					'name'         	=> esc_html__('Woo Badge Designer - WooCommerce Product Badge Designer WordPress Plugin', 'vmagazine-news'),
					'version' 		=> esc_html__('1.0.1', 'vmagazine-news'),
					'author' 		=> 'AccessPress Themes',
					'filename' 		=> 'woo-badge-designer.php',
					'host_type' 	=> 'remote', // Use either bundled, remote, wordpress
					'link' 			=> 'https://1.envato.market/LyK3o',
					'screenshot' 	=> 'https://accesspressthemes.com/plugin-repo/woo-badge-designer/woo-badge-designer.jpg',
					'class' 		=> 'WOPGLD_Class',
					'info' 			=> esc_html__('Add some attractive badges on your product listing and single page and increase your sales upto 55%.', 'vmagazine-news'),
				),

				'wp-admin-white-label-login' => array(
					'slug' 			=> 'wp-admin-white-label-login',
					'name'      	=> esc_html__('WP Admin White Label Login - WordPress Plugin For Advanced Customizable Login page', 'vmagazine-news'),
					'version' 		=> esc_html__('1.3.5', 'vmagazine-news'),
					'author' 		=> 'AccessPress Themes',
					'filename' 		=> 'wp-admin-white-label-login.php',
					'host_type' 	=> 'remote', // Use either bundled, remote, wordpress
					'link' 		=> 'https://1.envato.market/c/1302794/275988/4415?u=https%3A%2F%2Fcodecanyon.net%2Fitem%2Fwp-admin-white-label-login-wordpress-plugin-for-advanced-customizable-login-page%2F23127723',
					'screenshot' 	=> 'https://accesspressthemes.com/plugin-repo/wp-admin-white-label-login/wp-admin-white-label-login.jpg',
					'class' 		=> 'WP_Admin_White_Label_Login',
					'info' 		=> esc_html__('Make your default wp-admin screen look like a non WP one! Choose from some great ready to use template designs and many features to boost your WordPress backend.', 'vmagazine-news'),
				),

				'easy-side-tab-pro' => array(
					'slug' 			=> 'easy-side-tab-pro',
					'name'      	=> esc_html__('Easy Side Tab Pro - Responsive Floating Tab Plugin For Wordpress', 'vmagazine-news'),
					'version' 		=> esc_html__('1.0.6', 'vmagazine-news'),
					'author' 		=> 'AccessPress Themes',
					'filename' 		=> 'easy-side-tab-pro.php',
					'host_type' 	=> 'remote', // Use either bundled, remote, wordpress
					'link' 			=> 'https://1.envato.market/c/1302794/275988/4415?u=https%3A%2F%2Fcodecanyon.net%2Fitem%2Feasy-side-tab-pro-responsive-floating-tab-plugin-for-wordpress%2F22296723',
					'screenshot' 	=> 'https://accesspressthemes.com/plugin-repo/easy-side-tab-pro/easy-side-tab.jpg',
					'class' 		=> 'ESTP_Class',
					'info' 		=> esc_html__('Place some great designed floating tabs on your site for quick links. Increase accessibility of your site.', 'vmagazine-news'),
				),

				'everest-timeline' => array(
					'slug' 			=> 'everest-timeline',
					'name'         	=> esc_html__('Everest Timeline - Responsive WordPress Timeline Plugin', 'vmagazine-news'),
					'version' 		=> esc_html__('2.0.2', 'vmagazine-news'),
					'author' 		=> 'AccessPress Themes',
					'filename' 		=> 'everest-timeline.php',
					'host_type' 	=> 'remote', // Use either bundled, remote, wordpress
					'screenshot' 	=> 'https://accesspressthemes.com/plugin-repo/everest-timeline/everest-timeline.jpg',
					'class' 		=> 'APMM_Class_Pro',
					'link'			=>'https://1.envato.market/c/1302794/275988/4415?u=https%3A%2F%2Fcodecanyon.net%2Fitem%2Feverest-timeline-responsive-wordpress-timeline-plugin%2F20922265',
					'info' 		=> esc_html__('A perfect timeline maker! If you\'re planning to make one go for it!', 'vmagazine-news'),
				),
			)
		),
	);

	$strings = array(
		// Welcome Page General Texts
		'welcome_menu_text' => esc_html__( 'VMagazine Lite', 'vmagazine-news' ),
		'theme_short_description' => esc_html__( 'VMagazine is a responsive free multi layout news magazine WordPress theme. The theme is perfect for all newspaper, magazines and blog websites. In fact, this is one of the quickest and simplest way to create a news magazine website. The theme is also highly configurable, uses SiteOrigin Page Builder, has 8 built in widgets, 4 elegantly designed demo layouts that can be imported with just one click and the flexibility to place your ads as you desire.', 'vmagazine-news' ),

		// Plugin Action Texts
		'install_n_activate' 	=> esc_html__('Install and Activate', 'vmagazine-news'),
		'deactivate' 			=> esc_html__('Deactivate', 'vmagazine-news'),
		'activate' 				=> esc_html__('Activate', 'vmagazine-news'),

		// Getting Started Section
		'doc_heading' 		=> esc_html__('Step 1 - Documentation', 'vmagazine-news'),
		'doc_description' 	=> esc_html__('Read the Documentation and follow the instructions to manage the site , it helps you to set up the theme more easily and quickly. The Documentation is very easy with its pictorial  and well managed listed instructions. ', 'vmagazine-news'),
		'doc_link'			=> 'https://doc.accesspressthemes.com/vmagazine-lite/',
		'doc_read_now' 		=> esc_html__( 'Read Now', 'vmagazine-lite' ),
		'cus_heading' 		=> esc_html__('Step 2 - Customizer Panel', 'vmagazine-news'),
		'cus_read_now' 		=> esc_html__( 'Go to Customizer Panels', 'vmagazine-news' ),

		// Recommended Plugins Section
		'pro_plugin_title' 			=> esc_html__( 'Premium Plugins', 'vmagazine-news' ),
		'free_plugin_title' 		=> esc_html__( 'Free Plugins', 'vmagazine-news' ),

		

		// Demo Actions
		'activate_btn' 		=> esc_html__('Activate', 'vmagazine-news'),
		'installed_btn' 	=> esc_html__('Activated', 'vmagazine-news'),
		'demo_installing' 	=> esc_html__('Installing Demo', 'vmagazine-news'),
		'demo_installed' 	=> esc_html__('Demo Installed', 'vmagazine-news'),
		'demo_confirm' 		=> esc_html__('Are you sure to import demo content ?', 'vmagazine-news'),

		// Actions Required
		'req_plugin_info' => esc_html__('All these required plugins will be installed and activated while importing demo. Or you can choose to install and activate them manually. If you\'re not importing any of the demos, you must install and activate these plugins manually.', 'vmagazine-news' ),
		'req_plugins_installed' => esc_html__( 'All Recommended action has been successfully completed.', 'vmagazine-news' ),
		'customize_theme_btn' 	=> esc_html__( 'Customize Theme', 'vmagazine-news' ),
		'pro_plugin_title' 			=> esc_html__( 'Premium Plugins', 'vmagazine-news' ),
		'free_plugin_title' 		=> esc_html__( 'Free Plugins', 'vmagazine-news' ),
	);

	/**
	 * Initiating Welcome Page
	*/
	$my_theme_wc_page = new Vmagazine_Lite_Welcome( $plugins, $strings );


	/**
	 * Initiate Demo Importer if plugin Exists
	*/
	if(class_exists('APTU_Class')) :

		$demos = array(

			'vmagazine-news' => array(
				'title' => esc_html__('News Demo', 'vmagazine-news'),
				'name' => 'news-demo',
				'preview_url' => 'https://demo.accesspressthemes.com/vmagazine-news/',
				'screenshot' => get_stylesheet_directory_uri().'/inc/welcome/demos/news-demo/screen.jpg',
				'home_page' => 'home',
				'menus' => array(
					'primary menu' => 'primary_menu',
					'Footer Menu' => 'footer_menu',
					'Top Header Menu' => 'top_menu',
				)
			),

			'world-demo' => array(
				'title' => esc_html__('World Mag Demo', 'vmagazine-news'),
				'name' => 'world-demo',
				'preview_url' => 'https://demo.accesspressthemes.com/vmagazine-lite/worldmag/',
				'screenshot' => get_template_directory_uri().'/inc/welcome/demos/world-demo/screen.jpg',
				'home_page' => 'home',
				'menus' => array(
					'primary menu' => 'primary_menu',
					'Footer Menu' => 'footer_menu',
					'Top Menus' => 'top_menu',

				)
			),
            
            'tech-demo' => array(
				'title' => esc_html__('Tech Mag Demo', 'vmagazine-news'),
				'name' => 'tech-demo',
				'preview_url' => 'https://demo.accesspressthemes.com/vmagazine-lite/techmag/',
				'screenshot' => get_template_directory_uri().'/inc/welcome/demos/tech-demo/screen.jpg',
				'home_page' => 'home-2',
				'menus' => array(
					'primary menu' => 'primary_menu',
					'Footer Menu' => 'footer_menu',
					'top menu' => 'top_menu',
				)
			),
            
            'fashion-demo' => array(
				'title' => esc_html__('Fashion Demo', 'vmagazine-news'),
				'name' => 'fashion-demo',
				'preview_url' => 'https://demo.accesspressthemes.com/vmagazine-lite/fashion/',
				'screenshot' => get_template_directory_uri().'/inc/welcome/demos/fashion-demo/screen.jpg',
				'home_page' => 'home',
				'menus' => array(
					'Fashion Menu' => 'primary_menu',
					'Footer Menu' => 'footer_menu',
					'top menu' => 'top_menu',
				)
			),
            
            'gaming-demo' => array(
				'title' => esc_html__('Gaming Demo', 'vmagazine-news'),
				'name' => 'gaming-demo',
				'preview_url' => 'https://demo.accesspressthemes.com/vmagazine-lite/gaming/',
				'screenshot' => get_template_directory_uri().'/inc/welcome/demos/gaming-demo/screen.jpg',
				'home_page' => 'home',
				'menus' => array(
					'primary menu' => 'primary_menu',
					'Footer Menu' => 'footer_menu',
					'Top Header Menu' => 'top_menu',
				)
			),

			'newspaper-demo' => array(
				'title' => esc_html__('Newspaper Demo', 'vmagazine-news'),
				'name' => 'newspaper-demo',
				'preview_url' => 'https://demo.accesspressthemes.com/vmagazine-lite/newspaper/',
				'screenshot' => get_template_directory_uri().'/inc/welcome/demos/newspaper-demo/screen.jpg',
				'home_page' => 'home',
				'menus' => array(
					'primary menu' => 'primary_menu',
					'Footer Menu' => 'footer_menu',
					'Top Header Menu' => 'top_menu',
				)
			),
			
		);

		$demoimporter = new APTU_Class( $demos, $demo_dir = get_stylesheet_directory().'/inc/welcome/demos/' );

	endif;