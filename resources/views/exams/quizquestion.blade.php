@extends('layouts.app') <!-- Assuming you have a layout file -->

@section('content')
<div class="col-sm-9 col-md-9" id="gob">
    @if(session('name'))
        {{ session('name') }}
    @else
        not set
    @endif

    <div id="question-container">
        @if($questions->count() > 0)
            @foreach($questions as $question)
                <!-- Display question and choices -->
                <p>{{ $question->Question }}</p>
                <!-- Add your question display logic here -->
            @endforeach
        @else
            <p>No questions found.</p>
        @endif
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function saveScore() {
            let total = correctANS.length + wrongANS.length;
            let correct = correctANS.length;
            let wrong = wrongANS.length;
            let score = correct;
            let user = "{{ session('name') }}";

            fetch('{{ url('/api/save-exam-score') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    total: total,
                    correct: correct,
                    wrong: wrong,
                    user: user,
                    title: title
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                console.log('Score saved successfully:', data);
            })
            .catch(error => {
                console.error('Error saving score:', error);
            });
        }
    });
</script>
@endsection