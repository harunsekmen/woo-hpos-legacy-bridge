<?php
declare(strict_types=1);

namespace WooHposLegacyBridge;

defined('ABSPATH') || exit;

final class Telemetry {
    public function register(): void {}

    public const LOG_OPTION = 'whlb_intercepted_keys_log';
    public const MAX_ENTRIES = 50;

    public static function record_intercept(string $action, int $order_id, string $meta_key): void {
        if (empty($meta_key) || str_starts_with($meta_key, '_edit_lock')) {
            return;
        }

        $log = get_option(self::LOG_OPTION, []);
        if (!is_array($log)) {
            $log = [];
        }

        $entry = [
            'action'    => $action,
            'order_id'  => $order_id,
            'meta_key'  => sanitize_text_field($meta_key),
            'time'      => current_time('mysql'),
            'caller'    => self::find_caller(),
        ];

        array_unshift($log, $entry);
        if (count($log) > self::MAX_ENTRIES) {
            $log = array_slice($log, 0, self::MAX_ENTRIES);
        }

        update_option(self::LOG_OPTION, $log, false);
    }

    private static function find_caller(): string {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
        foreach ($trace as $frame) {
            $file = $frame['file'] ?? '';
            if ($file && !str_contains($file, 'woo-hpos-legacy-bridge') && !str_contains($file, 'wp-includes/meta.php')) {
                return basename(dirname($file)) . '/' . basename($file);
            }
        }
        return 'external-plugin';
    }

    public static function get_recent(): array {
        $log = get_option(self::LOG_OPTION, []);
        return is_array($log) ? $log : [];
    }

    public static function clear(): void {
        update_option(self::LOG_OPTION, [], false);
    }
}

