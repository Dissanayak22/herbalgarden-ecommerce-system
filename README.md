# 🌿 HerbalGarden E-Commerce (Local PHP App)

A lightweight herbal products shop built with **PHP (PDO)**, **MySQL**, **HTML/CSS**, and sessions. Includes a user-facing storefront and an admin dashboard for managing products and orders. Designed to run locally via **XAMPP**.

---
## 🚀 Features
- Browse products, categories, and product detail pages
- Session-based cart (add, update, remove, clear)
- Secure checkout storing orders + order items
- Admin authentication (hashed password)
- Product CRUD with image upload
- Order management (view items, mark completed)
- Database auto-creation script + SQL dump

---
## 🗂️ Structure
```
herbalgarden-ecommerce/
├── assets/
│   ├── css/style.css
│   ├── js/main.js
│   └── uploads/ (product images)
├── includes/
│   ├── config.php
│   ├── header.php
│   └── footer.php
├── admin/
│   ├── login.php / logout.php
│   ├── dashboard.php
│   ├── add_product.php / edit_product.php / view_products.php
│   ├── view_orders.php / order_detail.php
├── index.php
├── products.php
├── product_detail.php
├── cart.php
├── checkout.php
├── thankyou.php
├── create_database.php
└── herbalgarden_db.sql
```

---
## 🛠️ Setup (XAMPP)
1. Copy folder to: `htdocs/herbalgarden-ecommerce`
2. Start **Apache** + **MySQL** in XAMPP
3. Visit: http://localhost/herbalgarden-ecommerce/create_database.php (one time)
4. Storefront: http://localhost/herbalgarden-ecommerce/
5. Admin panel: http://localhost/herbalgarden-ecommerce/admin/

---
## 🔐 Admin Credentials
```
Username: admin
Password: admin123
```
Change the password after first login by updating the DB row.

---
## 💾 Database
Name: `herbalgarden_db`
Tables: `products`, `orders`, `order_items`, `admin_users`
You can recreate schema either by:
- Running `create_database.php`, or
- Importing `herbalgarden_db.sql` via phpMyAdmin

---
## 🛒 Cart Logic
Stored in `$_SESSION['cart']` as an associative array keyed by product ID:
```
[id => [id, name, price, image, quantity, subtotal]]
```
Totals are recalculated server-side during checkout.

---
## 🧪 Security Notes
- Uses prepared statements (PDO) everywhere
- Admin passwords hashed via `password_hash()`
- Output escaped with `htmlspecialchars()`
- Basic session-based auth (improve by regenerating session ID post-login in production)

---
## 📦 Roadmap Ideas
- Pagination & search
- Category management table
- Image optimization & thumbnails
- CSRF tokens for form actions
- REST API or JSON endpoints
- Docker setup

---
## 🧹 Maintenance
To reset demo data:
1. Truncate `orders` and `order_items`
2. Optionally truncate `products` and re-import sample entries from SQL dump

---
## 📄 License
MIT (feel free to adapt for learning or small internal demos)

Enjoy building with nature! 🌱
