# 🔄 WooCommerce HPOS & Legacy Postmeta Compatibility Bridge

[🇬🇧 English](README.md) · [🇹🇷 Türkçe](README.tr.md)

[![WooCommerce HPOS Ready](https://img.shields.io/badge/WooCommerce-HPOS%20Ready-7f54b3.svg)](https://github.com/harunsekmen/woo-hpos-legacy-bridge)
[![License: GPL-3.0](https://img.shields.io/badge/License-GPL%203.0-blue.svg)](https://github.com/harunsekmen/woo-hpos-legacy-bridge/blob/main/LICENSE)
[![PHP: 8.1+](https://img.shields.io/badge/PHP-8.1%20to%208.3-8892be.svg)](https://github.com/harunsekmen/woo-hpos-legacy-bridge)

> A compatibility layer that allows older plugins and custom integrations to read and write order data smoothly on WooCommerce stores using High-Performance Order Storage (HPOS).

Developed and maintained by **[Harun Sekmen](https://github.com/harunsekmen)** — E-Commerce Systems Engineer & WooCommerce Developer.

[✉️ Discuss HPOS Migration](mailto:sekmenwp@gmail.com) · [💼 Upwork Profile](https://www.upwork.com/freelancers/~01a7a8b58043e6e6d9) · [🔗 Main GitHub Profile](https://github.com/harunsekmen)

---

## 📸 Visual Previews & Operational Interface

### WordPress Admin HPOS Compatibility & Shield Dashboard
Store administrators can verify that HPOS is active, inspect legacy caller files in real time, and monitor intercepted keys without modifying third-party code:

![WooCommerce HPOS Bridge Dashboard](docs/assets/admin-hpos-bridge-dashboard.png)

---

## ⚡ The Problem It Solves

Modern WooCommerce stores use dedicated high-performance order tables (**HPOS** / `wp_wc_orders`) for significantly faster order handling and cleaner data architecture compared to the legacy `wp_posts` structure.

However, many established stores rely on essential third-party plugins—such as local shipping label printers, invoice generators, custom ERP connectors, or legacy custom functions—that still attempt to access order information using older WordPress calls:
```php
// Legacy pattern still found in older plugins:
$tracking_number = get_post_meta($order_id, '_tracking_number', true);
update_post_meta($order_id, '_invoice_created', 'yes');
```

When a store switches to HPOS without legacy postmeta compatibility:
- **Missing Order Details:** Older plugins may fail to read or save customer tax IDs, tracking codes, or custom shipping notes because the order is stored in HPOS tables instead of `wp_posts`.
- **Double-Writing Overhead:** Leaving full compatibility synchronization enabled indefinitely duplicates writes across both table engines, slowing down order creation.
- **Blocked Upgrades:** Store owners hesitate to adopt the speed benefits of HPOS because they worry older mission-critical plugins will break.

**WooCommerce HPOS & Legacy Postmeta Compatibility Bridge** solves this by transparently intercepting core postmeta queries (`get_post_metadata`, `update_post_metadata`) for order objects and routing them directly to modern WooCommerce HPOS order methods.

---

## 🛠️ Key Architectural Features

- **Transparent Query Routing:** Older plugins continue to read and write custom order metadata without modifying their code.
- **Caller Diagnostics in WP Admin:** Logs which files or plugins are still triggering legacy calls so you can plan clean updates over time.
- **Recursion Guard:** Prevents infinite hook loops during complex order save cascades.
- **Enables Confident HPOS Upgrades:** Lets merchants transition to modern database tables without breaking day-to-day warehouse or shipping workflows.
- **High-Performance Order Storage (HPOS) Ready:** Specifically engineered for stores running modern WooCommerce custom order tables.

---

## 💼 Store Upgrades & Legacy Code Modernization

Planning to upgrade an established WooCommerce store to HPOS without business disruption?

- **HPOS Readiness Audits:** Inventory all active extensions, custom functions, and ERP feeds for HPOS compatibility.
- **Staging Verification:** Complete migration testing on a staging server before applying changes to production.
- **Custom Plugin Modernization:** Refactoring older custom themes and plugins to use native WooCommerce CRUD methods.
- **Zero-Downtime Deployment:** Safe transition with clean database backups and verification checklists.

📩 **Consult on your upgrade:** Reach out to **[sekmenwp@gmail.com](mailto:sekmenwp@gmail.com)** or connect via **[Upwork](https://www.upwork.com/freelancers/~01a7a8b58043e6e6d9)**.

---

## 🚀 Developer Setup & Local Testing

1. **Clone into plugins directory:**
   ```bash
   git clone https://github.com/harunsekmen/woo-hpos-legacy-bridge.git wp-content/plugins/woo-hpos-legacy-bridge
   ```

2. **Activate via WP-CLI:**
   ```bash
   wp plugin activate woo-hpos-legacy-bridge
   ```

3. **Inspect intercepted calls:** Visit **WooCommerce > HPOS Bridge** in WordPress Admin to review live caller diagnostics.

---

## 📄 License

This open-source project is licensed under the **[GNU General Public License v3.0](LICENSE)**.

