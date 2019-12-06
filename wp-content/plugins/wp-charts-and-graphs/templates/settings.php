<div id="screen_preloader" style="position: absolute;width: 100%;height: 1000px;z-index: 9999;text-align: center;background: #fff;padding-top: 200px;">
	<h3>WP Charts and Graphs<br><small><i>by Pantherius</i></small></h3>
	<img src="<?php print(plugins_url( '../assets/img/screen_preloader.gif' , __FILE__ ));?>">
	<h5><?php _e( 'LOADING', PWPC_CHARTS_TEXT_DOMAIN );?><br><br><?php _e( 'Please wait...', 'pantherius-wordpress-survey-polls' );?></h5>
</div>
<div class="wrap pwpc" style="visibility:hidden">
	
	<h3 class="pwpc-title">WP Charts and Graphs</h3>
	
	<div id="pwp-charts-left">
		<div id="wp-charts-settings">
			
			<div class="pwpc-form-row">
				<label class="pwpc-form-label" for="pwpc_types"><?php _e( "Please choose the type here", PWPC_CHARTS_TEXT_DOMAIN );?></label>
				<select class="pwpc-form-control" id="pwpc_types">
					<option value="piechart"><?php _e( "Pie Chart", PWPC_CHARTS_TEXT_DOMAIN );?></option>
					<option value="polarchart"><?php _e( "Polar Chart", PWPC_CHARTS_TEXT_DOMAIN );?></option>
					<option value="doughnutchart"><?php _e( "Doughnut Chart", PWPC_CHARTS_TEXT_DOMAIN );?></option>
					<option value="linechart"><?php _e( "Line Chart", PWPC_CHARTS_TEXT_DOMAIN );?></option>
					<option value="barchart"><?php _e( "Bar Chart", PWPC_CHARTS_TEXT_DOMAIN );?></option>
					<option value="radarchart"><?php _e( "Radar Chart", PWPC_CHARTS_TEXT_DOMAIN );?></option>
				</select>
			</div>
			
			<div class="pwpc-form-row">
				<label class="pwpc-form-label" for="pwpc_legend"><?php _e( "Display Legend?", PWPC_CHARTS_TEXT_DOMAIN );?></label>
				<select class="pwpc-form-control" id="pwpc_legend">
					<option value="false"><?php _e( "No", PWPC_CHARTS_TEXT_DOMAIN );?></option>
					<option value="true"><?php _e( "Yes", PWPC_CHARTS_TEXT_DOMAIN );?></option>
				</select>
			</div>
			
			<div class="pwpc-form-row">
				<label class="pwpc-form-label" for="titles"><?php _e( "Please add the titles here", PWPC_CHARTS_TEXT_DOMAIN );?></label>
				<input class="pwpc-form-control" id="pwpc_titles" type="text" value="Title 1, Title 2, Title 3, Title 4">
			</div>
			
			<div class="pwpc-form-row">
				<label class="pwpc-form-label" for="values"><?php _e( "Please add the values here", PWPC_CHARTS_TEXT_DOMAIN );?></label>
				<input class="pwpc-form-control" id="pwpc_values" type="text" value="3,7,5,12">
			</div>

			<div class="pwpc-form-row">
				<label class="pwpc-form-label" for="values"><?php _e( "Specify the maximum value, that you would like to be displayed on the chart or leave it empty.", PWPC_CHARTS_TEXT_DOMAIN );?></label>
				<input class="pwpc-form-control" id="pwpc_max" type="text" value="">
			</div>

			<div class="pwpc-form-row">
				<button class="pwpc-btn button button-primary" type="button" id="generate_wpc_shortcode"><?php _e( "Generate", PWPC_CHARTS_TEXT_DOMAIN );?></button>
			</div>	
		</div>

		<div class="pwpc-form-row">
			<label class="pwpc-form-label" for="pwpc-shortcode"><?php _e( "Please copy this shortcode to any of your page or post", PWPC_CHARTS_TEXT_DOMAIN );?></label>
			<input class="pwpc-form-control" id="pwpc-shortcode" type="text" value="" placeholder="<?php _e( "The shortcode will be displayed after you clicked the Generate button", PWPC_CHARTS_TEXT_DOMAIN );?>">
		</div>
		<div class="pwpc-area" id="pwpc-chart-area"></div>
	</div>
	<?php require_once( plugin_dir_path( __FILE__ ) . '/sidebar.php' );?>
</div>