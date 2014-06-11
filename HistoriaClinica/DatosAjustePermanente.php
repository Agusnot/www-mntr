<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$cons100="Select Medicos.usuario,cargo,rm,Nombre from Salud.Medicos,Central.Usuarios where
	Medicos.usuario=Usuarios.usuario and Medicos.usuario='$usuario[1]' and Compania='$Compania[0]'";
	//echo $cons100;
	$res100=ExQuery($cons100);
	while($fila100=ExFetch($res100))
	{
		$MatMedicos[$fila100[0]]=array($fila100[0],$fila100[1],$fila100[2],$fila100[3]);
	}	
	//--Ajuste Permanente
	$consAjuPer="Select ajustepermanentedet.perfil,ajustepermanentedet.item from HistoriaClinica.AjustePermanenteDet,
	HistoriaClinica.AjustePermanente where ajustepermanentedet.Formato='$Formato' and AjustePermanenteDet.TipoFormato='$TipoFormato' 
	and AjustePermanenteDet.Compania='$Compania[0]' and ajustepermanente.compania=ajustepermanentedet.compania and 
	ajustepermanente.Formato=ajustepermanentedet.Formato and ajustepermanente.TipoFormato=ajustepermanentedet.TipoFormato
	and ajustepermanente.perfil=ajustepermanentedet.perfil and ajustepermanente.perfil='".$MatMedicos[$usuario[1]][1]."'";
	$resAjuPer=ExQuery($consAjuPer);
	while($filaAjuPer=ExFetchArray($resAjuPer))
	{
		$MatAjustePer[$filaAjuPer['item']]=array($filaAjuPer['perfil'],$filaAjuPer['item']);	
	}
	//echo $consAjuPer."<br>".$MatMedicos[$usuario[1]][1];
	//--
	$constb="Select TblFormat from  HistoriaClinica.Formatos where Formato='$Formato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]'";
	$restb=ExQuery($constb);
	$filatb=ExFetch($restb);$Tabla=$filatb[0];
?>	
	<script language="javascript" src="/Funciones.js"></script>
    <script language='javascript' src="/calendario/popcalendar.js"></script>
	<script language="JavaScript">
        
    </script>
<body background="/Imgs/Fondo.jpg">
<?	
	if($Guardar)
	{		
		$cons="Select * from HistoriaClinica.ItemsxFormatos where Formato='$Formato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]' and Titulo IS NULL and estado='AC' Order By orden";
		//echo $cons;
		$res=ExQuery($cons);		
		while($fila=ExFetchArray($res))
		{
			if($MatAjustePer[$fila['item']])
			{
				if(${"campo_".$fila['id_item']})
				{
					$ParteActualiza=$ParteActualiza.${"campo_".$fila['id_item']}."='".${${"campo_".$fila['id_item']}}."', ";
				}				
			}
		}	
		$ParteActualiza=substr($ParteActualiza,0,strlen($ParteActualiza)-2);		
		$cons="Update HistoClinicaFrms.$Tabla set $ParteActualiza , usuarioajuste='$usuario[1]', fechaajuste='$ND[year]-$ND[mon]-$ND[mday]'
		where Compania='$Compania[0]' and Formato='$Formato' and TipoFormato='$TipoFormato' and Id_Historia=$IdHistoria and Cedula='$Paciente[1]'";
		//echo $cons;
		$res=ExQuery($cons);
		?><script language="javascript">location.href='Datos.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>'</script><?
		//echo $ParteActualiza;
	}		
	//-----			
?>

<form name="FORMA" method="POST"  enctype="multipart/form-data"> 
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="Formato" value="<? echo $Formato?>">
<input type="hidden" name="TipoFormato" value="<? echo $TipoFormato?>">
<input type="hidden" name="IdHistoria" value="<? echo $IdHistoria?>">
<table border="1" bordercolor="#ffffff" style='font : normal normal small-caps 12px Tahoma;'> 
<tr><td colspan="2"  bgcolor="#e5e5e5" style="font-weight:bold;text-align:center">Ajuste permanente <? echo "<br>$TipoFormato - $Formato"?></td></tr>
<?
	if($IdHistoria)
	{	
		/*if($MatAjustePer)
		{
			$NumCampo=substr("00000",0,5-strlen($IdItem)).$IdItem;
			foreach($MatAjustePer as $It)
			{
				echo $It[0]." --> ".$It[1]."<br>";
				
				//--					
			}	
		}*/
		$cons="Select * from HistoriaClinica.ItemsxFormatos where Formato='$Formato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]' and Titulo IS NULL and estado='AC' Order By orden";
		//echo $cons;
		$res=ExQuery($cons);		
		while($fila=ExFetchArray($res))
		{
			if($MatAjustePer[$fila['item']])
			{
				$NumCampo="CMP".substr("00000",0,5-strlen($fila['id_item'])).$fila['id_item'];
				$ListaCampos=$ListaCampos.$NumCampo.",";			
				$NoTotCampos++;	
				$MatItems[$fila['id_item']]=array($fila['id_item'],$fila['item'],strtolower($NumCampo),$fila['tipodato'],$fila['tipocontrol'],$fila['liminf'],$fila['limsup'],$fila['longitud'],$fila['ancho'],$fila['alto'],$fila['parametro'],$fila['Obligatorio'],$fila['titulo'],$fila['tip'],$fila['mensaje']);						
			}
		}
		//--			
		if($ListaCampos){$ListaCampos.",";}
		$ListaCampos=substr($ListaCampos,0,strlen($ListaCampos));
		$cons="Select $ListaCampos Fecha,Hora,Dx1,Dx2,Dx3,Dx4,Dx5,TipoDx,causaexterna,finalidadconsult from HistoClinicaFrms.$Tabla 
		where Formato='$Formato' and TipoFormato='$TipoFormato' and Id_Historia=$IdHistoria  and Compania='$Compania[0]' 
		and Cedula='$Paciente[1]'";		
		$res=ExQuery($cons);echo ExError();
		//echo $cons."<br>";
		$fila=ExFetchArray($res);
		//--
		foreach($MatItems as $IdIt)
		{
			?>
            <script language="javascript"></script>
			<tr><td  bgcolor="#e5e5e5" style="font-weight:bold;"><? echo $IdIt[1]?></td><td><input type="text" name="<? echo $IdIt[2]?>" value="<? echo $fila[$IdIt[2]]?>"></td></tr>
            <input type="hidden" name="campo_<? echo $IdIt[0]?>" value="<? echo $IdIt[2]?>">
			<?
		}	
	}
	?>
	</table>
    <input type="submit" name="Guardar" value="Guardar">
    <input type="button" name="Volver" value="Volver" onClick="location.href='Datos.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>'">
	</form>
</body>