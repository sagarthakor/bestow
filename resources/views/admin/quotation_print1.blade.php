

<?php
$inr="<span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>";
?>

<?php
if($discsum==0)
{
	$totcol=13;
	$totdivide=7;
   $totdivide1=6;

}else{
$totcol=14;
$totdivide=7;
$totdivide1=7;
}
?>
    <!DOCTYPE html>
<html>
<head>
    <title></title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
        }
        body{
            padding: 90px;
        }
        section{
            padding-top: 70px;
        }
        .bank td{
            text-align: left;
        }
        .quotation{
            margin-bottom: 70px;
        }
        .amount td{
            text-align: left;
            vertical-align: top;
            padding-top: 23px;
        }
        .total td{
            border: none;
            border-top: 1px solid black;
        }
        .column {
            float: left;
            padding: 10px;
            height: 300px;
        }
        .middle .row{
            margin-bottom: 8px;

        }
        .middle, .right{
            padding: 70px 30px 0px 0px;
        }


        .left, .right, .middle {
            width: 33.33%;

        }

        .row:after {
            content: "";
            display: table;
            clear: both;

        }
        .subject th{
            background-color: white;
            padding: 10px;
        }

        table,th,td{
            border: 1px solid black;
            border-collapse: collapse;
        }
        th{
            background-color: #ccc;
        }
        td{
            text-align: center;
            padding: 0px 11px;
        }
        .middle table{
            width: 97%;
        }
        .right table{
            width: 80%;
        }
        .heading h1{
            text-align: center;
            font-size: 35px;
            color: #3571b6;
        }
        .left-title p{
            text-align: justify;
            width: 66%;
            font-size: 14px;
        }
        .left-img img{
            max-width: 90%;
        }
        .heading {
            margin-top: 0px;
        }
        .subject table{
            width: 100%;
            text-align: left;
        }

    </style>
</head>
<body>


