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
            ->addColumn('job-title', function ($query) {
                if ($query->status && $query->title) {
                    // Return the list item with a div container using flex
                    return '<div class="d-flex justify-content-between align-items-center">
                                 <span>' . ucfirst($query->title) . '</span>' .
                        newGetStatusWithClass($query->status)
                        . '</div>';
                }
                return null;
            })
            ->addColumn('manager_title', function ($query) {
                if ($query->manager_id) {
                    // Return the list item with a div container using flex
                    return '<div class="d-flex justify-content-between align-items-center">
                                 <span>' . ucfirst($query->manager->title) . '</span>' .
                        newGetStatusWithClass($query->manager->status)
                        . '</div>';
                }
                return null;
            })
            ->addColumn('children_job_titles', function ($query) {
                $subordinates = $query->subordinates;  // Fetch related sub-departments

                if ($subordinates->isNotEmpty()) {
                    // Return a formatted list of sub-department names
                    return '<ul class="list-unstyled">' . $subordinates->map(function ($subJob) {

                            if ($subJob->status) {
                                // Return the list item with a div container using flex
                                return '<li class="mb-1">
                                            <div class="d-flex justify-content-between align-items-center" >
                                            <span>' . ucfirst($subJob->title) . '</span>' .
                                    newGetStatusWithClass($subJob->status)
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
            ->rawColumns(['action', 'image', 'children_job_titles', 'manager_title', 'status', 'job-title', 'contacts'])
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
            Column::make('job-title')->width(300),
            Column::make('short_title')->width(150),
            Column::make('contacts')->width(60),
            Column::make('manager_title')->width(300),
            Column::make('children_job_titles')->width(300),
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
