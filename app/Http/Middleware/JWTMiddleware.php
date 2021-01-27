<?php

namespace App\Http\Middleware;

use App\Http\Traits\ConsumesExternalApi;
use Closure;
use Flash;
use Illuminate\Http\Request;
use Throwable;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTMiddleware extends BaseMiddleware
{
    use ConsumesExternalApi;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if (!session()->has('token')) {
                return redirect(route('login'));
            }

            $authToken = session('token');
            $this->setToken($authToken);
            $apiRequest = $this->post('/refresh');
            $data = $apiRequest->getData();

            if ($apiRequest->getStatusCode() == 200 && $data->success === true) {
                $user = $data->data->user;
                $user->is_admin = $data->data->is_admin;
                $user->roles = $data->data->roles;
                session([
                    'user' => $data->data->user,
                    'token' => $data->data->token,
                ]);
            } else {
                Flash::error($data->message);
                $request->session()->invalidate();
                return redirect(route('login'));
            }

        } catch (Throwable $th) {
            Flash::error($th->getMessage());
            $request->session()->invalidate();
            return redirect(route('login'));
        }

        return $next($request);
    }
}
