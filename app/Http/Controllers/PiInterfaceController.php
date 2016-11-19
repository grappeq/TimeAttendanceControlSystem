<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Employee;
use App\WorkSession;
use Validator;
use Illuminate\Support\Facades\DB;

/**
 * Raspberry Pi API controller
 * @package App\Http\Controllers
 */
class PiInterfaceController extends Controller {

    const keySecret = 'MufZBvSbNFrbERgzqJbXxSUCDLhyDt';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    /**
     * Handles employee login interface
     * 
     * @param Request $request
     * @return Response
     */
    public function employeeLogin(Request $request) {
        $jsonArray = [];
        $jsonArray['success'] = false;

        if (sha1($request->authorizationSeed.self::keySecret) != $request->authorizationHash) {
            $jsonArray['errorType'] = 'noAuthorizationProvided';
            return response()->json($jsonArray);
        }

        if (empty($request->employeeId)) {
            $jsonArray['errorType'] = 'noEmployeeIdProvided';
            return response()->json($jsonArray);
        }

        $employee = Employee::find($request->employeeId);

        if (!$employee) {
            $jsonArray['errorType'] = 'employeeDoesNotExist';
            return response()->json($jsonArray);
        }

        if ($employee->isLogged()) {
            $jsonArray['actionType'] = 'logout';
            $employee->logOut();
            $jsonArray['success'] = true;
            
            $lastSession = WorkSession::where('employeeId', $employee->id)
                    ->orderBy('endDatetime', 'desc')
                    ->take(1)
                    ->get()[0];
            $jsonArray['timeWorked'] = $lastSession->timeWorked;
        } else {
            $jsonArray['actionType'] = 'login';
            $employee->logIn();
            $jsonArray['success'] = true;
        }

        $jsonArray['employeeData']['firstName'] = $employee->firstName;
        $jsonArray['employeeData']['lastName'] = $employee->lastName;
        $jsonArray['employeeData']['id'] = $employee->id;

        return response()->json($jsonArray);
    }

}
