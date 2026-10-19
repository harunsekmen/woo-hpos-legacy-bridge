<?php
declare(strict_types=1);

namespace WooHposLegacyBridge;

defined('ABSPATH') || exit;

/**
 * @var bool $hpos_active
 * @var array $logs
 */
?>
<div class="wrap whlb-admin-wrap">
    <h1><?php esc_html_e('WooCommerce HPOS & Legacy Postmeta Compatibility Bridge', 'woo-hpos-legacy-bridge'); ?></h1>
    <p class="description">
        <?php esc_html_e('Transparent middleware routing legacy get_post_meta() and update_post_meta() calls seamlessly into High-Performance Order Storage (wp_wc_orders).', 'woo-hpos-legacy-bridge'); ?>
    </p>

    <div class="whlb-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-top: 20px;">
        <div class="whlb-main-col">
            <div class="postbox" style="padding: 24px;">
                <h2 style="margin-top: 0; font-size: 16px;"><?php esc_html_e('HPOS Compatibility Status', 'woo-hpos-legacy-bridge'); ?></h2>

                <div style="display: flex; gap: 16px; margin-bottom: 24px;">
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-left: 4px solid #16a34a; padding: 16px 20px; border-radius: 6px; flex: 1;">
                        <span style="font-size: 18px; font-weight: 700; color: #16a34a; display: block;">
                            <?php echo $hpos_active ? esc_html__('HPOS Engine: ACTIVE', 'woo-hpos-legacy-bridge') : esc_html__('HPOS Engine: READY', 'woo-hpos-legacy-bridge'); ?>
                        </span>
                        <span style="font-size: 12px; color: #4b5563;">
                            <?php esc_html_e('Orders stored in high-performance custom tables (wp_wc_orders). Bridge is actively shielding third-party plugins.', 'woo-hpos-legacy-bridge'); ?>
                        </span>
                    </div>

                    <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-left: 4px solid #2563eb; padding: 16px 20px; border-radius: 6px; flex: 1;">
                        <span style="font-size: 18px; font-weight: 700; color: #2563eb; display: block;">
                            <?php esc_html_e('Zero Broken Plugins', 'woo-hpos-legacy-bridge'); ?>
                        </span>
                        <span style="font-size: 12px; color: #4b5563;">
                            <?php esc_html_e('Legacy get_post_meta / update_post_meta calls are rerouted with zero database data loss.', 'woo-hpos-legacy-bridge'); ?>
                        </span>
                    </div>
                </div>

                <h3 style="font-size: 14px;"><?php esc_html_e('Live Intercepted Legacy Calls (Shield Telemetry)', 'woo-hpos-legacy-bridge'); ?></h3>
                <?php if (empty($logs)): ?>
                    <p style="color: #64748b; font-style: italic;"><?php esc_html_e('No legacy postmeta calls detected yet. When an outdated plugin queries an order via get_post_meta(), it will be logged here.', 'woo-hpos-legacy-bridge'); ?></p>
                <?php else: ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('Time', 'woo-hpos-legacy-bridge'); ?></th>
                                <th><?php esc_html_e('Action', 'woo-hpos-legacy-bridge'); ?></th>
                                <th><?php esc_html_e('Order ID', 'woo-hpos-legacy-bridge'); ?></th>
                                <th><?php esc_html_e('Meta Key', 'woo-hpos-legacy-bridge'); ?></th>
                                <th><?php esc_html_e('Legacy Caller File', 'woo-hpos-legacy-bridge'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><?php echo esc_html($log['time'] ?? '-'); ?></td>
                                    <td>
                                        <span class="badge" style="padding: 2px 6px; border-radius: 4px; font-weight: 700; font-size: 11px; background: <?php echo ($log['action'] ?? '') === 'write' ? '#fef3c7; color: #92400e;' : '#e0f2fe; color: #0369a1;'; ?>">
                                            <?php echo esc_html(strtoupper($log['action'] ?? 'read')); ?>
                                        </span>
                                    </td>
                                    <td><strong>#<?php echo esc_html((string) ($log['order_id'] ?? 0)); ?></strong></td>
                                    <td><code><?php echo esc_html($log['meta_key'] ?? ''); ?></code></td>
                                    <td><span style="color: #64748b; font-size: 12px;"><?php echo esc_html($log['caller'] ?? 'external'); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <div class="whlb-side-col">
            <div class="postbox" style="padding: 24px;">
                <h2 style="margin-top: 0; font-size: 16px;"><?php esc_html_e('Bridge Diagnostics', 'woo-hpos-legacy-bridge'); ?></h2>
                <p style="font-size: 13px; color: #475569; line-height: 1.5;">
                    <?php esc_html_e('With WooCommerce HPOS enabled, older extensions (shipping labels, invoice generators, ERP syncs) that call get_post_meta($order_id) fail silently because orders no longer reside in wp_posts.', 'woo-hpos-legacy-bridge'); ?>
                </p>
                <p style="font-size: 13px; color: #475569; line-height: 1.5;">
                    <?php esc_html_e('This bridge intercepts the WordPress get_post_metadata filter and seamlessly routes reads and writes directly to $order->get_meta() and $order->update_meta_data().', 'woo-hpos-legacy-bridge'); ?>
                </p>

                <hr style="margin: 20px 0;">

                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <input type="hidden" name="action" value="whlb_clear_log">
                    <?php wp_nonce_field('whlb_clear_action', 'whlb_nonce'); ?>
                    <button type="submit" class="button button-secondary">
                        <?php esc_html_e('Clear Telemetry Log', 'woo-hpos-legacy-bridge'); ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

