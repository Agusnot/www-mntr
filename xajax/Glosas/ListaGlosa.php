<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>
<?	if($Elimina)
	{
	$cons="DELETE FROM facturacion.motivoglosa where Compania='$Compania[0]' and tipoglosa='$TipoGlosa' and nufactura='$NoFac'";
	$res=ExQuery($cons);	
    echo ExError($res);
		if($res==1){echo "<em>Registro Eliminado!</em>";}		
	}	
	if($Save1){
$cons="Update facturacion.respuestaglosa 
Set  compania='$Compania[0]',numero='$NumeroGlosa',vrtotal='$VrFac', vrglosatotal='$GlosaTot',fecharasis='$FechaGlosa',usuarioglosa='$usuario[1]',nufactura='$NoFac',
pagaipsglosa=$faltante where nufactura='$NoFac'";
		$res=ExQuery($cons);
		echo ExError($res);	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
function Validar()
{ if(document.FORMA.NumeroGlosa.value==""){alert("Debe Ingresar el Numero de las glosas"); document.FORMA.NumeroGlosa.focus(); return false;} 
if(document.FORMA.FechaGlosa.value==""){alert("debe seleccionar una fecha"); document.FORMA.FechaGlosa.focus(); return false;}
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<? $cons="SELECT tipoglosa,observacionglosa,vrtotal,vrglosa,nufactura FROM facturacion.motivoglosa where nufactura='$NoFac' ";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){?>
	<table  style='font : normal normal small-caps 12px Tahoma;' border="0.5" bordercolor="#e5e5e5" cellpadding="2" align="center">    	
		<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">	
        	<td width="20">&nbsp;</td>			
        	<td width="78">Tipo Glosa</td>
        	<td width="237">Observaciones</td>
        	<td width="92">Valor Total</td>
        	<td width="97">Valor Glosa</td>
        	<td width="74">Nº Factura</td>
        	<td width="30"></td>
       	    <?	while($fila=ExFetch($res)){
				?>	
	  <tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
                	<td><img src="../Imgs/b_check.png">                  	</td>
                	<td align="center" style="cursor:hand" title="Ver" >
						<? echo $fila[0]?>                  	</td>
                    <td align="center"><? echo $fila[1]?></td>
					<td align="center"><? echo number_format($fila[2],2)?></td>
					<td align="center"><? echo number_format($fila[3],2)?></td>
                    <td align="right"><? echo $NoFac ?></td>
                    <td align="right">  <button onClick="if(confirm('Dese eliminar La Glosa?\n')){location.href='ListaGlosa.php?DatNameSID=<? echo $DatNameSID?>&Elimina=1&TipoGlosa=<? echo $fila[0]?>&NoFac=<? echo $NoFac?>';}"><img src="/Imgs/b_drop.png"></button>                    </td>                   
                </tr>
        <?	}?>
      	</tr>
		<tr>
		<td colspan="4" ></td>			
		<td style="font-weight:bold" >Total Valor Glosas</td>
		<td  style="font-weight:bold" >Valor Restante</td>			
		</tr>
		<tr>
		 <?
			$con= "SELECT vrglosa,vrtotal FROM facturacion.motivoglosa where  nufactura='$NoFac' ";
					$resx= ExQuery($con);
					while($fila3=ExFetch($resx))
					{
						if ($fila3[0]>=1)
						{	$Total = $Total + $fila3[0];
							$faltante= 	$fila3[1];
							$faltante= $faltante -  $Total;										
						}						
					} ?>
		<td colspan="4"></td><td align="center">		
							
               <?
					echo number_format($Total,2);	?>
					<input type='hidden'  name='GlosaTot' readonly  value=" <? echo $Total ?>" size='8'>	
					</td>
				<td align='center'><font color='green'><? echo $faltante ?></font></td>	
				<input type='hidden' name='faltante' value=" <? echo $faltante ?>">
		</tr>
		<tr align="center">	
		<td colspan="6">Numero Glosa&nbsp;&nbsp;
        
        <?	
$consul1="select numero from facturacion.respuestaglosa where nufactura='$NoFac'and numero is not null Order By numero DESC limit 1 ";	
$res1=ExQuery($consul1);
$result=ExFetch($res1);	
echo"<input type='text'  name='NumeroGlosa'  style='width:65px' value='$result[0]'>"; ?>       
&nbsp;&nbsp;Fecha Glosa &nbsp;&nbsp;
		  <input type="Text" name="FechaGlosa"  readonly onClick="popUpCalendar(this, FORMA.FechaGlosa, 'yyyy-mm-dd')" style="width:100px"></td>		
		</tr>
		<tr align="center">
		
		<td colspan="6"> 
		<button type="submit" name="Save1">Guardar</button>
		</td>
		</tr>
	</table>
<?	}
	else{?>
    	<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">  
        	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>No hay Glosas que cumpan con los parametros de la busqueda</td></tr>
		</table>
<?	}
?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>					 
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>          
</body>
</html>
