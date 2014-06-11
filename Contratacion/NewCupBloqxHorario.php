<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<?
if($Codigo || $Nombre)
{
	$cons = "Select Codigo,Nombre from Contratacionsalud.Cups where Compania = '$Compania[0]' and
	Codigo like '$Codigo%' and Nombre ilike '%$Nombre%' and Codigo in(Select codigo from  contratacionsalud.cupsxconsulextern where  Compania='$Compania[0]') 	
	order by Nombre";			
	//echo $cons;
	$res = ExQuery($cons);
	if(ExNumRows($res)>0)
	{?>
		<table  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">
        	<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold"><td>Codigo</td><td>Nombre</td>
      	<?	while($fila=ExFetch($res))
			{?>
				<tr onMouseOver="this.bgColor='#00CCFF'" onMouseOut="this.bgColor=''" style="cursor:hand" title="Seleccionar"
                onclick="if(confirm('¿Esta seguro de agregar esta restriccion?')){
               		parent.location.href='CupBloqueoxHorario.php?DatNameSID=<? echo $DatNameSID?>&Codigo=<? echo $Codigo?>&Nombre=<? echo $Nombre?>&TMPCOD=<? echo $TMPCOD?>&Restric=<? echo $Restric?>&CodRestric=<? echo $fila[0]?>';}">
                	<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td>
                </tr>
		<?	}?>
		</table>
<?	}
}?>
        
</form>
</body>
</html>