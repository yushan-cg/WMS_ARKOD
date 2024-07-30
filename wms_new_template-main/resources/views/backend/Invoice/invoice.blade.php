<title>Invoice</title>

<style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    font-size: 14px;
    }

    p   {
        margin: 4px;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 0 auto;
        //border: 3px solid #000000;
        overflow: auto; /* Clear floats */
    }

    .invoice-banner {
        text-align: center
    }

    .invoice-banner .logo {
        width: 597.5px;
        height:70px;
    }

    .header-right {
        padding: 0;
        float: right;
        width: fit-content;
        width: 41%;
       // border: 3px solid #000000;

    }

    .header-left {
        float: left;
        width: 30%;
    }

    /* Add clearfix */
    .clearfix::after {
        content: "";
        clear: both;
        display: table;
    }

    .customer-details{
        width: 45%;
    }

    /////table
    /* styles.css */

table {
    width: 100%;
    border-collapse: collapse;
    //table-layout: auto; /* Ensures table adjusts based on content */

}

tr {
    height: auto;
}

th, td {
    font-weight: regular;
    height: 16px;
    padding: 1px;
    text-align: left;
    //border: 1px solid #ccc;
}

th {
    background-color: #3fc1e2;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
    color: black;
}

/* Alternate row colors for table body (tbody) */
tr:nth-child(even) td {
    background-color: #ffffff;
}

tr:nth-child(odd) td {
    background-color:  #c4f3ff;
}

/* Adjust column widths as needed */
th:nth-child(1),
td:nth-child(1) {
    width: 80px;
    //width: 11.2%;
}

th:nth-child(2),
td:nth-child(2) {
    width: 288px;
    //width: 47.6%;
}

th:nth-child(3),
td:nth-child(3) {
    width: 115px;
    //width: 22.9%;
}

th:nth-child(4),
td:nth-child(4) {
    width: 92px;
    //width: 18.3%;
}

.payment{
    text-align: center;
}

.payment-qr img {
            max-width: 310px; /* Set maximum width */
            max-height: 310px; /* Set maximum height */
            width: auto; /* Allow the image to adjust its width while maintaining aspect ratio */
            height: auto; /* Allow the image to adjust its height while maintaining aspect ratio */
            display: block; /* Ensure the image is centered within its container */
            margin: auto; /* Center the image horizontally */
        }

</style>

<body>
    <div class="container">
        <div class="invoice-banner">
            <img src="{{ asset('assets/images/invoice-banner.png') }}" alt="ARKOD" class="img-fluid logo">
        </div>

        <header>
            <h3>ARKOD SMART LOGITECH SDN BHD (1396015-V)</h3>

            <div class="header-left">
                <p>www.arkod.com.my</p>
                <p>Kuching, Sarawak , 93450</p>
                <p>[+6018-911 6168]</p>
                <p><a href="mailto:sales@arkod.com.my">sales@arkod.com.my</a></p>
            </div>
            <div class="header-right">
                <p>Invoice No.: {{ $invoice_no }}</p>
                <p>Date: {{ $date }}</p>
                <p>Customer ID: {{ $customer_id }}</p>
                <p>Payment Method: {{ $payment_method }}</p>
            </div>
            <div class="clearfix"></div> <!-- Add clearfix here -->
        </header>

        <div class="customer-details">
            <div style="padding-top: 18px; padding-bottom: 18px">
                <p><strong>{{ $name }}</strong></p>
                <p><strong>Attn: {{ $attention }}</strong></p>
            </div>

            <div>
                <p>Address: {{ $address }}</p>
                <p>Tel. no.: {{ $tel }}</p>
            </div>
        </div>

        <div  style="margin-right: auto;">
            <table style="padding-bottom: 20px;">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Payment Terms</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>{{ $payment_terms }}</td>
                        <td>{{ $due_date }}</td>
                    </tr>
                <!-- Rows will be generated dynamically using server-side code-->
                <?php
                // Example PHP code to fetch data from the database and create rows
                /*$rows = fetchDataFromDatabase(); // Replace with your actual data retrieval logic

                foreach ($rows as $row) {
                    echo "<tr>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td>{$row['payment_terms']}</td>";
                    echo "<td>{$row['due_date']}</td>";
                    echo "</tr>";
                }*/
                ?>
                </tbody>
            </table>
        </div>


        <table style="padding-bottom: 20px;">
            <thead>
                <tr>
                    <th>Quantity</th>
                    <th>Description</th>
                    <th>Unit Price RM</th>
                    <th>Total RM</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rows will be generated dynamically using server-side code-->
                <?php
                // Example dynamic row generation (replace with actual dynamic PHP logic)
                foreach ($items as $item) {
                    echo "<tr>";
                    echo "<td>{$item['quantity']}</td>";
                    echo "<td>{$item['description']}</td>";
                    echo "<td>{$item['unit_price']}</td>";
                    echo "<td>{$item['total_price']}</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
        </table>

        <div class="payment-banner" rowspan="4">
        </div>

        <table style="border-top: 3px solid #66e0ff;">
            <tbody>
                <tr>
                    <td class="payment-qr" rowspan="4" style="width: 374px; text-align: center; background-color: #ffffff;">
                        <img src="{{ asset('assets/images/payment-banner.png') }}" alt="ARKOD" class="img-fluid logo">
                    </td>
                    <th style="width: 115px;">Subtotal</th>
                    <td style="width: 92px;">RM {{ $subtotal }}</td>
                </tr>
                <tr>
                    <th>SST@ {{ $sstPercentage }}%</th>
                    <td style="width: 92px;">RM {{ $sst }}</td>
                </tr>
                <tr>
                    <th><strong>TOTAL</strong></th>
                    <td style="width: 92px;">RM {{ $final_price }}</td>
                </tr>
            </tbody>
        </table>

        <div class="payment" >
            <p>Make all cheques payable to ARKOD SMART LOGITECH SDN BHD (1396015-V)</p>
            <p style="color: rgb(98, 98, 251); font-size: 18px;">Public Bank Acc. No. 3223583706</p>
        </div>


    </div>
</body>
