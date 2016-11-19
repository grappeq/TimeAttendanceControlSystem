<?php

namespace App\Http\Controllers\WorkSessions;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\WorkSession;
use App\Employee;
use Validator;

/**
 * WorkSession controller class
 * @package App\Http\Controllers
 */
class WorkSessionController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show new work session form view.
     *
     * @return Response
     */
    public function showNewForm() {
        return view('worksessions/new');
    }

    /**
     * Handles user create POST request.
     * @param Request $request
     * @return Response
     */
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
                    'employeeId' => 'required|exists:employees,id',
                    'startDatetime' => 'required|date_format:Y-m-d H:i:s',
                    'endDatetime' => 'required|date_format:Y-m-d H:i:s|datetime_greater_than_field:startDatetime',
        ]);

        if ($validator->fails()) {
            return redirect('/work_sessions/new')
                            ->withInput()
                            ->withErrors($validator);
        }
        $employee = Employee::find($request->employeeId);
        $startTimestamp = strtotime($request->startDatetime);
        $endTimestamp = strtotime($request->endDatetime);
        $timeWorked = $endTimestamp - $startTimestamp;

        $request->session()->flash('message', "Sesja została utworzona!");
        WorkSession::create([
            'jobId' => $employee->jobId,
            'employeeId' => $employee->id,
            'startDatetime' => $request->startDatetime,
            'endDatetime' => $request->endDatetime,
            'wagePerHour' => $employee->job->wagePerHour,
            'timeWorked' => $timeWorked,
            'earnedAmount' => $timeWorked / 3600 * $employee->job->wagePerHour,
            'paycheckId' => 0,
        ]);
        return redirect('/work_sessions/list');
    }

    /**
     * Show edit view.
     * @param Request $request
     * @param string $workSessionId 
     * @return Response
     */
    public function showEditForm(Request $request, $workSessionId) {
        $workSession = WorkSession::find($workSessionId);
        if (!$workSession) {
            $request->session()->flash('message', "Sesja o podanym numerze nie istnieje!");
            $request->session()->flash('alert-class', "alert-error");
            return redirect('/work_sessions/list');
        }
        return view('worksessions/edit', [
            'workSession' => $workSession
        ]);
    }

    /**
     * Handles edit POST request.
     * @param Request $request
     * @param mixed $employeeId
     * @return Response
     */
    public function edit(Request $request, $workSessionId) {
        $workSession = WorkSession::find($workSessionId);
        if (!$workSession) {
            $request->session()->flash('message', "Sesja o podanym numerze nie istnieje!");
            $request->session()->flash('alert-class', "alert-error");
            return redirect('/work_sessions/list');
        }

        $validator = Validator::make($request->all(), [
                    'startDatetime' => 'required|date_format:Y-m-d H:i:s',
                    'endDatetime' => 'required|date_format:Y-m-d H:i:s|datetime_greater_than_field:startDatetime',
        ]);

        if ($validator->fails()) {
            return redirect('/work_sessions/edit/' . $workSession->id)
                            ->withInput()
                            ->withErrors($validator);
        }

        $workSession->setStart(strtotime($request->startDatetime));
        $workSession->finish(strtotime($request->endDatetime));
        $workSession->save();

        $request->session()->flash('message', "Zmiany zostały zapisane!");
        return redirect('/work_sessions/edit/' . $workSession->id)
                        ->withInput();
    }

    /**
     * Handles delete GET request.
     * @param Request $request
     * @param mixed $workSessionId
     * @return Response
     */
    public function delete(Request $request, $workSessionId) {
        $workSession = WorkSession::find($workSessionId);
        if (!$workSession) {
            $request->session()->flash('message', "Sesja o podanym numerze nie istnieje!");
            $request->session()->flash('alert-class', "alert-error");
            return redirect('/work_sessions/list');
        }

        $workSession->delete();

        $request->session()->flash('message', "Usunięto sesję!");
        return redirect('/work_sessions/list');
    }

}
