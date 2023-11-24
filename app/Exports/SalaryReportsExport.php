<?php

namespace App\Exports;

use App\Models\SalaryReport;
use Maatwebsite\Excel\Concerns\{Exportable, FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles};
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class SalaryReportsExport implements WithMapping, WithHeadings, ShouldAutoSize, FromCollection, WithStyles

{
    use Exportable;

    protected array $filters = [];

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $data = $this->getExportData();

        return new Collection($data);
    }

    public function headings(): array
    {
        return [
            '#',
            'User',
            'Position',
            'Salary',
            'Work Days',
            'Actual Days',
            'Calculated Salary',
            'Prize',
            'Vacation',
            'Total',
            'Salary Tax',
            'Pension Fund',
            'ISH',
            'ITSH',
            'Employer Pension Fund',
            'Employer ISH',
            'Employer ITSH',
            'Total Deductions',
            'Amount to be Paid',
            'Advance',
            'Total Amount to be Paid',
        ];
    }


    public function map($row): array
    {
        $totalSalary = $row->salary / $row->working_days * $row->actual_days;
        if ($totalSalary <= 200) {
            $employeeFund = $totalSalary * 0.03;
        } else
            $employeeFund = 6 + ($totalSalary - 200) * 0.10;

        if ($totalSalary <= 8000) {
            $employeeItsh = $totalSalary * 0.02;
        } else {
            $employeeItsh = 80 + ($totalSalary - 8000) * 0.005;
        }

        if ($totalSalary <= 200) {
            $employerFund = $totalSalary * 0.22;
        } else {
            $employerFund = 44 + ($totalSalary - 200) * 0.15;
        }

        if ($totalSalary <= 8000) {
            $employerItsh = $totalSalary * 0.02;
        } else {
            $employerItsh = 80 + ($totalSalary - 8000) * 0.005;
        }

        static $rowNumber = 0;

        $rowNumber++;
        return [
            $rowNumber,
            $row->user->fullname,
            $row->user->position->name,
            $row->salary,
            $row->working_days,
            $row->actual_days,
            $totalSalary,
            $row->prize,
            $row->vacation,
            $totalSalary + $row->prize + $row->vacation,
            0,
            $employeeFund,
            $totalSalary * 0.5 / 100,
            $employeeItsh,
            $employerFund,
            $totalSalary * 0.5 / 100,
            $employerItsh,
            $employeeTotalTax = $employeeFund + $totalSalary * 0.5 / 100 + $employeeItsh + 0,
            $totalSalary - $employeeTotalTax,
            $row->advance,
            $totalSalary - $employeeTotalTax - $row->advance,
        ];
    }

    protected function getExportData()
    {
        return SalaryReport::where('company_id', $this->filters['company'])
            ->where('date', $this->filters['date-salary'])
            ->get();
    }
    public function styles(Worksheet $sheet)
    {
        // Border eklemek için aşağıdaki gibi bir şey yapabilirsiniz
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow())
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    }
}