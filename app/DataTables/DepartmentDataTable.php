<?php

namespace App\DataTables;

use App\Models\Department;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DepartmentDataTable extends DataTable
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
                return '<a href="' . route('admin.department.edit', $query->id) . '" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                        <a href="' . route('admin.department.destroy', $query->id) . '" class="btn btn-danger delete-item"><i class="fas fa-trash"></i></a>';
            })
            ->addColumn('image', function ($query) {
                if ($query->image) {
                    return '<img src="' . asset($query->image) . '" style="width: 70px;" alt="' . $query->name . '">';
                }
                return null;
            })
            ->addColumn('parent_department', function ($query) {
                if ($query->parent_id) {
                    return '<p>' . $query->parentDepartment->name . '</p>';
                }
                return null;
            })
            ->addColumn('sub_departments', function ($query) {
                $subDepartments = $query->subDepartments;  // Fetch related sub-departments

                if ($subDepartments->isNotEmpty()) {
                    // Return a formatted list of sub-department names
                    return '<ul class="list-unstyled">' . $subDepartments->map(function ($subDept) {
                            return '<li>' . $subDept->name . '</li>';
                        })->implode('') . '</ul>';
                }
                return null;
            })
            ->addColumn('image', function ($query) {
                if ($query->image) {
                    return '<img src="' . asset($query->image) . '" style="width: 70px;" alt="' . $query->name . '">';
                }
                return null;
            })
            ->addColumn('job_titles', function ($query) {
                // Check if the departments relation exists and has data
                if ($query->jobTitles && $query->jobTitles->isNotEmpty()) {
                    return '
                        <ul class="list-unstyled">
                            ' . implode('', $query->jobTitles->map(function ($jobTitle) {
                            return '<li>' . e($jobTitle->title) . '</li>';
                        })->toArray()) . '
                        </ul>
                    ';
                }
                return null;
            })
            ->rawColumns(['image', 'action', 'job_titles', 'parent_department', 'sub_departments'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Department $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('department-table')
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
            Column::make('name'),
            Column::make('short_name'),
            Column::make('parent_department'),
            Column::make('sub_departments'),
            Column::make('job_titles'),
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
        return 'Department_' . date('YmdHis');
    }
}
