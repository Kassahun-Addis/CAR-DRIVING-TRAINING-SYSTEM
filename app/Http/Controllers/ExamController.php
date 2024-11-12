<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainee;
use Illuminate\Support\Facades\Http;
use App\Models\Exam;
use Illuminate\Support\Facades\Log;

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
    
    public function redirectToExam()
{
    // Assuming the PHP exam system is accessible at this URL
    $examUrl = url('/exam/index.php'); // Adjust the path if necessary

    // Redirect the trainee to the PHP exam system
    return redirect()->away($examUrl);
}

 public function handleExamCallback(Request $request)
{

    Log::info('Callback hit');

    Log::info('Callback received:', ['data' => $request->all()]);

    // Check both JSON and standard request input
    $traineeId = $request->input('trainee_id') ?? $request->json('trainee_id');
    $examResult = $request->input('exam_result') ?? $request->json('exam_result');

    if (!$traineeId || !$examResult) {
        Log::error('Missing trainee_id or exam_result in callback data', ['data' => $request->all()]);
        return response()->json(['error' => 'Invalid data'], 400);
    }

    // Attempt to find the trainee
    $trainee = Trainee::find($traineeId);
    if (!$trainee) {
        Log::error('Trainee not found', ['traineeId' => $traineeId]);
        return response()->json(['error' => 'Trainee not found'], 404);
    }

    try {
        // Save the result
        Exam::create([
            'trainee_id' => $trainee->id,
            'score' => $examResult,
        ]);
        
        Log::info('Exam result stored successfully', [
            'traineeId' => $traineeId,
            'score' => $examResult
        ]);

        return response()->json(['message' => 'Exam result stored successfully!'], 200);
    } catch (\Exception $e) {
        Log::error('Error saving exam result', [
            'error' => $e->getMessage(),
            'traineeId' => $traineeId,
            'score' => $examResult
        ]);
        return response()->json(['error' => 'Failed to save exam result'], 500);
    }
}

    public function index(Request $request)
{
    $search = $request->input('search'); // Get the search term
    $perPage = $request->input('perPage', 10); // Get the number of items per page, default to 10
    $sortBy = $request->input('sortBy', 'trainee_name'); // Default sort by trainee name
    $sortOrder = $request->input('sortOrder', 'asc'); // Default sort order

    $exams = Exam::select('exams.*')
        ->join('trainees', 'exams.trainee_id', '=', 'trainees.id')
        ->when($search, function ($query, $search) {
            return $query->where('trainees.full_name', 'like', "%{$search}%")
                         ->orWhere('exams.score', 'like', "%{$search}%")
                         ->orWhere('exams.created_at', 'like', "%{$search}%")
                         ->orWhere(function ($query) use ($search) {
                             if (strtolower($search) === 'pass') {
                                 $query->where('exams.score', '>=', 74);
                             } elseif (strtolower($search) === 'fail') {
                                 $query->where('exams.score', '<', 74);
                             }
                         });
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

    public function saveExamScore(Request $request)
    {
        Log::info('Save exam score hit');
        Log::info('Save exam score received:', ['data' => $request->all()]);
        $traineeId = $request->input('trainee_id');
        $score = $request->input('score');
        $companyId = $request->input('company_id');
 
        if (!$traineeId || !$score) {
            return response()->json(['error' => 'Invalid data'], 400);
        }
 
        // Assuming you have a Trainee model and Exam model
        $trainee = Trainee::find($traineeId);
        if (!$trainee) {
            return response()->json(['error' => 'Trainee not found'], 404);
        }
 
        try {
            Exam::create([
                'trainee_id' => $traineeId,
                'score' => $score,
                'company_id' => $companyId,
            ]);
 
            return response()->json(['message' => 'Exam score saved successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save exam score'], 500);
        }
    }

    public function showQuizQuestion()
    {
        // Fetch any necessary data from the database
        // $questions = Question::all(); // Example: Fetch questions from the database

        return view('exams.quizquestion'); // Pass data to the view if needed
    }

}
