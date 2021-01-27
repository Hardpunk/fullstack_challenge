<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Spatie\Activitylog\Models\Activity;

class LogAPIController extends AppBaseController
{
    /**
     * Retrieve list of activity logs
     *
     * @return JsonResponse
     */
    public function index()
    {
        $logs = Activity::all();

        if ($logs->count() > 0) {
            $logs = array_map(function($log) {
                $log['user'] = User::where('id', $log['causer_id'])->value('name') ?? 'desconhecido';
                return $log;
            }, $logs->toArray());
        }

        return $this->sendResponse($logs, 'Logs recuperados com sucesso.');
    }
}
