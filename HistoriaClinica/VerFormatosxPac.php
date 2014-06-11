<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	
	if($ImpCupsSelect)
	{
		if($ProcedsImp)
		{
			while( list($cad,$val) = each($ProcedsImp))
			{
				if($cad && $val)
				{				
						$Proceds=$Proceds."***$cad;;;1";
				}
			}?>
            <script language="javascript">
				open('/Facturacion/OrdenProced.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $CedPac?>&NoLiq=<? echo $NoLiq?>&Numero=<? echo $NumServ?>&Proceds=<? echo $Proceds?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES,resizable=1')
			</script>
	<?	}
	}
	
	if($ImpMedsSelect)
	{
		if($MedsImp)
		{
			$banMe=0;
			while( list($cad,$val) = each($MedsImp))
			{
				if($cad && $val)
				{	
					if($banMe==0){
						$Medicamentos="$cad";
						$banMe=1;
					}
					else
					{
						$Medicamentos=$Medicamentos."***$cad";
					}
					//echo "$cad<br>";
				}
			}?>
            <script language="javascript">
				open('/Facturacion/FormulaGenerica.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $CedPac?>&NoLiq=<? echo $NoLiq?>&AlmacenPpal=<? echo $Almacen?>&Urgente=1&Numero=<? echo $NumServ?>&Med=<? echo $Med?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>&NoFac=<? echo $NoFac?>&Medicamentos=<? echo $Medicamentos?>','','width=860','height=800','scrolling=yes')
			</script>	
	<?	}
		$Med="";
		$Almacen="";
	}
	
	$cons="select cedula,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom),tiposervicio from salud.servicios,central.terceros
	where servicios.compania='$Compania[0]' and numservicio='$NumServ' and cedula=identificacion and terceros.compania='$Compania[0]'";
	$res=ExQuery($cons); $fila=ExFetch($res); $Cedula=$fila[0]; $NomPac=$fila[1]; $Ambito=$fila[2];
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center">    
 	<tr>
    	<td  bgcolor="#e5e5e5" align="center" style="cursor:hand" colspan="2" title="Abrir Histora Clinca"
        onClick="location.href='ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $Cedula?>&Buscar=1'">
        <? echo "SERVICIO DE <strong>".strtoupper($Ambito)."</strong> PRESTADO A <strong>".strtoupper($NomPac)." - $Cedula</strong>";?></td>        
  	</tr>
</table>
<br>
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center">    
 	<tr>
    	<td  bgcolor="#e5e5e5" align="center" style="font-weight:bold;cursor:hand" colspan="2" title="Abrir Histora Clinca"
        onClick="location.href='ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $Cedula?>&Buscar=1'">HISTORIA CLINICA</td>        
  	</tr>
    <tr  bgcolor="#e5e5e5" align="center" style="font-weight:bold" colspan="2"><td>Tipo Formato</td><td>Formato</td></tr>
<?	$cons="select TipoFormato,Formato,tblformat from historiaclinica.formatos where compania='$Compania[0]' order by TipoFormato,formato";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons3="select table_name from information_schema.tables where table_name='".$fila[2]."'";
		$res3=ExQuery($cons3);
		if(ExNumRows($res3)>0)
		{
			if($FechaIni){$FI="and fecha>='$FechaIni'";}
			if($FechaFin){$FF="and fecha<='$FechaFin'";}
			$cons2="select cedula from histoclinicafrms.".$fila[2]." where compania='$Compania[0]' and numservicio=$NumServ $FI $FF	group by cedula";
			$res2=ExQuery($cons2);
			if(ExNumRows($res2)>0)
			{
				$fila2=ExFetch($res2);?>
            	<tr title="Imprimir Formato" style="cursor:hand" onClick="open('ImpHCMasivaxPac.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $fila[1]?>&TipoFormato=<? echo $fila[0]?>&FechaFin=<? echo $FechaFin?>&FechaIni=<? echo $FechaIni?>&Sexo=<? echo $Sexo?>&Ambito=<? echo $Ambito?>&Pabellon=<? echo $Pabellon?>&Entidad=<? echo $Entidad?>&Contrato=<? echo $Contrato?>&Cedula=<? echo $fila2[0]?>&NumServ=<? echo $NumServ?>','','');">
			<?	echo "<td>$fila[0]</td><td>$fila[1]</td></tr>";
			}
		}
	}?>  
