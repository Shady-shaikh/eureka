<?php

namespace App\Exports;

use App\Models\backend\Products;
use App\Models\frontend\Orders;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection,WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */




    public function collection()
    {
        return collect(Products::getAllProducts());
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return [
            'Product Title',
            'Product Sub Title',
            'Product Price',
            'Product Discounted Price',
            'Product Discount',
            'Product Quantity',
            'Product Type',
            'Product Size'
        ];
    }
}
