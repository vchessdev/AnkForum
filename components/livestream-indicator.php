<?php
// ============================================================
// AnkForum - components/livestream-indicator.php
// Live indicator badge for navbar and profiles
// ============================================================

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';

// Check if a user has active livestream
function getUserLivestream($userId) {
    $livestreams = readJson(LIVESTREAMS_FILE);
    foreach ($livestreams as $stream) {
        if ($stream['author_id'] === $userId && $stream['status'] === 'live') {
            return $stream;
        }
    }
    return null;
}

// Display live indicator
function renderLiveIndicator($stream, $size = 'md') {
    if (!$stream) return '';
    
    $sizeClasses = match($size) {
        'sm' => 'w-2 h-2',
        'md' => 'w-3 h-3',
        'lg' => 'w-4 h-4',
        default => 'w-3 h-3'
    };
    
    return <<<HTML
    <div class="live-indicator inline-flex items-center gap-1 px-2 py-1 bg-red-900/50 border border-red-500/50 rounded text-xs text-red-400 animate-pulse">
        <span class="$sizeClasses bg-red-500 rounded-full animate-pulse"></span>
        <span>LIVE</span>
    </div>
    HTML;
}

// Display livestream card
function renderLivestreamCard($stream) {
    $duration = '';
    if ($stream['status'] === 'live') {
        $startTime = strtotime($stream['started_at']);
        $duration = formatDuration(time() - $startTime);
    } elseif ($stream['status'] === 'ended') {
        $duration = formatDuration($stream['duration']);
    }
    
    return <<<HTML
    <div class="bg-slate-800 rounded-lg overflow-hidden hover:bg-slate-700 transition cursor-pointer"
         onclick="window.location.href='/livestream?id={$stream['id']}'">
        
        <div class="relative bg-black aspect-video flex items-center justify-center">
            <svg class="w-16 h-16 text-slate-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5h3V9h4v3h3l-5 5z"/>
            </svg>
            <div class="absolute top-2 right-2 bg-red-600 px-3 py-1 rounded text-xs font-semibold text-white">
                {$duration}
            </div>
        </div>
        
        <div class="p-3">
            <h4 class="font-semibold text-white text-sm mb-1">{$stream['title']}</h4>
            <div class="flex items-center gap-2 text-xs text-slate-400">
                <span>👤 {$stream['author_name']}</span>
                <span>•</span>
                <span>👥 {$stream['viewer_count']} viewers</span>
            </div>
        </div>
    </div>
    HTML;
}
