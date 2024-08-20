<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waybill</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 16px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            border: 3px solid #000000;
        }
        p, td {
            margin: 0;
            padding-left: 20px;
        }
        .headerstyle, th {
            color: darkblue;
            font-weight: bold;
            font-size: 20px;
            padding-bottom: 5px;
            padding-top: 5px;
        }
        .waybill-banner {
            text-align: center;
        }
        .waybill-banner .logo {
            width: 597.5px;
            height: 70px;
        }
        .waybill-details {
            text-align: right;
        }
        .waybill-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .shipper-details table, .receiver-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .shipper-details td, .receiver-details td {
            border: 1px solid transparent;
            vertical-align: top;
            text-align: left;
            align-content: left;
            word-wrap: break-word;
        }
        .order-details {
            width: 100%;
            border-collapse: collapse;
        }
        .order-details td {
            padding: 8px;
            vertical-align: top;
            word-wrap: break-word;
        }
        .qr-code {
            text-align: center;
            vertical-align: middle;
        }
        .qr-code img {
            max-width: 100px;
            max-height: 100px;
            width: auto;
            height: auto;
            display: block;
            margin: auto;
        }
        .pod {
            margin-bottom: 30px;
        }
        .remark {
            text-align: center;
            padding-bottom: 20px;
        }
        .waybill-remark .logo {
            padding-top: 5px;
            width: 580px;
            height: 60px;
        }
        .waybill-details .table-container {
            display: inline-block;
            text-align: left;
        }
        .border-topbottom {
            border-top: 2px solid black;
            border-bottom: 2px solid black;
        }
        .border-right {
            border-right: 2px solid black;
        }


    </style>
</head>
<body>
    <div class="container">
        <div class="waybill-banner">
            <img src="{{ asset('assets/images/waybill-banner.png') }}" alt="ARKOD" class="img-fluid logo">
        </div>
        <div class="waybill-details">
            <div class="table-container">
                <table>
                    <tbody>
                        <tr>
                            <td>Waybill No:</td>
                            <td>{{ $waybill_no }}</td>
                        </tr>
                        <tr>
                            <td>Date:</td>
                            <td>{{ $date }}</td>
                        </tr>
                        <tr>
                            <td>Customer ID:</td>
                            <td>{{ $customer_id }}</td>
                        </tr>
                        <tr>
                            <td>Service Type:</td>
                            <td>{{ $service_type }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="shipper-details">
                <p class="headerstyle border-topbottom">Shipper Details</p>
            <table>
                <tbody>
                    <tr>
                        <td style="width: 60px;">Name:</td>
                        <td>{{ $shipper['name'] }}</td>
                    </tr>
                    <tr>
                        <td style="width: 60px;">Address:</td>
                        <td>{{ $shipper['address'] }}</td>
                    </tr>
                    <tr>
                        <td style="width: 60px;">Postcode:</td>
                        <td>{{ $shipper['postcode'] }}</td>
                    </tr>
                    <tr>
                        <td style="width: 60px;">Attention:</td>
                        <td>{{ $shipper['attention'] }}</td>
                    </tr>
                    <tr>
                        <td style="width: 60px;">Phone:</td>
                        <td>{{ $shipper['tel'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="receiver-details">
                <p class="headerstyle border-topbottom">Receiver Details</p>
            <table>
                <tbody>
                    <tr>
                        <td style="width: 60px;">Name:</td>
                        <td>{{ $receiver['name'] }}</td>
                    </tr>
                    <tr>
                        <td style="width: 60px;">Address:</td>
                        <td>{{ $receiver['address'] }}</td>
                    </tr>
                    <tr>
                        <td style="width: 60px;">Postcode:</td>
                        <td>{{ $receiver['postcode'] }}</td>
                    </tr>
                    <tr>
                        <td style="width: 60px;">Attention:</td>
                        <td>{{ $receiver['attention'] }}</td>
                    </tr>
                    <tr>
                        <td style="width: 60px;">Phone:</td>
                        <td>{{ $receiver['tel'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <table class="order-details">
            <tbody>
                <tr class="border-topbottom">
                    <td class="headerstyle border-right">Order Details</td>
                    <td class="headerstyle">QR Code</td>
                </tr>
                <tr>
                    <td class="border-right">
                        <table>
                            <tbody>
                                <tr>
                                    <td>Content:</td>
                                    <td>{{ $order['content'] }}</td>
                                </tr>
                                <tr >
                                    <td>Category:</td>
                                    <td >{{ $order['category'] }}</td>
                                </tr>
                                <tr >
                                    <td>Size:</td>
                                    <td>{{ $order['size'] }}</td>
                                </tr>
                                <tr>
                                    <td>Total Weight:</td>
                                    <td >{{ $order['total_weight'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="qr-code" rowspan="1">
                        <img src="{{ asset('assets/images/qr-track.png') }}" alt="QR Code">
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="pod">
            <p class="headerstyle border-topbottom">Proof of Delivery (POD)</p>
            <p>Name:</p>
            <p>I.C.:</p>
            <p>Signature:</p>
        </div>
            <p class="headerstyle" style="border-top: 2px solid black;">Remark:</p>
        <div class="remark">
            <div class="waybill-remark">
                <img src="{{ asset('assets/images/waybill-remark.png') }}" alt="Remark" class="img-fluid logo">
            </div>
            <p>Thank You for Your Support!</p>
            <p>Reach out to us at <a href="mailto:sales@arkod.com.my">sales@arkod.com.my</a> or call +6018-9116168 if you need additional assistance.</p>
        </div>
    </div>
</body>
</html>
