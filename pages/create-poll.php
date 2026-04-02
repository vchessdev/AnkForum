<?php
// ============================================================
// AnkForum - pages/create-poll.php
// Create a poll (standalone or attached to post)
// ============================================================

$postId = $_GET['post_id'] ?? null;
?>

<div class="min-h-screen bg-gradient-to-b from-slate-900 via-slate-950 to-slate-900">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Tạo Poll</h1>
                <p class="text-slate-400">Hỏi cộng đồng và nhận phản hồi từ các thành viên</p>
            </div>

            <!-- Form -->
            <div class="bg-slate-800 border border-slate-700 rounded-lg p-8">
                <form id="create-poll-form" onsubmit="handleCreatePoll(event)">
                    <!-- Title -->
                    <div class="mb-6">
                        <label class="block text-white font-semibold mb-2">Câu hỏi *</label>
                        <input type="text" name="title" placeholder="Viết câu hỏi của bạn..."
                               class="w-full bg-slate-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500"
                               required maxlength="200">
                        <p class="text-xs text-slate-400 mt-1">Tối đa 200 ký tự</p>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label class="block text-white font-semibold mb-2">Mô tả (tùy chọn)</label>
                        <textarea name="description" placeholder="Thêm mô tả chi tiết..."
                                  class="w-full bg-slate-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500"
                                  rows="3" maxlength="500"></textarea>
                        <p class="text-xs text-slate-400 mt-1">Tối đa 500 ký tự</p>
                    </div>

                    <!-- Options -->
                    <div class="mb-6">
                        <label class="block text-white font-semibold mb-3">Lựa chọn *</label>
                        <div id="poll-options-container" class="space-y-3 mb-4">
                            <input type="text" placeholder="Lựa chọn 1" 
                                   class="poll-option-input w-full bg-slate-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500"
                                   required maxlength="100">
                            <input type="text" placeholder="Lựa chọn 2" 
                                   class="poll-option-input w-full bg-slate-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500"
                                   required maxlength="100">
                        </div>
                        <button type="button" onclick="addPollOption()"
                                class="text-sm text-brand-400 hover:text-brand-300 font-semibold">
                            + Thêm lựa chọn
                        </button>
                        <p class="text-xs text-slate-400 mt-2">Tối thiểu 2 lựa chọn, tối đa 10</p>
                    </div>

                    <!-- Settings -->
                    <div class="mb-6 p-4 bg-slate-700/50 rounded-lg space-y-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="multiple_choice" id="multiple_choice"
                                   class="w-4 h-4 rounded">
                            <span class="text-white text-sm">Cho phép chọn nhiều lựa chọn</span>
                        </label>

                        <div>
                            <label class="block text-white text-sm font-semibold mb-2">Hết hạn sau (tùy chọn)</label>
                            <select name="expires_after" class="w-full bg-slate-600 text-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500">
                                <option value="">Không giới hạn</option>
                                <option value="1h">1 giờ</option>
                                <option value="6h">6 giờ</option>
                                <option value="1d">1 ngày</option>
                                <option value="7d">7 ngày</option>
                                <option value="30d">30 ngày</option>
                            </select>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4">
                        <button type="submit"
                                class="flex-1 px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-lg transition">
                            Tạo Poll
                        </button>
                        <button type="button" onclick="window.history.back()"
                                class="flex-1 px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white font-semibold rounded-lg transition">
                            Hủy
                        </button>
                    </div>

                    <!-- Hidden fields -->
                    <input type="hidden" name="csrf_token" value="<?php echo csrfToken(); ?>">
                    <?php if ($postId): ?>
                        <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($postId); ?>">
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function addPollOption() {
    const container = document.getElementById('poll-options-container');
    const inputs = container.querySelectorAll('input');
    
    if (inputs.length >= 10) {
        showMessage('Tối đa 10 lựa chọn', 'error');
        return;
    }
    
    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'poll-option-input w-full bg-slate-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500';
    input.placeholder = `Lựa chọn ${inputs.length + 1}`;
    input.maxLength = '100';
    input.required = true;
    
    container.appendChild(input);
}

function handleCreatePoll(e) {
    e.preventDefault();
    
    const form = e.target;
    const title = form.title.value.trim();
    const description = form.description.value.trim();
    const multipleChoice = form.multiple_choice?.checked || false;
    const expiresAfter = form.expires_after?.value;
    
    // Get options
    const options = Array.from(document.querySelectorAll('.poll-option-input'))
        .map(input => input.value.trim())
        .filter(val => val);
    
    if (options.length < 2) {
        showMessage('Phải có ít nhất 2 lựa chọn', 'error');
        return;
    }
    
    if (options.length > 10) {
        showMessage('Tối đa 10 lựa chọn', 'error');
        return;
    }
    
    // Calculate expires_at
    let expiresAt = null;
    if (expiresAfter) {
        const now = new Date();
        const duration = {
            '1h': 3600,
            '6h': 6 * 3600,
            '1d': 24 * 3600,
            '7d': 7 * 24 * 3600,
            '30d': 30 * 24 * 3600
        }[expiresAfter];
        
        if (duration) {
            expiresAt = new Date(now.getTime() + duration * 1000).toISOString();
        }
    }
    
    const payload = {
        title,
        description,
        options,
        multiple_choice: multipleChoice,
        expires_at: expiresAt,
        csrf_token: form.csrf_token.value
    };
    
    if (form.post_id) {
        payload.post_id = form.post_id.value;
    }
    
    fetch('/api/polls/create.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showMessage('Poll được tạo thành công', 'success');
            setTimeout(() => {
                window.location.href = '/?page=home';
            }, 1500);
        } else {
            showMessage(data.error || 'Lỗi tạo poll', 'error');
        }
    })
    .catch(e => showMessage('Lỗi: ' + e.message, 'error'));
}
</script>
