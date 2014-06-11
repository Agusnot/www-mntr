<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
//Ult. Mod. 96-07-2011
//Ult. Mod. 26-03-2011
        $cons = "Select Departamentos.Departamento,Municipios.Municipio from
        central.compania,Central.Departamentos,Central.Municipios
        Where Nombre = '$Compania[0]' and Compania.Departamento = Departamentos.Codigo
        and Compania.Municipio = Municipios.CodMpo and Municipios.Departamento = Departamentos.Codigo";
        $res = ExQuery($cons);
        $fila = ExFetch($res);
        $Municipio = $fila[1]; $Departamento = $fila[0];
        $cons="Select distinct Comprobante,Numero,Fecha,Codigo1,Cantidad,
	NombreProd1,Presentacion,UnidadMedida,Cedula,VrCosto,Movimiento.VrIVA,(TotCosto+Movimiento.VrIVA),Movimiento.UsuarioCre,TotCosto,Detalle,
	IncluyeIVA,PorcIVA,UsuarioVoBo,date(FechaVoBo),AprobadoX,date(FechaAprobac)
	from Consumo.Movimiento,Consumo.CodProductos
	where Movimiento.AutoId=CodProductos.AutoId and Movimiento.Compania='$Compania[0]' and CodProductos.Compania='$Compania[0]'
	and Movimiento.AlmacenPpal='$AlmacenPpal' and CodProductos.AlmacenPpal='$AlmacenPpal' and Comprobante='$Comprobante' and Numero='$Numero'
	and CodProductos.Anio = $Anio";
        $res=ExQuery($cons);echo ExError();
	$fila=ExFetch($res);
	$Fecha=$fila[2];$Usuario=$fila[12];$Detalle=$fila[14];
	if($fila[7]=="Anulado"){echo "<img style='position:absolute;left:100px;top:100px;' src='/Imgs/Anulado.gif'>";}
	$cons2="Select PrimApe,SegApe,PrimNom,SegNom,Direccion,Telefono
        from Central.Terceros where Identificacion='$fila[8]' and Compania='$Compania[0]'";
	$res2=ExQuery($cons2);echo ExError();
	$fila2=ExFetch($res2);
	$NomTercero="$fila2[0] $fila2[1] $fila2[2] $fila2[3]";$Direccion=$fila2[4];$Telefono=$fila2[5];
        $VoBoX = $fila[17]; $FechaVoBo = $fila[18];
        $AproX = $fila[19]; $FechaApro = $fila[20];
?>
<head>
	<title><?echo $Sistema[$NoSistema]?></title>
</head>

<body background="/Imgs/Fondo.jpg">
<center><font style="font : 15px Tahoma;font-weight:bold">
<?echo $Compania[0]?><br /></font>
<font style="font : 12px Tahoma;">
<? echo $Compania[1]?><br /><? echo "$Compania[2] $Compania[3]"?><br /><? echo "$Municipio - $Departamento"?>
</center></strong></font>

<table bordercolor='#e5e5e5' border="0" style='font : normal normal small-caps 14px Tahoma;' align="center">
<tr><td bgcolor="#e5e5e5"><strong><font size="3">
<div style="cursor:hand" onClick="location.href='OrdenCompra2.php?DatNameSID=<? echo $DatNameSID?>&Analisis=1&Numero=<? echo $Numero?>&Comprobante=<? echo $Comprobante?>&AlmacenPpal=<? echo $AlmacenPpal?>&AnioComp=<? echo $Anio?>'">
<? echo $fila[0]?></div></td></tr><tr><td align="center"><? echo $fila[1]?></td></tr>
</table>


<table border="1" bordercolor="white" width="100%" style='font : normal normal small-caps 12px Tahoma;'>
<tr><td bgcolor="#e5e5e5">Fecha</td><td><?echo $fila[2]?></td>
<td></td><td></td>



</td>
</tr>


<tr><td bgcolor="#e5e5e5">Proveedor</td><td><?echo $NomTercero?></td><td bgcolor="#e5e5e5">Identificacion</td><td><? echo $fila[8]?></td>
<tr><td bgcolor="#e5e5e5">Direccion</td><td><?echo $Direccion?></td>
<td bgcolor="#e5e5e5">Telefono</td><td><?echo $Telefono?></td></tr>
<tr>
<td bgcolor="#e5e5e5">Detalle</td><td colspan="3"><? echo $Detalle?></td>
</tr>

</table>

