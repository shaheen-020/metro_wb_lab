<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Activity;

class ActivityController extends Controller
{
    /**
     * Display user activity timeline
     */
    public function index(): void
    {
        // Redirect if not logged in
        $user = Session::get('user');
        if (!$user) {
            header('Location: /');
            exit;
        }

        $userId = $user['id'];
        
        // Fetch activities
        $activities = Activity::getByUser($userId);

        $this->view('activity.php', [
            'activities' => $activities,
            'user' => $user
        ]);
    }
}
