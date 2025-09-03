<?php
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Services\NotificationService;

class NotificationComposer
{
    public function compose(View $view)
    {
        $notifications = NotificationService::getSystemNotifications()->take(10);
        $notificationCount = NotificationService::getNotificationCount();
        
        $view->with([
            'notifications' => $notifications,
            'notificationCount' => $notificationCount
        ]);
    }
}