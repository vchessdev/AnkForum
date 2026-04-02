<?php
// ============================================================
// AnkForum - pages/broadcast.php
// Livestream broadcast page (for streamer)
// ============================================================

$user = currentUser();

if (!$user) {
    redirect('/?page=login');
}

// Check if user has active stream
$livestreams = readJson(LIVESTREAMS_FILE);
$activeStream = null;

foreach ($livestreams as $stream) {
    if ($stream['author_id'] === $user['id'] && $stream['status'] === 'live') {
        $activeStream = $stream;
        break;
    }
}
?>

<div class="min-h-screen bg-slate-950">
    <div class="container mx-auto px-4 py-6">
        
        <!-- Start Stream Section -->
        <?php if (!$activeStream): ?>
            <div class="max-w-2xl mx-auto">
                <div class="bg-slate-800 border border-slate-700 rounded-lg p-8">
                    <h1 class="text-2xl font-bold text-white mb-2">Phát trực tiếp</h1>
                    <p class="text-slate-400 mb-8">Chia sẻ khoảnh khắc của bạn với cộng đồng</p>
                    
                    <form id="start-stream-form" onsubmit="handleStartStream(event)">
                        <div class="mb-6">
                            <label class="block text-white font-semibold mb-2">Tiêu đề *</label>
                            <input type="text" name="title" placeholder="Tiêu đề stream..."
                                   class="w-full bg-slate-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500"
                                   required maxlength="200">
                        </div>

                        <div class="mb-6">
                            <label class="block text-white font-semibold mb-2">Mô tả (tùy chọn)</label>
                            <textarea name="description" placeholder="Mô tả chi tiết..."
                                      class="w-full bg-slate-700 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-500"
                                      rows="4" maxlength="500"></textarea>
                        </div>

                        <button type="submit"
                                class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold text-lg rounded-lg transition">
                            🔴 Bắt đầu phát trực tiếp
                        </button>

                        <input type="hidden" name="csrf_token" value="<?php echo csrfToken(); ?>">
                    </form>
                </div>
            </div>
        
        <!-- Active Stream Section -->
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Main Area -->
                <div class="lg:col-span-2">
                    <!-- Stream Preview -->
                    <div class="bg-black rounded-lg overflow-hidden aspect-video mb-6 flex items-center justify-center relative">
                        <svg class="w-24 h-24 text-slate-700" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5h3V9h4v3h3l-5 5z"/>
                        </svg>
                        
                        <!-- Live Badge -->
                        <div class="absolute top-4 right-4 flex items-center gap-2 px-3 py-2 bg-red-600 rounded animate-pulse">
                            <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                            <span class="text-white text-sm font-semibold">LIVE</span>
                        </div>
                        
                        <!-- Stream URL -->
                        <div class="absolute bottom-4 left-4 right-4">
                            <p class="text-xs text-slate-400 mb-1">Chia sẻ link để người khác xem:</p>
                            <div class="flex gap-2">
                                <input type="text" value="<?php echo APP_URL; ?>/?page=livestream&id=<?php echo $activeStream['id']; ?>" 
                                       id="stream-url" readonly
                                       class="flex-1 bg-slate-800 text-white text-xs px-2 py-1 rounded">
                                <button type="button" onclick="copyToClipboard('stream-url')"
                                        class="px-3 py-1 bg-brand-600 text-white text-xs rounded hover:bg-brand-700 transition">
                                    Copy
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-slate-800 rounded-lg p-4 border border-slate-700">
                            <div class="text-slate-400 text-sm mb-1">Người xem</div>
                            <div class="text-2xl font-bold text-white" id="viewer-count">
                                <?php echo number_format($activeStream['viewer_count']); ?>
                            </div>
                        </div>
                        
                        <div class="bg-slate-800 rounded-lg p-4 border border-slate-700">
                            <div class="text-slate-400 text-sm mb-1">Thời gian</div>
                            <div class="text-2xl font-bold text-white" id="stream-duration">
                                00:00
                            </div>
                        </div>
                        
                        <div class="bg-slate-800 rounded-lg p-4 border border-slate-700">
                            <div class="text-slate-400 text-sm mb-1">Bình luận</div>
                            <div class="text-2xl font-bold text-white" id="comment-count">
                                <?php echo number_format(count($activeStream['comments'])); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Controls -->
                    <div class="flex gap-4">
                        <button type="button" id="screen-share-btn" onclick="toggleScreenShare('<?php echo $activeStream['id']; ?>')"
                                class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2">
                            <span id="screen-share-icon">📺</span>
                            <span id="screen-share-text">Chia sẻ màn hình</span>
                        </button>
                        <button type="button" onclick="endStreamNow('<?php echo $activeStream['id']; ?>')"
                                class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
                            Kết thúc Stream
                        </button>
                    </div>
                </div>

                <!-- Chat -->
                <div class="bg-slate-800 rounded-lg overflow-hidden flex flex-col h-[600px]">
                    <div class="p-4 border-b border-slate-700">
                        <h2 class="font-semibold text-white">Chat</h2>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto p-4 space-y-2" id="stream-chat">
                        <?php foreach (array_slice($activeStream['comments'], -20) as $comment): ?>
                            <div class="text-xs">
                                <span class="font-semibold text-brand-400"><?php echo htmlspecialchars($comment['username']); ?></span>
                                <p class="text-slate-300"><?php echo htmlspecialchars($comment['text']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="p-4 border-t border-slate-700">
                        <input type="text" id="stream-chat-input" placeholder="Nói gì đó..."
                               class="w-full bg-slate-700 text-white text-sm rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function handleStartStream(e) {
    e.preventDefault();
    
    const form = e.target;
    const title = form.title.value.trim();
    const description = form.description.value.trim();
    
    fetch('/api/livestreams/start.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            title,
            description,
            csrf_token: form.csrf_token.value
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            showMessage(data.error || 'Lỗi', 'error');
        }
    })
    .catch(e => showMessage('Lỗi: ' + e.message, 'error'));
}

