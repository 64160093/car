@extends('layouts.app')

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Report Document</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
@endsection

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">รายงานผลปฏิบัติงาน</h2>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <form action="{{ route('report.submit') }}" method="POST" enctype="multipart/form-data"
            onsubmit="removeCommasBeforeSubmit()">
            @csrf
            <input type="hidden" name="document_id" value="{{ $documents->document_id }}">

            <!-- Officer Information and Trip Information in a two-column layout -->
            <div class="row mb-4">
                <!-- Officer Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            ข้อมูลเจ้าหน้าที่
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="officer_name" class="col-sm-3 col-form-label text-right">ข้าพระเจ้า
                                    :</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ $documents->carmanUser->name }} {{ $documents->carmanUser->lname }}"
                                        readonly>
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="registration" class="col-sm-3 col-form-label text-right">หมายเลขทะเบียน
                                    :</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="registration" name="registration"
                                        value="{{ $documents->vehicle->car_category }} {{ $documents->vehicle->car_regnumber }} {{ $documents->vehicle->car_province }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="province" class="col-sm-3 col-form-label text-right">จังหวัดที่เดินทางไป
                                    :</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="province" name="province"
                                        value="{{ $documents->province->name_th }} " readonly>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <!-- ข้อมูลการเดินทาง -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            ข้อมูลการเดินทาง
                        </div>
                        <div class="card-body">
                            <div class="form-group row mb-4">
                                <label for="stime"
                                    class="col-sm-5 col-form-label text-right">{{ __('ออกเดินทางเวลา : ') }}</label>
                                <div class="col-sm-7">
                                    <input type="time" class="form-control @error('stime') is-invalid @enderror"
                                        id="stime" name="stime" value="{{ old('stime') }}" required>
                                </div>
                                @error('stime')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group row mb-4">
                                <label for="etime"
                                    class="col-sm-5 col-form-label text-right">{{ __('กลับถึงสถาบันวิทยาศาสตร์เวลา') }}</label>
                                <div class="col-sm-7">
                                    <input type="time" class="form-control @error('etime') is-invalid @enderror"
                                        id="etime" name="etime" value="{{ old('etime') }}" required>
                                </div>
                                @error('etime')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group row mb-4">
                                <label for="total_companion"
                                    class="col-sm-5 col-form-label text-right">{{ __('ผู้ร่วมเดินทาง') }}</label>
                                <div class="col-sm-7">
                                    <input type="number"
                                        class="form-control @error('total_companion') is-invalid @enderror"
                                        id="total_companion" name="total_companion" value="{{ old('total_companion') }}"
                                        required min="1" onkeypress="return isNumberKey(event)">
                                </div>
                                @error('total_companion')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group row mb-4">
                                <label for="skilo_num"
                                    class="col-sm-5 col-form-label text-right">{{ __('หมายเลขกี่โลเมตรก่อนเดินทาง') }}</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control @error('skilo_num') is-invalid @enderror"
                                        id="skilo_num" name="skilo_num" value="{{ old('skilo_num') }}" required
                                        onkeypress="return isNumberKey(event)">
                                </div>
                                @error('skilo_num')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group row mb-4">
                                <label for="ekilo_num"
                                    class="col-sm-5 col-form-label text-right">{{ __('หมายเลขกี่โลเมตรหลังเดินทาง') }}</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control @error('ekilo_num') is-invalid @enderror"
                                        id="ekilo_num" name="ekilo_num" value="{{ old('ekilo_num') }}" required
                                        onkeypress="return isNumberKey(event)">
                                </div>
                                @error('ekilo_num')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <script>
                                function isNumberKey(evt) {
                                    var charCode = (evt.which) ? evt.which : evt.keyCode;
                                    // Allow only numbers and decimal point
                                    if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 46) {
                                        return false;
                                    }
                                    return true;
                                }
                            </script>
                        </div>
                    </div>
                </div>

            </div>


            <!-- ค่าใช้จ่าย -->
            <div class="card mb-4">
                <div class="card-header">
                    ค่าใช้จ่ายที่ต้องออกตามรายการ
                </div>
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="gasoline_cost_checkbox" name="expenses[]"
                            value="gasoline_cost" onchange="toggleInput('gasoline_cost_input', this)" {{ (in_array('gasoline_cost', old('expenses', []))) ? 'checked' : '' }}>
                        <label class="form-check-label" for="gasoline_cost_checkbox">ค่าที่พัก</label>
                        <input type="text" class="form-control mt-2 d-none" id="gasoline_cost_input"
                            name="gasoline_cost" placeholder="จำนวนเงิน" value="{{ old('gasoline_cost') }}"
                            oninput="formatAndCalculate(this);" onkeypress="return isNumberKey(event)">
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="expressway_toll_checkbox" name="expenses[]"
                            value="expressway_toll" onchange="toggleInput('expressway_toll_input', this)" {{ (in_array('expressway_toll', old('expenses', []))) ? 'checked' : '' }}>
                        <label class="form-check-label" for="expressway_toll_checkbox">ค่าอาหาร</label>
                        <input type="text" class="form-control mt-2 d-none" id="expressway_toll_input"
                            name="expressway_toll" placeholder="จำนวนเงิน" value="{{ old('expressway_toll') }}"
                            oninput="formatAndCalculate(this);" onkeypress="return isNumberKey(event)">
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="parking_fee_checkbox" name="expenses[]"
                            value="parking_fee" onchange="toggleInput('parking_fee_input', this)" {{ (in_array('parking_fee', old('expenses', []))) ? 'checked' : '' }}>
                        <label class="form-check-label" for="parking_fee_checkbox">ค่าเชื้อเพลิง</label>
                        <input type="text" class="form-control mt-2 d-none" id="parking_fee_input" name="parking_fee"
                            placeholder="จำนวนเงิน" value="{{ old('parking_fee') }}" oninput="formatAndCalculate(this);"
                            onkeypress="return isNumberKey(event)">
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="another_cost_checkbox" name="expenses[]"
                            value="another_cost" onchange="toggleInput('another_cost_input', this)" {{ (in_array('another_cost', old('expenses', []))) ? 'checked' : '' }}>
                        <label class="form-check-label" for="another_cost_checkbox">อื่น ๆ</label>
                        <input type="text" class="form-control mt-2 d-none" id="another_cost_input" name="another_cost"
                            placeholder="จำนวนเงิน" value="{{ old('another_cost') }}"
                            oninput="formatAndCalculate(this);" onkeypress="return isNumberKey(event)">
                    </div>

                    <div class="form-group mt-4">
                        <label for="total_cost" class="col-sm-5 col-form-label text-right">ค่าใช้จ่ายรวม :</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="total_cost" name="total_cost"
                                value="{{ old('total_cost', '0.00') }}" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function toggleInput(inputId, checkbox) {
                    const inputField = document.getElementById(inputId);
                    if (checkbox.checked) {
                        inputField.classList.remove('d-none');
                        inputField.setAttribute('required', 'required');
                    } else {
                        inputField.classList.add('d-none');
                        inputField.value = '';
                        inputField.removeAttribute('required');
                        calculateTotalCost();
                    }
                }

                function formatNumber(num) {
                    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }

                function formatAndCalculate(input) {
                    // Remove commas for parsing
                    const value = parseFloat(input.value.replace(/,/g, '')) || 0;
                    input.value = formatNumber(value); // Format with commas
                    calculateTotalCost(); // Recalculate total
                }

                function calculateTotalCost() {
                    let totalCost = 0;
                    // Get values and remove commas for parsing
                    const gasolineCost = parseFloat(document.getElementById('gasoline_cost_input').value.replace(/,/g, '')) || 0;
                    const expresswayToll = parseFloat(document.getElementById('expressway_toll_input').value.replace(/,/g, '')) || 0;
                    const parkingFee = parseFloat(document.getElementById('parking_fee_input').value.replace(/,/g, '')) || 0;
                    const anotherCost = parseFloat(document.getElementById('another_cost_input').value.replace(/,/g, '')) || 0;

                    totalCost = gasolineCost + expresswayToll + parkingFee + anotherCost;
                    document.getElementById('total_cost').value = formatNumber(totalCost.toFixed(2)); // Format total cost
                }

                // Add this function to remove commas before form submission
                function removeCommasBeforeSubmit() {
                    function isNumeric(value) {
                        return !isNaN(value) && !isNaN(parseFloat(value));
                    }

                    // List of input fields that are associated with the checkboxes
                    const gasolineCostInput = document.getElementById('gasoline_cost_input');
                    const expresswayTollInput = document.getElementById('expressway_toll_input');
                    const parkingFeeInput = document.getElementById('parking_fee_input');
                    const anotherCostInput = document.getElementById('another_cost_input');

                    // Array to store input fields
                    const fields = [gasolineCostInput, expresswayTollInput, parkingFeeInput, anotherCostInput];

                    // Loop through each input field
                    for (let field of fields) {
                        // Only check fields that are not hidden and have a value
                        if (!field.classList.contains('d-none') && field.value.trim() !== '') {
                            // Remove commas for numeric validation
                            field.value = field.value.replace(/,/g, '').trim();

                            // Check if the value is a valid number
                            if (!isNumeric(field.value)) {
                                alert("Please enter valid numbers in all cost fields.");
                                return false; // Prevent form submission if invalid
                            }
                        }
                    }

                    return true; // Proceed with form submission if all fields are valid
                }
            </script>

            <!-- ความเรียบร้อยในการปฏิบัติงาน -->
            <div class="card mb-4">
                <div class="card-header">
                    การปฏิบัติงาน
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label
                            class="col-sm-3 col-form-label text-right">การปฏิบัติงานครั้งนี้เป็นไปด้วยความเรียบร้อยใช่หรือมไม่
                            :</label>

                        <div class="col-sm-9">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="success_yes" name="performance_isgood"
                                    value="Y" onchange="toggleRemarks(false)">
                                <label class="form-check-label" for="success_yes">ใช่</label>
                            </div>

                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" id="success_no" name="performance_isgood"
                                    value="N" onchange="toggleRemarks(true)">
                                <label class="form-check-label" for="success_no">ไม่</label>
                            </div>

                            <div class="form-group mt-2 d-none" id="remarks_container">
                                <label for="remarks" class="col-sm-3 col-form-label text-right">เนื่องจาก :</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="remarks" name="comment_issue"
                                        rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function toggleRemarks(isNotGood) {
                    const remarksContainer = document.getElementById('remarks_container');
                    const remarksInput = document.getElementById('remarks');

                    if (isNotGood) {
                        remarksContainer.classList.remove('d-none');
                        remarksInput.setAttribute('required', 'required'); // Make remarks field required
                    } else {
                        remarksContainer.classList.add('d-none');
                        remarksInput.removeAttribute('required'); // Remove required attribute
                        remarksInput.value = ''; // Clear the textarea if 'ใช่' is selected
                    }
                }
            </script>

            <!-- Signature -->
            <div class="card mb-4">
                <div class="card-header">
                    ลายเซ็นผู้ส่ง
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label for="signature" class="col-sm-3 col-form-label text-right">ลายเซ็น:</label>
                        <div class="col-sm-9">
                            @if(Auth::user()->signature)
                                <img src="{{ asset('storage/signatures/' . Auth::user()->signature) }}" alt="Signature"
                                    class="img-fluid" style="max-width: 200px;">
                            @else
                                <p>ไม่มีลายเซ็น</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mb-3">
                <button type="submit" class="btn btn-warning">ส่ง</button>
            </div>

        </form>
    </div>
</div>
@endsection