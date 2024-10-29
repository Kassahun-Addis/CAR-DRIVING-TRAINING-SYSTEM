@extends('layouts.app')

@section('title', 'Trainee - List')

@section('content')
<div class="container mt-5">
    <h2 style="text-align: center; padding:10px;">Trainee List</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row mb-3" style="display: flex; justify-content: space-between; align-items: center;">
        <!-- Entries selection and Add New button -->
        <div class="col-12 col-md-6 d-flex justify-content-between mb-2 mb-md-0">
            <form action="{{ route('trainee.index') }}" method="GET" class="form-inline" style="flex: 1;">
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
            <a href="{{ route('trainee.create') }}" class="btn btn-primary ml-2">Add New</a>
        </div>

        <!-- Search and Export buttons -->
        <div class="col-12 col-md-6 d-flex">
            <form action="{{ route('trainee.index') }}" method="GET" class="form-inline" style="flex: 1;">
                <div class="form-group w-100" style="display: flex; align-items: center;">
                    <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}" style="flex-grow: 1; margin-right: 5px; min-width: 0;">
                    <button type="submit" class="btn btn-primary mr-1">Search</button>
                </div>
            </form>

            <!-- Export buttons -->
            <div class="d-flex">
              <form action="{{ route('trainee.export') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="button" class="btn btn-primary mr-1" onclick="window.location.href='{{ route('trainee.exportPdf') }}'">PDF</button>

                    <button type="submit" class="btn btn-primary">Excel</button>
                </form>
            </div>
        </div>
    </div>



<!-- Responsive table wrapper -->
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Yellow card No</th>
                <th>Photo</th>
                <th>Full Name</th>
                <th>Gender</th>
                <th>Nationality</th>
                <th>City</th>
                <th>Sub City</th>
                <th>Woreda</th>
                <th>House No</th>
                <th>Phone No</th>
                <th>PO.Box</th>
                <th>Birth Place</th>
                <th>Education Level</th>
                <th>Blood Type</th>
                <th>Reciept No</th>
                <th>DOB</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trainees as $key => $trainee)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $trainee->yellow_card }}</td>
                    <td>
                        @if($trainee->photo)
                        <img src="{{ asset('storage/trainee_photos/' . $trainee->photo) }}" alt="No Image" width="100" height="100">
                        @else
                            No Image
                        @endif
                    </td>
                    <td>{{ $trainee->full_name }}</td>
                    <td>{{ $trainee->gender }}</td>
                    <td>{{ $trainee->nationality }}</td>
                    <td>{{ $trainee->city }}</td>
                    <td>{{ $trainee->sub_city }}</td>
                    <td>{{ $trainee->woreda }}</td>
                    <td>{{ $trainee->house_no }}</td>
                    <td>{{ $trainee->phone_no }}</td>
                    <td>{{ $trainee->po_box }}</td>
                    <td>{{ $trainee->birth_place }}</td>
                    <td>{{ $trainee->education_level }}</td>
                    <td>{{ $trainee->blood_type }}</td>
                    <td>{{ $trainee->receipt_no }}</td>
                    <td>{{ \Carbon\Carbon::parse($trainee->dob)->format('Y-m-d') }}</td>
                    <td class="text-nowrap">
                        <!-- Toggle Active/Inactive Button -->
                        <button class="btn btn-sm toggle-status {{ $trainee->status === 'active' ? 'btn-success' : 'btn-secondary' }}" data-id="{{ $trainee->id }}">
                            {{ $trainee->status === 'active' ? 'Active' : 'Inactive' }}
                        </button>
                    </td>
                    <td class="text-nowrap">
    <a href="{{ route('trainee.edit', $trainee->id) }}" class="btn btn-warning btn-sm">Edit</a>
    <form action="{{ route('trainee.destroy', $trainee->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this trainee?')">Delete</button>
    </form>
    <button class="btn btn-info btn-sm ml-1" onclick="printDrivingLicenseApplication({{ json_encode($trainee) }})">Print</button>
    <a href="{{ route('trainee.agreement', $trainee->id) }}" class="btn btn-info btn-sm ml-1">Agreement</a>
    <a href="{{ route('attendance.index', ['trainee_id' => $trainee->id, 'trainee_name' => $trainee->full_name]) }}" class="btn btn-danger btn-sm">View Attendance</a>
