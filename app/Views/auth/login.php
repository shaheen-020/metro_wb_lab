<?php
$title = 'Login | AuthBoard';
ob_start();
?>

<div class="max-w-md mx-auto bg-white p-6 rounded-xl shadow-md mt-12">
    <h2 class="text-2xl font-bold mb-4 text-gray-800 text-center">Login Now</h2>

    <form method="POST" action="/login" class="space-y-4">
        <!-- Email -->
        <div>
            <label for="email" class="block mb-1 font-semibold text-gray-700">Email</label>
            <input type="email" id="email" name="email" required
                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:outline-none focus:ring-0.5 focus:ring-black focus:border-black" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block mb-1 font-semibold text-gray-700">Password</label>
            <input type="password" id="password" name="password" required
                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:outline-none focus:ring-0.5 focus:ring-black focus:border-black" />
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition-colors w-full">
                Login
            </button>
        </div>
    </form>

    <p class="mt-4 text-gray-600 text-sm text-center">
        Don't have an account?
        <a href="/register" class="text-blue-600 font-semibold hover:underline">Register</a>
    </p>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>