<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if(!$Anio){
		$ND=getdate();
		$Anio=$ND[year];
	}
	if($Eliminar)
	{		
		$cons="Delete from salud.topescopago where anio='$Anio' and Compania='$Compania[0]' and id='$Id'";
		$res=ExQuery($cons);echo ExError();		
	}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center"> 
<tr align="center">	
	<td align="center" colspan="11">Año  
 <?	$cons="select anio from central.anios where compania='$Compania[0]'";
 	$res=ExQuery($cons);echo ExError();?>
    <select name="Anio" onChange="document.FORMA.submit()">
<?	while($row = ExFetchArray($res)){
		if($Anio==$row[0]){
			echo "<option value='$row[0]' selected>$row[0]</option>";
		}
		else{
			echo "<option value='$row[0]'>$row[0]</option>";
		}
	}?>
    </select> 
	<input type="button" value="Nuevo" onClick="location.href='NewTopesCopago.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>'"/>
    </td>
</tr>
<? 	$cons="select ambito from salud.ambitos where compania='$Compania[0]'";
	$res=ExQuery($cons);echo ExError();
	while($row = ExFetchArray($res)){
		$cons2="select * from salud.topescopago where compania='$Compania[0]' and anio=$Anio and ambito='$row[0]' order by tipoasegurador,tipousuario,nivelusu,clase,tipocopago";
		$res2=ExQuery($cons2);echo ExError();
		if(ExNumRows($res2)){ $ban==1;?>
	        <tr><td></td></tr>
			<tr>
    			<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="9"><? echo $row[0]?></td>	
		    </tr>
    	    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
        		<td>Tipo Asegurador</td><td>Tipo Usuario</td><td>Nivel</td><td>Clase</td><td>Tipo Copago</td><td>Valor</td><td>Tope Anual</td><td>Tope x Evento</td><td colspan="2">
	        </tr>        
<?			while($row2 = ExFetchArray($res2)){
				echo "<tr><td>$row2[3]</td><td>$row2[2]</td><td>$row2[4]</td><td>$row2[5]</td><td>$row2[8]</td><td align='right'>".number_format($row2[7],2)."</td><td align='right'>".number_format($row2[9],2)."</td><td align='right'>".number_format($row2[11],2)."</td>";?>
                <td><img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" onClick="location.href='NewTopesCopago.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Id=<? echo $row2[10]?>&Anio=<? echo $Anio?>'"></td>
                <td><img title="Eliminar" style="cursor:hand" onClick="if(confirm('Desea eliminar este registro?')){location.href='TopesCopago.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Id=<? echo $row2[10]?>';}" src="/Imgs/b_drop.png"></td>
                </tr>
		<?	}
		}
	}
	if($ban==''){
		
	}?>
</table>
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
</body>
</html>
