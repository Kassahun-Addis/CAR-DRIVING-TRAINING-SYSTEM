<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainee;
use Illuminate\Support\Facades\Http;
use App\Models\Exam;


class ExamController extends Controller
{

    public function takeExam($traineeId)
    {
        // Generate a random score between 50 and 100
        $score = rand(50, 100);

        // Store the exam result in the local database
        $trainee = Trainee::find($traineeId);
        if ($trainee) {
            $trainee->exams()->create([
                'score' => $score,
            ]);

            return redirect()->route('exams.results')->with('success', 'Exam taken successfully!');
        }

        return redirect()->back()->with('error', 'Trainee not found.');
    }
    
    // public function redirectToExam()
    // {
    //     // URL of the external exam site
    //     $examUrl = 'https://ethioautosafety.com/educations/qsm_quiz/%e1%8a%a0%e1%8b%8d%e1%89%b6-%e1%88%b4%e1%8d%8d%e1%89%b2-%e1%8b%a8%e1%88%b2%e1%88%b5%e1%89%b0%e1%88%9d-%e1%8d%88%e1%89%b0%e1%8a%93/';

    //     // Redirect the student to the external exam site
    //     return redirect()->away($examUrl);
    // }

    // public function fetchAndStoreExamResults($traineeId)
    //     {
    //         // Fetch exam result from external API
    //         $response = Http::get("https://external-exam-site.com/api/exam-results?trainee_id={$traineeId}&api_key=your-api-key");
 
    //         if ($response->successful()) {
    //             $examResult = $response->json('exam_result');
 
    //             // Store the exam result in the local database
    //             $trainee = Trainee::find($traineeId);
    //             if ($trainee) {
    //                 $trainee->exams()->create([
    //                     'score' => $examResult,
    //                 ]);
    //             }
    //         } else {
    //             // Handle error
    //             \Log::error('Failed to fetch exam result', ['traineeId' => $traineeId, 'response' => $response->body()]);
    //     }
    // }

    public function redirectToExam()
{
    // Simulate taking an exam locally
    $traineeId = auth()->id(); // Assuming the trainee is authenticated
    return $this->takeExam($traineeId);
}

    public function fetchAndStoreExamResults($traineeId)
    {
        // Mock response for local testing
        $mockExamResult = 85; // Example score to simulate

        // Store the mock exam result in the local database
        $trainee = Trainee::find($traineeId);
        if ($trainee) {
            $trainee->exams()->create([
                'score' => $mockExamResult,
            ]);
        }
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sortBy', 'trainee_name'); // Default sort by trainee name
        $sortOrder = $request->input('sortOrder', 'asc'); // Default sort order

        $exams = Exam::select('exams.*')
            ->join('trainees', 'exams.trainee_id', '=', 'trainees.id')
            ->when($search, function ($query, $search) {
                return $query->where('trainees.full_name', 'like', "%{$search}%");
            })
            ->when($sortBy === 'score', function ($query) use ($sortOrder) {
                return $query->orderBy('score', $sortOrder);
            }, function ($query) use ($sortOrder) {
                return $query->orderBy('trainees.full_name', $sortOrder);
            })
            ->paginate($perPage);

        return view('exams.index', compact('exams', 'search', 'perPage', 'sortBy', 'sortOrder'));
    }

    public function showResults()
    {
        // Assuming the student is authenticated and you have a relationship set up
        $traineeId = auth()->id(); // Get the authenticated trainee's ID
        // $exams = Exam::where('trainee_id', $traineeId)->get(); // Fetch exams for the trainee
        $exams = Exam::with('trainee')->where('trainee_id', $traineeId)->get(); // Eager load trainee relationship

        return view('exams.results', compact('exams')); // Pass the exams to the view
    }

}
