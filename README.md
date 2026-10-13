# VoltCore Blog

A small PHP + MySQL blog for the VoltCore electronics site, built for the Buoi 5 exercise. The original static HTML/CSS pages were converted to PHP with shared includes, and posts are now stored in a MySQL database and managed through a small admin CRUD area.

## Features

- **Public blog listing** (`blog.php`) with a featured post, a card grid, category filters, live search, sorting and pagination.
- **Single post view** (`post.php?id=<slug>`) with author/meta, hero image (or gradient placeholder), table of contents, related posts and a reading-progress bar.
- **Admin CRUD** (`admin/`) to create, edit and delete posts, with flash messages and validation.
- **Repository layer** (`lib/posts.php`) wrapping all database access via PDO prepared statements.
- **Icons as SVG** (`assets/*.svg`) — no emoji in the UI.
- Friendly error page returned when a slug is not found.

## Requirements

- PHP 8.0+ with the `pdo_mysql` extension
- MySQL 5.7+ / MariaDB 10.4+

## Project structure

```
Buoi5/
├── index.php              # Redirects / -> blog.php
├── blog.php               # Blog listing page
├── post.php               # Single post page (?id=<slug>)
├── admin/
│   ├── index.php          # List all posts
│   ├── create.php         # Create a post
│   ├── edit.php           # Edit a post (?id=<id>)
│   ├── delete.php         # Delete a post (POST only)
│   └── partials/          # Admin header / footer / shared form
├── config/
│   └── database.php       # PDO connection + credentials
├── lib/
│   └── posts.php          # Post repository and helpers
├── data/
│   └── posts.php          # Seed data source (legacy array)
├── db/
│   ├── schema.sql         # Database + posts table
│   └── seed.sql           # Sample posts
├── includes/
│   ├── nav.php            # Shared navigation
│   └── footer.php         # Shared footer
├── css/blog.css           # Stylesheet
└── assets/*.svg           # Logo and UI icons
```

## Setup

1. **Create the database and table.** Run the schema (creates the `voltcore` database and `posts` table), then load the sample data:

   ```bash
   mysql -u root -p < db/schema.sql
   mysql -u root -p voltcore < db/seed.sql
   ```

   Or import `db/schema.sql` and `db/seed.sql` through phpMyAdmin.

2. **Configure the connection.** Defaults live in `config/database.php` and can be overridden with environment variables:

   | Variable  | Default     |
   |-----------|-------------|
   | `DB_HOST` | `127.0.0.1` |
   | `DB_PORT` | `3306`      |
   | `DB_NAME` | `voltcore`  |
   | `DB_USER` | `root`      |
   | `DB_PASS` | *(empty)*   |

3. **Serve the site.**

   ```bash
   php -S localhost:8000
   ```

4. **Open the pages.**

   - Blog: <http://localhost:8000/blog.php>
   ## CRUD
   - Admin: <http://localhost:8000/admin/index.php> (To Use CRUD functions) 

## Routes

| Page        | URL                                  |
|-------------|--------------------------------------|
| Home        | `/` → redirects to `blog.php`        |
| Blog        | `blog.php`                           |
| Post        | `post.php?id=<slug>`                 |
| Admin list  | `admin/index.php`                    |
| New post    | `admin/create.php`                   |
| Edit post   | `admin/edit.php?id=<id>`             |

## Database

Single table, `posts`:

| Column          | Type          | Notes                             |
|-----------------|---------------|-----------------------------------|
| `id`            | INT UNSIGNED  | Primary key, auto-increment       |
| `slug`          | VARCHAR(191)  | Unique, used in URLs              |
| `title`         | VARCHAR(255)  |                                   |
| `category`      | VARCHAR(100)  | One of the categories below       |
| `hero_img`      | VARCHAR(255)  | Image path; falls back to gradient|
| `hero_emoji`    | VARCHAR(32)   | Legacy, unused                    |
| `hero_gradient` | VARCHAR(255)  | CSS gradient for the hero         |
| `author`        | VARCHAR(120)  |                                   |
| `avatar`        | VARCHAR(32)   | Legacy, unused                    |
| `post_date`     | DATE          |                                   |
| `read_time`     | INT UNSIGNED  | Minutes                           |
| `views`         | INT UNSIGNED  | Read count                        |
| `tags`          | TEXT          | JSON array                        |
| `toc`           | TEXT          | JSON array of section titles      |
| `content`       | LONGTEXT      | Raw HTML (trusted admin input)    |
| `created_at`    | TIMESTAMP     |                                   |
| `updated_at`    | TIMESTAMP     |                                   |

### Categories

Defined in `lib/posts.php` (`POST_CATEGORIES`). Each has an accent colour:

`Tutorials`, `Reviews`, `Technical`, `Tips & Tricks`, `AI & IoT`, `Projects`.

## Notes

- Post content is stored and rendered as raw HTML, so only trusted administrators should use the admin area.
- Slugs are auto-generated from the title when left blank and de-duplicated automatically.
- Tags and table-of-contents entries are entered one per line in the admin form and stored as JSON.
- When `hero_img` is empty the hero shows the brand logo over the stored CSS gradient.
