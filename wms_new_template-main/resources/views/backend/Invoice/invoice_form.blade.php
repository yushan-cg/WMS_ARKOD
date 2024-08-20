@extends('backend.layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>Invoice Detail</title>

<style>

</style>
<!-- Include necessary scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<div class="content-header">
    <div class="d-flex align-items-center">
        <div class="me-auto">
            <h4 class="page-title">Data Tables</h4>
            <div class="d-inline-block align-items-center">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ URL::to('/home') }}"><i class="mdi mdi-home-outline"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Invoice Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Invoice Form</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <!-- Invoice Form -->
                        <form id="invoiceForm" action="{{ route('invoices.store') }}"  method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="no">No : </label>
                                <input type="text" name="no" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="customer_id">Customer ID</label>
                                <input type="text" name="customer_id" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="sst_percentage">SST Percentage</label>
                                <input type="number" name="sst_percentage" class="form-control" id="sst_percentage" required>
                            </div>
                            <div class="form-group">
                                <label for="payment_method">Payment Method:</label>
                                <select class="form-control" name="payment_method" id="payment_method" required>
                                    <option value="Online Banking">Online Banking</option>
                                    <option value="Cheques">Cheques</option>
                                    <option value="Cash">Cash</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" id="name" autocomplete="off" required>
                                <div id="dropdown" class="dropdown-list"></div>
                            </div>
                            <div class="form-group">
                                <label for="attention">Attention</label>
                                <input type="text" class="form-control" name="attention" id="attention">
                            </div>
                            <div class="form-group">
                                <label for="address">Full Address</label>
                                <input type="text" class="form-control" name="address" id="address" required>
                            </div>
                            <div class="form-group">
                                <label for="tel">Phone</label>
                                <input type="text" class="form-control" name="tel" id="tel" required>
                            </div>
                            <table id="termsTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Payment Terms</th>
                                        <th>Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" name="payment_terms" class="form-control"></td>
                                        <td><input type="date" name="due_date" class="form-control" value=""></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="invoiceTable" class="table table-bordered">
                                <label for="items">Items</label>
                                <thead>
                                    <tr>
                                        <th>Quantity</th>
                                        <th>Description</th>
                                        <th>Unit Price RM</th>
                                        <th>Total RM</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="number" name="items[0][quantity]" class="form-control" oninput="calculateRow(this)"></td>
                                        <td><input type="text" name="items[0][description]" class="form-control"></td>
                                        <td><input type="number" step="0.01" name="items[0][unit_price]" class="form-control" oninput="calculateRow(this)"></td>
                                        <td><input type="number" step="0.01" name="items[0][total_price]" class="form-control" readonly></td>
                                        <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-primary" onclick="addRow()">Add Row</button>
                            <table class="table table-bordered" style="width: auto; margin-right: auto; margin-top:15px;">
                                <tbody>
                                    <tr>
                                        <th style="width: 130px;">Subtotal: </th>
                                        <td style="width: 130px;">RM <span id="subtotal_price">0</span></td>
                                    </tr>
                                    <tr>
                                        <th style="width: 130px;">SST : </th>
                                        <td style="width: 130px;">RM <span id="sst">0</span></td>
                                    </tr>
                                    <tr>
                                        <th style="width: 130px;">Final Total</th>
                                        <td style="width: 130px;">RM <span id="final_price">0</span></td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- Back Button -->
                            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Back</a>
                            <button type="submit" id="generateInvoiceButton" class="btn btn-primary">Generate Invoice</button>
                                <button id="loadingButton" class="btn btn-primary" type="button" style="display:none; background-color: #007bff; border-color: #007bff; color: #fff;" disabled>
                                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                    <span role="status">Loading...</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