<section style="">
    <div class="heading">
        <h1>INVOICE</h1>
    </div>
    <section style="padding-top: 0px;">
        <div class="row">
            <!-- -----left column----- -->
            <div class="column left">
                <div class="left-img">
                    <img src="{{asset('public/company_logo/'.$company->logo)}}" style="height: 135px">
                </div>
                <div class="left-title">
                    <h4>{{$company->company_name}}</h4>
                    {!! $company->address !!}
                </div>
            </div>
            <!-- -----middle column----- -->

            <div class="column middle align="center">
            <div class="row">
                <table >
                    <tr>
                        <th>Company</th>
                    </tr>
                    <tr>
                        <td> {{$quot->customer_name}}</td>
                    </tr>
                </table>
            </div>

            <div class="row">
                <table>
                    <tr>
                        <th colspan="2">Billing Address</th>
                    </tr>
                    <tr>
                        <td style="text-align: left;">{{strip_tags($quot->billing_address)}}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><b>Phone :</b>{{$quot->primary_phone}}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><b>Email :</b> {{$quot->primary_email}}</td>
                    </tr>
                </table>
            </div>

            <div class="row">
                <table>
                    <tr>
                        <th colspan="2">Customer Contact</th>
                    </tr>
                    <tr>
                        <td>{{$quot->contact_name}}</td><td>{{$quot->primary_phone}}</td>
                    </tr>
                </table>
            </div>

            <div class="row">
                <table>
                    <tr>
                        <th>GST No</th>
                    </tr>
                    <tr>
                        <td>{{$quot->owner_gst}} </td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- ---right column -->
        <div class="column right">
            <div class="row quotation">
                <table>
                    <tr>
                        <th>Quotes : {{$quot->quotation_no}}</th>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><b>Issued Date</b><span style="    padding-left: 5px;">:</span> {{date('d-m-Y',strtotime($quot->quot_date))}}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><b>Valid Date</b><span style="    padding-left: 16px;">:<span> {{date('d-m-Y',strtotime($quot->valid_date))}}</td>
                    </tr>
                </table>
            </div>

            <div class="row bank">
                <table>
                    <tr>
                        <th>Bank Details</th>
                    </tr>
                    <tr>
                        <td><b>Bank Name</b><span style="padding-left: 14px;">:</span> {{$company->bank_name}}</td>
                    </tr>
                    <tr>
                        <td><b>Bank Branch</b><span style="padding-left: 5px;">:</span> {{$company->bank_branch}}</td>
                    </tr>
                    <tr>
                        <td><b>MICR Code</b><span style="padding-left: 17px;">:</span> {{$company->micr_code}}</td>
                    </tr>
                    <tr>
                        <td><b>Account No</b><span style="padding-left: 14px;">:</span> {{$company->account_no}}</td>
                    </tr>
                    <tr>
                        <td><b>IFSC Code</b><span style="padding-left: 22px;">:</span> {{$company->ifsc_code}}</td>
                    </tr>
                </table>
            </div>
        </div>
        </div>
    </section>

    <section>
        <div class="subject">
            <table>
                <tr>
                    <th>Sub: {{$quot->subject}}</th>
                </tr>
            </table>
        </div>
    </section>

	<table>
	    	<tbody>
			<tr>
				<th style="text-align: center;vertical-align:top;">SR.</th>
				<th style="text-align: center;vertical-align:top;">Product Specification</th>

				<th style="text-align: center;vertical-align:top;">ID</th>
				<th style="text-align: center;vertical-align:top;">OD</th>
				<th style="text-align: center;vertical-align:top;">THK</th>
				<th style="text-align: center;vertical-align:top;">Material</th>
                <th style="text-align: center;vertical-align:top;">HSN</th>

                <th style="text-align: center;vertical-align:top;">Image</th>

				<th style="text-align: center;vertical-align:top;">Qty</th>
                <th style="text-align: center;vertical-align:top;">UOM</th>
				<th style="text-align: center;vertical-align:top;">Price</th>

				@if($discsum==0)
				@else
				<th style="text-align: center;vertical-align:top;">Disc<br>(%)</th>
				@endif
				<th style="text-align: center;vertical-align:top;">Tax<br>(%)</th>

				<th style="text-align: center;vertical-align:top;">Sub Total</th>
				<!-- <th style="text-align: center;vertical-align:top;">Total</th> -->
			</tr>

			<?php
			$srno=$total=$gsttotal=$grand=$discount=0;
			?>
			@foreach($quotitem as $item)
			<?php
			$srno++;
			?>
			<tr>
				<td style="text-align: center;vertical-align:top;">{{$srno}}</td>
				<td style="text-align: left;vertical-align:top;width:30%"><x-product-name :row="$item" print />
                    <br><span style="font-size: 8px">{{$item->description}}</span>
                </td>
				<td style="text-align: center;vertical-align:top;">{{$item->inner_diameter}}</td>
				<td style="text-align: center;vertical-align:top;">{{$item->outer_diameter}}</td>
				<td style="text-align: center;vertical-align:top;">{{$item->thikness}}</td>
				<td style="text-align: center;vertical-align:top;">{{$item->material_name}}</td>
				<td style="text-align: center;vertical-align:top;">{{$item->hsn}}</td>

				<td style="text-align: center;vertical-align:top;">
					@if($item->catname=="AS PER DRAWING")
					<img src="{{asset('public/product_image/'.$item->product_image)}}" style="height: 65px" width="65px">
					@endif
					@if(empty($item->category_image))
					@else
					<img src="{{asset('public/product_category/'.$item->category_image)}}" style="height: 65px" width="65px">
					@endif
				</td>
				<td style="text-align: center;vertical-align:top;">{{$item->qty}}</td>
                <td style="text-align: center;vertical-align:top;">{{$item->uom_name}}</td>
				<td style="text-align: right;vertical-align:top;">{{number_format($item->price,2,'.',',')}}</td>

				@if($discsum==0)
				@else
				<td style="text-align: right;vertical-align:top;">{{$item->discount_per}}</td>
				@endif
				<td style="text-align: right;vertical-align:top;">{{$item->gst_per}}</td>
				<td style="text-align: right;vertical-align:top;">{{number_format($item->amount,2,'.',',')}}</td>
				<!-- <td style="text-align: right;vertical-align:top;">{{$item->grand_total}}</td> -->


				<?php
				$total=round($total+$item->amount);
				$discount=round($discount+$item->discount_amount);
				$gsttotal=round($gsttotal+$item->gst_amount);
				$grand=round($grand+$item->grand_total);
				?>
			</tr>
			@endforeach
{{--                      @if($srno==1)--}}
{{--                      @for($i=0;$i<=10;$i++)--}}
{{--                        <tr><td colspan="<?=$totcol?>"><br><br></td></tr>--}}
{{--                      @endfor--}}
{{--                      @endif--}}
			<tr>
				<!-- <td colspan="6" style="text-align: center;letter-spacing: 1px;font-size: 13px"></td> -->
				<td colspan="<?=$totcol-1;?>" style="text-align: right;"><b>SUB TOTAL (+)</b></td>
				<td colspan="1" style="text-align: right;"><b><?=number_format($total,2,'.',',');?></b></td>
			</tr>
			@if($discsum==0)
				@else
			<tr>
				<!-- <td colspan="4" style="text-align: center;letter-spacing: 1px;font-size: 13px"></td> -->
				<td colspan="<?=$totcol-1;?>" style="text-align: right;"><b>DISCOUNT TOTAL (-)</b></td>
				<td colspan="1" style="text-align: right;"><b><?=number_format($discount,2,'.',',');?></b></td>
			</tr>
			@endif
			<tr>
				<!-- <td colspan="4" style="text-align: center;letter-spacing: 1px;font-size: 13px"></td> -->
				<td colspan="<?=$totcol-1;?>" style="text-align: right;"><b>GST TOTAL (+)</b></td>
				<td colspan="1" style="text-align: right;"><b><?=number_format($gsttotal,2,'.',',');?></b></td>
			</tr>


			<tr>
				<td colspan="<?=$totcol-1;?>" style="text-align: right;"><b>GRAND TOTAL (+)</b></td>
				<td colspan="1" style="text-align: right;"><b><?=number_format($grand,2,'.',',');?></b></td>
			</tr>


			<tr>
				<td colspan="<?=$totcol;?>" style="text-align:left;">
				<?php
					  $no = round($grand);
    $decimal = round($grand - ($no = floor($grand)), 2) * 100;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety');
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? '' : null;
            $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
        } else {
            $str [] = null;
        }
    }

    $Rupees = implode(' ', array_reverse($str));
    $paise = ($decimal) ? "And Paise " . ($words[$decimal - $decimal%10]) ." " .($words[$decimal%10])  : '';
    echo ($Rupees ? '<span style="font-weight:800">In Words :- Rs. ' . $Rupees : '') . $paise . " Only.</span>";



					?>
				</td>
			</tr>
                  <tr>
                      <td colspan="<?=$totcol?>">
                          <table style="width: 100%;border: none !important;">
                              <tr> <td colspan="2" style="font-size: 18px !important;text-align: left !important;margin-bottom: 6px !important;margin-top: 6px !important;border: none !important;">Bank Details</td></tr>
                              <tr></tr>
                              <tr><td style="border: none !important;">Bank Name </td><td style="border: none !important;">: {{$company->bank_name}}</td><td style="border: none !important;">Account Number</td><td style="border: none !important;">: {{$company->account_no}}</td></tr>
                              <tr><td style="border: none !important;">Bank Branch </td><td style="border: none !important;">: {{$company->bank_branch}}</td><td style="border: none !important;">IFSC Code </td><td style="border: none !important;">: {{$company->ifsc_code}}</td></tr>

                              <tr><td style="border: none !important;">MICR Code </td><td style="border: none !important;">: {{$company->micr_code}}</td></tr>
                          </table>

                      </td>
                  </tr>
			<tr>
				<td colspan="<?=$totdivide1;?>" style='vertical-align: top;'>
					<table style="border: none;">
						<tbody>
						<tr style="border: none;">
							<th style="border: none;vertical-align: top;">Terms & Conditions :</th>
							 </tr>
						<tr style="border: none;">
							<td style="border: none;">
								<?php
								echo $quot->term_condition;
								?>
						</td>
					</tr>
						</tbody>
					</table>
				</td>
				<td colspan="<?=$totdivide;?>" style='vertical-align: top;'>
					<table style="border: none;">
						<tbody>
						<tr style="border: none;vertical-align: top"><th style="border: none; text-align: right;font-size: 12px;vertical-align: top;font-weight:1000">FOR {{$company->company_name}}</th> </tr>
						<tr style="border: none;"><td style="border: none; color: #fff">0</td> </tr>
						<tr style="border: none;"><td style="border: none; color: #fff">0</td></tr>
						<tr style="border: none;"><td style="border: none; color: #fff">0</td></tr>
						<tr style="border: none;"><th style="border: none; text-align: right;font-size: 12px">AUTHORISED SIGNATORY</th></tr>
						</tbody>
					</table>
				</td>
			</tr>

            <tr>
                <td colspan="<?=$totcol?>">
                    Quote Prepared By : {{$quot->first_name}} {{$quot->last_name}}
                </td>
            </tr>
		</tbody>
	</table>
</section>>

</body>
</html>
