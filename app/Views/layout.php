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

<body class="min-h-screen bg-gray-100 flex flex-col items-center p-4">

    <!-- Header -->
    <header class="w-full max-w-3xl mb-6 flex justify-between items-center pt-6 bg-purple">
        <h1 class="text-2xl font-bold text-black">AuthBoard</h1>

        <?php if (!empty($_SESSION['user'])): ?>
            <nav class="space-x-4 bg-purple">
                <a href="/dashboard" class="text-black font-semibold hover:underline">Dashboard</a>
                <button
                    class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md"
                    onclick="window.location.href='/logout'">
                    Logout
                </button>
            </nav>
        <?php else: ?>
            <nav class="space-x-4">
                <a href="/login" class="text-blue-600 font-semibold hover:underline">Login</a>
                <a href="/register" class="text-blue-600 font-semibold hover:underline">Register</a>
            </nav>
        <?php endif; ?>
    </header>

    <!-- Main Content -->
    <main class="w-full max-w-3xl">
        <?= $content ?>
    </main>

</body>

</html>