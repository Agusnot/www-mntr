<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar){
		if($TopeAnual==''){$TopeAnual=0;}
		if(!$Edit)
		{
			$cons3 = "Select id from Salud.topescopago where Compania = '$Compania[0]' order by id desc";					
			$res3 = ExQuery($cons3);
			$fila3 = ExFetch($res3);			
			$AutoId = $fila3[0] +1;
			$cons="Insert into Salud.topescopago(compania,anio,tipousuario,tipoasegurador,nivelusu,clase,ambito,valor,tipocopago,topeanual,id) values ('$Compania[0]','$Anio','$TipoUsuario','$TipoAsegurador','$Nivel','$Clase','$Ambito',$Valor,'$TipoCopago',$TopeAnual,$AutoId)";						
		}
		else
		{			
			$cons="Update Salud.topescopago set compania='$Compania[0]',anio='$Anio',tipousuario='$TipoUsuario',tipoasegurador='$TipoAsegurador',nivelusu='$Nivel',clase='$Clase',ambito='$Ambito',valor='$Valor',tipocopago='$TipoCopago',topeanual='$TopeAnual' where Compania='$Compania[0]' and id='$Id'";			
		}
		$res=ExQuery($cons);echo ExError();
		?>
        <script language="javascript">
	       location.href='TopesCopago.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>';
        </script>        
        <?	
	}
	if($Edit){
		$cons="select * from salud.topescopago where compania='$Compania[0]' and id='$Id'";
		$res=ExQuery($cons);echo ExError();
		$fila=ExFetchArray($res);
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
function validar(){	
	if(document.FORMA.Valor.value==''){
		alert("El campo Valor no debe quedar vacio!!!");return false;
	}	
}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return validar()">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
<tr><td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="8">Nuevo Tope Copago <? echo $Anio?></td></tr>
<tr>
	<td bgcolor="#e5e5e5" style="font-weight:bold">Proceso</td>
    <td>
    <select name="Ambito">
 <?	$cons2="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";
 	$res2=ExQuery($cons2);echo ExError();
	while($fila2 = ExFetchArray($res2)){
		if($fila2[0]==$fila[6]){
			echo "<option value='$fila2[0]' selected>$fila2[0]</option>";
		}
		else{
			echo "<option value='$fila2[0]'>$fila2[0]</option>";
		}
	}?>
    </select>
    </td>
	<td bgcolor="#e5e5e5" style="font-weight:bold">Tipo Asegurador</td>
    <td>
    <select name="TipoAsegurador">
 <?	$cons2="select tipo from central.tiposaseguramiento order by tipo";
 	$res2=ExQuery($cons2);echo ExError();
	while($fila2 = ExFetchArray($res2)){
		if($fila2[0]==$fila[3]){
			echo "<option value='$fila2[0]' selected>$fila2[0]</option>";
		}
		else{
			echo "<option value='$fila2[0]'>$fila2[0]</option>";
		}
	}?>
    </select>
    </td>
</tr>
<tr>
    <td bgcolor="#e5e5e5" style="font-weight:bold">Tipo Usuario</td>
    <td>
    <select name="TipoUsuario">
 <?	$cons2="select tipo from salud.tiposusuarios order by tipo";
 	$res2=ExQuery($cons2);echo ExError();
	while($fila2 = ExFetchArray($res2)){
		if($fila2[0]==$fila[2]){
			echo "<option value='$fila2[0]' selected>$fila2[0]</option>";
		}
		else{
			echo "<option value='$fila2[0]'>$fila2[0]</option>";
		}
	}?>
    </select>
    </td>    
	<td bgcolor="#e5e5e5" style="font-weight:bold">Nivel</td>
    <td>
    <select name="Nivel">
 <?	$cons2="select nivel from salud.nivelesusu order by nivel";
 	$res2=ExQuery($cons2);echo ExError();
	while($fila2 = ExFetchArray($res2)){
		if($fila2[0]==$fila[4]){
			echo "<option value='$fila2[0]' selected>$fila2[0]</option>";
		}
		else{
			echo "<option value='$fila2[0]'>$fila2[0]</option>";
		}
	}?>
    </select>
    </td>
</tr>
<tr>  
    <td bgcolor="#e5e5e5" style="font-weight:bold">Clase</td>
    <td>
    <select name="Clase"> <?	
	echo "<option value='Fijo'>Fijo</option>";
 	if($fila[5]=='Porcentual'){
 		echo "<option value='Porcentual' selected>Porcentual</option>";
	}
	else{
		echo "<option value='Porcentual'>Porcentual</option>";
	}?>
    </select>
    </td>  

	<td bgcolor="#e5e5e5" style="font-weight:bold">Tipo Copago</td>
    <td>
    <select name="TipoCopago">
 <?	$cons2="select tipocopago from salud.tipocopago order by tipocopago";
 	$res2=ExQuery($cons2);echo ExError();
	while($fila2 = ExFetchArray($res2)){
		if($fila2[0]==$fila[8]){
			echo "<option value='$fila2[0]' selected>$fila2[0]</option>";
		}
		else{
			echo "<option value='$fila2[0]'>$fila2[0]</option>";
		}
	}?>
    </select>
    </td>  
</tr>
<tr>    
    <td bgcolor="#e5e5e5" style="font-weight:bold">Valor</td>
    <td><input type="text" name="Valor" value="<? echo $fila[7]?>" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)"></td>        
	<td bgcolor="#e5e5e5" style="font-weight:bold">Tope Anual</td>
	<td><input type="text" name="TopeAnual" value="<? echo $fila[9]?>" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)"></td>
</tr>
<tr>
	<td colspan="8" align="center"><input type="submit" value="Guardar" name="Guardar"><input type="button" value="Cancelar" onClick="location.href='TopesCopago.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>'"></td>
</tr>
</table>
<input type="hidden" name="Anio" value="<? echo $Anio?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>
