@extends('layouts.app')

@section('title', 'Exam - List')

@section('content')
<div class="container mt-5">
    <h2 style="text-align: center; padding:10px;">Exam List</h2>

    @if(session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="row mb-3" style="display: flex; justify-content: space-between; align-items: center;">
        <div class="col-12 col-md-6 d-flex justify-content-between mb-2 mb-md-0">
            <form action="{{ route('exams.index') }}" method="GET" class="form-inline" style="flex: 1;">
                <div class="form-group">
                    <span>Show
                        <select name="perPage" class="form-control" onchange="this.form.submit()" style="display: inline-block; width: auto;">
                            <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        entries
                    </span>
                </div>
            </form>
        </div>

        <div class="col-12 col-md-6 d-flex justify-content-end align-items-center">
            <form action="{{ route('exams.index') }}" method="GET" class="form-inline" style="flex: 1;">
                <div class="form-group w-100" style="display: flex; align-items: center;">
                    <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}" style="flex-grow: 1; margin-right: 5px; min-width: 0;">
                    <button type="submit" class="btn btn-primary mr-1">Search</button>
                    <button type="button" class="btn btn-info" onclick="printExamList()">Print</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Responsive table wrapper -->
    <div class="table-responsive">
        <table class="table table-bordered" id="examTable">
            <thead>
                <tr>
                    <th>
                        <a href="{{ route('exams.index', ['sortBy' => 'trainee_name', 'sortOrder' => request('sortOrder') === 'asc' ? 'desc' : 'asc']) }}">
                            Trainee Name
                        </a>
                    </th>
                    <th>
                        <a href="{{ route('exams.index', ['sortBy' => 'score', 'sortOrder' => request('sortOrder') === 'asc' ? 'desc' : 'asc']) }}">
                            Score
                        </a>
                    </th>
                    <th>Pass/Fail</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @if($exams->isNotEmpty())
                    @foreach($exams as $exam)
                        <tr>
                            <td>{{ $exam->trainee->full_name }}</td>
                            <td>{{ $exam->score }}</td>
                            <td>{{ $exam->score >= 74 ? 'Pass' : 'Fail' }}</td>
                            <td>{{ $exam->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                @else
                    <!-- Sample data for demonstration purposes -->
                    <tr>
                        <td>Haileyesus</td>
                        <td>85</td>
                        <td>Pass</td>
                        <td>2024-10-01</td>
                    </tr>
                    <tr>
                        <td>Kassahun</td>
                        <td>68</td>
                        <td>Fail</td>
                        <td>2024-10-02</td>
                    </tr>
                    <tr>
                        <td>Bethelhem</td>
                        <td>90</td>
                        <td>Pass</td>
                        <td>2024-10-03</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        Showing {{ $exams->firstItem() }} to {{ $exams->lastItem() }} of {{ $exams->total() }} entries
    </div>
    
    <div class="mt-3 d-flex justify-content-between align-items-center">
        <div>
            @if ($exams->onFirstPage())
                <span class="btn btn-light disabled">Previous</span>
            @else
                <a href="{{ $exams->previousPageUrl() }}" class="btn btn-light">Previous</a>
            @endif

            @foreach (range(1, $exams->lastPage()) as $i)
                @if ($i == $exams->currentPage())
                    <span class="btn btn-primary disabled">{{ $i }}</span>
                @else
                    <a href="{{ $exams->url($i) }}" class="btn btn-light">{{ $i }}</a>
                @endif
            @endforeach

            @if ($exams->hasMorePages())
                <a href="{{ $exams->nextPageUrl() }}" class="btn btn-light">Next</a>
            @else
                <span class="btn btn-light disabled">Next</span>
            @endif
        </div>

        <div>
            {{ $exams->links() }}
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-between align-items-center">
        {{ $exams->links() }}
    </div>
</div>

<script>
function printExamList() {
    // Create a header for the print content
    const title = `<h2 style="text-align: center;">Trainees Exam Result List</h2>`;
    
    // Get the exam table HTML
    const printContent = title + document.getElementById('examTable').outerHTML;
    const originalContent = document.body.innerHTML;
    
    // Replace the body content with the print content
    document.body.innerHTML = printContent;
    
    // Trigger the print
    window.print();
    
    // Restore the original content
    document.body.innerHTML = originalContent;
}
</script>
@endsection