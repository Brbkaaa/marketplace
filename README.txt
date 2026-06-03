============================================
        MARKETPLACE - Final Project
============================================

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
