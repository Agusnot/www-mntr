<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($NumSerProced){$NumServicio=$NumSerProced;}
?>
	<body background="/Imgs/Fondo.jpg">
	<script language="javascript" src="/Funciones.js"></script>
    <script language='javascript' src="/calendario/popcalendar.js"></script>

<script language="JavaScript">
	function BuscarDx(Objeto,Objeto2)
	{
		frames.FrameOpener.location.href='BuscarDx.php?ControlOrigen='+Objeto+'&DetalleObj='+Objeto2;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='50px';
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='410';
	}
</script>
<?
	$ND=getdate();
	/*
	$cons="Select NumServicio,TipoServicio from Salud.Servicios where Cedula='$Paciente[1]' and Estado='AC' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$NumServicio=$fila[0];
	$Ambito=$fila[1];
	if(!$NumServicio){echo "<em><br><br><br><br><br><center><font size='6' color='red'>No es posible registrar historia clinica sin servicios activos</font></em>";exit;}
	*/

	$cons="Select Alineacion,tblformat from HistoriaClinica.Formatos where Formato='$Formato' and TipoFormato='$TipoFormato' and compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);echo ExError();
	$Alineacion=$fila[0];$Tabla=$fila[1];

	if(!$IdPantalla){$IdPantalla=1;}
	if(!$IdItem){$IdItem=1;}

	if(!$IdHistoria){

	$cons2="Select Id_Historia from HistoClinicaFrms.$Tabla where Formato='$Formato' and TipoFormato='$TipoFormato' and Cedula='$Paciente[1]' 
	and Compania='$Compania[0]' Group By Id_Historia Order By Id_Historia Desc";
	$cons2="Select Id_Historia from HistoClinicaFrms.$Tabla where Formato='$Formato' and TipoFormato='$TipoFormato'
	and Compania='$Compania[0]' Group By Id_Historia Order By Id_Historia Desc";
	$res2=ExQuery($cons2,$conex);
	$fila2=ExFetch($res2);
	$IdHistoria=$fila2[0]+1;}

	if (isset($Registro))
	{
		if(!$Fecha){$Fecha="$ND[year]-$ND[mon]-$ND[mday]";}
		if(!$Hora){$Hora="$ND[hours]:$ND[minutes]:$ND[seconds]";}

		$cons3="Select RM,Cargo from Salud.Medicos where Usuario='$usuario[1]'";
		$res3=ExQuery($cons3,$conex);
		$fila3=ExFetch($res3);
		$RM=$fila3[0];$Cargo=$fila3[1];

		$cons="Select * from Salud.PacientesxPabellones where Cedula='$Paciente[1]' and Estado='AC' and Compania='$Compania[0]'";
		$res=ExQuery($cons,$conex);
		$fila=ExFetchArray($res);
		$Unidad=$fila['pabellon'];

///////////////// INSERCION DE DATOS A LA HISTORIA CLINICA /////////////////////////////

		$cons="Select Item,TipoDato,LimInf,LimSup,Obligatorio,LineaSola,CierraFila,Titulo,TipoControl,Mensaje,Id_Item,SubFormato 
		from HistoriaClinica.ItemsxFormatos where Formato='$Formato' and TipoFormato='$TipoFormato' 
		and Pantalla=$IdPantalla and Compania='$Compania[0]' Order By Id_Item";

		$res=ExQuery($cons,$conex);echo ExError($conex);
		while($fila=ExFetch($res))
		{
			if($fila[7]==0)
			{
				$IdItem=$fila[10];
				$Campo=str_replace(".","-",$fila[0]);
				$Campo=str_replace(" ","_",$Campo);
	
				if($fila[8]=="Cuadro de Chequeo" && !(trim($_POST["$Campo"]))){$_POST["$Campo"]="No";}
				if($fila[4]=="1" && !(trim($_POST["$Campo"])))
				{$Registro="Quieto"?>
					<script language="JavaScript">
						alert("No puede dejar el campo <? echo $Campo?> en blanco!!!");
					</script>
					
	<?			}
				
				if($fila[1]=="N" && ($_POST[$Campo]>$fila[3] || $_POST[$Campo]<$fila[2]))
				{	$Registro="Quieto";$_POST[$Campo]="";?>
					<script language="JavaScript">
						alert("El valor del campo <? echo $Campo?> se encuentra fuera de limite!!!");
					</script>
	<?			}
				$NumCampo=substr("00000",0,5-strlen($fila[10])).$fila[10];
				$ListCmpInsert=$ListCmpInsert."CMP".$NumCampo.",";
				$CmpInsert=$CmpInsert."'".$_POST["$Campo"]."',";
				
				$LisCmpUpdt=$LisCmpUpdt."CMP".$NumCampo."='".$_POST["$Campo"]."',";
			}			
			if($fila[7]==1 && $fila[0]=='Diagnostico') ///////////////////ASIGNACION DIAGNOSTICA
			{
				$Campo="TipoDx";
				$LisCmpUpdt=$LisCmpUpdt."TipoDx='".$_POST["TipoDx"]."',";
				$ListCmpInsert=$ListCmpInsert."TipoDx,";
				$CmpInsert=$CmpInsert."'".$_POST["TipoDx"]."',";

				if(!$_POST['Dx1'])
				{
					$Registro="Quieto"?>
					<script language="JavaScript">
						alert("No puede dejar el Diagnostico Principal en blanco!!!");
					</script>
					
	<?			}
				$cons8="Select Detalle,CIE10,Id from historiaclinica.dxformatos where Compania='$Compania[0]' and Estado='AC' and Formato='$Formato' and TipoFormato='$TipoFormato' Order By Id";
				$res8=ExQuery($cons8);
				while($fila8=ExFetch($res8))
				{
					$Campo="Dx".$fila8[2];
					$LisCmpUpdt=$LisCmpUpdt."Dx".$fila8[2]."='".$_POST["$Campo"]."',";

					$ListCmpInsert=$ListCmpInsert."Dx".$fila8[2].",";
					$CmpInsert=$CmpInsert."'".$_POST["$Campo"]."',";

				}
			}
			
		}
		$ListCmpInsert=substr($ListCmpInsert,0,strlen($ListCmpInsert)-1);
		$CmpInsert=substr($CmpInsert,0,strlen($CmpInsert)-1);
		$LisCmpUpdt=substr($LisCmpUpdt,0,strlen($LisCmpUpdt)-1);

		$cons9="Select * from HistoClinicaFrms.$Tabla where Formato='$Formato' and TipoFormato='$TipoFormato' and Id_Historia=$IdHistoria and Cedula='$Paciente[1]' and Compania='$Compania[0]'";
		$res9=ExQuery($cons9,$conex);
		if(ExNumRows($res9)==0)
		{
			if(!$IdHistoOrigen){$IdHistoOrigen="NULL";$SFFormato="";$SFTF="";}
			if($fila[8]=="Imagen"){$Imagen=$_POST["$Campo"];}else{$Imagen="";}
			if(!$CUP || $CUP=="NULL"){$CUP="NULL";}else{$CUP="'$CUP'";}
			if(!$Unidad || $Unidad=="NULL"){$Unidad="NULL";}else{$Unidad="'$Unidad'";}
			if(!$NumServicio){$NumServicio="NULL";}if(!$fila[5]){$fila[5]="NULL";}if(!$fila[6]){$fila[6]="NULL";}if(!$fila[7]){$fila[7]="NULL";}
			if($NumProced){$Proced1=",numproced";$Proced2=",$NumProced";}
			$cons1="Insert into HistoClinicaFrms.$Tabla(Formato,Id_Historia,Usuario,Fecha,Hora,Cedula,Ambito,UnidadHosp,TipoFormato,Cargo,NumServicio,Compania,$ListCmpInsert $Proced1)
			values('$Formato',$IdHistoria,'$usuario[1]','$Fecha','$Hora','$Paciente[1]','$Ambito',$Unidad,'$TipoFormato','$Cargo',$NumServicio,'$Compania[0]',$CmpInsert $Proced2)";
		}
		else
		{
			$cons1="Update HistoClinicaFrms.$Tabla set $LisCmpUpdt
			where Formato='$Formato' and TipoFormato='$TipoFormato' and Id_Historia=$IdHistoria and Cedula='$Paciente[1]' and Compania='$Compania[0]'";
		}
		$res1=ExQuery($cons1);
///////////////// FIN INSERCION DE DATOS A LA HISTORIA CLINICA /////////////////////////////		

////////////////////////////////////////ASIGNACION DE CUPS////////////////////////////////
		$cons="Select Id_Item,Item
		from HistoriaClinica.ItemsxFormatos where Formato='$Formato' and TipoFormato='$TipoFormato' 
		and Pantalla=$IdPantalla and Titulo IS NULL and Compania='$Compania[0]' Order By orden";
		$res=ExQuery($cons);echo ExError($conex);
		while($fila=ExFetch($res))
		{
			$Campo=str_replace(".","-",$fila[1]);
			$Campo=str_replace(" ","_",$Campo);

			$cons11="Delete from histoclinicafrms.cupsxfrms 
			where formato='$Formato' and tipoformato='$TipoFormato' and id_historia=$IdHistoria and cedula='$Paciente[1]' and compania='$Compania[0]'
			and numservicio=$NumServicio and id_item=$fila[0]";
			$res11=ExQuery($cons11);	

			if($_POST["$Campo"])
			{
				$cons9="Select CUP from HistoriaClinica.CUPSxFormatos where TipoFormato='$TipoFormato' and Formato='$Formato' and (Cargo='$Cargo' Or Cargo='') and Compania='$Compania[0]'
				and Item=$fila[0] and (VrItem='' Or VrItem='" . $_POST["$Campo"]."')";
				$res9=ExQuery($cons9);
				if(ExNumRows($res9)>0)
				{
					$fila9=ExFetch($res9);
					$CUP=$fila9[0];
					$cons10="Select * from histoclinicafrms.cupsxfrms where TipoFormato='$TipoFormato' and Formato='$Formato' and Id_Historia=$IdHistoria and Cedula='$Paciente[1]' 
					and Compania='$Compania[0]'";
					$res10=ExQuery($cons10);
					$cons11="Insert into histoclinicafrms.cupsxfrms (formato, tipoformato, id_historia, cedula, compania, numservicio,cup, id_item)
					values('$Formato','$TipoFormato',$IdHistoria,'$Paciente[1]','$Compania[0]',$NumServicio,'$CUP',$fila[0])";
					$res11=ExQuery($cons11);	
				}
			}
		}
		//////////////////////////////FIN ASIGNACION DE CUPS /////////////////////////
		
		if($Registro=="Siguiente"){$IdPantalla++;}
		if($Registro=="Anterior"){$IdPantalla=$IdPantalla-1;$Edit=1;}
	}
	
		
	$cons="Select Pantalla from HistoriaClinica.ItemsxFormatos where Formato='$Formato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]' group By Pantalla Order By Pantalla Desc";
	$res=ExQuery($cons,$conex);
	$fila=ExFetch($res);
	if($IdPantalla>$fila[0])
	{
		if($NumProced){
			$cons="update salud.plantillaprocedimientos set fechalab='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'	
			where compania='$Compania[0]' and cedula='$Paciente[1]' and numservicio=$NumSerProced and numprocedimiento=$NumProced and cup='$CUPProced'";
			$res=ExQuery($cons);		
		}
		if($SoloUno){
			$SoloUno=$IdHistoria;
			?><script language="javascript">
				opener.document.FORMA.submit();
			</script><?
		}?>
		<script language="JavaScript">			
			location.href='Datos.php?DatNameSID=<? echo $DatNameSID?>&SubFormato=<? echo $SFFormato?>&IdHistoOrigen=<? echo $IdHistoOrigen?>&SFTF=<? echo $SFTF?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&SoloUno=<? echo $SoloUno?>';
		</script>	
<?	}