<br /><br /><center>
<font style="font : 12px Tahoma;">
De acuerdo a su cotizacion, sirvase enviar con destino a <? echo $Compania[0]?> los siguientes articulos:<br><br>
</font></center>
<table border="1" bordercolor="#e5e5e5" width="100%" style='font : normal normal small-caps 12px Tahoma;'>
<tr align="center" style="font-weight:bold" bgcolor="#e5e5e5"><td>Codigo</td><td>Nombre</td><td align="right">Cantidad</td><td>Vr Unidad</td><td>Vr IVA</td><td>Total</td></tr>
<?
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
                if(!$fila[10] || $fila[10] == 0)
		{
			if($fila[15]==1)
			{
                             //$fila[11]: TotalIva      $fila[14]:SUBTOTAL    $fila[17]:porcIVA
                            $fila[10] = $fila[13] - ($fila[13]/(($fila[16]/100)+1));
                            $fila[13] = $fila[13] - $fila[10];
                            $fila[11] = $fila[13] + $fila[10];
			}
		}
		$IvaX = $fila[10]/$fila[4];
		if($fila[15]==1)
		{
			$fila[9] = $fila[9] - $IvaX;
		}
		echo "<tr><td>$fila[3]</td><td>$fila[5] $fila[6] $fila[7]</td>
                <td align='right'>$fila[4]</td>
                <td align='right'>".number_format($fila[9],2)."</td>
                <td align='right'>".number_format($IvaX,2)."</td><td align='right'>".number_format($fila[11],2)."</td></tr>";
		$SubTotal=$SubTotal+$fila[13];
		$IVA=$IVA+$fila[10];
		$Total=$Total+$fila[11];
	}
	echo "<tr><td colspan=2></td><td bgcolor='#e5e5e5'><strong>TOTALES</td><td align='right' bgcolor='#e5e5e5'><strong>".number_format($SubTotal,2)."</td>
	<td align='right' bgcolor='#e5e5e5'><strong>".number_format($IVA,2)."</td>
	<td align='right' bgcolor='#e5e5e5'><strong>".number_format($Total,2)."</td></tr>";
	$Letras=NumerosxLet(round($Total,0));
	$cons55="Select Mensaje1 from Consumo.Comprobantes where Compania='$Compania[0]' and Comprobante='$Comprobante'";
	$res55=ExQuery($cons55);echo ExError();
	$fila55=ExFetch($res55);

	$Firmas=Firmas($Fecha,$Compania);
	$Cargo=$Firmas['Representante'][0];$NombreDirec=$Firmas['Representante'][1];

?>
<tr><td colspan="6">SON: <font size="-2"><? echo strtoupper($Letras)?></td></tr>
<tr><td colspan="5">

</table>
<br /><br /><br />
<table border="0" style='font : normal normal small-caps 12px Tahoma;'>
    <tr valign="middle"><td>
    <?
        $consxx = "Select Cedula from Central.Usuarios Where Nombre = '$Usuario'";
        $resxx = ExQuery($consxx);
        $filaxx = ExFetch($resxx); $CedulaUsuario = $filaxx[0];
        if (file_exists($_SERVER{'DOCUMENT_ROOT'} . "/Firmas/$CedulaUsuario.png"))
        {
            ?>
            <img src="<? echo "/Firmas/$CedulaUsuario.png"?>" width="200px" /><br>
            <?
        }
    ?>
    _________________________________&nbsp;&nbsp;<br /><strong>Elaboro<br><?echo $Usuario?></strong></td>
<!--<td style="width:100px;">
<td>_________________________________<strong><br><? echo $NombreDirec?><br><? echo $Cargo?>-->
<?
if($VoBoX)
{
    ?><td><?
    $consxx = "Select Cargo,Cedula from central.CargosxCompania,Central.Usuarios
    Where Compania='$Compania[0]' and CargosxCompania.Identificacion = Usuarios.Cedula
    and Usuarios.Nombre = '$VoBoX'"; //echo $consxx;
    $resxx = ExQuery($consxx);
    $filaxx = ExFetch($resxx); $CargoVoBo = $filaxx[0]; $CedulaVoBo = $filaxx[1];
    if (file_exists($_SERVER{'DOCUMENT_ROOT'} . "/Firmas/$CedulaVoBo.png"))
    {
        ?>
        <img src="<? echo "/Firmas/$CedulaVoBo.png"?>" width="200px" /><br>
        <?
    }
?>
    _________________________________&nbsp;&nbsp;<br /><strong>Revisado <br>
    <?echo "$FechaVoBo <br> $VoBoX <br> $CargoVoBo"?></strong></td>
<?
}
if($AproX)
{
    ?><td><?
    $consxx = "Select Cargo,Cedula from central.CargosxCompania,Central.Usuarios
    Where Compania='$Compania[0]' and CargosxCompania.Identificacion = Usuarios.Cedula
    and Usuarios.Nombre = '$AproX'"; //echo $consxx;
    $resxx = ExQuery($consxx);
    $filaxx = ExFetch($resxx); $CargoApro = $filaxx[0]; $CedulaApro = $filaxx[1];
    if (file_exists($_SERVER{'DOCUMENT_ROOT'} . "/Firmas/$CedulaApro.png"))
    {
        ?>
        <img src="<? echo "/Firmas/$CedulaApro.png"?>" width="200px" /><br>
        <?
    }
  ?>
    _________________________________&nbsp;&nbsp;<br /><strong>Aprobado <br>
    <?echo "$FechaApro <br> $AproX <br> $CargoApro"?></strong></td>
  <?
}
?>
</tr>
</table><br><font size="-1">
<?
	echo "<em>$fila55[0]</em>";
?></font>
<img src="/Imgs/Logo.jpg" style="width: 80px; height: 90px; position: absolute; top: 10px; left: 50px;" />
</body>