<?
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		if($Edit)
		{
			foreach($_POST as $nombre_campo => $valor)
			{
				if($nombre_campo!="Tabla" && $nombre_campo!="Guardar" && $nombre_campo!="Criterio" && $nombre_campo!="Edit")
				{
					$Consulta=$Consulta.$nombre_campo."='".$valor."',";
				}
			}
			$Consulta=substr($Consulta,0,strlen($Consulta)-1);
			$cons="Update $Tabla set $Consulta where $Criterio Limit 1";
		}
		else
		{
			foreach($_POST as $nombre_campo => $valor)
			{
				if($nombre_campo!="Tabla" && $nombre_campo!="Guardar" && $nombre_campo!="Criterio" && $nombre_campo!="Edit")
				{
					$Campos=$Campos.$nombre_campo.",";
					$Valores=$Valores."'".$valor."',";
				}
			}
			$Campos=substr($Campos,0,strlen($Campos)-1);
			$Valores=substr($Valores,0,strlen($Valores)-1);
			$cons="Insert into $Tabla ($Campos) values ($Valores)";
		}
		$res=ExQuery($cons);
		if($res==1){?>
		<script language="JavaScript">
			location.href='AdministrarTablas.php?Tabla=<?echo $Tabla?>';
		</script>
		<?}
		else{echo "Error->".ExError();}
	}
?>

<script language="JavaScript">
	function Validar()
	{
		var inputs = document.all.tags("input"); 
		for(i=0;i<inputs.length;i++)
		{ 
			if(inputs[i].type == "text" && inputs[i].value=="")
			{ 
				alert("Debe diligenciar el campo " + inputs[i].name);
				inputs[i].focus();
				return false;
			} 
		} 
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onsubmit="return Validar()">
<table border="1" bordercolor="#ffffff" background="/Imgs/encabezado.jpg" border='1' cellspacing='0' style='font : normal normal small-caps 13px Tahoma;'>
<?
	if($Edit){$Criterio=str_replace("|","'",$Criterio);}
	else{$Criterio=1;}
	$cons="Select * from $Tabla where $Criterio";
	$res=ExQuery($cons);
	if($Edit){$fila=ExFetch($res);}
	for($i=0;$i<=mysql_num_fields($res)-1;$i++)
	{
		echo "<tr>";
		echo "<td style='font-weight:bold;color:white'>".mysql_field_name($res,$i)."</td>";
		$Tipo=mysql_field_type($res,$i);
		$Long=(mysql_field_len($res,$i))*5;
		echo "<td>";
		if($Tipo=="string" || $Tipo=="int")
		{
			echo "<input type='Text' name='". mysql_field_name($res,$i)."' style='width:$Long px' value='$fila[$i]'>";
		}
		echo "</td>";
		echo "</tr>";
	}
	echo "<tr><td colspan=2 align='center'><input type='Submit' name='Guardar' value='Guardar'></td></tr>";
	echo "<input type='Hidden' name='Tabla' value='$Tabla'>";?>
	<input type='Hidden' name='Edit' value="<?echo $Edit?>">
	<input type='Hidden' name='Criterio' value="<? echo $Criterio?>">
	</table>
</form>
</body>