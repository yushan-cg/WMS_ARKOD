@extends('backend.layouts.app')
@section('content')
<title>Waybill List</title>

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
                        <li class="breadcrumb-item active" aria-current="page">Waybill List</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    @if(session('success'))
        <div id="successAlert" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Waybill List</h3>
                    {{-- add button for waybill --}}
                    <a href="{{ route('waybills.create') }}" class="btn btn-success" style="width: 150px; float: right; padding-right: 5px;" title="Add Waybill">Add Waybill</a>
                </div>


                <div class="box-body">
                    <div class="table-responsive">
                        <table id="waybill_list" class="table table-hover no-wrap" data-page-size="10">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Waybill No.</th>
                                    <th>Remarks</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($waybills as $waybill)
                                <tr data-waybill-id="{{ $waybill['id']}}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $waybill->waybill_no }}</td>
                                    <td>{{ $waybill->remarks }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                        {{-- generate PDF --}}
                                        <button class="text-info me-2" style="border: none; background: none;" data-bs-toggle="tooltip" data-bs-original-title="Generate PDF" alt="alert" onclick="window.open('{{ route('waybills.pdf', $waybill->id) }}', '_blank')">
                                            <i class="ti-printer" alt="alert"></i>
                                        </button>
                                        {{-- add remarks --}}
                                        <button class="text-info me-2" style="border: none; background: none;" data-bs-toggle="tooltip" data-bs-original-title="Add Remarks" alt="alert" onclick="showRemarksModal('{{ $waybill->id }}')">
                                            <i class="ti-plus" alt="alert"></i>
                                        </button>
                                            <!-- Delete Form -->
                                            <form id="deleteForm{{ $waybill->id }}" action="{{ route('waybills.destroy', ['id' => $waybill->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="text-danger sa-params" style="border: none; background: none;" data-bs-toggle="tooltip" data-bs-original-title="Delete" alt="alert" onclick="confirmDelete('{{ $waybill->id }}')">
                                                    <i class="ti-trash" alt="alert"></i>
                                                </button>
                                            </form>
                                        </div>
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

    <!-- Add Remarks Modal -->
<div class="modal fade" id="remarksModal" tabindex="-1" aria-labelledby="remarksModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="remarksModalLabel">Add Remarks</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="remarksForm">
                    @csrf
                    <input type="hidden" id="waybillId" name="waybill_id">
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->
<script>
    $(document).ready(function () {

        // Initialize DataTable with pagination
        $('#waybill_list').DataTable({
            "paging": true, // Enable pagination
            "pageLength": 10 // Number of rows per page
        });

    });

    //delete confirmation
    var waybillId = $(this).data('waybill-id');
    function confirmDelete(waybillId) {
        if (confirm('Are you certain you wish to remove this record?')) {
            document.getElementById('deleteForm' + waybillId).submit();
        }
    }

    // Automatically close the success alert after 3 seconds
    setTimeout(function() {
        document.getElementById('successAlert').style.display = 'none';
    }, 3000); // 3000 milliseconds = 3 seconds

    // Show remarks modal
    function showRemarksModal(waybillId) {
        $('#waybillId').val(waybillId);
        $('#remarksModal').modal('show');
    }

    // Handle remarks form submission
    $('#remarksForm').submit(function(event) {
        event.preventDefault();

        const formData = $(this).serialize();

        $.ajax({
            url: '{{ route("waybills.addRemarks") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#remarksModal').modal('hide');
                    location.reload(); // Reload the page to reflect changes
                } else {
                    alert('Failed to add remarks. Please try again.');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
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
