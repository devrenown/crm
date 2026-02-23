<?php

namespace App\DataTables;

use App\Models\LeaveRequest;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Button;
use Illuminate\Support\Facades\Auth;

class LeaveRequestDataTable extends DataTable
{
    public function dataTable($query): EloquentDataTable
    {
        $user = Auth::user();

        return (new EloquentDataTable($query))
            ->addIndexColumn()

            /* ===========================
             | EMPLOYEE NAME 
             =========================== */
            // ->addColumn('employee', function ($row) use ($user) {
            //     return !$user->isEmployee()
            //         ? ($row->user?->full_name ?? 'N/A')
            //         : '';
            // })

            ->addColumn('employee', function ($row) use ($user) {

                if (
                    activeRoleCan('view-all-leave') ||
                    activeRoleCan('view-team-leave') ||
                    activeRoleCan('view-direct-team-leave')
                ) {
                    return $row->user?->full_name ?? 'N/A';
                }

                return '';
            })

            /* ===========================
             | LEAVE TYPE
             =========================== */
            ->addColumn('leave_type', fn ($row) => $row->leaveType?->name ?? 'N/A')

            /* ===========================
             | DAYS (APPROVED > REQUESTED)
             =========================== */
            ->addColumn('days', function ($row) {
                return $row->approved_days ?? $row->days ?? 0;
            })

            /* ===========================
             | TERM INFO
             =========================== */
            ->addColumn('term_info', function ($row) {
                if ($row->term === 'Mixed' && !empty($row->term_details)) {
                    $parts = collect($row->term_details)->map(function ($d) {
                        return $d['term'] === 'Fullday'
                            ? $d['date'] . ': Full Day'
                            : ($d['term'] === 'Halfday'
                                ? $d['date'] . ': Half Day (' . ($d['half_day_type'] ?? '-') . ')'
                                : $d['date'] . ': Short Leave (' . ($d['short_leave_hours'] ?? 0) . ' hrs)');
                    });
                    return $parts->implode('<br>');
                }
                // fallback
                return $row->term === 'Halfday'
                    ? 'Half Day (' . ($row->half_day_type ?? '-') . ')'
                    : ($row->term === 'Shortleave'
                        ? 'Short Leave (' . ($row->short_leave_hours ?? 0) . ' hrs)'
                        : 'Full Day');
            })


            /* ===========================
             | EMPLOYEE REASON
             =========================== */
            ->addColumn('reason', function ($row) use ($user) {
                return !$user->isEmployee()
                    ? \Str::limit($row->reason, 40)
                    : '';
            })

            /* ===========================
             | DOCUMENT
             =========================== */
            ->addColumn('document', function ($row) {
                return $row->document_path
                    ? '<a href="' . asset('storage/' . $row->document_path) . '" 
                         target="_blank" class="btn btn-sm btn-primary">View</a>'
                    : '';
            })

            /* ===========================
             | STATUS BADGE
             =========================== */
            ->addColumn('status_badge', function ($row) {
                $map = [
                    'Pending'   => 'warning',
                    'Approved'  => 'success',
                    'Rejected'  => 'danger',
                    'Cancelled' => 'dark',
                ];

                $color = $map[$row->status] ?? 'secondary';

                return "<span class='badge bg-{$color}'>{$row->status}</span>";
            })

            /* ===========================
             | APPROVAL FLOW
             =========================== */
           ->addColumn('approval_levels', function ($row) {

                // If L2 approval is NOT required → show final status
                if (!$row->leaveType?->requires_l2_approval) {
                    return ucfirst($row->status);
                }

                // L1
                if ($row->rejected_at_level === 'L1') {
                    $l1 = 'L1 ❌';
                } elseif ($row->approved_level_1_on) {
                    $l1 = 'L1 ✔️';
                } else {
                    $l1 = 'L1 ⏳';
                }

                // L2
                if ($row->rejected_at_level === 'L2') {
                    $l2 = 'L2 ❌';
                } elseif ($row->approved_level_2_on) {
                    $l2 = 'L2 ✔️';
                } else {
                    $l2 = 'L2 ⏳';
                }

                return "{$l1} | {$l2}";
            })



            /* ===========================
             | ACTIONS
             =========================== */
            // ->addColumn('action', function ($row) {
            //     return view('pages.leaves.partials.actions', [
            //         'leave' => $row
            //     ])->render();
            // })

            ->addColumn('action', function ($row) {
                if (!auth()->user()->Can('view', $row)) {
                    return '';
                }

                return view('pages.leaves.partials.actions', [
                    'leave' => $row
                ])->render();
            })

            ->rawColumns([
                'document',
                'status_badge',
                'approval_levels',
                'action'
            ]);
    }