function endStreamNow(streamId) {
    if (!confirm('Bạn chắc chắn muốn kết thúc stream?')) return;
    
    fetch('/api/livestreams/end.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            stream_id: streamId,
            csrf_token: '<?php echo csrfToken(); ?>'
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showMessage('Stream đã kết thúc', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showMessage(data.error || 'Lỗi', 'error');
        }
    })
    .catch(e => showMessage('Lỗi: ' + e.message, 'error'));
}

function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    document.execCommand('copy');
    showMessage('Đã copy link', 'success');
}

// Auto-update stats every 3 seconds
<?php if ($activeStream): ?>
setInterval(() => {
    fetch('/api/livestreams/get.php?id=<?php echo $activeStream['id']; ?>')
    .then(r => r.json())
    .then(data => {
        if (data.stream) {
            document.getElementById('viewer-count').textContent = 
                new Intl.NumberFormat('vi-VN').format(data.stream.viewer_count);
            document.getElementById('comment-count').textContent = 
                new Intl.NumberFormat('vi-VN').format(data.stream.comments.length);
        }
    });
}, 3000);

// Update duration
let startTime = new Date('<?php echo $activeStream['started_at']; ?>').getTime();
setInterval(() => {
    const elapsed = Math.floor((Date.now() - startTime) / 1000);
    const hours = Math.floor(elapsed / 3600);
    const minutes = Math.floor((elapsed % 3600) / 60);
    const seconds = elapsed % 60;
    const duration = [hours, minutes, seconds]
        .map(v => String(v).padStart(2, '0'))
        .filter((v, i) => i > 0 || v !== '00')
        .join(':');
    document.getElementById('stream-duration').textContent = duration;
}, 1000);
<?php endif; ?>

// Screen sharing toggle
let screenStream = null;
let isScreenShareActive = false;

async function toggleScreenShare(streamId) {
    try {
        if (isScreenShareActive) {
            // Stop screen sharing
            if (screenStream) {
                screenStream.getTracks().forEach(track => track.stop());
            }
            isScreenShareActive = false;
            
            // Notify server
            await fetch('/api/livestreams/toggle-screen-share.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    stream_id: streamId,
                    enable_screen_share: 'false',
                    csrf: '<?php echo csrfToken(); ?>'
                })
            });
            
            // Update UI
            document.getElementById('screen-share-btn').classList.remove('bg-green-600', 'hover:bg-green-700');
            document.getElementById('screen-share-btn').classList.add('bg-blue-600', 'hover:bg-blue-700');
            document.getElementById('screen-share-text').textContent = 'Chia sẻ màn hình';
            document.getElementById('screen-share-icon').textContent = '📺';
            showMessage('Đã dừng chia sẻ màn hình', 'success');
            return;
        }
        
        // Start screen sharing
        if (!navigator.mediaDevices || !navigator.mediaDevices.getDisplayMedia) {
            showMessage('Trình duyệt không hỗ trợ chia sẻ màn hình', 'error');
            return;
        }
        
        screenStream = await navigator.mediaDevices.getDisplayMedia({
            video: { 
                cursor: 'always',
                displaySurface: 'monitor'
            },
            audio: false
        });
        
        isScreenShareActive = true;
        
        // Handle when user stops sharing from system menu
        screenStream.getTracks()[0].onended = () => {
            toggleScreenShare(streamId);
        };
        
        // Notify server
        await fetch('/api/livestreams/toggle-screen-share.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                stream_id: streamId,
                enable_screen_share: 'true',
                csrf: '<?php echo csrfToken(); ?>'
            })
        });
        
        // Update UI
        document.getElementById('screen-share-btn').classList.remove('bg-blue-600', 'hover:bg-blue-700');
        document.getElementById('screen-share-btn').classList.add('bg-green-600', 'hover:bg-green-700');
        document.getElementById('screen-share-text').textContent = 'Dừng chia sẻ màn hình';
        document.getElementById('screen-share-icon').textContent = '✅';
        showMessage('Bắt đầu chia sẻ màn hình thành công', 'success');
        
    } catch (error) {
        if (error.name !== 'NotAllowedError') {
            showMessage('Lỗi: ' + error.message, 'error');
        }
        // User cancelled - do nothing
    }
}
</script>
