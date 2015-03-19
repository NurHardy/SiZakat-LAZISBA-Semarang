<?php
/**
 * HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */

    // get the HTML
    ob_start();
    include($_GET['content'].'.php');
    $content = ob_get_clean();

    // convert to PDF
    require_once('../libraries/pdf/html2pdf.class.php');
    try
    {
		//klo P : potrait, L : landscape
        $html2pdf = new HTML2PDF('L', 'A4', 'en', true, 'UTF-8', array(20, 15, 5, 15));
        //$html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output('detail_data_peserta_bus.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
	
	
?>
