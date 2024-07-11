<?php

namespace App\Console\Commands;

use App\Models\ScheduledVacancy;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeletePastScheduledVacancies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-past-scheduled-vacancies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Soft delete past scheduled vacancies';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now();

        // Soft delete past scheduled vacancies
        ScheduledVacancy::whereDate('end_date', '<', $today)->delete();

        $this->info('Past scheduled vacancies deleted successfully.');
    }
}
