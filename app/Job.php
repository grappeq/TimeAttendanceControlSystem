<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model {

    protected $table = 'jobs';
    protected $fillable = [
        'name', 'wagePerHour', 'isActive'
    ];

    /**
     * Returns array of employees with this job
     * @return App\Employee[]
     */
    public function employees() {
        return $this->hasMany('App\Employee');
    }

}
