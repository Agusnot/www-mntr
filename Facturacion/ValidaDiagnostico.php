<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener2').style.position='absolute';
		parent.document.getElementById('FrameOpener2').style.top='1px';
		parent.document.getElementById('FrameOpener2').style.left='1px';
		parent.document.getElementById('FrameOpener2').style.width='1';
		parent.document.getElementById('FrameOpener2').style.height='1';
		parent.document.getElementById('FrameOpener2').style.display='none';
	}
</script>	
<?
if($CodDx){?>
	<script language="javascript">
		
			parent.parent.document.getElementById('<? echo $NameCod ?>').value="<? echo $CodDx?>";
			parent.parent.document.getElementById('<? echo $NameNom ?>').value="<? echo $NomDx?>";
			parent.CerrarThis();
	</script>
<?

}?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;'>
<? 
if($Nombre==''&&$Codigo!=''){
	$cons="select * from salud.cie where codigo ilike '$Codigo%' ";
}
else{
	if($Nombre!=''&&$Codigo==''){
		$cons="select * from salud.cie where diagnostico ilike '%$Nombre%'";
	}
	else{
		if($Nombre!=''&&$Codigo!=''){
			$cons="select * from salud.cie where diagnostico ilike '%$Nombre%' and codigo ilike '$Codigo%'";
		}
	}	
}
//echo $cons;
if($Codigo!=''||$Nombre!=''){

	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{?>
    
	<tr><td><a href="ValidaDiagnostico.php?DatNameSID=<? echo $DatNameSID?>&Codigo=<? echo $Codigo?>&Nombre=<? echo $Nombre?>&NameCod=<? echo $NameCod?>&NameNom=<? echo $NameNom?>&CodDx=<? echo $fila[1]?>&NomDx=<? echo $fila[0]?>"><? echo $fila[1]?></a></td>
    <td><a href="ValidaDiagnostico.php?DatNameSID=<? echo $DatNameSID?>&Codigo=<? echo $Codigo?>&Nombre=<? echo $Nombre?>&NameCod=<? echo $NameCod?>&NameNom=<? echo $NameNom?>&CodDx=<? echo $fila[1]?>&NomDx=<? echo $fila[0]?>"><? echo $fila[0]?></a> </td></tr>
    
<? 	}
}
?>

<tr><td></td></tr>
</table>

</body>
</html>
