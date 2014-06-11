<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Eliminar){
		$cons="delete from facturacion.descuentosliq where compania='$Compania[0]' and cedula='$Paciente[1]' and noliquidacion is null";
		$res=ExQuery($cons);
	}
	$cons="select noliquidacion,fechacrea,nombre,motivo,numservicio
	from facturacion.descuentosliq,central.usuarios where compania='$Compania[0]' and descuentosliq.cedula='$Paciente[1]' and 
	usuarios.usuario=descuentosliq.usuario";
	//echo $cons;
	$res=ExQuery($cons);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function NuevoDescuento(e,mod)	
	{
		x = e.clientX;
		y = e.clientY; 
		st = document.body.scrollTop;
		frames.FrameOpener.location.href="NewDescuentoLiq.php?DatNameSID=<? echo $DatNameSID?>&Edit="+mod;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=(y)+st+10;
		document.getElementById('FrameOpener').style.left=x;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='300px';
		document.getElementById('FrameOpener').style.height='230px';
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post"> 
<?	if($ND[mon]<10){$cero='0';}else{$cero='';}
	if($ND[mday]<10){$cero1='0';}else{$cero1='';}
	$FechaCompActua="$ND[year]-$cero$ND[mon]-$cero1$ND[mday]";
	if($Paciente[48]!=$FechaCompActua){echo "<em><center><br><br><br><br><br><font size=5 color='BLUE'>La Hoja de Identificacion no se ha guardado!!!";exit;}	
if($Paciente[1]){?>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
		<td>Liquidacion</td><td>Fecha</td><td>Usuario</td><td>Motivo</td><td colspan="2"></td>
	</tr>
<?	while($fila=ExFetch($res)){
		$fila[1]=substr($fila[1],0,10);
		 	if($fila[0]){?>
				<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" align="center" title="Ver Liquidacion"
                	onclick="open('VerLiqGuadada.php?DatNameSID=<? echo $DatNameSID?>&NoLiquidacion=<? echo $fila[0]?>&Ced=<? echo $Paciente[1]?>','','width=800,height=600,scrollbars=YES')">
     	<? 	}
			else{?>
            	<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" align="center">
		<?	}?>
        	<td><? echo $fila[0]?>&nbsp;</td><td><? echo $fila[1]?></td><td><? echo $fila[2]?></td><td><? echo $fila[3]?></td>         
            
      	<? 	if($fila[0]){?>
        		<td><img src="/Imgs/b_edit.png" style="cursor:hand" onClick="alert('No se puede editar este registro debido a que esta ligado a una liquidacion')" title="Editar"> </td>
        		<td>
					<img style="cursor:hand"  title="Eliminar" onClick="alert('No se puede eliminar este registro debido a que esta ligado a una liquidacion')" src="/Imgs/b_drop.png">
             	</td>
     	<? 	}
			else{?>
            	<td><img src="/Imgs/b_edit.png" style="cursor:hand" onClick="NuevoDescuento(event,'1')" title="Editar"> </td>
            	<td>
            		<img style="cursor:hand"  title="Eliminar" onClick="if(confirm('Desea eliminar este registro?')){location.href='DescuentosLiq.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1';}" src="/Imgs/b_drop.png">
              	</td>
		<?	}?>          		
        </tr>	
<?		if($fila[0]==''){$Ban=1;}
	}?>
	<tr align="center">
<?	if($Ban==1){?>    
		<td colspan="6"><input type="button" value="Nuevo" onClick="alert('El usuario ya tiene una autorizacion de descuento sin asignar!!!');"/></td>
<?	}
	else{?>
		<td colspan="6"><input type="button" value="Nuevo" onClick="NuevoDescuento(event)"/></td>
<?	}?>        
	</tr>
</table><?
}
else{
		echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>No hay un paciente seleccionado!!! </b></font></center>";
}?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" > 
</iframe> 
</body>
</html>
