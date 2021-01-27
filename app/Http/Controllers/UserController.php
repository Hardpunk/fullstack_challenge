<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Requests\CreateUserRequest;
use App\Http\Traits\ConsumesExternalApi;
use Carbon\Carbon;
use Flash;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View as ViewView;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Validator;

class UserController extends AppBaseController
{
    use ConsumesExternalApi;

    /** @var object $user */
    private $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = (object) session('user');
            $this->user->created_at = Carbon::parse($this->user->created_at);
            $this->user->updated_at = Carbon::parse($this->user->updated_at);

            return $next($request);
        })->except('register');
    }

    /**
     * Display a listing of the User.
     *
     * @return ViewView|Factory
     * @throws HttpResponseException
     */
    public function index()
    {
        $user = $this->user;
        $response = $this->get('/users');
        handleResponse($response);
        $data = $response->getData();
        handleResponseData($data, 'index');
        $users = $data->data;

        return view('users.index', compact('user', 'users'));
    }

    /**
     * Show the form for creating a new User.
     *
     * @return ViewView|Factory
     */
    public function create()
    {
        $user = $this->user;
        return view('users.create', compact('user'));
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     * @return Redirector|RedirectResponse
     * @throws HttpResponseException
     */
    public function store(CreateUserRequest $request)
    {
        $this->saveUser($request);

        Flash::success('Usuário cadastrado com sucesso.');

        return redirect(route('users.index'));
    }

    /**
     * Register new User from login form.
     *
     * @param CreateUserRequest $request
     * @return RedirectResponse|HttpResponse|JsonResponse
     * @throws HttpResponseException
     * @throws ValidationException
     */
    public function register(CreateUserRequest $request)
    {
        $this->saveUser($request);

        $loginController = new LoginController();
        return $loginController->login($request);
    }

    /**
     * Display the specified User.
     *
     * @param int $id
     * @return ViewView|Factory
     * @throws HttpResponseException
     */
    public function show($id)
    {
        $user = $this->user;
        $response = $this->get("/users/{$id}");
        handleResponse($response, 'users.index');
        $data = $response->getData();
        handleResponseData($data, 'users.index');
        $showUser = $data->data;
        $showUser->created_at = Carbon::parse($showUser->created_at);
        $showUser->updated_at = Carbon::parse($showUser->updated_at);

        return view('users.show', compact('user', 'showUser'));
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param int $id
     * @return ViewView|Factory
     * @throws HttpResponseException
     */
    public function edit($id)
    {
        $user = $this->user;
        $response = $this->get("/users/{$id}");
        handleResponse($response);
        $data = $response->getData();
        handleResponseData($data, 'users.index');
        $editUser = $data->data;

        return view('users.edit', compact('user', 'editUser'));
    }

    /**
     * Update the specified User in storage.
     *
     * @param int $id
     * @param Request $request
     * @return Redirector|RedirectResponse
     * @throws HttpResponseException
     */
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['sometimes', 'nullable', 'string', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $route = 'users.index';
        $response = $this->put("/users/{$id}", $input);
        handleResponse($response, $route);
        $data = $response->getData();
        handleResponseData($data, $route);

        Flash::success($data->message);

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param int $id
     * @return Redirector|RedirectResponse
     * @throws HttpResponseException
     */
    public function destroy($id)
    {
        $response = $this->delete("/users/{$id}");
        $route = 'users.index';
        handleResponse($response, $route);
        $data = $response->getData();
        handleResponseData($data, $route);

        Flash::success($data->message);

        return redirect(route('users.index'));
    }

    /**
     * Common method to create a new user
     *
     * @param Request $request
     * @return JsonResponse
     * @throws HttpResponseException
     */
    private function saveUser(Request $request)
    {
        $input = $request->all();
        $route = 'users.index';
        $response = $this->post('/users', $input);
        handleResponse($response, $route);
        $data = $response->getData();
        handleResponseData($data, $route);

        return $response;
    }
}
