@extends('backend.Layouts.app')

@section('title', 'Floor Overview')

@section('content')
<style>
 .bg-full {
        background-color: #FF5733;
    }
    .bg-partial {
        background-color:  #05a322;
    }

    .bg-empty {
        background-color: #808080;
    }

    .content-container{
        padding-top: 20px;
    }
    .zone-container {
        padding-top: 20px;
        padding-bottom: 80px;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .grid-container-zone1 {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 10px;
    }
    .grid-container-zone2 {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
    }

    .section-box {
        border-radius: 10px;
        width: 110px;
        padding-bottom: 50px; /* Maintain aspect ratio */
        position: relative;
    }


    .section-box a .box-dec {
         display: none;
    }

    .section-box a {
        position: absolute;
        margin-bottom: 5px;
        width: calc(100% - 10px); /* Adjust for gap */
        height: calc(100% - 10px); /* Adjust for gap */
         /* Yellow color */
        border-radius: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        text-decoration: none;
        color: #fff;
    }


    .section-box a .section-rack {
    position: absolute;
    //background-color: rgba(0, 135, 90, 0.9);
    border-radius: 10px;
    padding: 10px;
    -webkit-box-shadow: 0 5px 15px 4px rgba(0, 135, 90, 0.25);
    box-shadow: 0 5px 15px 4px rgba(0, 135, 90, 0.25);
    margin-top: 20px;
    margin-left: 20px;
    z-index: 99; }

  .section-box a .section-rack img {
    max-width: 100px;
    margin-right: 10px;
    padding: 10px;
    background-color: #0ca773;
    border-radius: 10px; }

    .section-box a:hover .box-dec {
    display: block; }

    .box-img {
        margin-right: 10px;
    }

    .box-img img {
        max-width: 100%;
        max-height: 100%;
    }

    .dec {
        flex: 1;
        text-align: center;
    }
</style>
<div class="content-container">
    <div class="container">
        <select id="rackSelector" class="form-select form-select-lg mb-3" aria-label="Default select example">
            <option value="zone1">Zone 1</option>
            <option value="zone2">Zone 2</option>

        </select>

        <div id="contentArea">
            <!-- Content will be dynamically updated here -->
            <!-- To show default first-->
            @include('backend.rack.zone1')
        </div>
    </div>

    <script>
        document.getElementById('rackSelector').addEventListener('change', function() {
        const selectedValue = this.value;
        const contentArea = document.getElementById('contentArea');

        // Clear existing content
        contentArea.innerHTML = '';

        // Add content based on the selected value
        if (selectedValue === 'zone1') {
            contentArea.innerHTML = `
            @include('backend.rack.zone1')
            `;
        } else if(selectedValue === 'zone2') {
            contentArea.innerHTML = `
            @include('backend.rack.zone2')
            `;
        } else {

        }
    });
    </script>
</div>


@endsection

@section('page content overlay')
    <!-- Page Content overlay -->


    <!-- Vendor JS -->
    <!-- Vendor JS -->
    <script src="{{ asset('assets/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/datatable/datatables.min.js') }}"></script>

    <!-- Deposito Admin App -->
    <script src="{{ asset('assets/js/template.js') }}"></script>

    <script src="{{ asset('assets/js/pages/data-table.js') }}"></script>


@endsection
