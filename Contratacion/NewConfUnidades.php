<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Guardar)
	{			
		if(!$Edit)
		{
		   $cons="Insert into Salud.Pabellones(Pabellon,Nocamas,Observaciones,Compania,ambito,sobrecupo,centrocosto) values ('$Pabellon','$Nocamas','$Observaciones','$Compania[0]','$Ambito',$Sobrecupo,'$CC')";
		   for($i=1;$i<=$Nocamas;$i++){	
			   	$cons2="Insert into salud.camasxunidades(compania,ambito,unidad,idcama,nombre,estado) values ('$Compania[0]','$Ambito','$Pabellon',$i,'$i','AC') ";			   	
				$res2=ExQuery($cons2);
			}
		}
		else
		{			
			$cons="Update Salud.Pabellones set Pabellon='$Pabellon',Nocamas='$Nocamas',Observaciones='$Observaciones',centrocosto='$CC'
			where Pabellon='$PabellonAnt' and ambito='$Ambito' and Compania='$Compania[0]'";
			$cons2="Update salud.camasxunidades set unidad='$Pabellon' where unidad='$PabellonAnt' and ambito='$Ambito' and compania='$Compania[0]'";
			$res2=ExQuery($cons2);
		}
		
		$res=ExQuery($cons);
		?>
        <script language="javascript">
	       location.href='ConfUnidades.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>';
        </script>
        <?
	}
	if($Edit)
	{
		$cons="Select * from Salud.Pabellones where Pabellon='$Pabellon' and ambito='$Ambito' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		$fila=ExFetchArray($res);
		if(!$Nocamas){$Nocamas=$fila['nocamas'];}
		if(!$Sobrecupo){$Sobrecupo=$fila['sobrecupo'];}
		if(!$Observaciones){$Observaciones=$fila['observaciones'];}
		if(!$Pabellon){$Pabellon=$fila['pabellon'];}
		if(!$CC){$CC=$fila['centrocosto'];}
	}
	
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<script language="javascript">	
	function Validar()
	{
		if(document.FORMA.Pabellon.value==""||document.FORMA.Nocamas.value=="")
		{
			alert("No deben haber campos vacios!!!");return false;
		}
		if(document.FORMA.CC.value==""){alert("Debe seleccionar un centro de costos!!!");return false;}
	}
	function Mostrar()
	{
		document.getElementById('Busquedas').style.position='absolute';
		document.getElementById('Busquedas').style.top='110px';
		document.getElementById('Busquedas').style.right='10px';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		document.getElementById('Busquedas').style.display='none';
	}
</script>
<script language='javascript' src="/Funciones.js"></script>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
	<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
    <tr>
    	<td colspan="3" align="center" bgcolor="#e5e5e5" style=" font-weight:bold">Ambito-<? echo $Ambito?></td>
    </tr>
	<tr>
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Unidad</td><td><input  type="text" maxlength="50" name="Pabellon" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $Pabellon?>"></td>        
    </tr>
	<tr><td bgcolor="#e5e5e5" style=" font-weight:bold">No. de Camas</td>
<? 	if(!$Edit){?>
    	<td><select name="Nocamas" onChange="document.FORMA.submit()"><option></option>
        <? 
			for($i=1;$i<201;$i++)
			{
				if($i==$Nocamas)
				{?>
					<option value="<? echo $i?>" selected><? echo $i?></option>
				<? }
				else
				{ ?>
					<option value="<? echo $i?>"><? echo $i?></option>
				<? }
			}
		?>
        </select>        
        </td>
<?	}
	else{echo "<td><input style='width:45' type='text' name='Nocamas' value='$Nocamas' readonly></td>";}?>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Sobrecupo(Max 25%)</td>
        <td><select name="Sobrecupo">
     		<? 	if($Nocamas<5){
					$limit=1;
				}
				else{
					$limit=$Nocamas*0.25;				
				}
			for($i=0;$i<=$limit;$i++)
			{
				if($i==$Sobrecupo)
				{?>
					<option value="<? echo $i?>" selected><? echo $i?></option>
				<? }
				else
				{ ?>
					<option value="<? echo $i?>"><? echo $i?></option>
				<? }
			}
		?>   
	       	</select>
        </td>        
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Observaciones</td><td><textarea name="Observaciones" cols="50" rows="5" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"><? echo $Observaciones?></textarea></td>        
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Centro de Costos</td>
        <td><input type="text" name="CC" value="<? echo $CC?>"
        	onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&SigObjeto=<? echo "Guardar"?>&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $ND[year]?>'" 	onKeyUp="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&SigObjeto=<? echo "Guardar"?>&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $ND[year]?>'"></td>        
    </tr>
    <tr>
    	<td colspan="2" align="center"><input type="submit" value="Guardar" name="Guardar"><input type="button" value="Cancelar" onClick="location.href='ConfUnidades.php?DatNameSID=<? echo $DatNameSID?>&Ambito=<? echo $Ambito?>'"></td></tr>
</table>
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" name="PabellonAnt" value="<? echo $Pabellon?>">
<input type="hidden" name="Ambito" value="<? echo $Ambito?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
</body>
</html>