<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar){
		if(!$Edit){
			$cons="insert into salud.valormulta (compania,tipoasegurador,valor) values ('$Compania[0]','$TipoAsegurador',$ValorMulta)";
			$res=ExQuery($cons);
			?>
				<script language="javascript">location.href="ConfValorMulta.php?DatNameSID=<? echo $DatNameSID?>";</script>
			<?
		}
		else{
			$cons="select tipoasegurador from salud.valormulta where compania='$Compania[0]' and tipoasegurador='$TipoAsegurador' and tipoasegurador!='$TipoAseguradorAnt'";			
			$res=ExQuery($cons);
			if(ExNumRows($res)>0){
				?><script language="javascript">alert("El tipo de asegurador ya tiene un valor para la muta!!!");</script><?
			}
			else{
				$cons2="update salud.valormulta set tipoasegurador='$TipoAsegurador',valor=$ValorMulta where compania='$Compania[0]' and tipoasegurador='$TipoAseguradorAnt'";
				//echo $cons2;
				$res2=ExQuery($cons2);
				?>
					<script language="javascript">location.href="ConfValorMulta.php?DatNameSID=<? echo $DatNameSID?>";</script>
				<?
			}
		}		
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function validar()
	{
		if(document.FORMA.ValorMulta.value==""){
			alert("Debe digitar el valor de la multa!!!");return false;
		}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return validar()">  
<?	if($Edit==1){
		$cons2="select tipo from central.tiposaseguramiento order by tipo";		 	
	}
	else{
		$cons2="select tipo from central.tiposaseguramiento where tipo not in (select tipoasegurador from salud.valormulta where compania='$Compania[0]') order by tipo";		 	
		
	}
	$res2=ExQuery($cons2);echo ExError();
?>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">      
	<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Tipo Asegurador</td></tr>
    <tr align="center">
    	<td><select name="TipoAsegurador">		 
		<?	while($fila2 = ExFetchArray($res2)){
				if($fila2[0]==$TipoAsegurador){
					echo "<option value='$fila2[0]' selected>$fila2[0]</option>";
				}
				else{
					echo "<option value='$fila2[0]'>$fila2[0]</option>";
				}
			}?>
    	</select></td>
    </tr>
    <tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Valor Multa</td></tr>
    <tr>
    	<td align="center"><input type="text" name="ValorMulta" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" value="<? echo $ValorMulta?>"></td>
    </tr>
    <tr align="center">
    	<td><input type="submit" name="Guardar" value="Guardar"><input type="button" value="Cancelar" onClick="location.href='ConfValorMulta.php?DatNameSID=<? echo $DatNameSID?>'"</td>
    </tr>
</table>
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" name="TipoAseguradorAnt" value="<? echo $TipoAsegurador?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>     
</body>
</html>
