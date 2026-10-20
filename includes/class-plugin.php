<?php
declare(strict_types=1);

namespace WooHposLegacyBridge;

defined('ABSPATH') || exit;

final class Plugin {
    public const VERSION = '1.0.0';
    public const OPTION_KEY = 'whlb_settings';

    private static ?self $instance = null;
    private Interceptor $interceptor;
    private Telemetry $telemetry;
    private Admin $admin;

    public static function instance(): self {
        return self::$instance ??= new self();
    }

    private function __construct() {
        $this->interceptor = new Interceptor();
        $this->telemetry   = new Telemetry();
        $this->admin       = new Admin();
    }

    public function init(): void {
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', [$this, 'render_woocommerce_missing_notice']);
            return;
        }

        $this->interceptor->register();
        // Telemetry uses static methods
        $this->admin->register();
    }

    public function render_woocommerce_missing_notice(): void {
        ?>
        <div class="notice notice-error">
            <p><?php esc_html_e('WooCommerce HPOS Legacy Bridge requires WooCommerce to be installed and active.', 'woo-hpos-legacy-bridge'); ?></p>
        </div>
        <?php
    }

    public static function get_plugin_dir(): string {
        return plugin_dir_path(dirname(__FILE__));
    }

    public static function get_plugin_url(): string {
        return plugin_dir_url(dirname(__FILE__));
    }
}

