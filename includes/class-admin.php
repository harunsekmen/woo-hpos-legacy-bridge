<?php
declare(strict_types=1);

namespace WooHposLegacyBridge;

defined('ABSPATH') || exit;

final class Admin {
    public function register(): void {
        add_action('admin_menu', [$this, 'add_menu_page']);
        add_action('admin_post_whlb_clear_log', [$this, 'handle_clear_log']);
    }

    public function add_menu_page(): void {
        add_submenu_page(
            'woocommerce',
            __('HPOS Legacy Bridge', 'woo-hpos-legacy-bridge'),
            __('HPOS Bridge', 'woo-hpos-legacy-bridge'),
            'manage_woocommerce',
            'whlb-dashboard',
            [$this, 'render_dashboard']
        );
    }

    public function handle_clear_log(): void {
        if (!current_user_can('manage_woocommerce')) {
            wp_die(__('Permission denied.', 'woo-hpos-legacy-bridge'));
        }
        check_admin_referer('whlb_clear_action', 'whlb_nonce');
        Telemetry::clear();
        wp_safe_redirect(add_query_arg(['page' => 'whlb-dashboard', 'cleared' => '1'], admin_url('admin.php')));
        exit;
    }

    public function render_dashboard(): void {
        if (!current_user_can('manage_woocommerce')) {
            return;
        }

        $hpos_active = Interceptor::is_hpos_active();
        $logs        = Telemetry::get_recent();

        require_once Plugin::get_plugin_dir() . 'templates/admin-dashboard.php';
    }
}

