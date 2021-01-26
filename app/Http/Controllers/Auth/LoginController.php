<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Traits\ConsumesExternalApi;
use App\Providers\RouteServiceProvider;
use Flash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers, ConsumesExternalApi;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $requestUri = $request->getRequestUri();
            if (session()->has('user') && ($requestUri !== '/logout')) {
                return redirect(route('index'));
            }
            return $next($request);
        });
    }

    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     * @return RedirectResponse|Response|JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        if (session()->has('token')) {
            return redirect(route('index'));
        }

        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $apiRequest = $this->post('/auth/login', $this->credentials($request));
        if ($apiRequest->getStatusCode() == 200) {
            $data = $apiRequest->getData();
            session([
                'user' => $data->user,
                'token' => $data->token,
            ]);
            return true;
        }

        return false;
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function logout(Request $request)
    {
        $apiRequest = $this->post('/logout');
        $data = $apiRequest->getData();
        if ($apiRequest->getStatusCode() == 200) {
            $request->session()->invalidate();
            Flash::success($data->message);
            return redirect(route('index'));
        }

        Flash::error($data->message);
        return redirect()->back();
    }
}
