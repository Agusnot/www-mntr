<?
	if($DatNameSID){session_name("$DatNameSID");}	
	session_start();
		
	$conex = pg_connect("dbname=sistema user=apache password=Server*1982") or die ('no establecida');
	$cons="select identificacion,primape,segape,primnom,segnom from central.terceros where compania='$Compania[0]' and tipo='Asegurador'";
	$res = pg_query($conex,$cons);
	while($fila=pg_fetch_row($res)){		
		$Aseguradores[$fila[0]]=array($fila[1],$fila[2],$fila[3],$fila[4]);		
	}
	$cons="select codigo,departamento from central.departamentos";
	$res = pg_query($conex,$cons);
	while($fila=pg_fetch_row($res)){		
		$Departamentos[$fila[0]]=$fila[1];		
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.Identificacion.value=="" && document.FORMA.PApellido.value=="" && document.FORMA.SApellido.value=="" && document.FORMA.PNombre.value=="" && document.FORMA.SNombre.value=="")
		{
			alert("Por favor llene al menos un campo para realizar una busqueda!!!");
			return false;
		}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg"> 
<form name="FORMA" method="post"  enctype="multipart/form-data" onSubmit="return Validar()">

<?	
	if(!$Buscar)
	{?>
		<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">
    	<tr>
        	<td bgcolor="#e5e5e5" style="font-weight:bold" align="right">Identificaci&oacute;n: </td>
            <td colspan="2"><input type="text" name="Identificacion" onFocus="xLetra(this)" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" maxlength="14" size="14" /></td>
        </tr>
        <tr>
        	<td bgcolor="#e5e5e5" style="font-weight:bold" align="right">Apellidos: </td>
            <td><input type="text" name="PApellido" maxlength="30" onFocus="xLetra(this)" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" size="30" /></td>
            <td><input type="text" name="SApellido" maxlength="30" onFocus="xLetra(this)" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" size="30" /></td>
         </tr>
         <tr>
         	<td bgcolor="#e5e5e5" style="font-weight:bold" align="right">Nombres: </td>
            <td><input type="text" name="PNombre" maxlength="30" onFocus="xLetra(this)" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" size="30" /></td>
            <td><input type="text" name="SNombre" maxlength="30" onFocus="xLetra(this)" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" size="30" /></td>
         </tr>
         <tr>
         	<td colspan="3" align="center"><input type="submit" name="Buscar" value="Buscar"></td>
         </tr>
         </table>
<?	}
	else{
		echo "<font style='font : normal normal small-caps 12px Tahoma;'><strong>HOSPITAL SAN RAFAEL DE PASTO<br>";
		echo "HOJA DE VERIFICACION DE DERECHOS<br><br>";
		$conex = mysql_connect('localhost','root','Server*1492') or die ('no establecida');
		mysql_select_db("BDAfiliados", $conex);
		/*if($Codigo)
		{
			$cons="Select * from Afiliados where Identificacion like '$Identificacion'";
		}
		elseif($Identificacion)
		{
			$cons="Select * from Afiliados where Identificacion like '$Identificacion'";
		}
		else
		{
			$cons="Select * from Afiliados 
			where Primer_Apellido like '%$PApellido%' and Segundo_Apellido like '%$SApellido%' and Primer_Nombre like '%$PNombre%' and Segundo_Nombre like '%$SNombre%'";
		}*/
		
		if($Identificacion){
			if($PApellido){$PA=" and Primer_Apellido like '%$PApellido%'";} if($SApellido){$SA=" and Segundo_Apellido like '%$SApellido%'";}
			if($PNombre){$PN=" and Primer_Nombre like '%$PNombre%'";} if($SNombre){$SN=" and Segundo_Nombre like '%$SNombre%'";}if($Id){ $Ident=" and Id=$Id";}
			$cons="Select * from Afiliados where Identificacion like '$Identificacion' $PA $SA $PN $SN $Ident";
		}
		elseif($PApellido){
			if($SApellido){$SA=" and Segundo_Apellido like '%$SApellido%'";}if($Id){ $Ident=" and id=$Id";}
			if($PNombre){$PN=" and Primer_Nombre like '%$PNombre%'";} if($SNombre){$SN=" and Segundo_Nombre like '%$SNombre%'";}
			$cons="Select * from Afiliados where Primer_Apellido like '%$PApellido%' $SA $PN $SN $Ident";
		}
		elseif($SApellido){			
			if($PNombre){$PN=" and Primer_Nombre like '%$PNombre%'";} if($SNombre){$SN=" and Segundo_Nombre like '%$SNombre%'";}if($Id){ $Ident=" and id=$Id";}
			$cons="Select * from Afiliados where Segundo_Apellido like '%$SApellido%' $PN $SN $Ident";
		}
		elseif($PNombre){
		 	if($SNombre){$SN=" and Segundo_Nombre like '%$SNombre%'";}if($Id){ $Ident=" and id=$Id";}
			$cons="Select * from Afiliados where Primer_Nombre like '%$PNombre%' $SN $Ident";
		}
		elseif($SNombre){if($Id){ $Ident=" and id=$Id";}
			$cons="Select * from Afiliados where Segundo_Nombre like '%$SNombre%' $Ident";
		}		
		$res=mysql_query($cons); echo mysql_error($conex);
		//echo $cons;
		if(mysql_num_rows($res)==1)
		{
			//$fila=mysql_fetch_array($res);
			$fila=mysql_fetch_row($res);
			echo "<table border=1 bordercolor='#e5e5e5' style='font : normal normal small-caps 12px Tahoma;'>";
			$NumCampos=mysql_num_fields($res);
			for($n=0;$n<$NumCampos;$n++)
			{	
				if(mysql_field_name($res,$n)=="Entidad"){
					$fila[$n]=$Aseguradores["$fila[$n]"][0]." ".$Aseguradores["$fila[$n]"][1]." ".$Aseguradores["$fila[$n]"][2]." ".$Aseguradores["$fila[$n]"][3];
				}
				//if($Departamentos["$fila[$n]"]){$fila[$n]=$Departamentos["$fila[$n]"];}
				$i++;
				echo "<td bgcolor='#e5e5e5' style='font-weight:bold'>".str_replace("_"," ",mysql_field_name($res,$n))."</td>";
				echo "<td>".$fila[$n]." &nbsp;</td>";	
				if($i>=3){echo "<tr>";$i=0;}
			}
			if(!$Identificacion){$Identificacion=$fila['Identificacion'];}
			?>
			</table>
			<input type="button" name="Volver" value="Volver" onClick="location.href='BusquedaAfiliados.php?DatNameSID=<? echo $DatNameSID?>'" />
            <input type="button" value="Reporte de Inconsistencias" 
            onClick="location.href='InformeIncons.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>'" />
			<?
		}
		else
		{
			if(mysql_num_rows($res)>1){
				echo "<table border=1 bordercolor='#e5e5e5' style='font : normal normal small-caps 12px Tahoma;'>";
				echo "<tr bgcolor='#e5e5e5'><td>Identificacion</td><td>Nombres</td></tr>";					
				while($fila=mysql_fetch_array($res))
				{?>
					<tr style="cursor:hand" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"
					onclick="location.href='BusquedaAfiliados.php?Buscar=1&DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $fila['Identificacion']?>&PApellido=<? echo $fila['Primer_Apellido']?>&SApellido=<? echo $fila['Segundo_Apellido']?>&PNombre=<? echo $fila['Primer_Nombre']?>&SNombre=<? echo $fila['Segundo_Nombre']?>&Id=<? echo $fila['id']?>'">                	
						<td><? echo $fila['Identificacion']?></td>
						<td><? echo $fila['Primer_Apellido']." ".$fila['Segundo_Apellido']." ".$fila['Primer_Nombre']." ".$fila['Segundo_Nombre']?></td>
					</tr>
			<?	}	
			}
			else{
				$conex = pg_connect("dbname=sistema user=apache password=Server*1982") or die ('no establecida');
				$cons="select numinforme from reportes3047.inc_bd where compania='$Compania[0]' order by numinforme desc";
				$res=pg_query($conex,$cons);
				$fila=pg_fetch_row($res);
				$NumInf=$fila[0]+1;	?>
				<table border=1 bordercolor='#e5e5e5' style='font : normal normal small-caps 12px Tahoma;' >
                <tr bgcolor='#e5e5e5'><td>No Se Encontraron Registros Que Coincidan Con Los Criterios de Busqueda</td></tr>			
                <tr>
                	<td align="center">
                		<input type="button" name="Volver" value="Volver" onClick="location.href='BusquedaAfiliados.php?DatNameSID=<? echo $DatNameSID?>'" />
                		<input type="button" value="Reporte de Inconsistencias"  onClick="location.href='InformeIncons.php?DatNameSID=<? echo $DatNameSID?>&NoFind=1'">
                   	</td>
              	</tr>					
<?			}			
		}
	}?>

<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
