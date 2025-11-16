<?php
$title = 'My Bookmarks | AuthBoard';
ob_start();
?>
<div class="max-w-3xl mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-6 text-indigo-700">My Bookmarked Posts</h2>
    <?php if (!empty($bookmarks)): ?>
        <div class="flex flex-col gap-6">
            <?php foreach ($bookmarks as $post): ?>
                <div class="post-card hover:shadow-lg transition-shadow w-full bg-white rounded-lg p-4 md:p-6 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm md:text-base"><strong><?= htmlspecialchars($post['name']); ?></strong></p>
                            <p class="text-xs md:text-sm text-gray-600 mt-1">By <?= htmlspecialchars($post['name']); ?></p>
                        </div>
                        <div class="text-right text-xs text-gray-400"><?= htmlspecialchars($post['created_at']); ?></div>
                    </div>
                    <div class="mt-3">
                        <p class="text-xs md:text-sm leading-relaxed"><?= nl2br(htmlspecialchars($post['content'])); ?></p>
                    </div>
                    <?php if (!empty($post['image'])): ?>
                        <img src="/uploads/<?= htmlspecialchars($post['image']); ?>" alt="Post Image" class="lg:w-1/2 w-11/12 h-48 md:h-56 lg:h-96 rounded-lg mt-2 object-cover">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="bg-white/80 rounded-xl p-8 text-center text-gray-500 shadow">You have no bookmarks yet.</div>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
