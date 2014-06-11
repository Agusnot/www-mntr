<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Eliminar)
	{
		if($Clase=='CUPS'){
			$cons="Delete from contratacionsalud.cupsxplanservic where Cup='$Cup' and Autoid=$Autoid and Compania='$Compania[0]'";		
			$res=ExQuery($cons);echo ExError();
		}
		else{
			if($Eliminar!=2){
			$cons="Delete from contratacionsalud.medsxplanservic where codigo='$Codigo' and Autoid=$Autoid and Compania='$Compania[0]' and Almacenppal='$Almacenppal'";					
			$res=ExQuery($cons);echo ExError();
			}
		}
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function MaxyMin(Clase,Autoid){
		frames.FrameOpener.location.href="PorcentajesMaxyMin.php?DatNameSID=<? echo $DatNameSID?>&Clase="+Clase+"&Autoid="+Autoid;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='140';
		document.getElementById('FrameOpener').style.left='30%';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='300px';
		document.getElementById('FrameOpener').style.height='120px';
	}
	function EditarCup(e,Codigo,Clase,Almacen)
	{	
		y = e.clientY; 
		st = document.body.scrollTop;		
		frames.FrameOpener.location.href='EditCupPlanServ.php?DatNameSID=<? echo $DatNameSID?>&Codigo='+Codigo+'&Clase='+Clase+'&Plan=<? echo $Autoid?>&Almacen='+Almacen;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=y-70+st;
		document.getElementById('FrameOpener').style.left='20px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='950';
		document.getElementById('FrameOpener').style.height='170';		
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<?
	
	if($Autoid)
	{	
		if($Clase=='CUPS'){
			$cons = "select codigo,nombre,reqvobo,facturable,minimos,maximos from contratacionsalud.cupsxplanservic,contratacionsalud.cups where codigo=cup and autoid='$Autoid' and 										
			clase='$Clase' and contratacionsalud.cups.compania = '$Compania[0]' and contratacionsalud.cupsxplanservic.compania='$Compania[0]' order by nombre";					
		}
		elseif($Clase=="Medicamentos"){		
			$cons = "select codigo1,(nombreprod1||' '||unidadmedida||' '||presentacion) as nomb,reqvobo,facturable,minimos,maximos,contratacionsalud.medsxplanservic.almacenppal
			from contratacionsalud.medsxplanservic,consumo.codproductos
			where codigo=codproductos.codigo1 and medsxplanservic.autoid=$Autoid and consumo.codproductos.compania = '$Compania[0]' and medsxplanservic.compania='$Compania[0]'
			and codproductos.almacenppal=medsxplanservic.almacenppal 
			group by codigo1,codigo,nomb,reqvobo,facturable,minimos,maximos,contratacionsalud.medsxplanservic.almacenppal  order by nomb";	 				
		}
		//echo $cons;
		$res = ExQuery($cons);
		
?><table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">
	<tr><? if(!$Eliminar||$Eliminar==1){?>
    <td colspan="8" align="center"><input style="width:180px" type="button" value="Retirar Plan" onClick="if(confirm('Desea eliminar el Plan?')){parent.location.href='PlanesServicio.php?DatNameSID=<? echo $DatNameSID?>&Clase=<? echo $Clase?>&Eliminar=2&Autoid=<? echo $Autoid?>';}">
<? if($Clase=='Medicamentos'){?>
	    <input type="button" value="Agregar Medicamentos" onClick="parent.location.href='AgregarMedicamentosxPlanServ.php?DatNameSID=<? echo $DatNameSID?>&Clase=<? echo $Clase?>&Autoid=<? echo $Autoid?>'"><? 
	}else{?>
    	<input type="button" value="Agregar CUPS" onClick="parent.location.href='AgregarCupsxPlanServ.php?DatNameSID=<? echo $DatNameSID?>&Clase=<? echo $Clase?>&Autoid=<? echo $Autoid?>'">
		<input type="button" value="Modificar Max y Min" onClick="MaxyMin('<? echo $Clase?>','<? echo $Autoid?>')">
		<?	
	}?></td>
	<? }?>
    </tr>
<?	
		if(ExNumRows($res)>0)
		{			
?>		
		    <tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
    		<td>Codigo</td><td>Nombre</td><td>Req. Visto Bueno</td><td>Facturable</td><td>Minimos</td><td>Maximos</td><td colspan="2"></td>
    	</tr>
        <?  while($fila = ExFetch($res))
			{			
    		?>	<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"> <?
				echo "<td>$fila[0]</td><td>$fila[1]</td><td>"; if($fila[2]==1){echo "Si";}else{echo "No";} echo"</td><td>";if($fila[3]==1){echo "Si";}else{echo "No";} echo "</td><td>$fila[4]</td><td>$fila[5]</td>";				?>
				<td width="16px">
           			<img title="Editar" style="cursor:hand" src="/Imgs/b_edit.png" onClick="EditarCup(event,'<? echo $fila[0]?>','<? echo $Clase?>','<? echo $fila[6]?>')">
            	</td><?
				if($Clase=='CUPS'){
					?>                    
                   	<td width="16px"><a href="#" onClick="if(confirm('Desea eliminar el registro?')){location.href='BusquedaCUPSxPlanServ.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Cup=<? echo $fila[0]?>&Autoid=<? echo $Autoid?>&Clase=<? echo $Clase?>';}">
                <? }
				else{?>
                	<td width="16px"><a href="#" onClick="if(confirm('Desea eliminar el registro?')){location.href='BusquedaCUPSxPlanServ.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1&Almacenppal=<? echo $fila[6]?>&Codigo=<? echo $fila[0]?>&Autoid=<? echo $Autoid?>&Clase=<? echo $Clase?>';}">
              <? }?>
			<img title="Eliminar" border="0" src="/Imgs/b_drop.png"/></td></a>					
				<? 
				
				echo "</tr>";
			}
		?> </table>	<? 	
		} 
	} ?>
    <input type="hidden" name="Clase" value="<? echo $Clase?>">
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
</body>
</html>
