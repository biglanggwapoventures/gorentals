<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Auth;

class UserNotificationComposer
{
    /**
     * Create a new profile composer.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $count = 0;
        if(Auth::check()){
            // dd(Auth::user()->receivedMessages->toArray());
            $count = Auth::user()->unreadMessagesCount();
        }
        $view->with('notificationCount', $count);
    }
}