# WarungSmart

## 📖 Deskripsi

**WarungSmart** adalah aplikasi berbasis web untuk digitalisasi warung tradisional. Sistem ini mendukung manajemen produk, pesanan, membership, kasbon, laporan, serta forecasting permintaan menggunakan metode **Single Exponential Smoothing (SES)** dengan evaluasi **MAD** dan **MAPE**.

Aplikasi ini dirancang agar mudah digunakan oleh **Admin (Pa Usman)**, **Supplier**, dan **Customer** dengan antarmuka yang sederhana namun fungsional.

---

## 🚀 Fitur Utama

### 👨‍💼 Admin (Pa Usman)

* Kelola produk (tambah, edit, hapus, set stok, aktif/nonaktifkan produk)
* Kelola membership (buat jenis membership, atur diskon, aktif/nonaktifkan)
* Kelola kasbon pelanggan (catat hutang, update status pembayaran, lihat riwayat)
* Lihat laporan transaksi (harian, mingguan, bulanan dengan filter)
* Generate forecasting permintaan produk (SES + evaluasi MAD/MAPE)
* Kelola supplier (approve produk baru, pantau aktivitas)
* Manajemen pengguna (supplier & customer)

### 📦 Supplier

* Tambah produk baru (status *pending* hingga disetujui admin)
* Lihat pesanan masuk dari customer
* Update status pesanan (accepted/rejected dengan catatan)
* Lihat histori harga produk
* Pantau stok produk
* Edit detail produk yang sudah di-*approve*

### 🛒 Customer

* Belanja produk dengan harga normal atau diskon membership
* Lihat pesanan saya (status, total harga, riwayat transaksi)
* Gunakan membership untuk diskon otomatis
* Catat kasbon (pembelian hutang)
* Lihat riwayat kasbon dan status pembayaran
* Daftar atau perpanjang membership

---

## 🛠️ Teknologi

* **Framework**: Laravel v12
* **Database**: MySQL
* **Frontend**: Blade, Bootstrap, Chart.js
* **Tools**: GitHub, Composer, NPM

---

## 📂 Struktur Proyek

```
/app
/resources/views
/routes
/database/migrations
/public
```

---

## 📊 Dokumentasi Sistem

### Entity Relationship Diagram (ERD)

**Entities:**

* User
* Product
* Order
* Membership
* ForecastResult
* ForecastMetric

**Relasi:**

* User – Membership
* Supplier – Product
* Customer – Order
* Product – Order
* Product – ForecastResult
* Product – ForecastMetric

### Use Case

**Admin:**

* Kelola Produk
* Kelola Membership
* Kelola Kasbon
* Forecast Permintaan
* Laporan Transaksi
* Kelola Supplier
* Manajemen Pengguna

**Supplier:**

* Tambah Produk
* Pesanan Masuk
* Update Status Pesanan
* Histori Harga
* Pantau Stok
* Edit Produk

**Customer:**

* Belanja Produk
* Membership
* Kasbon
* Pesanan
* Riwayat Kasbon
* Daftar / Perpanjang Membership

### Activity Diagram (Contoh Alur Pemesanan)

1. Customer memilih produk
2. Sistem menampilkan detail + diskon membership
3. Customer melakukan checkout
4. Sistem membuat order
5. Supplier menerima notifikasi pesanan
6. Supplier mengupdate status pesanan
7. Admin melihat laporan transaksi

### UML Class Diagram (Ringkas)

**User**

* Attributes: `id`, `name`, `email`, `role`, `membership_id`
* Methods: `login()`, `register()`, `hasMembership()`

**Product**

* Attributes: `id`, `name`, `price`, `stock`, `supplier_id`
* Methods: `updateStock()`, `approveProduct()`

**Order**

* Attributes: `id`, `product_id`, `customer_id`, `quantity`, `total_price`
* Methods: `calculateTotal()`, `updateStatus()`

**Membership**

* Attributes: `id`, `type`, `discount_percentage`
* Methods: `applyDiscount()`

**ForecastResult**

* Attributes: `id`, `product_id`, `period`, `forecast`, `actual`
* Methods: `generateForecast()`

**ForecastMetric**

* Attributes: `id`, `product_id`, `mad`, `mape`
* Methods: `calculateMAD()`, `calculateMAPE()`

---

## ⚙️ Instruksi Penggunaan

### 👨‍💼 Admin

1. Login ke dashboard admin
2. Kelola produk (CRUD, stok, status aktif)
3. Kelola membership dan diskon
4. Kelola kasbon pelanggan
5. Jalankan forecasting permintaan produk
6. Analisis laporan transaksi
7. Approve produk supplier
8. Kelola akun pengguna

### 📦 Supplier

1. Login ke dashboard supplier
2. Tambah produk baru (menunggu approval admin)
3. Pantau pesanan masuk
4. Update status pesanan
5. Lihat histori harga produk
6. Pantau stok
7. Edit produk yang sudah disetujui

### 🛒 Customer

1. Login ke dashboard customer
2. Lihat dan pilih produk
3. Checkout produk (diskon otomatis jika membership aktif)
4. Pembelian kasbon (jika ada)
5. Lihat status dan riwayat pesanan
6. Lihat riwayat kasbon
7. Daftar atau perpanjang membership

---

## ⚙️ Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/warungsmart.git
cd warungsmart
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Migrasi Database

```bash
php artisan migrate --seed
```

### 5. Jalankan Server

```bash
php artisan serve
```

---

## 📈 Forecasting

* **Metode**: Single Exponential Smoothing (SES)
* **Evaluasi**: Mean Absolute Deviation (MAD) & Mean Absolute Percentage Error (MAPE)
* **Catatan**: Hasil evaluasi disimpan di backend dan tidak ditampilkan di UI

---

## 👥 Tim Pengembang

* **Oesman** — Lead Developer & Architect
* Supporting Developers & UI/UX Designers

---

## 📜 Lisensi

Proyek ini bersifat **open-source** dan dapat digunakan serta dikembangkan lebih lanjut sesuai kebutuhan.
