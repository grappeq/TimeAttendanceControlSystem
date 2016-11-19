<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\WorkSession;
use App\Job;
use App\Employee;
use Validator;
use App\Paycheck;
use Illuminate\Support\Facades\DB;

/**
 * Paycheck-related views Controller
 *
 * @author Kacper Grabowski
 */
class PaycheckController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Shows paycheck history 
     * @param Request $request
     * @return Response
     */
    public function showPaycheckList(Request $request) {
        $paychecks = Paycheck::orderBy('created_at', 'desc')->get();

        return view('paychecks/list', [
            'paychecks' => $paychecks
        ]);
    }

    /**
     * Shows range choice form for creating new paycheck
     * @param Request $request
     * @return Response
     */
    public function showRangeChoiceForm(Request $request) {
        $defaultPeriodStart = date("Y-m-01 00:00:00", strtotime("-1 month", time()));
        $defaultPeriodEnd = date("Y-m-t 23:59:59", strtotime("-1 month", time()));

        return view('paychecks/rangechoice', [
            'defaultPeriodStart' => $defaultPeriodStart,
            'defaultPeriodEnd' => $defaultPeriodEnd,
        ]);
    }

    /**
     * Validates range choice and employee id and 
     * displays errors or redirects to summary page
     * @param Request $request
     * @return Response
     */
    public function validateRangeChoice(Request $request) {
        $validator = Validator::make($request->all(), [
                    'employeeId' => 'required|exists:employees,id',
                    'periodStartDatetime' => 'required|date_format:Y-m-d H:i:s',
                    'periodEndDatetime' => 'required|date_format:Y-m-d H:i:s|datetime_greater_than_field:periodStartDatetime',
        ]);

        if ($validator->fails()) {
            return redirect('/paychecks/new/range_choice')
                            ->withInput()
                            ->withErrors($validator);
        }

        return redirect('/paychecks/new/summary/employeeId:' . $request->employeeId
                . '/startPeriod:' . $request->periodStartDatetime
                . '/endPeriod:' . $request->periodEndDatetime);
    }

    /**
     * Create new paycheck
     * 
     * @param Request $request
     * @return Response
     */
    public function create($employeeId, $startPeriod, $endPeriod,
            Request $request) {
        $employee = Employee::find($employeeId);

        if (!$employee) {
            $request->session()->flash('message', "Pracownik o podanym numerze nie istnieje!");
            $request->session()->flash('alert-class', "alert-error");
            return redirect('/paychecks/new/range_choice');
        }

        $workSessionsQuery = $this->getWorkSessionsQuery($employeeId, $startPeriod, $endPeriod);
        $totalTimeWorked = $workSessionsQuery->sum('timeWorked');
        $totalEarnedAmount = $workSessionsQuery->sum('earnedAmount');

        $newPaycheck = Paycheck::create([
                    'periodStartDatetime' => $startPeriod,
                    'periodEndDatetime' => $endPeriod,
                    'timeWorked' => $totalTimeWorked,
                    'earnings' => $totalEarnedAmount,
                    'employeeId' => $employeeId,
        ]);

        $workSessionsQuery->update([
            'paycheckId' => $newPaycheck->id
        ]);


        $request->session()->flash('message', "Wypłata została zapisana!");
        return redirect('/paychecks/list');
    }

    protected function getWorkSessionsQuery($employeeId, $startPeriod,
            $endPeriod) {
        return WorkSession::where('employeeId', $employeeId)
                        ->where('startDatetime', '>=', $startPeriod)
                        ->where('endDatetime', '<=', $endPeriod)
                        ->where('endDatetime', '!=', '')
                        ->where('paycheckId', '0');
    }

    protected function getPaycheckDataByDays($workSessions) {
        foreach ($workSessions as $workSession) {
            if ($workSession->endDate == $workSession->startDate) {
                if (isset($paycheckDataByDates[$workSession->startDate])) {
                    $paycheckDataByDates[$workSession->startDate]['timeWorked'] += $workSession->timeWorked;
                    $paycheckDataByDates[$workSession->startDate]['earnedAmount'] += $workSession->earnedAmount;
                } else {
                    $paycheckDataByDates[$workSession->startDate]['timeWorked'] = $workSession->timeWorked;
                    $paycheckDataByDates[$workSession->startDate]['earnedAmount'] = $workSession->earnedAmount;
                }
            } else { // session lasts longer then day
                // Handle first day
                $endOfTheFirstDay = strtotime($workSession->startDate . ' 23:59:59') + 1;
                $startTimestamp = strtotime($workSession->startDatetime);
                $timeWorked = $endOfTheFirstDay - $startTimestamp;
                $earnedAmount = number_format($timeWorked / 3600 * $workSession->wagePerHour, 2);
                if (isset($paycheckDataByDates[$workSession->startDate])) {
                    $paycheckDataByDates[$workSession->startDate]['timeWorked'] += $timeWorked;
                    $paycheckDataByDates[$workSession->startDate]['earnedAmount'] += $earnedAmount;
                } else {
                    $paycheckDataByDates[$workSession->startDate]['timeWorked'] = $timeWorked;
                    $paycheckDataByDates[$workSession->startDate]['earnedAmount'] = $earnedAmount;
                }

                //Handle last day
                $beginningOfThelastDay = strtotime($workSession->endDate . ' 00:00:00');
                $endTimestamp = strtotime($workSession->endDatetime);
                $timeWorked = $endTimestamp - $beginningOfThelastDay;
                $earnedAmount = number_format($timeWorked / 3600 * $workSession->wagePerHour, 2);
                if (isset($paycheckDataByDates[$workSession->endDate])) {
                    $paycheckDataByDates[$workSession->endDate]['timeWorked'] += $timeWorked;
                    $paycheckDataByDates[$workSession->endDate]['earnedAmount'] += $earnedAmount;
                } else {
                    $paycheckDataByDates[$workSession->endDate]['timeWorked'] = $timeWorked;
                    $paycheckDataByDates[$workSession->endDate]['earnedAmount'] = $earnedAmount;
                }
            }
        }

        $maxTimeWorked = 0;
        foreach ($paycheckDataByDates as $date => $dateInfo) {
            $maxTimeWorked = max($maxTimeWorked, $dateInfo['timeWorked']);
            $paycheckDataByDates[$date]['timeWorkedPretty'] = WorkSession::formatTime($dateInfo['timeWorked']);
        }

        foreach ($paycheckDataByDates as $date => $dateInfo) {
            $percentage = round($dateInfo['timeWorked'] / $maxTimeWorked * 100);
            $paycheckDataByDates[$date]['timeWorkedPercentage'] = $percentage;
        }
        return $paycheckDataByDates;
    }

    protected function getMaxTimeWorked($paycheckDataByDates) {
        $maxTimeWorked = 0;
        foreach ($paycheckDataByDates as $date => $dateInfo) {
            $maxTimeWorked = max($maxTimeWorked, $dateInfo['timeWorked']);
        }
        return $maxTimeWorked;
    }

    /**
     * Shows new paycheck summary page
     * 
     * @param Request $request
     * @return Response
     */
    public function showSummaryScreen($employeeId, $startPeriod, $endPeriod,
            Request $request) {
        $employee = Employee::find($employeeId);

        if (!$employee) {
            $request->session()->flash('message', "Pracownik o podanym numerze nie istnieje!");
            $request->session()->flash('alert-class', "alert-error");
            return redirect('/paychecks/new/range_choice');
        }

        $workSessionsQuery = $this->getWorkSessionsQuery($employeeId, $startPeriod, $endPeriod);
        $workSessions = $workSessionsQuery->get();
        if (count($workSessions) == 0) {
            $request->session()->flash('message', "W zadanym przedziale nie ma żadnych nie wypłaconych sesji.");
            $request->session()->flash('alert-class', "alert-error");
            return redirect('/paychecks/new/range_choice');
        }

        $paycheckDataByDates = $this->getPaycheckDataByDays($workSessions);


        $numberOfDays = count($paycheckDataByDates);
        $totalTimeWorked = $workSessionsQuery->sum('timeWorked');
        $totalEarnedAmount = $workSessionsQuery->sum('earnedAmount');
        $avgTimeWorked = $totalTimeWorked / $numberOfDays;
        $avgEarnedAmount = $totalEarnedAmount / $numberOfDays;
        $avgTimePercentage = round($avgTimeWorked / $this->getMaxTimeWorked($paycheckDataByDates) * 100);

        $totalTimeWorkedFormatted = WorkSession::formatTime($totalTimeWorked);
        $totalEarnedAmountFormatted = number_format($totalEarnedAmount, 2);
        $avgTimeWorkedFormatted = WorkSession::formatTime("H:i:s", $avgTimeWorked);
        $avgEarnedAmountFormatted = number_format($avgEarnedAmount, 2);

        return view('paychecks/summary', [
            'paycheckDataByDates' => $paycheckDataByDates,
            'employee' => $employee,
            'startPeriod' => $startPeriod,
            'endPeriod' => $endPeriod,
            'totalTimeWorked' => $totalTimeWorkedFormatted,
            'totalEarnedAmount' => $totalEarnedAmountFormatted,
            'avgTimeWorked' => $avgTimeWorkedFormatted,
            'avgEarnedAmount' => $avgEarnedAmountFormatted,
            'avgTimePercentage' => $avgTimePercentage,
        ]);
    }

    /**
     * Handles undo GET request.
     * @param Request $request
     * @param mixed $paycheckId
     * @return Response
     */
    public function undo(Request $request, $paycheckId) {
        $paycheck = Paycheck::find($paycheckId);
        if (!$paycheck) {
            $request->session()->flash('message', "Sesja o podanym numerze nie istnieje!");
            $request->session()->flash('alert-class', "alert-error");
            return redirect('/paychecks/list');
        }

        $workSessions = WorkSession::where('paycheckId', $paycheck->id)
                ->update(['paycheckId' => 0]);

        $paycheck->delete();

        $request->session()->flash('message', "Cofnięto wypłatę!");
        return redirect('/paychecks/list');
    }

}
