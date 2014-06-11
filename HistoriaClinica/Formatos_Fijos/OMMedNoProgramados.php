<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$cons="select formato from historiaclinica.formatos where compania='$Compania[0]' and nopos='Medicamentos No POS'";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){$MedNoPos=1;}else{$MedNoPos=0;}
	
	if(!$NoServicio)
	{
		$cons="Select NumServicio From Salud.Servicios Where Cedula='$Paciente[1]' and Compania='$Compania[0]' and estado='AC'";
		$res = ExQuery($cons);echo ExError($res);
		$fila = ExFetch($res);
		$NoServicio=$fila[0];
	}
	if(!$NoOrden)
	{
		$cons="Select NumOrden from Salud.OrdenesMedicas where Cedula='$Paciente[1]' and Compania='$Compania[0]' 
		and IdEscritura='$IdEscritura' order by NumOrden desc";
		$res = ExQuery($cons);
 		if(ExNumRows($res))
		{
			$fila = ExFetch($res);		
			$NoOrden = $fila[0]+1;
		}
		else
		{
			$NoOrden=1;
		}
	}
	$MostrarCancelar = 1;
	$ND = getdate();
	//echo "$ND[year]-$ND[mon]-$ND[mday]";?>
    <iframe scrolling="no" id="FrameFondo" name="FrameFondo" frameborder="0" height="0" width="0" style="filter:Alpha(Opacity=200, FinishOpacity=40, Style=2, StartX=20, StartY=40, FinishX=0, FinishY=0);display:none;border:thin; background-color:transparent" ></iframe>
	<iframe id="FrameOpenerNP" name="FrameOpenerNP" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" scrolling="yes"></iframe>
<?	if($Guardar)
	{
		if(!$Editar)
		{
			$cons = "Select NombreProd1,UnidadMedida,Presentacion from Consumo.CodProductos where Compania='$Compania[0]'
			and AlmacenPpal='$AlmacenPpal' and Anio=$ND[year] and AutoId=$AutoIdProd";
			$res = ExQuery($cons);
			$fila = ExFetch($res);
			$Medicamento = "$fila[0] $fila[1] $fila[2]";
			$TextoOrden = "$Medicamento";
			
			$cons = "Insert into Salud.OrdenesMedicas (Compania,Fecha,Cedula,NumServicio,Detalle,IdEscritura,NumOrden,Usuario,TipoOrden,Acarreo,posologia) values
			('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$NoServicio,
			'$TextoOrden',$IdEscritura,$NoOrden,'$usuario[1]','Medicamento No Programado',1,'$CantMedNOProg')";
			$res = ExQuery($cons);
			$d=date('w',mktime(0,0,0,$ND[mon],$ND[mday],$ND[year]));
			switch($d)
			{
				case 1: $Diasem='Lunes';break;
				case 2: $Diasem='Martes';break;
				case 3: $Diasem='Miercoles';break;
				case 4: $Diasem='Jueves';break;
				case 5: $Diasem='Viernes';break;
				case 6: $Diasem='Sabado';break;
				case 0: $Diasem='Domingo';break;
			}	
			$cons = "Insert into Salud.PlantillaMedicamentos (Compania,AlmacenPpal,AutoIdProd,Usuario,FechaFormula,
			CedPaciente,FechaIni,CantidadMedNOProg,Justificacion,Notas,NumServicio,Detalle,TipoMedicamento,CantDiaria,$Diasem)
			values ('$Compania[0]','$AlmacenPpal','$AutoIdProd','$usuario[1]',
			'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',
			'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
			'$CantMedNOProg','$Justificacion','$Notas',$NoServicio,'$TextoOrden','Medicamento No Programado',1,1)";
			$res = ExQuery($cons);
			if(!$POS){
				$cons="select formato,tipoformato,tblformat from historiaclinica.formatos where compania='$Compania[0]' and estado='AC' and nopos='Medicamentos No POS'";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$consT="select id_historia from histoclinicafrms.$fila[2] where formato='$fila[0]' and tipoformato='$fila[1]' and cedula='$Paciente[1]' 
				order by id_historia desc";
				$resT=ExQuery($consT);
				$filaT=ExFetch($resT);
				$IdH=$fila[0]+1;
				$AbrirForm="1"; ?>                       
				<script language="javascript">							
						
					document.getElementById('FrameFondo').style.position='absolute';
					document.getElementById('FrameFondo').style.top='1px';
					document.getElementById('FrameFondo').style.left='1px';
					document.getElementById('FrameFondo').style.display='';
					document.getElementById('FrameFondo').style.width='1000';
					document.getElementById('FrameFondo').style.height='800';
					
					frames.FrameOpenerNP.location.href="/HistoriaClinica/NuevoRegistro.php?DatNameSID=<? echo $DatNameSID?>&CedPac=<? echo $Paciente[1]?>&Fecha=<? echo "$ND[year]-$ND[mon]-$ND[mday]"?>&NumSer=<? echo $NoServicio?>&SoloUno=<? echo $IdH;?>&Formato=<? echo $fila[0]?>&TipoFormato=<? echo $fila[1]?>&Medicamento=<? echo $Medicamento?>&Posologia=<? echo $Pslg?>&MedNP=1&AlmacenPpal=<? echo $AlmacenPpal?>&AutoIdProd=<? echo $AutoIdProd?>&IdEscritura=<? echo $IdEscritura?>&FechaI=<? echo $FechaI?>&TipoMedicamento=Medicamento Programado";
					document.getElementById('FrameOpenerNP').style.position='absolute';
					document.getElementById('FrameOpenerNP').style.top='10px';
					document.getElementById('FrameOpenerNP').style.left='10px';
					document.getElementById('FrameOpenerNP').style.display='';
					document.getElementById('FrameOpenerNP').style.width='990';
					document.getElementById('FrameOpenerNP').style.height='790';						
				</script>	
		<?	}
			else{
				?><script language="javascript">location.href="NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>";</script><?
			}
		}
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function AbrirMedicamentos()
	{
		frames.FrameOpener.location.href="Medicamentos.php?DatNameSID=<? echo $DatNameSID?>&Formulacion=NOProgramados";
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='20px';
		document.getElementById('FrameOpener').style.left='30px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='600px';
		document.getElementById('FrameOpener').style.height='450px';
	}
	function Validar()
	{
		if(document.FORMA.NoFormato.value==1&&document.FORMA.NoValidar.value!="1"){
			alert("Este medicamento no puede ser ordenado ya que no se encuentra el formato para justificacion de Medicamentos No POS"); 
			return false;
		}		
	}
