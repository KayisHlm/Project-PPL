# 🚀 Quick Reference - Database Migrations

## ⚡ Quick Commands

### First Time Setup
```bash
npm install
cp .env.example .env    # Don't forget to edit DB_PASSWORD!
npm run db:setup        # ✅ Creates DB, runs migrations & seeds
```

### Daily Development
```bash
npm run db:migrate      # Run new migrations
npm run db:seed         # Add sample data
```

### Reset Everything (Fresh Start)
```bash
npm run db:reset        # ⚠️ DANGER: Drops database!
npm run db:setup        # Recreate everything
```

## 📁 Folder Structure

```
migrations/
├── schema/             ← SQL files for CREATE/ALTER TABLE
├── seeders/            ← SQL files for INSERT sample data
├── migrate.js          ← Main runner script
├── create_database.js  ← Creates PostgreSQL database
└── reset_database.js   ← Drops & recreates database
```

## 📝 NPM Scripts Available

| Script | Description |
|--------|-------------|
| `npm run db:setup` | Complete setup (create + migrate + seed) |
| `npm run db:create` | Create database only |
| `npm run db:migrate` | Run schema migrations |
| `npm run db:seed` | Insert sample data |
| `npm run db:reset` | ⚠️ Drop & recreate database |

## 📊 Current Schema

### Tables Created:
- ✅ `users` - Authentication (sellers & admins)
- ✅ `sellers` - Shop & PIC information

### Sample Data:
- 1 Platform Admin
- 2 Sellers (1 approved, 1 pending)

## 🔧 .env Configuration

Required environment variables:
```env
DB_HOST=localhost
DB_PORT=5432
DB_NAME=katalog
DB_USER=postgres
DB_PASSWORD=your_password_here
```

## ⚠️ Important Notes

1. **Never modify existing migration files** in production
2. **Always backup** before running migrations in production
3. **Test migrations** on development first
4. **Use seeders** only for development/testing

## 📚 More Info

- Full documentation: `README.md`
- Folder structure guide: `STRUCTURE.md`
