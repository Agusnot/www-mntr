<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Guardar)
	{
		if(!$Edit)
		{
			$cons="Insert into Salud.Ambitos(Ambito,consultaextern,hospitalizacion,hospitaldia,pyp,urgencias,Compania,centrocostos) values ('$Ambito',$ConsExterna,$Hospitalizacion,$HospitalDia,$PyP,$Urgencias,'$Compania[0]','$CC')";
		}
		else
		{
			$cons="Update Salud.Ambitos set Ambito='$Ambito',consultaextern=$ConsExterna,hospitalizacion=$Hospitalizacion,hospitaldia=$HospitalDia
			,pyp=$PyP,urgencias=$Urgencias,centrocostos='$CC'
			where Ambito='$AmbitoAnt' and Compania='$Compania[0]'";			
		}
		//echo $cons;
		$res=ExQuery($cons);echo ExError();
		?>
        <script language="javascript">
	       location.href='ConfAmbitos.php?DatNameSID=<? echo $DatNameSID?>';
        </script>
        <?
	}
	if($Edit)
	{
		$cons="Select * from Salud.Ambitos where Ambito='$Ambito' and consultaextern=$ConsExterna and hospitalizacion=$Hospitalizacion and hospitaldia=$HospitalDia and pyp=$PyP and urgencias=$Urgencias and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		$fila=ExFetchArray($res);
	}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<script language="javascript">
	function salir(){
		 location.href='ConfAmbitos.php?DatNameSID=<? echo $DatNameSID?>';
	}
	
	function Validar()
	{
		if(document.FORMA.Ambito.value=="")
		{
			alert("Debe ingresar un proceso!!!");return false;
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
	<table BORDER=1  style='font : normal normal small-caps 12px Tahoma' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
	<tr>
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Proceso</td>
        <td><input type="text" maxlength="30" name="Ambito" onKeyUp="ExLetra(this)" onKeyDown="ExLetra(this)" value="<? echo $fila['ambito']?>"></td>
        <td bgcolor="#e5e5e5" style=" font-weight:bold">Consulta Externa</td><td><select name="ConsExterna">     
            <option value="0">No</option>
       <?	if($ConsExterna==1||$fila['consultaextern']==1){?>
            	<option value="1" selected>Si</option>
       <?	}
	   		else{?>
				<option value="1">Si</option>
		<?	}?>
        </select>
        </td>        
    </tr>
   	<tr>    	
        <td bgcolor="#e5e5e5" style=" font-weight:bold">Hospitalizacion</td><td><select name="Hospitalizacion">     
            <option value="0">No</option>
       <?	if($Hospitalizacion==1){?>
            	<option value="1" selected>Si</option>
       <?	}
	   		else{?>
				<option value="1">Si</option>
		<?	}?>
        </select>
        </td>        
        <td bgcolor="#e5e5e5" style=" font-weight:bold">Hospital Dia</td><td><select name="HospitalDia">     
            <option value="0">No</option>
       <?	if($HospitalDia==1){?>
            	<option value="1" selected>Si</option>
       <?	}
	   		else{?>
				<option value="1">Si</option>
		<?	}?>
        </select>
        </td>       
    </tr>
<tr>    	
        <td bgcolor="#e5e5e5" style=" font-weight:bold">P y P</td><td><select name="PyP">     
            <option value="0">No</option>
       <?	if($PyP==1){?>
            	<option value="1" selected>Si</option>
       <?	}
	   		else{?>
				<option value="1">Si</option>
		<?	}?>
        </select>
        </td>        
        <td bgcolor="#e5e5e5" style=" font-weight:bold">Urgencias</td><td><select name="Urgencias">     
            <option value="0">No</option>
       <?	if($Urgencias==1){?>
            	<option value="1" selected>Si</option>
       <?	}
	   		else{?>
				<option value="1">Si</option>
		<?	}?>
        </select>
        </td>       
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style=" font-weight:bold">Centro de Costos</td>
        <td><input type="text" name="CC" value="<? echo $fila['centrocostos']?>"
        	onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&SigObjeto=<? echo "Guardar"?>&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $ND[year]?>'" 	onKeyUp="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&SigObjeto=<? echo "Guardar"?>&Centro='+this.value+'&Tipo=CCG&Anio=<? echo $ND[year]?>'"></td>        
    </tr>
    <tr>
    	<td colspan="4" align="center"><input type="submit" value="Guardar" name="Guardar"><input type="button" value="Cancelar" onClick="salir()"></td>
</table>
<input type="hidden" name="Edit" value="<? echo $Edit?>">
<input type="hidden" name="AmbitoAnt" value="<? echo $Ambito?>">
<input type="hidden" name="ConsExternaAnt" value="<? echo $ConsExterna?>">
<input type="hidden" name="HospitalizacionAnt" value="<? echo $Hospitalizacion?>">
<input type="hidden" name="HospitalDiaAnt" value="<? echo $HospitalDia?>">
<input type="hidden" name="PyPAnt" value="<? echo $PyP?>">
<input type="hidden" name="UrgenciasAnt" value="<? echo $Urgencias?>">
<input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">

</form>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
</body>
</html>
