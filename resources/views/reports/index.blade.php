@extends('layouts.app')

@section('title', 'Generate Reports')

@section('content')
<div class="container mt-5">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }
        .form-group label {
            font-weight: 500;
            color: #343a40;
        }
        .form-control, .btn-primary {
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        .form-control:hover {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .filter-section {
            display: none;
            margin-top: 20px;
        }
        .filter-group {
            margin-top: 15px;
        }
        .icon {
            margin-right: 8px;
            color: #007bff;
        }
        @media (max-width: 576px) {
            .container {
                padding: 15px;
            }
            h2 {
                font-size: 1.5rem;
            }
            .btn-primary {
                font-size: 14px;
                padding: 8px;
            }
        }
    </style>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="mt-3 mb-3">
        <h2><i class="icon fas fa-chart-bar"></i>Generate Reports</h2>
        <form action="{{ route('reports.generate') }}" method="GET">
            <div class="form-group">
                <label for="record_type"><i class="icon fas fa-database"></i>Select Report Type:</label>
                <select name="record_type" id="record_type" class="form-control" required onchange="updateFilters()">
                    <option value="">Select...</option>
                    <option value="trainees">Trainees</option>
                    <option value="payments">Payments</option>
                    <option value="trainers">Trainers</option>
                    <option value="exams">Exams</option>
                    <option value="classes">Classes</option>
                </select>
            </div>

            <div id="filters" class="filter-section">
                <div class="form-group">
                    <label for="filter_type"><i class="icon fas fa-filter"></i>Select Filter Type:</label>
                    <select id="filter_type" class="form-control" onchange="updateSubFilters()">
                        <option value="">Select...</option>
                    </select>
                </div>

                <div id="gender_options" class="sub-filter filter-group">
                    <label for="gender_option"><i class="icon fas fa-venus-mars"></i>Gender Options:</label>
                    <select name="gender_option" id="gender_option" class="form-control">
                        <option value="">Please select</option>
                        <option value="all">All</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                <div id="category_options" class="sub-filter filter-group">
                    <label for="category_option"><i class="icon fas fa-list"></i>Category Options:</label>
                    <select name="category_option" id="category_option" class="form-control">
                        <option value="">Please select</option>    
                        <option value="ደረቅ 1">ደረቅ 1</option>
                        <option value="ደረቅ 2">ደረቅ 2</option>
                        <option value="ደረቅ 3">ደረቅ 3</option>
                        <option value="ፈሳሽ 1">ፈሳሽ 1</option>
                        <option value="ፈሳሽ 2">ፈሳሽ 2</option>
                        <option value="ህዝብ 1">ህዝብ 1</option>
                        <option value="ህዝብ 2">ህዝብ 2</option>
                        <option value="ህዝብ 3">ህዝብ 3</option>
                        <option value="ባለ ሶስት እግር">ባለ ሶስት እግር</option>
                        <option value="ሞተርሳይክል">ሞተርሳይክል</option>
                        <option value="አውቶሞቢል">አውቶሞቢል</option>
                        <option value="ማሽነሪ ኦፕሬተር">ማሽነሪ ኦፕሬተር</option>
                    </select>
                </div>

                <div id="education_level_options" class="sub-filter filter-group">
                    <label for="education_level_option"><i class="icon fas fa-graduation-cap"></i>Education Level Options:</label>
                    <select name="education_level_option" id="education_level_option" class="form-control">
                        <option value="">Please select</option>
                        <option value="10">10th</option>
                        <option value="12">12th</option>
                        <option value="Degree">Degree</option>
                        <option value="master">Master</option>
                        <option value="PHD">PHD</option>

                    </select>
                </div>

                <div id="blood_type_options" class="sub-filter filter-group">
                    <label for="blood_type_option"><i class="icon fas fa-tint"></i>Blood Type Options:</label>
                    <select name="blood_type_option" id="blood_type_option" class="form-control">
                            <option value="">Please select</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="O">O</option>
                            <option value="AB">AB</option>
                            <option value="A+">A+</option>
                            <option value="B+">B+</option>
                            <option value="A-">A-</option>
                            <option value="B-">B-</option>
                            <option value="O+">O+</option>
                    </select>
                </div>

                <div id="payment_status_options" class="sub-filter filter-group">
                    <label for="payment_status_option"><i class="icon fas fa-money-bill-wave"></i>Payment Status Options:</label>
                    <select name="payment_status_option" id="payment_status_option" class="form-control">
                        <option value="">Please select</option>
                        <option value="Paid">Paid</option>
                        <option value="Partial">Partial</option> <!-- Updated from Partially to Partial -->
                        <option value="Unpaid">Unpaid</option>
                    </select>
                </div>
                <div id="status_options" class="sub-filter filter-group">
                    <label for="status_option"><i class="icon fas fa-check"></i>Status Options:</label>
                    <select name="status_option" id="status_option" class="form-control">
                        <option value="">Please select</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div id="exams_options" class="sub-filter filter-group">
                    <label for="exams_option"><i class="icon fas fa-check"></i>Exams Options:</label>
                    <select name="exams_option" id="exams_option" class="form-control">
                        <option value="">Please select</option>
                        <option value="Passed">Passed</option>
                        <option value="Failed">Failed</option>
                    </select>
                </div>
                <div id="classes_options" class="sub-filter filter-group">
                    <label for="classes_option"><i class="icon fas fa-check"></i>Classes Options:</label>
                    <select name="classes_option" id="classes_option" class="form-control">
                        <option value="">Please select</option>
                        <option value="Class 1">Class 1</option>
                        <option value="Class 2">Class 2</option>
                        <option value="Class 3">Class 3</option>
                        <option value="Class 4">Class 4</option>
                        <option value="Class 5">Class 5</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-12 form-group">
                    <label for="start_date"><i class="icon fas fa-calendar-alt"></i>Start Date:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control">
                </div>
                <div class="col-md-6 col-sm-12 form-group">
                    <label for="end_date"><i class="icon fas fa-calendar-alt"></i>End Date:</label>
                    <input type="date" name="end_date" id="end_date" class="form-control">
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3"><i class="fas fa-file-alt"></i> Generate Report</button>
        </form>
    </div>
</div>

<!-- FontAwesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" defer></script>

<script>
    const form = document.querySelector('form');
    if (!form) {
        console.error('Form not found');
    }

    form.addEventListener('submit', function(event) {
        const recordType = document.getElementById('record_type').value;
        const filterType = document.getElementById('filter_type').value;

        if (!recordType) {
            alert('Please select a report type.');
            event.preventDefault();
        } else if (!filterType) {
            alert('Please select a filter type.');
            event.preventDefault();
        }
    });

    function updateFilters() {
        const recordType = document.getElementById('record_type').value;
        const filtersSection = document.getElementById('filters');
        const filterTypeSelect = document.getElementById('filter_type');

        filterTypeSelect.selectedIndex = 0;
        filtersSection.style.display = recordType ? 'block' : 'none';
        document.querySelectorAll('.sub-filter').forEach(subFilter => subFilter.style.display = 'none');

        if (recordType === 'trainees') {
            filterTypeSelect.innerHTML = `
                <option value="">Select...</option>
                <option value="gender">Gender</option>
                <option value="category">Category</option>
                <option value="blood_type">Blood Type</option>
                <option value="education_level">Education Level</option>
                <option value="status">Status</option>
            `;
        } else if (recordType === 'payments') {
            filterTypeSelect.innerHTML = `
                <option value="">Select...</option>
                <option value="payment_status">Payment Status</option>
            `;
        } else if (recordType === 'trainers') {
            filterTypeSelect.innerHTML = `
                <option value="">Select...</option>
                <option value="status">Status</option>
            `;
        } else if (recordType === 'exams') {
            filterTypeSelect.innerHTML = `
                <option value="">Select...</option>
                <option value="exams">Score</option>
            `;
        } else if (recordType === 'classes') {
            filterTypeSelect.innerHTML = `
                <option value="">Select...</option>
                <option value="classes">Class Lists</option>
            `;
        } else {
            filterTypeSelect.innerHTML = `<option value="">Select...</option>`;
        }
    }

    function updateSubFilters() {
        const selectedFilter = document.getElementById('filter_type').value;
        document.querySelectorAll('.sub-filter').forEach(subFilter => subFilter.style.display = 'none');
        if (selectedFilter) {
            const subFilterElement = document.getElementById(selectedFilter + '_options');
            if (subFilterElement) {
                subFilterElement.style.display = 'block';
            }
        }
    }
</script>


@endsection