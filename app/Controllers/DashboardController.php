<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Post;

class DashboardController extends Controller {
    public function index(): void
    {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $posts = Post::getAllWithUser();
        $bookmarkedIds = [];
        if ($user) {
            $bookmarks = Post::getBookmarks($user['id']);
            foreach ($bookmarks as $bm) {
                $bookmarkedIds[] = (int)$bm['id'];
            }
        }
        $this->view('dashboard.php', [
            'user' => $user,
            'posts' => $posts,
            'bookmarkedIds' => $bookmarkedIds
        ]);
    }
}
