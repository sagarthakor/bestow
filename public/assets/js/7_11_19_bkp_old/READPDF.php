<?php
	require('acess_admin/include/PdfToText.phpclass') ;

	function  output ( $message )
	   {
		if  ( php_sapi_name ( )  ==  'cli' )
			echo ( $message ) ;
		else
			echo ( nl2br ( $message ) ) ;
	    }

	$file	=  '/opt/lampp/htdocs/dwarkesh_web/acess_admin/assets/images/resume/SUJIT-Resume2' ;
	echo $file;
	$pdf	=  new PdfToText ( "$file.pdf",PdfToText::PDFOPT_CAPTURE) ;
	output ( "Original file contents :\n" ) ;
	output ( file_get_contents ( "$file.txt" ) ) ;
	output ( "-----------------------------------------------------------\n" ) ;

	output ( "Extracted file contents :\n" ) ;
	output ( $pdf -> Text ) ;

?>