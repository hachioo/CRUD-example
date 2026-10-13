<?php

require_once __DIR__ . '/../config/database.php';

/* ══════════════════════════════════════════
   POSTS REPOSITORY
   All database access for the posts table.
══════════════════════════════════════════ */

const POST_CATEGORIES = [
    'Tutorials'     => '#c8410f',
    'Reviews'       => '#1a6b4a',
    'Technical'     => '#2563a8',
    'Tips & Tricks' => '#7c3aed',
    'AI & IoT'      => '#0891b2',
    'Projects'      => '#b45309',
];

function post_category_color(string $cat): string
{
    return POST_CATEGORIES[$cat] ?? '#7a7068';
}

function post_format_date(?string $date): string
{
    $ts = $date ? strtotime($date) : false;
    return $ts ? date('F j, Y', $ts) : (string) $date;
}

function post_decode_list(?string $json): array
{
    if (!$json) {
        return [];
    }
    $arr = json_decode($json, true);
    return is_array($arr) ? $arr : [];
}

/* Map a raw DB row to the shape used by the templates. */
function post_from_row(array $r): array
{
    return [
        'id'           => (int) $r['id'],
        'slug'         => $r['slug'],
        'title'        => $r['title'],
        'cat'          => $r['category'],
        'catColor'     => post_category_color($r['category']),
        'heroImg'      => (string) ($r['hero_img'] ?? ''),
        'heroEmoji'    => (string) ($r['hero_emoji'] ?? ''),
        'heroGradient' => (string) ($r['hero_gradient'] ?? ''),
        'author'       => $r['author'],
        'avatar'       => (string) ($r['avatar'] ?? ''),
        'date'         => post_format_date($r['post_date']),
        'dateISO'      => $r['post_date'],
        'time'         => (string) (int) $r['read_time'],
        'views'        => number_format((int) $r['views']),
        'viewsRaw'     => (int) $r['views'],
        'tags'         => post_decode_list($r['tags']),
        'toc'          => post_decode_list($r['toc']),
        'content'      => (string) ($r['content'] ?? ''),
    ];
}

function post_excerpt(array $p, int $length = 160): string
{
    $text = trim(preg_replace('/\s+/', ' ', strip_tags($p['content'] ?? '')));
    if (function_exists('mb_strlen')) {
        return mb_strlen($text) > $length ? mb_substr($text, 0, $length) . '…' : $text;
    }
    return strlen($text) > $length ? substr($text, 0, $length) . '…' : $text;
}

/* ══ READ ══ */

function all_posts(): array
{
    $rows = db()->query('SELECT * FROM posts ORDER BY post_date DESC, id DESC')->fetchAll();
    return array_map('post_from_row', $rows);
}

function find_post(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM posts WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ? post_from_row($row) : null;
}

function find_post_by_slug(string $slug): ?array
{
    $stmt = db()->prepare('SELECT * FROM posts WHERE slug = ?');
    $stmt->execute([$slug]);
    $row = $stmt->fetch();
    return $row ? post_from_row($row) : null;
}

function related_posts(string $category, string $slug, int $limit = 3): array
{
    $stmt = db()->prepare(
        'SELECT * FROM posts WHERE category = ? AND slug <> ?
         ORDER BY post_date DESC, id DESC LIMIT ' . (int) $limit
    );
    $stmt->execute([$category, $slug]);
    return array_map('post_from_row', $stmt->fetchAll());
}

/* ══ HELPERS ══ */

function post_slugify(string $title): string
{
    $s = strtolower(trim($title));
    $s = preg_replace('/[^a-z0-9]+/', '-', $s);
    $s = trim((string) $s, '-');
    return $s !== '' ? $s : 'post';
}

function post_unique_slug(string $slug, ?int $excludeId = null): string
{
    $base = $slug;
    $i = 1;

    while (true) {
        $sql  = 'SELECT COUNT(*) FROM posts WHERE slug = ?' . ($excludeId ? ' AND id <> ?' : '');
        $stmt = db()->prepare($sql);
        $stmt->execute($excludeId ? [$slug, $excludeId] : [$slug]);

        if ((int) $stmt->fetchColumn() === 0) {
            return $slug;
        }
        $slug = $base . '-' . (++$i);
    }
}

