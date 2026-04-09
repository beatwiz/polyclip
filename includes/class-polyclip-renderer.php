<?php
/**
 * Renders the polygon clip-path HTML.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Polyclip_Renderer {

    /** @var int Instance counter for unique IDs. */
    private static int $instance = 0;

    private array $atts;
    private string $id;

    public function __construct( array $atts ) {
        $this->atts = $atts;
        self::$instance++;
        $this->id = 'polyclip-' . self::$instance;
    }

    public function render(): string {
        $resolved = $this->resolve_images();

        if ( empty( $resolved['fallback'] ) ) {
            return '<!-- polyclip: image attribute is required -->';
        }

        $animate        = filter_var( $this->atts['animate'], FILTER_VALIDATE_BOOLEAN );
        $duration       = max( 1, (float) $this->atts['duration'] );
        $image_position = $this->sanitize_position( $this->atts['image_position'] );
        $alt            = esc_attr( $this->atts['alt'] );
        $aspect         = $this->parse_aspect( $this->atts['aspect'] );

        // Resolve vertices: custom > preset > default.
        $preset_data = $this->resolve_preset();
        $points      = $preset_data['points'];
        $keyframes   = $preset_data['keyframes'];

        // Apply safe zone clamping to all keyframes and base points.
        $keyframes = $this->apply_safe_zone( $keyframes );
        $points    = $this->apply_safe_zone( [ $points ] )[0];

        // Build the static clip-path.
        $clip_path = 'polygon(' . implode( ', ', $points ) . ')';

        // Build inline style for the container.
        $aspect_css = $aspect['w'] . ' / ' . $aspect['h'];
        $padding_fallback = round( ( $aspect['h'] / $aspect['w'] ) * 100, 4 );

        $html = '<div class="polyclip" id="' . esc_attr( $this->id ) . '"'
              . ' style="aspect-ratio:' . $aspect_css . '">';

        // Padding-bottom fallback for older browsers.
        $html .= '<div class="polyclip__fallback" style="padding-bottom:' . $padding_fallback . '%"></div>';

        // Clip container.
        $clip_style = 'clip-path:' . $clip_path . ';';
        if ( $animate ) {
            $clip_style .= '--polyclip-duration:' . $duration . 's;';
        }

        $anim_class = $animate ? ' polyclip__clip--animated' : '';
        $html .= '<div class="polyclip__clip' . $anim_class . '"'
               . ' style="' . esc_attr( $clip_style ) . '">';

        $html .= $this->render_image_element( $resolved, $alt, $image_position );

        $html .= '</div>'; // __clip
        $html .= '</div>'; // .polyclip

        // If animated, inject keyframes as inline <style> (unique per instance, supports custom vertices).
        if ( $animate && ! empty( $keyframes ) ) {
            $html .= $this->render_keyframes( $keyframes );
        }

        // Responsive aspect ratios via media queries.
        $html .= $this->render_responsive_aspect();

        return $html;
    }

    /**
     * Resolve images for all breakpoints.
     *
     * @return array{fallback: string, desktop: string, tablet: string, mobile: string, use_picture: bool}
     */
    private function resolve_images(): array {
        $result = [
            'fallback' => '',
            'desktop'  => '',
            'tablet'   => '',
            'mobile'   => '',
            'use_picture' => false,
        ];

        // Parse device-specific lists.
        $desktop_list = $this->parse_image_list( $this->atts['desktop'] ?? '' );
        $tablet_list  = $this->parse_image_list( $this->atts['tablet'] ?? '' );
        $mobile_list  = $this->parse_image_list( $this->atts['mobile'] ?? '' );

        // Parse universal lists.
        $images_list = $this->parse_image_list( $this->atts['images'] ?? '' );
        $single      = trim( $this->atts['image'] ?? '' );

        // Build universal fallback: images > image.
        $universal = ! empty( $images_list ) ? $images_list : ( $single ? [ $single ] : [] );

        // Resolve universal fallback (pick one randomly).
        if ( ! empty( $universal ) ) {
            $result['fallback'] = $this->pick_random( $universal );
        }

        // If any device-specific images exist, enable <picture>.
        if ( ! empty( $desktop_list ) || ! empty( $tablet_list ) || ! empty( $mobile_list ) ) {
            $result['use_picture'] = true;
            $result['desktop'] = ! empty( $desktop_list ) ? $this->pick_random( $desktop_list ) : '';
            $result['tablet']  = ! empty( $tablet_list )  ? $this->pick_random( $tablet_list )  : '';
            $result['mobile']  = ! empty( $mobile_list )  ? $this->pick_random( $mobile_list )  : '';

            // If no universal fallback, use desktop > tablet > mobile.
            if ( empty( $result['fallback'] ) ) {
                $result['fallback'] = $result['desktop'] ?: $result['tablet'] ?: $result['mobile'];
            }
        }

        return $result;
    }

    /**
     * Parse a comma-separated list of image URLs or attachment IDs.
     *
     * @return string[] Array of resolved image URLs.
     */
    private function parse_image_list( string $input ): array {
        $input = trim( $input );
        if ( $input === '' ) {
            return [];
        }

        $items  = array_map( 'trim', explode( ',', $input ) );
        $images = [];

        foreach ( $items as $item ) {
            if ( $item === '' ) {
                continue;
            }

            // Numeric value = attachment ID.
            if ( is_numeric( $item ) ) {
                $url = wp_get_attachment_image_url( (int) $item, 'full' );
                if ( $url ) {
                    $images[] = $url;
                }
            } else {
                $images[] = esc_url( $item );
            }
        }

        return $images;
    }

    /**
     * Pick a random image from a list.
     */
    private function pick_random( array $images ): string {
        if ( count( $images ) === 1 ) {
            return $images[0];
        }
        return $images[ array_rand( $images ) ];
    }

    /**
     * Render <img> or <picture> element based on resolved images.
     */
    private function render_image_element( array $resolved, string $alt, string $image_position ): string {
        $img_attrs = 'alt="' . $alt . '" loading="lazy" style="object-position:' . $image_position . '"';

        if ( ! $resolved['use_picture'] ) {
            return '<img src="' . esc_url( $resolved['fallback'] ) . '" ' . $img_attrs . '>';
        }

        $html = '<picture>';

        // Mobile first: smallest breakpoint first.
        if ( ! empty( $resolved['mobile'] ) ) {
            $html .= '<source media="(max-width: 767px)" srcset="' . esc_url( $resolved['mobile'] ) . '">';
        }

        if ( ! empty( $resolved['tablet'] ) ) {
            $html .= '<source media="(min-width: 768px) and (max-width: 1024px)" srcset="' . esc_url( $resolved['tablet'] ) . '">';
        }

        if ( ! empty( $resolved['desktop'] ) ) {
            $html .= '<source media="(min-width: 1025px)" srcset="' . esc_url( $resolved['desktop'] ) . '">';
        }

        // Fallback <img> (required by <picture>).
        $html .= '<img src="' . esc_url( $resolved['fallback'] ) . '" ' . $img_attrs . '>';
        $html .= '</picture>';

        return $html;
    }

    /**
     * Resolve preset or custom vertices.
     */
    private function resolve_preset(): array {
        $presets = polyclip_get_presets();

        // Custom vertices override everything.
        if ( ! empty( $this->atts['vertices'] ) ) {
            $points = $this->parse_custom_vertices( $this->atts['vertices'] );
            if ( ! empty( $points ) ) {
                // For custom vertices, generate subtle keyframes by nudging points.
                return [
                    'points'    => $points,
                    'keyframes' => $this->generate_subtle_keyframes( $points ),
                ];
            }
        }

        $preset_name = sanitize_key( $this->atts['preset'] );
        if ( isset( $presets[ $preset_name ] ) ) {
            return $presets[ $preset_name ];
        }

        return $presets['default'];
    }

    /**
     * Parse "x1,y1 x2,y2 x3,y3 ..." into ["X% Y%", ...].
     * Accepts absolute coords (viewBox) or percentage coords (with %).
     */
    private function parse_custom_vertices( string $input ): array {
        $input  = trim( $input );
        $pairs  = preg_split( '/\s+/', $input );
        $points = [];

        $aspect = $this->parse_aspect( $this->atts['aspect'] );

        foreach ( $pairs as $pair ) {
            $coords = explode( ',', $pair );
            if ( count( $coords ) !== 2 ) {
                continue;
            }

            $x = trim( $coords[0] );
            $y = trim( $coords[1] );

            // If already percentage, use as-is.
            if ( str_contains( $x, '%' ) && str_contains( $y, '%' ) ) {
                $points[] = $x . ' ' . $y;
            } else {
                // Convert absolute to percentage.
                $px = round( ( (float) $x / $aspect['w'] ) * 100, 2 );
                $py = round( ( (float) $y / $aspect['h'] ) * 100, 2 );
                $points[] = $px . '% ' . $py . '%';
            }
        }

        return $points;
    }

    /**
     * Clamp a vertex radially: if it's closer to the center than the
     * safe zone radius, push it outward along the same angle.
     */
    private function clamp_to_safe_zone( float $x, float $y, float $sz ): array {
        if ( $sz <= 0 ) {
            return [ $x, $y ];
        }

        $cx = 50.0;
        $cy = 50.0;
        $dx = $x - $cx;
        $dy = $y - $cy;
        $dist = sqrt( $dx * $dx + $dy * $dy );

        if ( $dist >= $sz ) {
            return [ $x, $y ];
        }

        if ( $dist < 0.01 ) {
            $dx = 1.0;
            $dy = 0.0;
            $dist = 1.0;
        }

        $scale = $sz / $dist;
        $nx = $cx + $dx * $scale;
        $ny = $cy + $dy * $scale;

        if ( $nx < 0 || $nx > 100 || $ny < 0 || $ny > 100 ) {
            $max_scale = 1000.0;
            if ( $dx > 0 ) {
                $max_scale = min( $max_scale, ( 100 - $cx ) / $dx );
            } elseif ( $dx < 0 ) {
                $max_scale = min( $max_scale, -$cx / $dx );
            }
            if ( $dy > 0 ) {
                $max_scale = min( $max_scale, ( 100 - $cy ) / $dy );
            } elseif ( $dy < 0 ) {
                $max_scale = min( $max_scale, -$cy / $dy );
            }

            $nx = $cx + $dx * $max_scale;
            $ny = $cy + $dy * $max_scale;
        }

        return [ round( max( 0, min( 100, $nx ) ), 2 ), round( max( 0, min( 100, $ny ) ), 2 ) ];
    }

    /**
     * Apply safe zone to all keyframes.
     */
    private function apply_safe_zone( array $keyframes ): array {
        $sz = max( 0, min( 70, (float) ( $this->atts['safe_zone'] ?? 0 ) ) );
        if ( $sz <= 0 ) {
            return $keyframes;
        }

        $dampen = 1.0 - ( $sz / 70.0 ) * 0.8;

        $base = $keyframes[0] ?? [];
        $result = [];

        foreach ( $keyframes as $fi => $frame ) {
            $processed = [];
            foreach ( $frame as $pi => $point ) {
                if ( ! preg_match( '/^([\d.]+)%\s+([\d.]+)%$/', $point, $m ) ) {
                    $processed[] = $point;
                    continue;
                }

                $x = (float) $m[1];
                $y = (float) $m[2];

                if ( $fi > 0 && isset( $base[ $pi ] ) ) {
                    if ( preg_match( '/^([\d.]+)%\s+([\d.]+)%$/', $base[ $pi ], $bm ) ) {
                        $bx = (float) $bm[1];
                        $by = (float) $bm[2];
                        $x = $bx + ( $x - $bx ) * $dampen;
                        $y = $by + ( $y - $by ) * $dampen;
                    }
                }

                [ $x, $y ] = $this->clamp_to_safe_zone( $x, $y, $sz );
                $processed[] = round( $x, 2 ) . '% ' . round( $y, 2 ) . '%';
            }
            $result[] = $processed;
        }

        return $result;
    }

    /**
     * Generate subtle keyframes by nudging each point +/-2-3%.
     */
    private function generate_subtle_keyframes( array $base_points ): array {
        $keyframes = [ $base_points ];

        $nudges = [
            [ -2, 3, 1, -2, 2, -1, -3, 1, 2, -2, 1, -3 ],
            [ 1, -2, -3, 2, -1, 3, 2, -3, -1, 3, -2, 1 ],
            [ -1, 1, 2, -3, 3, -2, -2, 2, -3, 1, 3, -1 ],
        ];

        foreach ( $nudges as $nudge_set ) {
            $kf = [];
            $i  = 0;
            foreach ( $base_points as $point ) {
                if ( preg_match( '/^([\d.]+)%\s+([\d.]+)%$/', $point, $m ) ) {
                    $nx = max( 0, min( 100, (float) $m[1] + ( $nudge_set[ $i % count( $nudge_set ) ] ?? 0 ) ) );
                    $ny = max( 0, min( 100, (float) $m[2] + ( $nudge_set[ ( $i + 1 ) % count( $nudge_set ) ] ?? 0 ) ) );
                    $kf[] = round( $nx, 2 ) . '% ' . round( $ny, 2 ) . '%';
                } else {
                    $kf[] = $point;
                }
                $i += 2;
            }
            $keyframes[] = $kf;
        }

        return $keyframes;
    }

    /**
     * Render CSS @keyframes for this instance.
     */
    private function render_keyframes( array $keyframes ): string {
        $name  = 'polyclip-morph-' . $this->id;
        $count = count( $keyframes );

        $css = '@keyframes ' . $name . '{';

        foreach ( $keyframes as $i => $points ) {
            $pct = ( $count > 1 ) ? round( ( $i / ( $count - 1 ) ) * 100, 2 ) : 0;
            $css .= $pct . '%{clip-path:polygon(' . implode( ',', $points ) . ')}';
        }

        $css .= '}';

        $css .= '#' . $this->id . ' .polyclip__clip--animated{';
        $css .= 'animation-name:' . $name;
        $css .= '}';

        return '<style>' . $css . '</style>';
    }

    /**
     * Render responsive aspect ratio media queries.
     */
    private function render_responsive_aspect(): string {
        $breakpoints = [
            'desktop' => [ 'attr' => 'aspect_desktop', 'media' => '(min-width: 1025px)' ],
            'tablet'  => [ 'attr' => 'aspect_tablet',  'media' => '(min-width: 768px) and (max-width: 1024px)' ],
            'mobile'  => [ 'attr' => 'aspect_mobile',  'media' => '(max-width: 767px)' ],
        ];

        $css = '';
        $selector = '#' . $this->id;

        foreach ( $breakpoints as $bp ) {
            $val = trim( $this->atts[ $bp['attr'] ] ?? '' );
            if ( $val === '' ) {
                continue;
            }

            $a = $this->parse_aspect( $val );
            $ratio_css     = $a['w'] . ' / ' . $a['h'];
            $padding_pct   = round( ( $a['h'] / $a['w'] ) * 100, 4 );

            $css .= '@media ' . $bp['media'] . '{';
            $css .= $selector . '{aspect-ratio:' . $ratio_css . '}';
            $css .= $selector . ' .polyclip__fallback{padding-bottom:' . $padding_pct . '%}';
            $css .= '}';
        }

        if ( $css === '' ) {
            return '';
        }

        return '<style>' . $css . '</style>';
    }

    /**
     * Parse "W:H" aspect ratio string.
     */
    private function parse_aspect( string $aspect ): array {
        $parts = explode( ':', $aspect );
        return [
            'w' => max( 1, (int) ( $parts[0] ?? 1800 ) ),
            'h' => max( 1, (int) ( $parts[1] ?? 780 ) ),
        ];
    }

    /**
     * Sanitize image object-position value.
     */
    private function sanitize_position( string $pos ): string {
        $allowed = [ 'center', 'top', 'bottom', 'left', 'right' ];
        $pos     = strtolower( trim( $pos ) );

        $parts = explode( ' ', $pos );
        foreach ( $parts as $p ) {
            if ( ! in_array( $p, $allowed, true ) && ! preg_match( '/^\d+%$/', $p ) ) {
                return 'center';
            }
        }

        return $pos;
    }
}
