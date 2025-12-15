# WarungSmart

## 📖 Deskripsi

**WarungSmart** adalah aplikasi web untuk digitalisasi warung tradisional. Sistem ini mendukung manajemen produk, pesanan, membership, kasbon, laporan, serta forecasting permintaan menggunakan metode **Single Exponential Smoothing (SES)** dengan evaluasi **MAD** dan **MAPE**.

Aplikasi ini digunakan oleh tiga peran utama:

* **Admin (Pa Usman)**
* **Supplier**
* **Customer**

---

## 🚀 Fitur Utama

### 👨‍💼 Admin (Pa Usman)

* Kelola produk (CRUD, stok, aktif/nonaktifkan produk)
* Kelola membership (buat jenis membership, atur diskon, approve/downgrade)
* Kelola kasbon pelanggan (catat hutang, update status pembayaran, lihat riwayat)
* Lihat laporan transaksi (harian, mingguan, bulanan dengan filter)
* Generate forecasting permintaan produk (SES + evaluasi MAD/MAPE)
* Kelola supplier (approve produk baru, pantau aktivitas)
* Manajemen pengguna (supplier & customer)

### 📦 Supplier

* Tambah produk baru (status *pending* hingga disetujui admin)
* Lihat pesanan masuk dari customer
* Update status pesanan (accepted/rejected dengan alasan penolakan)
* Lihat histori harga produk
* Pantau stok produk
* Edit detail produk yang sudah di-*approve*

### 🛒 Customer

* Belanja produk dengan harga normal atau diskon membership
* Lihat pesanan saya (riwayat transaksi, status, alasan penolakan jika ada)
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
* Debt
* ForecastResult
* ForecastMetric

**Relasi:**

* User – Membership
* Supplier – Product
* Customer – Order
* Product – Order
* Product – ForecastResult
* Product – ForecastMetric
* Customer – Debt

---

### Use Case

**Admin:**

* Kelola Produk
* Kelola Membership
* Kelola Kasbon
* Forecast Permintaan
* Laporan Transaksi
* Kelola Supplier
* Manajemen User

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

---

### Activity Diagram (Alur Pemesanan)

1. Customer memilih produk
2. Sistem menampilkan detail produk dan diskon membership
3. Customer melakukan checkout → order tercatat
4. Supplier menerima notifikasi pesanan
5. Supplier mengupdate status pesanan (accepted/rejected dengan alasan)
6. Admin melihat laporan transaksi

---

### UML Class Diagram (Ringkas)

**User**

* Attributes: `id`, `name`, `email`, `role`, `membership_id`

**Product**

* Attributes: `id`, `name`, `price`, `stock`, `supplier_id`

**Order**

* Attributes: `id`, `product_id`, `customer_id`, `quantity`, `total_price`, `status`, `rejection_reason`

**Membership**

* Attributes: `id`, `type`, `discount_percentage`

**Debt**

* Attributes: `id`, `customer_id`, `product_id`, `amount`, `status`, `due_date`, `notes`

**ForecastResult**

* Attributes: `id`, `product_id`, `period`, `forecast`, `actual`

**ForecastMetric**

* Attributes: `id`, `product_id`, `mad`, `mape`

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
4. Update status pesanan (accepted/rejected dengan alasan)
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
* **Catatan**: Hasil evaluasi ditampilkan di dashboard admin

---

## 👥 Tim Pengembang

* **Rifky** — Lead Developer & Systems Architect
* **Pa Usman** — Admin POV / Client
* Supporting Developers & UI/UX Designers

---

## 📜 Lisensi

Proyek ini bersifat **open-source** dan dapat digunakan serta dikembangkan lebih lanjut sesuai kebutuhan.
