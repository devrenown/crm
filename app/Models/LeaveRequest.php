<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;
use Carbon\Carbon;

class LeaveRequest extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'leave_requests';

    /* -----------------------------------------------------------------
     |  MASS ASSIGNMENT
     |------------------------------------------------------------------*/
    protected $fillable = [
        'tenant_id',
        'user_id',
        'leave_type_id',

        'start_date',
        'end_date',

        'days',
        'approved_days',

        // legacy (kept for backward compatibility)
        'short_leave_hours',
        'is_half_day',
        'half_day_type',

        // modern
        'term',
        'term_details',

        'reason',
        'document_path',
        'document_mime',

        'status',
        'approval_stage',
        'is_balance_applied',

        // Approval
        'approved_level_1_id',
        'approved_level_1_on',
        'approved_level_1_remark',
        'approved_level_2_id',
        'approved_level_2_on',
        'approved_level_2_remark',

        // Cancellation
        'cancelled_by',
        'cancelled_on',
        'cancellation_reason_l1',
        'cancellation_reason_l2',

        // Rejection
        'rejected_by',
        'rejected_on',
        'rejected_at_level',
        'rejection_reason_l1',
        'rejection_reason_l2',

        'remarks',
        'ip_address',
        'user_agent',
    ];

    /* -----------------------------------------------------------------
     |  CASTS
     |------------------------------------------------------------------*/
    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date'   => 'date:Y-m-d',

        'approved_level_1_on' => 'datetime',
        'approved_level_2_on' => 'datetime',
        'cancelled_on'        => 'datetime',
        'rejected_on'         => 'datetime',

        'is_half_day'        => 'boolean',
        'is_balance_applied' => 'boolean',

        'term_details'       => 'array',
    ];

    /* -----------------------------------------------------------------
     |  MODEL EVENTS
     |------------------------------------------------------------------*/
    protected static function booted()
    {
        static::creating(function (self $leave) {
            $leave->calculateDays();
        });

        static::updating(function (self $leave) {
            if ($leave->isDirty('term_details') || $leave->isDirty('term')) {
                $leave->calculateDays();
            }
        });
    }

    /* -----------------------------------------------------------------
     |  BUSINESS LOGIC
     |------------------------------------------------------------------*/

    /**
     * Calculate leave days (single source of truth)
     */
   public function calculateDays(): void
{
    // Decode term_details if it's a JSON string
    $termDetails = $this->term_details;
    if (is_string($termDetails)) {
        $termDetails = json_decode($termDetails, true) ?? [];
    }

    if (!empty($termDetails)) {
        $total = 0;

        foreach ($termDetails as $day) {
            $total += match ($day['term']) {
                'Fullday'   => 1,
                'Halfday'   => 0.5,
                'Shortleave'=> ($day['short_leave_hours'] ?? 0) / 8,
                default     => 0,
            };
        }

        $this->days = round($total, 2);
        return;
    }

    // LEGACY MODE
    switch ($this->term) {
        case 'Shortleave':
            $this->days = ($this->short_leave_hours ?? 0) / 8;
            $this->end_date = $this->start_date;
            break;

        case 'Halfday':
            $this->days = 0.5;
            $this->end_date = $this->start_date;
            break;

        case 'Fullday':
        default:
            $end = $this->end_date ?? $this->start_date;
            $this->days = Carbon::parse($this->start_date)
                ->diffInDays(Carbon::parse($end)) + 1;
            break;
    }
}


    /**
     * Human-readable term label
     */
    public function getTermLabelAttribute(): string
    {
        return match ($this->term) {
            'Shortleave' => 'Short Leave',
            'Halfday'    => 'Half Day',
            default      => 'Full Day',
        };
    }

    /* -----------------------------------------------------------------
     |  STATUS DERIVATION (AUTHORITATIVE)
     |------------------------------------------------------------------*/

    public function getFinalStatusAttribute(): string
    {
        // Explicit status always wins
        if ($this->status === 'Cancelled') {
            return 'Cancelled';
        }

        if ($this->status === 'Rejected') {
            return 'Rejected';
        }

        if ($this->status !== 'Approved') {
            return $this->status ?? 'Pending';
        }

        $requiresL2 = (int) ($this->leaveType->requires_l2_approval ?? 0);

        //  L2 NOT REQUIRED → L1 IS FINAL
        if (!$requiresL2 && $this->approved_level_1_id) {
            return 'Approved';
        }

        //  L2 REQUIRED AND DONE
        if ($requiresL2 && $this->approved_level_2_id) {
            return 'Approved';
        }

        //  Waiting for L2
        if ($requiresL2 && $this->approved_level_1_id) {
            return 'Partially Approved';
        }

        return 'Pending';
    }


    public function levelStatus(string $level): ?string
    {
        return match ($level) {
            'L1' => $this->rejection_reason_l1 ? 'Rejected'
                : ($this->cancellation_reason_l1 ? 'Cancelled'
                : ($this->approved_level_1_id ? 'Approved' : null)),

            'L2' => $this->rejection_reason_l2 ? 'Rejected'
                : ($this->cancellation_reason_l2 ? 'Cancelled'
                : ($this->approved_level_2_id ? 'Approved' : null)),

            default => null,
        };
    }

    public function getRejectionReasonAttribute(): ?string
    {
        return match ($this->rejected_at_level) {
            'L1' => $this->rejection_reason_l1,
            'L2' => $this->rejection_reason_l2,
            default => null,
        };
    }

    public function getCancellationReasonAttribute(): ?string
    {
        return $this->cancellation_reason_l2
            ?? $this->cancellation_reason_l1;
    }

    /* -----------------------------------------------------------------
     |  RELATIONSHIPS
     |------------------------------------------------------------------*/

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function level1Approver()
    {
        return $this->belongsTo(User::class, 'approved_level_1_id');
    }

    public function level2Approver()
    {
        return $this->belongsTo(User::class, 'approved_level_2_id');
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function scopeVisibleTo($query, User $user)
    {
        // View all
        if (activeRoleCan('view-all-leave')) {
            return $query;
        }

        // Team + sub team
        if (activeRoleCan('view-team-leave')) {
            return $query->whereHas('user', fn ($q) =>
                $q->where('reporting_manager', $user->id)
                ->orWhere('sub_reporting_manager', $user->id)
            );
        }

        // Direct team only
        if (activeRoleCan('view-direct-team-leave')) {
            return $query->whereHas('user', fn ($q) =>
                $q->where('reporting_manager', $user->id)
            );
        }

        // Own only
        if (activeRoleCan('view-leave')) {
            return $query->where('user_id', $user->id);
        }

        // No permission
        return $query->whereRaw('1 = 0');
    }

}
