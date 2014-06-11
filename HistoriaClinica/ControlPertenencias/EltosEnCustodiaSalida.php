<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	
	$cons="select nombre,usuario from central.usuarios";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Usus[$fila[1]]	=$fila[0];
	}
	$cons = "Select Departamentos.Departamento,Municipios.Municipio from central.compania,Central.Departamentos,Central.Municipios
	Where Nombre = '$Compania[0]' and Compania.Departamento = Departamentos.Codigo
	and Compania.Municipio = Municipios.CodMpo and Municipios.Departamento = Departamentos.Codigo";
	$res = ExQuery($cons);
	$fila = ExFetch($res);
	$Municipio = $fila[1]; $Departamento = $fila[0];
	
	$cons="select cedula,primape,segape,primnom,segnom,tiposervicio from salud.servicios,central.terceros 
	where servicios.compania='$Compania[0]' and numservicio=$NumServ and cedula='$Ced' and cedula=identificacion and terceros.compania='$Compania[0]'";
	$res=ExQuery($cons); $DatPaciente=ExFetch($res); 
	
	$cons="select responsable,elemento,estado,fechasalida,nota,nc from salud.elementoscustodia where compania='$Compania[0]' and cedula='$Ced' and numservicio=$NumServ and 
	fechasalida is not null order by responsable,elemento";
	$res=ExQuery($cons);
	
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Elementos En Custodia</title>
</head>

<body background="/Imgs/Fondo.jpg">
<center><font style="font : 15px Tahoma;font-weight:bold">
<?echo $Compania[0]?><br /></font>
<font style="font : 12px Tahoma;">
<? echo $Compania[1]?><br /><? echo "$Compania[2] $Compania[3]"?><br /><? echo "$Municipio - $Departamento"?>
</center></strong></font>
<br>
<br>
<table style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="4">
	<tr>
    	<td align="center" style="font-weight:bold" colspan="4">DATOS PACIENTE</td>
    </tr>
	<tr>
	    <td align="center" style="font-weight:bold">Cedula:</td><td><? echo $Ced?></td>
        <td align="center" style="font-weight:bold">Nombre:</td>   	
        <td><? echo "$DatPaciente[1] $DatPaciente[2] $DatPaciente[3] $DatPaciente[4]"?></td>
   	</tr>
    <tr>
        <td align="center" style="font-weight:bold">Ambito</td>
        <td><? echo $DatPaciente[5]?></td>
   	</tr>
</table>    
<br><br>
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="4">
	<tr><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan="15">Elementos En Custodia Con Salida</td></tr>
	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
    	<td>Responsalbe</td><td>Elemento</td><td>Estado</td><td>Fecha Salida</td><td>nota</td><td>NC</td>
	</tr>        
<?	if(ExNumRows($res)>0)
	{
		while($fila=ExFetch($res))
		{
			$FS=substr($fila[3],0,10);
			if($fila[5]==1){$NoC="Si";}else{$NoC="No";}
			?>
            <tr align="center">            
				<td><? echo $Usus[$fila[0]]?></td><td><? echo $fila[1]?></td><td><? echo $fila[2]?></td><td><? echo $FS?></td>
                <td><? echo $fila[4]?>&nbsp;</td><td><? echo $NoC?></td>
         	</tr>
<?		}
	}?>
</table>
<br><br>
<br><br>
<table style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="4">
	<tr align="center">
    	<td><hr color="#000000" width="200px"></td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><hr color="#000000" width="200px"></td>
   	</tr>
    <tr align="center">
        <td>Firma Paciente</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>Firma Responsable</td>
   </tr>
</table>
<img src="/Imgs/Logo.jpg" style="width: 80px; height: 90px; position: absolute; top: 10px; left: 50px;" />
</body>
</html>