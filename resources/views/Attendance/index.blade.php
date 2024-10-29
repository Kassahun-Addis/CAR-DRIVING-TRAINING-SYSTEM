@extends('student.app')

@section('title', 'Attendance - List')

@section('content')
<div class="container mt-5">
    
<h2 style="text-align: center; padding:10px;">Attendance List</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row mb-3" style="display: flex; justify-content: space-between; align-items: center;">
        <div class="col-12 col-md-6 d-flex justify-content-between mb-2 mb-md-0">
            <form action="{{ route('attendance.index') }}" method="GET" class="form-inline" style="flex: 1;">
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
            @auth('trainee')
                <a href="{{ route('attendance.create') }}" class="btn btn-primary ml-2">Add New</a>
            @endauth
        </div>

        <div class="col-12 col-md-6 d-flex justify-content-end align-items-center">
            <form action="{{ route('attendance.index') }}" method="GET" class="form-inline" style="flex: 1;">
                <div class="form-group w-100" style="display: flex; align-items: center;">
                    <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}" style="flex-grow: 1; margin-right: 5px; min-width: 0;">
                    <button type="submit" class="btn btn-primary mr-1">Search</button>
                    <div class="d-none d-md-block ml-1">
                        <button class="btn btn-info btn-sm ml-1" onclick="printAttendanceList()">Print</button>
                    </div>
                </div>
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
                    <th class="no-print">Actions</th>
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
                        <td class="text-nowrap no-print">
                            <a href="{{ route('attendance.edit', $attendance->attendance_id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('attendance.destroy', $attendance->attendance_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this attendance?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        Showing {{ $attendances->firstItem() }} to {{ $attendances->lastItem() }} of {{ $attendances->total() }} entries
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
        // total_time: firstRow.getAttribute('data-total-time')
    };

    var printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Print Attendance List</title>');
    printWindow.document.write('<style>body { font-family: Arial, sans-serif; } table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #000; padding: 8px; text-align: left; } th { background-color: #f2f2f2; } .no-print { display: none; }</style>');
    printWindow.document.write('</head><body>');
    
    printWindow.document.write(`<h4>ከሰለሞን የአሽከርካሪዎች ማሠልጠኛ ተቋም ለእጩ አሽከርከሪዎች የሚሠጥ የሥልጠና መከታተያ ፎርም</h4>`);
    printWindow.document.write(`
        <p>
            - ሥልጠና የተጀመረበት ቀን: ${trainingDetails.start_date} <br>
            - ሥልጠና የሚጨርስበት ዙር: ${trainingDetails.end_date} <br>
            - ሥልጠና የተሰጠበት የአገልግሎት ዓይነት: ${trainingDetails.category_id} <br>
            - ሥልጠና የተሰጠበት የተሽከርካሪ ሠሌዳ ቁጥር: ${trainingDetails.plate_no} <br>
            - የተግባር አሠልጣኝ ሥም: ${trainingDetails.trainer_name} <br>
            - የሠልጣኝ ሥም: ${trainingDetails.trainee_name} <br>
            - የተማሪ ስልክ : ${trainingDetails.trainee_phone} 
        </p>
    `);
    // Add the attendance table without the "Actions" column
    printWindow.document.write('<table><thead><tr>');
    printWindow.document.write('<th>No</th><th>Date</th><th>Start Time</th><th>Finish Time</th><th>Difference</th><th>Trainee Name</th><th>Trainer Name</th><th>Status</th><th>Comments</th>');
    printWindow.document.write('</tr></thead><tbody>');

    attendanceRows.forEach((row) => {
        printWindow.document.write('<tr>');
        Array.from(row.children).forEach((cell, cellIndex) => {
            if (cellIndex !== row.children.length - 1) {
                printWindow.document.write(`<td>${cell.innerHTML}</td>`);
            }
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
        
        - Training Start Date: <strong> ${trainingDetails.start_date}</strong> <br>
            - Training End Date: ${trainingDetails.end_date} <br>
            - Category: ${trainingDetails.category_id} <br>
            - Car Plate No: ${trainingDetails.plate_no} <br>
            - Trainer Name: ${trainingDetails.trainer_name} <br>
            - Trainee Name: ${trainingDetails.trainee_name} <br>
            - Trainee Phone Number: ${trainingDetails.trainee_phone} 
        </p>
    `);

    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
</script>

@endsection