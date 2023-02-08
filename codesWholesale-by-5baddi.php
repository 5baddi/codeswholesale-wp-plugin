<?php

/*
Plugin Name: CodesWholesale by @5baddi
Author: 5baddi
Author URI: http://www.baddi.info/
Description: WordPress integration with CodesWholesale API.
Version: 0.1
Text Domain: codesWholesale_5baddi
*/

use CodesWholesaleBy5baddi\CodesWholesaleBy5baddi;

define('CWS_5BADDI_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('CWS_5BADDI_PLUGIN_BASEPATH', plugin_dir_path(__FILE__));
define('CWS_5BADDI_PLUGIN_NAME_PERFIX', 'codesWholesale_5baddi');
define('CWS_5BADDI_PLUGIN_TEXT_DOMAIN', CWS_5BADDI_PLUGIN_NAME_PERFIX);
define('CWS_5BADDI_PLUGIN_ASSETS_URL', sprintf('%sassets/', plugin_dir_url(CWS_5BADDI_PLUGIN_BASENAME)));

// Should be float value 0.0
/** @var float */
define('CWS_5BADDI_PLUGIN_VERSION', '0.1');
define('CWS_5BADDI_PLUGIN_ASSETS_VERSION', '0.1'); 
define('CWS_5BADDI_PLUGIN_DB_VERSION', '0.1');

$codesWholesaleBy5baddi = CodesWholesaleBy5baddi::getInstance();