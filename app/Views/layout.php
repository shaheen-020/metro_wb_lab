<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'AuthBoard' ?></title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .post-card {
            background: white;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="min-h-screen bg-gray-100 flex flex-col items-center p-4 md:p-6 lg:p-8">

    <!-- Header -->
    <header class="w-full max-w-7xl mb-6 md:mb-8 pt-4 md:pt-6 px-4 bg-purple">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 md:gap-6">
            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-black">AuthBoard</h1>

            <?php if (!empty($_SESSION['user'])): ?>
                <nav class="flex items-center gap-3 md:gap-4 flex-wrap">
                    <a href="/dashboard" class="text-black font-semibold hover:underline text-sm md:text-base transition">Dashboard</a>
                    <button
                        class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 md:py-2 md:px-6 rounded-md text-sm md:text-base transition"
                        onclick="window.location.href='/logout'">
                        Logout
                    </button>
                </nav>
            <?php else: ?>
                <nav class="flex gap-3 md:gap-4 flex-wrap">
                    <a href="/login" class="text-blue-600 font-semibold hover:underline text-sm md:text-base transition">Login</a>
                    <a href="/register" class="text-blue-600 font-semibold hover:underline text-sm md:text-base transition">Register</a>
                </nav>
            <?php endif; ?>
        </div>
    </header>

    <!-- Main Content -->
    <main class="w-full max-w-7xl px-4">
        <?php if (!empty($_SESSION['flash'])): ?>
            <?php $f = $_SESSION['flash']; unset($_SESSION['flash']); ?>
            <div class="mb-4 md:mb-6 px-4 py-3 md:py-4 rounded-md text-sm md:text-base <?= $f['type'] === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                <?= htmlspecialchars($f['message']) ?>
            </div>
        <?php endif; ?>

        <?= $content ?>
    </main>

    <!-- Edit modal -->
    <div id="editModal" class="fixed inset-0 hidden items-end sm:items-center justify-center z-50 p-4">
        <div class="absolute inset-0 bg-black/50" id="editModalOverlay"></div>
        <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md md:max-w-lg p-6 md:p-8 z-10">
            <h3 class="text-lg md:text-xl font-semibold mb-4">Edit post</h3>
            <textarea id="editModalContent" class="w-full border rounded-md p-3 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-600 text-sm md:text-base" rows="6" placeholder="Post content..."></textarea>

            <div class="flex gap-2 flex-col sm:flex-row justify-end">
                <button id="editModalCancel" class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300 text-sm md:text-base transition order-2 sm:order-1">Cancel</button>
                <button id="editModalSave" class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 text-sm md:text-base transition order-1 sm:order-2">Save</button>
            </div>

            <!-- Hidden form submitted when user confirms -->
            <form id="editForm" method="POST" action="/post/edit" class="hidden">
                <input type="hidden" name="post_id" id="edit_modal_post_id" value="">
                <input type="hidden" name="content" id="edit_modal_content" value="">
            </form>
        </div>
    </div>

    <!-- Delete confirmation modal + single delete form -->
    <div id="deleteModal" class="fixed inset-0 hidden items-end sm:items-center justify-center z-50 p-4">
        <div class="absolute inset-0 bg-black/50" id="deleteModalOverlay"></div>
        <div class="relative bg-white rounded-lg shadow-lg w-full max-w-md md:max-w-lg p-6 md:p-8 z-10">
            <h3 class="text-lg md:text-xl font-semibold mb-4">Confirm deletion</h3>
            <p id="deleteModalMessage" class="text-sm md:text-base text-gray-700 mb-6">Are you sure you want to delete this post? This action cannot be undone.</p>

            <div class="flex gap-2 flex-col sm:flex-row justify-end">
                <button id="deleteModalCancel" class="px-4 py-2 rounded-md bg-gray-200 hover:bg-gray-300 text-sm md:text-base transition order-2 sm:order-1">Cancel</button>
                <button id="deleteModalConfirm" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 text-sm md:text-base transition order-1 sm:order-2">Delete</button>
            </div>

            <!-- Hidden form submitted when user confirms -->
            <form id="deleteForm" method="POST" action="/post/delete" class="hidden">
                <input type="hidden" name="post_id" id="modal_post_id" value="">
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ==== EDIT MODAL ====
            var editModal = document.getElementById('editModal');
            var editOverlay = document.getElementById('editModalOverlay');
            var editCancelBtn = document.getElementById('editModalCancel');
            var editSaveBtn = document.getElementById('editModalSave');
            var editContentArea = document.getElementById('editModalContent');
            var editForm = document.getElementById('editForm');
            var editPostIdInput = document.getElementById('edit_modal_post_id');
            var editContentInput = document.getElementById('edit_modal_content');

            function openEditModal(postId, content) {
                editPostIdInput.value = postId;
                editContentArea.value = content;
                editModal.classList.remove('hidden');
                editModal.classList.add('flex');
                editContentArea.focus();
            }

            function closeEditModal() {
                editModal.classList.remove('flex');
                editModal.classList.add('hidden');
            }

            // Wire up edit buttons (rendered in dashboard)
            document.querySelectorAll('.edit-btn').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    var id = btn.getAttribute('data-post-id');
                    var content = btn.getAttribute('data-content');
                    openEditModal(id, content);
                });
            });

            editOverlay.addEventListener('click', closeEditModal);
            editCancelBtn.addEventListener('click', function (e) { e.preventDefault(); closeEditModal(); });

            editSaveBtn.addEventListener('click', function (e) {
                e.preventDefault();
                var content = editContentArea.value.trim();
                if (!content) {
                    alert('Post content cannot be empty.');
                    return;
                }
                editContentInput.value = content;
                editForm.submit();
            });

            // ==== DELETE MODAL ====
            var modal = document.getElementById('deleteModal');
            var overlay = document.getElementById('deleteModalOverlay');
            var cancelBtn = document.getElementById('deleteModalCancel');
            var confirmBtn = document.getElementById('deleteModalConfirm');
            var msgEl = document.getElementById('deleteModalMessage');
            var form = document.getElementById('deleteForm');
            var modalInput = document.getElementById('modal_post_id');

            function openModal(postId) {
                modalInput.value = postId;
                var host = window.location.host || '{host}';
                msgEl.textContent = 'Are you sure you want to delete this post on ' + host + '? This action cannot be undone.';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeModal() {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }

            // Wire up delete buttons (rendered in dashboard)
            document.querySelectorAll('.delete-btn').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    var id = btn.getAttribute('data-post-id');
                    openModal(id);
                });
            });

            overlay.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', function (e) { e.preventDefault(); closeModal(); });

            confirmBtn.addEventListener('click', function (e) {
                e.preventDefault();
                // submit the hidden form
                form.submit();
            });
        });
    </script>

    <?php if (!empty($_SESSION['debug'])): ?>
        <script>
            try {
                console.log('APP_DEBUG:', <?= json_encode($_SESSION['debug'], JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) ?>);
            } catch (e) {
                console.log('APP_DEBUG: (could not stringify debug payload)');
            }
        </script>
        <?php unset($_SESSION['debug']); ?>
    <?php endif; ?>

</body>

</html>