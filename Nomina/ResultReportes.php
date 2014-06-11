<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
//	echo $Mes." - ".$Anio."<br>";
//---------------------------
//if(!$Vinculacion==""){$Vin=" and vinculacion='$Vinculacion'";}
if($Mes&&$Anio)
{
//	echo "entro a la busqueda dde nomina<br>";
	$cons="select * from nomina.nomina where mes='$Mes' and anio='$Anio'";
	$res=ExQuery($cons);
	$cont=ExNumRows($res);
}
//echo $cont;
if($cont!="Null")
{
	//-----------------
	if($Reporte=='Reporte 1')
	{
		?>
		<script language="javascript">
//		alert("reporte 1");
		location.href="Reportes/RegGralPago.php?DatNameSID=<? echo $DatNameSID?>&Mes=<? echo $Mes?>&Anio=<? echo $Anio?>&Vinculacion=<? echo $Vinculacion?>";
        </script>
		<?
	}
	elseif($Reporte=='Reporte 2')
	{
		?>
		<script language="javascript">
//		alert("reporte 2");
		location.href="Reportes/ComxFunxSec.php?DatNameSID=<? echo $DatNameSID?>&Mes=<? echo $Mes?>&Anio=<? echo $Anio?>&Vinculacion=<? echo $Vinculacion?>";
        </script>
		<?
	}
	elseif($Reporte=='Reporte 3')
	{
		?>
		<script language="javascript">
//		alert("reporte 2");
		location.href="Reportes/DesprendibleXFuncionario.php?DatNameSID=<? echo $DatNameSID?>&Mes=<? echo $Mes?>&Anio=<? echo $Anio?>&Vinculacion=<? echo $Vinculacion?>";
        </script>
		<?
	}
	elseif($Reporte=='Reporte 4')
	{
		?>
		<script language="javascript">
//		alert("reporte 2");
		location.href="Reportes/ComxFunc.php?DatNameSID=<? echo $DatNameSID?>&Mes=<? echo $Mes?>&Anio=<? echo $Anio?>&Vinculacion=<? echo $Vinculacion?>";
        </script>
		<?
	}
	elseif($Reporte=='Reporte 5')
	{
		?>
		<script language="javascript">
//		alert("reporte 5");
		location.href="Reportes/CompxFuncxCC.php?DatNameSID=<? echo $DatNameSID?>&Mes=<? echo $Mes?>&Anio=<? echo $Anio?>&Vinculacion=<? echo $Vinculacion?>";
        </script>
		<?
	}
	elseif($Reporte=='Reporte 6')
	{
		?>
		<script language="javascript">
//		alert("reporte 5");
		location.href="Reportes/RegDetxCon.php?DatNameSID=<? echo $DatNameSID?>&Mes=<? echo $Mes?>&Anio=<? echo $Anio?>&Vinculacion=<? echo $Vinculacion?>";
        </script>
		<?
	}
	elseif($Reporte=='Reporte 7')
	{
		?>
        <script language="javascript">
//		alert("Reporte 7");
		location.href="Reportes/ReporteTotal.php?DatNameSID=<? echo $DatNameSID?>&Mes=<? echo $Mes?>&Anio=<? echo $Anio?>&Vinculacion=<? echo $Vinculacion?>"
		</script>
        <?
	}
	elseif($Reporte=='Reporte 8')
	{
		?>
        <script language="javascript">
//		alert("Reporte 8");
		location.href="Reportes/PlanUni.php?DatNameSID=<? echo $DatNameSID?>&Mes=<? echo $Mes?>&Anio=<? echo $Anio?>&Vinculacion=<? echo $Vinculacion?>"
		</script>
        <?
	}
}
else
{
	$consm="select mes from central.meses where numero='$Mes'";
    $resm=ExQuery($consm);
	$filam=ExFetch($resm);
	?>
	<script language="javascript">
	alert("No hay liquidacion del Mes de <? echo $filam[0];?> del <? echo $Anio?>");
    </script>
	  <?
}
?>
