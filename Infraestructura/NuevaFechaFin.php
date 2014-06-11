<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
	if($Guardar)
	{
		$cons="Update Infraestructura.Ubicaciones set FechaFin = '$FechaFin'
		Where Compania='$Compania[0]' and AutoId = $AutoId and FechaIni = '$FechaIni'";
		//echo $cons;
		$res=ExQuery($cons);		
	?>	<script language="javascript">
			parent.document.FORMA.submit();	
			CerrarThis();
     	</script><?
	}
?>
</head>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
	function Validar()
	{		
		if(document.FORMA.FechaFin.value==""){
			alert("Debe Seleccionar la Fecha Final");return false;
		}
		else{
			if(document.FORMA.FechaIni.value>=document.FORMA.FechaFin.value){
				alert("La fecha final debe ser mayor a la fecha inicial !!!");return false;
			}
		}
	}
</script>
<body background="/Imgs/Fondo.jpg">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="AutoId" value="<? echo $AutoId;?>">
<?
	$cons = "Select FechaIni from Infraestructura.Ubicaciones Where Compania='$Compania[0]' and AutoId = $AutoId order by FechaIni Desc";
	$res = ExQuery($cons);
	$fila = ExFetch($res);
	$FechaIni = $fila[0];
?>
<input type="hidden" name="FechaIni" value="<? echo $FechaIni;?>" />

<table border="1" style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" > 
	<tr>
    	<td align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Fecha Fin</td>                
    </tr>    
    <tr>
    	<td><input type="text" readonly name="FechaFin" onClick="popUpCalendar(this, FORMA.FechaFin, 'yyyy-mm-dd')"></td>
    </tr>
    <tr align="center">
    	<td><input type="submit" name="Guardar" value="Guardar"></td>
    </tr>
</table>
</form>
</body>
</html>
