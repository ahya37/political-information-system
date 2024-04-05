<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;
use Spatie\PdfToImage\Pdf as Spatie;
use setasign\Fpdi\Fpdi;
use File;

class PdfToImage  
{

    public static function convert($request, $village){
		
		$chartImage = $request->input('chartimage');
		
		
        // Load the PDF template (you may need to create a blade file for this)
        $pdfTemplate = view('pages.report.siptpschart', compact('chartImage'))->render();
		
        // Create DomPDF instance  
        $dompdf = new Dompdf(); 
		
        // Load HTML content into DomPDF
        $dompdf->loadHtml($pdfTemplate);

        // (Optional) Set paper size and orientation
        $dompdf->setPaper('A4', 'landscape'); 
  
        // Render the PDF 
        $dompdf->render(); 
		$pdfContent = $dompdf->output();
		// dd($pdfContent);
		
		// return $pdfContent;
		
		// $dir_name = 'datachart/sip/desa/GRAFIK_PEROLEHAN_SUARA_DESA_'.$village->name.'_'.$village->id;
		// $directoryPdf = public_path('datachart/sip/desa/GRAFIK_PEROLEHAN_SUARA_DESA_'.$village->name.'_'.$village->id.'.pdf');
		$directory = public_path('/datachart/sip/' . $village->name);
		  
        if (File::exists($directory)) {
 
            File::deleteDirectory($directory); // hapus dir nya juga 
		}
		
		File::makeDirectory(public_path('/datachart/sip/' . $village->name));
		//buat baru Save the PDF file to the storage directory
		$fileName = 'GRAFIK_PEROLEHAN_SUARA_DESA_'.$village->name.'_'.$village->id.'.pdf';
		$pdfFilePath = public_path('/datachart/sip/' . $village->name . '/' . $fileName);
		File::put($pdfFilePath, $pdfContent); 
   
		
		//convert ke image 
		// jika file image ada, hapus dulu , ganti yg baru 
		// $directoryImage = public_path('datachart/sip/desa/GRAFIK_PEROLEHAN_SUARA_DESA_'.$village->name.'_'.$village->id.'.png');
		// if(File::exists($directoryImage)){
			// File::delete($directoryImage);
		// }
		
		// Convert the PDF to an image
		// $pdf = new Spatie($directoryPdf);
		// $pdf->setOutputFormat('png');
		// return $pdf->saveImage(public_path('datachart/sip/desa/GRAFIK_PEROLEHAN_SUARA_DESA_'.$village->name.'_'.$village->id.'.png'));
		
    }	 
	
}