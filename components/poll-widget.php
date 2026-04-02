<?php
// ============================================================
// AnkForum - components/poll-widget.php
// Poll display and voting widget
// ============================================================

// $poll variable should be passed to this component

if (empty($poll)) return;

$user = currentUser();
$totalVotes = array_sum(array_map(function($opt) { return $opt['votes']; }, $poll['options']));
$userVoted = $user && in_array($user['id'], array_column($poll['voters'] ?? [], 'user_id'));
?>

<div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-lg p-6 border border-slate-700 my-4">
    <!-- Poll Header -->
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-white mb-1"><?php echo htmlspecialchars($poll['title']); ?></h3>
        <?php if (!empty($poll['description'])): ?>
            <p class="text-sm text-slate-400"><?php echo htmlspecialchars($poll['description']); ?></p>
        <?php endif; ?>
        <div class="flex items-center gap-2 mt-2 text-xs text-slate-500">
            <span>👤 <?php echo htmlspecialchars($poll['author_name']); ?></span>
            <span>•</span>
            <span><?php echo formatTime($poll['created_at']); ?></span>
            <span>•</span>
            <span><?php echo number_format($totalVotes); ?> bình chọn</span>
        </div>
    </div>

    <!-- Options -->
    <div class="space-y-3 mb-4">
        <?php foreach ($poll['options'] as $option): ?>
            <?php
            $percentage = $totalVotes > 0 ? ($option['votes'] / $totalVotes) * 100 : 0;
            $isClickable = !$userVoted || $poll['multiple_choice'];
            ?>
            <div class="poll-option group cursor-pointer" 
                 onclick="<?php if ($isClickable) echo "votePoll('" . addslashes($poll['id']) . "', '" . addslashes($option['id']) . "')"; ?>"
                 data-option-id="<?php echo htmlspecialchars($option['id']); ?>"
                 data-poll-id="<?php echo htmlspecialchars($poll['id']); ?>">
                
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm text-slate-300 group-hover:text-white transition">
                        <?php echo htmlspecialchars($option['text']); ?>
                    </span>
                    <span class="text-xs font-semibold text-brand-400">
                        <?php echo number_format($option['votes']); ?> (<?php echo round($percentage, 1); ?>%)
                    </span>
                </div>
                
                <!-- Progress bar -->
                <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-brand-500 to-brand-400 rounded-full transition-all duration-300"
                         style="width: <?php echo $percentage; ?>%"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Status -->
    <div class="text-xs text-slate-500">
        <?php if ($userVoted): ?>
            <span class="text-green-400">✓ Bạn đã bình chọn</span>
        <?php else: ?>
            <span>Bấm để bình chọn</span>
        <?php endif; ?>
        <?php if ($poll['multiple_choice']): ?>
            <span class="ml-2">| Có thể chọn nhiều</span>
        <?php endif; ?>
    </div>
</div>

<script>
function votePoll(pollId, optionId) {
    const user = <?php echo json_encode($user); ?>;
    
    if (!user) {
        showMessage('Vui lòng đăng nhập để bình chọn', 'error');
        return;
    }

    fetch('/api/polls/vote.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            poll_id: pollId,
            option_id: optionId,
            csrf_token: '<?php echo csrfToken(); ?>'
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            showMessage(data.error || 'Lỗi khi bình chọn', 'error');
        }
    })
    .catch(e => showMessage('Lỗi: ' + e.message, 'error'));
}
</script>
