-- ══════════════════════════════════════════
--  VoltCore Blog – database schema
--  Import with:  mysql -u root -p < db/schema.sql
--  (or paste into phpMyAdmin)
-- ══════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS voltcore
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE voltcore;

DROP TABLE IF EXISTS posts;

CREATE TABLE posts (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  slug          VARCHAR(191) NOT NULL,
  title         VARCHAR(255) NOT NULL,
  category      VARCHAR(100) NOT NULL DEFAULT 'Tutorials',
  hero_img      VARCHAR(255)     DEFAULT NULL,
  hero_emoji    VARCHAR(32)      DEFAULT NULL,
  hero_gradient VARCHAR(255)     DEFAULT NULL,
  author        VARCHAR(120) NOT NULL DEFAULT 'VoltCore',
  avatar        VARCHAR(32)      DEFAULT NULL,
  post_date     DATE         NOT NULL,
  read_time     INT UNSIGNED NOT NULL DEFAULT 5,
  views         INT UNSIGNED NOT NULL DEFAULT 0,
  tags          TEXT             DEFAULT NULL,
  toc           TEXT             DEFAULT NULL,
  content       LONGTEXT         DEFAULT NULL,
  created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_posts_slug (slug),
  KEY idx_posts_category (category),
  KEY idx_posts_date (post_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
