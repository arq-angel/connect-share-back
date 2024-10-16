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
            ->addColumn('department_name', function ($query) {
                if ($query->name) {
                    // Return the list item with a div container using flex
                    return '<div class="d-flex justify-content-between align-items-center">
                                 <span>' . ucfirst($query->name) . '</span>'.
                        getStatusWithClass($query->status)
                        .'</div>';
                }
                return null;
            })
            ->addColumn('parent_department', function ($query) {
                if ($query->parent_id) {
                    // Return the list item with a div container using flex
                    return '<div class="d-flex justify-content-between align-items-center">
                                 <span>' . ucfirst($query->parentDepartment->name) . '</span>'.
                        getStatusWithClass($query->parentDepartment->status)
                        .'</div>';
                }
                return null;
            })
            ->addColumn('sub_departments', function ($query) {
                $subDepartments = $query->subDepartments;  // Fetch related sub-departments

                if ($subDepartments->isNotEmpty()) {
                    // Return a formatted list of sub-department names
                    return '<ul class="list-unstyled">' . $subDepartments->map(function ($subDep) {
                            if ($subDep->status) {
                                // Return the list item with a div container using flex
                                return '<li class="mb-1">
                                            <div class="d-flex justify-content-between align-items-center" >
                                            <span>' . ucfirst($subDep->name) . '</span>' .
                                    getStatusWithClass($subDep->status)
                                    . '</div>
                                            </li>';
                            }
                            return null;
                        })->implode('') . '</ul>';
                }
                return null;
            })
            ->addColumn('contacts', function ($query) {
                if ($query->directory_flag) {
                    return '
                        <div class="d-flex align-items-center justify-content-center">
                            <span class="badge bg-success text-white rounded-circle" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check"></i> <!-- FontAwesome check icon -->
                            </span>
                        </div>
                    ';
                }
                return
                    '<div class="d-flex align-items-center justify-content-center">
                            <span class="badge bg-danger text-white rounded-circle" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-times"></i> <!-- FontAwesome cross icon -->
                            </span>
                        </div>';
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
                    return '<ul class="list-unstyled">' . $query->jobTitles->map(function ($jobTitle) {

                            if ($jobTitle->status) {
                                // Return the list item with a div container using flex
                                return '<li class="mb-1">
                                            <div class="d-flex justify-content-between align-items-center" >
                                            <span>' . ucfirst($jobTitle->title) . '</span>' .
                                    getStatusWithClass($jobTitle->status)
                                    . '</div>
                                            </li>';
                            }
                            return null;
                        })->implode('') . '</ul>';
                }
                return null;
            })
            ->rawColumns(['image', 'action', 'job_titles', 'parent_department', 'sub_departments', 'department_name', 'contacts'])
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
            Column::make('department_name'),
            Column::make('short_name')->width(120),
            Column::make('contacts')->width(60),
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