</script>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="Hidden" name="AutoIdProd" value="<? echo $AutoIdProd?>" />
<input type="hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
<input type="Hidden" name="IdEscritura" value="<? echo $IdEscritura?>" />
<input type="hidden" name="NoServicio" value="<? echo $NoServicio?>" />
<input type="hidden" name="NoOrden" value="<? echo $NoOrden?>" />
<input type="hidden" name="POS" value="<? echo $POS?>">
<input type="hidden" name="MedNoPos" value="<? echo $MedNoPos?>">
<input type="hidden" name="TipoFormato" value="<? echo $TipoFormato?>">
<input type="hidden" name="Formato" value="<? echo $Formato?>">
<input type="hidden" name="AbrirForm" value="<? echo $AbrirForm?>">

	<table rules="groups" width="60%" align="center" cellpadding="2" border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 13px Tahoma;'>
    	<tbody><tr>
        	<td colspan="3" bgcolor="<? echo $Estilo[1]?>" style="color:white" align="center"><strong>MEDICAMENTO</strong></td>
        </tr></tbody>
        <tbody><tr>
        	<td colspan="3" align="center"><input type="Text" name="Medicamento" value="<? echo $Medicamento?>" readonly size="90" style="text-align:center"/>
            <img src="/Imgs/HistoriaClinica/bigfolder.png" title="Escoger Medicamento" style="cursor:hand" onClick="AbrirMedicamentos()" /></td>
        </tr></tbody>
<?
	if($Medicamento)
	{
	unset($MostrarCancelar);
	?>
	<tbody>
    	<tr>
        	<td bgcolor="#e5e5e5" align="center">Frecuencia</td>
        </tr>
        <tr>
        	<td>
            <textarea name="CantMedNOProg" onKeyUp="xLetra(this)" rows="5" onKeyDown="xLetra(this)" style="width:100%"><? echo $Frecuencia?></textarea>
            </td>
        </tr>
        <tr>
        	<td bgcolor="#e5e5e5" align="center">Justificaci&oacute;n</td>
        </tr>
        <tr>
        	<td >
            <textarea name="Justificacion" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" style="width:100%"><? echo $Justificacion?></textarea>
            </td>
        </tr>
        <tr>
        	<td bgcolor="#e5e5e5" align="center">Notas</td>
        </tr>
        <tr>
        	<td >
         	<textarea name="Notas" style="width:100%" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"><? echo $Notas?></textarea>
            </td>
        </tr></tbody>
        <tr align="center"><td>
        	<input type="submit" name="Guardar" value="Guardar Medicamento" />
            <input type="button" name="Cancelar" value="Cancelar" onClick="location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"/>
        </td></tr>
	<?
	}
?>
</table>
<?
	if($MostrarCancelar)
	{?><center><input type="button" name="BtnMostrarCancelar" value="Cancelar" 
    onClick="location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"></center><? }
?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<script language="javascript">
if(document.FORMA.POS.value!="1"&&document.FORMA.MedNoPos.value!=1){document.FORMA.NoFormato.value="1";}else{document.FORMA.NoFormato.value="";}
</script>
<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
</body>