</table>    
<br>    
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center">    
	<tr>
    	<td  bgcolor="#e5e5e5" align="center" style="font-weight:bold" colspan="11">PROCEDIMIENOTS ORDENADOS</td>        
  	</tr>        
<?	$cons2="select codigo,grupo from contratacionsalud.gruposservicio where compania='$Compania[0]' order by grupo";
	$res2=ExQuery($cons2);
	while($fila2=ExFetch($res2))
	{
		$cons="select cup,detalle,fechaini,numorden,idescritura,numservicio,grupo from salud.plantillaprocedimientos,contratacionsalud.cups
		where plantillaprocedimientos.compania='$Compania[0]' and cups.compania='$Compania[0]' and codigo=cup and numservicio=$NumServ 
		and grupo='$fila2[0]' and fechaini>='$FechaIni' and fechaini<='$FechaFin' order by detalle";
		$res=ExQuery($cons);
		if(ExNumRows($res)>0)
		{
			$Proceds="";
			$cons3="select cup,detalle,count(cup),fechaini,numservicio from salud.plantillaprocedimientos,contratacionsalud.cups
			where plantillaprocedimientos.compania='$Compania[0]' and cups.compania='$Compania[0]' and codigo=cup and numservicio=$NumServ 
			and grupo='$fila2[0]' and fechaini>='$FechaIni' and fechaini<='$FechaFin' group by cup,detalle,fechaini,numservicio order by detalle";
			$res3=ExQuery($cons3);			
			while($fila3=ExFetch($res3))
			{
				$Proceds=$Proceds."***$fila3[0];;;$fila3[2]";	
			}//echo $Proceds;
			//codigo,detalle,cantidad?>
			<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
            	<td colspan="11"><? echo $fila2[1]?> <button style="cursor:hand" title="Imprimir Orden"
                onClick=" open('/Facturacion/OrdenProced.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $CedPac?>&NoLiq=<? echo $NoLiq?>&Numero=<? echo $NumServ?>&Proceds=<? echo $Proceds?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES,resizable=1')"><img src="/Imgs/b_print.png"></button></td>
          	</tr>
            <tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
                <td>Codigo</td><td>Nombre</td><td>Fecha</td><td></td>
            </tr>
		<?	while($fila=ExFetch($res))
			{?>
				<tr>
			<?	echo "<td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td>";?>
				<td><!--
					<button title="Imprimir Orden"
					onClick="open('/Facturacion/OrdenProced.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $Cedula?>&Numero=<? echo $fila[5]?>&CodCup=<? echo $fila[0]?>&NumOrd=<? echo $fila[3]?>&IdEsc=<? echo $fila[4]?>','','width=860','height=500','scrollbars=yes')"><img src="/Imgs/b_print.png"></button>-->
                    <input type="checkbox" name="ProcedsImp[<? echo $fila[0]?>]">
				</td></tr>	
		<?	}
		}
		$cons="";
	}?>    
    <tr align="center"><td colspan="11"><input type="submit" name="ImpCupsSelect" value="Imprimir Seleccionados"></td></tr>
</table>    
<br>
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center">    
	<tr>
    	<td  bgcolor="#e5e5e5" align="center" style="font-weight:bold" colspan="11">CUPS REGISTRADOS A TRAVES DE HISTORIA CLINICA</td>        
  	</tr>
    <tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
    	<td>Codigo</td><td>Nombre</td><td>Tipo Formato</td><td>Formato</td><td></td>
  	</tr>