?>

<table border="1" bordercolor="#e5e5e5" style="font : 12px Tahoma;"> 
<form name="FORMA" method="POST"> 
<?

	if($IdHistoria)
	{
		$NumCampo=substr("00000",0,5-strlen($IdItem)).$IdItem;
		
		$cons9="Select Id_Item from HistoriaClinica.ItemsxFormatos where Formato='$Formato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]' and (Titulo IS NULL or Titulo = 0) Order By orden";
		$res9=ExQuery($cons9);		
		while($fila9=ExFetch($res9))
		{
			$NumCampo="CMP".substr("00000",0,5-strlen($fila9[0])).$fila9[0];
			$ListaCampos=$ListaCampos.$NumCampo.",";
			$NoTotCampos++;
			$DatCampos[$fila9[0]]=array($fila9[0],1);
		}
		$ListaCampos=substr($ListaCampos,0,strlen($ListaCampos)-1);
		$cons1="Select $ListaCampos,Fecha,Hora,Dx1,Dx2,Dx3,Dx4,Dx5,TipoDx from HistoClinicaFrms.$Tabla where Formato='$Formato' and TipoFormato='$TipoFormato' and Id_Historia=$IdHistoria  and Compania='$Compania[0]'	and Cedula='$Paciente[1]'";

		$res1=ExQuery($cons1,$conex);echo ExError();
		$fila1=ExFetchArray($res1);
		foreach($DatCampos as $ListFields)
		{
			$NomCampo=substr("00000",0,5-strlen($ListFields[0])).$ListFields[0];
			$DatosHC[$ListFields[0]]=$fila1['cmp'.$NomCampo];
		}
		$Fecha=$fila1['fecha'];$Hora=$fila1['hora'];
		$ValDx[1]=$fila1['dx1'];$ValDx[2]=$fila1['dx2'];$ValDx[3]=$fila1['dx3'];$ValDx[4]=$fila1['dx4'];$ValDx[5]=$fila1['dx5'];$ValTipoDx=$fila1['tipodx'];
	}

	 if(!$ValDx[1])
	 {
		$cons46="Select Dx1,Dx2,Dx3,Dx4,Dx5,TipoDx from histoclinicafrms.$Tabla where Formato='$Formato' and TipoFormato='$TipoFormato' and Cedula='$Paciente[1]' 
		and Compania='$Compania[0]' and Dx1!='' Order By Id_Historia Desc";
		$res46=ExQuery($cons46);
		$fila46=ExFetchArray($res46);
		$ValDx[1]=$fila46['dx1'];$ValDx[2]=$fila46['dx2'];$ValDx[3]=$fila46['dx3'];$ValDx[4]=$fila46['dx4'];$ValDx[5]=$fila46['dx5'];$ValTipoDx=$fila46['tipodx'];
	 }


	$cons="Select * from HistoriaClinica.ItemsxFormatos where Formato='$Formato' and TipoFormato='$TipoFormato' and Pantalla=$IdPantalla and Compania='$Compania[0]' and Estado='AC' Order By orden";
	if($Alineacion=="Horizontal")
	{
		$res=ExQuery($cons,$conex);
		while($fila=ExFetchArray($res))
		{
			$Tip=$fila['tip'];
			$Mensaje=$fila['item'];
			echo "<td bgcolor='#e5e5e5' align='center'>" . $Mensaje . "</td>";
		}
		echo "<tr>";
	}

	$res=ExQuery($cons);echo ExError();
	while($fila=ExFetchArray($res))
	{
		$Tip=$fila['tip'];
		$Mensaje=$fila['item'];
		$Nombre=str_replace(".","-",$fila['item']);
		$Nombre=str_replace(" ","_",$Nombre);
		if($fila['lineasola']==1){$Ancho='100%'; $colspanLS="colspan='99'";}else{$Ancho=$fila['ancho'];}
		$Stilo="width:" . $Ancho .";height:". $fila['alto'] . ";maxlength:" . $fila['longitud'];

		if($Alineacion=="Vertical")
		{ ;
			if($CrearTabla==1 && $fila['subformato']==1){echo "</table>";$CrearTabla=0;}
			if($fila['subformato']==1){echo "<input type='Hidden' name='$Nombre' value=1>";}
			if($CrearTabla==1 && $fila['titulo']==1){echo "</table>";$CrearTabla=0;}
			if($fila['item']=="Diagnostico"){$Colspan=99;}else{$Colspan=1;}
			if($fila['titulo']==1){echo "<tr><td bgcolor='#e5e5e5' colspan='99'><strong><center>".$Mensaje;}
			else
			{
	
				if($CrearTabla==1 && $fila['lineasola']==1){echo "</table>";$CrearTabla=0;}
				if($fila['subformato']==1){$Mensaje="";}
				if($fila['lineasola']==1)
				{
					$colspanLS="colspan='99'";
					echo "<tr><td colspan='99'>"./*$Mensaje.*/"</td></tr><tr><td colspan='99'>";
				}
				elseif($fila['lineasola']==0)
				{
					$colspanLS="";
					if($CierraFila)
					{
						echo "<tr>";
					}
					if(!$CrearTabla)
					{
						echo "<tr><td colspan='99'>";
						echo "<table border=0 bordercolor='blue' style='font : 12px Tahoma;'><tr>";$CrearTabla=1;
					}
					echo "<td $colspanLS>".$Mensaje."</td><td>";
					$CierraFila=$fila['cierrafila'];
				}
			}
		}
		elseif($Alineacion=="Horizontal")
		{
			echo "<td align='center'>";
		}

		if(!$DatosHC[$fila['id_item']])
		{
			$Edad=ObtenEdad($Paciente[23]);
			$fila['defecto']=str_replace("AHORA","$ND[year]-$ND[mon]-$ND[mday]",$fila['defecto']);
			$fila['defecto']=str_replace("EDADDEF",$Edad,$fila['defecto']);
			$fila['defecto']=str_replace("RESIDEF",$Paciente[11],$fila['defecto']);
			$fila['defecto']=str_replace("OCUPADEF",$Paciente[35],$fila['defecto']);
			$fila['defecto']=str_replace("SEXODEF",$Paciente[24],$fila['defecto']);
			$fila['defecto']=str_replace("SERIAL",$IdHistoria,$fila['defecto']);
			$DatosHC[$fila['id_item']]=$fila['defecto'];
		}
		
		if($fila['tipodato']=="N"){$Events="onKeyUp=xNumero(this) onKeyDown=xNumero(this) onBlur=campoNumero(this)";}
		else{$Events="onKeyUp=xLetra(this) onKeyDown=xLetra(this)";}

		/*if($fila['traerde'])
		{
			$cons45="Select TblFormat from  HistoriaClinica.Formatos where Formato='". $fila['traerde']. "' and TipoFormato='".$fila['tftraerde']."' and Compania='$Compania[0]'";

			$res45=ExQuery($cons45);
			$fila45=ExFetch($res45);
			$TablaOrigen=$fila45[0];
				
			$cons45="Select Detalle from HistoClinicaFrms.$TablaOrigen where Formato='". $fila['traerde']. "' and TipoFormato='".$fila['tftraerde']."' and Compania='$Compania[0]'
				  	 and Cedula='$Paciente[1]' and Item='".$fila['campotraerde']."' Order By Id_Historia Desc";
			$res45=ExQuery($cons45);
			$fila45=ExFetch($res45);
			if(!$DatosHC[$fila['item']]){$DatosHC[$fila['item']]=$fila45[0];}
			PENDIENTE-----------------------
		}*/

		

		if($fila['parametro'])
		{
			$fila['parametro']=str_replace("="," onfocus=this.value=",$fila['parametro']);
			$fila['parametro']=str_replace("[","parseInt(",$fila['parametro']);
			$fila['parametro']=str_replace("]",".value)",$fila['parametro']);
			$Events2=$fila['parametro'];
		}
		if($fila['tipocontrol']=="Area de Texto"){echo "<textarea $Events2 $Events title='$Tip' style='$Stilo' name='$Nombre'>" . $DatosHC[$fila['id_item']] . "</textarea>";}
		if($fila['tipocontrol']=="Cuadro de Texto"){echo "<input title='$Tip' $Events2 $Events maxlength='".$fila['longitud']."' type='Text' style='$Stilo' name='$Nombre' value='" . $DatosHC[$fila['id_item']] . "'>";}
		if($fila['tipocontrol']=="Fecha"){?><input title="<? echo $Tip?>" <? echo "$Events2 $Events";?> maxlength="<? echo $fila['longitud']?>" type='Text' style=" <? echo $Stilo ?>" name="<? echo $Nombre?>" value="<? echo $DatosHC[$fila['id_item']]; ?>" readonly onClick="popUpCalendar(this, this, 'dd-mm-yyyy')"> <? }
		if($fila['tipocontrol']=="Lista Opciones")
		{	
			echo " <select title='$Tip' name='$Nombre'>";
			$vector=explode(";",$fila['parametro']);
			foreach ($vector as $valor)
			{
					if($DatosHC[$fila['id_item']]==$valor)
					{
						echo"<option value='$valor' selected>$valor</option>";
					}
					else
					{
						echo"<option value='$valor'>$valor</option>";
					}
					
			} 
			echo "</select>";
		}
		if($fila['tipocontrol']=="PDF")
		{
        	echo "<input type='file' name='$Nombre' title='$Tip' style='width:480'";
		}
		if($fila['tipocontrol']=="Cuadro de Chequeo")
		{
			if($DatosHC[$fila['id_item']]=='Si')
			{
				echo"<input title='$Tip' type='checkbox' name='$Nombre' checked value='Si'/>";
			}
			else
			{
				echo "<input title='$Tip' type='checkbox' name='$Nombre' value='Si'/>";
			}
		}		
		if($fila['tipocontrol']=="Imagen")
		{
			echo "<input type='Hidden' name='$Nombre' value='".$fila['imagen']."'>";
			echo "<img title='$Tip' src='".$fila['imagen']."'>";
		}
		if($fila['subformato']==1)
		{
			$DivFor=explode(".",$fila['item']);
			$SFTF=$DivFor[0];$SFFormato=$DivFor[1];?>
			<tr><td colspan="90">
            	<iframe style="width:<? echo $fila['ancho']?>; height:<? echo $fila['alto']?>" src="NuevoRegistro.php?DatNameSID=<? echo $DatNameSID?>&IdHistoOrigen=<? echo $IdHistoria?>&SFFormato=<? echo $Formato?>&SFTF=<? echo $TipoFormato?>&TipoFormato=<? echo $SFTF?>&Formato=<? echo $SFFormato?>&IdHistoria=<? echo $IdHistoria?>" style="width:100%;" frameborder="0"></iframe> 
                </td>
         	</tr>
<?		}
		if($fila['item']=="Medicamento No Pos")
		{?>        	
        	<tr>			
           		<td colspan="99"><strong>Principio Activo:</strong><? echo $Medicamento?>&nbsp;</td>               
	      	</tr>
            <tr>
            	 <td colspan="99"><strong>Posologia:</strong><? echo $Posologia?>&nbsp;</td>
            </tr>                
           
	<?	}
		if($fila['item']=="CUP No Pos")
		{?>
			<tr>			
           		<td colspan="99"><strong>Codigo:</strong><? echo $CUPNP?>&nbsp;</td>               
	      	</tr>
            <tr>
            	 <td colspan="99"><strong>Nombre:</strong><? echo $NomCUPNP?>&nbsp;</td>
            </tr>	
	<?	}
		if($fila['item']=="Diagnostico")
		{

			$cons8="Select Detalle,CIE10,Id from historiaclinica.dxformatos where Compania='$Compania[0]' and Estado='AC' and Formato='$Formato' and TipoFormato='$TipoFormato' Order By Id";
			$res8=ExQuery($cons8);
			while($fila8=ExFetch($res8))
			{								
				 if($fila8[2]!=1){$Colspan=2;$Width="480px";}else{$Colspan=1;$Width="300px";}
				 $cons19="Select Diagnostico from Salud.CIE where Codigo='".$ValDx[$fila8[2]]."'";
				 $res19=ExQuery($cons19);
				 $fila19=ExFetch($res19);
				 $DetValDx=$fila19[0]; 
				?>
	
				<tr><td colspan="99"><? echo $fila8[0]?><input readonly type="text" name="Dx<? echo $fila8[2] ?>" style="width:40px;" value="<? echo $ValDx[$fila8[2]]?>">
				<input readonly type="text" name="DetDx<? echo $fila8[2] ?>" style="width:<? echo $Width?>;" value="<? echo $DetValDx?>">
				<input type="button" value="..." onClick="BuscarDx('Dx<? echo $fila8[2]?>','DetDx<? echo $fila8[2]?>')">
		   <? 	if($fila8[2]==1)
					{?>					
					<select name="TipoDx">
					<?
						$cons45="Select TipoDiagnost,Codigo from Salud.TiposDiagnostico where Compania='$Compania[0]'";
						$res45=ExQuery($cons45);
						while($fila45=ExFetch($res45))
						{
							if($fila45[1]==$ValTipoDx){$Selected=" selected ";}else{$Selected="";}
							echo "<option value='$fila45[1]' $Selected>$fila45[0]</option>";
						}
					?>
					</select>
					</td><?	
				}?>
				</tr>				
<?			}?>
			<!--<tr>
            	<td>CausaExterna</td>
           	
            </tr>-->
        <? 	$cons45="select consultaextern from salud.ambitos where compania='$Compania' and ambito='$Ambito'"; 
			$res45=ExQuery($cons);
			$fila45=ExFetch($res45);
			$SiFinalidad=$fila45[0];
			$cons45="select causa,codigo from salud.causaexterna";
			$res45=ExQuery($cons45);
			if(!$CausaExterna){$CausaExterna=$Cusext;}
			?>
			<tr>
            	<td colspan="99"> <strong>Causa Externa </strong>
                	<select name="CausaExterna">
                    <?	while($fila45=ExFetch($res45))
						{
							if($CausaExterna==$fila45[1]){
								echo "<option value='$fila45[1]' selected>$fila45[0]</option>";	
							}
							else{
								echo "<option value='$fila45[1]' >$fila45[0]</option>";	
							}
						}?>
                    </select>               
             
	<?		if(1)
			{
				if(!$FinalidadConsulta){$Finldd;}
				$cons45="select finalidad,codigo from salud.finalidadesact where tipo=1";
				$res45=ExQuery($cons45);	?>
                <strong>
                    Finalidad Consulta </strong>
                        <select name="FinalidadConsulta">
                        <?	while($fila45=ExFetch($res45))
                            {
								if($FinalidadConsulta==$fila45[1]){
	                                echo "<option value='$fila45[1]' selected>".utf8_decode($fila45[0])."</option>";
								}
								else{
									echo "<option value='$fila45[1]'>".utf8_decode($fila45[0])."</option>";
								}
                            }?>
                        </select>              
		<?	}
			echo "</td></tr>";
		}
	}
