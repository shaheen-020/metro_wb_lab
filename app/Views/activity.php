<?php
$title = 'My Activity';
include __DIR__ . '/layout.php';
?>

<?php
// Calculate relative time (e.g., "2 hours ago")
function timeAgo($timestamp) {
    $time = strtotime($timestamp);
    $now = time();
    $diff = $now - $time;

    if ($diff < 60) {
        return 'just now';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' minute' . ($mins > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        $weeks = floor($diff / 604800);
        return $weeks . ' week' . ($weeks > 1 ? 's' : '') . ' ago';
    }
}

// Get action icon and label
function getActionDisplay($action) {
    $map = [
        'login' => ['icon' => '👤', 'label' => 'Logged in'],
        'logout' => ['icon' => '👤', 'label' => 'Logged out'],
        'create_post' => ['icon' => '📝', 'label' => 'Created a post'],
        'edit_post' => ['icon' => '✏️', 'label' => 'Edited a post'],
        'delete_post' => ['icon' => '🗑️', 'label' => 'Deleted a post'],
        'bookmark' => ['icon' => '⭐', 'label' => 'Bookmarked a post'],
        'unbookmark' => ['icon' => '☆', 'label' => 'Removed a bookmark'],
    ];
    return $map[$action] ?? ['icon' => '•', 'label' => ucfirst(str_replace('_', ' ', $action))];
}
?>

<div class="px-4 md:px-6 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-2">Activity Timeline</h1>
            <p class="text-gray-600 text-lg">Your recent actions and activity history</p>
        </div>
    </div>
</div>

<!-- Timeline -->
<div class="px-4 md:px-6 pb-12">
    <div class="max-w-3xl mx-auto">
        <?php if (empty($activities)): ?>
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="inline-block bg-white rounded-full p-6 shadow-lg mb-4">
                    <span class="text-5xl">📭</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">No Activity Yet</h2>
                <p class="text-gray-600 mb-6">Your activity will appear here as you interact with the platform.</p>
                <a href="/dashboard" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition">
                    Go to Dashboard
                </a>
            </div>
        <?php else: ?>
            <!-- Activity Items -->
            <div class="space-y-4">
                <?php foreach ($activities as $activity): 
                    $action = getActionDisplay($activity['action_type']);
                ?>
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition border-l-4 border-indigo-500 p-6">
                        <div class="flex items-start gap-4">
                            <!-- Icon -->
                            <div class="text-2xl flex-shrink-0 mt-1">
                                <?php echo $action['icon']; ?>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-1">
                                    <p class="font-semibold text-gray-900">
                                        <?php echo htmlspecialchars($action['label']); ?>
                                    </p>
                                    <span class="text-sm text-gray-500">
                                        <?php echo timeAgo($activity['created_at']); ?>
                                    </span>
                                </div>

                                <!-- Post Preview (if applicable) -->
                                <?php if ($activity['post_id'] && $activity['content']): ?>
                                    <div class="mt-3 bg-gray-50 p-3 rounded border border-gray-200">
                                        <p class="text-gray-700 text-sm line-clamp-2">
                                            <?php echo htmlspecialchars(substr($activity['content'], 0, 150)); ?>
                                            <?php if (strlen($activity['content']) > 150): ?>
                                                ...
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Date -->
                            <div class="text-xs text-gray-500 flex-shrink-0 text-right">
                                <?php echo date('M d, Y', strtotime($activity['created_at'])); ?>
                                <br>
                                <?php echo date('h:i A', strtotime($activity['created_at'])); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
