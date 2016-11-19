<?php

namespace App\Http\Controllers\WorkSessions;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\WorkSession;

/**
 * WorkSession list controller class
 * @package App\Http\Controllers
 */
class WorkSessionsListController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show work session list based on filtering
     * @param Request $request
     * @return Response
     */
    public function showList(Request $request) {
        $workSessions = WorkSession::orderBy('endDatetime', 'desc')->get();
        return view('worksessions/list', [
            'workSessions' => $workSessions
        ]);
    }

}
