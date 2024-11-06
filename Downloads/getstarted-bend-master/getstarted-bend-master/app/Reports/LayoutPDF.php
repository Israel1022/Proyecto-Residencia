<?php
namespace App\Reports;

use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Storage;
use DateTime;


class LayoutPDF extends Fpdf {

    private $data;
    protected $widths;
    protected $aligns;
    protected $extgstates = array();

    public function setData($data)
    {
        $this->data = $data;
    }

    function Header() {
        $meses = array('01' => 'ENERO', '02' => 'FEBRERO', '03' => 'MARZO', '04' => 'ABRIL', '05' => 'MAYO', '06' => 'JUNIO', '07' => 'JULIO', '08' => 'AGOSTO', '09' => 'SEPTIEMBRE', '10' => 'OCTUBRE', '11' => 'NOVIEMBRE', '12' => 'DICIEMBRE');

        $this->Image(storage_path("app/img/".$this->data['rptImage']['image']), $this->data['rptImage']['x'], $this->data['rptImage']['y'], $this->data['rptImage']['w'], $this->data['rptImage']['h']);

        $this->SetFont('Helvetica', 'B', 16);
        $this->Cell(0, 6, utf8_decode($this->data['empresa']->nombre), 0, 1, 'C');

        // $this->SetFont('Helvetica', '', 14);
        // $this->Cell(0, 6, utf8_decode('Departamento Informática'), 1, 1, 'C');

        $this->Ln(3);


        $this->SetFont('Helvetica', 'BI', 12);
        $this->Cell(0, 6, 'Solicitud de Servicio', 0, 0, 'C');

        $this->SetFillColor(200,200,200);
        $this->SetTextColor(255);
        $this->SetDrawColor(200,200,200);
        $this->SetFont('Helvetica','B',12);

        $this->RoundedRect(160, $this->GetY(), 50, 6, 2, 'FD');

        $this->SetFont('Helvetica', 'BI', 10);
        $this->Cell(0, 6, 'FOLIO : ' . $this->data['solicitud']->folio, 1, 1, 'R');

        $this->SetFillColor(0);
        $this->SetTextColor(0);

        // $this->Ln(-6);

        $created = new DateTime($this->data['solicitud']->created_at);
        $this->SetFont('Helvetica', 'I', 10);
        $this->Cell(0, 6, utf8_decode('MÉRIDA, YUC. A '). $created->format('d') . ' DE ' . $meses[$created->format('m')] . ' DEL ' . $created->format('Y') . ' (' . $created->format('H:i:s') . ') Hrs', 0, 1, 'C');

        $this->Ln(12);
    }

    function Footer() {
        $this->SetY(-40);
        $this->SetFont('Arial','B',10);
        $this->MultiCell(0,5,utf8_decode("Es responsabilidad del solicitante hacer el respaldo de sus archivos."),0);
        $this->MultiCell(0,5,utf8_decode("La DGT NO se hace responsable de la pérdida de información."),0);

        $this->SetFillColor(200,200,200);
        $this->SetLeftMargin(0);
        $this->SetRightMargin(0);
        $this->SetY(-25);
        $this->MultiCell(0,25,'',0,'J',true);


        $this->SetLeftMargin(5);
        $this->SetRightMargin(5);

        $this->SetTextColor(255);
        // Position at 2.5 cm from bottom
        $this->SetY(-25);
        $this->Ln(5);

        $this->SetFont('Arial','BI',10);
        $this->MultiCell(0,5,utf8_decode("Subsecretaría de Tecnologías de la Información y Comunicaciones."),0,'C',true);
        $this->SetFont('Arial','I',10);
        $this->MultiCell(0,5,utf8_decode("Edificio Administrativo Siglo XXI. Calle 20-A 284-B por 3-C Colonia Xcumpich, C.P. 97204 Mérida, Yucatán."),0,'C',true);


        // $this->SetFont('','',8);

        // // $this->SetX(0);
        $this->SetFont('Arial','BI',10);
        $this->Cell(100,5,utf8_decode('yucatan.gob.mx'),0,0,'C',true);
        $this->SetFont('Arial','I',10);
        $this->Cell(100,5,'Tel.: +52 (999) 400 0290, Ext 42000',0,1,'C',true);
        // // $this->SetX(0);
        // // $this->SetFont('Arial','I',10);
        // $this->SetLeftMargin(0);
        $this->SetRightMargin(0);
        // $this->SetX(0);
        $this->Cell(0,5,$this->PageNo().'/{nb}',0,1,'R',true);
    }

