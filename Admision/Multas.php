<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$cons="select nombre,usuario from central.usuarios";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Usus[$fila[1]]=$fila[0];
	}
	if($Eliminar){
		$cons="delete from salud.multas where compania='$Compania[0]' and entidad='$EPS' and fechacrea='$Fech' and cedula='$Ced' and valor=$Valor";
		//echo $cons;
		$res=ExQuery($cons);
	}
	$cons="select super from central.usuarios where usuario='$usuario[1]'";
	$res=ExQuery($cons);
	$fila=Exfetch($res); $Super=$fila[0];
	$cons="select identificacion,primnom,segnom,primape,segape from central.terceros where compania='$Compania[0]' and tipo='Asegurador'";	
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Aseguradores[$fila[0]]="$fila[1] $fila[2] $fila[3] $fila[4]";
		//echo "$fila[0]=$fila[1] $fila[2] $fila[3] $fila[4]";
	}
	
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function LevMulta(e,Ced,Fecha,Valor,EPS)
	{		
		y = e.clientY; 
		st = document.body.scrollTop;
		frames.FrameOpener.location.href='LevatarMulta.php?DatNameSID=<? echo $DatNameSID?>&Cedula='+Ced+'&Fecha='+Fecha+'&Valor='+Valor+'&EPS='+EPS;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=y-40+st;
		document.getElementById('FrameOpener').style.left='60px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='550';
		document.getElementById('FrameOpener').style.height='370';
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return validar()">  
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">       
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Nombre</td><td>Identificacion</td><td>Entidad</td><td>Usuario Cancelador</td><td>Fecha Cancelacion</td>
   	<? if($Estado=="AN"){ echo "<td>Usurio Lenvanto</td><td>Usuario Registra Lenvantamiento</td><td>Fecha Levantamiento</td><td>Origen Levantamiento</td><td>Motivo Levantamiento</td>"; }?>
    <? if($Estado=="PG"){ echo "<td>Usuario Registra Pago</td><td>Fecha Pago</td>"; }?>
	    <td>Valor</td>
	<? if($Estado=="AC"){?><td></td><? }?>
	    
    </tr>
<?
	if($Estado=="Todas"){$Est="";}
	else{$Est="and multas.estado='$Estado'";}
	if($Estado=="AN"){$Est1=",usulev,levanta,fechalevanta,origenlev,motivolev";}
	if($Estado=="PG"){$Est1=",usupago,fechapago";}
	if($Entidad){$Ent="and entidad='$Entidad'";}
	if($Cedula){$Ced="and multas.cedula='$Cedula'";}
	$cons="select primnom,segnom,primape,segape,multas.cedula,multas.entidad,usuarios.nombre,multas.fechacrea,multas.valor $Est1 from salud.multas,central.terceros,central.usuarios
	where multas.compania='$Compania[0]' and terceros.compania='$Compania[0]' and multas.cedula=terceros.identificacion  and usuarios.usuario=multas.usuario $Est $Ent $Ced
	group by primnom,segnom,primape,segape,multas.cedula,multas.entidad,multas.fechacrea,multas.valor,usuarios.nombre $Est1
	order by primnom,segnom,primape,segape";
	//echo $cons;
	$res=ExQuery($cons); 
	while($fila=ExFetch($res))
	{
	?>
		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
        	<td><? echo "$fila[0] $fila[1] $fila[2] $fila[3]";?></td><td><? echo $fila[4]?></td><td><? echo $Aseguradores[$fila[5]]?></td><td><? echo $fila[6]?></td>
            <td><? echo $fila[7]?></td>
      	<?	if($Estado=="AN"){echo "<td>$fila[10]</td><td>".$Usus[$fila[9]]."</td><td>$fila[11]</td><td>$fila[12]</td><td>$fila[13]</td>";}?>
        <?	if($Estado=="PG"){echo "<td>".$Usus[$fila[9]]."</td><td>$fila[10]</td>";}?>
            <td align="right"><? echo  number_format($fila[8],2)?></td>
        <?	if($Super==1&&$Estado=="AC"){?>
        		<td>
                	<img title="Levantar Multa" style="cursor:hand" 
	            	onClick="LevMulta(event,'<? echo $fila[4]?>','<? echo $fila[7]?>','<? echo $fila[8]?>','<? echo $fila[5]?>');" src="/Imgs/b_drop.png">
                </td>
		<?	}?>
        </tr>	
<?		$Total=$Total+$fila[8];
	}
?>   
	<tr>
    	<td <? if($Estado=="Todas"){?>colspan="5"<? } if($Estado=="AN"){?>colspan="10"<? }if($Estado=="AC"){?> colspan="5"<? }if($Estado=="PG"){?> colspan="7"<? }?> align="right">
        	<strong>Total</strong></td> 
        <td><? echo number_format($Total,2);?></td>       
    </tr> 
    <tr>
    	<td colspan="15" align="center">
        	<input type="button" value="Imprimir" onClick="open('RptMultas.php?DatNameSID=<? echo $DatNameSID?>','','width=1100,height=600,scrollbars=yes')" style="width:95px">
            <input type="button" value="Consolidado" onClick="open('RptCosolidadoMultas.php?DatNameSID=<? echo $DatNameSID?>','','width=1100,height=600')">
        </td>
    </tr>
</table> 
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">   
</form>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
</body>
</html>
