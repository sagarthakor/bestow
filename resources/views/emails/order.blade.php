
<!DOCTYPE html>
<html>

<head>
    <title>Order Confirmation</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style type="text/css">
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        @media screen and (max-width: 480px) {
            .mobile-hide {
                display: none !important;
            }

            .mobile-center {
                text-align: center !important;
            }
        }

        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }

        body {
            background-color: #E1E1E1;
            color: #848484;
            background-image: url({{asset('public/bkg-1.jpg')}});
            position: relative;
            background-attachment: fixed;
        }
    </style>

<body style="margin: 0 !important; padding: 0 !important; background-color: #eeeeee;" bgcolor="#eeeeee">
<div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Open Sans, Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
    For what reason would it be advisable for me to think about business content? That might be little bit risky to have crew member like them.
</div>
{{Form::open(['method'=>'get'])}}
{{Form::hidden('quot_no',$order->order_no)}}
<table border="0"  cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center" >
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:100%;border: 2px solid #ddd !important;">
                <tr>
                    <td align="center" valign="top" style="font-size:0; padding: 35px;" bgcolor="#ddd">
                        <div style="display:inline-block; max-width:50%; min-width:100px; vertical-align:top; width:100%;">
                            <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:300px;">
                                <tr>
                                    <td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 36px; font-weight: 800; line-height: 48px;" class="mobile-center">
                                        <h1 style="font-size: 36px; font-weight: 800; margin: 0; color: #ffffff;">
{{--                                            LOGO<img src="{{asset('public/company_logo/'.$logo)}}"></h1>--}}
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div style="display:inline-block; max-width:50%; min-width:100px; vertical-align:top; width:100%;" class="mobile-hide">
                            <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:300px;">
                                <tr>
                                    <td align="right" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; line-height: 48px;">
                                        <table cellspacing="0" cellpadding="0" border="0" align="right">
                                            <tr>
                                                <td style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400;">
                                                    <p style="font-size: 18px; font-weight: 400; margin: 0; color: #ffffff;">
                                                        <a href="#" target="_blank" style="color: #000; text-decoration: none;">Order Confirmation &nbsp;</a></p>
                                                </td>
                                                <td style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 24px;">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="background-color: #ffffff;" bgcolor="#ffffff">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:100%">
                            <tr>
                                <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 0px;"> <img src="https://img.icons8.com/carbon-copy/100/000000/checked-checkbox.png" width="125" height="80" style="display: block; border: 0px;" /><br>
                                    <h2 style="font-size: 30px; font-weigt: 800; line-height: 36px; color: #333333; margin: 0;"> Thank You For Your Order Request ! </h2>
                                </td>
                            </tr>

                            <tr>
                                <td align="left" style="padding-top: 20px;">
                                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                        <tr>
                                            <td width="75%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;"> Order No. # </td>
                                            <td width="25%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;">
                                                {{$order->order_number ?? ""}} </td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>


                            <tr>
                                <td align="left" style="padding-top: 20px;">
                                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                        <tr>
                                            <th>Image</th>
                                            <th>Product</th>
                                            <th>Custom Description</th>
                                            <th>Qty</th>
                                            <th>Price</th>

                                        </tr>
                                        <?php

                                        $totamt=0;
                                        ?>
                                        @foreach($order_item as $qi)
                                            <?php
                                            $totamt=$totamt+$qi->amount;
                                            $pathToImage = public_path()."/product_image/".$qi->product_image;
                                            ?>
                                            <tr>
                                                <td width="10%" align="center" style="vertical-align:top;font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 12px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
                                                <a href="{{url('product-details/'.$qi->category_name.'/'.$qi->subcategory_name.'/'.$qi->product_name)}}"><img class="img img-responsive" style="vertical-align:top;height:80px !important;" src="<?php echo $message->embed($pathToImage); ?>"></a>
                                                </td>
                                                <td width="20%" align="left" style="vertical-align:top;font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 12px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
                                                    <a href="{{url('product-details/'.$qi->category_name.'/'.$qi->subcategory_name.'/'.$qi->product_name)}}">  {{$qi->product_name}}</a>
                                                </td>
                                                <td width="" align="left" style="vertical-align:top;font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 12px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
                                                    {{$qi->custom_description}}
                                                </td>
                                                <td width="" align="center" style="vertical-align:top;font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 12px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
                                                    {{$qi->qty}}
                                                </td>
                                                <td width="" align="center" style="vertical-align:top;font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 12px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
                                                    {{$qi->price}}
                                                </td>

                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4" style="vertical-align:top;font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 12px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;text-align: right">Item Total</td>
                                            <td style="vertical-align:top;font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 12px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;text-align: center">
                                                {{$order->net_amount}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" style="vertical-align:top;font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 12px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;text-align: right">Tax</td>
                                            <td style="vertical-align:top;font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 12px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;text-align: center">
                                                {{round($order->cgsttotal+$order->sgsttotal+$order->gst_amount)}}</td>
                                        </tr>
                                        @if($order->adjustment > 0)
                                        <tr>
                                            <td colspan="4" style="vertical-align:top;font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 12px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;text-align: right">Adjustment</td>
                                            <td style="vertical-align:top;font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 12px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;text-align: center">
                                                {{$order->adjustment}}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td colspan="4" style="vertical-align:top;font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 12px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;text-align: right">Grand Total</td>
                                            <td style="vertical-align:top;font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 12px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;text-align: center">
                                                {{round($order->grand_total)}}</td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>


            </table>
        </td>
    </tr>
</table>
{{Form::close()}}
</body>

</html>
