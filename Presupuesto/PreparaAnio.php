<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if(!$AnioSel){$AnioSel=$ND[year];}
	if($Iniciar)
	{
		while (list($val,$cad) = each ($BD)) 
		{
			$AnioAnt=$AnioSel-1;
			$cons="Select * from $val";
			$res=ExQuery($cons);
			for($i=0;$i<=ExNumFields($res)-1;$i++)
			{
				$Campos=$Campos.ExFieldName($res,$i).",";
			}
			$Campos=substr($Campos,0,strlen($Campos)-1);
			$Campos2=str_replace("anio","'$AnioSel'",$Campos);
			$cons2="Insert into $val ($Campos) Select $Campos2 from $val where Compania='$Compania[0]' and Anio='$AnioAnt'";
			$Campos="";$Campos2="";
			$res2=ExQuery($cons2);echo ExError($res2);
			$NumTablas++;
		}
?>
        <script language="javascript">
			alert("Se cargaron <? echo $NumTablas?> tablas exitosamente");
		</script>
        <?
	}
?>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA">
<center><br><br><br>
<table border="1" cellpadding="4" rules="groups" bordercolor="#FFFFFF"  style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr style="color:white;font-weight:bold" bgcolor="#666699"><td align="right">A&ntilde;o a preparar</td>
<td>
<select name="AnioSel" onChange="document.FORMA.submit();">
<?	
	$cons="Select Anio from Central.Anios where Compania='$Compania[0]' Order By Anio";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		if($fila[0]==$AnioSel){echo "<option selected value=$fila[0]>$fila[0]</option>";}
		else{echo "<option value=$fila[0]>$fila[0]</option>";}
	}
	$Msj="<img alt='Documento con registros' src='/Imgs/b_deltbl.png'>";
?>
</select>
</td>
</tr>
<tr style="color:white;font-weight:bold" align="center" bgcolor="#666699"><td>Documentos</td><td>Presupuesto</td></tr>

<tr><td>Estructura Plan</td><td align="center">
<? $cons="Select * from Presupuesto.EstructuraPUC where Compania='$Compania[0]' and Anio=$AnioSel";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1;?>
<input type="Checkbox" name="BD[Presupuesto.EstructuraPUC]"><? }else{echo "$Msj";}?></td>

<tr><td>Plan Presupuestal</td><td align="center">
<? $cons="Select * from Presupuesto.PlanCuentas where Compania='$Compania[0]' and Anio=$AnioSel";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1;?>
<input type="Checkbox" name="BD[Presupuesto.PlanCuentas]"><? }else{echo "$Msj";}?></td>

<tr><td>Mensajes comprobantes</td><td align="center">
<? $cons="Select * from presupuesto.msjcomprobantes where Compania='$Compania[0]' and Anio=$AnioSel";$res=ExQuery($cons);if(ExNumRows($res)==0){$Haga=1;?>
<input type="Checkbox" name="BD[presupuesto.msjcomprobantes]"><? }else{echo "$Msj";}?></td>



<tr><td colspan="2"><center>
<? if($Haga==1){?>
<input type="submit" name="Iniciar" value="Iniciar"><? }?></td></tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>

</body>