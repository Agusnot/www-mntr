<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if(!$Anio){$Anio=$ND[year];}
?><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
<style>
.Tit1{color:white;background:<?echo $Estilo[1]?>;font-weight:bold;}
</style>
<style>
a{color:<?echo $Estilo[1]?>;text-decoration:none;}
a:hover{color:blue;text-decoration:underline;}
</style>

<table height="100%" rules="groups" border="1" width="100%" bordercolor="black" cellpadding="2" cellspacing="0" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr style="height:10px;" class="Tit1"><td><center>Asistente de B&uacute;squeda</center></td></tr>
<tr><td <? if($Reporteador){?> valign="top"<? }?>>
<?
	if(!$Tipo)
	{
		echo "<center><em>Haga clic sobre un par&aacute;metro de b&uacute;squeda</em>";
	}
		if($Tipo=="CuentasFrame")
		{ ?>
		<script language="JavaScript">
		function PonerCuenta(CuentaConta,Naturaleza,Objeto)
		{
			parent.frames.Info.document.getElementById(Objeto).value=CuentaConta;
//			parent.document.getElementById("Val"+Objeto).value=1;
			parent.frames.Info.document.getElementById(Objeto).focus();
		}
		</script>
<?
			echo "B&uacute;squeda por plan de cuentas<br>";
			echo "Criterio <strong>$Cuenta</strong><br>";
			if($Cuenta){
				$cons="Select Cuenta,Nombre,Tipo,Naturaleza from Contabilidad.PlanCuentas where Cuenta ilike '$Cuenta%' and Compania='$Compania[0]' and Anio=$Anio Order By Cuenta";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{
					if($fila[2]=="Titulo")
					{
						 echo "<font style='font-size:10px;'>$fila[0] - $fila[1]</font>"?><br><?
					}
					else
					{
						?><a style='font-size:10px;' href='#' onclick="PonerCuenta(<? echo $fila[0]?>,'<? echo $fila[3]?>','<? echo $Campo?>')"><? echo "$fila[0] - $fila[1]"?></a><br><?
					}
				}
			}
		}
?>