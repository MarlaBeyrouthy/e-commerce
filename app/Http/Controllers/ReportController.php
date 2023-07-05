<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\report;

class ReportController extends Controller
{



    public function create_report(Request $request)
    {
        $valid_Titles = ['user', 'product', 'order','technical'];

        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|in:' . implode(',', $valid_Titles),
            'description' => 'nullable',
        ]);
        $report = new report;
        $report->user_id = auth()->id();
        $report->title = $validatedData['title'];
        $report->description = $validatedData['description'];
        $report->save();
        return response()->json(['message' => 'report created successfully', 'report' => $report], 201);
    }

    public function my_reports()
    {
        $reports = report::where('user_id', auth()->id())->get();
        return response()->json(['reports' => $reports]);
    }

    //
}
