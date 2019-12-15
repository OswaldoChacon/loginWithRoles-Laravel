<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfPost
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  \Exception  $exception
     * @return mixed
     */
    public function handle($request,  Exception $exception)
    {
        if ($exception instanceof MethodNotAllowedHttpException && $request->isMethod('POST')) {
            return redirect('/');
        }
        return parent::render($request, $exception);
    }
}
