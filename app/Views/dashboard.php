<?php
$title = 'Dashboard | AuthBoard';
ob_start();
?>
<div class="bg-slate-200 rounded-lg p-4 md:p-6 mb-4 md:mb-6">
    <h2 class="text-xl md:text-3xl font-bold">Welcome, <?= htmlspecialchars($user['name']) ?>!</h2>
    <p class="text-xs md:text-sm opacity-90 py-3">Your email: <?= htmlspecialchars($user['email']) ?></p>
    <hr class="my-4 border-white/30">

    <button
        class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 md:py-3 md:px-6 rounded-md w-full md:w-auto transition"
        onclick="window.location.href='/post/create'">
        Create New Post
    </button>

    <hr class="my-4 border-white/30">
    <h3 class="text-lg md:text-2xl font-semibold py-5">Recent Posts</h3>
</div>

<?php if (!empty($posts)): ?>
    <div class="flex flex-col gap-4 md:gap-6">
        <?php foreach ($posts as $post): ?>
            <div class="post-card hover:shadow-lg transition-shadow w-full bg-white rounded-lg p-4 md:p-6 shadow-sm">
                <p class="text-sm md:text-base lg:w-1/2 w-11/12"><strong><?= htmlspecialchars($post['name']); ?></strong></p>
                <p class="text-xs md:text-sm my-3 lg:w-1/2 w-11/12"><?= nl2br(htmlspecialchars($post['content'])); ?></p>

                <?php if (!empty($post['image'])): ?>
                    <img src="/uploads/<?= htmlspecialchars($post['image']); ?>"
                        alt="Post Image"
                        class="lg:w-1/2 w-11/12 h-48 md:h-56 lg:h-96 rounded-lg mt-2 object-cover">
                <?php endif; ?>

                <small class="text-xs md:text-sm text-gray-500">Posted on <?= htmlspecialchars($post['created_at']); ?></small>
                <?php if ((int)$post['user_id'] === (int)$user['id']): ?>
                    <div class="mt-3 flex gap-2 flex-col sm:flex-row">
                        <!-- Edit button triggers edit modal -->
                        <button type="button" class="edit-btn btn btn-primary bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md flex-1 sm:flex-none transition text-sm md:text-base lg:w-[150px] w-full" data-post-id="<?= (int)$post['id'] ?>" data-content="<?= htmlspecialchars($post['content'], ENT_QUOTES) ?>">
                            Edit
                        </button>
                        <!-- Delete button triggers delete modal -->
                        <button type="button" class="delete-btn btn btn-danger bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-md flex-1 sm:flex-none transition text-sm md:text-base lg:w-[150px] w-full" data-post-id="<?= (int)$post['id'] ?>">
                            Delete  
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p class="text-gray-600 italic">No posts yet. Be the first to post something!</p>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>