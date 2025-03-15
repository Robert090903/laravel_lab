<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

// Allowed tables (whitelist)
$allowedTables = ['student', 'teacher', 'parent', 'classroom', 'course', 'grade', 'attendance', 'exam', 'exam_result', 'exam_type', 'classroom_student'];

// Get all records from a table
Route::get('{table}', function ($table) use ($allowedTables) {
    if (!in_array($table, $allowedTables)) {
        return response()->json(['error' => 'Unauthorized table access'], 403);
    }
    return response()->json(DB::table($table)->get());
});

// Get a single record by ID
Route::get('{table}/{id}', function ($table, $id) use ($allowedTables) {
    if (!in_array($table, $allowedTables)) {
        return response()->json(['error' => 'Unauthorized table access'], 403);
    }
    return response()->json(DB::table($table)->where('id', $id)->first());
});

// Create a new record
Route::post('{table}', function (Request $request, $table) use ($allowedTables) {
    if (!in_array($table, $allowedTables)) {
        return response()->json(['error' => 'Unauthorized table access'], 403);
    }

    // Validate request data (Ensure required fields exist)
    $validator = Validator::make($request->all(), [
        'id' => 'nullable|integer', // ID is optional but must be an integer if provided
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $id = DB::table($table)->insertGetId($request->all());
    return response()->json(['message' => 'Created successfully!', 'id' => $id], 201);
});

// Update a record
Route::put('{table}/{id}', function (Request $request, $table, $id) use ($allowedTables) {
    if (!in_array($table, $allowedTables)) {
        return response()->json(['error' => 'Unauthorized table access'], 403);
    }

    // Validate request data
    $validator = Validator::make($request->all(), [
        'id' => 'nullable|integer', // Ensure ID is an integer if provided
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    DB::table($table)->where('id', $id)->update($request->all());
    return response()->json(['message' => 'Updated successfully!']);
});

// Delete a record
Route::delete('{table}/{id}', function ($table, $id) use ($allowedTables) {
    if (!in_array($table, $allowedTables)) {
        return response()->json(['error' => 'Unauthorized table access'], 403);
    }

    DB::table($table)->where('id', $id)->delete();
    return response()->json(['message' => 'Deleted successfully!']);
});
