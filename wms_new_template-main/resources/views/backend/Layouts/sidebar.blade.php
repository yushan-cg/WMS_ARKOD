<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">
        <div class="multinav">
            <div class="multinav-scroll" style="height: 100%;">
                <!-- sidebar menu-->
                <ul class="sidebar-menu" data-widget="tree">
                    @if (Auth::user()->role == 1)
                        <li class="treeview">
                            <a href="#">
                                <i data-feather="package"></i>
                                <span>Product</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ URL::to('/list_product') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Product List</a>
                                </li>
                                <li><a href="{{ URL::to('/pickup_list') }}"><i class="icon-Commit"><span
                                    class="path1"></span><span class="path2"></span></i>Pickup List</a></li>
                                <li><a href="{{ URL::to('/check_new_product') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>New Product
                                        Request</a></li>
                                <li><a href="{{ URL::to('/review_request') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Restock
                                        Request</a></li>
                                <li><a href="{{ URL::to('/add_product') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Add Product</a>
                                </li>
                                <li><a href="{{ URL::to('/quantity_list') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Product Stock
                                        Level</a></li>
                                <li><a href="{{ URL::to('/weekly-report') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Weekly Report</a>
                                </li>
                                <li><a href="{{ URL::to('/qr_product') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>QR Code
                                        Scanner</a>
                                </li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i data-feather="user"></i>
                                <span>Picker</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ URL::to('/picker_status') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Picker Status</a>
                                </li>
                                <li><a href="{{ URL::to('/cart_index') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Assign Product</a>
                                </li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i data-feather="truck"></i>
                                <span>Order List</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ URL::to('/delivery_form_admin') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Delivery Order
                                        Form</a></li>

                                <li><a href="{{ URL::to('/delivery_order_list') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Delivery Order
                                        List</a></li>

                                <li><a href="{{ URL::to('/receive-return-stock') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Return Order</a>
                                </li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i data-feather="pie-chart"></i>
                                <span>Storage Location</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ URL::to('/racks') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Rack List</a></li>
                                <li><a href="{{ URL::to('/floors') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Floor List</a>
                                </li>
                                <li><a href="{{ URL::to('/qr_floors') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Floor QR</a>
                                </li>
                                <li><a href="{{ URL::to('/qr_racks') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Rack QR</a>
                                </li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i data-feather="database"></i>
                                <span>Database</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ URL::to('/company_list') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Company List</a>
                                </li>
                                <li><a href="{{ URL::to('/user_list') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>User List</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if (Auth::user()->role == 2)
                        @php
                            $sidebarController = new App\Http\Controllers\SidebarController();
                        @endphp
                        <li class="treeview">
                            <a href="#">
                                <i data-feather="monitor"></i>
                                <span>Task</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ URL::to('/picker_task') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>
                                        Picker Task <span class="right badge badge-danger"
                                            id="picker-tasks-count">{{ $sidebarController->getCountPickerTasks() }}</span></a>
                                </li>
                                <li><a href="{{ URL::to('/return-order-task') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>
                                        Return Order Task <span class="right badge badge-danger"
                                            id="picker-return-count">{{ $sidebarController->getCountPickerReturn() }}</span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i data-feather="package"></i>
                                <span>Stock</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ URL::to('/quantity_list') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Product Stock
                                        Level</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i data-feather="truck"></i>
                                <span>History</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ URL::to('/history') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>History</a></li>
                            </ul>
                        </li>
                    @endif
                    @if (Auth::user()->role == 3)
                        <li class="treeview">
                            <a href="#">
                                <i data-feather="package"></i>
                                <span>Product</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ URL::to('/customer_add_product') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Add New
                                        Product</a></li>
                                <li><a href="{{ URL::to('/mystatus_new_product') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Status of
                                        Approval</a></li>
                                <li><a href="{{ URL::to('/list_product') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Product List</a>
                                </li>
                                <li><a href="{{ URL::to('/request_restock_status') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Product Restock
                                        Status</a></li>
                                <li><a href="{{ URL::to('/my_stock_level') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Stock Level</a>
                                </li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i data-feather="book"></i>
                                <span>Monthly Report</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ URL::to('/report') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>View Monthly
                                        Report</a></li>
                                <li><a href="{{ URL::to('/report-create') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Download Monthly
                                        Report</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i data-feather="truck"></i>
                                <span>Delivery and Return</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ URL::to('/delivery_form') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Delivery
                                        Form</a></li>
                                    <li><a href="{{ URL::to('/pickup_form') }}"><i class="icon-Commit"><span
                                                    class="path1"></span><span class="path2"></span></i>Pickup
                                            Form</a></li>
                                <li><a href="{{ URL::to('/return-stock-form') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Return Order
                                        Form</a></li>
                                <li><a href="{{ URL::to('/return-stock-status') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Return Order
                                        Status</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i data-feather="database"></i>
                                <span>Database</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ URL::to('/detail_company') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Company
                                        Detail</a></li>
                                <li><a href="{{ URL::to('/add_company') }}"><i class="icon-Commit"><span
                                                class="path1"></span><span class="path2"></span></i>Add Company</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    <li class="treeview">
                        <a href="#">
                            <i data-feather="log-out"></i>
                            <span>Action</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="tables_simple.html"><i class="icon-Commit"><span class="path1"></span><span
                                            class="path2"></span></i>lockscreen</a></li>
                            <li><a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="icon-Commit"><span class="path1"></span><span
                                            class="path2"></span></i>Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                    <div class="sidebar-widgets">
                        <div class="copyright text-center m-25 text-white-50">
                            <p><strong class="d-block">Arkms1</strong> ©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> All Rights Reserved
                            </p>
                        </div>
                    </div>
            </div>
        </div>

    </section>
</aside>
