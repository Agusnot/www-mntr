<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($CodElim)
	{		
		if($Tipo=="Medicamentos")
        {
            $cons="delete from contratacionsalud.itemsxpaquete where codigo='$CodElim' and compania='$Compania[0]' and idpaq=$IdPaquete
            and Tipo='Medicamentos'";	
            $res=ExQuery($cons);
        }
        else
        {
            $cons="delete from contratacionsalud.itemsxpaquete where codigo='$CodElim' and compania='$Compania[0]' and idpaq=$IdPaquete";	
            $res=ExQuery($cons);
        }
    }
	if($Guardar1)
	{
		if(!$Editar)
		{
			$cons="select idpaquete from contratacionsalud.paquetesxcontratos where compania='$Compania[0]' order by idpaquete desc";	
			$res=ExQuery($cons);
			$fila=ExFetch($res); $IdPac=$fila[0]+1;
			$cons="insert into contratacionsalud.paquetesxcontratos (compania,idpaquete,entidad,contrato,nocontrato,paquete,usucrea,fechacrea) values
			('$Compania[0]',$IdPac,'$Ent','$Contrato','$NoContrato','$Paquete','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]')";
			$res=ExQuery($cons);
			$Editar=1;
			$IdPaquete=$IdPac;
		}
	}
	if($Editar)
	{
		$cons="select paquete,entidad,contrato,nocontrato from contratacionsalud.paquetesxcontratos where compania='$Compania[0]' and idpaquete=$IdPaquete";	
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		if(!$Paquete){$Paquete=$fila[0];}
		if(!$Ent){$Ent=$fila[1];}
		if(!$Contrato){$Contrato=$fila[2];}
		if(!$NoContrato){$NoContrato=$fila[3];}
		//echo "$Contrato xxx";
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Validar1()
	{
		document.FORMA.Guardar1.value=1;
		document.FORMA.submit();
	}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
	<tr>
    	<td  bgcolor="#e5e5e5" style="font-weight:bold">Paquete</td>
        <td colspan="3"><input type="text" name="Paquete" value="<? echo $Paquete?>" style="width:100%"></td>
    </tr>
    <tr>
    	<td  bgcolor="#e5e5e5" style="font-weight:bold">Entidad</td>
        <td colspan="3">
        <?	$cons="select primape,segape,primnom,segnom,entidad from contratacionsalud.contratos,central.terceros where terceros.compania='$Compania[0]'
			and contratos.compania='$Compania[0]' and entidad=identificacion and estado='AC'
			group by primape,segape,primnom,segnom,entidad order by primape,segape,primnom,segnom";
			$res=ExQuery($cons);?>
        	<select name="Ent" onChange="document.FORMA.submit()">
            	<option></option>
          	<?	while($fila=ExFetch($res))
				{
					if($Ent==$fila[4]){echo "<option value='$fila[4]' selected>$fila[0] $fila[1] $fila[2] $fila[3]</option>";}	
					else{echo "<option value='$fila[4]'>$fila[0] $fila[1] $fila[2] $fila[3]</option>";}	
				}?>
            </select>
        </td>
    </tr>
    <tr>
    	<td  bgcolor="#e5e5e5" style="font-weight:bold">Contrato</td>
        <td>
        <?	$cons="select contrato from contratacionsalud.contratos where contratos.compania='$Compania[0]'
			and entidad='$Ent' and estado='AC' order by contrato";
			$res=ExQuery($cons);?>
        	<select name="Contrato" onChange="document.FORMA.submit()">
            	<option></option>
          	<?	while($fila=ExFetch($res))
				{
					if($Contrato==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}	
					else{echo "<option value='$fila[0]'>$fila[0]</option>";}	
				}?>
            </select>
        </td>
        <td  bgcolor="#e5e5e5" style="font-weight:bold">No. Contrato</td>
        <td>
        <?	$cons="select numero from contratacionsalud.contratos where contratos.compania='$Compania[0]'
			and entidad='$Ent' and contrato='$Contrato' and estado='AC' order by contrato";
			$res=ExQuery($cons);?>
        	<select name="NoContrato" onChange="document.FORMA.submit()">
            	<option></option>
          	<?	while($fila=ExFetch($res))
				{
					if($NoContrato==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}	
					else{echo "<option value='$fila[0]'>$fila[0]</option>";}	
				}?>
            </select>
        </td>
    </tr>
    <tr align="center">
    	<td colspan="4">
        	<input type="button" value="Guardar" onClick="Validar1()">
            <input type="button" value="Regresar" onClick="location.href='Paquetes.php?DatNameSID=<? echo $DatNameSID?>'">
        </td>
    </tr>
</table>
<br>
<?	
if($Editar){?>
    <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
        <tr><td colspan="17" align="center">
            <input type="button" value="Agregar CUPS" onClick="location.href='NewCupsxPaquetes.php?DatNameSID=<? echo $DatNameSID?>&IdPaquete=<? echo $IdPaquete?>'">
            <input type="button" value="Agregar Medicamentos"
               onclick="location.href='/HistoriaClinica/Formatos_Fijos/OMMedUrgentes.php?DatNameSID=<?echo $DatNameSID?>&Origen=Paquetes&IdPaq=<?echo $IdPaquete?>&Paquete=<?echo $Paquete?>&Entidad=<?echo $Ent?>&Contrato=<?echo $Contrato?>&NoContrato=<?echo $NoContrato?>'"/>
            </td>
        </tr>
        <tr><td bgcolor="#e5e5e5" style="font-weight:bold" colspan="17" align="center">CUPS</td></tr>        
        <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
        	<td>Codigo</td><td>Nombre</td><td>Cantidad</td><td>Finalidad</td><td>Justificacion</td><td>Nota</td><td></td></tr>
<?		$cons="insert into contratacionsalud.itemsxpaquete (compania,idpaq,usucrea,fechacrea,codigo,detalle,tipo,cantidad,tipofinalidad,finalidad,justificacion,nota) 		values
		('$Compania[0]',$IdPaquete,'$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Codigo','$Nombre','CUP','$Cantidad'
		,'$TipoFnld','$FinalidadProc','$Justific','$Observ')";	
		
		$cons="select codigo,detalle,cantidad,finalidad,justificacion,nota 
		from contratacionsalud.itemsxpaquete where compania='$Compania[0]' and idpaq=$IdPaquete
		and tipo='CUP'";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td>$fila[3]</td><td>$fila[4]&nbsp;</td><td>$fila[5]&nbsp;</td>";	?>
			<td><img src="/Imgs/b_drop.png" style="cursor:hand" title="Eliminar"
            onClick="if(confirm('Esta seguro de eliminar este registro?')){location.href='NewPaquete.php?DatNameSID=<? echo $DatNameSID?>&CodElim=<? echo $fila[0]?>&IdPaquete=<? echo $IdPaquete?>&Editar=<? echo $Editar?>';}">
            </td></tr>	
	<?	}
	?>        
    </table>
    <?
    $cons = "Select Codigo,Detalle,Cantidad,ViaSumnistro,Posologia,Justificacion,Nota
    from contratacionsalud.ItemsxPaquete
    Where Compania='$Compania[0]' and Tipo='Medicamentos' and IdPaq=$IdPaquete";
    $res = ExQuery($cons);
    if(ExNumRows($res)>0)
    {
        ?>
        <table style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">
            <tr bgcolor="#e5e5e5" style="font-weight: bold" align="center">
                <td colspan="7">MEDICAMENTOS</td>
            </tr>
            <tr bgcolor="#e5e5e5" style="font-weight: bold" align="center">
                <td>Codigo</td><td>Medicamento</td><td>Cantidad</td><td>Via</td><td>Posologia</td><td>Justificacion</td><td>Nota</td>
            </tr>
            <?
            while($fila=ExFetch($res))
            {
                ?>
            <tr>
                <td><? echo $fila[0]?></td><td><? echo $fila[1]?></td><td><? echo $fila[2]?></td>
                <td><? echo $fila[3]?></td><td><? echo $fila[4]?></td><td><? echo $fila[5]?></td><td><? echo $fila[6]?></td>
                <td>
                    <img src="/Imgs/b_drop.png" style="cursor:hand" title="Eliminar"
                    onClick="
                    if(confirm('Esta seguro de eliminar este registro?'))
                    {location.href='NewPaquete.php?DatNameSID=<? echo $DatNameSID?>&Tipo=Medicamentos&CodElim=<? echo $fila[0]?>&IdPaquete=<? echo $IdPaquete?>&Editar=<? echo $Editar?>';}" />
                    
            </td>
            </tr>
                <?
            }
            ?>
        </table>
        <?
    }
}?>
<input type="hidden" name="CodElim" value="">
<input type="hidden" name="Guardar1" value="">
<input type="hidden" name="IdPaquete" value="<? echo $IdPaquete?>">
<input type="hidden" name="Editar" value="<? echo $Editar?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>    