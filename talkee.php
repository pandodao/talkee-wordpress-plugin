<?php
/*
 * Plugin Name:       Talkee
 * Plugin URI:        https://talkee.pando.im/
 * Description:       Own Web3 Commenting and Chat with Ethereum Login & Wallets
 * Version:           0.0.1
 * Requires at least: 6.0
 * Requires PHP:      7.0
 * Author:            Pando.im
 * Author URI:        https://pando.im
 * License:           GPL v3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       talkee
 * Domain Path:       /languages
 */

// Check if Talkee Class already exists
if(!class_exists('\Pando\Talkee\Talkee')){
	require_once 'talkee.class.php';
	require_once 'talkee.admin.class.php';
    // Load i18n files
    load_plugin_textdomain( 'talkee', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    // Load Frontend Code
	$talkee = new \Pando\Talkee\Talkee();
    // Load Admin Console Code
	$talkee_admin = new \Pando\Talkee\Admin();
}