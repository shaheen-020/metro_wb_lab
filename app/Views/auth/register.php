<?php
$title = 'Register | AuthBoard';
ob_start();
?>

<div class="max-w-md mx-auto bg-white/80 backdrop-blur-sm p-6 rounded-2xl shadow-lg mt-12">
    <h2 class="text-2xl font-bold mb-4 text-gray-800 text-center">Create an account</h2>

    <form method="POST" action="/register" class="space-y-4">
        <!-- Name -->
        <div>
            <label for="name" class="block mb-1 font-semibold text-gray-700">Name</label>
            <input type="text" id="name" name="name" required
                class="w-full border border-gray-200 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300 shadow-sm" />
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block mb-1 font-semibold text-gray-700">Email</label>
            <input type="email" id="email" name="email" required
                class="w-full border border-gray-200 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300 shadow-sm" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block mb-1 font-semibold text-gray-700">Password</label>
            <input type="password" id="password" name="password" required
                class="w-full border border-gray-200 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300 shadow-sm" />
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition-colors w-full">
                Create an account
            </button>
        </div>
    </form>

    <p class="mt-4 text-gray-600 text-sm text-center">
        Already have an account?
        <a href="/login" class="text-indigo-600 font-semibold hover:underline">Login</a>
    </p>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>