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
		        do_settings_sections( 'weather_api_data' ); 
		        submit_button( 'Save Settings' );	
	        ?>
	    </form>
	    <?php

	     $test = do_shortcode( '[weathertoday]' );
	     echo "<pre>";
	     print_r($test);
	     echo "<br/>";
	     $options = get_option( 'weather_api_data_options' );
	     echo "<pre>";
	     print_r( $options);
	}

	if (!function_exists('register_settings')){

		function register_settings(){

		    register_setting( 'weather_api_data_options', 'weather_api_data_options', 'weather_api_data_options_validate' );

		    add_settings_section( 'api_settings', 'API Settings', 'data_section_text', 'weather_api_data' );

		    add_settings_field( 'data_setting_api_key', 'API Key', 'data_setting_api_key', 'weather_api_data', 'api_settings' );

		    add_settings_field( 'data_setting_location', 'Location', 'data_setting_location', 'weather_api_data', 'api_settings' );

		    add_settings_field( 'data_setting_days', 'Days', 'data_setting_days', 'weather_api_data', 'api_settings' );

		    add_settings_field( 'data_setting_temp_type', 'Temperature Type', 'data_setting_temp_type', 'weather_api_data', 'api_settings' );
		    
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

	function data_setting_api_key() {
	    $options = get_option( 'weather_api_data_options' );
	    echo "<input id='data_setting_api_key' name='data_setting_api_key' type='text' value='" .$options['api_key']. "' />";
	}

	function data_setting_location() {
	    $options = get_option( 'weather_api_data_options' );
	    echo "<input id='data_setting_location' name='data_setting_location' type='text' value='" .$options['location']. "' />";
	}

	function data_setting_days() {
	    $options = get_option( 'weather_api_data_options' );
	    echo "<input id='data_setting_days' name='data_setting_days' type='number' min='1' max='10' value='" .$options['days']. "' />";
	}

	function data_setting_temp_type() {
	    $options = get_option( 'weather_api_data_options' );
	  
	    echo "<select name='data_setting_temp_type' id='data_setting_temp_type'><option value='temp_c'>".strtoupper('celsius')." (℃)</option><option value='temp_f'>".strtoupper('fahrenheit')." (℉)</option></select>";
	}

	// public function getWeatherApiData(){
	// 	//$url = 'http://api.weatherapi.com/v1/forecast.json?key=1231e2e03e0f48cfb6375659232106&q=chandigarh&days=3&aqi=yes&alerts=yes';
	// 	$options = get_option('plugin_options');
	// 	print_r($options);
	// }

	add_action('admin_post_submit-form', 'handle_form_action');

	function handle_form_action(){
	    global $wpdb;
	    $key = $_POST['data_setting_api_key'];
	    $location = $_POST['data_setting_location'];
	    $data = array('key'=>$key,'location'=>$location);
	    $wpdb->insert( 'wp_weather_data', $data);

	    // redirect after insert alert
	    wp_redirect(admin_url('admin.php?page=weather_api_data'));
	    die();


	} 

