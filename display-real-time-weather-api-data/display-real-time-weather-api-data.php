<?php
/*
Plugin Name:  Display Real Time Weather API Data  
Description:  This Plugin Display Weather Information On Your Site Through Shortcode. It will be displayed on the Plugins page in WordPress admin area. 
Version:      1.0
Author:       Code Corners 
Author URI:   https://codecorners.in
Text Domain:  display-real-time-weather-api-data
*/


	function drtwad_add_settings_page() {
	    add_options_page( 'Real Time Weather API Data', 'Real Time Weather API Data', 'manage_options', 'drt_weather_api_data', 'drtwad_render_plugin_settings_page' );
	}
	add_action( 'admin_menu', 'drtwad_add_settings_page' );


	function drtwad_render_plugin_settings_page() {
		 settings_errors(); 
	    ?>
	    <h2>Real Time Weather API Data Settings</h2>
	    <form action="options.php" method="post">
	        <?php 
		        settings_fields( 'drt_weather_api_data_options' );
		        do_settings_sections( 'drt_weather_api_data' ); 
		        submit_button( 'Save Settings' );
	        ?>
	    </form>
	    <?php
	}

	function dbi_register_settings() {

	    register_setting( 'drt_weather_api_data_options', 'drt_weather_api_data_options', 'drt_weather_api_data_options_validate' );

	    add_settings_section( 'api_settings', 'API Settings', 'drtwa_data_section_text', 'drt_weather_api_data' );

	    add_settings_field( 'drtwa_data_setting_api_key', 'API Key', 'drtwa_data_setting_api_key', 'drt_weather_api_data', 'api_settings' );

	    add_settings_field( 'drtwa_data_setting_location', 'Location', 'drtwa_data_setting_location', 'drt_weather_api_data', 'api_settings' );

	    add_settings_field( 'drtwa_data_setting_days', 'Days', 'drtwa_data_setting_days', 'drt_weather_api_data', 'api_settings' );

	    add_settings_field( 'drtwa_data_setting_temp_type', 'Temperature Type', 'drtwa_data_setting_temp_type', 'drt_weather_api_data', 'api_settings' );
	    
	}
	add_action( 'admin_init', 'dbi_register_settings' );

	function drt_weather_api_data_options_validate( $input ) {

	    $newinput['api_key'] = trim( $input['api_key'] );

	    if ( ! preg_match( '/^[a-z0-9]{32}$/i', $newinput['api_key'] ) ) {
	        $newinput['api_key'] = '';
	    }

    	return $newinput;
	}

	function drtwa_data_section_text() {
	    echo '<p>Here you can set all the options for using the API</p>';
	}

	function drtwa_data_setting_api_key() {
	    $options = get_option( 'drt_weather_api_data_options' );
	    echo "<input id='drtwa_data_setting_api_key' name='drt_weather_api_data_options[api_key]' type='text' value='" . esc_attr( $options['api_key'] ) . "' />";
	}

	function drtwa_data_setting_location() {
	    $options = get_option( 'drt_weather_api_data_options' );
	    echo "<input id='drtwa_data_setting_location' name='drt_weather_api_data_options[location]' type='text' value='" . esc_attr( $options['location'] ) . "' />";
	}

	function drtwa_data_setting_days() {
	    $options = get_option( 'drt_weather_api_data_options' );
	    echo "<input id='drtwa_data_setting_days' name='drt_weather_api_data_options[days]' type='number' min='1' max='10' value='" . esc_attr( $options['days'] ) . "' />";
	}

	function drtwa_data_setting_temp_type() {
	    $options = get_option( 'drt_weather_api_data_options' );
	  
	    echo "<select name='drt_weather_api_data_options[temp_type]' id='drtwa_data_setting_temp_type'><option value='temp_c'>".strtoupper('celsius')." (℃)</option><option value='temp_f'>".strtoupper('fahrenheit')." (℉)</option></select>";
	}



