<?php
require_once __DIR__ . '/bootstrap.php';
if (!function_exists('get_option')) { function get_option($k, $d = []) { return $d; } }
require_once dirname(__DIR__) . '/includes/class-telemetry.php';

echo "Testing WooHposLegacyBridge (Telemetry)...
";

$recent = \WooHposLegacyBridge\Telemetry::get_recent();
assert(is_array($recent), "Recent log must be array");

echo "PASS: woo-hpos-legacy-bridge verified.
";