?>

</table>
<input type="hidden" name="Fecha" value="<? echo $Fecha?>">
<input type="hidden" name="Hora" value="<? echo $Hora?>">
<input type="Hidden" name="Formato" value="<? echo $Formato?>">
<input type="Hidden" name="TipoFormato" value="<? echo $TipoFormato?>">
<input type="Hidden" name="IdPantalla" value="<? echo $IdPantalla?>">
<input type="Hidden" name="IdHistoria" value="<? echo $IdHistoria?>">
<input type="Hidden" name="Registro" value="Siguiente">
<input type="Hidden" name="IdItem" value="<? echo $IdItem?>">
<input type="Hidden" name="SFFormato" value="<? echo $SFFormato?>">
<input type="Hidden" name="SFTF" value="<? echo $SFTF?>">
<input type="Hidden" name="IdHistoOrigen" value="<? echo $IdHistoOrigen?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID ?>">
<input type="hidden" name="SoloUno" value="<? echo $SoloUno?>">
<input type="hidden" name="CUPProced" value="<? echo $CUPProced?>">
<input type="hidden" name="FechaProced" value="<? echo $FechaProced?>">
<input type="hidden" name="NumSerProced" value="<? echo $NumSerProced?>">
<input type="hidden" name="NumProced" value="<? echo $NumProced?>">

</form>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
</body>