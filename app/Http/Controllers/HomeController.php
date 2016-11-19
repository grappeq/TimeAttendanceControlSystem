<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\WorkSession;
use App\Job;
use Illuminate\Support\Facades\DB;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    function getRandomColor() {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

    protected function getCurrentSessionsJobsJSON() {
        $array = [];
        $currentSessionsJobs = DB::table('work_sessions')
                ->select('jobId', DB::raw('count(*) as numberOfEmployees'))
                ->where('endDatetime', '0000-00-00 00:00:00')
                ->groupBy('jobId')
                ->lists('numberOfEmployees', 'jobId');
        foreach ($currentSessionsJobs as $jobId => $numberOfEmployees) {
            $jobObject = Job::find($jobId);
            $array[] = [
                'label' => $jobObject->name,
                'value' => $numberOfEmployees,
                'color' => $this->getRandomColor(),
            ];
        }

        return json_encode($array);
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index() {
        $todayDate = date("Y-m-d");
        $currentlyWorkingEmployeesNumber = WorkSession::where('endDatetime', '0000-00-00 00:00:00')
                ->distinct('employeeId')
                ->count('employeeId');

        $currentSessionsJobsJSON = $this->getCurrentSessionsJobsJSON();

        $todaySessionsQuery = WorkSession::whereDate('startDatetime', '=', $todayDate)
                ->orWhere(function ($query) {
                    $todayDate = date("Y-m-d");
                    $query->whereDate('endDatetime', '=', $todayDate);
                })
                ->orWhere('endDatetime', '0000-00-00 00:00:00');
        $todayWorkedEmployeesNumber = $todaySessionsQuery
                ->distinct('employeeId')
                ->count('employeeId');

        $todaySessions = $todaySessionsQuery->get();
        $todayWorkedTime = 0;
        $todayEarnedMoney = 0;

        foreach ($todaySessions as $workSession) {
            if ($workSession->endDate == '') {
                $endTimestamp = time();
            } else {
                $endTimestamp = strtotime($workSession->endDatetime);
            }
            if ($workSession->startDate == $todayDate) {
                $startTimestamp = strtotime($workSession->startDatetime);
            } else {
                $startTimestamp = strtotime($todayDate . ' 00:00:00');
            }
            $timeWorked = $endTimestamp - $startTimestamp;
            $amountEarned = $timeWorked / 3600 * $workSession->wagePerHour;

            $todayWorkedTime += $timeWorked;
            $todayEarnedMoney += number_format($amountEarned, 2);
        }

        $todayWorkedHours = $todayWorkedTime / 3600;
        return view('home', [
            'currentlyWorkingEmployeesNumber' => $currentlyWorkingEmployeesNumber,
            'todayWorkedEmployeesNumber' => $todayWorkedEmployeesNumber,
            'currentSessionsJobsJSON' => $currentSessionsJobsJSON,
            'todayWorkedHours' => number_format($todayWorkedHours, 0),
            'todayEarnedMoney' => number_format($todayEarnedMoney, 0),
        ]);
    }

}
