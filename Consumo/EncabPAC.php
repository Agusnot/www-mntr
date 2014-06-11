<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("ObtenerSaldos.php");
	$ND=getdate();
	$VrSaldoIni=SaldosIniciales($ND[year],$AlmacenPpal,"$ND[year]-$ND[mon]-$ND[mday]");
?>
<body background="/Imgs/Fondo.jpg">
 <form name="FORMA" method="post" action="VerPac.php" target="VerPac">
 <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
  <table style='font : normal normal small-caps 12px Tahoma;' border="0" bordercolor="#e5e5e5">
   <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
    <td>Almacen Principal</td><td>Centro de Costos</td><td>A&ntilde;o</td>
   </tr>
   <tr>
    <td><select name="AlmacenPpal" onChange="document.FORMA.submit();">
<?
			$cons = "Select AlmacenPpal from Consumo.UsuariosxAlmacenes where Usuario='$usuario[0]' and Compania='$Compania[0]'";
			$res = ExQuery($cons);
			while($fila = ExFetch($res))
			{
				if($AlmacenPpal==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}
			}
?>
    </select></td>
    <td><select name="CC" onChange="document.FORMA.submit();">
    <?
    	$cons = "Select Codigo,CentroCostos from Central.CentrosCosto where Compania='$Compania[0]'";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			if($fila[0]==$CC){ echo "<option selected value='$fila[0]'>$fila[0] - $fila[1]</option>";}
			else{echo "<option value='$fila[0]'>$fila[0] - $fila[1]</option>";}
		}
	?>
    </select></td>
    <td><select name="Anio" onChange="document.FORMA.submit();">
    <option value="<? echo $ND[year]+1?>"><? echo $ND[year]+1?></option>
<?
				$cons = "Select Anio from Central.Anios Where Compania='$Compania[0]' Order By Anio Desc";
				$res = ExQuery($cons);
				while($fila=ExFetch($res))
				{
					if($fila[0]==$Anio){echo "<option selected value='$fila[0]'>$fila[0]</option>'";}
					else{echo "<option value='$fila[0]'>$fila[0]</option>'";}
				}
?>
    </select></td>
    <td><input type="submit" name="Ver" value="Ver" /></td>
   </tr>
  </table>
  </form>
</body>