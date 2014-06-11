<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
        $nocodi=explode("|",$AutoId);
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		//parent.document.getElementById('FrameOpener').style.top='1px';
		//parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
	function Validar()
	{
		if(document.FORMA.Nota.value == ""){alert("Para Cerrar el caso escriba una NOTA");return false;}	
	}
</script>
<?
	if($Guardar)
	{
		if(count($nocodi)==1)
                {
                    $cons = "Update Infraestructura.Mantenimiento set FechaCierreCaso='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
                    NotaCierre='$Nota', EstadoSolicitud='Cerrado' Where Compania='$Compania[0]' and AutoId=$AutoId and EstadoSolicitud='Evaluado'";
                }
                else
                {
                    $cons = "Update Infraestructura.Mantenimiento set FechaCierreCaso='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
                    NotaCierre='$Nota', EstadoSolicitud='Cerrado' Where Compania='$Compania[0]' and AutoId=0 and EstadoSolicitud='Evaluado'
                    and Descripcion='$nocodi[0]' and Tercero = '$nocodi[1]' and CC='$nocodi[2]' and SubUbicacion='$nocodi[3]' and FechaSolicitud='$nocodi[4]'";
                }
		$res = ExQuery($cons);
		?><script language="javascript">frames.parent.document.FORMA.submit();</script><?
	}
	if(count($nocodi)==1)
        {
            $cons = "Select NotaCierre from Infraestructura.Mantenimiento Where Compania='$Compania[0]' and AutoId=$AutoId and EstadoSolicitud='Cerrado'
            order by FechaCierreCaso desc";
        }
        else
        {
            $cons = "Select NotaCierre from Infraestructura.Mantenimiento Where Compania='$Compania[0]' and AutoId=0 and EstadoSolicitud='Cerrado'
            and Descripcion='$nocodi[0]' and Tercero = '$nocodi[1]' and CC='$nocodi[2]' and SubUbicacion='$nocodi[3]' and FechaSolicitud='$nocodi[4]'
            order by FechaCierreCaso desc";
        }
        $res = ExQuery($cons);
	$fila = ExFetch($res);
	$Nota = $fila[0];
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="AutoId" value="<? echo $AutoId?>" />
<div align="right">
<button name="Cerrar" title="Cerrar" onClick="CerrarThis()"><img src="/Imgs/b_drop.png" /></button>
</div>
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" width="100%">
<tr bgcolor="#e5e5e5" align="center" style="font-weight:bolder"><td>Nota de Cierre de Caso</td></tr>
<tr><td><textarea name="Nota" rows="8" style="width:100%" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"><? echo $Nota;?></textarea></td></tr>
</table>
<input type="submit" name="Guardar" value="Guardar" />
</form>
</body>