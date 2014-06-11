<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	if($Guardar)
	{
		while( list($cad,$val) = each($IncluyeCup))
		{
			if($cad && $val)
			{
				
				if($Reqvobo[$cad]=='on'){$Reqvob=1;}else{$Reqvob=0;}
				if($Facturable[$cad]=='on'){$Factura=1;}else{$Factura=0;}
				if(!$Minimos[$cad]){$Minimos[$cad]=0;}
				if(!$Maximos[$cad]){$Maximos[$cad]=0;}
				$cons = "Insert into ContratacionSalud.CupsXPlanservic (AutoId,Cup,Compania,reqvobo,facturable,minimos,maximos,clase) values ($Autoid,'$cad','$Compania[0]',$Reqvob,$Factura,$Minimos[$cad],$Maximos[$cad],'CUPS')";				
				$res = ExQuery($cons);
				echo ExError($res);			
			}
		}
		
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<?
	
	if($Codigo || $Nombre)
	{
		$cons = "Select Codigo,Nombre from Contratacionsalud.Cups where Compania = '$Compania[0]' and
		Codigo ilike '$Codigo%' and Nombre ilike '%$Nombre%' and Codigo not in(Select Cup from ContratacionSalud.CupsXPlanServic where AutoId = '$Autoid' and Compania='$Compania[0]') order by Nombre";		
		//echo $cons;		
		$res = ExQuery($cons);
		if(ExNumRows($res)>0)
		{
?>		<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">
			<tr><td align="right" colspan="10">
			<button type="submit" name="Guardar"><img src="/Imgs/b_save.png" title="Guardar"></button>
			</td></tr>            
		    <tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
    		<td>Codigo</td><td>Nombre</td><td>Incluir?</td><td>Req. Visto Bueno</td><td>Facturable</td><td>Minimos</td><td>Maximos</td>
    	</tr>
        <?  while($fila = ExFetch($res))
			{
    		?>	<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''"> <?
				echo "<td>$fila[0]</td><td>$fila[1]</td>";							

				?> 
				<td align="center"><input type="checkbox" name="IncluyeCup[<? echo $fila[0]?>]" checked></td>
                <td align="center"><input type="checkbox" name="Reqvobo[<? echo $fila[0]?>]" ></td>
                <td align="center"><input type="checkbox" name="Facturable[<? echo $fila[0]?>]"></td>
                <td align="center"><input type="text" name="Minimos[<? echo $fila[0]?>]" size="6" maxlength="6" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td>
				<td align="center"><input type="text" name="Maximos[<? echo $fila[0]?>]" size="6" maxlength="6" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td><? 
				echo "</tr>";
			}
		?> </table>	<? 	
		} 
	} ?>
    <input type="hidden" name="Clase" value="<? echo $Clase?>">
    <input type="hidden" name="Autoid" value="<? echo $Autoid?>">
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
