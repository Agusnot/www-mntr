<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	session_register("TerceroSel");
	include("Funciones.php");
	$ND=getdate();
	if($RegTercero)
	{
		if($RegTercero==-1){$RegTercero=0;}
		$TerceroSel=$RegTercero;
	}
?>
<style>
a{color:black;text-decoration:none;}
a:hover{color:blue;text-decoration:underline;}
</style>
<style>body{background:<?echo $Estilo[6]?>;color:<?echo $Estilo[7]?>;font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>}</style>
<style>
.Tit1{color:white;background:<?echo $Estilo[1]?>;font-weight:bold;}
</style>
<table  border="1" bordercolor="#ffffff" width="100%" style="font-family:<?echo $Estilo[8]?>;font-size:<?echo $Estilo[9]?>;font-style:<?echo $Estilo[10]?>">
<tr class="Tit1"><td>Identificaci&oacute;n</td><td>Nombre</td></tr>
<tr><td colspan="2" bgcolor="#EEF6F6"><strong><a href="TerceroxCuenta.php?DatNameSID=<? echo $DatNameSID?>&RegTercero=-1">Quitar Selecci&oacute;n</a></td></tr>
<?
	$cons="Select Movimiento.Identificacion,PrimApe,SegApe,PrimNom,SegNom from Contabilidad.Movimiento,Central.Terceros
	where Movimiento.Identificacion=Terceros.Identificacion and Cuenta ilike '$Cuenta%' and Movimiento.Compania='$Compania[0]'  and Estado='AC'
	and Terceros.Compania='$Compania[0]'
	 Group By Movimiento.Identificacion,PrimApe,SegApe,PrimNom,SegNom Order By PrimApe,SegApe,PrimNom,SegNom";
	$res=ExQuery($cons);echo ExError($res);
	while($fila=ExFetch($res))
	{
		if($TerceroSel==$fila[0]){$Classe="yellow";$Bolder="bold";$BG=$Estilo[1];}
		else{$Bolder="";$Classe="";if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
			else{$BG="white";$Fondo=1;}}

		echo "<tr style='font-weight:$Bolder' bgcolor='$BG'><td><font color='$Classe'>$fila[0]</td><td><a href='TerceroxCuenta.php?DatNameSID=$DatNameSID&RegTercero=$fila[0]&Cuenta=$Cuenta'><font color='$Classe'>" . strtoupper("$fila[1] $fila[2] $fila[3] $fila[4]") . "</a></td></tr>";
	}
?>
