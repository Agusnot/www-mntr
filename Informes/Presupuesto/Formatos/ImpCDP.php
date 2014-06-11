<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Informes.php");
	$Fondo=1;
	$cons="Select AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Credito,ContraCredito,DocSoporte,'',Estado,UsuarioCre,Anio
	from Presupuesto.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Compania='$Compania[0]' and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia' Order By Credito Desc";
	$res=ExQuery($cons);echo ExError();
	$fila=ExFetchArray($res);
	$UsuarioCre=$fila[12];
        $Fecha=$fila[1];
        $Anio=substr($fila[1],0,4);
        ///////////////////
        /*define("UTF_8", 1);
         define("ASCII", 2);
         define("ISO_8859_1", 3);
         function codificacion($texto)
         {
             $c = 0;
             $ascii = true;
             for ($i = 0;$i<strlen($texto);$i++) {
                 $byte = ord($texto[$i]);
                 if ($c>0) {
                     if (($byte>>6) != 0x2) {
                         return ISO_8859_1;
                     } else {
                         $c--;
                     }
                 } elseif ($byte&0x80) {
                     $ascii = false;
                     if (($byte>>5) == 0x6) {
                         $c = 1;
                     } elseif (($byte>>4) == 0xE) {
                         $c = 2;
                     } elseif (($byte>>3) == 0x14) {
                         $c = 3;
                     } else {
                         return ISO_8859_1;
                     }
                 }
             }
             return ($ascii) ? ASCII : UTF_8;
         }

         function utf8_decode_seguro($texto)
         {
             return (codificacion($texto)==ISO_8859_1) ? $texto : utf8_decode($texto);
         }
        ///////////////////
        */
?>
<title>Impresión de Comprobante</title>
<div style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<?
	if($fila["Estado"]=="AN")
	{
		echo "<img src='/Imgs/Anulado.gif' style='position:absolute;top:170px;left:140px'>";
	}

	$Numero=$Numero;

?>
<table border="0">
<tr><td>
<img src="/Imgs/Logo.jpg" style="width:100px;position:relative">
</td><td><strong>
<font style="font-family:<?echo $Estilo[8]?>;font-size:11;">
<?
echo strtoupper($Compania[0])."<br></strong>";
echo "$Compania[1]<br>$Compania[2] - $Compania[3]<br>";
?>
</table>
</div>
<table rules="cols"  bordercolor="#ffffff" style="position:absolute;right:20px;top:20px;" border="1" style="font-family:<?echo $Estilo[8]?>;font-size:10;">
<tr bgcolor="#e5e5e5"><td><?echo strtoupper($Comprobante)?></td></tr>
<tr><td><font size="+1"><center><?echo $Numero?></td></tr>
</table>
<?
	
	$cons1="Select PrimApe,SegApe,PrimNom,SegNom,Identificacion,Direccion,Telefono from Central.Terceros where Identificacion='$fila[4]' and Terceros.Compania='$Compania[0]'";
	$res1=ExQuery($cons1);
	$fila1=ExFetch($res1);
?>
<br><br>
<center style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<?
	$cons2="Select * from Presupuesto.MsjComprobantes where Anio=$Anio";

	$res2=ExQuery($cons2);
	$fila2=ExFetch($res2);
	echo "<center>".utf8_decode_seguro($fila2[1])."</center>";
?>
</center>
<br>
<table border="1" width="85%" bordercolor="#ffffff" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr><td><strong>Fecha</td><td><?echo $fila[1]?></td></tr>
<tr><td><strong>Tercero</td><td><?echo "$fila1[0] $fila1[1] $fila1[2] $fila1[3]"?></td><td><strong>Identificacion</td><td><?echo $fila1[4]?></td></tr>
<tr><td><strong>Dirección</td><td><?echo $fila1[5]?></td><td><strong>Telefono</td><td><?echo $fila1[6]?></td></tr>
<tr><td><strong>Detalle</td><td colspan="3"><?echo $fila['detalle']?></td></tr>
</table>

<table border="1" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:11;font-style:<?echo $Estilo[10]?>">
<tr bgcolor="#e5e5e5" style="font-weight:bold"><td>Codigo</td><td>Nombre</td><td>Doc</td><td>Valor</td></tr>
		<?
			$res=ExQuery($cons);
			while($fila=ExFetchArray($res))
			{
				$cons9="Select Nombre from Presupuesto.PlanCuentas where Cuenta='".$fila['cuenta']."' and Anio=". $fila['anio'] . " and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
				$res9=ExQuery($cons9);
				$fila9=ExFetch($res9);
				$NomCuenta=substr($fila9[0],0,80);
				$consEPUC="Select NoCaracteres from Presupuesto.EstructuraPuc where Compania='$Compania[0]' and Anio=". $fila['anio'] ." Order By Nivel";
				$resEPUC=ExQuery($consEPUC);echo ExError();
				while($filaEPUC=ExFetch($resEPUC))
				{
					$NumCar=$NumCar+$filaEPUC[0];
					$PartCuenta=substr($fila['cuenta'],0,$NumCar);
					$cons10="Select Cuenta,Nombre from Presupuesto.PlanCuentas where Cuenta='$PartCuenta' and Anio=". $fila['anio'] . " and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
					$res10=ExQuery($cons10);
					$fila10=ExFetch($res10);

					$cons9="Select Nombre from Presupuesto.PlanCuentas where Cuenta='".$fila10[0]."' and Anio=". $fila['anio'] . " and Vigencia='$Vigencia' and ClaseVigencia='$ClaseVigencia'";
					$res9=ExQuery($cons9);
					$fila9=ExFetch($res9);
					$NomCuenta=substr($fila9[0],0,80);

					if($fila10[0]==$fila['cuenta']){break;}
					echo "<tr bgcolor='$BG'><td>$fila10[0]</td><td>$NomCuenta</td><td align='right'>".$fila['docsoporte']."</td><td align='right'>0.00</td></tr>";
				}
				$NumCar=0;
				echo "<tr bgcolor='$BG'><td>".$fila['cuenta']."</td><td>$NomCuenta</td><td align='right'>".$fila['docsoporte']."</td><td align='right'>".number_format($fila['credito'],2)."</td></tr>";
				$TotCre=$TotCre+$fila['credito'];
				$TotCCre=$TotCCre+$fila['contracredito'];
				
				
				if($Fondo==1){$BG="#F7F7F7";$Fondo=0;}
				else{$BG="white";$Fondo=1;}
				
			}
			echo "<tr align='right' style='font-weight:bold'><td colspan=2></td><td bgcolor='#e5e5e5'>TOTAL</td><td bgcolor='#e5e5e5' >".number_format($TotCre,2)."</td></tr>";
			$TotCre=0;$TotCCre=0;
?>
</table>
<br>
<?
    $Firmas=Firmas($Fecha,$Compania);
?>
<table border="0" bordercolor="#000000" cellspacing="1" width="100%" style="font : normal normal 12px Tahoma;">
<tr valign="top"><td width="40%"><em><hr /><? echo $Firmas['Presupuesto'][0]?><br />Aprob&oacute;</em></td><td width="20%"></td><td width="40%"><hr /><em><?echo $UsuarioCre?><br />Elabor&oacute;</em></td></tr>
</table>
