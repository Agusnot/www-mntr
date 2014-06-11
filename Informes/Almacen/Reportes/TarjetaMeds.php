<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Informes.php");
include("ObtenerSaldos.php");
require('LibPDF/rotation.php');
$ND = getdate(); 
$Cantidadtot = array();
$Ambito = explode("-",$Ambito);
if(!$TarjetaMeds)
echo "No existen registros";
else{
while(list($Identificacion,$Arreglo) = each($TarjetaMeds))
{
    $cons = "Select PrimNom,SegNom,PrimApe,SegApe,identificacion from Central.Terceros, Consumo.Movimiento Where Central.Terceros.Compania='$Compania[0]' 
	and cedula=Identificacion and fechadespacho='$Fecha'
	and Identificacion='$Identificacion' order by Identificacion ASC";
    $res = ExQuery($cons);
    $fila = ExFetch($res);
    $Nombre[$Identificacion] = "$fila[0] $fila[1] $fila[2] $fila[3]";
    while(list($AutoId,$Cantidad) = each($Arreglo))
    {
        $cons = "Select NombreProd1,UnidadMedida,Presentacion from Consumo.CodProductos
            Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal' and Anio = $ND[year] and AutoId=$AutoId";
        $res = ExQuery($cons);
        $fila = ExFetch($res);
        $Medicamento[$AutoId] = "$fila[0] $fila[1] $fila[2]";
        $cons = "Select Hora,Cantidad,Via,idescritura,numorden from salud.horacantidadxMedicamento
            Where Compania='$Compania[0]' and AlmacenPpal='$AlmacenPpal'
            and paciente='$Identificacion' and AutoId = $AutoId and Estado = 'AC' and Tipo='P'";
        $res = ExQuery($cons);
        while($fila = ExFetch($res))
        {
            //if(!$Ya[$fila[0]][$fila[1]])
            //{
			$consServicio="select numservicio from salud.servicios where cedula='$Identificacion' and estado='AC'";
			$resServicio=ExQuery($consServicio);
			$filaServicio=ExFetch($resServicio);
			
			$consFecha="select fecha from salud.ordenesmedicas where numservicio='$filaServicio[0]' and idescritura='$fila[3]' and numorden='$fila[4]'";
			$resFecha=ExQuery($consFecha);
			$filaFecha=ExFetch($resFecha);
			
			$consNota="select notas from salud.plantillamedicamentos where numservicio='$filaServicio[0]' and idescritura='$fila[3]' and fechaformula='$filaFecha[0]'";
			$resNota=ExQuery($consNota);
			$filaNota=ExFetch($resNota);
			
			if($filaNota[0]){
				if(!$Tarjeta[$Identificacion][$AutoId]){!$Tarjeta[$Identificacion][$AutoId] = "$filaNota[0] ";}
				 $Via[$Identificacion][$AutoId] = $fila[2];
                if(!$Cantidadtot[$Identificacion][$AutoId]){$Cantidadtot[$Identificacion][$AutoId] = $fila[1];}
                else{$Cantidadtot[$Identificacion][$AutoId] = $Cantidadtot[$Identificacion][$AutoId] + $fila[1];}
                $Ya[$fila[0]][$fila[1]] = 1; 
			} else{
			//$filaNota[0]="NOTA: $filaNota[0]<br><br>";}
	
	            if(!$Tarjeta[$Identificacion][$AutoId]){!$Tarjeta[$Identificacion][$AutoId] = "$fila[1]($fila[0])";}
                else{$Tarjeta[$Identificacion][$AutoId] = $Tarjeta[$Identificacion][$AutoId]." - $fila[1]($fila[0]) ";}
                $Via[$Identificacion][$AutoId] = $fila[2];
                if(!$Cantidadtot[$Identificacion][$AutoId]){$Cantidadtot[$Identificacion][$AutoId] = $fila[1];}
                else{$Cantidadtot[$Identificacion][$AutoId] = $Cantidadtot[$Identificacion][$AutoId] + $fila[1];}
                $Ya[$fila[0]][$fila[1]] = 1; 
            }
        }
    }
}
?>
<head>
<style>
    body
    {
        margin-top: 0;
        margin-bottom: 0;
        margin-left: 0;
        margin-right: 0;
    }
    .break { page-break-before: always; }
</style>
</head>
<body>

   <?$CC=1;
   while(list($Identificacion,$Tarjeta1) = each($Tarjeta))
   {
     if(!$X)
     {
         echo "<table style='font : normal normal small-caps 12px Tahoma;' cellspacing='10' width='90%'><tr>";
         
     }
     ?><td align="center" valign="top" width="50%">
         <table style='font : normal normal small-caps 10px Tahoma;' border="1" bordercolor="#f1f1f1" cellspacing="0">
          <tr style=" font-weight: bold"><td align="center" colspan ="4">
                  <div align="right"><font style=" font-size: 9px;">
                        <? echo $CC;/*$OrdenPacientes[$Identificacion]*/?></font></div>
              <? echo "$Ambito[0] - $Pabellon<br>$Nombre[$Identificacion]($Identificacion)";
			  		//es para escribir la entidad
					$consE1="select numservicio from salud.servicios where cedula='$Identificacion'";
					$resE1=ExQuery($consE1);
					$filaE1=ExFetch($resE1);
					$consE2="select entidad from salud.pagadorxservicios where numservicio='$filaE1[0]'";
					$resE2=ExQuery($consE2);
					$filaE2=ExFetch($resE2);
					$consE3="select primape from central.terceros where identificacion='$filaE2[0]'";
					$resE3=ExQuery($consE3);
					$filaE3=ExFetch($resE3);
					echo"<br>$filaE3[0]";
			
			  
			  ?></td></tr>
          <tr bgcolor="#e5e5e5">
              <td width="30%">Medicamento</td><td  width="30%">Dosis</td><td width="5%">Cant</td><td width="5%">Via</td>
          </tr>
          <?
          while(list($AutoId,$Posologia) = each($Tarjeta1))
          {
              ?>
              <tr>
                  <td><? echo utf8_decode_seguro(strtoupper($Medicamento[$AutoId]))?></td>
                  <td align="right"><? echo $Posologia;?></td>
                  <td align="right"><? echo $Cantidadtot[$Identificacion][$AutoId];?></td>
                  <td><? echo $Via[$Identificacion][$AutoId]?></td>
              </tr>
              <?
          }
          ?>
              <tr><td colspan="4" align="right"><? echo "Fecha de impresion: $ND[year]-$ND[mon]-$ND[mday]"?></td></tr>
      </table></td><?
      if($X)
      {
          echo "</tr></table>"; 
          if($C%3==0){echo "<p class='break'></p><br>";}
          //echo $C."---".$C%3;
          unset ($X);$Salto="";}
      else
      {
        $X = 1;
        $C++;
      }
	  $CC++;
   }
   ?>
</body>
<?

/*
class PDF extends PDF_Rotate
{
    function BasicTable($Tarjeta)
    {
       global $Nombre; global $Medicamento;global $Ambito; global $Pabellon; global $Via; global $Cantidadtot; global $ND;
        $C=1;
       while(list($Identificacion,$Arreglo) = each($Tarjeta))
       {
          $this->SetFont('Arial','B',8);
          if($NuevaCol){$this->SetX(110);}
          else{$this->SetX(3);}
          $this->Cell(100,5,utf8_decode_seguro("$Ambito[0] - $Pabellon"),'TLR',0,'C');
          $this->Ln();
          if($NuevaCol){$this->SetX(110);}
          $this->Cell(100,5,utf8_decode_seguro($C.".".$Nombre[$Identificacion])."($Identificacion)",'LRB',0,'C');
          $this->Ln();
          if($NuevaCol){$this->SetX(110);}
          $this->Cell(55,5,'Medicamento','LRB',0,'C');
          $this->Cell(30,5,'Dosis','LRB',0,'C');
          $this->Cell(7,5,'Cant','LRB',0,'C');
          $this->Cell(8,5,'Via','LRB',0,'C');
          $this->Ln();
          if($NuevaCol){$this->SetX(110);}
          $this->SetFont('Arial','',8);
          while(list($AutoId,$Posologia) = each($Arreglo))
          {
              $this->Cell(55,5,utf8_decode_seguro(strtoupper(substr($Medicamento[$AutoId],0,35))),'LRB',0,'L');
              $this->Cell(30,5,$Posologia,'LRB',0,'R');
              $this->Cell(7,5,$Cantidadtot[$Identificacion][$AutoId],'LRB',0,'L');
              $this->Cell(8,5,substr($Via[$Identificacion][$AutoId],0,5),'LRB',0,'L');
              $this->Ln();
              if($NuevaCol){$this->SetX(110);}
          }
          if($NuevaCol){$this->SetX(110);}
          $this->Cell(100,5,"Fecha de impresion: $ND[year]-$ND[mon]-$ND[mday]",'LRB',0,'R');
          $this->Ln(8);
          $PosY = $this->GetY();
          if($PosY>150 && $PosY<180)
          {
              if($NuevaCol){$this->AddPage();$hacerunset = 1;}
              $this->SetXY(110,3);
              $NuevaCol = 1;
              if($hacerunset)
              {
                  unset($hacerunset);
                  unset($NuevaCol);
              }
          }
          $C++;
       }
    }
    function Header()
    {}
    function Footer()
    {}
}*/
//$pdf=new PDF('P','mm','Letter');
//$pdf->SetMargins(3, 3, 3);
//$pdf->AliasNbPages();
//$pdf->AddPage();
//$pdf->SetFont('Arial','',8);
//$pdf->BasicTable($Tarjeta);
//$pdf->Output();
}
?>