        public function query(LeaveRequest $model)
        {
            $user = Auth::user();
            \Log::info('Querying LeaveRequest', ['user_id' => $user->id]);
            $query = $model->newQuery()->with([
                'user:id,firstname,middlename,lastname,reporting_manager,sub_reporting_manager',
                'leaveType:id,name,requires_l2_approval'
            ])->where('tenant_id', $user->tenant_id);

            if (activeRoleCan('view-all-leave')) {

                // HR / Admin → everything

            } elseif (activeRoleCan('view-direct-team-leave')) {

                // Direct team only
                $query->whereHas('user', fn ($q) =>
                    $q->where('reporting_manager', $user->id)
                );

            } elseif (activeRoleCan('view-team-leave')) {

                // Team + sub team
                $query->whereHas('user', fn ($q) =>
                    $q->where('reporting_manager', $user->id)
                    ->orWhere('sub_reporting_manager', $user->id)
                );

            } elseif (activeRoleCan('view-leave')) {

                // Own only
                $query->where('user_id', $user->id);

            } else {

                // Safety
                $query->whereRaw('1 = 0');
            }
           
            // Apply filters
            if ($status = request('status')) {
                $query->where('status', $status);
            }

            if ($leaveType = request('leave_type')) {
                $query->where('leave_type_id', $leaveType);
            }

            if ($keyword = request('keyword')) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('reason', 'like', "%{$keyword}%")
                    ->orWhereHas('user', function ($u) use ($keyword) {
                        $u->whereRaw(
                            "CONCAT(firstname,' ',lastname) LIKE ?",
                            ["%{$keyword}%"]
                        );
                    });
                });
            }

            return $query;
        }





    public function html()
    {
        return $this->builder()
            ->setTableId('leave-requests-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Brtip')
            ->searching(false)
            ->orderBy(1)
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reload'),
            ]);
    }

    public function getColumns(): array
    {
        $user = Auth::user();

        $columns = [
            Column::make('DT_RowIndex')
                ->title('#')
                ->orderable(false)
                ->searchable(false),
        ];

        // if (!$user->isEmployee()) {
        //     $columns[] = Column::computed('employee')->title('Employee');
        // }

        if (activeRoleCan('view-team-leave') || activeRoleCan('view-direct-team-leave')|| activeRoleCan('view-all-leave')) {
            $columns[] = Column::computed('employee')->title('Employee');
        }

        $columns[] = Column::computed('leave_type')->title('Leave Type');
        $columns[] = Column::make('start_date')->title('Start Date');
        $columns[] = Column::make('end_date')->title('End Date');
        $columns[] = Column::computed('days')->title('Days');
        $columns[] = Column::computed('term_info')->title('Term')->orderable(false);


        $columns[] = Column::computed('status_badge')->title('Status')->orderable(false);
        $columns[] = Column::computed('approval_levels')->title('Output')->orderable(false);
        $columns[] = Column::computed('action')
            ->title('Actions')
            ->exportable(false)
            ->printable(false);

        return $columns;
    }

    protected function filename(): string
    {
        return 'LeaveRequests_' . now()->format('YmdHis');
    }
}
