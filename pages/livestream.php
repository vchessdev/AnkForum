<?php
// ============================================================
// AnkForum - pages/livestream.php
// Livestream detail page
// ============================================================

$streamId = $_GET['id'] ?? null;

if (!$streamId) {
    ?>
    <div class="container mx-auto px-4 py-12 text-center">
        <h1 class="text-2xl font-bold text-red-500">Stream không tồn tại</h1>
    </div>
    <?php
    return;
}

$livestreams = readJson(LIVESTREAMS_FILE);
$stream = null;

foreach ($livestreams as $s) {
    if ($s['id'] === $streamId) {
        $stream = $s;
        break;
    }
}

if (!$stream) {
    ?>
    <div class="container mx-auto px-4 py-12 text-center">
        <h1 class="text-2xl font-bold text-red-500">Stream không tồn tại</h1>
    </div>
    <?php
    return;
}

$isOwner = $user && $user['id'] === $stream['author_id'];
$isLive = $stream['status'] === 'live';
?>

<div class="min-h-screen bg-slate-950">
    <div class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Main Video Area -->
            <div class="lg:col-span-2">
                <!-- Video Player -->
                <div class="bg-black rounded-lg overflow-hidden aspect-video mb-6 flex items-center justify-center relative">
                    <svg class="w-24 h-24 text-slate-700" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5h3V9h4v3h3l-5 5z"/>
                    </svg>
                    
                    <!-- Live Badge -->
                    <?php if ($isLive): ?>
                        <div class="absolute top-4 right-4 flex items-center gap-2 px-3 py-2 bg-red-600 rounded">
                            <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                            <span class="text-white text-sm font-semibold">LIVE</span>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Viewer Count -->
                    <div class="absolute bottom-4 left-4 text-white text-sm">
                        👥 <?php echo number_format($stream['viewer_count']); ?> đang xem
                    </div>
                </div>

                <!-- Stream Info -->
                <div class="bg-slate-800 rounded-lg p-6 mb-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-white mb-2"><?php echo htmlspecialchars($stream['title']); ?></h1>
                            <?php if (!empty($stream['description'])): ?>
                                <p class="text-slate-400 mb-4"><?php echo htmlspecialchars($stream['description']); ?></p>
                            <?php endif; ?>
                            
                            <div class="flex items-center gap-4 text-sm text-slate-400">
                                <span><?php echo formatTime($stream['started_at']); ?></span>
                                <span>•</span>
                                <span><?php if ($isLive) { echo 'Đang phát'; } else { echo 'Đã kết thúc'; } ?></span>
                            </div>
                        </div>
                        
                        <?php if ($isOwner && $isLive): ?>
                            <button onclick="endStream('<?php echo $streamId; ?>')" 
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded font-semibold transition">
                                Kết thúc
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Streamer Info -->
                    <div class="flex items-center gap-3 p-4 bg-slate-700/50 rounded">
                        <?php if (!empty($stream['author_avatar'])): ?>
                            <img src="<?php echo htmlspecialchars($stream['author_avatar']); ?>" 
                                 class="w-12 h-12 rounded-full" alt="">
                        <?php else: ?>
                            <div class="w-12 h-12 rounded-full bg-brand-500 flex items-center justify-center text-white font-bold">
                                <?php echo strtoupper(substr($stream['author_name'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div>
                            <div class="font-semibold text-white"><?php echo htmlspecialchars($stream['author_name']); ?></div>
                            <a href="/?page=profile&user=<?php echo urlencode($stream['author_name']); ?>" 
                               class="text-sm text-brand-400 hover:underline">
                                Xem profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-slate-800 rounded-lg overflow-hidden flex flex-col h-[600px]">
                    <!-- Header -->
                    <div class="p-4 border-b border-slate-700">
                        <h2 class="font-semibold text-white">Chat trực tiếp</h2>
                        <p class="text-xs text-slate-400"><?php echo number_format(count($stream['comments'])); ?> tin nhắn</p>
                    </div>

                    <!-- Chat Messages -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-3" id="livestream-chat">
                        <?php foreach (array_slice($stream['comments'], -20) as $comment): ?>
                            <div class="text-sm">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-semibold text-brand-400"><?php echo htmlspecialchars($comment['username']); ?></span>
                                    <span class="text-xs text-slate-500"><?php echo formatTime($comment['created_at']); ?></span>
                                </div>
                                <p class="text-slate-300 text-xs break-words"><?php echo htmlspecialchars($comment['text']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Input -->
                    <?php if ($user): ?>
                        <div class="p-4 border-t border-slate-700">
                            <div class="flex gap-2">
                                <input type="text" id="chat-input" placeholder="Nhập tin nhắn..." 
                                       class="flex-1 bg-slate-700 text-white text-sm rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-brand-500">
                                <button onclick="sendLiveComment('<?php echo $streamId; ?>')"
                                        class="px-3 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded transition">
                                    Gửi
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="p-4 border-t border-slate-700 text-center">
                            <a href="/?page=login" class="text-sm text-brand-400 hover:underline">
                                Đăng nhập để chat
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function endStream(streamId) {
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

function sendLiveComment(streamId) {
    const input = document.getElementById('chat-input');
    const text = input.value.trim();
    
    if (!text) return;
    
    fetch('/api/livestreams/comment.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            stream_id: streamId,
            text: text,
            csrf_token: '<?php echo csrfToken(); ?>'
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            location.reload();
        } else {
            showMessage(data.error || 'Lỗi gửi chat', 'error');
        }
    })
    .catch(e => showMessage('Lỗi: ' + e.message, 'error'));
}

// Auto-refresh chat every 3 seconds
setInterval(() => {
    fetch('/api/livestreams/get.php?id=<?php echo $streamId; ?>')
    .then(r => r.json())
    .then(data => {
        if (data.stream) {
            const chat = document.getElementById('livestream-chat');
            const latestComments = data.stream.comments.slice(-20);
            // You can update chat here
        }
    });
}, 3000);
</script>
