@extends('backend.layouts.app')
@section('content')
{{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
<title>Client List</title>
<style>
    .active-row {
        background-color: #f0f0f0; /* Light gray background for customer rows */
    }
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
                        <li class="breadcrumb-item active" aria-current="page">Client List</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="box" style="padding-bottom: 20px;">
        <!-- Include the customer table -->
        @include('backend.company.customer-table')
    </div>
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Client List</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="clientlist" class="table table-hover no-wrap" data-page-size="10">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Attention</th>
                                    <th>Tel</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clients as $client)
                                    <tr class="client-row" data-client-id="{{ $client['id'] }}">
                                        <td>{{ $client['name'] }}</td>
                                        <td>{{ $client['email'] }}</td>
                                        <td>{{ $client['address'] }}</td>
                                        <td>{{ $client['attention'] }}</td>
                                        <td>{{ $client['tel'] }}</td>
                                        <td>
                                            <button class="text-info me-10 add-customer-btn" style="border: none; background: none;" data-client-id="{{ $client['id'] }}" data-bs-toggle="modal" data-bs-target="#addModal" title="Add Customer">
                                                <i class="ti-plus" alt="alert"></i>
                                            </button>
                                            <button class="text-info me-10 update-customer-btn" style="border: none; background: none;" data-client-id="{{ $client->id }}" data-bs-toggle="modal" data-bs-target="#updateModal" title="Update Client">
                                                <i class="ti-marker-alt" alt="alert"></i>
                                            </button>
                                            <button href="" class="text-danger sa-params" style="border: none; background: none;" data-bs-toggle="tooltip" data-bs-original-title="Delete" alt="alert">
                                                <i class="ti-trash" alt="alert"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!--Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add Customer Form -->
                <form id="addCustomerForm" action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="client_id" id="client_id" value="{{ $client->id }}">
                    <div class="form-group">
                        <label for="name">Customer Name</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter customer name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Customer Email</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter customer email" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Customer Address</label>
                        <input type="text" name="address" class="form-control" id="address" placeholder="Enter customer address" required>
                    </div>
                    <div class="form-group">
                        <label for="attention">Attention</label>
                        <input type="text" name="attention" class="form-control" id="attention" placeholder="Enter attention" required>
                    </div>
                    <div class="form-group">
                        <label for="tel">Telephone</label>
                        <input type="text" name="tel" class="form-control" id="tel" placeholder="Enter telephone" required>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Add Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Update Client Form -->
                <form id="updateClientForm" action="{{ route('clients.update', $client->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="client_id" id="client_id" value="{{ $client->id }}">

                    {{-- <input type="hidden" name="client_id" id="update_client_id" value=""> --}}
                    <div class="form-group">
                        <label for="name">Client Name</label>
                        <input type="text" name="name" class="form-control" id="update_name" value="{{ $client->name }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Client Email</label>
                        <input type="email" name="email" class="form-control" id="update_email" value="{{ $client->email }}">
                    </div>
                    <div class="form-group">
                        <label for="address">Client Address</label>
                        <input type="text" name="address" class="form-control" id="update_address" value="{{ $client->address }}">
                    </div>
                    <div class="form-group">
                        <label for="attention">Attention</label>
                        <input type="text" name="attention" class="form-control" id="update_attention" value="{{ $client->attention }}">
                    </div>
                    <div class="form-group">
                        <label for="tel">Telephone</label>
                        <input type="text" name="tel" class="form-control" id="update_tel" value="{{ $client->tel }}">
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Update Client</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


</section>
<!-- /.content -->
<script>
    $(document).ready(function () {
        // Function to close customer table and toggle associated customer table
        function toggleCustomerTable(clientId) {
            // Close all open customer tables
            $('.customer-row').hide();

            // Toggle associated customer table
            $('.customer-row[data-client-id="' + clientId + '"]').toggle();
        }

        // Click event handler for client rows
        $('.client-row').click(function () {
            var clientId = $(this).data('client-id');
            toggleCustomerTable(clientId);

            // Remove active class from all rows
            $('.client-row').removeClass('active-row');
            // Add active class to the clicked row
            $(this).addClass('active-row');

            // Dynamically Update Customer Title
            var clientName = $(this).find('td:first').text(); // Assuming the client name is in the first column
            $('#client-name').text('Customer List - ' + clientName);
        });

        // Prevent toggling when clicking buttons
        $('.client-row button').click(function (e) {
            e.stopPropagation();
        });

        // Handle "Add Customer" button click
        $('.add-customer-btn').click(function() {
            var clientId = $(this).data('client-id');
            $('#client_id').val(clientId);
        });

        // Handle "Update Customer" button click
        $('.update-customer-btn').click(function() {
            var clientId = $(this).data('client-id');
            $('#client_id').val(clientId);
        });

        // Initialize DataTable with pagination
        $('#clientlist').DataTable({
            "paging": true, // Enable pagination
            "pageLength": 10 // Number of rows per page
        });

    });

    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
<script>
// Update Client Modal
// $('#updateModal').on('show.bs.modal', function (event) {
//     var button = $(event.relatedTarget); // Button that triggered the modal
//     var clientId = button.data('client-id'); // Extract client ID from data attribute
//     var modal = $(this);
//     modal.find('.modal-body #update_client_id').val(clientId); // Set the value of the hidden input field in the modal
// });

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
