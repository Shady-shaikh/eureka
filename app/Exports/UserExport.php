<?php

namespace App\Exports;

use App\Models\frontend\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserExport implements FromCollection,WithHeadings
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
        return collect(User::getUsersDeatails($this->sdate, $this->edate));
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return [
            'Name',
            'Email',
            'Mobile Number',
            'Registered Date',
        ];
    }
}
