<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Requests\CreateUserRequest;
use App\Http\Traits\ConsumesExternalApi;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Flash;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Response;
use Validator;

class UserController extends AppBaseController
{
    use ConsumesExternalApi;

    /** @var $userRepository UserRepository */
    private $userRepository;

    /** @var object $user */
    private $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;

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
     * @param UserDataTable $userDataTable
     * @return Response
     */
    public function index(UserDataTable $userDataTable)
    {
        $user = $this->user;
        $apiRequest = $this->get('/users');
        $data = $apiRequest->getData();
        $users = $data->data;
        return $userDataTable->render('users.index', compact('user', 'users'));
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
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
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = $this->userRepository->create($input);

        Flash::success('User saved successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Register new User from login form.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function register(CreateUserRequest $request)
    {
        $input = $request->all();
        $response = $this->post('/users', $input);
        $data = $response->getData();

        if (isset($data->errors)) {
            $errors = (array) $data->errors;
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($errors);
        }

        $loginController = new LoginController();
        return $loginController->login($request);
    }

    /**
     * Display the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->user;
        $apiRequest = $this->get("/users/{$id}");
        $data = $apiRequest->getData();

        if (!$data->success) {
            Flash::error($data->message);
            return redirect(route('users.index'));
        }

        $editUser = $data->data;

        return view('users.edit', compact('user', 'editUser'));
    }

    /**
     * Update the specified User in storage.
     *
     * @param  int              $id
     * @param Request $request
     *
     * @return Response
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
        $updateRequest = $this->put("/users/{$id}", $input);
        $data = $updateRequest->getData();
        if (!$data->success) {
            Flash::error($data->message);
            return redirect(route('users.index'));
        }

        Flash::success($data->message);

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('User deleted successfully.');

        return redirect(route('users.index'));
    }
}
