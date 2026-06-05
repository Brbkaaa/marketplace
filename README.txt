Marketplace is a mini e-commerce web application where buyers can browse and purchase products, sellers can list products with images, and admins manage the entire
  platform. Built with PHP (OOP), MySQL, HTML5, CSS3, and jQuery. Features include role-based access control, session/cookie management, file uploads, responsive design,
  dark mode, and AJAX-powered cart functionality.


SETUP INSTRUCTIONS:
1. Import the database: Open phpMyAdmin → Import → select sql/marketplace.sql
2. Place the "marketplace" folder in your htdocs directory
3. Start Apache and MySQL from XAMPP Control Panel
4. Open http://localhost/marketplace/ in browser

DEFAULT ACCOUNTS:
- Admin:  admin@marketplace.com / admin123
- (Register new buyer/seller accounts via the Register page)

PHP VERSION: 8.x
DATABASE: MySQL (marketplace)

FEATURES:
- User registration & login (email-based)
- 3 roles: buyer, seller, admin
- Product listing with image upload
- Category filtering & live search (jQuery)
- Shopping cart (session-based)
- Order placement
- Admin panel: manage users, products, export users to file
- Seller panel: add/edit/delete products
- Responsive design (mobile/tablet/desktop)
- CSS3 animations, transitions, transforms
- Secure: hashed passwords, prepared statements, input validation, HTTP-only cookies

DATABASE TABLES (6):
- users
- products
- categories
- product_categories (N:N junction)
- orders
- order_items (N:N junction)

RELATIONSHIPS:
- 1:N — users → products (seller has many products)
- 1:N — users → orders (buyer has many orders)
- N:N — products ↔ categories (via product_categories)
- N:N — products ↔ orders (via order_items)


URLs:
  HOME:               http://localhost/marketplace/index.php
  SHOP:               http://localhost/marketplace/shop.php
  PRODUCT DETAIL:     http://localhost/marketplace/product.php?id=1
  CART:               http://localhost/marketplace/cart.php
  CHECKOUT:           http://localhost/marketplace/checkout.php
  LOGIN:              http://localhost/marketplace/login.php
  REGISTER:           http://localhost/marketplace/register.php
  PROFILE:            http://localhost/marketplace/profile.php
  LOGOUT:             http://localhost/marketplace/logout.php

  SELLER PANEL:       http://localhost/marketplace/seller/dashboard.php
  ADD PRODUCT:        http://localhost/marketplace/seller/add_product.php
  EDIT PRODUCT:       http://localhost/marketplace/seller/edit_product.php?id=1

  ADMIN PANEL:        http://localhost/marketplace/admin/dashboard.php
  MANAGE USERS:       http://localhost/marketplace/admin/manage_users.php
  MANAGE PRODUCTS:    http://localhost/marketplace/admin/manage_products.php
  MANAGE ORDERS:      http://localhost/marketplace/admin/manage_orders.php
  EXPORT USERS:       http://localhost/marketplace/admin/export_users.php
  USER NOTES:         http://localhost/marketplace/admin/user_notes.php?id=2
