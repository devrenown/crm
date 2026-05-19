<?php

namespace App\DataTables;

use App\Models\WorkReport;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enums\UserType;

class AdminWorkReportDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     */

    public function dataTable($query)
    {
        return (new EloquentDataTable($query))

            ->addIndexColumn()
            ->addColumn('employee', function ($row) {

                $imageUrl = $row->user && $row->user->avatar 
                ? asset('/storage/' . $row->user->avatar)
                : asset('/images/placeholder.jpg');

                $employee = "
                   <div> 
                     <img src='{$imageUrl}' alt='Avatar' class='rounded-circle me-2' style='width:40px; height:40px;'>
                     <span>{$row->user->fullname}</span>
                   </div>
                ";

                return $employee;
            })

            ->addColumn('title', function ($row) {
                return $row->title;
            })

            ->addColumn('project', function ($row) {
                return $row->project ? $row->project->name : 'N/A';
            })

            ->addColumn('status', function ($row) {
                return $row->status ? sprintf(
                    '<span class="badge bg-inverse-%s">%s</span>',
                    $row->status->badgeClass(),
                    e($row->status->label())
                ) : 'N/A';
            })

            ->addColumn('created_at', function ($row) {
                // return $row->created_at->format('d M Y, h:i A');
                return tz($row->created_at, 'd M Y, h:i A');
            })

            ->addColumn('action', function ($row) {
                $id     = $row->id;
                $emp_id = $row->user->id;
                return view('pages.work-report.action', compact('id', 'emp_id'));
            })

            ->rawColumns(['employee', 'status', 'action']);
    }

    /**
     * Get the query source of dataTable.

     */

    public function query()
    {

        $filter = request('filter') ?? 'all';
        $query = WorkReport::with(['user', 'project'])

        ->whereNotNull('title')
        ->whereIn('id', function ($query) {
            $query->select(DB::raw('MAX(id)'))
                  ->from('work_reports')
                  ->groupBy('user_id');

        })
        ->latest();

        if ($filter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($filter === '7days') {
            $query->where('created_at', '>=', Carbon::now()->subDays(7));
        } elseif ($filter === '1month') {
            $query->where('created_at', '>=', Carbon::now()->subMonth());
        } elseif ($filter === '1year') {
            $query->where('created_at', '>=', Carbon::now()->subYear());
        }

        if (activeRole() === UserType::TL->value) {
            $query->whereHas('user', function ($q) {
                $q->where('reporting_manager', auth()->id());
            });
        }

        return $query;
    }


    /**
     * Optional method if you want to use the html builder.
     */

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('tasks-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->buttons([

                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */

    public function getColumns(): array

    {
        return [
            Column::make('DT_RowIndex')
                ->title('#')
                ->searchable(false)
                ->orderable(false),

            Column::make('employee'),
            Column::make('title'),
            Column::make('project'),
            Column::make('status'),
            Column::make('created_at')->searchable(true),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->visible(auth()->user()->canAny(['edit-work-task', 'delete-work-task']))
                ->addClass('text-end'),

        ];
    }


    /**
     * Get the filename for export.
     */

    protected function filename(): string
    {
        return 'Task_' . date('YmdHis');
    }

}