<?	$cons2="select tipoformato,formato,tblformat from historiaclinica.formatos where compania='$Compania[0]'";
	$res2=ExQuery($cons2); 

	while($fila2=Exfetch($res2))
	{
		$TablasHC[$fila2[0]][$fila2[1]]	=$fila2[2];
		//echo "$fila2[0] $fila2[1] $fila2[2]<br>";
	}
	
	$cons="select cup,nombre,tipoformato,formato,id_historia from histoclinicafrms.cupsxfrms,contratacionsalud.cups 
	where cupsxfrms.compania='$Compania[0]' and cups.compania='$Compania[0]' and numservicio=$NumServ and codigo=cup
	order by nombre";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{		
		$cons2="select fecha,usuario from histoclinicafrms.".$TablasHC[$fila[2]][$fila[3]]." where compania='$Compania[0]' and fecha>='$FechaIni'
		and fecha<='$FechaFin' and id_historia=$fila[4] and numservicio=$NumServ";
		//echo $cons2."<br>";
		$res2=ExQuery($cons2); 
		if(ExNumRows($res2)>0){
			echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td><td>$fila[3]</td>";?>
            <td>
            	<button  title="Ver Formato de Historia Clinica" onClick="open('/HistoriaClinica/ImpHistoria.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $fila[3]?>&TipoFormato=<? echo $fila[2]?>&IdHistoria=<? echo $fila[4]?>&CedImpMasv=<? echo $Cedula?>','','');">
                	<img src="/Imgs/b_print.png">
                </button>
            </td>
            </tr>
	<?	}
	}?>    
</table>   
<br>
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center">    
	<tr>
    	<td  bgcolor="#e5e5e5" align="center" style="font-weight:bold" colspan="11">CUPS REGISTRADOS A TRAVES DE ODONTOLOGIA</td>        
  	</tr>
<?	$cons="select cup,nombre,fecha from odontologia.odontogramaproc,contratacionsalud.cups where odontogramaproc.compania='$Compania[0]' 
	and cups.compania='$Compania[0]' and tipoodonto='Seguimiento' and numservicio='$NumServ' and fecha>='$FechaIni' and fecha<='$FechaFin'
	and identificacion='$Cedula' and codigo=cup order by nombre";
	$res=ExQuery($cons);?>    
    <tr bgcolor="#e5e5e5" align="center" style="font-weight:bold">
    	<td>Codigo</td><td>Nombre</td><td>Fecha</td>
  	</tr>
<?	while($fila=ExFetch($res))
	{	
		if(!$BanOdonto){$Fechas[$fila[2]]=$fila[2];}
		else{
			if(!$Fechas[$fila[2]]){$Fechas[$fila[2]]=$fila[2];}	
		}
		$BanOdonto=1;
		echo "<tr><td>$fila[0]</td><td>$fila[1]</td><td>$fila[2]</td></tr>";	
	}
	if($BanOdonto){?>
    	<tr><td colspan="3" align="center"><input type="button" value="Ver Odontograma"
        onClick="<? foreach($Fechas as $Fecs){?>
			open('/HistoriaClinica/Odontologia/ImprimeOdontograma.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Cedula?>&Fecha=<? echo $Fecs?>&TipoOdontograma=<? echo "Seguimiento";?>','','width=1180,height=700,scrollbars=yes');
		<? }?>"></td>
<?	}?>    
</table>
<br>
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center">    
	<tr>
    	<td  bgcolor="#e5e5e5" align="center" style="font-weight:bold" colspan="11">MEDICAMENTOS</td>        
  	</tr>
