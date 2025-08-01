# 🚀 React + Laravel User Management System

A simple full-stack application using **React 17 (TypeScript)** and **Laravel 8 API** that allows creating users with multiple roles and viewing them by role filters.

---

## 📋 Features

### Laravel (Backend)

- Laravel 8 REST API
- Users can have **multiple roles**
- `POST /api/users` – Create user with full name, email, and roles
- `GET /api/users` – Fetch list of users
- `GET /api/roles` – Fetch available roles
- Validations:
  - Email (required, unique, valid format)
  - Full Name (required)
  - Roles (required, array)

### React (Frontend)

- Built with **React 17 + TypeScript**
- **Tailwind CSS** for styling
- Axios for API integration
- Pages:
  - **User List**: Displays all users grouped by roles
  - **Add User**: Form to create user with multiple role selection
- Uses `react-router-dom@5` for routing

---

## 🛠️ Installation

### Backend (Laravel)

1. Clone backend repo or go to your Laravel project folder
2. Run:

   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

3. Configure `.env` to match your database:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. Run migrations and seed roles:

   ```bash
   php artisan migrate --seed
   ```

5. Start the server:

   ```bash
   php artisan serve
   ```

### Frontend (React)

1. Go to the frontend folder:

   ```bash
   cd reactjs-usermanagement-fe
   ```

2. Install dependencies:

   ```bash
   npm install
   ```

3. Create `.env` file:

   ```
   VITE_API_BASE_URL=http://localhost:8000/api
   ```

4. Run the React app:

   ```bash
   npm run dev
   ```

---

## 📦 Folder Structure (Frontend)

```
src/
├── api/             # Axios config and API functions
├── components/      # Reusable UI components
├── pages/           # Page-level views (UserList, AddUser)
├── types/           # TypeScript interfaces
├── App.tsx          # Routing configuration
```

---

## ✅ Deliverables

- [x] Add user with full name, email, multiple roles
- [x] View users list grouped by roles
- [x] React functional components with hooks
- [x] TypeScript support
- [x] Axios integration
- [x] Laravel API with validation and seeding

---

## 👨‍💻 Author

Created by **Kiner Mercurio** – for the S30 Laravel + ReactJS Test 2022.04
