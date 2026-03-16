<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CancelExpiredAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:cancel-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tất cả các lịch ở trạng thái chờ khám sẽ bị tự động chuyển thành đã hủy sau khi qua 1h so với thời gian khám dự tính';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $expiredThreshold = $now->subHour();

        $count = Appointment::where('status', 'pending')
            ->where(function ($query) use ($expiredThreshold) {
                $query->where('date', '<', $expiredThreshold->toDateString())
                    ->orWhere(function ($q) use ($expiredThreshold) {
                        $q->where('date', '=', $expiredThreshold->toDateString())
                          ->where('time', '<', $expiredThreshold->toTimeString());
                    });
            })
            ->update(['status' => 'cancel']);

        if ($count > 0) {
            $this->info("Đã tự động hủy {$count} lịch hẹn hết hạn.");
            Log::info("Auto-cancelled {$count} expired appointments.");
        } else {
            $this->info("Không có lịch hẹn nào hết hạn.");
        }

        return 0;
    }
}
