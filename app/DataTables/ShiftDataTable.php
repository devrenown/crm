<?php

namespace App\DataTables;

use App\Models\Shift;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\SearchPane;
use Spatie\Menu\Laravel\Html;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Carbon\Carbon;

class ShiftDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))

            ->addIndexColumn()
            ->addColumn('time', function ($row) {
                return Carbon::parse($row->start_time)->format('H:i')
                    . ' - ' .
                    Carbon::parse($row->end_time)->format('H:i');
            })
            ->editColumn('status', function ($row) {
                return $row->status == 1 ? '<span class="badge bg-inverse-success">Active</span>' : '<span class="badge bg-inverse-danger">Inactive</span>';
            })
            ->editColumn('created_at', function ($row) {
                return format_date($row->created_at);
            })
            ->addColumn('action', function ($row) {
                $id = $row->id;
                return view('pages.shift.actions', compact(
                    'id'
                ));
            })
            ->rawColumns(['action', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Shift $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('shift-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bftip')
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
        $columns = [
            Column::make('DT_RowIndex')
            ->title('#')
            ->searchable(false)
            ->orderable(false),

            Column::make('name')->searchable(),

            Column::computed('time')
            ->title('Time')
            ->orderable(false)
            ->searchable(false),

            Column::make('break_minutes')->title('Break (min)'),
            Column::make('grace_minutes')->title('Grace (min)'),

            Column::make('status'),
            Column::make('created_at'),
        ];

        if (auth()->user()->canAny(['edit-shift', 'delete-shift'])) {
            $columns[] = Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-end');
        }

        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Shift_' . date('YmdHis');
    }
}