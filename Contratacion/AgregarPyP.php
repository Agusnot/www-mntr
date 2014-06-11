<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Guardar){
		$cons="delete from contratacionsalud.pypcontratos where compania='$Compania[0]' and entidad='$Entidad' and contrato='$Contrato' and nocontrato='$Numero'";
		$res=ExQuery($cons);
		while( list($cad,$val) = each($Programa)){			
			$cons="insert into contratacionsalud.pypcontratos (compania,usuario,fecha,programa,entidad,contrato,nocontrato) values 
			('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$cad,'$Entidad','$Contrato','$Numero')";
			$res=ExQuery($cons);
			//echo $cons;
		}
		?><script language="javascript">
			location.href="NewContratos.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&Numero=<? echo $Numero?>&Edit=1'";
      	</script><?
	}
	$cons="select nombre,numprograma from pyp.programas where compania='$Compania[0]'";
	$res=ExQuery($cons);
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">

<table  BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" > 
	<tr>
    	<td colspan="8" bgcolor="#e5e5e5" style="font-weight:bold" align="center">DATOS BASICOS</td>
    </tr>
<?	while($fila=ExFetch($res)){
		echo "<tr><td>";
		$cons2="select programa from contratacionsalud.pypcontratos where compania='$Compania[0]' and programa=$fila[1] and entidad='$Entidad' and contrato='$Contrato'
		and nocontrato='$Numero'";
		$res2=ExQuery($cons2);
		if(ExNumRows($res2)>0){
			echo "<input type='checkbox' name='Programa[$fila[1]]' id='$fila[1]' checked>";
		}
		else{
			echo "<input type='checkbox' name='Programa[$fila[1]]' id='$fila[1]'>";
		}		
		echo " $fila[0]</td></tr>";
	}?>    
    <tr align="center">
    	<td colspan="8">
        	<input type="submit" value="Guardar" name="Guardar">
        	<input type="button" value="Cancelar" 
            onClick="location.href='NewContratos.php?DatNameSID=<? echo $DatNameSID?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&Numero=<? echo $Numero?>&Edit=1'">
      	</td>
    </tr>
</table>
<input type="hidden" value="Entidad" name="<? echo $Entidad?>">
<input type="hidden" value="Contrato" name="<? echo $Contrato?>">
<input type="hidden" value="Numero" name="<? echo $Numero?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>"> 
</form>    
</body>
</html>
