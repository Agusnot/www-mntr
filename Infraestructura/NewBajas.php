<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	include("FuncionesUnload.php");
	@require_once ("xajax/xajax_core/xajax.inc.php");
	
	$obj = new xajax(); 
	$obj->registerFunction("Clear_Table");
	$obj->processRequest(); 
	
	$ND = getdate();
	if($Guardar)
	{
		if(!$Edit)
		{
			$cons = "Update InfraEstructura.Bajas set fecha = '$Anio-$Mes-$Dia', UsuarioCrea = '$usuario[0]', TextoActa = '$Acta',
			FechaCrea = '$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]', Estado='Solicitado',
			TMPCOD='', Clase = '$Clase', Numero='$Numero' Where Compania='$Compania[0]' and TMPCOD='$TMPCOD'";
			$res = ExQuery($cons);	
		}
		?><script language="javascript">location.href="Movimiento.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Bajas";</script><?	
	}
	if(!$Numero)
	{
		$cons = "Select Numero,TextoActa from Infraestructura.Bajas Where Compania='$Compania[0]' and Numero IS NOT NULL
		and SUBSTR(Numero,0,5) = '$Anio' order by Numero Desc";
		$res = ExQuery($cons);
		if(ExNumRows($res) == 0)
		{
			$cons1 = "Select NumInicial From Infraestructura.Numeracion Where Compania='$Compania[0]' and Anio=$Anio and Tipo = 'Bajas'";
			$res1 = ExQuery($cons1);
			$fila1 = ExFetch($res1);
			$Numero = $Anio.$fila1[0];
		}
		else
		{
			$fila = ExFetch($res);
			$Numero = $fila[0] + 1;
			$Acta = $fila[1];
		}	
	}
	if(!$TMPCOD){$TMPCOD=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? $obj->printJavascript("../xajax");?>
<script language="javascript">
	function Mostrar()
	{
		document.getElementById('Busquedas').style.position='absolute';
		document.getElementById('Busquedas').style.top='50px';
		document.getElementById('Busquedas').style.right='10px';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		document.getElementById('Busquedas').style.display='none';
	}
	function Validar()
	{
		if(document.FORMA.Acta.value==""){alert("Escriba un Mensaje para el acta de Baja");return false;}	
	}
</script>
<?
	if($Edit)
	{
		$cons = "Update Infraestructura.Bajas set TMPCOD='$TMPCOD' Where Compania='$Compania[0]' and Numero=$Numero";
		$res = ExQuery($cons);
		$cons = "Select Fecha,TextoActa from Infraestructura.Bajas Where Compania='$Compania[0]' and Numero=$Numero";
		$res = ExQuery($cons);
		$fila = ExFetch($res);
		$Acta = $fila[1];
	}
?>
</head>
<? $Nulos="Fecha,UsuarioCrea,FechaCrea,Clase,Numero,TextoActa,UsuarioAR,FechaAR";?>
<body background="/Imgs/Fondo.jpg" 
onunload="if(document.FORMA.NoEliminar!='1'){xajax_Clear_Table('Infraestructura.Bajas','<? echo $TMPCOD?>','<? echo $Nulos?>');}">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Clase" value="<? echo $Clase;?>" />
<input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>" />
<input type="hidden" name="Tipo" value="<? echo $Tipo?>" />
<input type="hidden" name="NoEliminar" />
<table border="0">
<tr>
<td>
	<table border="1" width="750" bordercolor="<? echo $Estilo[1]?>" style="font-family:<? echo $Estilo[8]?>;font-size:12;font-style:<? echo $Estilo[10]?>">
    <tr style="color:<? echo $Estilo[6]?>;font-weight:bold;text-align:center" bgcolor="<? echo $Estilo[1]?>">
    	<td colspan="4">Nueva Baja</td>
    </tr>
    <tr>
    	<td width="10%">Fecha</td>
		<td><input type="Text" name="Anio" style="width:40px;" onFocus="Ocultar()" readonly="yes" value="<? echo $Anio?>">
		<?
			$cons="Select * from Central.UsuariosxModulos where Usuario='$usuario[1]' and Modulo='Administrador'";
			$res=ExQuery($cons);
			if(ExNumRows($res)==1)
			{
		?>
			<select name="Mes" style="width:40px" onFocus="Ocultar()">
		<?
			for($i=1;$i<=12;$i++)
			{
				if($i==$Mes){echo "<option selected value='$i'>$i</option>";}
				else{echo "<option value='$i'>$i</option>";}
			}
		?>
			</select>
		<?
            }
            else
            {
        ?>
			<input type="Text" name="Mes" readonly="yes" style="width:20px" maxlength="2" onFocus="Ocultar()" value="<? echo $Mes?>">
		<?
			}
		if(!$Dia){$Dia=$ND[mday];}
		if($Dia<10 && !$Edit){$Dia="0".$Dia;}
		if(!$FechaDocumento){$FechaDocumento="$Anio-$Mes-$Dia";}
		?>
		<input type="Text" name="Dia" maxlength="2" onFocus="Ocultar()" style="width:20px;" value="<?echo $Dia?>">
		</td>
		<td>Numero</td>
		<td><input type="Text" name="Numero" onFocus="Ocultar()" readonly
        	style="width:170px;font-size:16px;color:blue;border:0px;font-weight:bold" value="<? echo $Numero?>"></td>
    </tr>
    <tr>
    	<td>Acta de Baja</td>
        <td colspan="3"><textarea name="Acta" style="width:100%" rows="5"><? echo $Acta;?></textarea></td>
    </tr>
    </table>
</td>
</tr>
<tr>
<td>
	<iframe name="Bajas" frameborder="0" scrolling="no" id="Bajas" src="DetBajas.php?DatNameSID=<? echo $DatNameSID?>&Clase=<? echo $Clase;?>&TMPCOD=<? echo $TMPCOD;?>" style="width:100%" height="300px"></iframe>
</td>
</tr>
<tr>
<td>
<center>
	<input type="submit" name="Guardar" value="Guardar Registro" onClick="NoEliminar.value='1'" />
    <input type="button" name="Cancelar" value="Cancelar" onClick="location.href='Movimiento.php?DatNameSID=<? echo $DatNameSID?>&Tipo=<? echo $Tipo;?>'" />
</center>
</td>
</tr>
</table>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php" frameborder="0" height="400"></iframe>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
</form>
</body>
</html>