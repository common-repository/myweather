<?php
/**
 * @package myweather
 * @author Eduardo Chiaro
 * @version 2.5.1
 */
/*
Plugin Name: addWeather
Version: 2.5.1
Plugin URI: http://www.thedeveloperinside.com/resources/addweather
Author: Eduardo Chiaro
Author URI: http://www.eduardochiaro.it/
Description: addWeather plugin allows you to set up displays for cities of your choice. 

 Contributors:
 ==============================================================================
 Developer              Eduardo Chiaro      http://www.eduardochiaro.it
 Developer              alpinism     		http://www.erata.net
 Xml Parser				minixml  			http://minixml.psychogenic.com
 Persian Language  		Sufi Mustafa		http://www.bia2arak.com/
 Russian Language 		FatCow				http://www.fatcow.com

*/

$plugin_directory = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__))."/";

function set_cache($n,$d){
	$f=@fopen($n,"w");
	if (!$f) {
		return false;
	} else {
		fwrite($f,$d);
		fclose($f);
		return true;
	}
}

function get_xml($linkedfile){
	if(function_exists("curl_init")){
		$ch = curl_init();
		$timeout = 5; // set to zero for no timeout
		curl_setopt ($ch, CURLOPT_URL, $linkedfile);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$file_contents = curl_exec($ch);
		curl_close($ch);
	}else{
		$file_contents=file_get_contents($linkedfile);
	}
	return $file_contents;
}

function search_file_cached($city,$wp_weather_xml){
	$plugin_directory=dirname(__FILE__)."/";
	$wp_weather_cache_temp=get_option('wp_weather_city_cache');
	$file=$city.".xml";
	$regexp="/(<!--date:)(.*)(-->)/";
	
	if(is_file($plugin_directory."cache/".$file)){
		$actualfile=file_get_contents($plugin_directory."cache/".$file);
		if(preg_match($regexp,$actualfile,$ext)){
			if(($ext[2]+$wp_weather_cache_temp)>time()){
				$xml=$actualfile;
			}else{
				$xml=get_xml($wp_weather_xml);
				$xml.="<!--date:".time()."-->";
				set_cache($plugin_directory."cache/".$file,$xml);
			}
		}
	}else{
		$xml=get_xml($wp_weather_xml);
		$xml.="<!--date:".time()."-->";
		set_cache($plugin_directory."cache/".$file,$xml);
	}
	return $xml;
}
function wp_myweather(){
	wp_addweather();
}

function wp_addweather(){
	require_once(dirname(__FILE__).'/minixml/minixml.inc.php');
	require_once(dirname(__FILE__)."/systems/yahoo.php");
	global $plugin_directory;
	$city=get_option('wp_weather_city');
	$cityname=get_option('wp_weather_city_name');
	
	if(!@preg_match("/{/i",$city) && $city!=""){
		$city=explode("||",$city);
		$cityname=explode("||",$cityname);
		$new=array();
		$x=0;
		foreach($city as $a){
			$new[$a]=$cityname[$x];
			$x++;
		}
		$city=serialize($new);
		update_option('wp_weather_city',$city);
		delete_option("wp_weather_city_name");
	}
	
	
	$citylist=unserialize($city);
	if(is_array($citylist)){
					
		echo '<div id="wp_addweather">'."\n\r";
		foreach($citylist as $code => $cityname){
			$t_type=get_option('wp_weather_city_temp');
			$usedtemplate=get_option('wp_weather_template');
			if(!$t_type){
				$t_type="C";
			}
			$template=addWeatherGetData($code,$t_type);
			
					
			if(get_option('wp_weather_city_trim')){
				if(strlen($template['city'])>11) $template['city'] = "<abbr title=\"".$template['city']."\">".substr($template['city'],0,8)."...</abbr>";
			}
			
			if(is_array($template)){				
				
				$template['icon']=$plugin_directory .'templates/'.$usedtemplate.'/icon/'.$template['icon'].'';
	
				$loadfile=file_get_contents(dirname(__FILE__).'/templates/'.$usedtemplate.'/template.html');
				foreach($template as $key=>$value){
					$loadfile=str_replace('{'.strtoupper($key).'}',$value,$loadfile);
				
				}
				echo $loadfile;
			}
		}
		echo "</div>"."\n\r";
	}
}
function addweather_style() {
	global $plugin_directory;
	$usedtemplate=get_option('wp_weather_template');
	print('<link href="'.$plugin_directory.'templates/'.$usedtemplate.'/style.css" rel="stylesheet" type="text/css" />'."\n");
}
// Admin
function addweather_admin() {
	include("admin.addweather.php");
}
function addweather_to_admin() {
	if (function_exists('add_submenu_page')) {
		add_submenu_page('plugins.php', 'addWeather', 'addWeather', 10, basename(__FILE__), 'addweather_admin');
	}
}

function addweather_plugin_actions( $links, $file ){
	$this_plugin = plugin_basename(__FILE__);
	
	if ( $file == $this_plugin ){
		$settings_link = '<a href="plugins.php?page=addweather.php">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}
add_action('admin_menu','addweather_to_admin');
add_action('plugin_action_links','addweather_plugin_actions',10, 2);
add_action('wp_head', 'addweather_style', 10);

// widget
function widget_addweather_init() {
    if ( !function_exists('register_sidebar_widget') ){
            return;
    }
    register_sidebar_widget(array('addWeather', 'widgets'), 'widget_addweather');    
}

function widget_addweather($args){
    extract($args);
    echo $before_widget . $before_title . $title . $after_title;
    wp_addweather();
    echo $after_widget;
}
add_action('widgets_init', 'widget_addweather_init');

?>