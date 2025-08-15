# My.blog_platform

A modern, lively blog platform with a 3D-gradient UI, dark mode, post grid, trending widget, rich text editor, categories & tags, comments, image uploads, and more.

## Quick Start (XAMPP)

1. Extract the entire `My.blog_platform` folder into:
   `C:\xampp\htdocs\`
2. Start **Apache** + **MySQL** in XAMPP.
3. Open **phpMyAdmin** → create database: `my_blog_platform`
4. Import `my_blog_platform.sql` (found in this folder).
5. Visit: `http://localhost/My.blog_platform/`
6. Login as admin:
   - Email: `admin@example.com`
   - Password: `admin123` (set your own later)

> First user who registers on a fresh DB becomes **admin** automatically.

## Create Posts
- Click **Write** in navbar.
- Add title, category, tags (comma separated), featured image (JPG/PNG/WebP, max 2MB), and content via the rich text editor.
- Publish to see it on the homepage grid.

## Trending
- Sidebar shows 3 most viewed + 2 most recently commented posts.

## Dark Mode
- Use the 🌓 toggle (stores preference in browser).

## InfinityFree Deployment

1. Upload the **contents** of this folder to your InfinityFree `htdocs`.
2. Create a MySQL database in InfinityFree and note the **host**, **db name**, **username**, **password**.
3. Edit `config/config.php` and set:
   ```php
   define('DB_HOST', 'sqlXXX.infinityfree.com');
   define('DB_NAME', 'your_db_name');
   define('DB_USER', 'your_user');
   define('DB_PASS', 'your_pass');
   define('BASE_URL', 'https://your-subdomain.infinityfreeapp.com/'); // trailing slash
   ```
4. Open phpMyAdmin in InfinityFree and **import `my_blog_platform.sql`**.
5. Visit your site URL. Register a new user (first becomes admin) or login with the demo admin.

## Security
- Strong password hashing via `password_hash()` / `password_verify()`.
- Prepared statements to prevent SQL injection.
- CSRF tokens for forms.
- Uploads restricted to images; no script execution allowed in `/uploads`.