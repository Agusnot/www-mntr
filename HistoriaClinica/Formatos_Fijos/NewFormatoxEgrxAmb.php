<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	//echo $DatNameSID;
	if($Guardar){
		$cons="insert into salud.formatosegreso (compania,ambito,tipoformato,formato,fechacrea,usucrea) values
		('$Compania[0]','$Ambito','$TipoFormato','$Formato','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$usuario[1]')";	
		$res=ExQuery($cons);?>
		<script language="javascript">
			location.href="FormatosxEgrxAmb.php?DatNameSID=<? echo $DatNameSID?>";
		</script>
<?	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.TipoFormato.value==""){alert("Debe selecionar el tipo de formato!!!");return false;}
		if(document.FORMA.Formato.value==""){alert("Debe selecionar el formato!!!");return false;}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">  
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
	<tr><td colspan="2" align="center"  bgcolor="#e5e5e5" style="font-weight:bold">NUEVO FORMATO EGRESO</td></tr>
    <tr>
    	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Tipo Formato</td>
        <td>
        <?	$cons="select Ambito from salud.ambitos where compania='$Compania[0]' and ambito!='Sin Ambito' order by ambito";
			$res=ExQuery($cons);?>
            <select name="Ambito">
           	<?	while($fila=ExFetch($res))
				{
					if($fila[0]==$Ambito){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
					else{echo "<option value='$fila[0]'>$fila[0]</option>";}
				}?>
            </select>
        </td>
    </tr>
    <tr>
    	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Tipo Formato</td>
        <td>
        <?	$cons="select tipoformato from historiaclinica.formatos where compania='$Compania[0]' 
			group by tipoformato order by tipoformato";
			$res=ExQuery($cons);?>
            <select name="TipoFormato" onchange="document.FORMA.submit()">
            	<option></option>
           	<?	while($fila=ExFetch($res))
				{
					if($fila[0]==$TipoFormato){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
					else{echo "<option value='$fila[0]'>$fila[0]</option>";}
				}?>
            </select>
        </td>
    </tr>
     <tr>
    	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Formato</td>
        <td>
        <?	$cons="select formato from historiaclinica.formatos where compania='$Compania[0]' and tipoformato='$TipoFormato'
			and formato not in (select formato from salud.formatosegreso where compania='$Compania[0]' and ambito='$Ambito' and tipoformato='$TipoFormato')
			group by formato order by formato";
			$res=ExQuery($cons);?>
            <select name="Formato" onchange="document.FORMA.submit()">
            	<option></option>
           	<?	while($fila=ExFetch($res))
				{
					if($fila[0]==$Formato){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
					else{echo "<option value='$fila[0]'>$fila[0]</option>";}
				}?>
            </select>
        </td>
    </tr>
    <tr align="center">
    	<TD colspan="2">
        	<input type="submit" name="Guardar" value="Guardar" />
        	<input type="button" value="Cancelar" onclick="location.href='FormatosxEgrxAmb.php?DatNameSID=<? echo $DatNameSID?>'"/>
       	</TD>
	</tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
</form>    
</body>
</html>