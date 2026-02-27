<?php

namespace App\Exports\Sheets;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Carbon;

class EmployeeAttendanceSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function collection()
    {
        return $this->user->attendances()
            ->with('punchTimestamps')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Punch In',
            'Punch Out',
            'Total Hours',
        ];
    }

    public function map($attendance): array
    {   
        $timestamps = $attendance->punchTimestamps;
        
        // $punchIn    = $attendance->timestamps->first()?->startTime;
        // $punchOut   = $attendance->timestamps->whereNotNull('endTime')->last()?->endTime;

        $punchIn = $timestamps->min('startTime');
        $punchOut = $timestamps->whereNotNull('endTime')->max('endTime');

        $totalMinutes = $timestamps->sum(function ($t) {
            return $t->endTime
                ? Carbon::parse($t->startTime)->diffInMinutes(Carbon::parse($t->endTime))
                : 0;
        });

        return [
            $attendance->created_at->format('d M Y'),
            $punchIn ? Carbon::parse($punchIn)->format('h:i A') : '',
            $punchOut ? Carbon::parse($punchOut)->format('h:i A') : '',
            sprintf('%02d:%02d', intdiv($totalMinutes, 60), $totalMinutes % 60),
        ];
    }

    public function title(): string
    {
        return substr($this->user->name, 0, 31);
    }
}