</td>
</tr>
            @endforeach
        </tbody>
    </table>
</div>


<!-- Showing entries information -->
<div class="mt-3">
    Showing {{ $trainees->firstItem() }} to {{ $trainees->lastItem() }} of {{ $trainees->total() }} entries
</div>

<!-- Customized Pagination -->
<div class="mt-3 d-flex justify-content-between align-items-center">
    <div>
        @if ($trainees->onFirstPage())
            <span class="btn btn-light disabled">Previous</span>
        @else
            <a href="{{ $trainees->previousPageUrl() }}" class="btn btn-light">Previous</a>
        @endif

        @foreach (range(1, $trainees->lastPage()) as $i)
            @if ($i == $trainees->currentPage())
                <span class="btn btn-primary disabled">{{ $i }}</span>
            @else
                <a href="{{ $trainees->url($i) }}" class="btn btn-light">{{ $i }}</a>
            @endif
        @endforeach

        @if ($trainees->hasMorePages())
            <a href="{{ $trainees->nextPageUrl() }}" class="btn btn-light">Next</a>
        @else
            <span class="btn btn-light disabled">Next</span>
        @endif
    </div>

    <!-- Default pagination links -->
    <div>
        {{ $trainees->links() }}
    </div>
</div>
</div>



<!-- JavaScript function to print driving license application form -->
<script>
function printDrivingLicenseApplication(trainee) {
    const printWindow = window.open('', '', 'height=1000,width=900');
    printWindow.document.write('<html><head><title>Driving License Application</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('body { font-family: Arial, sans-serif; margin: 20px; font-size: 16px; }');
    printWindow.document.write('.containerss { border: 2px solid black; padding: 20px; margin: 10px; }');
    printWindow.document.write('.application-form { border: 2px solid black; padding: 20px; margin: 10px; }');
    printWindow.document.write('.header-container { display: flex; justify-content: space-between; align-items: center; }');
    printWindow.document.write('.photo-container { width: 100px; height: 100px; margin-right: 20px; }');
    printWindow.document.write('.photo-container img { width: 100%; height: auto; }');
    printWindow.document.write('.form-section { margin-bottom: 10px; display: flex; align-items: baseline; }');
    printWindow.document.write('strong { margin-right: 5px; }');
    printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
    printWindow.document.write('td, th { border: 1px solid black; padding: 8px; text-align: left; }');
    printWindow.document.write('th { background-color: #f2f2f2; }');
    printWindow.document.write('</style></head><body>');
    
    // Outer border container
    printWindow.document.write('<div class="container">');
    
    // Header with photo and yellow card number
    printWindow.document.write('<div class="header-container">');
    printWindow.document.write('<div class="photo-container">');
    if (trainee.photo) {
        const photoUrl = trainee.photo.startsWith('http') ? trainee.photo : `{{ asset('storage/trainee_photos/') }}/${trainee.photo}`;
        printWindow.document.write('<img src="' + photoUrl + '" alt="No Image">');
    } else {
        printWindow.document.write('<p>No Image</p>');
    }
    printWindow.document.write('</div>');
    
    printWindow.document.write('<div style="width: 65%; margin: 0 auto; text-align: center; word-wrap: break-word;">');
    printWindow.document.write('<h4 style="font-size: 14px; margin-bottom: -5px;">በአዲስ አበባ ከተማ አስተዳደር</h4>');
    printWindow.document.write('<h4 style="font-size: 14px; margin-bottom: -5px;">የአሽከርካሪና ተሽከርካሪ ፈቃድ ቁጥጥር ባለስልጣን</h4>');
    printWindow.document.write('<h4 style="font-size: 14px; margin-bottom: -5px;">City Government of Addis Ababa</h4>');
    printWindow.document.write('<h4 style="font-size: 14px; margin-bottom: -5px;">Driver & Vehicle, Licensing & Control Authority</h4>');
    printWindow.document.write('<h4 style="font-size: 14px; margin-bottom: -5px;">የመንጃ ፈቃድ ማመልከቻና መከታተያ ቅጽ</h4>');
    printWindow.document.write('<h4 style="font-size: 14px; margin-bottom: -5px;">Driving License Application & Follow-Up Form</h4>');
    printWindow.document.write('</div>');
    
    printWindow.document.write('<h4>No: ' + trainee.yellow_card + '</h4>');
    printWindow.document.write('</div>');

    printWindow.document.write('<p><strong>1. በአመልካቹ የሚሞላ </strong></p>');
    printWindow.document.write('<p><strong>&nbsp;&nbsp;&nbsp Filled by the Applicant</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;የማሰልጠኛ ተቋሙ ስም(D.L. School___________)</p>');

    // Add the form fields with reduced distance between labels and values
    printWindow.document.write('<div class="application-form">');
    printWindow.document.write('<div class="form-section"><strong>1.1 Name:</strong> ' + trainee.full_name + '  &nbsp;&nbsp;<strong> Gender:</strong> ' + trainee.gender + '&nbsp;&nbsp;<strong> Nationality:</strong> ' + trainee.nationality + '</div>');
    printWindow.document.write('<div class="form-section"><strong>1.2 City:</strong> ' + trainee.city + '&nbsp;&nbsp;<strong> Sub-City:</strong> ' + trainee.sub_city + '&nbsp;&nbsp;<strong> Woreda:</strong> ' + trainee.woreda + '&nbsp;&nbsp;<strong> House Ho:</strong> ' + trainee.house_no + '</div>');
    printWindow.document.write('<div class="form-section"><strong>1.3 PO Box:</strong> ' + trainee.po_box + '&nbsp;&nbsp;<strong> Place of Birth:</strong> ' + trainee.birth_place + '&nbsp;&nbsp;<strong> Phone No:</strong> ' + trainee.phone_no + '</div>');
    printWindow.document.write('<div class="form-section"><strong>1.4 Driving License No:</strong> ' + trainee.driving_license_no + '&nbsp;&nbsp; <strong> Education Level:</strong> ' + trainee.education_level + '</div>');
    printWindow.document.write('<p>1.5 የሚጥል በሽታ ፤ የልብ ድካምና የደም ግፊት የለብኝም ፤ ከዚህ የተሞላውን በሙሉ ትክክለኛነቱን አረጋግጣለሁ።</p>');
    printWindow.document.write('<p> I have no problem of epilepsy, heart attack, blood pressure: I confirm the genuineness of the information filled here above.</p>');
    printWindow.document.write('<div class="form-section"><strong>1.6 የደም አይነት/Blood Type:</strong> ' + trainee.blood_type + '</div>');
    printWindow.document.write('<div class="form-section"><strong>&nbsp;&nbsp; የአመልካቹ ፊርማ:</strong> ___________________&nbsp;&nbsp; ቀን: ' + new Date().toISOString().split('T')[0] + '</div>');
    printWindow.document.write('<div class="form-section"><strong>&nbsp;&nbsp; Applicants Name:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date </div>');
    printWindow.document.write('<div class="form-section"><strong>1.7 የተከፈለበት ደረሰኝ ቁጥር :</strong> ' + trainee.receipt_no + '</div>');
    printWindow.document.write('<div class="form-section"><strong>&nbsp;&nbsp; Receipt No:</strong> ' + trainee.receipt_no + '</div>');
    // Close the outer container
    printWindow.document.write('</div>');
    printWindow.document.write('</div>');

    printWindow.document.write('<p><strong>2. ስለጤንነት ሁኔታ መግለጫ </strong></p>');
    printWindow.document.write('<p><strong>2.1 የአመልካቹ ጤንነት ሁኔታ____________________________________________________________________</strong></p>');
    printWindow.document.write('<p><strong>__________________________________________________________________</strong></p>');
    printWindow.document.write('<p><strong>2.2 የባለሙያ ዉሳኔ፦  ብቁ ነው___________________ብቁ አይደለም___________________ </strong></p>');
    printWindow.document.write('<p><strong>2.3 ያረጋገጠው ባለሙያ ስም___________________ፊርማ_____________ቀን______________________ </strong></p>');
    
    printWindow.document.write('<p><strong>3. ስለፈተና ዉጤት ዝርዝር ሁኔታ፤   3.1 የመንገድ ምልክቶችና የመንገድ ስነ-ስርአት </strong></p>');

    // Add the table at the end
    printWindow.document.write('<table>');
    printWindow.document.write('<tr><th>ተቁ</th><th>ፈተና የተሰጠበት ቀን</th><th>የሰሌዳ ቁጥር</th><th>ዉጤት</th><th>የፈታኝ ስምና ፊርማ</th><th>ያረጋገጠው ሃላፊ ስምና ፊርማ</th><th>ምርመራ</th></tr>');
    printWindow.document.write('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>');
    printWindow.document.write('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>');
    printWindow.document.write('</table>');

    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
}
</script>

<!-- JavaScript function to print all bank details in table format -->
<script>
function printAllBankDetails() {
const trainees = @json($trainees->items()); // Use items() to get the array of bank details

// Check if trainees is an array
console.log(trainees); // Debugging line to see the content of $trainees

const printWindow = window.open('', '', 'height=500,width=800');
printWindow.document.write('<html><head><title>All trainees Details</title>');
printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; }');
printWindow.document.write('th, td { border: 1px solid black; padding: 10px; text-align: left; }</style>');
printWindow.document.write('</head><body>');
printWindow.document.write('<h2>All trainees Details</h2>');
printWindow.document.write('<table>');

// Add table headers
printWindow.document.write('<thead><tr>');
printWindow.document.write('<th>ID</th>');
printWindow.document.write('<th>Name</th>');
printWindow.document.write('<th>Description</th>');

// Add more headers as needed
printWindow.document.write('</tr></thead><tbody>');

// Loop through the trainees data
trainees.forEach(bank => {
    printWindow.document.write('<tr>');
    printWindow.document.write(<td>${bank.bank_id}</td>); // Use bank_id here
    printWindow.document.write(<td>${bank.bank_name}</td>); // Use bank_name here
    printWindow.document.write(<td>${bank.description}</td>); // Use description here

    // Add more fields as needed
    printWindow.document.write('</tr>');
});

printWindow.document.write('</tbody></table>');
printWindow.document.write('</body></html>');
printWindow.document.close();
printWindow.print();
}
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-status').forEach(button => {
        button.addEventListener('click', function() {
            const currentStatus = this.textContent.trim();
            const newStatus = currentStatus === 'Active' ? 'Inactive' : 'Active';
            const traineeId = this.getAttribute('data-id');

            // Toggle the button text and class
            this.textContent = newStatus;
            this.classList.toggle('btn-success');
            this.classList.toggle('btn-secondary');

            // Send an AJAX request to update the status in the database
            fetch(`/trainees/${traineeId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: newStatus.toLowerCase() })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status !== 'success') {
                    console.error('Error updating status:', data.message);
                    // Optionally, revert the button state if the update fails
                    this.textContent = currentStatus;
                    this.classList.toggle('btn-success');
                    this.classList.toggle('btn-secondary');
                }
            })
            .catch(error => {
                console.error('Error updating status:', error);
                // Optionally, revert the button state if the update fails
                this.textContent = currentStatus;
                this.classList.toggle('btn-success');
                this.classList.toggle('btn-secondary');
            });
        });
    });
});
</script>
@endsection