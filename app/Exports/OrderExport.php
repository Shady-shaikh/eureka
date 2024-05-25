<?php

namespace App\Exports;

use App\Models\frontend\Orders;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderExport implements FromCollection,WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $sdate;
    protected $edate;

    function __construct($sdate, $edate)
    {
        $this->sdate = $sdate;
        $this->edate = $edate;
    }

    public function collection()
    {
        return collect(Orders::getOrdersDeatails($this->sdate, $this->edate));
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return [
            'Order Id',
            'Customer Name',
            'Customer Email',
            'Amount',
            'Payment Tracking Code',
            'Order Date'
        ];
    }
}
