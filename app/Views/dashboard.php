<?php
$title = 'Dashboard | AuthBoard';
ob_start();
?>
<div class="bg-slate-200 rounded-lg mt-5 p-4 md:p-6 mb-4 md:mb-6">
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
                    <img src="/uploads/<?= htmlspecialchars($post['image']); ?>"
                        alt="Post Image"
                        class="lg:w-1/2 w-11/12 h-48 md:h-56 lg:h-96 rounded-lg mt-2 object-cover">
                <?php endif; ?>

                <small class="text-xs md:text-sm text-gray-500">Posted on <?= htmlspecialchars($post['created_at']); ?></small>
                
                <!-- Bookmark button (visible for all posts) -->
                <?php
                $isBookmarked = !empty($bookmarkedIds) && in_array((int)$post['id'], $bookmarkedIds, true);
                ?>
                <div class="mt-4 flex gap-2 flex-col sm:flex-row">
                    <form method="POST" action="<?= $isBookmarked ? '/unbookmark' : '/bookmark' ?>" style="display:inline;">
                        <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">
                        <input type="hidden" name="redirect" value="/dashboard">
                        <button type="submit" class="px-4 py-2 rounded-md font-semibold shadow transition text-sm md:text-base <?= $isBookmarked ? 'w-full lg:w-[150px] bg-slate-700 hover:bg-slate-900 text-white' : 'w-full lg:w-[150px] bg-black hover:bg-gray-900 text-white' ?>">
                            <?= $isBookmarked ? '★ Saved' : '☆ Save' ?>
                        </button>
                    </form>

                    <!-- Edit & Delete buttons (only for user's own posts) -->
                    <?php if ((int)$post['user_id'] === (int)$user['id']): ?>
                        <button type="button" class="edit-btn btn btn-primary bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md flex-1 sm:flex-none transition text-sm md:text-base lg:w-[150px] w-full" data-post-id="<?= (int)$post['id'] ?>" data-content="<?= htmlspecialchars($post['content'], ENT_QUOTES) ?>">
                            Edit
                        </button>
                        <button type="button" class="delete-btn btn btn-danger bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-md flex-1 sm:flex-none transition text-sm md:text-base lg:w-[150px] w-full" data-post-id="<?= (int)$post['id'] ?>">
                            Delete  
                        </button>
                    <?php endif; ?>
                </div>
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