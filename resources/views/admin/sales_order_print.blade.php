<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>

	<link rel="stylesheet" href="style.css">

</head>
<style type="text/css">
	body
	{
		padding: 5px;
	}
	table
	{
		width: 100%;
		margin: 5px 0px;
	}
	table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
	tr, td, th
	{
		border: 1px solid #000;
		color: #000;
		padding: 2px 2px;
		line-height: 15px;
		letter-spacing: 1px;
		font-size: 12px;
	}

	th, td p
	{
		letter-spacing: 1px;
		line-height: 15px;
		margin:0px auto;
	}
	h5 span
	{
		padding: 0px 10px;
		font-weight: 600;
	}

	 @font-face {
            font-family: cedarville-font-family;
            src: url("{{ asset('fonts/Cedarville-Cursive.ttf') }}");
            font-weight: normal;
        }
        @font-face {
            font-family: cedarville-font-family;
            src: url("{{ asset('fonts/Cedarville-Cursive.ttf') }}");
            font-weight: bold;
        }
</style>

<?php
$inr="<span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>";
?>
<body>


	<table>
	    	<tbody>

		    <tr>
		        <!--<td colspan="1" style="border-right:none;vertical-align: top; padding: 15px;"></td>-->
				<td colspan="5" style="text-align: right; border-left:none;vertical-align: top;">
				    <table style="border: none;">
				        <tr> <td style="border: none;width:60%">  <img src="{{asset('public/company_logo/'.$company->logo)}}" height="80px"></td>
				        <td style="border: none;border-left:none;vertical-align: top;padding-left: 18px">
					<h2 style="text-transform: uppercase; margin-bottom:3px;margin-top:8px;font-size: 15px;text-align: right;" >{{$company->company_name}}</h2>
					<p style="margin:0px;line-height:15px;font-size:12px;text-align: right;" >{{strip_tags($company->address)}} , <br>Mobile :- {{$company->mobile}} , <br>Email :- {{$company->email}}</p>
					<p style="margin:6px 0px;font-size:13px;font-weight: 800;margin-top:12px;text-align: right;"> <span>GSTIN :- {{$company->gst}}</span></p></td></tr>

				    </table>

				</td>
			</tr>
			<tr>
				<td colspan="5" style="text-align: center;"><h2>Sales Order</h2></td>
			</tr>
			<tr>
				<td colspan="2" style="border: none;vertical-align: top;border-right: none;">
				    <table style="border: none;">
				        <tr>
				            <td style="border: none;"><b>Bill To,<br><span style="font-size: 10px;margin-top: 5px">M/s. {{$data->customer_name}}</span></b><br>{{strip_tags($data->billing_address)}}<br>Mobile :- {{$data->primary_phone}}, Email :- {{$data->primary_email}}<br>GSTIN :- {{$data->owner_gst}}</td>
				        </tr>

            		</table>
				</td>

				<td colspan="3" style="vertical-align: top;font-size: 10px !important;border-left: none;text-align: right;">
					<table style="border: none;">
						<tr style="border: none;">
							<td style="border: none;font-weight: bold;text-align: right;">SALES ORDER NO. :</td>
							<td style="border: none;text-align: right;">{{$data->salaesorder_no}}</td>
						</tr>
						<tr style="border: none;">
							<td style="border: none; font-weight: bold;text-align: right;">QUOTATION NO. :</td>
							<td style="border: none;text-align: right;">{{$data->quotation_no}}</td>
						</tr>
						<tr style="border: none;">
							<td style="border: none;font-weight: bold;text-align: right;">QUOTATION DATE :</td>
							<td style="border: none;text-align: right;">{{date('d-m-Y',strtotime($data->quot_date))}}</td>
						</tr>


					</table>
				</td>
			</tr>

			<tr>
				<th style="text-align: center;vertical-align:top;">SR.</th>
				<th style="text-align: center;vertical-align:top;">Product Specification</th>


				<th style="text-align: center;vertical-align:top;">Qty</th>
				<th style="text-align: center;vertical-align:top;">Price</th>


				<th style="text-align: center;vertical-align:top;">Amount</th>
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
				<td style="text-align: center;vertical-align:top;width:4%">{{$srno}}</td>
				<td style="text-align: left;vertical-align:top;width:60%"><x-product-name :row="$item" print /><br><span style="font-size: 8px">{{$item->description}}</span></td>

				<td style="text-align: center;vertical-align:top;">{{$item->qty}}</td>
				<td style="text-align: right;vertical-align:top;">{{number_format($item->price,2,'.',',')}}</td>

				<td style="text-align: right;vertical-align:top;">{{number_format($item->total,2,'.',',')}}</td>
				<!-- <td style="text-align: right;vertical-align:top;">{{$item->grand_total}}</td> -->


				<?php
				$total=round($total+$item->total);
				$discount=round($discount+$item->discount_amount);
				$gsttotal=round($gsttotal+$item->gst_amount);
				$grand=round($grand+$item->grand_total);
				?>
			</tr>
			@endforeach

			<tr>
				<!-- <td colspan="6" style="text-align: center;letter-spacing: 1px;font-size: 13px"></td> -->
				<td colspan="4" style="text-align: right;"><b>SUB TOTAL (+)</b></td>
				<td colspan="1" style="text-align: right;"><b><?=number_format($total,2,'.',',');?></b></td>
			</tr>
			@if($discsum==0)
				@else
			<tr>
				<!-- <td colspan="4" style="text-align: center;letter-spacing: 1px;font-size: 13px"></td> -->
				<td colspan="4" style="text-align: right;"><b>DISCOUNT TOTAL (-)</b></td>
				<td colspan="1" style="text-align: right;"><b><?=number_format($discount,2,'.',',');?></b></td>
			</tr>
			@endif
			<tr>
				<!-- <td colspan="4" style="text-align: center;letter-spacing: 1px;font-size: 13px"></td> -->
				<td colspan="4" style="text-align: right;"><b>GST TOTAL (+)</b></td>
				<td colspan="1" style="text-align: right;"><b><?=number_format($gsttotal,2,'.',',');?></b></td>
			</tr>


			<tr>
				<td colspan="4" style="text-align: right;"><b>GRAND TOTAL (+)</b></td>
				<td colspan="1" style="text-align: right;"><b><?=number_format($grand,2,'.',',');?></b></td>
			</tr>


			<tr>
				<td colspan="5" style="text-align:left;">
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
				<td colspan="2" style='vertical-align: top;'>
					<table style="border: none;">
						<tbody>
						<tr style="border: none;">
							<th style="border: none;vertical-align: top;">Terms & Conditions :</th>
							 </tr>
						<tr style="border: none;">
							<td style="border: none;">
								<?php
								echo $data->term_condition;
								?>
						</td>
					</tr>
						</tbody>
					</table>
				</td>
				<td colspan="3" style='vertical-align: top;'>
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
		</tbody>
	</table>
</body>
</html>
