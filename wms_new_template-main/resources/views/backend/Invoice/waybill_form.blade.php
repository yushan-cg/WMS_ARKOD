@extends('backend.layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Waybill Detail</title>

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
                            <li class="breadcrumb-item active" aria-current="page">Waybill Detail</li>
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
                        <h3 class="box-title">Waybill Form</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">

                            <!-- Waybill Form -->
                            <form id="waybillForm" action="{{ route('waybills.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="no">No:</label>
                                    <input type="text" class="form-control" name="no" id="no" required>
                                </div>
                                <div class="form-group">
                                    <label for="customer_id">Customer ID:</label>
                                    <input type="text" class="form-control" name="customer_id" id="customer_id" required>
                                </div>
                                <div class="form-group">
                                    <label for="service_type">Service Type:</label>
                                    <select class="form-control" name="service_type" id="service_type" required>
                                        <option value="Door to Door">Door to Door</option>
                                        <option value="Pick Up">Pick Up</option>
                                        <option value="Sea Freight">Sea Freight</option>
                                        <option value="Air Freight">Air Freight</option>
                                        <option value="Land Transport">Land Transport</option>
                                    </select>
                                </div>
                                <h3>Shipper Details</h3>
                                {{--  --}}
                                <div class="form-group">
                                    <label for="shipper_name">Shipper Name:</label>
                                    <input type="text" class="form-control" name="shipper_details[name]" id="shipper_name" autocomplete="off" required>
                                    <div id="shipper_dropdown" class="dropdown-list"></div>
                                </div>
                                {{--  --}}
                                <div class="form-group">
                                    <label for="shipper_address">Address:</label>
                                    <input type="text" class="form-control" name="shipper_details[address]" id="shipper_address" required>
                                </div>
                                <div class="form-group">
                                    <label for="shipper_postcode">Postcode:</label>
                                    <input type="text" class="form-control" name="shipper_details[postcode]" id="shipper_postcode" required>
                                </div>
                                <div class="form-group">
                                    <label for="shipper_attention">Attention:</label>
                                    <input type="text" class="form-control" name="shipper_details[attention]" id="shipper_attention">
                                </div>
                                <div class="form-group">
                                    <label for="shipper_tel">Phone:</label>
                                    <input type="text" class="form-control" name="shipper_details[tel]" id="shipper_tel" required>
                                </div>
                                <h3>Receiver Details</h3>
                                {{--  --}}
                                <div class="form-group">
                                    <label for="receiver_name">Receiver Name:</label>
                                    <input type="text" class="form-control" name="receiver_details[name]" id="receiver_name" autocomplete="off" required>
                                    <div id="receiver_dropdown" class="dropdown-list"></div>
                                </div>
                                {{--  --}}
                                <div class="form-group">
                                    <label for="receiver_address">Address:</label>
                                    <input type="text" class="form-control" name="receiver_details[address]" id="receiver_address" required>
                                </div>
                                <div class="form-group">
                                    <label for="receiver_postcode">Postcode:</label>
                                    <input type="text" class="form-control" name="receiver_details[postcode]" id="receiver_postcode" required>
                                </div>
                                <div class="form-group">
                                    <label for="receiver_attention">Attention:</label>
                                    <input type="text" class="form-control" name="receiver_details[attention]" id="receiver_attention">
                                </div>
                                <div class="form-group">
                                    <label for="receiver_tel">Phone:</label>
                                    <input type="text" class="form-control" name="receiver_details[tel]" id="receiver_tel" required>
                                </div>
                                <h3>Order Products</h3>
                                <div class="form-group">
                                    <label for="order_content">Content:</label>
                                    <input type="text" class="form-control" name="order_products[content]" id="order_content">
                                </div>
                                <div class="form-group">
                                    <label for="order_category">Category:</label>
                                    <input type="text" class="form-control" name="order_products[category]" id="order_category">
                                </div>
                                <div class="form-group">
                                    <label for="order_size">Size:</label>
                                    <input type="text" class="form-control" name="order_products[size]" id="order_size">
                                </div>
                                <div class="form-group">
                                    <label for="order_total_weight">Total Weight:</label>
                                    <input type="text" class="form-control" name="order_products[total_weight]" id="order_total_weight">
                                </div>
                                <!-- Back Button -->
                                <a href="{{ route('waybills.index') }}" class="btn btn-secondary">Back</a>
                                <button type="submit" id="generateWaybillButton" class="btn btn-primary">Generate Waybill</button>
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

        $(document).ready(function() {
            // Shipper autocomplete
            $('#shipper_name').on('keyup', function() {
                let query = $(this).val();
                if (query.length >= 2) {
                    $.ajax({
                        url: "{{ route('shipper.search') }}",
                        method: 'GET',
                        data: { query: query },
                        success: function(data) {
                            $('#shipper_dropdown').html(data);
                        }
                    });
                } else {
                    $('#shipper_dropdown').html('');
                }
            });

            // Receiver autocomplete
            $('#receiver_name').on('keyup', function() {
                let query = $(this).val();
                if (query.length >= 2) {
                    $.ajax({
                        url: "{{ route('receiver.search') }}",
                        method: 'GET',
                        data: { query: query },
                        success: function(data) {
                            $('#receiver_dropdown').html(data);
                        }
                    });
                } else {
                    $('#receiver_dropdown').html('');
                }
            });

            // Handle click on shipper dropdown item
            $(document).on('click', '#shipper_dropdown .dropdown-item', function() {
                let shipperDetails = JSON.parse($(this).attr('data-details'));
                fillShipperDetails(shipperDetails);
                $('#shipper_dropdown').html(''); // Clear dropdown
            });

            // Handle click on receiver dropdown item
            $(document).on('click', '#receiver_dropdown .dropdown-item', function() {
                let receiverDetails = JSON.parse($(this).attr('data-details'));
                fillReceiverDetails(receiverDetails);
                $('#receiver_dropdown').html(''); // Clear dropdown
            });

            // Function to fill shipper details
            function fillShipperDetails(details) {
                $('#shipper_name').val(details.name);
                $('#shipper_address').val(details.address);
                $('#shipper_postcode').val(details.postcode);
                $('#shipper_attention').val(details.attention);
                $('#shipper_tel').val(details.tel);
            }

            // Function to fill receiver details
            function fillReceiverDetails(details) {
                $('#receiver_name').val(details.name);
                $('#receiver_address').val(details.address);
                $('#receiver_postcode').val(details.postcode);
                $('#receiver_attention').val(details.attention);
                $('#receiver_tel').val(details.tel);
            }
        });

    //generate waybill in new tab & redirect back to index
    document.getElementById('generateWaybillButton').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default form submission

    const form = document.getElementById('waybillForm');
    const formData = new FormData(form);
    const generateButton = document.getElementById('generateWaybillButton');
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
