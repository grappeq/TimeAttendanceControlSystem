<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paycheck extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paychecks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'periodStartDatetime', 'periodEndDatetime', 'timeWorked', 'earnings', 'employeeId'
    ];

    /**
     * Return employee.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee() {
        return $this->belongsTo('App\Employee', 'employeeId', 'id');
    }

    /**
     * Return employee.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\User', 'created_by', 'username');
    }
    
    public function getTimeWorkedFormatted() {
        return WorkSession::formatTime($this->timeWorked);    
    }

}
