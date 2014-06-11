<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		if($CodCup)
		{
			while( list($cad,$val) = each($CodCup))
			{
				if($cad && $val)
				{				
					//$cons="insert into salud.tempdispomedsxgrup (usuario,compania,tmpcod) values ('$cad','$Compania[0]','$TMPCOD') ";
					$cons="insert into historiaclinica.cupslabs(compania,cup,formato,tipoformato) values ('$Compania[0]','$cad','$NewFormato','$TF')";
					$res = ExQuery($cons);				
					echo ExError($res);							
				}
			}
		}
	}
?>	
<script language='javascript' src="/Funciones.js"></script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table border="1" bordercolor="#e5e5e5" cellpadding="5" style="font-family:Tahoma; font-size:11px;" align="center"> 
	<?
	if($Codigo||$Nombre){
		$cons="select cup from historiaclinica.cupslabs where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF'";
		$res=ExQuery($cons);
		if(ExNumRows($res)>0){
			$RestCup=" and codigo not in (select cup from historiaclinica.cupslabs where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF')";
		}
		if($Codigo){$Cod="and codigo ilike '$Codigo%'";}
		if($Nombre){$Nom="and nombre ilike '%$Nombre%'";}
		$cons="select codigo,nombre from contratacionsalud.cups where compania='$Compania[0]' $Cod $Nom $RestCup order by codigo";	
		$res=ExQuery($cons);?>
        <tr style="color:white; font-weight:bold"  bgcolor="<? echo $Estilo[1]?>">
    		<td><div class="style3">Codigo</div></td><td><div class="style3">Nombre</div></td><td></td>
  		</tr>
<?		while($fila=ExFetch($res))
		{?>
			<tr>
            	<td><? echo $fila[0]?></td><td><? echo $fila[1]?></td>
                <td>
                	<input type="checkbox" name="CodCup[<? echo $fila[0]?>]">
                </td>
            </tr>	
	<?	}
	} ?>
	
</table>
<input type="hidden" name="Guardar" value="">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="NewFormato" value="<? echo $NewFormato?>">
<input type="hidden" name="TF" value="<? echo $TF?>">
</form>
</body>    