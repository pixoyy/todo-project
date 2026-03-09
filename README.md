# 📝 TODO Project Management System

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-00758F?style=for-the-badge&logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

Sistem manajemen proyek dan task yang modern dengan antarmuka responsif dan fitur kolaborasi lengkap.

[🌐 Demo](#) • [📚 Dokumentasi](#setup) • [🐛 Report Bug](../../issues) • [💡 Request Feature](../../issues)

</div>

---

## ✨ Fitur Utama

### 🎯 Project Management
- ✅ Kelola multiple projects dengan mudah
- ✅ Track status project (Aktif/Tidak Aktif)
- ✅ Set tanggal mulai dan berakhir project
- ✅ Dashboard analytics dengan statistik real-time
- ✅ Progress tracking per project

### 📋 Task Organization
- ✅ Buat task dengan kategori yang jelas
- ✅ Set prioritas (Low, Medium, High)
- ✅ Track status task (Todo, In Progress, Review, Done)
- ✅ Assign task ke team member
- ✅ Set deadline dengan notifikasi status overdue
- ✅ Attach deskripsi lengkap untuk setiap task

### 🏷️ Category Management
- ✅ Organize task per kategori
- ✅ Custom color untuk setiap kategori (visual coding)
- ✅ Reorder kategori dengan drag-drop interface
- ✅ Bulk reorder multiple categories sekaligus
- ✅ Auto-increment order untuk convenience

### 👥 Team & Authorization
- ✅ Role-based access control (RBAC)
- ✅ Multiple authorization levels
- ✅ User management dengan admin dashboard
- ✅ Team member assignment
- ✅ Activity tracking

### 📊 Dashboard & Analytics
- ✅ Beautiful dashboard dengan project cards
- ✅ Real-time statistics
- ✅ Active projects overview dengan task breakdown
- ✅ Category-wise task distribution
- ✅ Progress visualization

### 🔧 Developer Features
- ✅ Repository pattern untuk clean code
- ✅ Database soft deletes
- ✅ Pagination & filtering
- ✅ RESTful API ready
- ✅ Responsive design (Mobile-first)

---

## 🚀 Quick Start

### Prerequisites

Sebelum memulai, pastikan Anda sudah install:
- **PHP 8.2+** ([Download](https://www.php.net/downloads))
- **Composer** ([Download](https://getcomposer.org/download/))
- **MySQL 8.0+** ([Download](https://dev.mysql.com/downloads/mysql/))
- **Node.js & npm** ([Download](https://nodejs.org/))
- **Git** ([Download](https://git-scm.com/))

Verifikasi instalasi:
```bash
php --version
composer --version
mysql --version
node --version
npm --version
git --version
```

---

## 📦 Installation

### 1️⃣ Clone Repository

```bash
git clone https://github.com/pixoyy/todo-project.git
cd todo-project
```

### 2️⃣ Install PHP Dependencies

```bash
composer install
```

### 3️⃣ Install Node Dependencies

```bash
npm install
```

### 4️⃣ Setup Environment File

Copy `.env.example` ke `.env`:
```bash
cp .env.example .env
```

Edit `.env` dengan konfigurasi Anda:
```env
APP_NAME=TodoProject
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_project
DB_USERNAME=root
DB_PASSWORD=

# Cache & Session
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

### 5️⃣ Generate Application Key

```bash
php artisan key:generate
```

### 6️⃣ Create Database

```bash
# Buka MySQL
mysql -u root -p

# Di MySQL console
CREATE DATABASE todo_project;
EXIT;
```

### 7️⃣ Run Database Migrations

```bash
php artisan migrate
```

### 8️⃣ Run Database Seeder (Optional)

Untuk mengisi data dummy:
```bash
php artisan db:seed
```

Atau seed spesifik:
```bash
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=AuthorizationSeeder
```

### 9️⃣ Build Frontend Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 🔟 Start Development Server

```bash
php artisan serve
```

Server akan berjalan di: **http://localhost:8000**

---

## 🎯 Setup Lengkap (One Command)

Atau gunakan composer script untuk setup otomatis:

```bash
composer run setup
```

Script ini akan:
- ✅ Install PHP dependencies
- ✅ Copy `.env.example` ke `.env`
- ✅ Generate APP_KEY
- ✅ Run migrations
- ✅ Install npm dependencies
- ✅ Build frontend assets

---

## 👤 Login Default

Setelah seeding, gunakan credentials berikut:

| Field | Value |
|-------|-------|
| **Email** | admin@example.com |
| **Password** | password |

⚠️ **PENTING:** Ganti password default di production!

---

## 📂 Project Structure

```
todo-project/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Request handlers
│   │   ├── Middleware/       # HTTP middleware
│   │   └── Requests/         # Form validation
│   ├── Models/               # Eloquent models
│   └── Repositories/         # Data access layer
├── bootstrap/
│   ├── app.php
│   └── providers.php         # Service providers
├── config/                   # Configuration files
├── database/
│   ├── migrations/           # Schema migrations
│   ├── seeders/              # Database seeding
│   └── factories/            # Model factories
├── resources/
│   ├── css/                  # Stylesheet
│   ├── js/                   # JavaScript
│   └── views/                # Blade templates
├── routes/                   # Application routes
├── public/                   # Web-accessible files
├── storage/                  # Logs, cache, uploads
└── composer.json             # PHP dependencies
```

---

## 🗄️ Database Schema

### Projects
```sql
- id (Primary Key)
- name (string)
- description (text)
- status (boolean: 1=Aktif, 0=Tidak Aktif)
- start_date (date)
- end_date (date)
- admin_id (FK)
- timestamps & soft deletes
```

### Categories
```sql
- id (Primary Key)
- project_id (FK)
- name (string)
- color (string: hex color)
- order (integer: for sorting)
- status (boolean)
- timestamps & soft deletes
```

### Tasks
```sql
- id (Primary Key)
- category_id (FK)
- title (string)
- description (text)
- status (enum: todo, in_progress, review, done)
- priority (enum: low, medium, high)
- due_date (date)
- completed_at (timestamp)
- assigned_admin_id (FK)
- created_by_admin_id (FK)
- timestamps & soft deletes
```

### Users (Admins)
```sql
- id (Primary Key)
- name (string)
- email (string, unique)
- password (hashed)
- status (boolean)
- timestamps & soft deletes
```

---

## 🔐 Authorization & Roles

### Role Levels
- **Super Admin**: Full access ke semua fitur
- **Manager**: Manage projects dan team
- **User**: Create dan manage tasks
- **Viewer**: View only access

### Module Permissions
- Projects: Create, Read, Update, Delete
- Categories: Create, Read, Update, Delete
- Tasks: Create, Read, Update, Delete
- Users: Create, Read, Update, Delete
- Reports: View analytics

---

## 🚦 Available Routes

### Projects
| Method | Route | Action |
|--------|-------|--------|
| GET | `/projects` | List all projects |
| GET | `/projects/add` | Show create form |
| POST | `/projects/add` | Store new project |
| GET | `/projects/{id}` | Show project details |
| GET | `/projects/edit/{id}` | Show edit form |
| PATCH | `/projects/edit/{id}` | Update project |
| DELETE | `/projects/{id}` | Delete project |

### Categories
| Method | Route | Action |
|--------|-------|--------|
| GET | `/categories` | List all categories |
| GET | `/categories/reorder` | Bulk reorder page |
| PATCH | `/categories/reorder` | Save bulk reorder |
| POST | `/categories/add` | Create category |
| PATCH | `/categories/edit/{id}` | Update category |
| DELETE | `/categories/{id}` | Delete category |

### Tasks
| Method | Route | Action |
|--------|-------|--------|
| GET | `/tasks` | List all tasks |
| GET | `/tasks/add` | Show create form |
| POST | `/tasks/add` | Create task |
| GET | `/tasks/{id}` | Show task details |
| PATCH | `/tasks/edit/{id}` | Update task |
| DELETE | `/tasks/{id}` | Delete task |

### Dashboard & Analytics
| Method | Route | Action |
|--------|-------|--------|
| GET | `/` | Main dashboard |
| GET | `/users` | Manage users |
| GET | `/roles` | Manage roles |
| GET | `/authorization` | Permission management |

---

## ⚙️ Configuration

### app/config/

**database.php** - Database connections
```php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST'),
    'database' => env('DB_DATABASE'),
    // ...
]
```

**app.php** - Application settings
```php
'timezone' => 'Asia/Jakarta',
'locale' => 'id',
'fallback_locale' => 'en',
```

---

## 🧪 Testing

### Run Tests
```bash
php artisan test
```

### Run Specific Test
```bash
php artisan test tests/Feature/TaskTest.php
```

### With Coverage
```bash
php artisan test --coverage
```

---

## 📦 Deployment

### Production Build
```bash
# Build assets
npm run build

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Cache config
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache
```

### Environment Setup
```env
APP_ENV=production
APP_DEBUG=false
CACHE_STORE=redis
SESSION_DRIVER=redis
```

### Server Requirements
- PHP 8.2+ dengan extensions: OpenSSL, PDO, Tokenizer, XML
- MySQL 8.0+
- Minimum 512MB RAM
- 1GB storage

---

## 🐛 Troubleshooting

### "SQLSTATE[HY000]: General error: 1030 Got error..."
```bash
# Truncate cache table
php artisan cache:clear
php artisan db:wipe --seed
```

### "No application encryption key"
```bash
php artisan key:generate
```

### "Access denied for user 'root'@'localhost'"
Set correct database credentials di `.env`:
```env
DB_USERNAME=root
DB_PASSWORD=your_password
```

### "npm: command not found"
Install Node.js dari https://nodejs.org/

### "Class not found" errors
```bash
composer dump-autoload
```

---

## 📚 Documentation

- [Laravel Documentation](https://laravel.com/docs)
- [Bootstrap 5 Docs](https://getbootstrap.com/docs/5.0/)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Blade Template Docs](https://laravel.com/docs/blade)

---

## 🤝 Contributing

Kami welcome contributions! Untuk berkontribusi:

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## 📄 License

Distributed under the MIT License. See `LICENSE` file for more information.

---

## 👨‍💼 Author

**Pixoyy**
- GitHub: [@pixoyy](https://github.com/pixoyy)
- Email: [contact@example.com]

---

## 🙏 Acknowledgments

- [Laravel Framework](https://laravel.com)
- [Bootstrap CSS](https://getbootstrap.com)
- [Font Awesome Icons](https://fontawesome.com)
- Community & contributors

---

## 📞 Support

Butuh bantuan? 
- 💬 Buka [GitHub Issues](../../issues)
- 📧 Email: [support@example.com]
- 💡 Diskusi: [GitHub Discussions](../../discussions)

---

<div align="center">

Made with ❤️ by Pixoyy

⭐ Jika project ini membantu, jangan lupa kasih star!

</div>

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
