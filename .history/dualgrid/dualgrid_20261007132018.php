<?php
/**
 * Plugin Name: Dualgrid - Componentes CreativeWilds
 * Description: Shortcodes y panel administrable para la landing CreativeWilds.
 * Version: 3.4.1
 * Author: RolandoCutie
 * Text Domain: dualgrid
 */

if (!defined('ABSPATH')) {
    exit;
}

define('DUALGRID_VERSION', '1.0.2');
define('DUALGRID_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DUALGRID_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once DUALGRID_PLUGIN_DIR . 'includes/creativewilds-data.php';
require_once DUALGRID_PLUGIN_DIR . 'includes/creativewilds-contact.php';
require_once DUALGRID_PLUGIN_DIR . 'includes/creativewilds-shortcodes.php';
require_once DUALGRID_PLUGIN_DIR . 'includes/creativewilds-admin.php';
