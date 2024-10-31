=== addWeather ===
Author: Eduardo Chiaro
Contributors: theDI, alpinism, Sufi Mustafa
Donate link: http://www.thedeveloperinside.com/
Tags: weather
Requires at least: 2.2
Tested up to: 2.8.6
Stable tag: trunk

addWeather plugin allows you to set up displays for cities of your choice.
In English, Italian, Persian, Russian


== Description ==
addWeather plugin allows you to set up displays for cities of your choice.

== Frequently Asked Questions ==


== Screenshots == 
1. addWeather in action
2. search city


== Installation ==
1. Download it.
2. Upload this folder to your /wp-content/plugins/ directory.
4. Active the addWeather Plugin in Plugin Management.
5. Place this code in your template:
<pre><code>
	&lt;?php wp_addweather();?&gt;
	...
</code></pre>
or use the widget.
6. Use Plugin &rarr; addWeather for select city.

== Changelog ==

= 2.5.1 =
* trim ciy name for long name
* fix internationalization problem
= 2.5 =
* plugin name change
= 2.4.16 =
* belorussian language
= 2.4.15 =
* russian language
= 2.4.14 =
* new png template
* changelog
= 2.4.13 =
* persian language
* bug fix on template
= 2.4.9 =
* bug fix
* yahoo system
= 2.4.8 =
* bug fix
* multi-language: Italian, English
= 2.4.7 =
* reset plugin is always visible
* choose metric system or english system
= 2.4.6.2 =
* fix template
* fix xml
* new reset plugin
= 2.4.6.1 =
* fix for php error level
* fix for feel like
= 2.4.6 =
* fix name change in siteurl. Thanks to Eliot Lear.
* new cache directory check
* new save system
= 2.4.5 =
* fix file_get_contents (auto switch from CURL on file_get_contents). Thanks to Ryan
= 2.4.3 =
* fix per template
= 2.4.1 =
* corretto bug sul sistema di cache
= 2.4 =
* multy-city
* nuovo sistema di cache
* grafica adatta alla versione 2.5 di wordpress
* gestione plugin spostata da Impostazione a Plugin
* bug fix
= 2.3.4 =
* corretto errore in attivazione per permessi file
= 2.3.3 =
* corretto errore in installazione (su php 4)
= 2.3.2 =
* corretto errore in installazione (credo, visto che a me andava anche prima)
= 2.3.1 =
* xml parser for PHP4
= 2.3 =
* new xml parser (simplexml)
* template manager
* working with php5
= 2.2 =
* bug fix for xml parser
= 2.1 =
* bug fix for wordpress 2.3


== Contributors ==
= Developer =
* Eduardo Chiaro    http://www.eduardochiaro.it
* alpinism     		http://www.erata.net
= Xml Parser =
* minixml			http://minixml.psychogenic.com
= Language =
* Persian Language		Sufi Mustafa	http://www.bia2arak.com
* Belorussian Language	ilyuha		http://antsar.info