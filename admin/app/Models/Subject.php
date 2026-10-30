<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'credits', 'description'];

    public function teacherSubjectAssignments()
    {
        return $this->hasMany(TeacherSubjectAssignment::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
