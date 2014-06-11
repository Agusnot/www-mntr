<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("GeneraValoresEjecucion2.php");

	session_register("DatosY");
	session_register("TitEjeX");
	session_register("TitleGraph");
	mysql_select_db("Presupuesto");
	if($Recaudos)
	{
		$Anio=2008;
		$n=0;
		for($i=1;$i<=9;$i++)
		{
		
			$MesIni=$i;$MesFin=$i;
			$Valores=GeneraValores();
			$Cuenta=11010101;

			$cons="Select * from Central.Meses where Numero=$MesIni";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$MesIniLet=$fila[0];
			$TitEjeX[$n]=substr($fila[0],0,3);
			
			$IngTotales=$Valores["Ingreso presupuestal"][$Cuenta]["CCredito"]-$Valores["Disminucion a ingreso presupuestal"][$Cuenta]["Credito"];
			$DatosY[$n]=$IngTotales/1000000;
			$n++;
		}

		$TitleGraph="Recaudos del Periodo";
		?>
		<script language="JavaScript">
			location.href="GeneraGrafico1.php";
		</script>
		<?
	}
	

?>
<table border="1">
<tr><td><a href="Graficos.php?Recaudos=1">Recaudos</a></td></tr>
<tr><td>Facturacion</td></tr>
<tr><td>Egresos</td></tr>
<tr><td>Disponibilidades</td></tr>
</table>
