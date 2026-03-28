<?php // pages/login.php ?>
<div class="min-h-screen flex items-center justify-center px-4 relative overflow-hidden">

    <!-- Ambient background orbs -->
    <div class="absolute top-1/4 left-1/4 w-80 h-80 bg-brand-600/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-purple-600/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">

        <!-- Logo -->
        <div class="text-center mb-8 animate-fade-in">
            <a href="/" class="inline-flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center shadow-lg glow-brand">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <span class="text-2xl font-black gradient-text font-display">AnkForum</span>
            </a>
            <p class="text-slate-400 text-sm">Cộng đồng chia sẻ nội dung hàng đầu</p>
        </div>

        <!-- Card -->
        <div class="auth-card animate-slide-up">
            <h1 class="text-2xl font-bold text-white mb-1">Đăng nhập</h1>
            <p class="text-slate-400 text-sm mb-7">Chào mừng trở lại! Đăng nhập để tiếp tục.</p>

            <!-- Error box -->
            <div id="error-box" class="hidden mb-5 p-4 bg-red-500/10 border border-red-500/20 rounded-14 text-red-400 text-sm" style="border-radius:12px;"></div>

            <form id="login-form" novalidate>
                <!-- Username/Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Tên đăng nhập hoặc Email
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </span>
                        <input type="text" name="login" id="login-input"
                            class="input-field pl-10" placeholder="username hoặc email@..."
                            autocomplete="username" required>
                    </div>
                    <p class="field-error text-xs text-red-400 mt-1 hidden" id="err-login"></p>
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Mật khẩu
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                        <input type="password" name="password" id="password-input"
                            class="input-field pl-10 pr-10" placeholder="••••••••"
                            autocomplete="current-password" required>
                        <button type="button" id="toggle-pass"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors">
                            <svg id="eye-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    <p class="field-error text-xs text-red-400 mt-1 hidden" id="err-password"></p>
                </div>

                <!-- Submit -->
                <button type="submit" id="submit-btn" class="btn-primary w-full h-12 text-base relative">
                    <span id="btn-text">Đăng nhập</span>
                    <span id="btn-loader" class="hidden absolute inset-0 flex items-center justify-center">
                        <svg class="animate-spin w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83"/></svg>
                    </span>
                </button>
            </form>

            <div class="divider my-6">hoặc</div>

            <p class="text-center text-sm text-slate-400">
                Chưa có tài khoản?
                <a href="/?page=register" class="text-brand-400 font-semibold hover:text-brand-300 transition-colors ml-1">
                    Đăng ký ngay
                </a>
            </p>
        </div>

        <p class="text-center text-xs text-slate-600 mt-6">
            © <?php echo date('Y'); ?> AnkForum · ankb.work.gd
        </p>
    </div>
</div>

<script>
(function() {
    const form = document.getElementById('login-form');
    const submitBtn = document.getElementById('submit-btn');
    const btnText = document.getElementById('btn-text');
    const btnLoader = document.getElementById('btn-loader');
    const errorBox = document.getElementById('error-box');

    // Toggle password visibility
    document.getElementById('toggle-pass').addEventListener('click', function() {
        const inp = document.getElementById('password-input');
        inp.type = inp.type === 'password' ? 'text' : 'password';
    });

    function setLoading(loading) {
        submitBtn.disabled = loading;
        btnText.classList.toggle('hidden', loading);
        btnLoader.classList.toggle('hidden', !loading);
    }

    function showError(msg) {
        errorBox.textContent = msg;
        errorBox.classList.remove('hidden');
        errorBox.style.animation = 'none';
        requestAnimationFrame(() => { errorBox.style.animation = 'slideDown 0.3s ease'; });
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        errorBox.classList.add('hidden');

        const login    = document.getElementById('login-input').value.trim();
        const password = document.getElementById('password-input').value;

        if (!login || !password) {
            showError('Vui lòng nhập đầy đủ thông tin');
            return;
        }

        setLoading(true);
        try {
            const res = await fetch('/api/auth/login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ login, password })
            });
            const data = await res.json();

            if (data.success) {
                btnText.textContent = '✓ Thành công!';
                btnLoader.classList.add('hidden');
                setTimeout(() => location.href = '/', 700);
            } else {
                showError(data.error || 'Đăng nhập thất bại');
                setLoading(false);
            }
        } catch (err) {
            showError('Lỗi kết nối, vui lòng thử lại');
            setLoading(false);
        }
    });

    // Enter key
    document.getElementById('password-input').addEventListener('keydown', e => {
        if (e.key === 'Enter') form.dispatchEvent(new Event('submit'));
    });
})();
</script>
