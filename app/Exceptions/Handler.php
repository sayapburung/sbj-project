<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
        public function register(): void
        {
        $this->renderable(function (PostTooLargeException $e, $request) {
    
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Upload gagal! Maksimal ukuran file adalah 5MB per file.');
        });
        }           
}