<script>
    function addRow() {
        const table = document.getElementById('invoiceTable').getElementsByTagName('tbody')[0];
        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);
        row.innerHTML = `

            <td><input type="number" name="items[${rowCount}][quantity]" class="form-control" oninput="calculateRow(this)"></td>
            <td><input type="text" name="items[${rowCount}][description]" class="form-control"></td>
            <td><input type="number" step="0.01" name="items[${rowCount}][unit_price]" class="form-control" oninput="calculateRow(this)"></td>
            <td><input type="number" step="0.01" name="items[${rowCount}][total_price]" class="form-control" readonly></td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button></td>
        `;
    }

    function removeRow(button) {
        var row = button.closest('tr');
        row.remove();
        calculateTotal();
    }

        function calculateRow(input) {
        var row = input.closest('tr');
        var quantity = parseFloat(row.querySelector('[name$="[quantity]"]').value) || 0;
        var unitPrice = parseFloat(row.querySelector('[name$="[unit_price]"]').value) || 0;
        var totalPrice = quantity * unitPrice;
        row.querySelector('[name$="[total_price]"]').value = totalPrice.toFixed(2);
        calculateTotal();
    }

    function calculateTotal() {
        var table = document.getElementById('invoiceTable').getElementsByTagName('tbody')[0];
        var rowCount = table.rows.length;
        var subtotal = 0;
        for (var i = 0; i < rowCount; i++) {
            var row = table.rows[i];
            var totalPrice = parseFloat(row.querySelector('[name$="[total_price]"]').value) || 0;
            subtotal += totalPrice;
        }
        document.getElementById('subtotal_price').innerText = subtotal.toFixed(2);
        var sstPercentage = parseFloat(document.getElementById('sst_percentage').value) || 0;
        var sst = (sstPercentage / 100) * subtotal;
        document.getElementById('sst').innerText = sst.toFixed(2);
        document.getElementById('final_price').innerText = (subtotal + sst).toFixed(2);
    }


    $(document).ready(function() {
        // Customer autocomplete details
        $('#name').on('keyup', function() {
            let query = $(this).val();
            if (query.length >= 2) {
                $.ajax({
                    url: "{{ route('customer.search') }}",
                    method: 'GET',
                    data: { query: query },
                    success: function(data) {
                        $('#dropdown').html(data);
                    }
                });
            } else {
                $('#dropdown').html('');
            }
        });

        // Handle click on customer dropdown item
        $(document).on('click', '#dropdown .dropdown-item', function() {
            let customerDetails = JSON.parse($(this).attr('data-details'));
            fillcustomerDetails(customerDetails);
            $('#dropdown').html(''); // Clear dropdown
        });

        // Function to fill customer details
        function fillcustomerDetails(details) {
            $('#name').val(details.name);
            $('#address').val(details.address);
            $('#postcode').val(details.postcode);
            $('#attention').val(details.attention);
            $('#tel').val(details.tel);
        }
    });


    //generate invoice in new tab & redirect back to index
    document.getElementById('generateInvoiceButton').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default form submission

    const form = document.getElementById('invoiceForm');
    const formData = new FormData(form);
    const generateButton = document.getElementById('generateInvoiceButton');
    const loadingButton = document.getElementById('loadingButton');

    // Show the loading button and hide the generate button
    generateButton.style.display = 'none';
    loadingButton.style.display = 'inline-block';

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        // Hide the loading button and show the generate button
        loadingButton.style.display = 'none';
        generateButton.style.display = 'inline-block';

        if (data.pdf_url && data.redirect_url) {
            // Open the PDF in a new tab
            window.open(data.pdf_url, '_blank');

            // Redirect to the specified URL
            window.location.href = data.redirect_url;
        } else {
            console.error('Incomplete data received:', data);
            // Handle incomplete data case
        }

    })
    .catch(error => {
        // Hide the loading button and show the generate button
        loadingButton.style.display = 'none';
        generateButton.style.display = 'inline-block';

        console.error('Error:', error);
        // Handle the error here, e.g., display an error message
    });
});

</script>
@endsection

@section('page content overlay')
<!-- Page Content overlay -->

<!-- Vendor JS -->
<script src="{{ asset('assets/js/vendors.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/chat-popup.js') }}"></script>
<script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/vendor_components/datatable/datatables.min.js') }}"></script>

<!-- Deposito Admin App -->
<script src="{{ asset('assets/js/template.js') }}"></script>
<script src="{{ asset('assets/js/pages/data-table.js') }}"></script>
@endsection
