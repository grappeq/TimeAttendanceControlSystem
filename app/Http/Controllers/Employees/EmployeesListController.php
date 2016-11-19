<?php

namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Employee;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Employee list controller class
 * @package App\Http\Controllers
 */
class EmployeesListController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show users list.
     * @param Request $request
     * @return Response
     */
    public function index(Request $request) {
        if (!empty($request->action)) {
            $this->handleEmployeeActions($request);
            return redirect('employees/list');
        }

        $employees = Employee::orderBy('created_at', 'asc')->get();
        return view('employees/list', [
            'employees' => $employees
        ]);
    }

    /**
     * Handles Login/Logout actions
     * @param Request $request
     */
    private function handleEmployeeActions(Request $request) {
        try {
            $employee = Employee::findOrFail($request->employeeId);
            if ($request->action == 'login') {
                if (!$employee->isLogged()) {
                    $employee->logIn();
                    $request->session()->flash('message', "Pracownik został zalogowany!");
                    $request->session()->flash('alert-class', "alert-success");
                } else {
                    $request->session()->flash('message', "Pracownik już jest zalogowany!");
                    $request->session()->flash('alert-class', "alert-error");
                }
            } else if ($request->action == 'logout') {
                if ($employee->isLogged()) {
                    $employee->logOut();
                    $request->session()->flash('message', "Pracownik został wylogowany!");
                    $request->session()->flash('alert-class', "alert-success");
                } else {
                    $request->session()->flash('message', "Pracownik już jest wylogowany!");
                    $request->session()->flash('alert-class', "alert-error");
                }
            }
        } catch (ModelNotFoundException $exeption) {
            $request->session()->flash('message', "Pracownik o podanym Id nie istnieje!");
            $request->session()->flash('alert-class', "alert-error");
        }
    }

}
