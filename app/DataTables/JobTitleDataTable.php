<?php

namespace App\DataTables;

use App\Models\JobTitle;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class JobTitleDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                return '<a href="' . route('admin.job-title.edit', $query->id) . '" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                        <a href="' . route('admin.job-title.destroy', $query->id) . '" class="btn btn-danger delete-item"><i class="fas fa-trash"></i></a>';
            })
            ->addColumn('manager', function ($query) {
                if ($query->manager_id) {
                    return '<p>' . $query->manager->title . '</p>';
                }
                return null;
            })
            ->addColumn('sub_job_titles', function ($query) {
                $subordinates = $query->subordinates;  // Fetch related sub-departments

                if ($subordinates->isNotEmpty()) {
                    // Return a formatted list of sub-department names
                    return '<ul class="list-unstyled">' . $subordinates->map(function ($subJob) {
                            return '<li>' . $subJob->title . '</li>';
                        })->implode('') . '</ul>';
                }
                return null;
            })
            ->addColumn('image', function($query) {
                if ($query->image) {
                    return '<img src="'.asset($query->image).'" style="width: 70px;" alt="'.$query->name.'">';
                }
                return null;
            })
            ->rawColumns(['action', 'image', 'sub_job_titles', 'manager'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(JobTitle $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('jobtitle-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(0)
                    ->selectStyleSingle()
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

            Column::make('id')->width(60),
            Column::make('image')->width(100),
            Column::make('title'),
            Column::make('short_title'),
            Column::make('manager'),
            Column::make('sub_job_titles'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'JobTitle_' . date('YmdHis');
    }
}
