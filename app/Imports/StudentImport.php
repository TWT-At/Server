<?php

namespace App\Imports;

use App\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Student([
            "student_id" => $row["student_id"],
            "name" => $row["name"],
            "password" => $row["student_id"],
            "group_name" => $row["group_name"],
            "group_role" => $row["group_role"],
            "campus" => $row["campus"],
            "email" => $row["email"],

        ]);
    }
}
