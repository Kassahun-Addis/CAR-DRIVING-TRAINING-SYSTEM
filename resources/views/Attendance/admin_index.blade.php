@extends('layouts.app')

@section('title', 'Admin - Attendance List')

@section('content')
<div class="container mt-5">
    <h2 style="text-align: center; padding:10px;">Admin Attendance List</h2>

    @if(session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="row mb-3" style="display: flex; justify-content: space-between; align-items: center;">
        <div class="col-12 col-md-6 d-flex justify-content-between mb-2 mb-md-0">
            <form action="{{ route('attendance.admin_index') }}" method="GET" class="form-inline" style="flex: 1;">
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
                <input type="hidden" name="trainee_id" value="{{ request('trainee_id') }}">
                <input type="hidden" name="trainee_name" value="{{ request('trainee_name') }}">
            </form>
        </div>

        <div class="col-12 col-md-6 d-flex justify-content-end align-items-center">
            <form action="{{ route('attendance.admin_index') }}" method="GET" class="form-inline" style="flex: 1;">
                <div class="form-group w-100" style="display: flex; align-items: center;">
                    <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}" style="flex-grow: 1; margin-right: 5px; min-width: 0;">
                    <button type="submit" class="btn btn-primary mr-1" style="height: 41px;">Search</button>
                    <div class="ml-0">
                        <button class="btn btn-info ml-1" onclick="printAttendanceList()" style="height: 41px;">Print</button>
                    </div>
                </div>
                <input type="hidden" name="trainee_id" value="{{ request('trainee_id') }}">
                <input type="hidden" name="trainee_name" value="{{ request('trainee_name') }}">
            </form>
        </div>

        <div class="d-flex justify-content-end align-items-center" style="margin: 15px 0 0 10px;">
        <div class="form-group d-flex align-items-center">
            <label for="total_time" style="min-width: 100px;">Total Time</label>
            <input type="text" class="form-control form-control-sm" id="total_time" name="total_time" style="width: 70px;" 
                value="{{ $totalTime }}" readonly>
        </div>
        <div class="form-group d-flex align-items-center" style="margin-left: 25px;">
            <label for="remaining_time" class="mr-2" style="min-width: 100px;">Remaining Time</label>
            <input type="text" class="form-control form-control-sm" id="remaining_time" name="remaining_time" style="width: 70px;" readonly>
        </div>
    </div>
    </div>

    <!-- Responsive table wrapper -->
    <div class="table-responsive">
        @if($attendances->isEmpty())
            <div class="alert alert-warning">
                No attendance records found.
            </div>
        @else
            <table class="table table-bordered" id="attendanceTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>Finish Time</th>
                        <th>Difference</th>
                        <th>Trainee Name</th>
                        <th>Trainer Name</th>
                        <th>Status</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $key => $attendance)
                        <tr data-start-date="{{ $attendance->start_date }}"
                            data-end-date="{{ $attendance->end_date }}"
                            data-category-id="{{ $attendance->category_id }}"
                            data-plate-no="{{ $attendance->plate_no }}"
                            data-trainee-phone="{{ $attendance->trainee_phone }}"
                            data-total-time="{{ $attendance->total_time ?? 0 }}"
                            data-difference="{{ $attendance->difference ?? 0 }}">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $attendance->date }}</td>
                            <td>{{ $attendance->start_time }}</td>
                            <td>{{ $attendance->finish_time }}</td>
                            <td>{{ $attendance->difference }}</td>
                            <td>{{ $attendance->trainee_name }}</td>
                            <td>{{ $attendance->trainer_name }}</td>
                            <td>{{ $attendance->status }}</td>
                            <td>{{ $attendance->comment }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mt-3">
        Showing {{ $attendances->firstItem() ?? 0 }} to {{ $attendances->lastItem() ?? 0 }} of {{ $attendances->total() }} entries
    </div>

    <div class="mt-3 d-flex justify-content-between align-items-center">
        <div>
            @if ($attendances->onFirstPage())
                <span class="btn btn-light disabled">Previous</span>
            @else
                <a href="{{ $attendances->previousPageUrl() }}" class="btn btn-light">Previous</a>
            @endif

            @foreach (range(1, $attendances->lastPage()) as $i)
                @if ($i == $attendances->currentPage())
                    <span class="btn btn-primary disabled">{{ $i }}</span>
                @else
                    <a href="{{ $attendances->url($i) }}" class="btn btn-light">{{ $i }}</a>
                @endif
            @endforeach

            @if ($attendances->hasMorePages())
                <a href="{{ $attendances->nextPageUrl() }}" class="btn btn-light">Next</a>
            @else
                <span class="btn btn-light disabled">Next</span>
            @endif
        </div>

        <div>
            {{ $attendances->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var successAlert = document.getElementById('success-alert');

    if (successAlert) {
        setTimeout(function() {
            successAlert.style.opacity = '0';
            setTimeout(function() {
                successAlert.style.display = 'none';
            }, 500);
        }, 3000);
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const attendanceRows = document.querySelectorAll('#attendanceTable tbody tr');
    let initialTotalTime = 0;
    let totalDifference = 0;

    // Calculate the initial total time and total difference
    attendanceRows.forEach(row => {
        const rowTotalTime = parseFloat(row.getAttribute('data-total-time')) || 0;
        const rowDifference = parseFloat(row.getAttribute('data-difference')) || 0;

        // Assuming total time is the same for all rows, set it once
        if (initialTotalTime === 0) {
            initialTotalTime = rowTotalTime;
        }

        totalDifference += rowDifference;
    });

    // Calculate the remaining time based on the initial total time
    const totalRemainingTime = initialTotalTime - totalDifference;
    console.log(`Initial Total Time: ${initialTotalTime}, Total Difference: ${totalDifference}, Total Remaining Time: ${totalRemainingTime.toFixed(2)}`);

    // Update the remaining time input field with the calculated total remaining time
    const remainingTimeInput = document.getElementById('remaining_time');
    remainingTimeInput.value = totalRemainingTime.toFixed(2);

    // Change background color and text color based on remaining time
    if (totalRemainingTime < 5) {
        remainingTimeInput.style.backgroundColor = 'red';
    } else {
        remainingTimeInput.style.backgroundColor = 'green';
    }

    // Set text color to white
    remainingTimeInput.style.color = 'white';
});
</script>

<!-- CSS for Print -->
<script>
function printAttendanceList() {
    const attendanceRows = document.querySelectorAll('#attendanceTable tbody tr');
    
    const firstRow = attendanceRows[0];
    const trainingDetails = {
        start_date: firstRow.getAttribute('data-start-date'),
        end_date: firstRow.getAttribute('data-end-date'),
        category_id: firstRow.getAttribute('data-category-id'),
        plate_no: firstRow.getAttribute('data-plate-no'),
        trainer_name: firstRow.cells[6].innerText,
        trainee_name: firstRow.cells[5].innerText,
        trainee_phone: firstRow.getAttribute('data-trainee-phone'),
    };

    var printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Print Attendance List</title>');
    printWindow.document.write('<style>body { font-family: Arial, sans-serif; } table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #000; padding: 8px; text-align: left; } th { background-color: #f2f2f2; } </style>');
    printWindow.document.write('</head><body>');
    
    printWindow.document.write(`<h4>ከሰለሞን የአሽከርካሪዎች ማሠልጠኛ ተቋም ለእጩ አሽከርከሪዎች የሚሠጥ የሥልጠና መከታተያ ፎርም</h4>`);
    printWindow.document.write(`
        <p>
            - ሥልጠና የተጀመረበት ቀን: <strong><u>${trainingDetails.start_date}</u></strong> <br>
            - ሥልጠና የሚጨርስበት ዙር: <strong><u>${trainingDetails.end_date}</u></strong> <br>
            - ሥልጠና የተሰጠበት የአገልግሎት ዓይነት: <strong><u>${trainingDetails.category_id}</u></strong> <br>
            - ሥልጠና የተሰጠበት የተሽከርካሪ ሠሌዳ ቁጥር: <strong><u>${trainingDetails.plate_no}</u></strong> <br>
            - የተግባር አሠልጣኝ ሥም: <strong><u>${trainingDetails.trainer_name}</u></strong> <br>
            - የሠልጣኝ ሥም: <strong><u>${trainingDetails.trainee_name}</u></strong> <br>
            - የተማሪ ስልክ : <strong><u>${trainingDetails.trainee_phone}</u></strong> 
        </p>
    `);
    // Add the attendance table without the "Actions" column
    printWindow.document.write('<table><thead><tr>');
    printWindow.document.write('<th>No</th><th>Date</th><th>Start Time</th><th>Finish Time</th><th>Difference</th><th>Trainee Name</th><th>Trainer Name</th><th>Status</th><th>Comments</th>');
    printWindow.document.write('</tr></thead><tbody>');

    attendanceRows.forEach((row) => {
        printWindow.document.write('<tr>');
        Array.from(row.children).forEach((cell) => {
            printWindow.document.write(`<td>${cell.innerHTML}</td>`);
        });
        printWindow.document.write('</tr>');
    });

    printWindow.document.write('</tbody></table>');

    printWindow.document.write(`
        <p>
        ሥልጠና ጨርሶ ብቁ መሆኑን ያረጋገጠው የተቋሙ ሥራ አሥኪያጅ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ሥልጠና ጨርሶ ለፈተና ብቁ መሆኑን ያረጋገጠው መምህር <br>
        ሥም  -------------------------------------------  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ሥም-------------------------------------------- <br>         
        ፊርማ  -------------------------------------------- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ፊርማ ------------------------------------------<br><br><br>
            
        <strong><u> ለአሰልጣኝ የአነዳድ ብቃት ማረጋገጫ ቅፅ </u></strong> <br><br>
            እኔ አሰልጣኝ አቶ/ ወ/ሮ <strong><u>${trainingDetails.trainer_name} </u></strong> በአዋጅ ቁጥር 2000/200 በተሰጠኝ እጩ አሽከርካሪዎችን የማሰልጠን ፍቃድ መሰረት በተቋሙ ፋይል / ባጅ ቁጥር____________ ተመዝግቦ የሚታወቀውን አሽከርካሪ አቶ/ወሮ/ወ/ት <strong> <u>${trainingDetails.trainee_name}</u></strong> 
            ተገቢውን የመስክ ስልጠና በሰሌዳ ቁጥር <strong> <u>${trainingDetails.plate_no} </u></strong> በሆነው ተሸከርካሪ ስልጣና በመስጠት <strong><u> ${trainingDetails.category_id} </u></strong> ካታጎሪ አሽከርካሪ ብቃት ማረጋገጫ ፍቃድ ፈተና ብቁ መሆናቸውን በፊርማዬ አረጋግጣለው፡፡ <br><br>
            በመሆኑም እጩ አሽከርካሪው በፈተና ወቅት የተሸከርካሪ ጉዳት ቢያደርስ በሃላፊነት የምጠየቅ መሆኔንም አረጋግጣለሁ፡፡ <br><br>
            ስምና ፊርማ____________________________		<br><br><br>			

        <strong><u> ለሰልጣኝ የአነዳድ ብቃት ማረጋገጫ ቅፅ </u></strong> <br><br>
          እኔ ሰልጣኝ አቶ/ ወ/ሮ/ት <strong><u>${trainingDetails.trainee_name}</u></strong> የተባልኩ በሰለሞን የአሽ/ ማ/ ተቋም ውስጥ ሙሉ የስልጠና ሰዓቴን በተገቢው መንገድ በማጠናቀቅ የሰለጠንኩና ለመፈተን ብቁ በመሆኔ አንዲሁም 
          በፈተና ሰዓት በሚፈጠር ማንኛውም የአሽከርካሪ ጉዳት እንዲሁም ሌሎች ተዛማጅ ችግሮች ቢደርስ ሙሉ ኃላፊነት የምወስድ መሆኔን በፊርማዬ አረጋግጣለሁ፡፡<br><br>
          ስምና ፊርማ_______________________<br><br><br>			
        </p>
    `);

    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
</script>

@endsection