<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$cons="select cup from salud.tmpcupsordenesmeds where cedula='$Paciente[1]' and tmpcod='$TMPCOD2'";
	$res=ExQuery($cons);
	$banD=0;
	while($fila=ExFetch($res))
	{
		$cons2="select dx from contratacionsalud.dxrestriccups where compania='$Compania[0]' and cup ='$fila[0]'";
		$res2=ExQuery($cons2);
		if(ExNumRows($res2)>0){
			if($banD==0)
			{
				$banD=1;
				$cons3="select dx from contratacionsalud.dxrestriccups where compania='$Compania[0]' and cup = '$fila[0]'";
			}
			else{
				$cons3=$cons3." intersect select dx from contratacionsalud.dxrestriccups where compania='$Compania[0]' and cup = '$fila[0]'";
			}
		}
	}
	//echo $cons3;
	
	if($cons3){$DxRestric=" and codigo in ($cons3)";}
	
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
	$cons="select * from salud.cie where codigo ilike '$Codigo%' $DxRestric order by codigo,diagnostico";
}
else{
	if($Nombre!=''&&$Codigo==''){
		$cons="select * from salud.cie where diagnostico ilike '$Nombre%' $DxRestric order by codigo,diagnostico";
	}
	else{
		if($Nombre!=''&&$Codigo!=''){
			$cons="select * from salud.cie where diagnostico ilike '$Nombre%' and codigo ilike '$Codigo%' $DxRestric order by codigo,diagnostico";
		}
	}	
}
//echo $cons;
if($Codigo!=''||$Nombre!=''){

	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		/*if($DxRestric[$fila[1]])
		{?>
			<tr style="cursor:hand" title="Asignar" onClick="alert('Este diagnostico no esta autorizado para este procedimiento!!!')">		
	<?	}
		else
		{*/?>    
        	<tr onClick="
            if('<? echo $TMPCOD2?>'){
            	parent.parent.document.FORMA.CodDiagnostico1.value='<? echo $fila[1]?>';              
                parent.parent.document.FORMA.NomDiagnostico1.value='<? echo $fila[0]?>';              
                parent.CerrarThis();
            }
            else{location.href='ValidaDiagnostico.php?DatNameSID=<? echo $DatNameSID?>&Codigo=<? echo $Codigo?>&Nombre=<? echo $Nombre?>&NameCod=<? echo $NameCod?>&NameNom=<? echo $NameNom?>&CodDx=<? echo $fila[1]?>&NomDx=<? echo $fila[0]?>';}" style="cursor:hand" title="Seleccionar">
 	<?	//}?>
            <td><? echo $fila[1]?></td>
            <td><? echo $fila[0]?></td>
        </tr>    
<? 	}
}
?>

<tr><td></td></tr>
</table>

</body>
</html>
