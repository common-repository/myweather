<?php 

$addweather_version="2.5.1";

function destroy_cache(){
	$cache=dirname(__FILE__)."/cache/";
	$d = dir($cache);
	while(false !== ($entry = $d->read())) {
		if(is_file($cache.$entry) && $entry!="index.html"){
			unlink($cache.$entry);					
		}
	}
	$d->close();
}

load_plugin_textdomain('addweather','wp-content/plugins/'.dirname(plugin_basename(__FILE__)). '/languages', dirname(plugin_basename(__FILE__)). '/languages' );

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
if($city!="") $city=unserialize($city);

if(isset($_GET['delete'])){
	unset($city[$_GET['delete']]);
	update_option('wp_weather_city',serialize($city));
}

if(isset($_POST['city']) && $_POST['city']!=""){
	if(is_array($city)){
		destroy_cache();
		$city[$_POST['city']]=$_POST['city-'.$_POST['city']];
		update_option('wp_weather_city',serialize($city));
	}
	if($city=="" && get_option('wp_weather_city_temp')){
		$city[$_POST['city']]=$_POST['city-'.$_POST['city']];
		update_option('wp_weather_city',serialize($city));
	}else{		
		destroy_cache();
		$city[$_POST['city']]=$_POST['city-'.$_POST['city']];
		add_option('wp_weather_city',serialize($city),'addweather city');
		add_option('wp_weather_city_temp',"C",'addweather city temp');
		add_option('wp_weather_city_cache',"3600",'addweather city cache');
		add_option('wp_weather_template',"default",'addweather city template');
	}
}
if(isset($_POST['temp']) && $_POST['temp']!=""){
	if(get_option('wp_weather_city_temp')){
		destroy_cache();
		update_option('wp_weather_city_temp',$_POST['temp']);
		update_option('wp_weather_city_trim',$_POST['trim']);
		update_option('wp_weather_city_cache',$_POST['cache']);
		update_option('wp_weather_template',$_POST['template']);
	}else{
		destroy_cache();
		add_option('wp_weather_city_temp',$_POST['temp'],'addweather city temp');
		add_option('wp_weather_city_trim',$_POST['trim'],'addweather city name trim');
		add_option('wp_weather_city_cache',$_POST['cache'],'addweather city cache');
		add_option('wp_weather_template',$_POST['template'],'addweather city template');
	}
}
if(isset($_POST['reset']) && $_POST['reset']>0){
	delete_option("wp_weather_city_name");
	delete_option("wp_weather_city");
	delete_option('wp_weather_city_trim');
	delete_option("wp_weather_city_temp");
	delete_option("wp_weather_city_cache");
	delete_option("wp_weather_template");

}

$city=get_option('wp_weather_city');
if($city!="") $city=@unserialize($city);

$citytemp=get_option('wp_weather_city_temp');
$template=get_option('wp_weather_template');
$nametrim=get_option('wp_weather_city_trim');

