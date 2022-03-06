<?php

namespace App\Http\Resources;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Employee $employee */
        $employee = $this->resource;
        /** @var User $user */
        $user = $employee->user;
        return [
            'id' => $employee->getAttribute('id'),
            'first_name' => $user->getAttribute('first_name'),
            'middle_name' => $user->getAttribute('middle_name'),
            'last_name' => $user->getAttribute('last_name'),
            'full_name' => $user->getAttribute('full_name'),
            'email' => $user->getAttribute('email'),
            'status' => new UserStatusResource($user->status),
            'department' => new EmployeeDepartmentResource($employee->department),
            'id_number' => $employee->getAttribute('id_number'),
            'position' => $employee->getAttribute('position'),
        ];
    }
}
