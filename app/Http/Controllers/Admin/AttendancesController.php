<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Enums\UserType;
use Carbon\CarbonPeriod;
use App\Models\Attendance;
use App\Models\AttendanceTimestamp;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class AttendancesController extends Controller
{
    
    public function index(Request $request){

        $pageTitle = __('Attendances');

        // $selectedMonth = $request->month ?? Carbon::now()->month;
        // $selectedYear = $request->year ?? Carbon::now()->year;
        $selectedMonth  = $request->filled('month') ? (int) $request->month : now()->month;
        $selectedYear   = $request->filled('year') ? (int) $request->year : now()->year;

        $years_range = CarbonPeriod::create(now()->subYears(10), Carbon::now()->addYears(10))->years();
        $days_in_month = Carbon::createFromDate($selectedYear, $selectedMonth,01)->daysInMonth;
        $users = User::where('is_active', true)->with(['attendances' => function ($query) use ($selectedMonth,$selectedYear) {
            $query->whereMonth('created_at', $selectedMonth)
                ->whereYear('created_at', $selectedYear)
                ->orderBy('created_at', 'desc')
                ->take(1);
        }])->where('type', UserType::EMPLOYEE);
        if(!empty($request->employee)){
            
            $keyword = trim($request->employee);

            $users = $users->where('email','LIKE','%'.$keyword.'%')
                        ->orWhere('firstname','LIKE','%'.$keyword.'%')
                        ->orWhere('middlename','LIKE','%'.$keyword.'%')
                        ->orWhere('lastname','LIKE','%'.$keyword.'%')
                        ->orWhereRaw("CONCAT_WS(' ', firstname, middlename, lastname) LIKE ?", ["%{$keyword}%"])
                        ->orWhereRaw("CONCAT_WS(' ', firstname, lastname) LIKE ?", ["%{$keyword}%"])
                        ->orWhere('username','LIKE','%'.$keyword.'%');
        }

        if (activeRole() === UserType::TL->value) {
            $users->where('reporting_manager', auth()->id());
        }
        $employees = $users->get();

        return view('pages.attendances.index',compact(
            'pageTitle','employees','years_range','days_in_month'
        ));
    }

    public function attendanceDetails(Request $request, Attendance $attendance)
    {
        $attendanceActivity = $attendance->timestamps()->get();
        
        $totalMinutes = $attendanceActivity->sum(function ($activity) {
            $in = \Carbon\Carbon::parse($activity->startTime);
            $out = \Carbon\Carbon::parse($activity->endTime);
            return $in->diffInMinutes($out);
        });
        
        $hours = intdiv($totalMinutes, 60);
        $minutes = $totalMinutes % 60;
    
        $totalHours = sprintf('%02d:%02d', $hours, $minutes);
        // $totalHours = $attendance->timestamps()->get()->sum('totalHours');
        return view('pages.attendances.attendance-details',compact(
            'attendance','totalHours','attendanceActivity'
        ));
    }

    public function attendanceHistory(Request $request)
    {
        $employeeId = decrypt($request->employee_id);

        if (!$employeeId) {
            return back()->with(notify('User Not Found!'));
        }

        // 1️⃣ Paginate distinct dates
        $datePaginator = AttendanceTimestamp::where('user_id', $employeeId)
            ->whereNotNull('attendance_id')
            ->selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->orderByDesc('date')
            ->paginate(10);

        // 2️⃣ Fetch all records for those dates
        $records = AttendanceTimestamp::where('user_id', $employeeId)
            ->whereIn(
                \DB::raw('DATE(created_at)'),
                $datePaginator->pluck('date')->toArray()
            )
            ->orderBy('created_at')
            ->get()
            ->groupBy(fn ($row) => $row->created_at->format('Y-m-d'))
            ->sortKeysDesc();

        return view('pages.attendances.history', [
            'pageTitle'   => 'Attendance History',
            'attendances' => $records,         
            'paginator'   => $datePaginator,  
        ]);
    }


}
