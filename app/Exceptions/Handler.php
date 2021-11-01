<?php

namespace App\Exceptions;

use App\Models\User;
use App\Notifications\ExceptionMail;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Notification;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

//    public function report(Throwable $e)
//    {
//        if(app()->environment('production')){
//            if ($this->shouldReport($e)) {
//                Notification::send(User::where('role_id', 1)->get(['email']), new ExceptionMail($e));
//            }
//        }
//        parent::report($e);
//    }
}
