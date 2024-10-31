<?php
function addWeatherGetData($code,$t_type){
	
	$fdata = "http://weather.yahooapis.com/forecastrss?p=".$code."&u=".strtolower($t_type);
		
	$file=search_file_cached($code,$fdata);

	$xmlObj = new MiniXMLDoc();

	$xmlObj->fromString($file);
	$xml = $xmlObj->getRoot();

	$template=array();

	$head = $xml->getElement('channel');
	if($head){
		if($head -> xchildren[6] -> xattributes["region"]){
			$city= $head -> xchildren[6] -> xattributes["city"] .", ".$head -> xchildren[6] -> xattributes["region"] ;
		}else{
			$city= $head -> xchildren[6] -> xattributes["city"] .", ".$head -> xchildren[6] -> xattributes["country"] ;
		}
		$template['city']=$city; //nome citta
	
		$template['time']=""; //orario
	
		$template['lat']=""; //latitudine
	
		$template['lon']=""; //longitudine
	
		$template['suns']=$head -> xchildren[10] -> xattributes["sunrise"]; //alba

		$template['sunr']=$head -> xchildren[10] -> xattributes["sunset"];	 //tramonto
	
		$template['zone']=""; //zona
	
		$template['lsup']=$head -> xchildren[12] -> xchildren[5] -> xattributes["date"]; //data rilevamento
	
	
		$template['obst']=""; //osservatorio
	
		$template['tmp']=$head -> xchildren[12] -> xchildren[5] -> xattributes["temp"]; //temperatura
		
	
		$template['flik']=""; //feel like
	
		$template['weather']=$head -> xchildren[12] -> xchildren[5] -> xattributes["text"]; //meteo
	
		$template['icon']=$head -> xchildren[12] -> xchildren[5] -> xattributes["code"]; //icona tempo
	
		$template['bar_r']=$head -> xchildren[9] -> xattributes["pressure"]; //pressione
	
		$template['bar_d']=$head -> xchildren[7] -> xattributes["pressure"];  //tipo pressione
	
		$template['wind_s']=$head -> xchildren[8] -> xattributes["speed"];  //velocità vento
	
		$template['wind_gust']=$head -> xchildren[9] -> xattributes["chill"]; //raffica 
	
		$template['wind_d']=$head -> xchildren[8] -> xattributes["direction"];  //direzione vento (°)
	
		$template['wind_t']="";  //direzione vento (N,S,E,W)

		$template['hmid']=$head -> xchildren[9] -> xattributes["humidity"]; //umidità in %
	
		$template['vis']=$head -> xchildren[9] -> xattributes["visibility"]; //visibilità (distanza)

		$template['uv_i']=""; // raggi uv
	
		$template['uv_t']=""; // tipo raggi
	
		$template['dewp']=""; //Punto di rugiada (in temp)
	
		$template['moon_icon']=""; //icona luna
	
		$template['moon_t']=""; //tipo luna
	
		list($template['city'])=explode(",",$template['city']);
		list($template['city'])=explode("/",$template['city']);
	

		$template['tmp']=$template['tmp']."&deg;";
		$template['flick']=$template['flick']."&deg;";
		$template['dewp']=round(($template['dewp']-32)/(1.8))."&deg;";
		$template['wind_s']=$template['wind_s']." ".$head -> xchildren[7] -> xattributes["speed"];
		$template['vis']=$template['vis']." ".$head -> xchildren[7] -> xattributes["distance"];


		switch($template['icon']){
		  default:
		  case 44:
				$icon=0;
				break;
		  case 5:
		  case 8:
		  case 9:
		  case 11:
				$icon=1;
				break;
		  case 15:
		  case 25:
				$icon=2;
				break;
		  case 20:
				$icon=3;
				break;
		  case 32:
		  case 36:
				$icon=4;
				break;
		  case 19:
		  case 21:
		  case 22:
				$icon=5;
				break;
		  case 0:
		  case 1:
		  case 2:
		  case 3:
		  case 4:
		  case 17:
		  case 35:
		  case 37:
		  case 38:
		  case 47:
				$icon=6;
				break;
		  case 39:
				$icon=7;
				break;
		  case 13:
				$icon=8;
				break;
		  case 23:
		  case 24:
				$icon=9;
				break;
		  case 14:
				$icon=10;
				break;
		  case 14:
		  case 16:
		  case 18:
		  case 41:
		  case 42:
		  case 43:
		  case 46:
				$icon=11;
				break;
		  case 10:
		  case 12:
		  case 40:
		  case 45:
				$icon=12;
				break;
		  case 26:
				$icon=13;
				break;
		  case 6:
		  case 7:
				$icon=14;
				break;
		  case 28:
		  case 30:
		  case 34:
				$icon=15;
				break;
		  case 27:
		  case 29:
		  case 31:
		  case 33:
				$icon=16;
				break;
		}
		$template['icon']=$icon;
		
		return $template;
	}else{
		return false;
	}

}
?>