/* Read form input into a normalized array. */
function post_collect_input(): array
{
    $toLines = static function ($value): array {
        $parts = preg_split('/\r\n|\r|\n/', (string) $value);
        $parts = array_map('trim', $parts ?: []);
        return array_values(array_filter($parts, static fn ($v) => $v !== ''));
    };

    return [
        'slug'          => trim((string) ($_POST['slug'] ?? '')),
        'title'         => trim((string) ($_POST['title'] ?? '')),
        'category'      => trim((string) ($_POST['category'] ?? 'Tutorials')),
        'hero_img'      => trim((string) ($_POST['hero_img'] ?? '')),
        'hero_emoji'    => trim((string) ($_POST['hero_emoji'] ?? '')),
        'hero_gradient' => trim((string) ($_POST['hero_gradient'] ?? '')),
        'author'        => trim((string) ($_POST['author'] ?? '')),
        'avatar'        => trim((string) ($_POST['avatar'] ?? '')),
        'post_date'     => trim((string) ($_POST['post_date'] ?? '')) ?: date('Y-m-d'),
        'read_time'     => max(0, (int) ($_POST['read_time'] ?? 5)),
        'views'         => max(0, (int) ($_POST['views'] ?? 0)),
        'tags'          => $toLines($_POST['tags'] ?? ''),
        'toc'           => $toLines($_POST['toc'] ?? ''),
        'content'       => (string) ($_POST['content'] ?? ''),
    ];
}

function post_bind(array $d): array
{
    return [
        ':slug'          => $d['slug'],
        ':title'         => $d['title'],
        ':category'      => $d['category'],
        ':hero_img'      => $d['hero_img'] !== '' ? $d['hero_img'] : null,
        ':hero_emoji'    => $d['hero_emoji'] !== '' ? $d['hero_emoji'] : null,
        ':hero_gradient' => $d['hero_gradient'] !== '' ? $d['hero_gradient'] : null,
        ':author'        => $d['author'] !== '' ? $d['author'] : 'VoltCore',
        ':avatar'        => $d['avatar'] !== '' ? $d['avatar'] : null,
        ':post_date'     => $d['post_date'],
        ':read_time'     => $d['read_time'],
        ':views'         => $d['views'],
        ':tags'          => json_encode($d['tags'], JSON_UNESCAPED_UNICODE),
        ':toc'           => json_encode($d['toc'], JSON_UNESCAPED_UNICODE),
        ':content'       => $d['content'],
    ];
}

/* ══ WRITE ══ */

function create_post(array $d): int
{
    $stmt = db()->prepare(
        'INSERT INTO posts
            (slug, title, category, hero_img, hero_emoji, hero_gradient,
             author, avatar, post_date, read_time, views, tags, toc, content)
         VALUES
            (:slug, :title, :category, :hero_img, :hero_emoji, :hero_gradient,
             :author, :avatar, :post_date, :read_time, :views, :tags, :toc, :content)'
    );
    $stmt->execute(post_bind($d));

    return (int) db()->lastInsertId();
}

function update_post(int $id, array $d): void
{
    $sql = 'UPDATE posts SET
                slug = :slug, title = :title, category = :category,
                hero_img = :hero_img, hero_emoji = :hero_emoji, hero_gradient = :hero_gradient,
                author = :author, avatar = :avatar, post_date = :post_date,
                read_time = :read_time, views = :views, tags = :tags, toc = :toc, content = :content
            WHERE id = :id';

    $bind = post_bind($d);
    $bind[':id'] = $id;

    db()->prepare($sql)->execute($bind);
}

function delete_post(int $id): void
{
    db()->prepare('DELETE FROM posts WHERE id = ?')->execute([$id]);
}