<?	$cons="select plantillamedicamentos.usuario,nombre from salud.plantillamedicamentos,consumo.codproductos,central.usuarios
	where plantillamedicamentos.compania='$Compania[0]' and autoidprod=autoid and plantillamedicamentos.usuario=usuarios.usuario
	and cedpaciente='$Cedula' and codproductos.compania='$Compania[0]' and codproductos.almacenppal=plantillamedicamentos.almacenppal
	and numservicio=$NumServ and fechaformula>='$FechaIni 00:00:00' and fechaformula<='$FechaFin 23:59:59' order by nombre";
	$res=ExQuery($cons); 
	//echo $cons;
	while($fila=ExFetch($res))
	{
		$UsusOMM[$fila[0]]=array($fila[0],$fila[1]);	
	}
	if($UsusOMM){
		foreach($UsusOMM as $UOMM)
		{
			if($NoFac){$NF="and autoidprod in (select cast(codigo as int) from facturacion.detallefactura where compania='$Compania[0]' and nofactura='$NoFac'
			and tipo='Medicamentos')";}
			
			$cons="select fechaformula,plantillamedicamentos.almacenppal,autoidprod,detalle,sum(cantdiaria),codproductos.codigo2,codigo1,posologia
			from salud.plantillamedicamentos,consumo.codproductos where plantillamedicamentos.compania='$Compania[0]' and autoidprod=autoid
			and cedpaciente='$Cedula' and codproductos.compania='$Compania[0]' and codproductos.almacenppal=plantillamedicamentos.almacenppal
			and numservicio=$NumServ and fechaformula>='$FechaIni 00:00:00' and fechaformula<='$FechaFin 23:59:59' and usuario='$UOMM[0]' $NF
			group by fechaformula,plantillamedicamentos.almacenppal,autoidprod,detalle,codproductos.codigo1,codigo2,posologia
			order by codigo2,codigo1";    
			$res=ExQuery($cons);
			if(ExNumRows($res)>0)
			{?>
				<tr align="center">
					<td bgcolor="#e5e5e5" align="center" style="font-weight:bold" colspan="11"><? echo "Ordena: <strong>$UOMM[1]</strong>"?>
                    	
                    </td>
				</tr>
				<tr bgcolor="#e5e5e5" align="center" style="font-weight:bold" >
					<td>Codigo</td><td>Descripcion</td><td>Cant</td><td>Posologia</td><td>Fecha Orden</td><td></td>
				</tr>
			<?	while($fila=ExFetch($res))
                {   
					if($fila[1]){$Almacen=$fila[1];}
					$CodMed=$fila[6];
					if(!$fila[5]){$fila[5]=$fila[6];}
					$Prod=explode("#",$fila[3]);
					$Cant=$fila[4];
					if($Prod[1]){$Cant=$Prod[1];}?>      
                    <tr>
                        <td><? echo $fila[5]?></td><td><? echo $Prod[0]?></td><td><? echo $Cant?></td><td><? echo $fila[7]?></td><td><? echo $fila[0]?></td>
                        <td><input type="checkbox" name="MedsImp[<? echo $CodMed?>]"></td>
                    </tr>
            <?	}?>
                <tr>
                    <td colspan="11" align="center">
                        <button  style="cursor:hand" title="Imprimir Orden de Medicamentos"
                        onClick=" open('/Facturacion/FormulaGenerica.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $CedPac?>&NoLiq=<? echo $NoLiq?>&AlmacenPpal=<? echo $Almacen?>&Urgente=1&Numero=<? echo $NumServ?>&Med=<? echo $UOMM[0]?>&FechaIni=<? echo $FechaIni?>&FechaFin=<? echo $FechaFin?>&NoFac=<? echo $NoFac?>','','width=860','height=800','scrolling=yes')">
                           Imprimir Formula de Medicamentos
                        </button>
                        <input type="submit" name="ImpMedsSelect" value="Imprimir Seleccionados" 
                        onClick="document.FORMA.Med.value='<? echo $UOMM[0]?>';document.FORMA.Almacen.value='<? echo $Almacen?>';">
                    </td>
                </tr>
		<?	}        	
 		}
	}?>
</table>    
<input type="hidden" name="Almacen" value="">
<input type="hidden" name="Med" value="">
<input type="hidden" name="CedPac" value="<? echo $CedPac?>">
<input type="hidden" name="NumServ" value="<? echo $NumServ?>">
<input type="hidden" name="FechaIni" value="<? echo $FechaIni?>">
<input type="hidden" name="FechaFin" value="<? echo $FechaFin?>">
<input type="hidden" name="NoFac" value="<? echo $NoFac?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>