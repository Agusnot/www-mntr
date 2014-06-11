<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
if($Elimina)
{
$cons="DELETE FROM facturacion.motivoglosa where Compania='$Compania[0]' and tipoglosa='$TipoGlosa' and nufactura='$NoFac'";
$res=ExQuery($cons);	
echo ExError($res);
if($res==1){echo "<em>Registro Eliminado!</em>";}		
}	
if($Save1){
$cons="Update facturacion.respuestaglosa 
Set  compania='$Compania[0]',numero='$NumeroGlosa',vrtotal='$VrFac', vrglosatotal='$GlosaTot',fecharasis='$FechaGlosa',fechanotificacion='$FechaNoti',usuarioglosa='$usuario[1]',nufactura='$NoFac',
restante=$faltante where nufactura='$NoFac'";
		$res=ExQuery($cons);	
?> <script language="javascript">alert("La Informacion se ha registrado correctamente!!!");</script>
<?
echo ExError($res);
}
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
if(document.FORMA.FechaNoti.value==""){alert("Debe seleciona una fecha de notificacion"); document.FORMA.FechaNoti.focus(); return false}
}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<? $cons="SELECT tipoglosa,observacionglosa,vrtotal,vrglosa,nufactura FROM facturacion.motivoglosa where nufactura='$NoFac' ";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){?>
	<table width="905" border="2" align="center" cellpadding="2" bordercolor="#e5e5e5"   style='font : normal normal small-caps 12px Tahoma;'>    	
		<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">	
        			
        	<td width="91">Codigo Glosa</td>
        	<td width="472">Observaciones</td>
        
        	<td width="245">Valor Glosa</td>
        	
        	<td width="62"></td>
       	    <?	while($fila=ExFetch($res)){
				?>	
	  <tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
                	
                	<td align="center" style="cursor:hand" title="Ver" >
						<? echo $fila[0]?>                  	</td>
                    <td align="center"><? echo $fila[1]?></td>
				
					<td align="center"><? echo number_format($fila[3],2)?></td>
                    
                    <td align="right">  <button 
 onClick="if(confirm('Dese eliminar La Glosa?\n')){location.href='ListaGlosa.php?DatNameSID=<? echo $DatNameSID?>&Elimina=1&TipoGlosa=<? echo $fila[0]?>&NoFac=<? echo $NoFac?>';}"><img src="/Imgs/b_drop.png">
       </button>   </td>                   
                </tr>
        <?	}?>
      	</tr>
		<tr>
		<td colspan="2" ></td>			
		<td bgcolor="#e5e5e5" style="font-weight:bold">Total Valor Glosas</td>
		<td width="62" bgcolor="#e5e5e5" style="font-weight:bold">Valor restante</td>			
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
		<td colspan="2">
		<?
$consulta="select nufactura FROM facturacion.respuestaglosa where compania='$Compania[0]'";
$respuesta=ExQuery($consulta);
while($row=ExFetch($respuesta))
{
echo $Row[0];
}	
		?>	
		</td><td align="center">						
               <?	echo number_format($Total,2);	?>
					<input type='hidden'  name='GlosaTot' readonly  value=" <? echo $Total ?>" size='8'>	
					</td>
				<td align='center'><font color='green'><? echo number_format($faltante,2) ?></font></td>	
				<input type='hidden' name='faltante' value=" <? echo $faltante ?>">
		</tr>
		<tr align="center">
		  <td colspan="6">
          <?
		  $consu="SELECT numero,fecharasis,fechanotificacion FROM facturacion.respuestaglosa WHERE compania='$Compania[0]' AND nufactura='$NoFac'";
		  $respu= ExQuery($consu);
		  if(ExNumRows($res)>0){
			   while($row=ExFetch($respu)){
				  ?> 
   <table width="698" height="30"  style='font : normal normal small-caps 12px Tahoma;' border="2" bordercolor="#e5e5e5" cellpadding="2" align="center">
    <tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">	
        	<td width="185" height="22">Numero Documento</td>			
        	<td width="241">fecha Creacion de Documento</td>
            <td width="242">fecha Recibo Notificacion</td>
          </tr>
            <tr align="center">
				<td width="185" height="22"><? echo $row[0] ?></td> 
                <td width="241"><? echo $row[1] ?></td>
            <td width="242"><? echo $row[2] ?></td>   
          </table>     
				  <p>
				    <? }  } ?>
		    </p>
		  <p>&nbsp;				                    </p></td>
	  </tr>
		<tr align="center">        
		<td colspan="6"  bgcolor="#e5e5e5" style="font-weight:bold">        
        <b>Ingrese Informacion En Los Campos !!!</b><br></br>
        Numero De Documento  
		      
 <?	
$consul1="select numero,fecharasis,fechanotificacion from facturacion.respuestaglosa 
where nufactura='$NoFac'and numero is not null Order By numero DESC limit 1 ";	
$res1=ExQuery($consul1);
$result=ExFetch($res1);	
echo"<input type='text'  name='NumeroGlosa'  style='width:70px' value='$result[0]'>";
 ?>       
&nbsp;Creacion Documento: &nbsp;
 <input type="Text" name="FechaGlosa"  readonly onClick="popUpCalendar(this, FORMA.FechaGlosa, 'yyyy-mm-dd')" style="width:75px" value="<? echo $result[1] ?>">
 &nbsp;Recibo de la Notificacion: &nbsp;
 <input type="Text" name="FechaNoti"  readonly onClick="popUpCalendar(this, FORMA.FechaNoti, 'yyyy-mm-dd')" style="width:75px" value="<? echo $result[2] ?>">   
          </td>		
		</tr>
		<tr align="center">		
		<td colspan="6"> 
		<button type="submit" name="Save1" onClick="location.href=VerGlosas.php.reload()" >Guardar</button>
		</td>
		</tr>
	</table>
<? } else{?>
   	  <table style='font : normal normal small-caps 12px Tahoma;' border="2" bordercolor="#e5e5e5" cellpadding="2" align="center">  
        	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>No hay Glosas que cumpan con los parametros de la busqueda</td></tr>
		</table>
<? } ?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>					 
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>          
</body>
</html>
