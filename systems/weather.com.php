<?php
function addWeatherGetData(){
	$fdata = "http://xoap.weather.com/weather/local/".$code."?cc=*&dayf=0";
		
	$file=search_file_cached($code,$fdata);

	$xmlObj = new MiniXMLDoc();

	$xmlObj->fromString($file);
	$xml = $xmlObj->getRoot();

	$template=array();

	$head = $xml->getElement('head');
	if($head){
		$locale = $head->getElement('locale');

		$template['locale']=$locale->getValue(); //lingua
	
		$form = $head->getElement('form');
	
		$template['form']=$form->getValue(); //formato
	
		$ut = $head->getElement('ut');
	
		$template['ut']=$ut->getValue(); //scala temperatura
	
		$ud = $head->getElement('ud');
	
		$template['ud']=$ud->getValue(); //scala distanza
	
		$us = $head->getElement('us');
	
		$template['us']=$us->getValue(); //scala velocita/tempo
	
		$up = $head->getElement('up');
	
		$template['up']=$up->getValue(); //scala pressione
	
		$ur = $head->getElement('ur');
	
		$template['ur']=$ur->getValue(); //
	
		$loc = $xml->getElement('loc');
		$dnam = $loc->getElement('dnam');	
	
		$template['city']=$dnam->getValue(); //nome citta
	
		$tm = $loc->getElement('tm');	
	
		$template['time']=$tm->getValue(); //orario
	
		$lat = $loc->getElement('lat');	
	
		$template['lat']=$lat->getValue(); //latitudine
	
		$lon = $loc->getElement('lon');	
	
		$template['lon']=$lon->getValue(); //longitudine
	
		$suns = $loc->getElement('suns');	
	
		$template['suns']=$suns->getValue(); //alba
	
		$sunr = $loc->getElement('sunr');	
	
		$template['sunr']=$sunr->getValue(); //tramonto
	
		$zone = $loc->getElement('zone');	
	
		$template['zone']=$zone->getValue(); //zona
	
		$cc = $xml->getElement('cc');
		$lsup = $cc->getElement('lsup');	
	
		$template['lsup']=$lsup->getValue(); //data rilevamento
	
		$obst = $cc->getElement('obst');	
	
		$template['obst']=$obst->getValue(); //osservatorio
	
		$tmp = $cc->getElement('tmp');	
	
		$template['tmp']=$tmp->getValue(); //temperatura
	
		$flik = $cc->getElement('flik');	
	
		$template['flik']=$flik->getValue(); //feel like
	
		$t = $cc->getElement('t');	
	
		$template['weather']=$t->getValue(); //meteo
	
		$icon = $cc->getElement('icon');	
	
		$template['icon']=$icon->getValue(); //icona tempo
	
		$bar = $cc->getElement('bar');	
		$r = $bar->getElement('r');	
	
		$template['bar_r']=$r->getValue(); //pressione

		$bar = $cc->getElement('bar');	
		$d = $bar->getElement('d');	
	
		$template['bar_d']=$d->getValue(); //tipo pressione

		$wind = $cc->getElement('wind');
		$s = $wind->getElement('s');		
	
		$template['wind_s']=$s->getValue(); //velocità vento

		$wind = $cc->getElement('wind');
		$gust = $wind->getElement('gust');	
	
		$template['wind_gust']=$gust->getValue(); //raffica 

		$wind = $cc->getElement('wind');
		$d = $wind->getElement('d');	
	
		$template['wind_d']=$d->getValue(); //

		$wind = $cc->getElement('wind');
		$t = $wind->getElement('t');	
	
		$template['wind_t']=$t->getValue(); //direzione vento (N,S,E,W)

		$hmid = $cc->getElement('hmid');
	
		$template['hmid']=$hmid->getValue(); //umidità in %

		$vis = $cc->getElement('vis');
	
		$template['vis']=$vis->getValue(); //visibilità (distanza)

		$uv = $cc->getElement('uv');
		$i = $uv->getElement('i');	
	
		$template['uv_i']=$i->getValue(); // raggi uv

		$uv = $cc->getElement('uv');
		$t = $uv->getElement('t');	
	
		$template['uv_t']=$t->getValue(); // tipo raggi

		$dewp = $cc->getElement('dewp');
	
		$template['dewp']=$dewp->getValue(); //Punto di rugiada (in temp)

		$moon = $cc->getElement('moon');
		$icon = $moon->getElement('icon');	
	
		$template['moon_icon']=$icon->getValue(); //icona luna

		$moon = $cc->getElement('moon');
		$t = $moon->getElement('t');	
	
		$template['moon_t']=$t->getValue(); //tipo luna
	
		list($template['city'])=explode(",",$template['city']);
		list($template['city'])=explode("/",$template['city']);	
		
		switch($t_type){
			default:
			case "F":
				$template['tmp']=$template['tmp']."&deg;";
				$template['flick']=$template['flick']."&deg;";
				$template['dewp']=round(($template['dewp']-32)/(1.8))."&deg;";
				$template['wind_s']=$template['wind_s']." mph";
				$template['vis']=$template['vis']." miles";
				break;
			case "C":
				$template['tmp']=round(($template['tmp']-32)/(1.8))."&deg;";
				$template['flick']=round(($template['flick']-32)/(1.8))."&deg;";
				$template['dewp']=round(($template['dewp']-32)/(1.8))."&deg;";
				$template['wind_s']=round($template['wind_s']*(1.6))." km/h";
				$template['vis']=round($template['vis']*(1.6))." km";
				break;
		}
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