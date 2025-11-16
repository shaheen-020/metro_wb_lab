<?php
namespace App\Controllers;

use App\Models\Post;
use App\Models\Activity;
use App\Core\Session;

class BookmarkController {
    // Show all bookmarks for the logged-in user
    public function index() {
        $user = Session::get('user');
        if (!$user) {
            header('Location: /login');
            exit;
        }
        $bookmarks = Post::getBookmarks($user['id']);
        $title = 'My Bookmarks';
        include __DIR__ . '/../Views/bookmarks.php';
    }

    // Save a bookmark
    public function bookmark() {
        $user = Session::get('user');
        if (!$user || empty($_POST['post_id'])) {
            header('Location: /login'); exit;
        }
        $postId = (int)$_POST['post_id'];
        \App\Models\Post::bookmark($user['id'], $postId);
        Activity::log($user['id'], 'bookmark', $postId, 'Bookmarked a post');
        header('Location: ' . ($_POST['redirect'] ?? '/dashboard'));
        exit;
    }

    // Remove a bookmark
    public function unbookmark() {
        $user = Session::get('user');
        if (!$user || empty($_POST['post_id'])) {
            header('Location: /login'); exit;
        }
        $postId = (int)$_POST['post_id'];
        \App\Models\Post::unbookmark($user['id'], $postId);
        Activity::log($user['id'], 'unbookmark', $postId, 'Removed a bookmark');
        header('Location: ' . ($_POST['redirect'] ?? '/dashboard'));
        exit;
    }
}