if(!$citytemp){
	$citytemp="C";
}
if(!$template){
	$template="default";
}
$citycache=get_option('wp_weather_city_cache');
if(!$citycache){
	$citycache="3600";
}
?>
<div class="wrap">
	<h2>addWeather (version <?php echo $addweather_version?>)</h2>
	<table width="100%">
	<tr>
	<td width="50%" valign="top">
	<form action="plugins.php?page=myweather.php" method="post">
	<p><?php echo __('if you have problems, click here to reset the plug-in','addweather')?></p><input type="hidden" name="reset" value="1" />
	<div class="submit"><input type="submit" name="Submit" value="Reset Plugin" /></div>
	</form>
	</td>
	</tr>
	</table>
	<?php if(is_array($city)){?>
	<table width="100%">
	<tr>
	<td width="50%" valign="top">
	<form action="plugins.php?page=addweather.php" method="post">		
			<h3><?php echo __('addWeather Configuration','addweather')?></h3>
		<table class="form-table"> 
			<tr valign="top"> 
				<th scope="row"><?php echo __('System of measurement','addweather')?></th>
                <td><select name="temp">
                <option value="F"<?php if($citytemp=="F"){echo ' selected="selected"';}?>><?php echo __('English','addweather')?></option>
                <option value="C"<?php if($citytemp=="C"){echo ' selected="selected"';}?>><?php echo __('Metric','addweather')?></option>
                </select></td>                      
			</tr>
			<tr valign="top"> 
				<th scope="row"><?php echo __('Cache time','addweather')?></th>
                <td><input type="text" name="cache" size="6" value="<?php echo $citycache;?>" /><?php echo __('seconds','addweather')?></td>                      
			</tr>
			<tr valign="top"> 
				<th scope="row"><?php echo __('Cache dir','addweather')?></th>
                <td><?php
                if(is_writable(dirname(__FILE__)."/cache/")){
                	echo __('writable','addweather');	
                }else{
                	echo "<strong style=\"color:red;\">".__('not writable','addweather')."</strong>";	
                }
                ?></td>                      
			</tr>
			<tr valign="top"> 
				<th scope="row"><?php echo __('Template','addweather')?></th>
                <td>
                <select name="template">
                <?php
                $d = dir(dirname(__FILE__)."/templates");
				while($entry=$d->read()) {
					if($entry!="." && $entry!=".." && $entry!=".svn" && $entry!=".DS_Store"){
						if($entry == $template){
							echo '<option value="'.$entry.'" selected="selected">'.$entry."</option>\n";
						}else{
							echo '<option value="'.$entry.'">'.$entry."</option>\n";
						}
						
					}
				}
				$d->close();
                ?>
                </select></td>                      
			</tr>
			<tr valign="top"> 
				<th scope="row"><?php echo __('Trim city name','addweather')?></th>
                <td>
<input type="checkbox" name="trim" value="1" <?php if ($nametrim) echo "checked=\"1\""; ?>/></td>                      
			</tr>
		</table>
		<div class="submit"><input type="submit" name="Submit" value="<?php echo __('Update Config','addweather')?> &raquo;" /></div>

	</form>
	</td>
	<td width='50%' valign="top">
			<h3><?php echo __('Actual Cities','addweather')?></h3>
			<?php if(is_array($city)){?>
			<table class="form-table"> 
			<?php foreach($city as $code => $cityname){?>
			<tr valign="top"> 
				<th scope="row"><?php echo $cityname?></th>
                <td><a href="?page=addweather.php&delete=<?php echo $code?>"><?php echo __('remove','addweather')?></a></td>                      
			</tr>
			<?php }?>
			</table>

			<?php }?>
	</td>
	</tr>
	</table>
	
	<?php }?>
	
	<form action="plugins.php?page=addweather.php#search" method="post">		
			<h3><?php echo __('Search city','addweather')?></h3>
		<table class="form-table"> 
			<tr valign="top"> 
				<th scope="row"><?php echo __('Insert city name','addweather')?></th>
                <td><input type="text" name="code" value="<?php echo $_POST['code']?>" /></td>                     
			</tr>
		</table>
		<div class="submit"><input type="submit" name="Submit" value="<?php echo __('Search city','addweather')?> &raquo;" /></div>
	</form>

<?php


if(isset($_POST['code']) && $_POST['code']!=""){

	require_once(dirname(__FILE__).'/minixml/minixml.inc.php');

	$fdata = "http://xoap.weather.com/search/search?where=".urlencode($_POST['code']);

	
	$file=get_xml($fdata);
		
	$xmlObj = new MiniXMLDoc();
	$xmlObj->fromString($file);
	$xml = $xmlObj->getRoot();
	
	$value=$xml->getElement('search');
	$nn=0;
	if(is_object($value)){
		$partListChildren = $value->getAllChildren();
		$nn=count($partListChildren);
	}
	if($nn==0){
		echo "<h3>".__('no city found','addweather')."</h3>";
	}else{
	$cityname= array();
	?>
	<form action="plugins.php?page=addweather.php" method="post">
	<h3><a name="search"></a><?php echo __('Search Result','addweather')?></h3>
		<table class="form-table"> 
			<tr valign="top"> 
<th scope="row"><?php echo __('City Found','addweather')?></th>
<td>
<select name="city">
<?php foreach($partListChildren as $city){?>
<option value="<?php echo $city->attribute('id')?>"><?php echo $city->getValue();?></option>
<?php }?>
</select>
<?php foreach($partListChildren as $city){?>
<input type="hidden" name="city-<?php  echo $city->attribute('id')?>" value="<?php echo $city->getValue();?>" />
<?php }?>
</td>
</tr>
</table>
<div class="submit"><input type="submit" name="Submit" value="<?php echo __('Save city','addweather')?> &raquo;" /></div>
</form>
<?php 
	}
}
?>
		</div>
