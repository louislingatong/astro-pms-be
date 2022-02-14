<?php

namespace App\Imports;

use App\Models\Machinery;
use App\Models\SubCategory;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;

class SubCategoryImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    /**
     * @param array $row
     * @return SubCategory
     */
    public function model(array $row): SubCategory
    {
        /** @var Machinery $machinery */
        $machinery = Machinery::where('name', $row['machinery'])->first();

        return new SubCategory([
            'name' => $row['name'],
            'machinery_id' => $machinery->getAttribute('id'),
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.machinery' => ['required', 'exists:machineries,name']
        ];
    }
}
