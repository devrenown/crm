<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\EmployeeAttendanceSheet;
use App\Models\User;
use App\Enums\UserType;

class AttendanceMultiUserExport implements WithMultipleSheets
{
    public function sheets (): array
    {
        $sheets = [];
        
        User::with('attendances')->where('is_active', true)->whereNotIn('type', [UserType::ADMIN, UserType::CLIENT])->chunk(50, function ($users) use (&$sheets) {
            foreach ($users as $user) {
                $sheets[] = new EmployeeAttendanceSheet($user);
            }
        });
        
        return $sheets;
    }
}
