<?php
    ob_start();
    include('Informes/Contabilidad/Reportes/ExtractoFondo.php');
    $content = ob_get_clean();

    // convert in PDF
    require_once('html2pdf/html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'fr');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output('Archivo.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
