<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if(!$FiltroEstado){$FiltroEstado="AC";}
	if($Eliminar)
	{
		$cons="Delete from ContratacionSalud.contratos where Entidad='$Entidad' and Contrato='$Contrato' and Numero='$Numero' and Compania='$Compania[0]'";
		$res=ExQuery($cons);echo ExError();	 //echo $cons;
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">		
	function VerEjecucion(e,Entidad,Contrato,Nocontrato){			
		x = e.clientX;
		y = e.clientY;
		st = document.body.scrollTop;
		frames.FrameOpener.location.href="VerExeContra.php?&DatNameSID=<? echo $DatNameSID?>&Entidad="+Entidad+"&Contrato="+Contrato+"&Nocontrato="+Nocontrato;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=(y)+st-100;
		document.getElementById('FrameOpener').style.left=100;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='800';
		document.getElementById('FrameOpener').style.height='300';						
	}
	function VerxFacturar(e,Entidad,Contrato,Nocontrato){			
		x = e.clientX;
		y = e.clientY;
		st = document.body.scrollTop;
		frames.FrameOpener.location.href="VerxFactContra.php?&DatNameSID=<? echo $DatNameSID?>&Entidad="+Entidad+"&Contrato="+Contrato+"&Nocontrato="+Nocontrato;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=(y)+st-100;
		document.getElementById('FrameOpener').style.left=100;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='950';
		document.getElementById('FrameOpener').style.height='400';						
	}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table  BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
	<tr>
    	<td colspan="4" bgcolor="#e5e5e5" style="font-weight:bold" align="center">Filtrar</td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Entidad</td><td><select name="FiltroEntidad" onChange="document.FORMA.submit()"><option></option>
        <? 	
			$cons="Select identificacion,primape,segape,primnom,segnom  from Central.Terceros,Contratacionsalud.Contratos 
			where Terceros.identificacion=Contratos.entidad and Terceros.Compania='$Compania[0]' and Contratos.Compania='$Compania[0]' 
			group by  identificacion,primape,segape,primnom,segnom
			order by primape,segape,primnom,segnom asc";
			$result=ExQuery($cons); echo ExError();
			while($row = ExFetch($result))
			{
				if($FiltroEntidad==$row[0]){?>
        		<option value="<? echo $row[0]?>" selected><? echo "$row[1] $row[2] $row[3] $row[4]"?></option>
            <? }
				else{?>
                <option value="<? echo $row[0]?>"><? echo "$row[1] $row[2] $row[3] $row[4]"?></option>
             <? }   
			}?>
        </select></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Estado</td><td><select name="FiltroEstado" onChange="document.FORMA.submit()">
        	<? 
			if($FiltroEstado=='AN')
			{?>
            <option value="AC"> Activo</option>
            <option value="AN" selected> Inactivo</option>            
            <? }else { ?>            
            <option value="AC" selected> Activo</option>
            <option value="AN"> Inactivo</option>            
            <? } ?>
        </select></td>
    </tr>
</table>
<br/>
<table  BORDER=1  style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" align="center">
	<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    	<td>Entidad</td><td>Contrato</td><td>Numero</td><td>Fecha Inicio</td><td>Fecha Final</td><td>Monto</td><td>Ejecucion</td><td>x Facturar</td>
        <td>Consumo</td><td>Saldo</td><td>Estado</td><td colspan="2"></td>
    </tr>
    <?  if($FiltroEntidad){$CondAdc=" and Entidad='$FiltroEntidad' ";}
		if($FiltroEstado){$CondAdc2=" and estado='$FiltroEstado' ";}
		$consulta="Select primape,segape,primnom,segnom,contrato,numero,fechaini,fechafin,monto,estado,entidad,consumcontra
		from ContratacionSalud.Contratos,central.terceros 
		where terceros.compania='$Compania[0]' and Contratos.Compania='$Compania[0]' 
		and entidad=identificacion $CondAdc $CondAdc2 order by primape,segape,primnom,segnom,contrato,numero";
		$result=ExQuery($consulta);
		
		while($row = ExFetchArray($result)) 
		{ 	
			$consFac="select sum(total),entidad,contrato,nocontrato from facturacion.facturascredito 
			where compania='$Compania[0]' and entidad='$row[10]' and contrato='$row[4]' and nocontrato='$row[5]' and estado='AC' group by entidad,contrato,nocontrato";						
			$resFac=ExQuery($consFac);
			$filaFac=ExFetch($resFac);	
			$consLiq="select sum(total) from facturacion.liquidacion 
			where compania='$Compania[0]' and pagador='$row[10]' and contrato='$row[4]' and nocontrato='$row[5]' and estado='AC' and nofactura is null";
			//echo $consLiq;
			$resLiq=ExQuery($consLiq);
			$filaLiq=ExFetch($resLiq);		
			if($row[9]=='AC'){$estadoc='Activo';} else{$estadoc='Inactivo';}?>
          	<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">  
		<?	$Saldo=$row[8]-$row[11]-$filaFac[0]-$filaLiq[0];
			echo "<td>$row[0] $row[1] $row[2] $row[3]</td><td>$row[4]</td>
			<td>$row[5]</td><td align='center'>$row[6]</td><td align='center'>$row[7]</td>
			<td align='right'>".number_format($row[8],2)."</td>";?>
            
			<td align='right' title="Ver Facturas" style="cursor:hand" onClick="VerEjecucion(event,'<? echo $row[10]?>','<? echo $row[4]?>','<? echo $row[5]?>')">
            
		<?	echo number_format($filaFac[0],2);?>
			</td><td align='right' title="Ver Liquidaciones" style="cursor:hand" onClick="VerxFacturar(event,'<? echo $row[10]?>','<? echo $row[4]?>','<? echo $row[5]?>')">
		<?	echo number_format($filaLiq[0],2)."</td>
			<td align='right'>".number_format($row[11],2)."</td><td align='right'>".number_format($Saldo,2)."</td><td align='center'>$estadoc</td><td>"; ?>
				<img src="/Imgs/b_edit.png" style="cursor:hand" onClick="location.href='NewContratos.php?DatNameSID=<? echo $DatNameSID?>&Edit=1&Entidad=<? echo "$row[10]"?>&Contrato=<? echo $row[4]?>&Numero=<? echo $row[5]?>'"></td>
          	<td>
           	<?	if($filaFac[0]){?>
            		<img src="/Imgs/b_drop_gray.png" style="cursor:hand" title="Eliminar">
            <?	}
				else{?>
					<img style="cursor:hand" onClick="if(confirm('Desea eliminar este registro?')){location.href='Contratos.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Entidad=<? echo  "$row[10]"?>&Contrato=<? echo $row[4]?>&Numero=<? echo $row[5]?>';}" src="/Imgs/b_drop.png">
          	<?	}?>
       		</td>	
            </tr>         
	<? }?>
    		
    <tr>
    	<td colspan="15" align="center"><input type="button" onClick="location.href='NewContratos.php?DatNameSID=<? echo $DatNameSID?>'" value="Nuevo"></td>
    </tr>
</table>
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge">  
</body>
</html>
