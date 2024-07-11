<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jobs_table';

    protected $fillable = ['job_name', 'status',];

    public function vacancies()
    {
        return $this->hasMany(Vacancy::class);
    }
}
