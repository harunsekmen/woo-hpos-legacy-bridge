# 🔄 WooCommerce HPOS & Eski Postmeta Uyumluluk Köprüsü

[🇬🇧 English](README.md) · [🇹🇷 Türkçe](README.tr.md)

> Eski eklentilerin $order->get_post_meta() ve get_post_meta($order_id) çağrılarını canlı yakalayıp yeni HPOS tablolarına yönlendiren, mağazaların veri kaybı yaşamadan HPOS'a geçmesini sağlayan şeffaf ara katman.

Geliştirici ve Bakım Sorumlusu: **[Harun Sekmen](https://github.com/harunsekmen)** — E-Ticaret Sistem Mühendisi & WooCommerce Uzmanı.

[✉️ İletişime Geç](mailto:sekmenwp@gmail.com) · [💼 Upwork Profili](https://www.upwork.com/freelancers/~01a7a8b58043e6e6d9) · [🔗 GitHub Ana Profil](https://github.com/harunsekmen)

---

## 📸 Görsel Önizlemeler ve Yönetim Paneli

### 1. WordPress Yönetici HPOS Denetim ve Telemetri Paneli
Eski eklentilerin kancalarını, yönlendirilen sorgu sayısını ve HPOS sağlık durumunu gösteren arayüz:

![HPOS Köprüsü Yönetici Paneli](docs/assets/admin-hpos-bridge-dashboard.png)

---

## ⚡ Çözdüğü Kritik Sorun

WooCommerce 8.0+ ile birlikte siparişler artık wp_posts tablosuna değil, yeni yüksek performanslı wp_wc_orders (HPOS) tablolarına yazılır:
- **Eski Eklenti Kırılmaları:** Binlerce mağazanın kullandığı eski fatura, kargo veya özel eklentiler hala doğrudan get_post_meta() çağrısı yapar.
- **Kayıp Sipariş Verileri:** Bu durum kargo takip numaralarının düşmemesine ve sipariş notlarının kaybolmasına yol açar.
- **HPOS'a Geçememe:** Mağazalar bu eklentiler yüzünden hızlı yeni tablolara geçemez ve eski yavaş veritabanında takılı kalır.

**HPOS Legacy Bridge**, WordPress'in metadata filtrelerine bağlanarak bu çağrıları çalışma anında şeffaf bir şekilde yeni HPOS sipariş nesnelerine yönlendirir.

---

## 🛠️ Öne Çıkan Özellikler

- **Şeffaf Sorgu Yönlendirme:** Eski eklenti kodunu değiştirmeden postmeta çağrılarını doğrudan HPOS'a bağlar.
- **Sıfır Veri Kaybı:** Kargo takip kodları ve özel sipariş alanları yeni tablolarda güvenle güncellenir.
- **Geliştirici Günlüğü:** Hangi eski eklentinin uyumsuz çağrı yaptığını yönetici panelinde raporlar.
- **Yüksek Performans:** Önbellek mekanizması sayesinde veritabanına ek sorgu yükü bindirmez.

---

## 🏗️ Teknik Mimari ve Standartlar

`PHP 8.1+` • `WooCommerce HPOS` • `WordPress Metadata API` • `Audit Logger`

---

## 🚀 Kurulum ve Test

1. Depoyu kurun ve test edin:
   ```bash
   git clone https://github.com/harunsekmen/woo-hpos-legacy-bridge.git wp-content/plugins/woo-hpos-legacy-bridge
   ```
2. Testi çalıştırın:
   ```bash
   php wp-content/plugins/woo-hpos-legacy-bridge/tests/test-bridge.php
   ```

---

## 📄 Lisans

Bu proje **GPL-3.0 Lisansı** ile açık kaynak olarak sunulmaktadır.
