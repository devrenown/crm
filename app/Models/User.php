<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserType;
use App\Models\AttendanceTimestamp;
use App\Models\Attendance;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\OnboardingInvitation;
use App\Models\Company;
use App\Models\WorkReport;
use App\Models\EmployeeIdentityProof;
use App\Traits\BelongsToTenant;
use App\Models\Tenant;
use App\Models\EmployeeShift;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable, BelongsToTenant;
    use \Spatie\Permission\Traits\HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'firstname',
        'middlename',
        'lastname',
        'email',
        'gender',
        'username',
        'type',
        'password',
        'address',
        'country',
        'company',
        'reporting_manager',
        'sub_reporting_manager',
        'country_code',
        'dial_code', 'phone',
        'avatar',
        'remember_token', 'last_activity_at',
        'created_by',
        'is_active', 'active_role', 'is_onboarding_complete', 'is_term_accepted', 'is_online', 'lang', 'layout', 'color_scheme',
        'layout_width', 'layout_position', 'topbar_color', 'sidebar_size', 'sidebar_view', 'sidebar_color',
    ];

    public function hasPermissionForActiveRole(string $permission): bool
    {
        $role = activeRole();

        if (! $role) {
            return false;
        }

        return $this->hasPermissionTo($permission, $role);
    }

    public function canForActiveRole(string $permission): bool
    {
        return $this->hasPermissionForActiveRole($permission);
    }

   
    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'user_id');
    }
   
    public function assets()
    {
        return $this->hasMany(Asset::class, 'user_id');
    }

    public function family(){
        return $this->hasMany(UserFamilyInfo::class,'user_id');
    }

    public function employeeDetail(){
        return $this->hasOne(EmployeeDetail::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }

    public function attendanceTimestamps()
    {
        return $this->hasMany(AttendanceTimestamp::class,'user_id');
    }
    
    public function firstAttendanceToday(): HasOne
    {
        return $this->hasOne(Attendance::class, 'user_id')
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'asc');
    }

    public function lastAttendanceToday(): HasOne
    {
        return $this->hasOne(Attendance::class, 'user_id')
            ->whereDate('endDate', today())
            ->whereNotNull('endDate')
            ->latestOfMany('created_at');
    }

    public function presentInCurrentMonthCount()
    {
        return Attendance::where('user_id', $this->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->selectRaw('COUNT(DISTINCT DATE(created_at)) as total')
            ->value('total');
    }

    public function lateDaysCountInCurrentMonth()
    {
        $shift = $this->shift?->shift;
        if (!$shift || !$shift->start_time) {
            return 0;
        }

        $tz = app('tenant_timezone') ?? 'Asia/Kolkata';
        $graceMinutes = $shift->grace_minutes ?? 0;

        return Attendance::where('user_id', $this->id)
            ->whereMonth('created_at', now($tz)->month)
            ->whereYear('created_at', now($tz)->year)
            ->get()
           
            ->groupBy(function ($item) use ($tz) {
                return Carbon::parse($item->created_at)->timezone($tz)->toDateString();
            })
            
            ->filter(function ($records) use ($shift, $graceMinutes, $tz) {
                
                $firstPunchRecord = $records->sortBy('created_at')->first();
                $punchIn = Carbon::parse($firstPunchRecord->startDate)->timezone($tz);
                $shiftStart = Carbon::parse($punchIn->toDateString() . ' ' . $shift->start_time, $tz);
                $graceEnd = $shiftStart->copy()->addMinutes((int)$graceMinutes + 1);

                return $punchIn->greaterThan($graceEnd);
            })
            ->count();
    }

    public function clientDetail(){
        return $this->hasOne(ClientDetail::class);
    }

    public function getNameAttribute()
    {
        return "$this->firstname $this->middlename $this->lastname";
    }
    public function getFullNameAttribute()
    {
        return $this->getNameAttribute();
    }

    public function getPhoneNumberAttribute()
    {
        return "$this->dial_code $this->phone";
    }
    
    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class, 'tenant_id', 'id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'password' => 'hashed',
            'type' => UserType::class,
        ];
    }

    public function hasVerifiedPhone()
    {
        return !empty($this->phone_verified_at);
    }

    public function employeeIdList () 
    {
        return $this->hasMany(EmployeeIdentityProof::class,'user_id', 'id');
    }

    public function onboardingInvitation ()
    {
        return $this->hasOne(OnboardingInvitation::class, 'user_id');
    }

    public function subordinates()
    {
        return $this->hasMany(self::class, 'reporting_manager', 'id')->where('is_active', true);
    }

    public static function reportingManagerList ()
    {
        return self::where('is_active', true)->whereIn('id', function ($query) {
            $query->select('reporting_manager')
            ->from('users')
            ->whereNotNull('reporting_manager');
        })
        ->with('subordinates');
    }

    public function reportingManager () 
    {
        return $this->belongsTo(self::class, 'reporting_manager');
    }

    public function subReportingManager () 
    {
        return $this->belongsTo(self::class, 'sub_reporting_manager');
    }

    public function companyDetail () 
    {
        return $this->belongsTo(Company::class, 'company');
    }

    public function sameDayWorkReport ()
    {
        return $this->hasOne(WorkReport::class, 'user_id')->whereDate('created_at', now())->orderBy('created_at', 'desc');
    }

    public function workReports () 
    {
        return $this->hasMany(WorkReport::class, 'user_id');
    }
    
    public function leaveRequests()
    {
        return $this->hasMany(\App\Models\LeaveRequest::class);
    }
    
    public function usedLeaveDays()
    {
        return $this->leaveRequests()
            ->where('status', 'approved')
            ->get()
            ->sum(function ($req) {
    
                $start = \Carbon\Carbon::parse($req->start_date);
                $end   = \Carbon\Carbon::parse($req->end_date);
    
                return $start->diffInDays($end) + 1; // Leave days count
            });
    }

    public function usedLeaveDaysInCurrentMonth()
    {
        $tz = app('tenant_timezone') ?? 'Asia/Kolkata';

        return $this->leaveRequests()
            ->where('status', 'approved')
            ->whereMonth('start_date', now($tz)->month)
            ->get()
            ->sum(function ($req) {
    
                if (!is_null($req->approved_days)) {
                    return (float) $req->approved_days;
                }

                if (!is_null($req->days)) {
                    return (float) $req->days;
                }

    
                return 0;
            });
    }
    
    public function totalAllowedLeaveDays()
    {
        return \App\Models\LeaveType::where('tenant_id', $this->tenant_id)
                ->sum('max_days_per_year');
    }
    
    public function leaveBalance()
    {
        $allowed = $this->totalAllowedLeaveDays();
        $used = $this->usedLeaveDays();
    
        return max($allowed - $used, 0);
    }
    
    public function isEmployee(): bool
    {
        return $this->active_role === UserType::EMPLOYEE->value;
    }

    public static function admins () 
    {
        return self::where('type', UserType::ADMIN)->withoutGlobalScopes()->whereIsActive(true)->get();
    }

    public function userOrganization ()
    {
        return $this->hasOne(Tenant::class, 'id', 'tenant_id')->withoutGlobalScopes();
    }

    public function shift() {
        return $this->hasOne(EmployeeShift::class, 'user_id')->latestOfMany();
    }

    public function employeeShifts()
    {
        return $this->hasMany(EmployeeShift::class);
    }

    public function getDesignationAttribute()
    {
        return $this->employeeDetail?->designation;
    }

    public function getDepartmentAttribute()
    {
        return $this->employeeDetail?->department;
    }

    public function onboarding ()
    {
      return $this->hasOne(UserOnboarding::class, 'user_id');
    }
}
