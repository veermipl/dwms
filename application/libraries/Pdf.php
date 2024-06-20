<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(dirname(__FILE__) . '/dompdf/autoload.inc.php');

class Pdf
{
    function createPDF($html, $filename='', $download=TRUE, $paper='A4', $orientation='portrait', $saveToFile = false){
        
        $dompdf = new Dompdf\Dompdf();
        $dompdf->load_html($html);
        $dompdf->set_paper($paper, $orientation);   
        $options = $dompdf->getOptions(); 
        $options->set(array('isRemoteEnabled' => true));
        $dompdf->setOptions($options);
        $dompdf->render();

        if ($saveToFile) {
            $output = $dompdf->output();
            file_put_contents($filename, $output);
        } else {
            if ($download) {
                $dompdf->stream($filename . '.pdf', array('Attachment' => 1));
            } else {
                $dompdf->stream($filename . '.pdf', array('Attachment' => 0));
            }
        }
    }
}
?>