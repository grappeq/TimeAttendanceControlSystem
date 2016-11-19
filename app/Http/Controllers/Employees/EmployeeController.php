<?php

namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Employee;
use Validator;
use Illuminate\Support\Facades\DB;

/**
 * Employee Controller Class
 * @package App\Http\Controllers
 */
class EmployeeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show new employee form view.
     *
     * @return Response
     */
    public function showNewEmployeeForm() {
        return view('employees/new');
    }

    /**
     * Handles user create POST request.
     * @param Request $request
     * @return Response
     */
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
                    'firstName' => 'required|max:255',
                    'lastName' => 'required|max:255',
                    'jobId' => 'required|exists:jobs,id',
                    'PESEL' => 'pesel|max:255|unique:employees',
        ]);

        if ($validator->fails()) {
            return redirect('/employees/new')
                            ->withInput()
                            ->withErrors($validator);
        }

        $request->session()->flash('message', "Pracownik został utworzony!");
        Employee::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'PESEL' => $request->PESEL,
            'jobId' => $request->jobId,
        ]);
        return redirect('/employees/list');
    }

    /**
     * Show employee details view.
     *
     * @return Response
     */
    public function showDetails($employeeId) {
        $employee = Employee::find($employeeId);
        return view('employees/details', [
            'employee' => $employee
        ]);
    }

    /**
     * Show employee edit view.
     *
     * @return Response
     */
    public function showEditForm($employeeId) {
        $employee = Employee::find($employeeId);
        return view('employees/edit', [
            'employee' => $employee
        ]);
    }

    /**
     * Handles user edit POST request.
     * @param Request $request
     * @param mixed $employeeId
     * @return Response
     */
    public function edit(Request $request, $employeeId) {
        /** Employee $employee  */
        $employee = Employee::find($employeeId);

        $validator = Validator::make($request->all(), [
                    'firstName' => 'required|max:255',
                    'lastName' => 'required|max:255',
                    'jobId' => 'required|exists:jobs,id',
                    'PESEL' => 'pesel|max:255',
        ]);

        if ($validator->fails()) {
            return redirect('/employees/edit/' . $employee->id)
                            ->withInput()
                            ->withErrors($validator);
        }

        $employee->firstName = $request->firstName;
        $employee->lastName = $request->lastName;
        $employee->PESEL = $request->PESEL;
        $employee->jobId = $request->jobId;
        $employee->save();
        $request->session()->flash('message', "Zmiany zostały zapisane!");
        return redirect('/employees/edit/' . $employee->id)
                        ->withInput();
    }

    /**
     * Handles ajax employee searches
     * Returns json list (in select2 format) of employees fitting to $searchTerm.
     * 
     * @param Request $request
     * @param string $searchTerm
     * @return Response
     */
    public function ajaxEmployeeSearch(Request $request, $searchTerm) {
        $searchTerm = strtolower($searchTerm);
        if($searchTerm == '*') $searchTerm = '';
        $employeesFound = DB::table('employees')->select(
                        DB::raw('CONCAT(firstName, " ", lastName) AS fullName'), 'id')
                ->where(DB::raw('CONCAT(firstName, " ", lastName)'), 'LIKE', "%$searchTerm%")
                ->orWhere(DB::raw('CONCAT(lastName, " ", firstName)'), 'LIKE', "%$searchTerm%")
                ->orderBy('id', 'asc')
                ->get();

        $jsonArray = [];
        $jsonArray['more'] = false;
        $jsonArray['results'] = [];
        foreach ($employeesFound as $employee) {
            $jsonArray['results'][] = [
                'id' => $employee->id,
                'text' => 'ID '.$employee->id.'. '.$employee->fullName
            ];
        }
        return response()->json($jsonArray);
    }

}
