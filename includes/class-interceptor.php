<?php
declare(strict_types=1);

namespace WooHposLegacyBridge;

use WC_Order;
use Automattic\WooCommerce\Utilities\OrderUtil;

defined('ABSPATH') || exit;

final class Interceptor {
    private static bool $reentrancy_guard = false;

    public function register(): void {
        // Intercept read queries directed to postmeta
        add_filter('get_post_metadata', [$this, 'intercept_get_post_meta'], 10, 4);

        // Intercept write queries directed to postmeta
        add_filter('update_post_metadata', [$this, 'intercept_update_post_meta'], 10, 5);
        add_filter('add_post_metadata', [$this, 'intercept_add_post_meta'], 10, 5);
    }

    public static function is_hpos_active(): bool {
        if (class_exists(OrderUtil::class) && method_exists(OrderUtil::class, 'custom_orders_table_usage_is_enabled')) {
            return OrderUtil::custom_orders_table_usage_is_enabled();
        }
        return false;
    }

    public static function is_order_id(int $object_id): bool {
        if ($object_id <= 0) {
            return false;
        }

        if (class_exists(OrderUtil::class) && method_exists(OrderUtil::class, 'is_order')) {
            return OrderUtil::is_order($object_id, wc_get_order_types());
        }

        return get_post_type($object_id) === 'shop_order';
    }

    public function intercept_get_post_meta(mixed $value, int $object_id, string $meta_key, bool $single): mixed {
        if (self::$reentrancy_guard || !self::is_hpos_active()) {
            return $value;
        }

        // Only intercept order objects
        if (!self::is_order_id($object_id)) {
            return $value;
        }

        self::$reentrancy_guard = true;
        try {
            $order = wc_get_order($object_id);
            if (!$order instanceof WC_Order) {
                return $value;
            }

            // Record telemetry for developer inspection
            Telemetry::record_intercept('read', $object_id, $meta_key);

            $meta_val = $order->get_meta($meta_key, $single);
            if ($meta_val !== '' && $meta_val !== false) {
                return $single ? $meta_val : [$meta_val];
            }
        } finally {
            self::$reentrancy_guard = false;
        }

        return $value;
    }

    public function intercept_update_post_meta(mixed $check, int $object_id, string $meta_key, mixed $meta_value, mixed $prev_value): mixed {
        if (self::$reentrancy_guard || !self::is_hpos_active()) {
            return $check;
        }

        if (!self::is_order_id($object_id)) {
            return $check;
        }

        self::$reentrancy_guard = true;
        try {
            $order = wc_get_order($object_id);
            if (!$order instanceof WC_Order) {
                return $check;
            }

            // Record telemetry for developer inspection
            Telemetry::record_intercept('write', $object_id, $meta_key);

            $order->update_meta_data($meta_key, $meta_value);
            $order->save();

            // Return non-null boolean to short-circuit WordPress core update_metadata query
            return true;
        } finally {
            self::$reentrancy_guard = false;
        }
    }

    public function intercept_add_post_meta(mixed $check, int $object_id, string $meta_key, mixed $meta_value, bool $unique): mixed {
        if (self::$reentrancy_guard || !self::is_hpos_active()) {
            return $check;
        }

        if (!self::is_order_id($object_id)) {
            return $check;
        }

        self::$reentrancy_guard = true;
        try {
            $order = wc_get_order($object_id);
            if (!$order instanceof WC_Order) {
                return $check;
            }

            Telemetry::record_intercept('add', $object_id, $meta_key);

            $order->add_meta_data($meta_key, $meta_value, $unique);
            $order->save();

            return true;
        } finally {
            self::$reentrancy_guard = false;
        }
    }
}

