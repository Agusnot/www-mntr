<?
	include("Funciones.php");	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
?>
<style>body{background:<?echo $Estilo[1]?>;color:<?echo $Estilo[2]?>;font-family:<?echo $Estilo[3]?>;font-size:12;font-style:<?echo $Estilo[5]?>}</style>
<style>
	a{color:white;text-decoration:none;}
	a:hover{color:yellow;text-decoration:underline;}
</style>
<table border="1" bordercolor="<?echo $Estilo[1]?>" style="font-size:11px;font-family:<?echo $Estilo[3]?>">
<?php
		$cons3="Select CodigoFUT,Nombre,Detalle,Tipo from Presupuesto.CodigosFUT where ClaseFUT='$Tipo' Order By CodigoFUT";
		$res3=ExQuery($cons3);
		while($fila3=ExFetch($res3))
		{?>
		<tr><td style="cursor:hand"<?if($fila3[3]=="Detalle"){ ?> onclick="this.style.backgroundColor='#e5e5e5';parent.document.FORMA.CodigoFUT.value='<?echo $fila3[0]?>'" ><? }
			$NumCar=strlen($fila3[0])-3;
			for($i=0;$i<=$NumCar;$i++){echo "&nbsp;&nbsp;";}
			if($fila3[3]=="Titulo"){echo "<img src='/Imgs/menost.gif'><img src='/Imgs/carpabiertat.gif'>&nbsp;";}
			else{echo "<img src='/Imgs/puntosut.gif'><img src='/Imgs/doct.gif'>&nbsp;";}
			if($fila3[3]=="Detalle"){?><a name="<?echo $fila3[0]?>"><?}
			if($Codigo==$fila3[0]){echo "<font color='yellow'><strong>";}
			echo "$fila3[0] $fila3[1]";		
			echo "</font></strong>";
			echo "<br></a>";

		}
?>
</table>