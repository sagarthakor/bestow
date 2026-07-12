<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>

	<link rel="stylesheet" href="style.css">

</head>
<style type="text/css">
	.maintable{

	}
    body
    {
        padding: 5px;
        color: #535b61;
        font-family: "Poppins", sans-serif;
        font-size: 14px;

    }
    table
    {
        width: 100%;

        margin: 5px 0px;
    }
    table, th, td {
        border: 1px solid rgba(0,0,0,.1);
        border-collapse: collapse;
    }
    tr, td, th
    {
        /*border: 1px solid #000;*/
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

    {{--@font-face {--}}
    {{--    font-family: cedarville-font-family;--}}
    {{--    src: url("{{ asset('fonts/Cedarville-Cursive.ttf') }}");--}}
    {{--    font-weight: normal;--}}
    {{--}--}}



</style>

<?php
$inr="<span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>";
?>
<body>

<?php
if($discsum==0)
{
	$totcol=6;
	$totdivide=3;
   $totdivide1=3;

}else{
$totcol=7;
$totdivide=4;
$totdivide1=3;
}
?>
	<table class="maintable">
	    	<tbody>
	    		  <tr>
		        <!--<td colspan="1" style="border-right:none;vertical-align: top; padding: 15px;"></td>-->
				<td colspan="<?=$totcol?>" style="text-align: right; border-left:none;vertical-align: top;">
				    <table style="border: none;">
				        <tr> <td style="border: none;width:38%">  <img src="{{asset('public/company_logo/'.$company->logo)}}" height="135px"></td>
				        <td style="border: none;border-left:none;vertical-align: top;padding-left: 18px">
					<h2 style="text-transform: uppercase; margin-bottom:3px;margin-top:8px;font-size: 15px;text-align: right;" >{{$company->company_name}}</h2>
					<p style="margin:0px;line-height:15px;font-size:12px;text-align: right;" >{{strip_tags($company->address)}} , <br>Mobile :- {{$company->mobile}} , <br>Email :- {{$company->email}}</p>
					<p style="margin:6px 0px;font-size:13px;font-weight: 800;margin-top:12px;text-align: right;"> <span>GSTIN :- {{$company->gst}}</span></p></td></tr>

				    </table>

				</td>
			</tr>
		   <tr>
				<td colspan="<?=$totcol?>" style="text-align: center;"><h2>QUOTATION</h2></td>
			</tr>
			<tr>
				<td colspan="<?=$totdivide1?>" style="border: none;vertical-align: top;border-right: none;">
				    <table style="border: none;">
				        <tr>
				            <td style="border: none;"><b>Bill To,<br><span style="font-size: 10px;margin-top: 5px">M/s. {{$quot->customer_name}}</span></b><br>{{strip_tags($quot->billing_address)}}<br>Mobile :- {{$quot->primary_phone}}, Email :- {{$quot->primary_email}}<br>GSTIN :- {{$quot->owner_gst}}</td>
				        </tr>

            		</table>
				</td>

				<td colspan="<?=$totdivide?>" style="vertical-align: top;font-size: 10px !important;border-left: none;text-align: right;">
				<table style="border: none;">
						<tr style="border: none;">
							<td style="border: none;font-weight: bold">Quotation No. </td>
							<td style="border: none;">: {{$quot->quotation_no}}</td>
						</tr>
						<tr style="border: none;">
							<td style="border: none;font-weight: bold">Quotation Date </td>
							<td style="border: none;">: {{date('d-m-Y',strtotime($quot->quot_date))}}</td>
						</tr>
						<tr style="border: none;">
							<td style="border: none;font-weight: bold">Quotation Valid </td>
							<td style="border: none;">: {{date('d-m-Y',strtotime($quot->valid_until))}}</td>
						</tr>
						<tr style="border: none;">
							<td style="border: none;font-weight: bold">Contact Name</td>
							<td style="border: none;">: {{$quot->contact_name}}</td>
						</tr>
						<tr style="border: none;">
							<td style="border: none;font-weight: bold">Contact Mobile</td>
							<td style="border: none;">: {{$quot->primary_phone}}</td>
						</tr>

					</table>
				</td>
			</tr>

			<tr>
				<th style="text-align: center;vertical-align:top;">SR.</th>
				<th style="text-align: center;vertical-align:top;">Product Specification</th>

				<th style="text-align: center;vertical-align:top;">Qty</th>
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
				<td style="text-align: left;vertical-align:top;width:30%">{{$item->product_name}}<br>
					<span style="font-size: 8px">{{$item->description}}</span>
				</td>

				<td style="text-align: center;vertical-align:top;">{{$item->qty}}</td>
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
                      <td colspan="<?=$totcol?>"><span style="font-size: 14px;font-weight: bold">Bank Details</span>
                          <table style="width: 100%;border: none !important;">
                              <tr><td style="border: none !important;">Bank Name </td>
                                  <td style="border: none !important;">: {{$company->bank_name}}</td>
                                  <td style="border: none !important;">Account Number</td>
                                  <td style="border: none !important;">: {{$company->account_no}}</td>
                              </tr>
                              <tr><td style="border: none !important;">Bank Branch </td><td style="border: none !important;">: {{$company->bank_branch}}</td><td style="border: none !important;">IFSC Code </td><td style="border: none !important;">: {{$company->ifsc_code}}</td></tr>

                              <tr>
                                  <td style="border: none !important;">MICR Code </td>
                                  <td style="border: none !important;">: {{$company->micr_code}}</td>
                                  <td style="border: none !important;"></td>
                              </tr>

                          </table>
                      </td>
                  </tr>
                  <tr>
				<td colspan="<?=$totdivide1;?>" style='vertical-align: top;'>
					<table style="border: none;height: auto;">
						<tbody>
						<tr style="border: none;">
							<th style="border: none;vertical-align: top;">Terms & Conditions :</th>
							 </tr>
						<tr style="border: none;">
							<td style="border: none;">
							{!! $quot->term_condition !!}
								<br><br>
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
                      <td colspan="<?=$totcol?>">Quote Prepared By : {{$quot->first_name}} {{$quot->last_name}}</td>
                  </tr>
		</tbody>
	</table>
</body>
</html>
