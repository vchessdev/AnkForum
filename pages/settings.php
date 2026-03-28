<?php
// pages/settings.php
$user = currentUser();
?>
<div class="max-w-xl mx-auto animate-fade-in">

    <h1 class="text-2xl font-black text-white mb-6 flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-brand-500/20 flex items-center justify-center">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
        </div>
        Cài đặt tài khoản
    </h1>

    <!-- Profile info section -->
    <div class="glass-card p-6 mb-4">
        <h2 class="text-base font-bold text-white mb-5 pb-3 border-b border-white/5">
            Thông tin cá nhân
        </h2>

        <!-- Avatar preview -->
        <div class="flex items-center gap-4 mb-6">
            <div class="relative group">
                <img id="avatar-preview"
                    src="<?php echo avatarUrl($user['avatar']); ?>"
                    class="avatar w-16 h-16">
                <label for="settings-avatar" class="absolute inset-0 flex items-center justify-center bg-black/60 rounded-full opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                </label>
                <input type="file" id="settings-avatar" accept="image/*" class="hidden" onchange="previewAndUploadAvatar(this)">
            </div>
            <div>
                <p class="font-semibold text-white"><?php echo sanitize($user['username']); ?></p>
                <p class="text-xs text-slate-500">Nhấn vào ảnh để thay đổi</p>
            </div>
        </div>

        <!-- Display name -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-300 mb-2">Tên hiển thị</label>
            <input type="text" id="display-name-input" class="input-field"
                value="<?php echo sanitize($user['display_name'] ?? $user['username']); ?>"
                placeholder="Tên hiển thị..." maxlength="50">
        </div>

        <!-- Bio -->
        <div class="mb-5">
            <label class="block text-sm font-medium text-slate-300 mb-2">
                Bio
                <span class="text-slate-500 font-normal ml-1">(tối đa 300 ký tự)</span>
            </label>
            <textarea id="bio-input" class="input-field auto-resize" rows="3"
                placeholder="Giới thiệu về bản thân..." maxlength="300"
                data-max-length="300" data-counter-id="bio-counter"><?php echo sanitize($user['bio'] ?? ''); ?></textarea>
            <div class="flex justify-end mt-1">
                <span id="bio-counter" class="text-xs text-muted"><?php echo 300 - mb_strlen($user['bio'] ?? ''); ?></span>
            </div>
        </div>

        <button onclick="saveProfile()" class="btn-primary w-full" id="save-profile-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            Lưu thay đổi
        </button>
    </div>

    <!-- Change password section -->
    <div class="glass-card p-6 mb-4">
        <h2 class="text-base font-bold text-white mb-5 pb-3 border-b border-white/5">
            Đổi mật khẩu
        </h2>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Mật khẩu hiện tại</label>
                <input type="password" id="current-pass" class="input-field" placeholder="••••••••">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Mật khẩu mới</label>
                <input type="password" id="new-pass" class="input-field" placeholder="Tối thiểu 6 ký tự, có chữ và số">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Xác nhận mật khẩu mới</label>
                <input type="password" id="confirm-pass" class="input-field" placeholder="Nhập lại mật khẩu mới">
            </div>
        </div>
        <button onclick="changePassword()" class="btn-secondary w-full mt-5">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Đổi mật khẩu
        </button>
    </div>

    <!-- Preferences -->
    <div class="glass-card p-6 mb-4">
        <h2 class="text-base font-bold text-white mb-5 pb-3 border-b border-white/5">
            Tùy chỉnh giao diện
        </h2>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-200">Chế độ tối</p>
                    <p class="text-xs text-slate-500">Bật/tắt dark mode</p>
                </div>
                <button onclick="DarkMode.toggle()"
                    class="w-12 h-6 bg-brand-600 rounded-full relative transition-all" id="darkmode-toggle">
                    <span class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-transform translate-x-6"></span>
                </button>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-200">Âm thanh thông báo</p>
                    <p class="text-xs text-slate-500">Phát âm khi có thông báo mới</p>
                </div>
                <button onclick="Notifications?.toggleSound()"
                    class="w-12 h-6 bg-brand-600 rounded-full relative transition-all" id="sound-toggle">
                    <span class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-transform translate-x-6"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Danger zone -->
    <div class="glass-card p-6 border border-red-500/20">
        <h2 class="text-base font-bold text-red-400 mb-3">Vùng nguy hiểm</h2>
        <p class="text-sm text-slate-400 mb-4">Đăng xuất khỏi tất cả thiết bị</p>
        <button onclick="doLogout()" class="btn-danger">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Đăng xuất
        </button>
    </div>
</div>

<script>
async function saveProfile() {
    const btn = document.getElementById('save-profile-btn');
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83"/></svg> Đang lưu...';

    const fd = new FormData();
    fd.append('bio', document.getElementById('bio-input').value);
    fd.append('display_name', document.getElementById('display-name-input').value);

    try {
        const res = await API.post('/api/users/update.php', fd);
        Toast.success(res.message || 'Đã lưu thành công!');
    } catch(e) {
        Toast.error(e.error || 'Lỗi lưu thông tin');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Lưu thay đổi';
    }
}

async function changePassword() {
    const cur  = document.getElementById('current-pass').value;
    const nw   = document.getElementById('new-pass').value;
    const conf = document.getElementById('confirm-pass').value;

    if (!cur || !nw || !conf) { Toast.error('Vui lòng điền đầy đủ'); return; }
    if (nw !== conf) { Toast.error('Mật khẩu xác nhận không khớp'); return; }

    const fd = new FormData();
    fd.append('current_password', cur);
    fd.append('new_password', nw);

    try {
        const res = await API.post('/api/users/update.php', fd);
        Toast.success('Đổi mật khẩu thành công!');
        document.getElementById('current-pass').value = '';
        document.getElementById('new-pass').value = '';
        document.getElementById('confirm-pass').value = '';
    } catch(e) { Toast.error(e.error || 'Lỗi đổi mật khẩu'); }
}

async function previewAndUploadAvatar(input) {
    const file = input.files[0];
    if (!file) return;
    if (!file.type.startsWith('image/')) { Toast.error('Chỉ chấp nhận file ảnh'); return; }

    // Show local preview immediately
    const reader = new FileReader();
    reader.onload = e => document.getElementById('avatar-preview').src = e.target.result;
    reader.readAsDataURL(file);

    Toast.info('Đang upload ảnh đại diện...');
    const fd = new FormData();
    fd.append('avatar', file);

    try {
        const res = await fetch('/api/users/update.php', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: fd
        });
        const data = await res.json();
        if (!res.ok || !data.success) {
            Toast.error(data.error || 'Lỗi upload avatar');
            return;
        }
        document.getElementById('avatar-preview').src = data.user.avatar + '?t=' + Date.now();
        Toast.success('Đã cập nhật ảnh đại diện!');
    } catch(e) {
        Toast.error('Lỗi kết nối khi upload');
    }
    input.value = '';
}

async function doLogout() {
    if (!confirm('Bạn có chắc muốn đăng xuất?')) return;
    try { await API.post('/api/auth/logout.php', {}); } catch(e) {}
    location.href = '/?page=login';
}
</script>
