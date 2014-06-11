<?
	///creo que este archivo ya no lo utilizo
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();
	if($Tipo == "Baja")
	{
		$Tablas = "Infraestructura.Bajas";
		$Campos = "Fecha,Numero,Estado,UsuarioCrea";
		$Cond = "Bajas.Compania='$Compania[0]'";
	}
	if($Tipo == "Traslado")
	{
		$Tablas = "Infraestructura.Traslados,Central.Terceros,Central.CentrosCosto";
		$Campos = "FechaSolicita,PrimApe,SegApe,PrimNom,SegNom,Traslados.Cedula,Estado,CCDestino,CentroCostos,Numero";
		$Cond = "Traslados.Cedula=Terceros.Identificacion and Traslados.Compania='$Compania[0]'
		and Terceros.Compania='$Compania[0]' and CentrosCosto.Anio = $ND[year] and CentrosCosto.Codigo = Traslados.CCDestino";
	}
	$cons = "Select $Campos From $Tablas where Masivo = 1 and $Cond Group by $Campos Order By Numero";
	$res = ExQuery($cons);
	while($fila = ExFetch($res))
	{
		foreach($fila as $col)
		{
			echo "$col";	
		}
		echo "<br>";
	}
?>
<input type="button" name="Nuevo" value="Nuevo" onclick="location.href='NewAccionMasiva.php?Tipo=<? echo $Tipo;?>'" />