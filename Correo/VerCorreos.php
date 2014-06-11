<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Ver=="Leidos"){$Opc="and fechalee is not null";}
	if($Ver=="Sin Leer"){$Opc="and fechalee is null";}
	if($Ver!='Enviados'){
		$cons="select nombre,usucrea,fechacrea,fechalee,mensaje,id,asunto from central.correos,central.usuarios where compania='$Compania[0]' and usurecive='$usuario[1]' 
		and usuario= usucrea and estado='AC' $Opc order by id desc";
	//echo $cons;
	}
	else{
		$cons="select  nombre,usucrea,fechacrea,fechalee,mensaje,id,asunto  from central.correos,central.usuarios where compania='$Compania[0]' and usucrea='$usuario[1]' and estadoenv='AC' and usuario=usurecive order by id desc";
	}
	$res=ExQuery($cons);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table BORDER=1  border="1" bordercolor="#e5e5e5" cellpadding="1" align="center" style='font : normal normal small-caps 12px Tahoma; width:100%'>	
	<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold">    	
<?	if($Ver!='Enviados'){?>
			<td width="25%">Envia</td><td width="45%">Asunto</td><td width="15%">Recibido</td><td width="15%">Leido</td><td></td>
	    </tr>
	<?	while($fila=ExFetch($res))
		{
			$FecRev=explode(" ",$fila[2]);
			$FecLee=explode(" ",$fila[3]);
			if(!$fila[3]){$Actualiza=1;}?>
            <tr title="Ver"  onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand">
            	<td onClick="parent.location.href='Correo.php?DatNameSID=<? echo $DatNameSID?>&Recivido=1&Id=<? echo $fila[5]?>&Actualiza=<? echo $Actualiza?>&Ver=<? echo $Ver?>'"><? echo "$fila[0]"?></td>
                <td onClick="parent.location.href='Correo.php?DatNameSID=<? echo $DatNameSID?>&Recivido=1&Id=<? echo $fila[5]?>&Actualiza=<? echo $Actualiza?>&Ver=<? echo $Ver?>'"><img <? if(!$fila[3]){?> src="/Imgs/c_sin_leer.jpg"<? }else{?> src="/Imgs/c_leido.jpg"<? }?>>
                <? echo $fila[6]?></td>
                <td align='center' onClick="parent.location.href='Correo.php?DatNameSID=<? echo $DatNameSID?>&Recivido=1&Id=<? echo $fila[5]?>&Actualiza=<? echo $Actualiza?>&Ver=<? echo $Ver?>'"><? echo "$fila[2]";?></td>
                <td align='center' onClick="parent.location.href='Correo.php?DatNameSID=<? echo $DatNameSID?>&Recivido=1&Id=<? echo $fila[5]?>&Actualiza=<? echo $Actualiza?>&Ver=<? echo $Ver?>'"><? echo "$fila[3]"//$FecLee[0]?>&nbsp;</td>
				<td><img src="/Imgs/b_drop.png" title="Eliminar" onClick="if(confirm('Esta seguro de eliminar este correo?')){parent.location.href='BandejaEntrada.php?DatNameSID=<? echo $DatNameSID?>&IdElim=<? echo $fila[5]?>&Tipo=<? echo $Ver?>'}">
                </td>
                </tr>
	<?	}
	}
	else{?>
			<td width="25%">Enviado a</td><td width="45%">Asunto</td><td width="15%">Recibido</td><td width="15%">Leido</td><td></td>
        </tr>
   	<?	while($fila=ExFetch($res))
		{
			$FecRev=explode(" ",$fila[2]);
			$FecLee=explode(" ",$fila[3]);?>
            <tr title="Ver"  onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand">
            <td  onClick="parent.location.href='Correo.php?DatNameSID=<? echo $DatNameSID?>&Id=<? echo $fila[5]?>&Ver=<? echo $Ver?>'">
		<?	echo "$fila[0]"?></td>
			<td onClick="parent.location.href='Correo.php?DatNameSID=<? echo $DatNameSID?>&Id=<? echo $fila[5]?>&Ver=<? echo $Ver?>'">
            <img <? if(!$fila[3]){?> src="/Imgs/c_sin_leer.jpg"<? }else{?> src="/Imgs/c_leido.jpg"<? }?>>
			<? echo $fila[6]?></td>
            <td onClick="parent.location.href='Correo.php?DatNameSID=<? echo $DatNameSID?>&Id=<? echo $fila[5]?>&Ver=<? echo $Ver?>'">
			<? //echo "$FecRev[0]"
			echo "$fila[2]"
			?></td>
            <td align='center' onClick="parent.location.href='Correo.php?DatNameSID=<? echo $DatNameSID?>&Id=<? echo $fila[5]?>&Ver=<? echo $Ver?>'">
			<? //echo $FecLee[0]
			echo "$fila[3]"
			?>&nbsp;</td>
            <td><img src="/Imgs/b_drop.png" title="Eliminar" onClick="if(confirm('Esta seguro de eliminar este correo?')){parent.location.href='BandejaEntrada.php?DatNameSID=<? echo $DatNameSID?>&IdElimEnv=<? echo $fila[5]?>&Tipo=<? echo $Ver?>'}">
            </td>
            </tr>
	<?	}
	}?>    
</table>
</form>  
</body>
</html>