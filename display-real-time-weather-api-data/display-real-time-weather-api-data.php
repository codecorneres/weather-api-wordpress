<?php
/*
Plugin Name:  Display Real Time Weather API Data  
Description:  This Plugin Display Weather Information On Your Site Through Shortcode. It will be displayed on the Settings in WordPress admin area. 
Version:      1.0
Author:       Code Corners 
Author URI:   https://codecorners.in
Text Domain:  display-real-time-weather-api-data
*/

	//If this file is called directly, abort!!!
	defined( 'ABSPATH' ) or die('Access denied!');

	//Define Dirpath for hooks
	define( 'DIR_PATH', plugin_dir_path( __FILE__ ) );


    wp_enqueue_script('script', plugins_url( '/js/script.js' , __FILE__ ) , array( 'jquery' ),'',true );
   
    wp_localize_script( 'ajax', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php')));

    wp_enqueue_style( 'style', plugins_url( '/css/style.css', __FILE__ ),array(),'all' );

    
	include('includes/shortcodes.php');
	include('includes/weather_api_data.php');

    if ( !function_exists('add_settings_page') ) {
		function add_settings_page() {
		    add_menu_page( 'Weather API Data', 'Weather API Data', 'manage_options', 'weather_api_data', 'render_plugin_settings_page' );
		}
	}
	add_action( 'admin_menu', 'add_settings_page' );


	function render_plugin_settings_page() {
		 settings_errors(); 
	    ?>
	    <h2>Real Time Weather API Data Settings</h2>
	    <form action="options.php" method="post">
	        <?php 
		        settings_fields( 'weather_api_data_options' );
		        do_settings_sections( 'weather_api_data_options' ); 
		        submit_button( 'Save Settings' );	
	        ?>
	    </form>
	    <?php
	}

	if (!function_exists('register_settings')){

		function register_settings(){

		    register_setting( 'weather_api_data_options', 'weather_api_data_options', 'weather_api_data_options_validate' );

		    add_settings_section( 'api_settings', 'API Settings', 'data_section_text', 'weather_api_data_options' );

		    add_settings_field( 'weather_api_key', 'API Key', 'weather_api_key', 'weather_api_data_options', 'api_settings' );

		    add_settings_field( 'weather_api_location', 'Location', 'weather_api_location', 'weather_api_data_options', 'api_settings' );

		    add_settings_field( 'weather_api_days', 'Days', 'weather_api_days', 'weather_api_data_options', 'api_settings' );

		    add_settings_field( 'weather_api_temp_type', 'Days', 'weather_api_temp_type', 'weather_api_data_options', 'api_settings' );

		   // Register the settings
		   register_setting('weather_api_data_options', 'weather_api_key');
		   register_setting('weather_api_data_options', 'weather_api_location');
		   register_setting('weather_api_data_options', 'weather_api_days');
		   register_setting('weather_api_data_options', 'weather_api_temp_type');
		    
		}
	}
	add_action( 'admin_init', 'register_settings' );

	function weather_api_data_options_validate( $input ) {

	    $newinput['api_key'] = trim( $input['api_key'] );

	    if ( ! preg_match( '/^[a-z0-9]{32}$/i', $newinput['api_key'] ) ) {
	        $newinput['api_key'] = '';
	    }

    	return $newinput;
	}

	function data_section_text() {
	    echo '<p>Here you can set all the options for using the API</p>';
	}

	function weather_api_key() {
	    $api_key = get_option( 'weather_api_key' );
	    echo "<input id='data_setting_api_key' name='weather_api_key' type='text' value='" .$api_key. "' />";
	}

	function weather_api_location() {
	    $location = get_option( 'weather_api_location' );
	    echo "<input id='data_setting_location' name='weather_api_location' type='text' value='" .$location. "' />";
	}

	function weather_api_days() {
	    $days = get_option( 'weather_api_days' );
	    echo "<input id='data_setting_days' name='weather_api_days' type='number' min='1' max='10' value='" .$days. "' />";
	}

	function weather_api_temp_type() {
	    $temp_type = get_option( 'weather_api_temp_type' ); ?>
	    <select name='weather_api_temp_type' id='data_setting_temp_type'>
	    	<option value='temp_c' <?php selected($temp_type, 'temp_c'); ?> > <?php echo strtoupper('celsius'); ?> (℃)</option>
	    	<option value='temp_f' <?php selected($temp_type, 'temp_f'); ?> > <?php echo strtoupper('fahrenheit'); ?> (℉)</option>
	    </select>
	<?php
	}


