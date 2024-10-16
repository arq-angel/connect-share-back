<?php

namespace App\DataTables;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class FacilityDataTable extends DataTable
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
                return '<a href="' . route('admin.facility.edit', $query->id) . '" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                        <a href="' . route('admin.facility.destroy', $query->id) . '" class="btn btn-danger delete-item"><i class="fas fa-trash"></i></a>';
            })
            ->addColumn('image', function ($query) {
                if ($query->image) {
                    return '<img src="' . asset($query->image) . '" style="width: 70px;" alt="' . $query->name . '">';
                }
                return null;
            })
            ->addColumn('departments', function ($query) {
                // Check if the departments relation exists and has data
                if ($query->departments && $query->departments->isNotEmpty()) {
                    return '
                        <ul class="list-unstyled">
                            ' . implode('', $query->departments->map(function ($department) {
                            if ($department->status) {
                                // Return the list item with a div container using flex
                                return '<li class="mb-1">
                                            <div class="d-flex justify-content-between align-items-center" >
                                            <span>' . ucfirst($department->name) . '</span>' .
                                    getStatusWithClass($department->status)
                                    . '</div>
                                            </li>';
                            }
                        })->toArray()) . '
                        </ul>
                    ';
                }
                return null;
            })
            ->addColumn('established_date', function ($query) {
                return date('d-m-Y', strtotime($query->created_at));
            })
            ->rawColumns(['image', 'action', 'departments', 'established_date'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Facility $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('facility-table')
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
            Column::make('departments')->width(300),
            Column::make('established_date'),
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
        return 'Facility_' . date('YmdHis');
    }
}
