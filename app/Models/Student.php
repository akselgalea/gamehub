<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends User
{
    use HasParent;

    /**
     * Get the grade that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    
    public function grade(): BelongsTo {
        return $this->belongsTo(Grade::class);
    }

    public function school() {
        $grade = $this->grade;

        if($grade)
            return $grade->school();
        
        return null;
    }

    public function schoolInfo($id) {
        try {
            $student = Student::findOrFail($id);
            return response()->json(['school' => $student->school, 'grade' => $student->grade]);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function gradeUpdate($id, $req) {
        $validated = $req->validated();

        try {
            Student::findOrFail($id)->grade()->associate($validated['gradeId'])->save();
            return ['status' => 200, 'message' => 'Curso actualizado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }
}
