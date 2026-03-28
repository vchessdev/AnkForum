#!/bin/bash
# ============================================================
# AnkForum - fix-permissions.sh
# Chạy script này nếu gặp lỗi upload hoặc lỗi ghi file
# Usage: bash fix-permissions.sh
# ============================================================

echo "🔧 Đang sửa quyền thư mục AnkForum..."

# Lấy đường dẫn thư mục hiện tại
DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Thư mục cần ghi được
chmod 755 "$DIR"
chmod -R 777 "$DIR/data"
chmod -R 777 "$DIR/uploads"
chmod -R 777 "$DIR/uploads/avatars"
chmod -R 777 "$DIR/uploads/banners"
chmod -R 777 "$DIR/uploads/posts"

# Tạo thư mục nếu chưa có
mkdir -p "$DIR/uploads/avatars"
mkdir -p "$DIR/uploads/banners"
mkdir -p "$DIR/uploads/posts"

# File JSON phải ghi được
touch "$DIR/data/users.json"
touch "$DIR/data/posts.json"
touch "$DIR/data/comments.json"
touch "$DIR/data/notifications.json"
chmod 666 "$DIR/data"/*.json

echo "✅ Hoàn thành!"
echo ""
echo "Kiểm tra:"
ls -la "$DIR/data/"
ls -la "$DIR/uploads/"
