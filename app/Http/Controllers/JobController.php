<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Job;
use Validator;

/**
 * Job-related views controller
 * @package App\Http\Controllers
 */
class JobController extends Controller {

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
    public function showNewForm() {
        return view('jobs/new');
    }

    /**
     * Handles job create POST request.
     * @param Request $request
     * @return Response
     */
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
                    'name' => 'required|max:255',
                    'wagePerHour' => 'required|between:0,9999.99',
        ]);

        if ($validator->fails()) {
            return redirect('/jobs/new')
                            ->withInput()
                            ->withErrors($validator);
        }

        $request->session()->flash('message', "Stanowisko zostało utworzone!");
        Job::create([
            'name' => $request->name,
            'wagePerHour' => $request->wagePerHour,
            'isActive' => (!empty($request->isActive) ? 1 : 0),
        ]);
        return redirect('/jobs/list');
    }

    /**
     * Show employee edit view.
     *
     * @return Response
     */
    public function showEditForm($jobId) {
        $job = Job::find($jobId);
        return view('jobs/edit', [
            'job' => $job
        ]);
    }

    /**
     * Handles job edit POST request.
     * @param Request $request
     * @param mixed $employeeId
     * @return Response
     */
    public function edit(Request $request, $jobId) {
        $job = Job::find($jobId);

        $validator = Validator::make($request->all(), [
                    'name' => 'required|max:255',
                    'wagePerHour' => 'required|between:0,9999.99',
        ]);

        if ($validator->fails()) {
            return redirect('/jobs/edit/' . $job->id)
                            ->withInput()
                            ->withErrors($validator);
        }

        $job->name = $request->name;
        $job->wagePerHour = $request->wagePerHour;
        $job->isActive = (!empty($request->isActive) ? 1 : 0);
        $job->save();
        $request->session()->flash('message', "Zmiany zostały zapisane!");
        return redirect('/jobs/edit/' . $job->id)
                        ->withInput();
    }

    /**
     * Show jobs list.
     *
     * @return Response
     */
    public function showList() {
        $jobs = Job::orderBy('isActive', 'desc')->get();
        return view('jobs/list', [
            'jobs' => $jobs
        ]);
    }

}
