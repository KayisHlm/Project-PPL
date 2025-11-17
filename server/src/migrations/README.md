# Database Migrations

Dokumentasi untuk menjalankan database migrations untuk tabel `users` dan `sellers`.

## Struktur Tabel

### Tabel: users
Menyimpan data pengguna (seller dan admin platform)

**Kolom:**
- `id`: BIGSERIAL PRIMARY KEY
- `email`: VARCHAR(255) NOT NULL UNIQUE
- `password`: VARCHAR(255) NOT NULL
- `role`: VARCHAR(50) NOT NULL CHECK (role IN ('seller', 'platform_admin'))
- `created_at`: TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
- `updated_at`: TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

**Indexes:**
- `idx_users_email` pada kolom `email`
- `idx_users_role` pada kolom `role`

### Tabel: sellers
Menyimpan informasi toko dan data PIC (Person In Charge)

**Kolom:**
- `id`: BIGSERIAL PRIMARY KEY
- `user_id`: BIGINT NOT NULL UNIQUE (Foreign Key ke users.id)
- `shop_name`: VARCHAR(255) NOT NULL
- `shop_description`: TEXT
- `pic_name`: VARCHAR(255) NOT NULL
- `pic_phone_number`: VARCHAR(20) NOT NULL
- `pic_email`: VARCHAR(255) NOT NULL UNIQUE
- `pic_address`: TEXT NOT NULL
- `pic_rt`: VARCHAR(10) NOT NULL
- `pic_rw`: VARCHAR(10) NOT NULL
- `pic_village`: VARCHAR(255) NOT NULL
- `pic_city`: VARCHAR(255) NOT NULL
- `pic_province`: VARCHAR(255) NOT NULL
- `pic_ktp_number`: VARCHAR(20) NOT NULL UNIQUE
- `pic_photo_path`: VARCHAR(500)
- `pic_ktp_path`: VARCHAR(500)
- `status`: VARCHAR(50) NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'approved', 'rejected'))
- `rejection_reason`: TEXT
- `verified_at`: TIMESTAMP
- `created_at`: TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
- `updated_at`: TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

**Indexes:**
- `idx_sellers_user_id` pada kolom `user_id`
- `idx_sellers_status` pada kolom `status`
- `idx_sellers_shop_name` pada kolom `shop_name`

**Foreign Keys:**
- `user_id` REFERENCES `users(id)` ON DELETE CASCADE

## Cara Menjalankan Migration

### 1. Pastikan Database PostgreSQL sudah berjalan

### 2. Install dependencies
```bash
npm install
```

### 3. Setup file .env
Copy file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database:
```bash
cp .env.example .env
```

Edit file `.env`:
```env
DB_HOST=localhost
DB_PORT=5432
DB_NAME=katalog
DB_USER=postgres
DB_PASSWORD=your_password_here
```

### 4. Buat database (jika belum ada)
```bash
node src/migrations/create_database.js
```

### 5. Jalankan Migration
```bash
node src/migrations/migrate.js
```

Atau:
```bash
node src/migrations/migrate.js migrate
```

### 6. (Opsional) Jalankan Seeder untuk data contoh
```bash
node src/migrations/migrate.js seed
```

### 7. (Opsional) Reset database (drop & create ulang)
```bash
node src/migrations/reset_database.js
```

Kemudian jalankan migration lagi:
```bash
node src/migrations/migrate.js
node src/migrations/migrate.js seed
```

## File Migration

### Struktur Folder:
```
migrations/
├── schema/               # Schema migrations (DDL)
│   ├── 001_create_users_table.sql
│   └── 002_create_sellers_table.sql
├── seeders/              # Data seeders (DML)
│   └── seed_users_sellers.sql
├── migrate.js            # Migration runner script
└── create_database.js    # Database creation script
```

### Detail Files:

**Schema Files (migrations/schema/):**
1. **001_create_users_table.sql** - Membuat tabel users dengan indexes dan triggers
2. **002_create_sellers_table.sql** - Membuat tabel sellers dengan foreign key ke users

**Seeder Files (migrations/seeders/):**
1. **seed_users_sellers.sql** - Data contoh (seeder) untuk testing

**Utility Scripts:**
- **migrate.js** - Script utama untuk menjalankan migrations dan seeders
- **create_database.js** - Script untuk membuat database PostgreSQL
- **reset_database.js** - Script untuk reset database (drop & create ulang)

## Command Reference

### NPM Scripts (Recommended) 🚀

```bash
# Complete setup (create DB + migrate + seed)
npm run db:setup

# Create database
npm run db:create

# Run migrations
npm run db:migrate

# Run seeders
npm run db:seed

# Reset database (drop & create) - ⚠️ DANGER!
npm run db:reset
```

### Direct Node Commands (Alternative)

```bash
# Complete setup
node src/migrations/create_database.js
node src/migrations/migrate.js
node src/migrations/migrate.js seed

# Individual commands
node src/migrations/create_database.js    # Create database
node src/migrations/migrate.js            # Run migrations
node src/migrations/migrate.js seed       # Run seeders
node src/migrations/reset_database.js     # Reset database
```

### Migration Commands (Legacy)
```bash
# Jalankan semua migrations (create tables)
node src/migrations/migrate.js

# Sama dengan perintah di atas
node src/migrations/migrate.js migrate
```

### Seeder Commands (Legacy)
```bash
# Jalankan seeder untuk menambah data contoh
node src/migrations/migrate.js seed
```

### Database Management (Legacy)
```bash
# Buat database baru
node src/migrations/create_database.js

# Reset database (drop & create ulang - HATI-HATI!)
node src/migrations/reset_database.js
```

### Complete Setup (First Time)
```bash
# Method 1: Using NPM Scripts (Easier) ✅
npm install
cp .env.example .env      # Edit dan sesuaikan DB_PASSWORD
npm run db:setup          # One command to rule them all!

# Method 2: Using Node Commands
npm install
cp .env.example .env      # Edit dan sesuaikan DB_PASSWORD
node src/migrations/create_database.js
node src/migrations/migrate.js
node src/migrations/migrate.js seed
```

## Catatan Penting

- Migration file menggunakan `CREATE TABLE IF NOT EXISTS` sehingga aman untuk dijalankan berulang kali
- Terdapat trigger otomatis untuk update kolom `updated_at` setiap kali ada perubahan data
- Password di seeder adalah contoh yang sudah di-hash dengan bcrypt (untuk production, gunakan password yang sesungguhnya)
- Foreign key constraint memastikan data integrity antara tabel users dan sellers
- CASCADE delete memastikan jika user dihapus, data seller terkait juga akan terhapus

## Troubleshooting

Jika terjadi error:
1. Pastikan PostgreSQL sudah running
2. Periksa konfigurasi database di file `db.js`
3. Pastikan user PostgreSQL memiliki hak akses yang sesuai
4. Periksa apakah database sudah dibuat
