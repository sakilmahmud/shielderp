<?php defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf
{

    private $dompdf;

    public function __construct()
    {
        require_once APPPATH . '../vendor/autoload.php';

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);
        $this->dompdf = new Dompdf($options);
    }

    public function loadHtml($html)
    {
        $this->dompdf->loadHtml($html);
    }

    public function setPaper($size, $orientation = 'portrait')
    {
        $this->dompdf->setPaper($size, $orientation);
    }

    public function render()
    {
        $this->dompdf->render();
    }

    public function stream($filename = "document.pdf", $options = array())
    {
        $this->dompdf->stream($filename, $options);
    }
}
