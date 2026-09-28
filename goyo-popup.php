<?php defined( 'ABSPATH' ) or die( 'Nothing to see here.' ); // Security (disable direct access).
/*
 * Plugin Name: Goyo Popup
 * Description: 반응형 팝업 플러그인
 * Version: 2.0
 * Text Domain: goyo-popup
 * Domain Path: /languages
 * Author: 고요펑크

*/

define( 'GOYO_POPUP_PATH', plugin_dir_path( __FILE__ ) );
define( 'GOYO_POPUP_URL', plugins_url( '', __FILE__ ) );
define( 'GOYO_POPUP_BASENAME', dirname( plugin_basename( __FILE__ ) ) );
define( 'GOYO_POPUP_TABLE', 'goyo_popup' );

// add_image_size( 'goyopopup_thumbnail', 150, 150, true );

require_once GOYO_POPUP_PATH . 'classes/class-goyo-popup-basic.php';
require_once GOYO_POPUP_PATH . 'classes/class-goyo-popup-front.php';
require_once GOYO_POPUP_PATH . 'classes/class-goyo-popup-admin.php';

register_activation_hook( __FILE__, array( 'GOYO_Popup_Admin', 'active' ) );
register_uninstall_hook( __FILE__, array( 'GOYO_Popup_Admin', 'uninstall' ) );
