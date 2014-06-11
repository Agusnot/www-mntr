<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar){
		if(!$Edit){
			$cons="insert into salud.interprogramas(compania,interprograma,cargo) values ('$Compania[0]','$Interprograma','$Cargo')";
			$res=ExQuery($cons);
		}
		else{
			$cons="update salud.interprogramas set compania='$Compania[0]',interprograma='$Interprograma',cargo='$Cargo' where compania='$Compania[0]' and interprograma='$InterprogramaAnt' and cargo='$CargoAnt'";
			//echo $cons;
			$res=ExQuery($cons);
		}?>
		<script language="javascript">
		location.href='ConfInterprogramas.php?DatNameSID=<? echo $DatNameSID?>';
		</script><?		
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
function validar(){
	if(document.FORMA.Interprograma.value==""){
		alert("Debe digitar una Interconsulta");return false;
	}
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return validar()">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2"> 	
	<tr><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Interconsulta</td><td><input type="text" name="Interprograma" value="<? echo $Interprograma?>" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)"></td></tr>
    <tr><td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Cargo</td>
    	<td><select name="Cargo">
     <?		$cons="select cargos from salud.cargos where compania='$Compania[0]' order by cargos";
	 		echo $cons;
			$res=ExQuery($cons);
	 		while($fila=ExFetch($res)){			
				if($Cargo==$fila[0]){
		 			echo "<option value='$fila[0]' selected>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
	 		}?>
    	</select></td>
    </tr>
    <tr><td colspan="4" align="center"><input type="submit" name="Guardar" value="Guardar"><input type="button" value="Cancelar" onClick="location.href='ConfInterprogramas.php?DatNameSID=<? echo $DatNameSID?>'"></td></tr>
</table>
<input type="hidden" name="InterprogramaAnt" value="<? echo $Interprograma?>">
<input type="hidden" name="CargoAnt" value="<? echo $Cargo?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>        
<body>
</body>
</html>
