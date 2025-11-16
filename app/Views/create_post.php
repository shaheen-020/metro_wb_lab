<?php
$title = 'Create Post | AuthBoard';
ob_start();
?>

<div class="max-w-3xl mx-auto bg-white/85 backdrop-blur-sm p-6 rounded-2xl shadow-lg mt-8">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Create Post</h2>

    <form method="POST" action="/post/create" enctype="multipart/form-data" class="space-y-4">
        <!-- Content textarea -->
        <div>
            <label for="content" class="block mb-1 font-semibold text-gray-700">Post your status</label>
            <textarea id="content" name="content" required 
                      class="w-full border border-gray-200 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300 resize-none shadow-sm"
                      rows="5" placeholder="What's on your mind?"></textarea>
        </div>

        <!-- Image upload -->
        <div>
            <label for="image" class="block mb-1 font-semibold text-gray-700">Add an image (optional)</label>
            <input type="file" id="image" name="image" accept="image/*"
                   class="block w-full text-gray-700 file:border file:border-gray-200 file:rounded-md file:px-3 file:py-2 file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200"/>
            <p class="text-xs text-gray-500 mt-1">Max 4MB. JPG, PNG, GIF supported.</p>
        </div>

        <!-- Submit button -->
        <div>
            <button type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition-colors">
                Post
            </button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
