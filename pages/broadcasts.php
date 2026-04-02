<?php
// ============================================================
// AnkForum - pages/broadcasts.php
// All livestreams page
// ============================================================

$livestreams = readJson(LIVESTREAMS_FILE);

// Filter by status
$status = $_GET['status'] ?? 'all'; // live, ended, all
$streams = [];

foreach ($livestreams as $stream) {
    if ($status === 'all' || $stream['status'] === $status) {
        $streams[] = $stream;
    }
}

// Sort by started_at descending
usort($streams, function($a, $b) {
    return strtotime($b['started_at']) - strtotime($a['started_at']);
});
?>

<div class="min-h-screen bg-slate-950">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Livestreams</h1>
            <p class="text-slate-400">Xem phát trực tiếp từ cộng đồng</p>
        </div>

        <!-- Filters -->
        <div class="flex gap-3 mb-8">
            <a href="/?page=broadcasts&status=all" 
               class="px-4 py-2 rounded-lg transition <?php echo ($status === 'all') ? 'bg-brand-600 text-white' : 'bg-slate-800 text-slate-400 hover:bg-slate-700'; ?>">
                Tất cả
            </a>
            <a href="/?page=broadcasts&status=live" 
               class="px-4 py-2 rounded-lg transition <?php echo ($status === 'live') ? 'bg-red-600 text-white' : 'bg-slate-800 text-slate-400 hover:bg-slate-700'; ?>">
                🔴 Đang phát
            </a>
            <a href="/?page=broadcasts&status=ended" 
               class="px-4 py-2 rounded-lg transition <?php echo ($status === 'ended') ? 'bg-brand-600 text-white' : 'bg-slate-800 text-slate-400 hover:bg-slate-700'; ?>">
                Đã kết thúc
            </a>
        </div>

        <!-- Grid -->
        <?php if (count($streams) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($streams as $stream): ?>
                    <a href="/?page=livestream&id=<?php echo urlencode($stream['id']); ?>"
                       class="group bg-slate-800 rounded-lg overflow-hidden hover:bg-slate-700 transition">
                        
                        <div class="relative bg-black aspect-video flex items-center justify-center overflow-hidden">
                            <svg class="w-16 h-16 text-slate-600 group-hover:scale-110 transition" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5h3V9h4v3h3l-5 5z"/>
                            </svg>
                            
                            <!-- Status Badge -->
                            <div class="absolute top-3 right-3 px-3 py-1 rounded text-xs font-bold text-white <?php echo ($stream['status'] === 'live') ? 'bg-red-600 animate-pulse' : 'bg-slate-700'; ?>">
                                <?php echo ($stream['status'] === 'live') ? '🔴 LIVE' : 'ENDED'; ?>
                            </div>
                            
                            <!-- Duration -->
                            <?php if ($stream['status'] === 'live'): ?>
                                <div class="absolute bottom-3 left-3 px-2 py-1 rounded text-xs font-semibold bg-black/50 text-white" id="duration-<?php echo $stream['id']; ?>">
                                    00:00
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-bold text-white mb-1 truncate group-hover:text-brand-300 transition">
                                <?php echo htmlspecialchars($stream['title']); ?>
                            </h3>
                            
                            <div class="flex items-center gap-2 mb-2">
                                <?php if (!empty($stream['author_avatar'])): ?>
                                    <img src="<?php echo htmlspecialchars($stream['author_avatar']); ?>" class="w-6 h-6 rounded-full">
                                <?php else: ?>
                                    <div class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-white text-xs font-bold">
                                        <?php echo strtoupper(substr($stream['author_name'], 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                                <span class="text-sm text-slate-400"><?php echo htmlspecialchars($stream['author_name']); ?></span>
                            </div>
                            
                            <div class="flex items-center justify-between text-xs text-slate-500">
                                <span>👥 <?php echo number_format($stream['viewer_count']); ?></span>
                                <span><?php echo formatTime($stream['started_at']); ?></span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-16">
                <div class="text-5xl mb-4">📭</div>
                <p class="text-slate-400 font-medium">Chưa có livestream nào</p>
                <a href="/?page=broadcast" class="text-brand-400 hover:underline mt-4 inline-block">
                    Hãy bắt đầu phát trực tiếp →
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($status === 'live'): ?>
<script>
// Update duration for live streams every second
document.querySelectorAll('[id^="duration-"]').forEach(el => {
    const streamId = el.id.replace('duration-', '');
    const duration = setInterval(() => {
        const link = el.closest('a');
        if (!link) {
            clearInterval(duration);
            return;
        }
        
        const startTime = link.href.match(/started_at=([^&]*)/)?.[1] || new Date().toISOString();
        const now = new Date();
        const elapsed = Math.floor((now - new Date(startTime)) / 1000);
        const hours = Math.floor(elapsed / 3600);
        const minutes = Math.floor((elapsed % 3600) / 60);
        const secs = elapsed % 60;
        
        el.textContent = [hours, minutes, secs]
            .map((v, i) => i === 0 && v === 0 ? '' : String(v).padStart(2, '0'))
            .filter(Boolean)
            .join(':') || '00:00';
    }, 1000);
});
</script>
<?php endif; ?>
