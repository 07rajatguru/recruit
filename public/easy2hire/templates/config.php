<?php

$app_url = getenv('APP_URL');

define("SITE_PATH", $app_url.'/index');
define("Overview", "/overview");
define("Dashboard", "/front-dashboard");
define("Features", "/features");
define("Modules", "/modules");
define("Time_Saver", "/time_saver");
define("Transparent", "/transparent");
define("Data_Analytics", "/data_insight");
define("About_us", "/about_us");
define("contact", "/contact_us");
define("Careers", "/careers");
define("Coming_Soon", "/coming_soon");

// $PageName = basename($_SERVER['PHP_SELF']);

if (($PageName == "time_saver") || ($PageName == "transparent") || ($PageName == "data_insight")){
	$PageName = "benefits";
} 

?>