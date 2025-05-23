<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AddCourseController;
use App\Http\Controllers\Admin\AddInstructorController;
use App\Http\Controllers\Admin\AddStudentController;
use App\Http\Controllers\Admin\EditStudentController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Instructor\InstructorController;
use App\Http\Controllers\MachineLearningController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\Auth\PasswordResetController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('auth.login');
    Route::get('/course-sessions', [MachineLearningController::class, 'index'])->name('student.course_sessions.index');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->middleware('auth:sanctum')
        ->name('auth.logout');
    Route::post('/register', [AuthenticatedSessionController::class, 'register'])->name('auth.register');
    // Route::get('video', [VideoController::class, 'index'])->name('video.index');
    // Route::post('video/encode', [VideoController::class, 'store'])->name('video.encode');
    Route::post('/password/reset', [PasswordResetController::class, 'resetPassword']);
    Route::post('/submit-attendance', [MachineLearningController::class, 'submitAttendance']);
    Route::get('/upload-videos', [MachineLearningController::class, 'processVideos'])->name('student.video.upload');
    Route::post('/update-processed-status', [MachineLearningController::class, 'updateProcessedStatus'])->name('student.video.update');
});

// Admin Routes (Only Admins Can Access)
Route::middleware(['auth:sanctum', 'role:Admin'])->prefix('admin')->group(function () {
    Route::get('/instructors', [AdminController::class, 'getAllInstructors'])->name('admin.instructors');
    Route::get('/students', [AdminController::class, 'getAllStudents'])->name('admin.students');
    Route::get('/courses', [AdminController::class, 'getAllCourses'])->name('admin.courses');
    Route::get('/courses/{courseId}/students', [AdminController::class, 'getAllAdminStudentsCourse'])
        ->name('admin.course.students');
    Route::get('/{courseId}/calender', [AdminController::class, 'getCourseCalendar']);
    Route::get('/courses/{courseId}/{studentId}/attendance-report', [AdminController::class, 'generateAttendanceReport'])
        ->name('admin.course.attendance.report');

    Route::post('/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    Route::post('/Addcourse', [AddCourseController::class, 'store'])->name('admin.course.add');
    Route::post('/add-student', [AddStudentController::class, 'addStudent'])->name('admin.student.add');
    Route::get('/students/{studentId}/courses', [AdminController::class, 'getCoursesForStudent'])
        ->name('admin.student.courses');
    Route::put('/students/{studentId}', [AdminController::class, 'editStudent'])
        ->name('admin.student.edit');
    // instructor
    Route::put('/instructors/{instructorId}', [AdminController::class, 'editInstructor'])
        ->name('admin.instructor.edit');
    Route::put('/courses/{courseId}', [AdminController::class, 'editCourse'])
        ->name('admin.course.edit');
    Route::post('/add-instructor', [AddInstructorController::class, 'addInstructor'])
        ->name('admin.instructor.add');
    Route::get('/user', [AdminController::class, 'getAuthenticatedAdmin'])->name('Admin.user');
    Route::post('/enrollStudents', [AdminController::class, 'enrollStudents'])
        ->name('admin.enroll.student');
    Route::post('/enrollInstructors', [AdminController::class, 'enrollInstructors'])
        ->name('admin.enroll.instructor');
    Route::get('/students/{studentId}/courses/{courseId}', [AdminController::class, 'getStudentCalendar'])
        ->name('admin.student.course.calendar');
    Route::delete('/courses/{courseId}/students/{studentId}', [AdminController::class, 'deleteStudentCourse'])
        ->name('admin.student.course.delete');
    //delete student
    Route::delete('/students/{studentId}', [AdminController::class, 'deleteStudent'])
        ->name('admin.student.delete');
    //delete instructor
    Route::delete('/instructors/{instructorId}', [AdminController::class, 'deleteInstructor'])
        ->name('admin.instructor.delete');
    Route::get('/courses/{courseId}/attendance-report', [AdminController::class, 'downloadCourseAttendanceReport'])
        ->name('admin.course.attendance.report.download');
    Route::get('/reports/student-attendance/{studentId}', [AdminController::class, 'downloadStudentCoursesAttendanceReport']);
    //not renrolled students 
    Route::get('/courses/{courseId}/Not-Enrolled-students', [AdminController::class, 'getNotEnrolledStudents'])
        ->name('admin.students.not-renrolled');
});

// Instructor Routes (Only Instructors Can Access)

Route::middleware(['auth:sanctum', 'role:Instructor'])->prefix('instructor')->group(function () {

    Route::get('/courses', [InstructorController::class, 'getCoursesForLoggedInInstructor'])->name('instructor.courses');
    Route::get('/courses/{courseId}/students', [InstructorController::class, 'getAllStudentsCourse']);
    //send notification
    Route::post('/courses/send-notification', [InstructorController::class, 'sendNotification'])
        ->name('instructor.course.student.notification');
    Route::post('/profile', [InstructorController::class, 'updateInstructorProfile'])->name('Instructor.profile.update');
    Route::get('/schedule-report', [InstructorController::class, 'getScheduleReportForLoggedInInstructor'])
        ->name('Instructor.schedule.report');
    Route::get('/user', [InstructorController::class, 'getAuthenticatedStudent'])->name('Instructor.user');
    Route::get('/courses/{courseId}/calendar', [InstructorController::class, 'getCourseCalendar'])
        ->name('instructor.course.calendar');
    Route::get('/students/{studentId}/courses/{courseId}/calender', [InstructorController::class, 'getStudentCalendar'])
        ->name('instructor.student.course.calendar');
    Route::get('requests', [InstructorController::class, 'getRequestsForInstructor'])
        ->name('instructor.requests');
    Route::post('/requests/{requestId}/update-status', [InstructorController::class, 'updateRequestStatus'])
        ->name('instructor.request.update.status');
    Route::get('/download-schedule-report', [InstructorController::class, 'downloadScheduleReport'])
        ->name('instructor.schedule.report.download');
    Route::get('/notifications-read', [InstructorController::class, 'notificationsRead'])
        ->name('instructor.notifications.read');
    Route::put('/notifications/{notificationId}/read', [InstructorController::class, 'markNotificationAsRead'])
        ->name('instructor.notification.read');
    Route::get('/courses/{courseId}/attendance-report', [InstructorController::class, 'downloadCourseAttendanceReport'])
        ->name('instructor.course.attendance.report.download');
    Route::get('/students/{studentId}/courses/{courseId}/attendance-report', [InstructorController::class, 'downloadStudentCourseAttendanceReport'])
        ->name('instructor.student.course.calendar');
});

// Student Routes (Only Students Can Access)
Route::middleware(['auth:sanctum', 'role:Student'])->prefix('student')->group(function () {
    Route::get('/courses', [StudentController::class, 'getCoursesForLoggedInStudent'])->name('student.courses');
    Route::get('/notifications', [StudentController::class, 'getNotificationsForLoggedInStudent'])
        ->name('student.notifications');
    Route::get('/courses/{courseId}/{studentId}/calendar', [StudentController::class, 'getStudentCalendar'])
        ->name('student.attendance.sessions');
    Route::put('/notifications/{notificationId}/read', [StudentController::class, 'markNotificationAsRead'])
        ->name('student.notification.read');
    Route::get('/schedule-report', [StudentController::class, 'getScheduleReportForLoggedInStudent'])
        ->name('student.schedule.report');
    Route::post('/profile', [StudentController::class, 'updateStudentProfile'])->name('student.profile.update');
    Route::get('/user', [StudentController::class, 'getAuthenticatedStudent'])->name('student.user');
    Route::post('/attendance-requests/{attendanceId}', [StudentController::class, 'requestCorrection'])->name('student.attendance.request');
    Route::post('/{studentId}/upload-video', [MachineLearningController::class, 'uploadStudentVideo'])
        ->name('student.upload.video');

    //delete image | video 
    Route::delete('/{studentId}/delete-image', [StudentController::class, 'deleteStudentImage'])
        ->name('student.image.delete');
    Route::delete('/{studentId}/delete-video', [StudentController::class, 'deleteStudentVideo'])
        ->name('student.video.delete');

    Route::get('/download-schedule-report', [StudentController::class, 'downloadScheduleReport'])
        ->name('student.schedule.report.download');
});
