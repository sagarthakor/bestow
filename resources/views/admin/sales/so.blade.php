<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style>
        * {
            box-sizing: border-box;
        }

        section{
            margin: 50px 0px;
        }

        /* Create three unequal columns that floats next to each other */
        .column {
            float: left;
            padding: 10px;
            height: 300px; /* Should be removed. Only for demonstration */
        }
        .middle .row{
            margin-bottom: 22px;

        }
        .right .row{
            margin:20px;
        }

        .left, .right, .middle {
            width: 33.33%;
        }
        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }
        .subject th{
            background-color: white;
            padding: 10px;
        }

        table, th, td {
            border: 1px solid black;
        }
        th{
            background-color: #ccc;
        }
        td{
            text-align: left;
        }
        .middle table{
            width: 72%;
        }
        .right table{
            width: 70%;
        }

    </style>
</head>
<body>
    <table>
        <tr>
            <td>

                    <center><h2>Quotation</h2></center>
            </td>
        </tr>
        <tr>
            <td style="border: none">
                <table style="border: none">
                    <tr>
                        <td colspan="1" style="vertical-align: top;">
                            <div class="img">
                                <img src="logo.png">
                            </div>

                            <div>
                                <h4>SYSTEM SOLUTION</h4>
                                <p>B-5/304, Unaddeep Complex,<br> SussenTarsali Ring Raod, <br>Opp. Essar Petrol
                                    Pump,<br>
                                    Vadodara ,Gujarat 390010
                                    India<br>
                                    Phone: +91 9099089475<br>
                                    Website: www.ssindia.co.in</p>
                            </div>
                        </td>
                        <td style="vertical-align: top;">
                            Billing Address<br>
                            3/2, Labdhi Industrial
                            Estate,Acid Mill Compound,
                            Ranmukteshwar Road,
                            Pratapnagar, Vadodara,
                            Gujarat 390004, India
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="border: none">
                <table>
                    <tr>
                    <td style="vertical-align: top;border: none">
                        <table>
                            <tr>
                                <th style="vertical-align: top;border: none">Customer Name</th>
                            </tr>
                            <tr>
                                <td style="text-align: center;vertical-align: top;border: none">Bansal Roofing Products<br> Limited</td>
                            </tr>
                        </table>

                    </td>
                        <td style="vertical-align: top;border: none">
                        <table>
                            <tr>
                                <th style="vertical-align: top;border: none">ContactName</th>
                            </tr>
                            <tr>
                                <td style="text-align: center;border: none">Dhara Panchal</td>
                            </tr>
                        </table>
                        </td>
                        <td style="vertical-align: top">

                        <table>
                            <tr>
                                <th style="border: none">Billing Address</th>
                            </tr>
                            <tr>
                                <td style="text-align: left;border: none">
                                    3/2, Labdhi Industrial
                                    Estate,Acid Mill Compound,
                                    Ranmukteshwar Road,
                                    Pratapnagar, Vadodara,
                                    Gujarat 390004, India
                                </td>
                            </tr>
                        </table>
                    </td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr>
            <td style="border: none">
                <table>
                    <tr>
                        <td style="vertical-align: top;border: none">
                            <table>
                                <tr>
                                    <th style="border: none">Quotes : SSINDIA_20210614</th>
                                </tr>
                                <tr>
                                    <td style="border: none">Issued Date: 2020-06-30</td>
                                </tr>
                                <tr>
                                    <td style="border: none">Valid Date: 2020-06-30</td>
                                </tr>
                            </table>
                        </td>
                            <td style="vertical-align: top;border: none">
                                <table>
                                    <tr>
                                        <th style="border: none">GST No & Bank Details</th>
                                    </tr>
                                    <tr>
                                        <td style="  text-align: left;border: none">GST No. : 24AADCB4379B1Z0 Bank Details :</td>
                                    </tr>
                                </table>
                            </td>

                    </tr>
                </table>

            </td>
        </tr>

        <tr>
            <td style="border: none">
                Sub: Quotation for Antivirus Escan Total Security for Business renewal
            </td>
        </tr>


        <tr>
            <td>
                <section>
                    <div class="row amount">
                        <table style="border-collapse: collapse;">
                            <tr>
                                <th>Sr. NO.</th>
                                <th>Text</th>
                                <th colspan="2">Quantity</th>
                                <th>Selling Price</th>
                                <th>Sub Total</th>
                                <th>Discount</th>
                                <th>Net Price without TAX</th>
                                <th>Tax(%)</th>
                                <th>Tax (INR)</th>
                                <th>Total</th>
                            </tr>

                            <tr>
                                <td>1</td>
                                <td><h4>Antivirus Escan Total Security for Business</h4>
                                    <p>Secure eScan Management Console | Set
                                        advanced security policies |Active
                                        Directory Synchronization (New) | Session
                                        Activity (New) | Real-Time Protection
                                        against Malware | Sophisticated File
                                        Blocking & Folder Protection | Web
                                        Protection | Asset Management | Advanced
                                        Protection against Ransomware Threats |
                                        Auto Back-up and Restore of Critical
                                        System files | File Activity Report | Print
                                        Activity Report | Outbreak Prevention
                                        Renewal</p>
                                </td>
                                <td>21.00</td>
                                <td>No</td>
                                <td>925.00</td>
                                <td>19425.00</td>
                                <td>0.00</td>
                                <td>19425.00</td>
                                <td>18.00</td>
                                <td>3496.50</td>
                                <td>22921.50</td>
                            </tr>

                            <tr>
                                <td></td>
                                <td>Subtotals</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>19425.00</td>
                                <td></td>
                                <td>3496.50</td>
                                <td>22921.50</td>
                            </tr>

                            <tr>
                                <td></td>
                                <td>Discount</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>0.00</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Shipping & Handling Charges</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>0.00</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Taxes For Shipping and Handling</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>0.00</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Adjustment</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>0.00</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><b>Grand Total (INR)</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>22921.50</td>
                            </tr>
                        </table>

                </section>
            </td>
        </tr>
    </table>








<section>
    <div class="row">
        <table style="width: 100%;">
            <tr>
                <th>Description</th>
            </tr>
            <tr>
                <td><p>- Considering both Baroda office and Factory users.</p>
                    <p>- Our GST No : 24AKDPB5558P1ZW</p>
                    <p>- Our Bank Details :</p>
                    <p>Company Name : SYSTEM SOLUTION<br>
                        Account Type : Current Account<br>
                        Bank Name : HDFC BANK<br>
                        Account Number : 50200019517742<br>
                        Bank Branch : Manjalpur Branch<br>
                        IFSC Code : HDFC0000275<br>
                        MICR Code 390240004</p></td>
            </tr>
        </table>

    </div>
</section>

<section>
    <div class="row">
        <table style="width: 100%;">
            <tr>
                <th>Terms & Conditions</th>
            </tr>
            <tr>
                <td><p>- Payment 100% Advance along with Official PO either by Cheque or NEFT in our account details mentioned in Description.</p>
                    <p>- GST Taxes are shown for each Individual Item as Applicable</p>
                </td>
            </tr>
        </table>

    </div>
</section>



</body>
</html>
