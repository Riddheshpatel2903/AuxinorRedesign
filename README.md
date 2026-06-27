# Auxinor Chemicals — Premium Chemical Trading Platform

Auxinor Chemicals is a high-performance, B2B chemical trading and distribution platform built for the Indian industrial market. This project represents a modern redesign focused on visual excellence, speed, and a streamlined administrative experience.

## ✨ Core Features

- **Visual CMS (In-Place Editing)**: A custom-built visual editor that allows admins to click and edit content directly on the page.
- **Product Management**: Robust cataloging system for industrial chemicals with technical specifications, CAS numbers, and category-based filtering.
- **Market Insights**: A dedicated blog and insights engine to keep clients updated on industry trends.
- **Dynamic Infrastructure**: Flexible page section architecture allowing for modular layout management.
- **Responsive Design**: Premium, dark-themed UI built with a mobile-first approach.

## 🛠 Tech Stack

- **Backend**: [Laravel 13](https://laravel.com) (PHP 8.3+)
- **Frontend**: [Tailwind CSS 4](https://tailwindcss.com), [Alpine.js](https://alpinejs.dev), [Vite](https://vitejs.dev)
- **CMS Engine**: [GrapesJS](https://grapesjs.com) (Integrated for advanced layout editing)
- **Database**: SQLite (Default) / MySQL

## 🚀 Getting Started

### Prerequisites

- PHP 8.3 or higher
- Composer
- Node.js (v20+) & NPM

### Local Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd AuxinorRedesign
   ```

2. **Run the setup command:**
   This project includes a convenient setup script that handles dependency installation, environment setup, and migrations.
   ```bash
   composer setup
   ```

3. **Run the development server:**
   This will start both the Laravel server and the Vite dev server concurrently.
   ```bash
   composer dev
   ```

4. **Access the application:**
   Open [http://localhost:8000](http://localhost:8000) in your browser.

## 📊 Administration

To access the admin features, navigate to `/admin`.

### Database Seeding
To populate the database with initial categories, products, and page sections:
```bash
php artisan db:seed
```

### Visual Editor
Once logged in as an admin, a "Visual Editor" button will appear in the bottom-right corner. You can also edit sections directly by hovering over them in the frontend and clicking the edit indicators.

## 📁 Project Structure

- `app/Models`: Core business logic and Eloquent models (Product, PageSection, etc.)
- `app/Http/Controllers`: Backend request handling.
- `resources/views`: Blade templates organized by page and partials.
- `database/seeders`: Seeders for initial data population.
- `public/assets`: Static assets and images.

## 📄 License

The Auxinor Redesign project is proprietary software. All rights reserved.
