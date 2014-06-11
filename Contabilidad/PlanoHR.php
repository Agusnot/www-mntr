<?
	session_name($DatNameSID);
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Encabezados=="on"){$Encabezados=1;}
	else{$Encabezados=0;}
?>
<body onFocus="Ocultar()" background="/Imgs/Fondo.jpg">
<script language='javascript' src="/calendario/popcalendar.js"></script> 
<script language="JavaScript">
	function Mostrar()
	{
		document.getElementById('Busquedas').style.position='absolute';
		document.getElementById('Busquedas').style.top='120px';
		document.getElementById('Busquedas').style.right='10px';
		document.getElementById('Busquedas').style.display='';
	}
	function Ocultar()
	{
		document.getElementById('Busquedas').style.display='none';
	}
	function Validar()
	{
		if(document.FORMA.Fecha.value==""){alert("Debe seleccionar una fecha!");return false;}
		if(document.FORMA.Comprobante.value==""){alert("Debe seleccionar un comprobante!");return false;}
	}
</script>
<?
	if($Eliminar)
	{
		$cons95="Delete from Contabilidad.PlanoHorizontal";$res95=ExQuery($cons95);
	}
	if($CargarArchivo)
	{
		$n=0;$na=0;
		$ArchivoPre = fopen($_FILES['Archivo']['tmp_name'],"r") or die('Error de apertura');
		while(!feof($ArchivoPre))
		{					
			$Linea = fgets($ArchivoPre);
			$Linea=explode(";",$Linea);
			$cons99="Insert into Contabilidad.PlanoHorizontal (";
			foreach($Linea as $Componente)
			{
				$cons99=$cons99."cmp".$n.",";
				$TotComponentes=$TotComponentes.trim($Componente);
				$n++;

			}
			$cons99=substr($cons99,0,strlen($cons99)-1);
			$cons99=$cons99.",AutoId) values(";

			foreach($Linea as $Componente)
			{
				$cons99=$cons99."'".trim($Componente)."',";
			}
			$cons99=substr($cons99,0,strlen($cons99)-1);
			$cons99=$cons99.",$na)";
			if($TotComponentes){
//			echo $cons99."<br>";
			$res99=ExQuery($cons99);}
			$n=0;
			$na++;$TotComponentes=NULL;
		}
	}
	if($Cargar)
	{
		$MatrizCuentas=array();
		$MatrizCampos=array();
		$MatrizNaturaleza=array();
		while (list($val,$cad) = each ($MatrizDatos)) 
		{
			if($cad=="Tercero"){$Identificacion="cmp".$val;}
			elseif($cad=="Centro de Costos"){$CC="cmp".$val;}
			elseif($cad=="Doc Soporte"){$DocSoporte="cmp".$val;}
			elseif($cad=="Detalle"){$Detalle="cmp".$val;}
			else
			{
				if($val<31 && $cad){
				$MatrizCuentas[]=$cad;
				$MatrizCampos[]="cmp".$val;}
				$MatrizNaturaleza[]=$DatoNaturaleza[$val];
			}
		}
		if(!$Identificacion){echo "No es posible subir archivo sin columna de tercero!";$NoInicia=1;}
		if(!$CC){echo "No es posible subir archivo sin columna de centro de costos!";$NoInicia=1;}
		if(!$DocSoporte){echo "No es posible subir archivo sin columna de documento referencia!";$NoInicia=1;}
		if(!$Detalle){echo "No es posible subir archivo sin columna de detalle!";$NoInicia=1;}

		$Encabezados=1;
		$ND=getdate();
		$Numero=ConsecutivoComp($Comprobante,$ND[year],"Contabilidad");
		
		if(!$NoInicia){
		$consPrev="Select AutoId,$Identificacion,$CC,$Detalle,$DocSoporte,";
		for($i=0;$i<=count($MatrizCampos)-1;$i++)
		{
			$consPrev=$consPrev.$MatrizCampos[$i].",";
		}
		if($Encabezados==1){$cond=" where AutoId>0";}
		$consPrev=substr($consPrev,0,strlen($consPrev)-1);
		$consPrev=$consPrev." 	from Contabilidad.PlanoHorizontal $cond Order By AutoId";
		$resPrev=ExQuery($consPrev);
		while($filaPrev=ExFetchArray($resPrev))
		{
                    echo $consPrev;
			$consTerc="Select Identificacion from central.Terceros where Identificacion='$filaPrev[1]'";
			$resTerc=ExQuery($consTerc);
			if(ExNumRows($resTerc)==0){echo "ERROR EN LA LINEA " . $filaPrev['autoid'] . " EL TERCERO " . $filaPrev[1] . " NO EXISTE<br>" ;$NoInicia=1;}
	
	
			$consTerc="Select centrocostos from central.centroscosto where codigo='$filaPrev[2]'";
			$resTerc=ExQuery($consTerc);
			if(ExNumRows($resTerc)==0){echo "ERROR EN LA LINEA " . $filaPrev['autoid'] . " EL CENTRO DE COSTOS " . $filaPrev[2] . " NO EXISTE<br>" ;$NoInicia=1;}
	
			for($i=0;$i<=count($MatrizCampos)-1;$i++)
			{
                                
				$consTerc="Select Cuenta from Contabilidad.PlanCuentas where Cuenta='$MatrizCuentas[$i]'";
				$resTerc=ExQuery($consTerc);
				if(ExNumRows($resTerc)==0){echo "ERROR EN LA LINEA " . $filaPrev['autoid'] . " LA CUENTA " . $MatrizCuentas[$i] . " NO EXISTE<br>" ;$NoInicia=1;}
                                else
                                {
                                    echo "--> $i ". $MatrizNaturaleza[$i+1]. " " . $MatrizCuentas[$i] . " <br>";
                                    if(!$MatrizNaturaleza[$i+1]){echo "ERROR EN LA LINEA " . $filaPrev['autoid'] . " LA CUENTA " . $MatrizCuentas[$i] . " NO TIENE ASIGNADA NATURALEZA<br>" ;$NoInicia=1;}
                                    if($MatrizNaturaleza[$i+1]=="DB"){$Debitos=$Debitos+$filaPrev[$MatrizCampos[$i]];}
                                    if($MatrizNaturaleza[$i+1]=="CR"){$Creditos=$Creditos+$filaPrev[$MatrizCampos[$i]];}
                                }
			}
		}
		if($Debitos!=$Creditos){echo "LAS SUMAS ENTRE DEBITOS ($Debitos) VS CREDITOS ($Creditos) NO CONCUERDAN<br>" ;$NoInicia=1;}
		$Debitos=NULL;$Creditos=NULL;}
		
		if($NoInicia){"<br>NO SE PUEDE SUBIR EL ARCHIVO PLANO POR ERRORES<br>";}
		else{
		$resPrev=ExQuery($consPrev);
		while($filaPrev=ExFetchArray($resPrev))
		{
			$Numero=ConsecutivoComp($Comprobante,substr($Fecha,0,4),"Contabilidad");
			for($i=0;$i<=count($MatrizCampos)-1;$i++)
			{
				$AutoId++;
				if($MatrizNaturaleza[$i+1]=="DB"){$Debitos=$filaPrev[$MatrizCampos[$i]];$Creditos=0;}
				if($MatrizNaturaleza[$i+1]=="CR"){$Creditos=$filaPrev[$MatrizCampos[$i]];$Debitos=0;}
				$Anio=substr($Fecha,0,4);
				$cons="INSERT INTO contabilidad.movimiento
							(AutoId,Fecha,Comprobante,Numero,Identificacion,Detalle,Cuenta,Debe,Haber,CC,DocSoporte,Compania,UsuarioCre,FechaCre,Estado,FechaDocumento,Anio)
							values
							($AutoId, '$Fecha', '$Comprobante', '$Numero', '$filaPrev[1]', '$filaPrev[3]', 
							'$MatrizCuentas[$i]', $Debitos, $Creditos, '$filaPrev[2]', '$filaPrev[4]', '$Compania[0]', 
							'$usuario[0]', '$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
							'AC', '$Fecha', $Anio)";
				$res=ExQuery($cons);
			}
		}
		echo "ARCHIVO CARGADO SATISFACTORIAMENTE<br>Crear Plantilla:";


                for($n=0;$n<=count($MatrizDatos)-1;$n++)
                {
                    $EsquemaPlantilla=$EsquemaPlantilla.$MatrizDatos[$n]."|".$MatrizNaturaleza[$n-1].",";
                }


                echo "<iframe name='Plantilla' src='GuardaPlantilla.php' style='visibility:hidden;position:absolute;width:2px;height;2px;'></iframe>";?>
                <input type="text" name="NombrePlantilla"/>
                <input type="Button" value='Guardar' onclick="frames.Plantilla.location.href='GuardaPlantilla.php?DatNameSID=<? echo $DatNameSID?>&Esquema=<? echo $EsquemaPlantilla?>&NombrePlantilla='+NombrePlantilla.value"/>
                <?
		$cons95="Delete from Contabilidad.PlanoHorizontal";$res95=ExQuery($cons95);
                }
	}
	
	$cons7="Select * from Contabilidad.PlanoHorizontal Order By AutoId";
	$res7=ExQuery($cons7);
	if(ExNumRows($res7)>0){
?>
<form name="FORMA" onSubmit="return Validar()">
<table border="1" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>" bordercolor="white">
<tr>
<td>Fecha</td><td><input type="text" value="<? echo $Fecha?>" name="Fecha" style="width:70px;" maxlength="10" onKeyPress="return false;" onClick="popUpCalendar(this, FORMA.Fecha, 'yyyy-mm-dd')"></td>
<td>Comprobante</td><td colspan="2">
<select name="Comprobante">
<option></option>
<?
	$cons11="Select Comprobante from Contabilidad.Comprobantes where Compania='$Compania[0]' Order By Comprobante";
	$res11=ExQuery($cons11);
	while($fila11=ExFetch($res11))
	{
		if($Comprobante==$fila11[0]){echo "<option selected value='$fila11[0]'>$fila11[0]</option>";}
		else{echo "<option value='$fila11[0]'>$fila11[0]</option>";}
	}
?>
</select>
</td>
<td colspan="2">1a Fila contiene encabezados <input type="checkbox" name="Encabezados" <? if($Encabezados){ echo "checked"; }?>></td>
<td><input type="submit" name="Cargar" value="Iniciar"/></td></tr>
<tr>
    <?
    if($Plantilla)
    {
        $MatrizDatos=NULL;
        $cons45="Select Detalle from Contabilidad.PlanoHRPlantilla where Compania='$Compania[0]' and Nombre='$Plantilla'";
        $res45=ExQuery($cons45);
        $fila45=ExFetch($res45);
        $Partes=explode(",",$fila45[0]);
        for($g=0;$g<=count($Partes)-1;$g++)
        {
            $PlantillaDiv=explode("|",$Partes[$g]);
            $MatrizDatos[$g]=$PlantillaDiv[0];
            $DatoNaturaleza[$g]=$PlantillaDiv[1];
        }
        
    }
    ?>
    
<tr><td>Tomar Plantilla</td>
    <td colspan="4">
        <select name="Plantilla" onchange="document.FORMA.submit();"><option></option>
            <?
                $cons87="Select Nombre from contabilidad.planohrplantilla where Compania='$Compania[0]'";
                $res87=ExQuery($cons87);
                while($fila87=ExFetch($res87))
                {
                    if($fila87[0]==$Plantilla){echo "<option selected value='$fila87[0]'>$fila87[0]</option>";}
                    else{echo "<option value='$fila87[0]'>$fila87[0]</option>";}
                }
            ?>
        </select>
    </td>
</tr>    
    <td colspan="30" align="center" bgcolor="#e5e5e5"><strong>Homologacion</td></tr>
<?

	$cons="Select * from Contabilidad.PlanoHorizontal Order By AutoId";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0)
	{
		echo "<tr>";
		$fila=ExFetch($res);
		for($i=0;$i<=ExNumFields($res)-1;$i++)
                
		{
			if(ExFieldName($res,$i)!="autoid"){
			if($fila[$i]!=NULL){echo "<td>";?>
			<input type='text' value="<? echo $MatrizDatos[$i]?>" name="MatrizDatos[<? echo $i?>]" style='width:90px;' onFocus="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanoHR&Anio=<? echo $ND[year]?>&Parte=' + this.value + '&Objeto=' + this.name" onKeyUp="Mostrar();frames.Busquedas.location.href='Busquedas.php?DatNameSID=<? echo $DatNameSID?>&Tipo=PlanoHR&Anio=<? echo $ND[year]?>&Parte=' + this.value + '&Objeto=' + this.name">
			</td> <? }}
		}
		echo "</tr>";
	}


	$cons="Select * from Contabilidad.PlanoHorizontal Order By AutoId";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0)
	{
		echo "<tr>";
		$fila=ExFetch($res);
		for($i=0;$i<=ExNumFields($res)-1;$i++)
		{
			if(ExFieldName($res,$i)!="autoid"){
			if($fila[$i]!=NULL){echo "<td>";?>
			<select name="DatoNaturaleza[<? echo $i?>]">
            <option value=""></option>
            <? if($DatoNaturaleza[$i]=="DB"){?><option selected value="DB">DB</option><? } else{?><option value="DB">DB</option><? }?>
            <? if($DatoNaturaleza[$i]=="CR"){?><option selected value="CR">CR</option><? } else{?><option value="CR">CR</option><? }?>
            </select>
			</td> <? }}
		}
		echo "</tr>";
	}

	$cons="Select * from Contabilidad.PlanoHorizontal  Order By AutoId Limit 20 Offset 0";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<tr>";
		for($i=0;$i<=ExNumFields($res)-1;$i++)
		{
			if(ExFieldName($res,$i)!="autoid"){
			if($fila[$i]!=NULL){
			echo "<td onfocus='Ocultar();'>$fila[$i]</td>";}}
		}
		echo "</tr>";
	}
	
?>
<tr><td colspan="6"><input type="Button" name="Nuevo Archivo" value="Nuevo Archivo" onClick="location.href='PlanoHR.php?DatNameSID=<? echo $DatNameSID?>&Eliminar=1'"></td></tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
</FORM>
<iframe id="Busquedas" name="Busquedas" style="display:none;" src="Busquedas.php?DatNameSID=<? echo $DatNameSID?>" frameborder="0" height="400"></iframe>
<?	}
else
{?>
<form name="FORMA2" method="post" enctype="multipart/form-data">
	<table border="1">
    <tr><td>Cargar archivo</td><td><input type="file" name="Archivo" onChange="document.FORMA2.Aux.value=document.FORMA2.Archivo.value;"></td></tr>
    <tr><td><input type="submit" value="Cargar Archivo" name="CargarArchivo">
    </table>
    <input type="hidden" name="Aux">
    </FORM>
<?}
?>