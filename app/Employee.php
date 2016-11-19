<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model {

    protected $table = 'employees';
    protected $fillable = [
        'firstName', 'lastName', 'PESEL', 'jobId'
    ];

    /**
     * Returns job assigned to employee.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function job() {
        return $this->belongsTo('App\Job', 'jobId', 'id');
    }

    /**
     * Returns employee's worksessions.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function workSessions() {
        return $this->hasMany('App\WorkSession', 'employeeId', 'id');
    }

    /**
     * Is employee logged
     * (has ongoing work session)
     * @return boolean
     */
    public function isLogged() {
        return ($this->currentWorkSession()->count() != 0);
    }

    /**
     * Get ongoing work session if such exists
     * @return App\WorkSession
     */
    public function currentWorkSession() {
        return $this->workSessions()->where('endDatetime', '0000-00-00 00:00:00')->take(1);
    }

    /**
     * Logs employee out (finishes current work session)
     * Writes directly to database
     */
    public function logOut() {
        $currentWorkSessionQuery = $this->currentWorkSession();
        if ($currentWorkSessionQuery->count() == 0) return;
        $currentWorkSession = $currentWorkSessionQuery->get()[0];
        $currentWorkSession->finish();
        $currentWorkSession->save();
    }

    /**
     * Logs employee in 
     * Writes directly to database
     */
    public function logIn() {
        if($this->isLogged()) return;
        WorkSession::startWorkSession($this);
    }
    
    /**
     * Full name accessor
     * Returns first and last name
     * @return string Full name
     */
    public function getFullNameAttribute() {
        return $this->firstName.' '.$this->lastName;
    }

}
