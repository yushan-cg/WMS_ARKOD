<?php

namespace App\Http\Controllers;

use App\Models\Notification;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;



class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // public function __construct()
    // {
    //     view()->composer('*', function ($view) {
    //         $view->with('notifications', Notificaton::where('uid', Auth::id())
    //             ->orderBy('created_at', 'desc')
    //             ->get());
    //     });
    // }

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::check()) {
                view()->share('notifications', Notification::where('uid', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get());
            }
            return $next($request);
        });
    }

        public function getNotifications()
    {
        $notifications = Notification::where('uid', auth()->id())
                                    ->orderBy('created_at', 'desc')
                                    ->get();

        return view('notifications.index', compact('notifications'));
    }

    public function clearNotifications()
    {
        $userId = Auth::id();
        Notification::where('uid', $userId)->delete();

        return redirect()->back()->with('success', 'All notifications cleared!');
    }


        // NotificationController.php

    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            $notification->is_read = true;
            $notification->save();
        }
        return redirect()->back();
    }

    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);
        return redirect()->back();
    }

}
