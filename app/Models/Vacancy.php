<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vacancy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['job_id', 'status', 'start_date', 'end_date', 'description'];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function scheduledVacancies()
    {
        return $this->hasMany(ScheduledVacancy::class);
    }
}
