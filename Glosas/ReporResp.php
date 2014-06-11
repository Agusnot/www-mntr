<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
?>

<?
if($Guardar) {
	$cons1="Update facturacion.respuestaglosa 
	Set usuariorespuesta='$usuario[1]',aceptaglosa='$VAceptado',pagaipsglosa=$Objetado,pagarips='$ValorEPS'
	 where nufactura='$NoFac'";
		$resul=ExQuery($cons1);	
				echo ExError();	}	
		
		?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>


</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
  <table  style='font : normal normal small-caps 12px Tahoma;' border="2" bordercolor="#e5e5e5" cellpadding="2"  align="center">
  <tr>
   
    <td width="206" colspan="1" align="center" bgcolor="#e5e5e5" style="font-weight:bold">Total Glosa</td>
    <td colspan="3" align="center" bgcolor="#e5e5e5" style="font-weight:bold">valor Aceptado</td>
  </tr>
  <tr>
  
    <? $conx= "SELECT vrtotal,vrglosa,aceptaglosa FROM facturacion.motivoglosa where  nufactura='$NoFac' ";
					$resx= ExQuery($conx);
					while($fil=ExFetch($resx))
					{	if ($fil[0]>=1)	{
						$Total = $Total + $VrFac;
						$TotalAceptado= $TotalAceptado + $fil[2];
						$faltante= 	$fil[0];
							$faltante= $faltante -  $Total;													 
                           $Objetado= $Total - $TotalAceptado;
						   $valoreps= $VrFac - $TotalAceptado;						
						}}					
					?>
    <td colspan="0"  align="center"><input name="TotalGlosa" type="hidden" size="8" disabled value="<? echo $Total?>">
      <? echo number_format($Total,2)?></td>
    <td width="194" colspan="0" align="center"><input name="VAceptado" type="hidden" size="8" value=" <? echo $TotalAceptado ?>">
      <? echo number_format($TotalAceptado,2)?>
      <input name="TotFac" type="hidden" size="5"  value="<? echo $faltante?>">
      <input name="Objetado" type="hidden" size="5"  value="<? echo $Objetado ?>">
      <input name="ValorEPS" type="hidden" size="5"  value="<? echo $valoreps ?>"></td>
  </tr>
  <tr>
    <td colspan="6" align="center"><font color="green" ><b>Nota: Recuerde Guardar Los cambion Presionando Click en el boton inferior!!!</b></font></td>
  </tr>
  <tr>
    <td align="center" colspan="8"><button type="submit" name="Guardar" onClick="location.href=VerRtaGlosas.php.reload()" > Guardar</button></td>
  </tr>
  </table>
  <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>					 
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>          
</body>
</html>