    //******************************************************************************************** */
    function RoundedRect($x, $y, $w, $h, $r, $style = '', $angle = '1234')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' or $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2f %.2f m', ($x+$r)*$k, ($hp-$y)*$k ));

        $xc = $x+$w-$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2f %.2f l', $xc*$k, ($hp-$y)*$k ));
        if (strpos($angle, '2')===false)
            $this->_out(sprintf('%.2f %.2f l', ($x+$w)*$k, ($hp-$y)*$k ));
        else
            $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);

        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2f %.2f l', ($x+$w)*$k, ($hp-$yc)*$k));
        if (strpos($angle, '3')===false)
            $this->_out(sprintf('%.2f %.2f l', ($x+$w)*$k, ($hp-($y+$h))*$k));
        else
            $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);

        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2f %.2f l', $xc*$k, ($hp-($y+$h))*$k));
        if (strpos($angle, '4')===false)
            $this->_out(sprintf('%.2f %.2f l', ($x)*$k, ($hp-($y+$h))*$k));
        else
            $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);

        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2f %.2f l', ($x)*$k, ($hp-$yc)*$k ));
        if (strpos($angle, '1')===false)
        {
            $this->_out(sprintf('%.2f %.2f l', ($x)*$k, ($hp-$y)*$k ));
            $this->_out(sprintf('%.2f %.2f l', ($x+$r)*$k, ($hp-$y)*$k ));
        }
        else
            $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }

    //++++++++++++++++++++++++++++++++
    function SetWidths($w)
    {
        // Set the array of column widths
        $this->widths = $w;
    }

    function SetAligns($a)
    {
        // Set the array of column alignments
        $this->aligns = $a;
    }

    function Row($data, $fill = false)
    {
        // Calculate the height of the row
        $nb = 0;
        for($i=0;$i<count($data);$i++)
            $nb = max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $h = 5*$nb;
        // Issue a page break first if needed
        $this->CheckPageBreak($h);
        // Draw the cells of the row
        for($i=0;$i<count($data);$i++)
        {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            // Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            // Draw the border
            if($fill)
                $this->SetFillColor(224,235,255);
            else
                $this->SetFillColor(255);

            $this->SetAlpha(0.5);
            $this->Rect($x,$y,$w,$h,'F');
            $this->SetAlpha(1);
            // Print the text
            $this->MultiCell($w,5,$data[$i],0,$a);
            // Put the position to the right of the cell
            $this->SetXY($x+$w,$y);
        }
        // Go to the next line
        $this->Ln($h);
    }

    function FancyRow($data, $border=array(), $align=array(), $style=array(), $maxline=array())
    {
        //Calculate the height of the row
        $nb = 0;
        for($i=0;$i<count($data);$i++) {
            $nb = max($nb, $this->NbLines($this->widths[$i],$data[$i]));
        }
        if (count($maxline)) {
            $_maxline = max($maxline);
            if ($nb > $_maxline) {
                $nb = $_maxline;
            }
        }
        $h = 5*$nb;
        //Issue a page break first if needed
        $this->CheckPageBreak($h);
        //Draw the cells of the row
        for($i=0;$i<count($data);$i++) {
            $w=$this->widths[$i];
            // alignment
            $a = isset($align[$i]) ? $align[$i] : 'L';
            // maxline
            $m = isset($maxline[$i]) ? $maxline[$i] : false;
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            //Draw the border
            if ($border[$i]==1) {
                $this->Rect($x,$y,$w,$h);
            } else {
                $_border = strtoupper($border[$i]);
                if (strstr($_border, 'L')!==false) {
                    $this->Line($x, $y, $x, $y+$h);
                }
                if (strstr($_border, 'R')!==false) {
                    $this->Line($x+$w, $y, $x+$w, $y+$h);
                }
                if (strstr($_border, 'T')!==false) {
                    $this->Line($x, $y, $x+$w, $y);
                }
                if (strstr($_border, 'B')!==false) {
                    $this->Line($x, $y+$h, $x+$w, $y+$h);
                }
            }
            // Setting Style
            if (isset($style[$i])) {
                $this->SetFont('', $style[$i]);
            }
            $this->MultiCell($w, 5, $data[$i], 0, $a, 0, $m);
            //Put the position to the right of the cell
            $this->SetXY($x+$w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        // If the height h would cause an overflow, add a new page immediately
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
    }

    function NbLines($w, $txt)
    {
        // Compute the number of lines a MultiCell of width w will take
        if(!isset($this->CurrentFont))
            $this->Error('No font has been set');
        $cw = $this->CurrentFont['cw'];
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
        $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
        $s = str_replace("\r",'',(string)$txt);
        $nb = strlen($s);
        if($nb>0 && $s[$nb-1]=="\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while($i<$nb)
        {
            $c = $s[$i];
            if($c=="\n")
            {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep = $i;
            $l += $cw[$c];
            if($l>$wmax)
            {
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                }
                else
                    $i = $sep+1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }

    /********************************************************************** */
    // alpha: real value from 0 (transparent) to 1 (opaque)
    // bm:    blend mode, one of the following:
    //          Normal, Multiply, Screen, Overlay, Darken, Lighten, ColorDodge, ColorBurn,
    //          HardLight, SoftLight, Difference, Exclusion, Hue, Saturation, Color, Luminosity
    function SetAlpha($alpha, $bm='Normal')
    {
        // set alpha for stroking (CA) and non-stroking (ca) operations
        $gs = $this->AddExtGState(array('ca'=>$alpha, 'CA'=>$alpha, 'BM'=>'/'.$bm));
        $this->SetExtGState($gs);
    }

    function AddExtGState($parms)
    {
        $n = count($this->extgstates)+1;
        $this->extgstates[$n]['parms'] = $parms;
        return $n;
    }

    function SetExtGState($gs)
    {
        $this->_out(sprintf('/GS%d gs', $gs));
    }

    function _enddoc()
    {
        if(!empty($this->extgstates) && $this->PDFVersion<'1.4')
            $this->PDFVersion='1.4';
        parent::_enddoc();
    }

    function _putextgstates()
    {
        for ($i = 1; $i <= count($this->extgstates); $i++)
        {
            $this->_newobj();
            $this->extgstates[$i]['n'] = $this->n;
            $this->_put('<</Type /ExtGState');
            $parms = $this->extgstates[$i]['parms'];
            $this->_put(sprintf('/ca %.3F', $parms['ca']));
            $this->_put(sprintf('/CA %.3F', $parms['CA']));
            $this->_put('/BM '.$parms['BM']);
            $this->_put('>>');
            $this->_put('endobj');
        }
    }

    function _putresourcedict()
    {
        parent::_putresourcedict();
        $this->_put('/ExtGState <<');
        foreach($this->extgstates as $k=>$extgstate)
            $this->_put('/GS'.$k.' '.$extgstate['n'].' 0 R');
        $this->_put('>>');
    }

    function _putresources()
    {
        $this->_putextgstates();
        parent::_putresources();
    }
  }
