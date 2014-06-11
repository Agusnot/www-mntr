<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()"> 
<?
if($Ver){
	if($TipoGLosa){$TGlosa="and tipoglosa='$TipoGlosa'";} 
	if($ObservacionGlosa){$Obs="and observacionglosa='$ObservacionGlosa'";}
	if($VrTGlosa){$TGlosa="and vrtotal='$VrTGlosa'"; }
	if($VrGlosa){$vrGlo="and vrglosa='$VrGlosa'";}
	if($FechaGlosa){$FecGlosa="and fechaglosa='$FechaGlosa'";}	
	$cons="select nufactura,tipoglosa,observacionglosa,vrtotal,vrglosa,fechaglosa,numradicacion FROM facturacion.motivoglosa";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){?>
	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">    	
		<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">	
        	<td>*</td>			
        	<td>Tipo Glosa</td><td>Observacion</td><td>Valor total </td><td>Valor Glosa</td><td>fecha Glosa</td><td>Vr Glosa</td><td>Nota Glosa</td>
       	<?	while($fila=ExFetch($res)){
				$Fec=explode(" ",$fila[1]);?>	
       			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
                	<td>*</td>
                	<td align="center" style="cursor:hand" title="Ver">
						<? echo $fila[0]?>
                  	</td>
                    <td align="center"><? echo $Fec[0]?></td><td align="center"><? echo $fila[4]?></td><td align="center"><? echo $fila[2]?></td>
                    <td align="right"><? echo number_format($fila[3],2)?></td><td align="right">&nbsp;<? if($fila[6]) {echo number_format($fila[6],2);}?></td>
                    <td>&nbsp;<font style="font-size:9px"><? echo "$fila[5]"?></font> <? echo "<br>$fila[7]"?></td>
                </tr>
        <?	}?>
      	</tr>
	</table>
<?	}
	else{?>
    	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
        	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>No hay Glosas que cumpan con los parametros de la busqueda</td></tr>
		</table>
<?	}} ?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
