<?php

namespace App\Imports;

use App\Model\EmployeeShift;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeeShiftImport implements ToModel, WithValidation, WithStartRow
{
    use Importable;

    private $month;
    private $validation;
    private $message;
    private $shift;
    private $mon;

    public function __construct($month, $validation, $message, $shift, $mon)
    {
        $this->month = $month;
        $this->validation = $validation;
        $this->message = $message;
        $this->shift = $shift;
        $this->mon = $mon;
    }

    public function rules(): array
    {
        return $this->validation;
    }

    public function customValidationMessages()
    {
        return $this->message;
    }

    public function model(array $row)
    {
        $month = "0000-00";

        if ($row[6]) {
            
            try {
                $month = Date::excelToDateTimeObject($row[6])->format('Y-m');
            } catch (\Throwable $th) {
                $month = date('Y-m', strtotime($row[6]));
            }
        }

  

        if ($month == $this->mon) {

            $dataSet = [
                'finger_print_id' => $row[1],
                'month' => $month,
            ];

            $extraDataset = [
                'created_by' => auth()->user()->user_id,
                'updated_by' => auth()->user()->user_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            foreach ($this->month as $key => $value) {
                $cell = ($key + 1);
                $dataSet['d_' . $cell] = $row[$key + 7] != null ? $this->shift[$row[$key + 7]] : null;
            }

            $hasShift = EmployeeShift::where('finger_print_id', $row[1])->where('month', $month)->first();

            if ($hasShift) {
                $hasShift->update(array_merge($dataSet, $extraDataset));
            } else {
                EmployeeShift::create(array_merge($dataSet, $extraDataset));
            }
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
