# Boat

> Full-stack web application built with **Laravel**, **Vue 3**, **Vite**, and **Bun**.

---

## 📌 Overview

This project uses:
- **Laravel** as the backend framework
- **Vue 3** as the frontend framework
- **Vite** for frontend bundling
- **Bun** for fast frontend dependency management

The architecture is optimized for:
- Clear separation of backend & frontend
- Fast local development
- Easy onboarding for new team members

---

## 🧰 Tech Stack

### Backend
- PHP ≥ 8.1
- Laravel
- Composer

### Frontend
- Vue 3
- Vite
- TypeScript (optional)
- Bun

### Tooling
- VS Code
- Volar (Vue Language Features)

---

## 💻 System Requirements

- PHP ≥ 8.1
- Composer
- Bun
- Node.js (optional but recommended)
- Git

---

## 🧠 Recommended IDE Setup

- VS Code
- Extensions:
  - Volar
- Disable Vetur to avoid conflicts

---

## 🚀 Getting Started

### 1. Clone repository
```bash
git clone <repository-url>
cd <project-name>
```

---

## ⚙️ Backend Setup (Laravel)

### 2. Install PHP dependencies
```bash
composer install
```

### 3. Setup environment file
```bash
cp .env.example .env
```

### 4. Generate application key
```bash
php artisan key:generate
```

---

## 🎨 Frontend Setup (Vue 3 + Vite + Bun)

### 5. Install frontend dependencies
```bash
bun install
```

### 6. Start Vite development server
```bash
bun run dev
```

---

## ▶️ Running the Application

### 7. Start Laravel server
```bash
php artisan serve
```

### Default URLs
- Backend: http://127.0.0.1:8000
- Frontend: http://localhost:5173

---

## 🏗️ Production Build

```bash
bun run build
php artisan optimize
```

---

## 📂 Project Structure

```text
├── app/
├── resources/
│   ├── js/
│   └── views/
├── public/
├── routes/
├── vite.config.ts
├── package.json
├── composer.json
└── README.md
```

---

## 📜 Common Commands

| Command | Description |
|------|-------------|
| composer install | Install backend dependencies |
| php artisan serve | Start Laravel server |
| bun install | Install frontend dependencies |
| bun run dev | Start Vite dev server |
| bun run build | Build frontend |

---

## 🔐 Environment Notes

- Do not commit `.env`
- Use `.env.example` as reference

---

## 📄 License

MIT
