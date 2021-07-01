<?php
/*
 * Plugin Name: Plugin projet WP Formulaire de contact
 * Author: Groupe
 * Author URI: myges.fr
 * Version: 0.1
 * Description: Formulaire de contact
 */

defined('ABSPATH') or die('Accès refusé');

// Include functions file
require_once plugin_dir_path(__FILE__).'includes/plg-functions.php';

register_activation_hook(__FILE__, 'plgesgi_activation');

function plgesgi_activation(){
    global $wpdb;
    $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}contact_plugin (id INT AUTO_INCREMENT PRIMARY KEY , name VARCHAR(255) NOT NULL);");
}

register_deactivation_hook( __FILE__, 'plgesgi_deactivation' );
function plgesgi_deactivation() {
    global $wpdb;
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}contact_plugin;");
}