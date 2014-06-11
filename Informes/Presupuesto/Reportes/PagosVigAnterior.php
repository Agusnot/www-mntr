<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	mysql_select_db("Presupuesto");
	include("GeneraValoresEjecucion.php");
?>
<img src="/Imgs/ContraDenar.jpg" / style="width:100px;position:absolute">
<center>
<font face="tahoma" style="font-size:12px"><strong>
CONTRALORIA DEPARTAMENTAL DE NARIÑO<BR />
NIT 800.157.830-3</strong>
</font>
<hr />
</center><br />
<font face="tahoma" style="font-variant:small-caps" style="font-size:12px"><strong>
<center>
Pagos Realizados con Cargo a Vigencia Anterior<br />
Periodo: <? echo $MesIni?> a <? echo $MesFin?> de <? echo $Anio?><br />
Entidad : <? echo $Compania[0]?><br />
<? echo $Compania[1]?><br /><br />
</center>
