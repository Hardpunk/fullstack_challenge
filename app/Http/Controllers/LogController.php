<?php

namespace App\Http\Controllers;

use App\Http\Traits\ConsumesExternalApi;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;

class LogController extends AppBaseController
{
    use ConsumesExternalApi;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = (object) session('user');

            return $next($request);
        });
    }

    /**
     * Retrieve list of activity logs
     *
     * @return View|Factory
     * @throws HttpResponseException
     */
    public function index()
    {
        $user = $this->user;
        $response = $this->get('/logs');
        handleResponse($response);
        $data = $response->getData();
        handleResponseData($data, 'index');
        $logs = $data->data;
        $logs = array_map(function($log) {
            $log->created_at = Carbon::createFromFormat('Y-m-d\TH:i:s.uP', $log->created_at)
                ->setTimezone('America/Sao_Paulo')
                ->format('d/m/Y H:i:s');
            return $log;
        }, $logs);

        return view('logs.index', compact('user', 'logs'));
    }
}
