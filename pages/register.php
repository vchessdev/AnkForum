<?php // pages/register.php ?>
<div class="min-h-screen flex items-center justify-center px-4 py-8 relative overflow-hidden">

    <div class="absolute top-1/4 right-1/3 w-80 h-80 bg-brand-600/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/3 left-1/4 w-64 h-64 bg-purple-600/10 rounded-full blur-3xl pointer-events-none"></div>

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
            <p class="text-slate-400 text-sm">Tạo tài khoản miễn phí ngay hôm nay</p>
        </div>

        <div class="auth-card animate-slide-up">
            <h1 class="text-2xl font-bold text-white mb-1">Tạo tài khoản</h1>
            <p class="text-slate-400 text-sm mb-7">Tham gia cộng đồng AnkForum ngay!</p>

            <div id="error-box" class="hidden mb-5 p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm"></div>

            <form id="register-form" novalidate>
                <!-- Username -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Tên đăng nhập</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </span>
                        <input type="text" name="username" id="username-input"
                            class="input-field pl-10" placeholder="ví dụ: ankuser123"
                            autocomplete="username" maxlength="30" required>
                        <span id="username-check" class="absolute right-3 top-1/2 -translate-y-1/2 hidden"></span>
                    </div>
                    <p class="text-xs text-red-400 mt-1 hidden" id="err-username"></p>
                    <p class="text-xs text-slate-500 mt-1">3-30 ký tự, chỉ dùng chữ cái, số và dấu _</p>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Email</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </span>
                        <input type="email" name="email" id="email-input"
                            class="input-field pl-10" placeholder="email@example.com"
                            autocomplete="email" required>
                    </div>
                    <p class="text-xs text-red-400 mt-1 hidden" id="err-email"></p>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Mật khẩu</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                        <input type="password" name="password" id="password-input"
                            class="input-field pl-10 pr-10" placeholder="Tối thiểu 6 ký tự"
                            autocomplete="new-password" required>
                        <button type="button" onclick="togglePass('password-input')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    <!-- Strength bar -->
                    <div class="mt-2 space-y-1">
                        <div class="flex gap-1">
                            <div class="h-1 flex-1 rounded-full bg-slate-700 overflow-hidden"><div id="s1" class="h-full rounded-full transition-all duration-300 w-0"></div></div>
                            <div class="h-1 flex-1 rounded-full bg-slate-700 overflow-hidden"><div id="s2" class="h-full rounded-full transition-all duration-300 w-0"></div></div>
                            <div class="h-1 flex-1 rounded-full bg-slate-700 overflow-hidden"><div id="s3" class="h-full rounded-full transition-all duration-300 w-0"></div></div>
                            <div class="h-1 flex-1 rounded-full bg-slate-700 overflow-hidden"><div id="s4" class="h-full rounded-full transition-all duration-300 w-0"></div></div>
                        </div>
                        <p id="strength-label" class="text-xs text-slate-500"></p>
                    </div>
                    <p class="text-xs text-red-400 mt-1 hidden" id="err-password"></p>
                </div>

                <!-- Confirm password -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Xác nhận mật khẩu</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </span>
                        <input type="password" name="confirm_password" id="confirm-input"
                            class="input-field pl-10" placeholder="Nhập lại mật khẩu"
                            autocomplete="new-password" required>
                    </div>
                    <p class="text-xs text-red-400 mt-1 hidden" id="err-confirm"></p>
                </div>

                <button type="submit" id="submit-btn" class="btn-primary w-full h-12 text-base relative">
                    <span id="btn-text">Tạo tài khoản</span>
                    <span id="btn-loader" class="hidden absolute inset-0 flex items-center justify-center">
                        <svg class="animate-spin w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83"/></svg>
                    </span>
                </button>

                <p class="text-center text-xs text-slate-500 mt-4">
                    Bằng cách đăng ký, bạn đồng ý với
                    <span class="text-brand-400">Điều khoản sử dụng</span> của chúng tôi.
                </p>
            </form>

            <div class="divider my-6">hoặc</div>
            <p class="text-center text-sm text-slate-400">
                Đã có tài khoản?
                <a href="/?page=login" class="text-brand-400 font-semibold hover:text-brand-300 transition-colors ml-1">Đăng nhập</a>
            </p>
        </div>
    </div>
</div>

<script>
function togglePass(id) {
    const inp = document.getElementById(id);
    inp.type = inp.type === 'password' ? 'text' : 'password';
}

(function() {
    // Password strength
    document.getElementById('password-input').addEventListener('input', function() {
        const val = this.value;
        let score = 0;
        if (val.length >= 6) score++;
        if (val.length >= 10) score++;
        if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
        if (/[0-9]/.test(val) && /[^a-zA-Z0-9]/.test(val)) score++;

        const colors = ['', '#ef4444', '#f59e0b', '#22d3ee', '#22c55e'];
        const labels = ['', 'Yếu', 'Trung bình', 'Tốt', 'Mạnh'];
        for (let i = 1; i <= 4; i++) {
            const bar = document.getElementById('s' + i);
            bar.style.width = i <= score ? '100%' : '0';
            bar.style.background = i <= score ? colors[score] : '';
        }
        const lbl = document.getElementById('strength-label');
        lbl.textContent = val ? 'Độ mạnh: ' + (labels[score] || 'Rất yếu') : '';
        lbl.style.color = colors[score] || '#64748b';
    });

    // Form submit
    const form = document.getElementById('register-form');
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Clear errors
        ['username','email','password','confirm'].forEach(f => {
            const el = document.getElementById('err-' + f);
            if (el) { el.textContent = ''; el.classList.add('hidden'); }
        });
        document.getElementById('error-box').classList.add('hidden');

        const data = {
            username:         document.getElementById('username-input').value.trim(),
            email:            document.getElementById('email-input').value.trim(),
            password:         document.getElementById('password-input').value,
            confirm_password: document.getElementById('confirm-input').value,
        };

        const btn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const btnLoader = document.getElementById('btn-loader');
        btn.disabled = true;
        btnText.classList.add('hidden');
        btnLoader.classList.remove('hidden');

        try {
            const res = await fetch('/api/auth/register.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify(data)
            });
            const result = await res.json();

            if (result.success) {
                btnText.textContent = '✓ Thành công!';
                btnLoader.classList.add('hidden');
                btnText.classList.remove('hidden');
                setTimeout(() => location.href = '/', 800);
            } else if (result.errors) {
                Object.entries(result.errors).forEach(([field, msg]) => {
                    const key = field === 'confirm_password' ? 'confirm' : field;
                    const el = document.getElementById('err-' + key);
                    if (el) { el.textContent = msg; el.classList.remove('hidden'); }
                });
                btn.disabled = false;
                btnText.classList.remove('hidden');
                btnLoader.classList.add('hidden');
            } else {
                document.getElementById('error-box').textContent = result.error || 'Đã xảy ra lỗi';
                document.getElementById('error-box').classList.remove('hidden');
                btn.disabled = false;
                btnText.classList.remove('hidden');
                btnLoader.classList.add('hidden');
            }
        } catch(err) {
            document.getElementById('error-box').textContent = 'Lỗi kết nối';
            document.getElementById('error-box').classList.remove('hidden');
            btn.disabled = false;
            btnText.classList.remove('hidden');
            btnLoader.classList.add('hidden');
        }
    });
})();
</script>
