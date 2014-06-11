<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
        $nocodi=explode("|",$AutoId);
?>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
</script>
<?
	if($Evaluar)
	{
		if(count($nocodi)==1)
                {
                    $cons = "Update Infraestructura.Mantenimiento set FechEvaluacion = '$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
                    EstadoSolicitud='Evaluado', Evaluacion='$Evaluacion' 
                    Where Compania='$Compania[0]' and AutoId=$AutoId and (EstadoSolicitud='Revisado' or EstadoSolicitud='Evaluado')";    
                }
                else
                {
                    $cons = "Update Infraestructura.Mantenimiento set FechEvaluacion = '$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
                    EstadoSolicitud='Evaluado', Evaluacion='$Evaluacion'
                    Where Compania='$Compania[0]' and AutoId=0 and (EstadoSolicitud='Revisado' or EstadoSolicitud='Evaluado')
                    and descripcion='$nocodi[0]' and Tercero='$nocodi[1]' and CC='$nocodi[2]' and SubUbicacion='$nocodi[3]' and FechaSolicitud='$nocodi[4]'";
                }
		$res = ExQuery($cons);
		?>
		<script language="javascript">
        	CerrarThis();
        </script>
		<?
	}
	
?>
<body background="/Imgs/Fondo.jpg">
<div align="right">
<button name="Cerrar" title="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" /></button>
</div>
<?
	if(count($nocodi)==1){$cons = "Select Evaluacion From Infraestructura.Mantenimiento Where Compania='$Compania[0]' and AutoId=$AutoId and EstadoSolicitud='Evaluado'";}
        else
        {
            $cons = "Select Evaluacion From Infraestructura.Mantenimiento Where Compania='$Compania[0]' and AutoId=0 and EstadoSolicitud='Evaluado'
            and descripcion='$nocodi[0]' and Tercero='$nocodi[1]' and CC='$nocodi[2]' and SubUbicacion='$nocodi[3]' and FechaSolicitud='$nocodi[4]'";
        }
	$res = ExQuery($cons);
	$fila = ExFetch($res);
	$Evaluacion = $fila[0];
?>
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="AutoId" value="<? echo $AutoId?>" />
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
<tr bgcolor="#e5e5e5" align="center"><td colspan="2" style="font-weight:bold">Evaluacion de Mantenimiento</td></tr>
<tr bgcolor="#e5e5e5"><td>Excelente</td><td><input type="radio" name="Evaluacion" value="Excelente" 
<? if($Evaluacion=="Excelente"){ echo " checked";}?> /></td></tr>
<tr bgcolor="#e5e5e5"><td>Bueno</td><td><input type="radio" name="Evaluacion" value="Bueno"
<? if($Evaluacion=="Bueno"){ echo " checked";}?>  /></td></tr>
<tr bgcolor="#e5e5e5"><td>Regular</td><td><input type="radio" name="Evaluacion" value="Regular"
<? if($Evaluacion=="Regular"){ echo " checked";}?>  /></td></tr>
<tr bgcolor="#e5e5e5"><td>Malo</td><td><input type="radio" name="Evaluacion" value="Malo"
<? if($Evaluacion=="Malo"){ echo " checked";}?>  /></td></tr>
</table>
<input type="submit" value="Evaluar" name="Evaluar" />
</form>
</body>