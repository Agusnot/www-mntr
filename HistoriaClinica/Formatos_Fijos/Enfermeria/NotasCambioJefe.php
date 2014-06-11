<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if(!$Anio){$Anio=$ND[year];}
	//if(!$SelUnidad){$SelUnidad=$Unidad;}
	if(!$Mes){$Mes=$ND[mon];}
	if($Mes<10){$Mes="0" . $Mes;}
	if(!$Dia){$Dia=$ND[mday];}
	if($Dia<10){$Dia='0' . $Dia;}
	$cons="Select vistobuenojefe from salud.medicos,salud.cargos where medicos.Compania='$Compania[0]' and medicos.compania=cargos.compania
	and usuario='$usuario[1]' and vistobuenojefe=1 and medicos.cargo=cargos.cargos";	
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$PermiteNNota=$fila[0];		
	if(!$PermiteNNota)
	{
		echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>Lo Sentimos, está sección es solo para Enfermeros Jefe!!! </b></font></center><br>";
		exit;	
		$Disa="disabled";
	}	
	//echo $Mes." --> ".$Dia." $Anio --> $SelUnidad<br>";
?>
<head>

</head>
<body  background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSid" value="<? echo $DatNameSID?>">
<center>
<font  style="text-transform:uppercase"><strong>NOTAS DE CAMBIO DE JEFE <? echo $SelUnidad?></strong></font>
</center>
<table  border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;' align="center">
<tr bgcolor="#e5e5e5" style="font-weight:bold">
 <td>A&ntilde;o:</td>
 <td>
 <?	$cons="select anio from central.anios where compania='$Compania[0]' order by anio desc";
 	$res=ExQuery($cons);?>
 	<select name="Anio" onChange="FORMA.submit()">
    <?	while($fila=ExFetch($res))
		{
			if($fila[0]==$Anio){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
			else{echo "<option value='$fila[0]'>$fila[0]</option>";}
		}?>
    </select>
 </td>
 <td>Mes:</td>
 <td>
 	<select name="Mes" onChange="FORMA.submit();">
<?	$cons="Select numero,mes from central.meses";	
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($fila[0]==$Mes){echo "<option value='$fila[0]' selected>$fila[1]</option>";}
		else{echo "<option value='$fila[0]'>$fila[1]</option>";}
	}
	?>
	</select>
    </td>
	<td>Dia</td>	
	<td>
    <select name='Dia' onChange="FORMA.submit();">	
	<?
	$UltDia=UltimoDia($ND[year],$Mes);
    for($i=1;$i<=$UltDia;$i++)
	{
		if($i==$Dia)
		{echo "<option value=$i selected>$i</option>";}
		else
		{echo "<option value=$i>$i</option>";}
	}
	?>
	</select>
    </td>
    <!--<td>Unidad</td>
	<td>
    <select name="SelUnidad" onChange="FORMA.submit();">
    <option value="">-Seleccione servicio-</option>
	<?
    /*$cons="select pabellon from salud.pabellones where compania='$Compania[0]' order by Pabellon";
	$res=ExQuery($cons);	
	while($fila=ExFetch($res))
	{
		if($fila[0]==$SelUnidad){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
		else{echo "<option value='$fila[0]'>$fila[0]</option>";}
	}*/
	?>
	</select>	
    </td>-->
	<td><input type="Button" value="Agregar Registro" onClick="location.href='NuevaNotaCambioJefe.php?&DatNameSID=<? echo $DatNameSID?>&SelUnidad=<? echo $SelUnidad?>'" title="Crear Nota con Fecha de Hoy" <? echo $Disa;?>>				</td>
	</tr>
</table>
<table  border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 11px Tahoma;' width="100%">
<?
$cons = "Select fecha,usuario,nota,unidad From HistoriaClinica.NotasCambioJefe where Compania='$Compania[0]' and date(Fecha)='$Anio-$Mes-$Dia' Order By Fecha Desc;";
//echo $cons;
$res = ExQuery($cons);
while($fila=ExFetch($res))
{
	$fila[2]=str_replace("\r\n","<br>",$fila[2]);
	?>
	<tr bgcolor='#e5e5e5'><td><strong>Creada por:<font color='maroon'><? echo " $fila[1] - $fila[0]";?></font></strong></td></tr>
	<tr><td style='text-align:justify;'><? echo $fila[2];?></td></tr>
<?
}?>
</table>
</form>
</body>