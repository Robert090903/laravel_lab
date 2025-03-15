<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->bigIncrements('grade_id');
            $table->string('name', 45);
            $table->string('description', 45)->nullable(); // Renamed `desc`
            $table->timestamps();
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('course_id');
            $table->string('name', 45);
            $table->string('description', 45);
            $table->unsignedBigInteger('grade_id'); // Explicitly unsigned
            $table->foreign('grade_id')->references('grade_id')->on('grades')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('student_id');
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('fname', 30);
            $table->string('lname', 30);
            $table->date('dob');
            $table->string('phone', 20)->nullable();
            $table->string('mobile', 20);
            $table->date('date_of_join');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->date('last_login_date')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->timestamps();
        });

        Schema::create('parents', function (Blueprint $table) {
            $table->bigIncrements('parent_id');
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('fname', 30);
            $table->string('lname', 30);
            $table->date('dob');
            $table->string('phone', 20)->nullable();
            $table->string('mobile', 20);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->date('last_login_date')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->timestamps();
        });

        Schema::create('teachers', function (Blueprint $table) {
            $table->bigIncrements('teacher_id');
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('fname', 30);
            $table->string('lname', 30);
            $table->date('dob');
            $table->string('phone', 20)->nullable();
            $table->string('mobile', 20);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->date('last_login_date')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->timestamps();
        });

        Schema::create('classrooms', function (Blueprint $table) {
            $table->bigIncrements('classroom_id');
            $table->year('year');
            $table->string('section', 2);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('remarks', 45)->nullable();
            $table->unsignedBigInteger('grade_id');
            $table->unsignedBigInteger('teacher_id');
            $table->foreign('grade_id')->references('grade_id')->on('grades')->onDelete('cascade');
            $table->foreign('teacher_id')->references('teacher_id')->on('teachers')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('classroom_student', function (Blueprint $table) {
            $table->unsignedBigInteger('classroom_id');
            $table->unsignedBigInteger('student_id');
            $table->foreign('classroom_id')->references('classroom_id')->on('classrooms')->onDelete('cascade');
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->primary(['classroom_id', 'student_id']);
        });

        Schema::create('attendance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->unsignedBigInteger('student_id');
            $table->boolean('status');
            $table->text('remark')->nullable();
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('exam_types', function (Blueprint $table) {
            $table->bigIncrements('exam_type_id');
            $table->string('name', 45);
            $table->string('description', 45)->nullable();
            $table->timestamps();
        });

        Schema::create('exams', function (Blueprint $table) {
            $table->bigIncrements('exam_id');
            $table->unsignedBigInteger('exam_type_id');
            $table->string('name', 45);
            $table->date('start_date');
            $table->foreign('exam_type_id')->references('exam_type_id')->on('exam_types')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('exam_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('exam_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_id');
            $table->decimal('marks', 5, 2);
            $table->foreign('exam_id')->references('exam_id')->on('exams')->onDelete('cascade');
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_results');
        Schema::dropIfExists('exams');
        Schema::dropIfExists('exam_types');
        Schema::dropIfExists('attendance');
        Schema::dropIfExists('classroom_student');
        Schema::dropIfExists('classrooms');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('parents');
        Schema::dropIfExists('students');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('grades');
    }
};
