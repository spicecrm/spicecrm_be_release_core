<?php
namespace SpiceCRM\modules\OutputTemplates\handlers\pdf;

use Dompdf\Dompdf;

/**
 *  Attention: this class is not finished yet... nor tested...
 * wiki: https://github.com/dompdf/dompdf/wiki/Usage
 * Class TcpdfHandler
 */
class DomPdfHandler extends LibPdfHandler
{
    
    protected function createInstance()
    {
        return new Dompdf();
    }

    public function process($html = null, array $options = null)
    {
        return parent::process($html, $options);

        $this->createDomPdf($html, $options);
        return true;
    }

    private function createDomPdf($html, $options = null)
    {
        $options = (object) $options;
        
        $this->class_instance->loadHtml($html);
        $this->class_instance->setPaper($options->page_size, $options->page_orientation);
        $this->class_instance->render();
        $this->content = $this->class_instance->output();
        return $this->class_instance;
    }

    public function toDownload($file_name = null)
    {
        if(!$file_name)
            $file_name = $this->template->getFileName();

        if(!$this->content)
            $this->process();

        return $this->class_instance->stream();
    }

    public function toFile($destination_path, $file_name = null)
    {
        if(!$file_name)
            $filename = $this->id.'.pdf';

        if(!$this->content)
            $this->process();

        if(!file_put_contents("$destination_path/$filename", $this->content))
            throw new Exception("Could not save file to $destination_path/$filename!");

        return ['name' => $filename, 'path' => $destination_path, 'mime_type' => 'application/pdf'];
    }

}