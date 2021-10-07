<?php
    //error_reporting(E_ERROR | E_PARSE);
    require("_core.php");
    require("num2letras.php");
    require('fpdf/fpdf.php');
    class Reporte extends FPDF{
        private $encabezado;
        private $barra_lateral;
        private $cuerpo;
        private $datos_adicionales;

        function setEncabezado($encabezado){
            $this->encabezado=$encabezado;
        }
        function setBarraLateral($barra_lateral){
            $this->barra_lateral=$barra_lateral;
        }
        function setCuerpo($cuerpo){
            $this->cuerpo=$cuerpo;
        }
        function setDatosAdicionales($datos_adicionales){
            $this->datos_adicionales=$datos_adicionales;
        }

        function Header(){
            if($this->PageNo()==1){
                $set_x = $this->getX();
                $set_y = 2;
                $this->SetLineWidth(.5);
                $this->SetFillColor(255,255,255);
    
                $this->AddFont('latin','','latin.php');
                //$this->Image($this->infoext['logo'],$set_x,$set_y,190,45);
                $this->SetDrawColor(0,0,0);
                $this->SetFont('Courier', 'B', 19);
                $this->SetTextColor(25, 65, 96);
                $set_y +=0;
                $this->setY($set_y+5);
                //$this->Cell(160,7,utf8_decode($this->infoext['nombre_empresa']),0,1,'L');
                $set_y +=5;
                $this->setY($set_y+5);
                $this->SetFont('Courier', 'B', 16);
                $this->setX(45);
                $this->Cell(160,7,utf8_decode("Dr. ".$this->encabezado['doctor']),0,1,'L');
                //Especialidades
                $this->setY($set_y+12);
                $this->setX(10);
                $this->SetFont('Courier', 'B', 13);
                $this->Cell(115,7,"JVPM No 14349",0,1,'R');

                $this->setX(10);
                $this->setY($set_y+18);
                $this->SetFont('Courier', 'B', 12);
                $this->Cell(160,7,utf8_decode("ULTRASONOGRAFISTA - MEDICINA GENERAL ADULTOS Y NIÑOS"),0,1,'R');

                $this->setX(10);
                $this->setY($set_y+25);
                $this->SetFont('Courier', 'B', 12);
                $this->Cell(140,7,utf8_decode("GRADUADO DE LA UNIVERSIDAD DE EL SALVADOR"),0,1,'R');
    
                $this->SetDrawColor(25,65,96);
                $this->Line(13,39,203,39);
                $this->Line(23,42,193,42);
    
                $this->SetTextColor(0, 0, 0);
                $this->setY($set_y+36);
                $this->SetFont('Courier', 'B', 12);
                $this->Cell(160,7,Mayu(utf8_decode("PACIENTE : ".$this->encabezado['paciente'])),0,1,'L');
                $this->setY($this->getY()-7);
                $this->setX(150);
                $this->Cell(160,7,Mayu(utf8_decode("EDAD :".$this->encabezado['fecha_nacimiento']." AÑOS")),0,1,'L');
                $this->setY($set_y+43);
                $this->SetFont('Courier', 'B', 12);
                $this->Cell(160,7,(utf8_decode("FECHA : ".$this->encabezado['fecha_cita']." a las ".$this->encabezado['hora_cita'])),0,1,'L');
                $this->setY($this->getY()-7);
                $this->setX(150);
                $this->Cell(160,7,(utf8_decode("EXPEDIENTE :".str_pad($this->encabezado['expediente'], 6, '0', STR_PAD_LEFT))),0,1,'L');
       
                $especialidades=$this->barra_lateral['especialidades'];//obteniendo las especialidades
                $this->SetFont('Courier', 'B', 9);
                $y=60;
                $i=0;
                while($i<count($especialidades)){//recorriend todas las especialidades
                    $this->setXY(7,$y);
                    $this->Cell(35,4,(utf8_decode($especialidades[$i])),0,1,'L');
                    $i++;
                    $y+=4;
                }
                $y+=4;
                $this->setXY(7,$y);
                $this->Cell(35,5,(utf8_decode("ULTRASONOGRAFIA")),0,1,'L');
                //Obteniendo las examenes
                //obteniendo los examenes
                $examenes=$this->barra_lateral['examenes'];
                $y+=4;
                $i=0;
                while($i<count($examenes)){
                    $this->setXY(7, $y);
                    $this->Cell(35,4, utf8_decode($examenes[$i]),0,1,'L');
                    $i++;
                    $y+=4;
                }
                //
                $y+=4;
                $this->setXY(7,$y);
                $this->Cell(35,5,(utf8_decode("BIOPSIA PERCUTANEAS")),0,1,'L');
                $y+=4;

                $this->setXY(7, $y);
                $this->Cell(35, 5, (utf8_decode("GUADAS POR ")), 0, 1,'L');
                $y+=4;

                $this->setXY(7, $y);
                $this->Cell(35, 5, (utf8_decode("ULTRASONIDO")), 0, 1, 'L');
                $y+=4;
                //Obteniendo LOS ultrasonido
                $ultrasonido=$this->barra_lateral['ultrasonido'];
                
                $i=0;
                while($i<count($ultrasonido)){
                    $this->setXY(7, $y);
                    $this->Cell(35, 4, utf8_decode($ultrasonido[$i]), 0, 1, 'L');
                    $i++;
                    $y+=4;
                }
                //Aqui enpieza la parte de su proxima cita sera..
                $this->setXY(60, 215);
                $fecha_proxima_cita=$this->datos_adicionales['fecha_proxima_cita'];
                $this->Cell(35, 5, (utf8_decode("Su proxima cita sera: ".$fecha_proxima_cita)), 0, 1, 'L');
                $this->setXY(145, 215);
                $this->Cell(35, 5, (utf8_decode("F.________________________")), 0, 1, 'L');

            }

        }
        function Footer(){

        }  
    }

    date_default_timezone_set("America/El_Salvador");
    //definiendo los datos del encabezado
    $encabezado=array(
        'doctor'=>'Rosemberq Osaac Benitez Morales',
        'jvp'=>'JVPM N 14349',
        'paciente'=>'Jose Miguel Perez',
        'fecha_nacimiento'=>'25/05/1995',
        'fecha_cita'=>'25/05/2021',
        'hora_cita'=>'12:59 PM',
        'edad'=>'24',
        'expediente'=>'0045',

    );

    $datos_adicionales=array(
        'fecha_proxima_cita'=>'25/05/2021',
        
    );
    
    $especialidades=array(
        '* Neumonias',
        '* Sinusitis',
        '* Otitis',
        '* Calculos Renales',
        '* Infeccion de',
        '  vias urinarias',
        '* Dolor Cronico',
        '  de hombros',
        '  y de rodillas',
        '* Parasitismo',
        '* Diarreas',
        '* Osteoporosis',
        '* Hernias',
        '* Hipertencion',
        '  Arterial',
        '* Colitis',
        '* Diabetes',
        '  Mellitus',
        '* Infecciones',
        '  de la piel',
        '* Obesidad',
        '* Alcoholismo'   
    );

    $examenes=array(
        '* Abdominal',
        '* Abdominal para',
        '  Traumas(FAST)',
        '* Renal.',
        '* Hepatico y de',
        '  Vias billiares',
        '* Vesical',
        '* Pelvico Abdominal',
        '* Pelvico Transvaginal',
        '* Diagnostico de',
        '  Embarazo.',
        '* Mamas',
        '* Tiroides.',
        '* Cuello',
        '* Dinamica de Vesicula.',
        '  bilial.(disfucion',
        '  Vesicular)',
        '* Prostatica Transabdominal',
        '* Prostatica Transrectal'
    );

    $ultrasonido=array(
        '* Mamas'
    );

    $barra_lateral=array(
        'especialidades'=>$especialidades,
        'examenes'=>$examenes,
        'ultrasonido'=>$ultrasonido
    );


    $pdf = new Reporte('P','mm', 'Letter');
    $pdf->setEncabezado($encabezado);
    $pdf->setBarraLateral($barra_lateral);
    $pdf->setDatosAdicionales($datos_adicionales);
    $jdas="";
    $pdf->SetMargins(15,15);
    $pdf->SetTopMargin(10);
    $pdf->SetLeftMargin(13);
    $pdf->AliasNbPages();
    $pdf->SetAutoPageBreak(true,15);
    $pdf->AddFont('Georgia','','georgia.php');
    $pdf->AddFont('Arial','','calibri.php');
    $pdf->AddFont('Arial','B','calibrib.php');
    $pdf->AddFont('latin','','latin.php');
    $pdf->AddFont('GeorgiaI','','GeorgiaI.php');
    $pdf->AddFont('GeorgiaBI','','GeorgiaBI.php');
    $pdf->AddPage();
    
    /*ob_clean();*/
    $pdf->Output("receta_pdf.pdf","I");

?>