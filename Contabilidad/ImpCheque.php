<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");

	$cons2="Select Cuenta from Contabilidad.Movimiento where Comprobante='$Comprobante' and (Cuenta ilike '111%' Or Cuenta like '112%') 
	and Numero='$Numero' and Compania='$Compania[0]'";
	$res2=ExQuery($cons2);echo ExError($res2);
	$fila2=ExFetch($res2);
	$Cuenta=$fila2[0];

	$cons1="Select Fecha,Debe,Haber,Identificacion from Contabilidad.Movimiento where Comprobante='$Comprobante' and Numero='$Numero' and Cuenta='$Cuenta' and Movimiento.Compania='$Compania[0]'";
	$res1=ExQuery($cons1);
	$fila1=ExFetchArray($res1);
	if($fila1[1]){$Valor=$fila1[1];}
	if($fila1[2]){$Valor=$fila1[2];}

	$Letras=NumerosxLet(number_format($Valor,2,".","")).substr("X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X ",1,80-strlen($Letras));;

	$cons="Select PrimApe,SegApe,PrimNom,SegNom from Central.Terceros where Identificacion='$fila1[3]' and Terceros.Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Tercero="$fila[0] $fila[1] $fila[2] $fila[3]";
	$Tercero=$Tercero.substr("X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X X",1,80-strlen($Tercero));

	$cons="Select * from Contabilidad.EstructuraCheques where Cuenta='$Cuenta' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetchArray($res);
?>
<title>Compuconta Software</title>
	<div style="text-align:center;position:absolute;top:<?echo $fila['aniox']?>;left:<?echo $fila['anioy']?>;font-size:12px"><?echo substr($fila1['fecha'],0,4)?></div>

	<div style="position:absolute;top:<?echo $fila['mesx']?>;left:<?echo $fila['mesy']?>;font-size:12px"><?echo substr($fila1['fecha'],5,2)?></div>

	<div style="position:absolute;top:<?echo $fila['diax']?>;left:<?echo $fila['diay']?>;font-size:12px"><?echo substr($fila1['fecha'],8,2)?></div>

	<div style="position:absolute;top:<?echo $fila['valorx']?>;left:<?echo $fila['valory']?>;font-size:12px"><?echo number_format($Valor,2)?></div>

	<div style="position:absolute;top:<?echo $fila['tercerox']?>;left:<?echo $fila['terceroy']?>;font-size:12px"><?echo $Tercero?></div>

	<div style="position:absolute;top:<?echo $fila['letrasx']?>;left:<?echo $fila['letrasy']?>;font-size:12px"><?echo strtoupper($Letras)?></div>