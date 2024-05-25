<?php

namespace App\DataTables;

// use App\Models\ModelDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ModelDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'modeldatatable.action');
    }


    // public function columns()
    // {
    //     return [
    //         // Other columns
    //         Column::make('name')
    //             ->title('Name')
    //             ->data('name')
    //             ->filter(function ($query, $keyword) {
    //                 // Apply search logic for the 'name' column
    //                 $query->where('name', 'like', '%' . $keyword . '%');
    //             }),
    //         // Other columns
    //     ];
    // }
    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ModelDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ModelDataTable $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('modeldatatable-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons(
                Button::make('create'),
                Button::make('export'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
            Column::make('id'),
            Column::make('add your columns'),
            Column::make('created_at'),
            Column::make('updated_at'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Model_' . date('YmdHis');
    }
}
