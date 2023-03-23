<?php

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;

require_once('fpdf/fpdf.php');
require_once('fpdi/src/autoload.php');


//$pdf =& new FPDI();
//$pdf->addPage('L');
//$pagecount = $pdf->setSourceFile('pdtest3.pdf');
//$tplIdx = $pdf->importPage(1); 
//$pdf->useTemplate($tplIdx); 
//$pdf->SetFont('Arial'); 
//$pdf->SetTextColor(255,0,0); 
//$pdf->SetXY(25, 25); 
//$pdf->Write(0, "This is just a test"); 
//$pdf->Output('newpdf.pdf', 'F');

//$pdf=new FPDF( 'P' , 'mm' , 'A4' );
$pdf = new Fpdi();
// add a page
$pdf->AddPage();
// set the source file
$pdf->setSourceFile('pdtest4.pdf');
// import page 1
$tplIdx = $pdf->importPage(1);
// use the imported page and place it at position 10,10 with a width of 100 mm
$pdf->useTemplate($tplIdx, 5, 5, 200);

// now write some text above the imported page
$pdf->Image('logo.png',120,260,50,20,'PNG','');

$pdf->Output('I', 'generated.pdf');

//    $rollno = 'test';
//    $firstname = 'test';
//    $lastname = 'test';
//    $email = 'test';
//
//    require("fpdf/fpdf.php");
//
//    $pdf = new FPDF();
//    $pdf->AddPage();
//
//    $pdf->SetFont('arial','',12);
//    $pdf->Cell(0,10,"Registration Details",1,1,'C');
//    $pdf->Cell(20,10,"Roll No",1,0);
//    $pdf->Cell(45,10,"First Name",1,0);
//    $pdf->Cell(45,10,"Last Name",1,0);
//    $pdf->Cell(0,10,"Email",1,1);
//
//    $pdf->Cell(20,10,$rollno,1,0);
//    $pdf->Cell(45,10,$firstname,1,0);
//    $pdf->Cell(45,10,$lastname,1,0);
//    $pdf->Cell(0,10,$email,1,0);
//
//    $pdf->Image('logo.png',20,60,180,20,'PNG','');
//
//    $pdf->output();
?>