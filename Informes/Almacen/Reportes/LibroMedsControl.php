<?
    if($DatNameSID){session_name("$DatNameSID");}
    session_start();
    include("Informes.php");
    require('LibPDF/fpdf.php');
    include("Consumo/ObtenerSaldos.php");
    //echo "$Anio...$MesIni...$DiaIni...$MesFin...$DiaFin";
    $FechaIni="$Anio-$MesIni-$DiaIni";
    $dia = strtotime($FechaIni)-(1*24*60*60); //Te resta un dia (2*24*60*60) te resta dos y //asi...
    $dia_Ini = date('Y-m-d', $dia); //Formatea dia
    //echo "$FechaIni ----- $dia_fin";
    $FechaFin="$Anio-$MesFin-$DiaFin";
    $ND=getdate();
    $VrSaldoIni=SaldosIniciales($Anio,$AlmacenPpal,"$Anio-01-01");
    $VrEntradas=Entradas($Anio,$AlmacenPpal,"$Anio-01-01",$dia_Ini);
    $VrSalidas=Salidas($Anio,$AlmacenPpal,"$Anio-01-01",$dia_Ini);
    $VrDevoluciones=Devoluciones($Anio,$AlmacenPpal,"$Anio-01-01",$dia_Ini);
    if($Medicamento){$AdCons="and Movimiento.AutoId=$Medicamento";}
    $cons = "Select Movimiento.Autoid,Fecha,TipoComprobante,
    Cedula,Cantidad,NombreProd1,UnidadMedida,
    Presentacion,TipoComprobante,NoFactura,
    PrimApe,SegApe,PrimNom,SegNom
    from Consumo.Movimiento,Consumo.CodProductos,Central.Terceros 
    Where Movimiento.Compania='$Compania[0]' and Movimiento.AlmacenPpal='$AlmacenPpal'
    and CodProductos.Compania='$Compania[0]' and CodProductos.AlmacenPpal='$AlmacenPpal' 
    and CodProductos.Anio=$Anio and Movimiento.AutoId = CodProductos.Autoid and 
    TipoComprobante != 'Orden de Compra' and Terceros.Compania = '$Compania[0]'
    and Cedula = Identificacion and fecha >= '$FechaIni' 
    and fecha <='$FechaFin' and Control='Si' 
    $AdCons and Movimiento.Estado='AC'
    order by NombreProd1,Fecha,Comprobante";
    $res = ExQuery($cons);
    while($fila = ExFetch($res))
    {
        if($fila[2]=="Entradas"){$Fac = " No Factura: $fila[9]";}else{$Fac = "";}
        //{
            $fila[3] = "($fila[3])$fila[10] $fila[11] $fila[12] $fila[13]$Fac";
        //}
        $NombreMedicamento[$fila[0]]="$fila[5] $fila[6] $fila[7]";
        if(!$Movimiento[$fila[0]][$fila[1]][$fila[2]][$fila[3]]){$Movimiento[$fila[0]][$fila[1]][$fila[2]][$fila[3]]=$fila[4];}
        else{$Movimiento[$fila[0]][$fila[1]][$fila[2]][$fila[3]]=$Movimiento[$fila[0]][$fila[1]][$fila[2]][$fila[3]]+$fila[4];}
    }
    if(!$PDF)
    {
        echo "<body background=\"/Imgs/Fondo.jpg\">";
        if($Movimiento)
        {
            $Enc = "<tr bgcolor=\"#e5e5e5\" style=\"font-weight: bold\" align=\"center\">
                    <td width='60px'>Fecha</td><td>Movimiento</td><td>Formula</td>
                    <td>Saldo<br>Anterior</td><td>Entrada</td><td>Salida</td><td>Saldo<br>Siguiente</td>
                </tr>";
            ?>
            <div align="right" style=" font: normal small-caps 11px Tahoma"><? echo "Periodo de: $FechaIni a $FechaFin";?></div>
            <table border="1" bordercolor="#e5e5e5" width="100%"  style='font : normal normal small-caps 11px Tahoma;'><?
            while(list($AutoId,$Movimiento1)=each($Movimiento))
            {
                //echo "list Autoid<br>";
                $CantPeriodo=$VrSaldoIni[$AutoId][0]+$VrEntradas[$AutoId][0]+$VrDevoluciones[$AutoId][0]-$VrSalidas[$AutoId][0];
                ?><tr bgcolor="<? echo $Estilo[1]?>" style="color: white; font-weight: bold">
                    <td colspan="7" align="center"><? echo strtoupper(utf8_decode($NombreMedicamento[$AutoId]))?></td>
                </tr>
                <?
                unset($SI,$TotEntradas,$TotSalidas);
                while(list($Fecha,$Movimiento2)=each($Movimiento1))
                {
                    //echo "list Fecha<br>";
                    while(list($TipoComprobante,$Movimiento3)=each($Movimiento2))
                    {
                        //echo "list TipoComprobante<br>";
                        echo $Enc;
                        echo "<tr><td>$Fecha</td><td>$TipoComprobante</td><td align='center'>
                        <table border=\"1\" bordercolor=\"#e5e5e5\" 
                        style='font : normal normal small-caps 11px Tahoma; font-size: 8; font-weight: bold'>
                        <tr>";
                        $C=0;
                        $TotalCantidad = 0;
                        while(list($Cedula,$Cantidad)=each($Movimiento3))
                        {
                            //echo "list Cedula<br>";
                            if($C==8){echo"</tr><tr>";$C=0;}
                            echo "<td>$Cedula($Cantidad)</td>";
                            $TotalCantidad=$TotalCantidad+$Cantidad;
                            $C++;
                        }
                        if(!$SI)
                        {
                            $SI = $CantPeriodo;
                        }
                        echo "</tr></table></td>";
                        echo "<td align='right'>".number_format($CantPeriodo,2)."</td>";
                        if($TipoComprobante=="Salidas"){$Salidas=$TotalCantidad;$Entradas=0;}
                        else{$Salidas=0;$Entradas=$TotalCantidad;}
                        $CantPeriodo=$CantPeriodo+$Entradas-$Salidas;
                        echo "<td align='right'>".number_format($Entradas,2)."</td>
                              <td align='right'>".number_format($Salidas,2)."</td>
                              <td align='right'>".number_format($CantPeriodo,2)."</td></tr>";
                        $TotEntradas = $Entradas + $TotEntradas;
                        $TotSalidas = $Salidas + $TotSalidas;
                    }
                }
                ?><tr bgcolor="#e5e5e5" style="font-weight: bold" align="center">
                    <td colspan="3" rowspan="2">RESUMEN</td>
                    <td align="center">Saldo<br>Anterior</td><td>Entradas</td><td>Salidas</td><td>Saldo<br>Siguiente</td>
                </tr>
                <tr>
                    <td align="right"><? echo number_format($SI,2)?></td>
                    <td align="right"><? echo number_format($TotEntradas,2)?></td>
                    <td align="right"><? echo number_format($TotSalidas,2)?></td>
                    <td align="right"><? echo number_format($SI + $TotEntradas - $TotSalidas,2)?></td>
                </tr>
                <?
            }
            ?></table>
            <?
        }
        else
        {
            echo "<font color='red'><b><i>No existen movimientos para este periodo</i></b></font>";
        }
        echo "</body>";
    }
    else
    {
        class PDF extends FPDF
        {//192,168.1.110
            function BasicTable($Movimiento)
            {
                global $NombreMedicamento; global $TipoCom; global $VrSaldoIni;
                        global $FechaIni; global $FechaFin;
            global $VrEntradas; global $VrDevoluciones; global $VrSalidas;
                //echo $NombreMedicamento.$Movimiento;
                while(list($AutoId,$Movimiento1)=each($Movimiento))
                {
                    $CantPeriodo=$VrSaldoIni[$AutoId][0]+$VrEntradas[$AutoId][0]+$VrDevoluciones[$AutoId][0]-$VrSalidas[$AutoId][0];
                    $this->Cell(0,5,"Periodo de: $FechaIni a $FechaFin",0,0,'R');$this->Ln();
                    $this->SetFont('Arial','B',15);$this->SetFillColor(228,228,228);
                    $this->Cell(260,10,utf8_decode($NombreMedicamento[$AutoId]),1,0,'C',1);$this->Ln();
                    $this->SetFont('Arial','',8);
                    while(list($Fecha,$Movimiento2)=each($Movimiento1))
                    {
                        while(list($TipoComprobante,$Movimiento3)=each($Movimiento2))
                        {
                            //ENCABEZADO
                            $POSY=$this->GetY();
                            if($POSY>=160 && $POSY<190){$this->AddPage();}
                            $this->Cell(20,5,"Fecha",1,0,'C',1);
                            $this->Cell(60,5,"Movimiento",1,0,'C',1);
                            $this->Cell(100,5,"Formula",1,0,'C',1);
                            $this->Cell(20,5,"Saldo Anterior",1,0,'C',1);
                            $this->Cell(20,5,"Entrada",1,0,'C',1);
                            $this->Cell(20,5,"Salida",1,0,'C',1);
                            $this->Cell(20,5,"Saldo Siguiente",1,0,'C',1);
                            $this->Ln();
                            //INfo
                            $setx = $this->GetX();$sety = $this->GetY();
                            $this->Cell(80,5,"",0,0,'C');
                            $C=0;$X=1;
                            $TotalCantidad = 0;
                            while(list($Cedula,$Cantidad)=each($Movimiento3))
                            {
                                if($C==5)
                                {
                                    $this->Ln();$this->Cell(80,5,"",0,0,'C');
                                    $C=0;$X++;
                                }
                                $this->SetFont('Arial','B',5);
                                $this->Cell(20,5,"$Cedula($Cantidad)",1,0,'C');
                                $this->SetFont('Arial','',8);
                                $TotalCantidad=$TotalCantidad+$Cantidad;
                                $C++;
                            }
                            if($C>0&&$C<5){$this->Cell(20*(5-$C),5,"",1,0,'C');}
                            $this->SetX($setx);$this->SetY($sety);
                            $this->Cell(20,5*$X,$Fecha,1,0,'C');
                            $this->Cell(60,5*$X,$TipoComprobante,1,0,'C');
                            $this->Cell(100,5*$X,"",0,0,'C');
                            $this->Cell(20,5*$X,number_format($CantPeriodo,2),1,0,'R');
                            if($TipoComprobante=="Salidas"){$Salidas=$TotalCantidad;$Entradas=0;}
                            else{$Salidas=0;$Entradas=$TotalCantidad;}
                            $CantPeriodo=$CantPeriodo+$Entradas-$Salidas;
                            $this->Cell(20,5*$X,number_format($Entradas,2),1,0,'R');
                            $this->Cell(20,5*$X,number_format($Salidas,2),1,0,'R');
                            $this->Cell(20,5*$X,number_format($CantPeriodo,2),1,0,'R');
                            $this->Ln();
                        }
                    }
                    $this->AddPage();
                }
            }

            function Header()
            {
            }
            function Footer()
            {
                global $ND;
                $this->SetY(-15);
                $this->SetFont('Arial','I',8);
                $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
                $this->Ln(3);
                $this->Cell(0,10,'Impreso: '."$ND[year]-$ND[mon]-$ND[mday]",0,0,'C');
            }
        }
        $pdf=new PDF('L','mm','Letter');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial','',8);

        $pdf->BasicTable($Movimiento);
        
        $pdf->Ln(20);
        $pdf->Ln(5);
        $pdf->Output();
    }
?>