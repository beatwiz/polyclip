<?php
/**
 * SSR handler for Polyclip Breakdance element.
 *
 * Converts gallery repeater data (wpmedia objects) into comma-separated
 * attachment IDs and delegates rendering to Polyclip_Renderer.
 *
 * @var array $propertiesData
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$images    = $propertiesData['content']['images']    ?? [];
$animation = $propertiesData['content']['animation'] ?? [];
$aspect    = $propertiesData['content']['aspect']    ?? [];

/**
 * Build "W:H" string from separate width/height fields.
 */
if ( ! function_exists( 'polyclip_ssr_build_aspect' ) ) :
function polyclip_ssr_build_aspect( $w, $h, $default = '' ) {
    $w = trim( (string) ( $w ?? '' ) );
    $h = trim( (string) ( $h ?? '' ) );
    if ( $w !== '' && $h !== '' ) {
        return $w . ':' . $h;
    }
    return $default;
}

/**
 * Extract comma-separated attachment IDs from a gallery repeater array.
 */
function polyclip_ssr_extract_ids( $repeater ) {
    if ( empty( $repeater ) || ! is_array( $repeater ) ) {
        return '';
    }
    $ids = [];
    foreach ( $repeater as $item ) {
        $id = $item['image']['id'] ?? 0;
        if ( $id ) {
            $ids[] = (int) $id;
        }
    }
    return implode( ',', $ids );
}
endif;

$atts = [
    'images'         => polyclip_ssr_extract_ids( $images['universal'] ?? [] ),
    'desktop'        => polyclip_ssr_extract_ids( $images['desktop'] ?? [] ),
    'tablet'         => polyclip_ssr_extract_ids( $images['tablet'] ?? [] ),
    'mobile'         => polyclip_ssr_extract_ids( $images['mobile'] ?? [] ),
    'preset'         => $animation['preset'] ?? 'default',
    'animate'        => ! empty( $animation['animate'] ) ? 'true' : 'false',
    'duration'       => $animation['duration'] ?? '8',
    'vertices'       => $animation['vertices'] ?? '',
    'safe_zone'      => $animation['safe_zone'] ?? '0',
    'aspect'         => polyclip_ssr_build_aspect( $aspect['width'] ?? '', $aspect['height'] ?? '', '1800:300' ),
    'aspect_desktop' => polyclip_ssr_build_aspect( $aspect['desktop_width'] ?? '', $aspect['desktop_height'] ?? '' ),
    'aspect_tablet'  => polyclip_ssr_build_aspect( $aspect['tablet_width'] ?? '', $aspect['tablet_height'] ?? '' ),
    'aspect_mobile'  => polyclip_ssr_build_aspect( $aspect['mobile_width'] ?? '', $aspect['mobile_height'] ?? '' ),
    'image_position' => $images['image_position'] ?? 'center',
    'alt'            => $images['alt'] ?? '',
];

// Ensure the renderer and presets are loaded.
if ( ! function_exists( 'polyclip_get_presets' ) ) {
    require_once POLYCLIP_DIR . 'includes/presets.php';
}
if ( ! class_exists( 'Polyclip_Renderer' ) ) {
    require_once POLYCLIP_DIR . 'includes/class-polyclip-renderer.php';
}

$renderer = new Polyclip_Renderer( $atts );
echo $renderer->render();
