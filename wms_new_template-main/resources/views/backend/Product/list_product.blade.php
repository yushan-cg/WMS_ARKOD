@extends('backend.Layouts.app')
@section('content')
<title>Product List</title>
<style>
.fixed-row {
    width: 50%; /* Adjust width as needed */
}
.fixed-col {
    width: 50%; /* Adjust width as needed */
}

.fixed-col img {
    width: 100%; /* Make image fill the column */
    height: auto; /* Maintain aspect ratio */
    max-height: 150px; /* Maximum height of images */
}

.text-danger {
	border: none;
	padding: 0;
	background: none;
}
</style>

<div>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center">
            <div class="me-auto">
                <h4 class="page-title">Data Tables</h4>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item" aria-current="page">List</li>
                            <li class="breadcrumb-item active" aria-current="page">Product List</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- waithing for approval table --}}
    <section class="content">
		<div class="row">
			<div class="col-12">
			<div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Product : Waiting For Approval</h3>
                </div>

				<div class="box-body">
				<div class="table-responsive">
					<table id="productApproval" class="table table-hover no-wrap product-order" data-page-size="5">
						<thead>
							<tr>
							<th>ID</th>
                            @if (Auth::user()->role == 1)
							<th>Clients</th>
							@endif
							<th>SKU</th>
							<th>Product Name</th>
							<th>Description</th>
                            <th>UOM</th>
							<th>Weight Per Unit</th>
							<th>Expired Date</th>
							<th>Product Image</th>
							<th>Status</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
						@foreach ($list as $index => $row)
                            @if ($row->status == 'Rejected' || $row->status == 'Pending')
                                <tr class="fixed-row fixed-col">
                                    {{-- <td>{{ $loop->iteration }}</td> --}}
                                    <td>{{ $row->id }}</td>
                                    @if (Auth::user()->role == 1)
                                        <td>{{ $row->client_name }}</td>
                                    @endif
                                    <td>{{ $row->SKU }}</td>
                                    <td>{{ $row->product_name }}</td>
                                    <td>{{ $row->product_desc }}</td>
                                    <td>{{ $row->UOM }}</td>
                                    <td>{{ $row->weight_per_unit }}</td>
                                    <td>{{ $row->expired_date }}</td>
                                    <td>
                                        <img src="{{ asset('product_img/' . $row->Img) }}" alt="{{ $row->product_name }}" width="50" height="50">
                                    </td>
                                    <td>{{ $row->status }}</td>

                                        <td>
                                            @if (Auth::user()->role == 1)
                                            <form action="{{ route('approve_product', $row->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success">Approve</button>
                                            </form>
                                            <form action="{{ route('reject_product', $row->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-danger">Reject</button>
                                            </form>
                                            @endif
                                            <!-- end modal -->
                                            <button type="button" class="text-danger sa-params" style="border: none; background: none;" data-bs-toggle="tooltip" data-bs-original-title="Delete" alt="alert" onclick="confirmDelete('{{ $row->id }}')">
                                                <i class="ti-trash" alt="alert"></i>
                                            </button>
                                                <form id="delete-product-form-{{ $row->id }}" action="{{ route('delete_product', $row->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                </tr>
                            @endif
						@endforeach
						</tbody>
					</table>
				</div>
				</div>
			</div>
			</div>
		</div>
	</section>

    <!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-12">
			<div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Product List</h3>
                    {{-- add button for product --}}
                    <button class="btn btn-success" style="width: 150px; float: right; padding-right: 5px;" data-bs-toggle="modal" data-bs-target="#addProductModal" title="Add Product">Add Product</button>
                    <x-modal-form
                        modalId="addProductModal"
                        modalTitle="Add Product"
                        formId="addProductForm"
                        formAction="{{ route('insert_product') }}"
                        submitButton="Add this product">
                        @include('backend.product.create_product')
                    </x-modal-form>
                </div>

				<div class="box-body">
				<div class="table-responsive">
					<table id="productorder" class="table table-hover no-wrap product-order" data-page-size="10">
						<thead>
							<tr>
							<th>ID</th>
                            @if (Auth::user()->role == 1)
							<th>Clients</th>
							@endif
							<th>SKU</th>
							<th>Product Name</th>
							<th>Description</th>
                            <th>UOM</th>
							<th>Weight Per Unit</th>
							<th>Expired Date</th>
							<th>Product Image</th>
							@if (Auth::user()->role == 1)
							<th>Action</th>
							@endif
							</tr>
						</thead>
						<tbody>
						@foreach ($list as $index => $row)
                            @if ($row->status == 'Approved')
                                <tr class="fixed-row fixed-col">
                                    {{-- <td>{{ $loop->iteration }}</td> --}}
                                    <td>{{ $row->id }}</td>
                                    @if (Auth::user()->role == 1)
                                        <td>{{ $row->client_name }}</td>
                                    @endif
                                    <td>{{ $row->SKU }}</td>
                                    <td>{{ $row->product_name }}</td>
                                    <td>{{ $row->product_desc }}</td>
                                    <td>{{ $row->UOM }}</td>
                                    <td>{{ $row->weight_per_unit }}</td>
                                    <td>{{ $row->expired_date }}</td>
                                    <td>
                                        <img src="{{ asset('product_img/' . $row->Img) }}" alt="{{ $row->product_name }}" width="50" height="50">
                                    </td>
                                    @if (Auth::user()->role == 1)
                                        <td>
                                            <!-- Add any action buttons or links here -->
                                            <button class="text-info me-2" style="border: none; background: none;" data-bs-toggle="modal" data-bs-original-title="Edit Product" alt="alert" data-bs-target="#editProductModal{{ $row->id }}">
                                                <i class="ti-pencil-alt" alt="alert"></i>
                                            </button>
                                            <!-- Modal for editing product -->
                                            <x-modal-form
                                                modalId="editProductModal{{ $row->id }}"
                                                modalTitle="Edit Product"
                                                formId="editProductForm{{ $row->id }}"
                                                formAction="{{ route('update_product', $row->id) }}"
                                                submitButton="Save edit">
                                                <form id="editProductForm{{ $row->id }}" action="{{ route('update_product', $row->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PATCH')
                                                    <!-- Form fields -->
                                                    <div class="modal-body">
                                                        @include('backend.product.edit_product', ['product' => $row])
                                                    </div>
                                            </x-modal-form>
                                            <!-- end modal -->
                                            <button type="button" class="text-danger sa-params" style="border: none; background: none;" data-bs-toggle="tooltip" data-bs-original-title="Delete" alt="alert" onclick="confirmDelete('{{ $row->id }}')">
                                                <i class="ti-trash" alt="alert"></i>
                                            </button>
                                            <form id="delete-product-form-{{ $row->id }}" action="{{ route('delete_product', $row->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endif
						@endforeach
						</tbody>
					</table>
				</div>
				</div>
			</div>
			</div>
		</div>
	</section>
	<!-- /.content -->
@endsection
@section('page content overlay')
	<!-- Page Content overlay -->

	<!-- Vendor JS -->
	<script src="{{ asset('assets/js/vendors.min.js') }}"></script>
	<script src="{{ asset('assets/js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/datatable/datatables.min.js') }}"></script>
	<script src="{{ asset('assets/vendor_components/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/sweetalert/jquery.sweet-alert.custom.js') }}"></script>

	<!-- Deposito Admin App -->
	<script src="{{ asset('assets/js/template.js') }}"></script>

	<script src="{{ asset('assets/js/pages/data-table.js') }}"></script>

	<script>
		function confirmDelete(id) {
			if (confirm('Are you sure you want to delete this product?')) {
				document.getElementById('delete-product-form-' + id).submit();
			}
		}

        $(document).ready(function() {
            $('#productApproval').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': false,
                'pageLength': 5,
                'lengthMenu': [            // Customize the options in the dropdown
                    [5, 10, 25, 50],       // Values for the number of entries
                    [5, 10, 25, 50]        // Labels to show in the dropdown
                ]
            });
        });

	</script>
@endsection
