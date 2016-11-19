<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Employee;

class WorkSession extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'work_sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employeeId', 'startDatetime', 'endDatetime', 'jobId', 'wagePerHour',
        'timeWorked', 'earnedAmount', 'paycheckId'
    ];

    /**
     * Return employee.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee() {
        return $this->belongsTo('App\Employee', 'employeeId', 'id');
    }

    /**
     * Returns job assigned to work session.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function job() {
        return $this->belongsTo('App\Job', 'jobId', 'id');
    }

    /**
     * Sets session end to provided timestamp
     * If no timestamp provided, current is taken
     * @param int $timestamp
     */
    public function finish($timestamp = null) {
        if ($timestamp === null) $timestamp = time();
        $this->endDatetime = date('Y-m-d H:i:s', $timestamp);
        $this->timeWorked = $timestamp - strtotime($this->startDatetime);
        $this->earnedAmount = $this->timeWorked / 3600 * $this->wagePerHour;
    }

    /**
     * Sets session start to provided timestamp
     * @param int $timestamp
     */
    public function setStart($timestamp) {
        $this->startDatetime = date('Y-m-d H:i:s', $timestamp);
    }

    /**
     * Returns time in format hours:minutes:seconds
     * @param int $numberOfSeconds
     * @return string Time formatted hours:minutes:seconds
     */
    public static function formatTime($numberOfSeconds) {
        $numberOfSeconds = intval($numberOfSeconds);
        $seconds = $numberOfSeconds % 60;
        $minutes = (($numberOfSeconds - $seconds) / 60) % 60;
        $hours = ($numberOfSeconds - $minutes * 60 - $seconds) / 3600;
        return sprintf('%02d', $hours) . ':' . sprintf('%02d', $minutes) . ':' . sprintf('%02d', $seconds);
    }

    /**
     * End datetime accessor
     *
     * @param  datetime  $value
     * @return mixed
     */
    public function getEndDatetimeAttribute($value) {
        return ($value != '0000-00-00 00:00:00' ? $value : '');
    }

    /**
     * End date accessor
     *
     * @return string date of end datetime
     */
    public function getEndDateAttribute() {
        if ($this->endDatetime == '') return '';
        return date("Y-m-d", strtotime($this->endDatetime));
    }

    /**
     * Start date accessor
     *
     * @return string date of start datetime
     */
    public function getStartDateAttribute() {
        return date("Y-m-d", strtotime($this->startDatetime));
    }

    /**
     * Creates session with provided employee and start timestamp
     * If no timestamp provided, current is taken
     * @param Employee $employee
     * @param int $timestamp
     */
    public static function startWorkSession(Employee $employee,
            $timestamp = NULL) {
        if ($timestamp === NULL) $timestamp = time();
        self::create([
            'jobId' => $employee->jobId,
            'employeeId' => $employee->id,
            'startDatetime' => date('Y-m-d H:i:s', $timestamp),
            'endDatetime' => '',
            'wagePerHour' => $employee->job->wagePerHour,
            'timeWorked' => 0,
            'earnedAmount' => 0,
            'paycheckId' => 0
        ]);
    }

    /**
     * Returns worked time in H:i:s format
     * @return String Formatted worked time
     */
    public function getTimeWorkedFormatted() {
        return self::formatTime($this->timeWorked);
    }

}
