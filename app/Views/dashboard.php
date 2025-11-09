<?php
$title = 'Dashboard | AuthBoard';
ob_start();
?>

<div class="bg-slate-200 rounded-lg p-4 mb-4">
    <h2 class="text-2xl font-bold">Welcome, <?= htmlspecialchars($user['name']) ?>!</h2>
    <p class="text-sm opacity-90 py-3">Your email: <?= htmlspecialchars($user['email']) ?></p>
    <hr class="my-4 border-white/30">

    <button 
        class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md" 
        onclick="window.location.href='/post/create'">
        Create New Post
    </button>

    <hr class="my-4 border-white/30">
    <h3 class="text-xl font-semibold py-5">Recent Posts</h3>
</div>

<?php if (!empty($posts)): ?>
    <?php foreach ($posts as $post): ?>
        <div class="post-card">
            <p><strong><?= htmlspecialchars($post['name']); ?></strong></p>
            <p><?= nl2br(htmlspecialchars($post['content'])); ?></p>

            <?php if (!empty($post['image'])): ?>
                <img src="/uploads/<?= htmlspecialchars($post['image']); ?>"
                     alt="Post Image"
                     class="max-w-xs rounded-lg mt-2">
            <?php endif; ?>

            <small class="text-gray-500">Posted on <?= htmlspecialchars($post['created_at']); ?></small>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="text-gray-600 italic">No posts yet. Be the first to post something!</p>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
