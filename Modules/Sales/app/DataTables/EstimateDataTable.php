<?php

namespace App\DataTables;

use App\Models\Estimate;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class EstimateDataTable extends \Yajra\DataTables\Services\DataTable
{
    /**
     * Build the DataTable class.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn() // for DT_RowIndex
            ->addColumn('client', function ($row) {
                return $row->client->fullname ?? '';
            })
            ->addColumn('grand_total', function ($row) {
                return LocaleSettings('currency_symbol') . number_format($row->grand_total, 2);
            })
            ->addColumn('status', function ($row) {
                return ucfirst($row->status);
            })
            ->addColumn('created_at', function ($row) {
                return format_date($row->created_at);
            })
            ->addColumn('action', function ($row) {
                $id = $row->id;
                return view('pages.estimates.actions', compact('id'));
            })
            ->rawColumns(['action']) // if you use buttons/links in action
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Estimate $model): QueryBuilder
    {
        return $model->newQuery()->with('client');
    }

    /**
     * Optional HTML builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('estimate-table')
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
     * Define the columns.
     */
    public function getColumns(): array
    {
        $columns = [
            Column::make('DT_RowIndex')->title('#')->searchable(false)->orderable(false),
            Column::make('est_no')->title(__('Estimate Number')),
            Column::make('client')->title(__('Client')),
            Column::make('start_date')->title(__('Estimate Date')),
            Column::make('due_date')->title(__('Expiry Date')),
            Column::make('grand_total')->title(__('Amount')),
            Column::make('status')->title(__('Status')),
            Column::make('created_at')->title(__('Date Created')),
        ];

        if (auth()->user()->canAny(['edit-estimate', 'delete-estimate'])) {
            $columns[] = Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-end');
        }

        return $columns;
    }

    protected function filename(): string
    {
        return 'Estimates_' . date('YmdHis');
    }
}
