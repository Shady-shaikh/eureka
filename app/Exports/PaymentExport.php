<?php

namespace App\Exports;

use App\Models\frontend\PaymentInfo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentExport implements FromCollection,WithHeadings
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
        return collect(PaymentInfo::getPaymentsDeatails($this->sdate, $this->edate));
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return [
            'Customer Name',
            'Customer Email',
            'Transaction Id',
            'Amount',
            'Payment Tracking Code',
            'Payment Date'
        ];
    }
}
