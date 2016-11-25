<?php
/**
* Plugin Name: Amenities Map
* Description: This plugin will add an amenity map to your webpage. 
* Version: 1.0.0
* Author: Zafer Sawaf
*/

define('AMENITY_PATH', plugin_dir_path(__FILE__));
define('AMENITY_URL', plugins_url('', __FILE__));

require(AMENITY_PATH.'core/init-data.php');
require(AMENITY_PATH.'core/scripts-styles.php');
require(AMENITY_PATH.'core/functions.php');
require(AMENITY_PATH.'core/init-meta-fields.php');
require(AMENITY_PATH.'core/init-settings.php');
require(AMENITY_PATH.'core/init-styles.php');
require(AMENITY_PATH.'core/am-shortcode.php');



