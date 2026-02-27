<?php

namespace App\DataTables;

use App\Models\WorkReport;
use App\Enums\UserType;
use App\Enums\TaskStatus;
use Spatie\Menu\Laravel\Html;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;


class WorkReportDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */

    public function dataTable($query)
    {
        return (new EloquentDataTable($query))

            ->addIndexColumn()

            ->addColumn('title', function($row){

                return $row->title;

            })

            ->addColumn('description', function($row){

                return $row->description ?? '';

            })

            ->addColumn('project', function ($row) {

                if (isset($row->project)) {

                    return $row->project->name;

                }

            })

            ->addColumn('status', function($row){

                if (isset($row->status)) {

                    return sprintf(

                        '<span class="badge bg-inverse-%s">%s</span>',

                        $row->status->badgeClass(),

                        e($row->status->label())

                    );

                }
                
            })

            ->addColumn('created_at', function($row){

                if(!empty($row->created_at)){

                    return format_date($row->created_at);

                }

            })

            ->addColumn('action', function($row){

                    $id = $row->id;

                    return view('pages.work-report.action',compact('id'));

            })

            ->rawColumns(['description','action', 'status']);

    }



    /**

     * Get the query source of dataTable.

     */

    public function query()
    {
        return WorkReport::where('user_id', auth()->user()->id)->whereNotNull('title')->orderBy('id', 'desc')->newQuery();
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



            Column::make('title')->searchable(true),

            Column::make('description')->width(200),

            Column::make('project')->width(200),

            Column::make('status'),

            Column::make('created_at'),

            Column::computed('action')

              ->exportable(false)

              ->printable(false)

              ->width(60)

              ->visible(auth()->user()->canAny(['edit-work-task','delete-work-task']))

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

