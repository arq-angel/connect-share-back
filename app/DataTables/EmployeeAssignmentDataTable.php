<?php

namespace App\DataTables;

use App\Models\EmployeeAssignment;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class EmployeeAssignmentDataTable extends DataTable
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
                return '<a href="' . route('admin.assignment.edit', $query->id) . '" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                        <a href="' . route('admin.assignment.destroy', $query->id) . '" class="btn btn-danger delete-item"><i class="fas fa-trash"></i></a>';
            })
            ->addColumn('image', function($query) {
                if ($query->employee->image) {
                    return '<img src="'.asset($query->employee->image).'" style="width: 70px;" alt="'.$query->employee->name.'">';
                }
                return null;
            })
            ->addColumn('employee', function($query) {
                return $query->employee->first_name . ' ' . $query->employee->middle_name . ' ' . $query->employee->last_name;
            })
            ->addColumn('facility', function($query) {
                return $query->facility->name;
            })
            ->addColumn('job_title', function($query) {
                return $query->jobTitle->title;
            })
            ->addColumn('department', function($query) {
                return $query->department->name;
            })
            ->rawColumns(['image', 'action', 'employee'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(EmployeeAssignment $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('employeeassignment-table')
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
            Column::make('employee'),
            Column::make('facility'),
            Column::make('department'),
            Column::make('job_title'),
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
        return 'EmployeeAssignment_' . date('YmdHis');
    }
}
