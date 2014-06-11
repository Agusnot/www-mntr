<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");

?>
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
</script>	
<?
	if($CedDef)
	{		
		if($Buscar==''){
		?>
        <script language="javascript">
			parent.location.href='CitaxReasignarAgenda.php?DatNameSID=<? echo $DatNameSID?>&CedulaSel=<? echo $CedDef?>&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&Fecha=<? echo $Fecha?>&HrIni=<? echo $HrIni?>&MinIni=<? echo $MinIni?>&Tiempo=<? echo $Tiempo?>&HrFin=<? echo $HrFin?>&MinFin=<? echo $MinFin?>';
			CerrarThis();
		</script>
        <?
		}
		else{
		?>
        <script language="javascript">
			parent.location.href='BuscarCita.php?DatNameSID=<? echo $DatNameSID?>&CedulaSel=<? echo $CedDef?>';
			CerrarThis();
		</script>
        <?
		}
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;'>
<?
	$cons="Select Identificacion,PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Compania='$Compania[0]' and Identificacion ilike '$Ced%'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{?>
		<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"><td><? echo $fila[0]?></td><td><a href="ValidaDocReasignar.php?DatNameSID=<? echo $DatNameSID?>&Buscar=<? echo $Buscar?>&CedDef=<? echo $fila[0]?>&Ced=<? echo $Ced?>&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&Fecha=<? echo $Fecha?>&HrIni=<? echo $HrIni?>&MinIni=<? echo $MinIni?>&Tiempo=<? echo $Tiempo?>&HrFin=<? echo $HrFin?>&MinFin=<? echo $MinFin?>"><? echo "$fila[1] $fila[2] $fila[3] $fila[4]"?></a></td></tr>
<?	}
?>
</table>
</body>
</html>
