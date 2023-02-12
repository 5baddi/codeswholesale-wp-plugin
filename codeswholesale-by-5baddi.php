<?php

/*
Plugin Name: CodesWholesale by @5baddi
Author: 5baddi
Author URI: http://www.baddi.info/
Description: WordPress integration with CodesWholesale API.
Version: 0.2
Text Domain: cws_5baddi
*/

use BaddiServices\CodesWholesale\CodesWholesaleBy5baddi;

set_time_limit(300);

$vendorPath = sprintf('%s/vendor/autoload.php', __DIR__);

if (! file_exists($vendorPath)) {
    throw new \RuntimeException(
        'Unable to load dependencies. please install dependencies!'
    );
}

require_once($vendorPath);

define('CWS_5BADDI_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('CWS_5BADDI_PLUGIN_BASEPATH', plugin_dir_path(__FILE__));
define('CWS_5BADDI_PLUGIN_NAME_PERFIX', 'cws_5baddi');
define('CWS_5BADDI_PLUGIN_TEXT_DOMAIN', CWS_5BADDI_PLUGIN_NAME_PERFIX);
define('CWS_5BADDI_PLUGIN_ASSETS_URL', sprintf('%sassets/', plugin_dir_url(CWS_5BADDI_PLUGIN_BASENAME)));
define('CWS_5BADDI_PLUGIN_ASSETS_PATH', sprintf('%sassets/', CWS_5BADDI_PLUGIN_BASEPATH));

// Should be float value 0.0
/** @var float */
define('CWS_5BADDI_PLUGIN_VERSION', '0.3');
define('CWS_5BADDI_PLUGIN_ASSETS_VERSION', '0.0.8'); 
define('CWS_5BADDI_PLUGIN_DB_VERSION', '0.1');

$codesWholesaleBy5baddi = CodesWholesaleBy5baddi::getInstance();
