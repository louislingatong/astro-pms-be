<?php

namespace App\Imports;

use App\Models\Interval;
use App\Models\IntervalUnit;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class IntervalImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    /**
     * @param array $row
     * @return Interval
     */
    public function model(array $row): Interval
    {
        /** @var IntervalUnit $unit */
        $unit = IntervalUnit::where('name', $row['unit'])->first();

        return new Interval([
            'value' => $row['value'],
            'interval_unit_id' => $unit->getAttribute('id'),
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.value' => ['required'],
            '*.unit' => ['required', 'exists:interval_units,name']
        ];
    }
}
