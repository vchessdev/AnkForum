# AnkForum 🚀

> Mạng xã hội & diễn đàn cộng đồng | ankb.work.gd

## ✨ Tính năng

- 🔐 Đăng ký / Đăng nhập / Đăng xuất với session PHP
- 👤 Trang cá nhân với avatar, banner, bio, hệ thống cấp độ
- 📝 Đăng bài với text + media (ảnh, video, file tới 500MB)
- ❤️ Like bài viết & bình luận
- 💬 Bình luận & trả lời lồng nhau (AJAX)
- 🔔 Thông báo real-time (AJAX polling 5s)
- 🏆 Hệ thống điểm & 10 cấp độ
- 👥 Follow / Unfollow người dùng
- 🔍 Tìm kiếm người dùng & bài viết
- 🌙 Dark mode
- 🎨 UI Glassmorphism hiện đại
- 📱 Responsive (mobile / tablet / desktop)
- ♾️ Infinite scroll

---

## 🛠️ Cài đặt

### Yêu cầu
- PHP 8.1+
- Apache với mod_rewrite (hoặc Nginx)
- PHP extensions: fileinfo, json, session

### Bước 1 — Upload files
```bash
# Upload toàn bộ thư mục ankforum lên server
# Ví dụ: /var/www/ankforum/
```

### Bước 2 — Phân quyền thư mục
```bash
chmod -R 755 /var/www/ankforum
chmod -R 777 /var/www/ankforum/data
chmod -R 777 /var/www/ankforum/uploads
```

### Bước 3 — Cấu hình domain

**Apache** — đã có `.htaccess`, chỉ cần bật mod_rewrite:
```bash
a2enmod rewrite
systemctl restart apache2
```

**Nginx** — dùng file `nginx.conf` đã cung cấp:
```bash
cp nginx.conf /etc/nginx/sites-available/ankforum
ln -s /etc/nginx/sites-available/ankforum /etc/nginx/sites-enabled/
nginx -t && systemctl reload nginx
```

### Bước 4 — Cấu hình php.ini
```ini
upload_max_filesize = 500M
post_max_size = 512M
max_execution_time = 300
memory_limit = 256M
session.gc_maxlifetime = 604800
```

### Bước 5 — Cài đặt trong config.php
```php
// Mở file config.php và chỉnh sửa:
define('APP_URL', 'https://ankb.work.gd');  // Domain của bạn
date_default_timezone_set('Asia/Ho_Chi_Minh');
```

---

## 📁 Cấu trúc thư mục

```
ankforum/
├── index.php           # Entry point
├── router.php          # URL routing
├── config.php          # Cấu hình global
├── helpers.php         # Utility functions
├── .htaccess           # Apache config
├── nginx.conf          # Nginx config
│
├── api/                # Backend API endpoints
│   ├── auth/
│   │   ├── login.php
│   │   ├── register.php
│   │   └── logout.php
│   ├── posts/
│   │   ├── feed.php
│   │   ├── create.php
│   │   ├── like.php
│   │   └── delete.php
│   ├── comments/
│   │   ├── list.php
│   │   ├── add.php
│   │   ├── like.php
│   │   └── delete.php
│   ├── users/
│   │   ├── profile.php
│   │   ├── follow.php
│   │   ├── followers.php
│   │   └── update.php
│   ├── notifications/
│   │   ├── list.php
│   │   ├── unread-count.php
│   │   └── mark-read.php
│   └── search.php
│
├── data/               # JSON storage (bảo mật: không public)
│   ├── users.json
│   ├── posts.json
│   ├── comments.json
│   └── notifications.json
│
├── uploads/            # Media uploads
│   ├── avatars/
│   ├── banners/
│   └── posts/
│
├── assets/
│   ├── css/app.css     # Glassmorphism styles
│   ├── js/
│   │   ├── app.js      # Toast, Modal, Lightbox, Dark mode
│   │   ├── ajax.js     # AJAX wrapper
│   │   └── notifications.js  # Real-time polling
│   ├── images/
│   └── sounds/
│
├── components/         # Reusable PHP components
│   ├── navbar.php
│   ├── sidebar-left.php
│   ├── sidebar-right.php
│   └── post-card.php
│
└── pages/              # Page views
    ├── home.php
    ├── login.php
    ├── register.php
    ├── profile.php
    ├── post.php
    ├── notifications.php
    └── settings.php
```

---

## 🏆 Hệ thống điểm & cấp độ

| Hành động      | Điểm |
|---------------|------|
| Đăng bài       | +10  |
| Bình luận      | +5   |
| Nhận like      | +2   |
| Có người follow| +3   |

| Cấp | Tên       | Điểm cần |
|-----|-----------|----------|
| 1   | 🌱 Newbie   | 0        |
| 2   | ⭐ Member   | 50       |
| 3   | 🔥 Active   | 150      |
| 4   | 💎 Regular  | 350      |
| 5   | 🏆 Veteran  | 700      |
| 6   | 🚀 Expert   | 1,200    |
| 7   | 👑 Elite    | 2,000    |
| 8   | ⚡ Legend   | 3,500    |
| 9   | 🌟 Master   | 6,000    |
| 10  | 🔱 God      | 10,000   |

---

## 🔐 Bảo mật

- Mật khẩu hash với `password_hash()` (bcrypt cost 12)
- CSRF token trên mọi request AJAX
- Sanitize toàn bộ input với `htmlspecialchars()`
- Thư mục `/data/` được chặn public access
- Session regenerate định kỳ
- Validate MIME type cho file upload
- Atomic write cho JSON (tránh race condition)

---

## 📞 Hỗ trợ

Website: [ankb.work.gd](https://ankb.work.gd)

---

*Made with ❤️ — AnkForum v1.0*
