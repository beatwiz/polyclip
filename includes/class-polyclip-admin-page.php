<?php
/**
 * Admin page: Shortcode Generator.
 *
 * Uses native WordPress meta boxes (add_meta_box / do_meta_boxes)
 * so that postbox styling, toggle and drag-to-reorder work out of the box.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Polyclip_Admin_Page {

    const NONCE_ACTION = 'polyclip_admin';
    const NONCE_NAME   = 'polyclip_nonce';
    const SCREEN_ID    = 'toplevel_page_polyclip-generator';

    public static function init(): void {
        add_action( 'admin_menu', [ __CLASS__, 'register_menus' ] );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
    }

    public static function register_menus(): void {
        $hook = add_menu_page(
            __( 'Polyclip', 'polyclip' ),
            __( 'Polyclip', 'polyclip' ),
            'manage_options',
            'polyclip-generator',
            [ __CLASS__, 'render_page' ],
            'dashicons-format-image',
            49
        );

        // Register meta boxes on the page's load hook.
        add_action( 'load-' . $hook, [ __CLASS__, 'register_meta_boxes' ] );

        add_submenu_page(
            'polyclip-generator',
            __( 'Shortcode Generator', 'polyclip' ),
            __( 'Shortcode Generator', 'polyclip' ),
            'manage_options',
            'polyclip-generator',
            [ __CLASS__, 'render_page' ]
        );
    }

    /**
     * Register meta boxes for this admin page.
     */
    public static function register_meta_boxes(): void {
        add_meta_box(
            'polyclip-images',
            __( 'Images', 'polyclip' ),
            [ __CLASS__, 'render_images_metabox' ],
            self::SCREEN_ID,
            'normal'
        );

        add_meta_box(
            'polyclip-settings',
            __( 'Settings', 'polyclip' ),
            [ __CLASS__, 'render_settings_metabox' ],
            self::SCREEN_ID,
            'normal'
        );

        add_meta_box(
            'polyclip-preview',
            __( 'Preview', 'polyclip' ),
            [ __CLASS__, 'render_preview_metabox' ],
            self::SCREEN_ID,
            'normal'
        );

        add_meta_box(
            'polyclip-shortcode',
            __( 'Generated Shortcode', 'polyclip' ),
            [ __CLASS__, 'render_shortcode_metabox' ],
            self::SCREEN_ID,
            'normal'
        );
    }

    public static function enqueue_assets( string $hook ): void {
        if ( $hook !== self::SCREEN_ID ) {
            return;
        }

        // Native WP postbox behaviour (toggle, drag).
        wp_enqueue_script( 'postbox' );
        wp_enqueue_media();

        wp_enqueue_style(
            'polyclip-admin',
            POLYCLIP_URL . 'assets/css/polyclip-admin.css',
            [],
            POLYCLIP_VERSION
        );

        wp_enqueue_script(
            'polyclip-admin',
            POLYCLIP_URL . 'assets/js/polyclip-admin.js',
            [ 'jquery', 'jquery-ui-sortable', 'postbox' ],
            POLYCLIP_VERSION,
            true
        );

        $presets = polyclip_get_presets();

        wp_localize_script( 'polyclip-admin', 'polyclipAdmin', [
            'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
            'nonce'       => wp_create_nonce( self::NONCE_ACTION ),
            'screenId'    => self::SCREEN_ID,
            'presets'     => array_keys( $presets ),
            'presetsData' => $presets,
            'i18n'     => [
                'selectImages'  => __( 'Select Images', 'polyclip' ),
                'useSelected'   => __( 'Use Selected Images', 'polyclip' ),
                'copied'        => __( 'Copied!', 'polyclip' ),
            ],
        ] );
    }

    /* -------------------------------------------------------
     * Page render (uses do_meta_boxes)
     * ----------------------------------------------------- */

    public static function render_page(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        ?>
        <div class="wrap polyclip-admin">
            <h1><?php esc_html_e( 'Polyclip — Shortcode Generator', 'polyclip' ); ?></h1>

            <form id="polyclip-form">
                <?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
                <?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>

                <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-1">
                        <div id="post-body-content">
                            <?php do_meta_boxes( self::SCREEN_ID, 'normal', null ); ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php
    }

    /* -------------------------------------------------------
     * Meta box callbacks
     * ----------------------------------------------------- */

    public static function render_images_metabox(): void {
        $image_groups = [
            'images'  => __( 'Universal Images', 'polyclip' ),
            'desktop' => __( 'Desktop Images (>1024px)', 'polyclip' ),
            'tablet'  => __( 'Tablet Images (768–1024px)', 'polyclip' ),
            'mobile'  => __( 'Mobile Images (<768px)', 'polyclip' ),
        ];
        foreach ( $image_groups as $group_key => $group_label ) :
        ?>
            <div class="polyclip-image-group" data-group="<?php echo esc_attr( $group_key ); ?>">
                <h3>
                    <?php echo esc_html( $group_label ); ?>
                    <button type="button" class="button button-small polyclip-add-image">
                        <?php esc_html_e( '+ Add Image', 'polyclip' ); ?>
                    </button>
                </h3>
                <div class="polyclip-image-list"></div>
            </div>
        <?php endforeach;
    }

    public static function render_settings_metabox(): void {
        $presets = polyclip_get_presets();
        ?>
        <table class="form-table">
            <tr>
                <th><label for="polyclip-preset"><?php esc_html_e( 'Preset', 'polyclip' ); ?></label></th>
                <td>
                    <select id="polyclip-preset">
                        <?php foreach ( array_keys( $presets ) as $name ) : ?>
                            <option value="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="polyclip-animate"><?php esc_html_e( 'Animate', 'polyclip' ); ?></label></th>
                <td><input type="checkbox" id="polyclip-animate" checked></td>
            </tr>
            <tr>
                <th><label for="polyclip-duration"><?php esc_html_e( 'Duration (s)', 'polyclip' ); ?></label></th>
                <td><input type="number" id="polyclip-duration" value="8" min="1" step="0.5" class="small-text"></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Aspect Ratio', 'polyclip' ); ?></th>
                <td>
                    <label><?php esc_html_e( 'Default', 'polyclip' ); ?>
                        <input type="text" id="polyclip-aspect" value="1800:780" class="polyclip-aspect-input" placeholder="1800:780">
                    </label>
                    <label><?php esc_html_e( 'Desktop', 'polyclip' ); ?>
                        <input type="text" id="polyclip-aspect-desktop" value="" class="polyclip-aspect-input" placeholder="—">
                    </label>
                    <label><?php esc_html_e( 'Tablet', 'polyclip' ); ?>
                        <input type="text" id="polyclip-aspect-tablet" value="" class="polyclip-aspect-input" placeholder="—">
                    </label>
                    <label><?php esc_html_e( 'Mobile', 'polyclip' ); ?>
                        <input type="text" id="polyclip-aspect-mobile" value="" class="polyclip-aspect-input" placeholder="—">
                    </label>
                    <p class="description"><?php esc_html_e( 'Leave blank to use default. Format: width:height', 'polyclip' ); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="polyclip-position"><?php esc_html_e( 'Image Position', 'polyclip' ); ?></label></th>
                <td>
                    <select id="polyclip-position">
                        <option value="center">center</option>
                        <option value="top">top</option>
                        <option value="bottom">bottom</option>
                        <option value="left">left</option>
                        <option value="right">right</option>
                        <option value="center top">center top</option>
                        <option value="center bottom">center bottom</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="polyclip-alt"><?php esc_html_e( 'Alt Text', 'polyclip' ); ?></label></th>
                <td><input type="text" id="polyclip-alt" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="polyclip-safe-zone"><?php esc_html_e( 'Safe Zone (%)', 'polyclip' ); ?></label></th>
                <td><input type="number" id="polyclip-safe-zone" value="0" min="0" max="70" step="1" class="small-text"></td>
            </tr>
            <tr>
                <th><label for="polyclip-vertices"><?php esc_html_e( 'Custom Vertices', 'polyclip' ); ?></label></th>
                <td>
                    <textarea id="polyclip-vertices" class="large-text" rows="2" placeholder="<?php esc_attr_e( 'Overrides preset. E.g.: 15%,0% 85%,0% 100%,100% 0%,100%', 'polyclip' ); ?>"></textarea>
                </td>
            </tr>
        </table>
        <?php
    }

    public static function render_preview_metabox(): void {
        ?>
        <div class="polyclip-preview-tabs">
            <button type="button" class="button polyclip-preview-tab active" data-device="auto"><?php esc_html_e( 'Auto', 'polyclip' ); ?></button>
            <button type="button" class="button polyclip-preview-tab" data-device="desktop"><?php esc_html_e( 'Desktop', 'polyclip' ); ?></button>
            <button type="button" class="button polyclip-preview-tab" data-device="tablet"><?php esc_html_e( 'Tablet', 'polyclip' ); ?></button>
            <button type="button" class="button polyclip-preview-tab" data-device="mobile"><?php esc_html_e( 'Mobile', 'polyclip' ); ?></button>
        </div>
        <div id="polyclip-preview-wrap" style="position:relative;width:100%;overflow:hidden;background:#f0f0f1;border-radius:4px;">
            <div id="polyclip-preview-fallback" style="padding-bottom:43.33%"></div>
            <div id="polyclip-preview-clip" style="position:absolute;inset:0;will-change:clip-path;">
                <img id="polyclip-preview-img" src="" alt="" style="display:block;width:100%;height:100%;object-fit:cover;">
            </div>
        </div>
        <?php
    }

    public static function render_shortcode_metabox(): void {
        ?>
        <div class="polyclip-shortcode-preview">
            <code id="polyclip-shortcode-output"></code>
        </div>
        <button type="button" class="button" id="polyclip-copy-btn"><?php esc_html_e( 'Copy to Clipboard', 'polyclip' ); ?></button>
        <?php
    }
}
