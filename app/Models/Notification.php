<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    protected $casts = [
        'data' => 'array',
    ];

    protected $fillable = [
        'instructor_id',
        'student_id',
        'course_id',
        'message',
        'type',
        'read_status', 
        'data',
    ];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
