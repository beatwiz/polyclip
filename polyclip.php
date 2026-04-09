<?php
/**
 * Plugin Name: Polyclip
 * Description: Animated polygon clip-path for images — multi-image support, responsive breakpoints, pure CSS animation.
 * Version: 1.0.3
 * Author: jawesome.dev
 * Author URI: https://jawesome.dev
 * Text Domain: polyclip
 * License: GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define('POLYCLIP_VERSION', '1.0.3');
define( 'POLYCLIP_DIR', plugin_dir_path( __FILE__ ) );
define( 'POLYCLIP_URL', plugin_dir_url( __FILE__ ) );

require_once POLYCLIP_DIR . 'includes/presets.php';
require_once POLYCLIP_DIR . 'includes/class-polyclip-renderer.php';
require_once POLYCLIP_DIR . 'includes/class-polyclip-admin-page.php';

// Initialize admin page.
Polyclip_Admin_Page::init();

// Register custom element for Oxygen 6 / Breakdance Element Studio.
add_action( 'breakdance_loaded', function () {
    \Breakdance\ElementStudio\registerSaveLocation(
        \Breakdance\Util\getDirectoryPathRelativeToPluginFolder( __DIR__ ) . '/elements',
        'Polyclip',
        'element',
        'Polyclip',
        false
    );
}, 9 );

/**
 * Register shortcode.
 */
add_shortcode( 'polyclip', function ( $atts ) {
    // Enqueue assets immediately — works with page builders (Breakdance, Elementor, etc.).
    wp_enqueue_style( 'polyclip' );
    wp_enqueue_script( 'polyclip' );

    $atts = shortcode_atts( [
        'image'          => '',
        'images'         => '',
        'desktop'        => '',
        'tablet'         => '',
        'mobile'         => '',
        'preset'         => 'default',
        'animate'        => 'true',
        'duration'       => '8',
        'vertices'       => '',
        'aspect'         => '1800:780',
        'aspect_desktop' => '',
        'aspect_tablet'  => '',
        'aspect_mobile'  => '',
        'image_position' => 'center',
        'alt'            => '',
        'safe_zone'      => '0',
    ], $atts, 'polyclip' );

    $renderer = new Polyclip_Renderer( $atts );
    return $renderer->render();
} );

/**
 * Register assets early. Enqueued on demand when shortcode runs.
 */
add_action( 'wp_enqueue_scripts', function () {
    wp_register_style(
        'polyclip',
        POLYCLIP_URL . 'assets/css/polyclip.css',
        [],
        POLYCLIP_VERSION
    );
    wp_register_script(
        'polyclip',
        POLYCLIP_URL . 'assets/js/polyclip-animation.js',
        [],
        POLYCLIP_VERSION,
        true
    );
} );
