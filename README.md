# 📋 FormCraft Builder

A full-featured, SaaS-ready **dynamic form builder** built with Laravel — similar to Google Forms or JotForm. Admins can create forms with a drag-and-drop builder, apply conditional logic, and manage all submissions from a clean dashboard.

---

## ✨ Features

| Feature | Description |
|---|---|
| 🔐 Auth | Session-based login/logout with role support (Admin / User) |
| 🏗️ Form Builder | Drag-and-drop field ordering |
| 🧩 Field Types | Text, Email, Number, Dropdown, Radio, Checkbox, File Upload, Date, Textarea |
| ✅ Validations | Required, Email, Numeric, Min/Max length, File type & size |
| ⚡ Conditional Logic | Show/hide fields based on other field values |
| 🌐 Public Forms | Shareable slug-based public URL — no login required for end users |
| 📥 Submissions | Stores all responses securely per field |
| 🔍 Submission Management | View, filter, search, and export submissions to CSV |
| 📊 Dashboard | Admin dashboard with form stats and submission counts |

---

## 🛠️ Tech Stack

- **Backend:** Laravel 11 (PHP 8.2+)
- **Frontend:** Laravel Blade + Alpine.js
- **Styling:** Tailwind CSS
- **Database:** MySQL 8.0
- **Drag & Drop:** SortableJS

---

## ⚙️ Installation

### 1. Clone the repository

```bash
git clone https://github.com/tahaa-syntax2608/FormCraft-Builder.git
cd FormCraft-Builder
```

### 2. Install dependencies

```bash
composer install
npm install
npm run build
```

### 3. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Open `.env` and set your database credentials:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=custom_form_builder
DB_USERNAME=root
DB_PASSWORD=

### 4. Run migrations and seed

```bash
php artisan migrate --seed
```

Default admin account:

| Field | Value |
|---|---|
| Email | `admin@formbuilder.com` |
| Password | `password` |

### 5. Start the server

```bash
php artisan serve
```

Visit: **http://localhost:8000/admin/dashboard**

---

## 🔌 API Endpoints

### Auth
| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/login` | Admin login |
| POST | `/api/logout` | Logout |

### Forms
| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/forms` | List all forms |
| POST | `/api/forms` | Create new form |
| PUT | `/api/forms/{id}` | Update form |
| DELETE | `/api/forms/{id}` | Delete form |
| POST | `/api/forms/{id}/duplicate` | Duplicate form |

### Public
| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/public/forms/{slug}` | Load form |
| POST | `/api/public/forms/{slug}/submit` | Submit response |

### Submissions
| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/forms/{id}/submissions` | List submissions |
| GET | `/api/forms/{id}/submissions/export` | Download CSV |

---

## 🗄️ Database Schema

users               → admin and user accounts
forms               → form metadata, slug, status, settings
form_fields         → dynamic field schema (type, label, validations, conditions)
form_submissions    → one record per submission
submission_values   → one record per field answer

---

## 📸 Screenshots

### Dashboard
![Dashboard](public/screenshots/dashboard.png)

---

## 👨‍💻 Author

**Muhammad Taha**
- GitHub: [@tahaa-syntax2608](https://github.com/tahaa-syntax2608)

---

## 📄 License

This project is open-source under the [MIT License](LICENSE).