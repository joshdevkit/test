<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use App\Notifications\OverdueNotification;
use Illuminate\Support\Facades\Notification;

class CheckOverdueSupplies extends Command
{

    protected $signature = 'supplies:check-overdue';
    protected $description = 'Check for overdue supplies and send notifications';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    /**
     * The console command description.
     *
     * @var string
     */

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now();

        // Find all office requests with "Received" status, overdue by more than 3 days, and not yet notified
        $overdueRequests = DB::table('office_requests')
            ->leftJoin('supplies', 'office_requests.item_id', '=', 'supplies.id')
            ->leftJoin('users', 'office_requests.requested_by', '=', 'users.id')
            ->select(
                'office_requests.*',
                'supplies.item as supply_item',
                'users.name as requested_by_name',
                'users.email as requested_by_email',
                'users.id as user_id'
            )
            ->where('office_requests.item_type', '=', 'Supplies')
            ->where('office_requests.status', '=', 'Received') // Only select requests with 'Received' status
            ->whereDate('office_requests.updated_at', '<', $today->subDays(3))  // Overdue by 3 days
            ->where('office_requests.is_notified', '=', false)  // Ensure notification hasn't been sent
            ->get();

        foreach ($overdueRequests as $request) {
            // Find the user who needs to be notified
            $user = User::find($request->user_id);

            if ($user) {
                // Send notification to the user (it will be stored in the database)
                $user->notify(new OverdueNotification($request));

                // Update the office request to mark that notification has been sent
                DB::table('office_requests')
                    ->where('id', $request->id)
                    ->update(['is_notified' => true]);
            }
        }

        $this->info('Overdue supply notifications have been sent.');
    }
}
