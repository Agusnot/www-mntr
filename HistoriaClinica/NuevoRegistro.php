<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	//echo "IdHistoriaOrigen=$IdHistoOrigen -- SFFormato=$SFFormato -- SFTF=$SFTF -- TipoFormato=$TipoFormato -- Formato=$Formato -- IdHistoria=$IdHistoria";
	if($NumSerProced){$NumServicio=$NumSerProced;}
?>
	<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
-->
    </style><body background="/Imgs/Fondo.jpg">
	<p>
	  <script language="javascript" src="/Funciones.js"></script>
	  <script language='javascript' src="/calendario/popcalendar.js"></script>
	  
      <script language="JavaScript">
	function CerrarThis()
	{
		
		parent.document.getElementById('FrameOpenerNP').style.position='absolute';
		parent.document.getElementById('FrameOpenerNP').style.top='1px';
		parent.document.getElementById('FrameOpenerNP').style.left='1px';
		parent.document.getElementById('FrameOpenerNP').style.width='1';
		parent.document.getElementById('FrameOpenerNP').style.height='1';
		parent.document.getElementById('FrameOpenerNP').style.display='none';
		parent.location.href="/HistoriaClinica/Formatos_Fijos/NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>";
		//parent.document.FORMA.submit();
	}
	
	function BuscarDx(Objeto,Objeto2, focus)
	{
		st = document.body.scrollTop;
		frames.FrameOpener.location.href='BuscarDx.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&ControlOrigen='+Objeto+'&DetalleObj='+Objeto2+'&focus='+focus;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=st+30;
		document.getElementById('FrameOpener').style.left='120px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='700';
		document.getElementById('FrameOpener').style.height='400';
	}
	function AgregarMeds(NomCampo)
	{
		st = document.body.scrollTop;
		frames.FrameOpener.location.href='BuscarMeds.php?DatNameSID=<? echo $DatNameSID?>&NomCampo='+NomCampo;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=st+60;
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='660';
		document.getElementById('FrameOpener').style.height='430';
	}
	function AgregarCUPS(NomCampo)
	{
		st = document.body.scrollTop;
		frames.FrameOpener.location.href='BuscarCUPS.php?DatNameSID=<? echo $DatNameSID?>&NomCampo='+NomCampo;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=st+60;
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='660';
		document.getElementById('FrameOpener').style.height='430';
	}
	function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
    </script>
      <?php 
if($Formato=="FORMATO NO POS"){?>
	  <strong>TRAER FORMATO NO POS DE :</strong>
      <select name="menu1" onChange="MM_jumpMenu('self',this,0)">
        <?php
	$consNOPOS="select usuario,fecha,cmp00002,cmp00006,hora from histoclinicafrms.tbl00011 where cedula='$Paciente[1]' order by fecha desc";
	$resNOPOS=ExQuery($consNOPOS);
	while($filaNOPOS=ExFetch($resNOPOS)){
		$consNOMBRENOPOS="select nombre from central.usuarios where usuario='$filaNOPOS[0]'";
		$resNOMBRENOPOS=ExQuery($consNOMBRENOPOS);
		$filaNOMBRENOPOS=ExFetch($resNOMBRENOPOS);
		$selectnopos="";
		if($horanopos!=NULL&&$horanopos==$filaNOPOS[4]){$selectnopos='selected="selected"';}
		echo'<option value="NuevoRegistro.php?DatNameSID='."$DatNameSID".'&Formato=FORMATO NO POS&TipoFormato=Formatos Generales&Frame=&IdHistoOrigen=&SFFormato=&SFTF=&fechanopos='."$filaNOPOS[1]".'&horanopos='."$filaNOPOS[4]".'"'."$selectnopos".'>'."Medicamento: $filaNOPOS[2], Fecha: $filaNOPOS[1], Hora: $filaNOPOS[4], Hecho por: $filaNOMBRENOPOS[0]".'</option>';
	}
	?>
        </select>
      <?php } ?>
	  <?php
	  if($Formato=="REQUISA"){
		$consR="update salud.salasintriage set requisa=1 where cedula='$Paciente[1]'  and estado=1";
		$resR=ExQuery($consR);
	  }
	  
		
		if ($CONDUCTA_A_SEGUIR != NULL) {
		
			$subcadena =  substr($CONDUCTA_A_SEGUIR, 0,4);
			$subcadena = strtoupper($subcadena);
			
				if ($subcadena == "ALTA"){
					if((strtoupper($Formato)=="HOJA DE INGRESO")  ){
				
						$consA="update salud.Servicios set fechaegr='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]', estado = 'AN', usuegreso = '$usuario[1]' WHERE Cedula = '$Paciente[1]' AND estado = 'AC'" ;
						$resA=ExQuery($consA);
					}
				}
		}	
	  
	  
	  
	  if($PRIORIDAD!=null){
		if($Formato=="TRIAGE"){
			$consA="update salud.salasintriage set atender=1, prioridad=$PRIORIDAD,usuariotriage='$usuario[1]' where cedula='$Paciente[1]' and estado=1";
			$resA=ExQuery($consA);
		}
	  }
	  ?>
	<p>
	<iframe scrolling="no" id="FrameFondo" name="FrameFondo" frameborder="0" height="0" width="0" style="filter:Alpha(Opacity=200, FinishOpacity=40, Style=2, StartX=20, StartY=40, FinishX=0, FinishY=0);display:none;border:thin; background-color:transparent" ></iframe>
<iframe id="FrameOpener" name="FrameOpener" style="display:none;border:#e5e5e5 ridge" frameborder="0" height="1" scrolling="auto"></iframe>
<?
	$cons="Select fecnac, sexo, ecivil, eps, tipousu, nivelusu from central.Terceros where Compania='$Compania[0]' and Identificacion='$Paciente[1]'";
	$res=ExQuery($cons);
	$MatDatosPaciente=ExFetch($res);
	$MatDatosPaciente[0]=ObtenEdad($MatDatosPaciente[0]);
	$MatOperadores["Igual a"]="==";
	$MatOperadores["Mayor a"]=">";
	$MatOperadores["Mayor Igual a"]=">=";
	$MatOperadores["Menor a"]="<";
	$MatOperadores["Menor Igual a"]="<=";	
	//echo $MatDatosPaciente[0]." -> ".$MatDatosPaciente[1]."<br>";
	$cons="Select NumServicio,TipoServicio from Salud.Servicios where Cedula='$Paciente[1]' and Estado='AC' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$NumServicio=$fila[0];
	$Ambito=$fila[1];
	if(!$NumServicio){
		$cons2="select NumServicio,TipoServicio from salud.servicios where Cedula='$Paciente[1]' and compania='$Compania[0]' order by NumServicio desc";
		$res2=ExQuery($cons2);
		$fila2=ExFetch($res2);
		if($fila2[0]){
			$NumServicio=$fila[0];
			$Ambito=$fila[1];
		}
	}
	if(!$NumServicio){$NumServicio="-1";}
	//if(!$NumServicio){echo "<em><br><br><br><br><br><center><font size='6' color='red'>No es posible registrar historia clinica sin servicios activos</font></em>";exit;}
	$cons="SELECT Alineacion,tblformat,incluirsignosvitales,FormatoXml,pacienteseguro
	FROM HistoriaClinica.Formatos 
	WHERE Formato='$Formato' and TipoFormato='$TipoFormato' and compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);echo ExError();
	$Alineacion=$fila[0];$Tabla=$fila[1];
	$IncluirSignosVitales=$fila[2];
   	$FormatoXML = $fila[3];
	$PacienteSeguro=$fila[4];
	//echo $IncluirSignosVitales;
	if(!$IdPantalla){$IdPantalla=1;}
	if(!$IdItem){$IdItem=1;}
	
	if(!$IdHistoria){
	//echo "q pasa";
	$cons2="Select Id_Historia from HistoClinicaFrms.$Tabla where Formato='$Formato' and TipoFormato='$TipoFormato' and Cedula='$Paciente[1]' 
	and Compania='$Compania[0]' Group By Id_Historia Order By Id_Historia Desc";
	$res2=ExQuery($cons2,$conex);
	$fila2=ExFetch($res2);
	$IdHistoria=$fila2[0]+1;}

	if (isset($Registro))
	{
		if(!$Fecha){$Fecha="$ND[year]-$ND[mon]-$ND[mday]";}
		if(!$Hora){$Hora="$ND[hours]:$ND[minutes]:$ND[seconds]";}

		$cons3="Select RM,Cargo from Salud.Medicos,salud.cargos where Usuario='$usuario[1]' and Medicos.compania='$Compania[0]' and cargos.compania='$Compania[0]'
		and cargos.asistencial=1 and (tratante=1 or vistobuenojefe=1 or vistobuenoaux=1)";
		$res3=ExQuery($cons3,$conex);
		$fila3=ExFetch($res3);
		$RM=$fila3[0];$Cargo=$fila3[1];
		
		$cons="Select * from Salud.PacientesxPabellones where Cedula='$Paciente[1]' and Estado='AC' and NumServicio=$NumServicio and Compania='$Compania[0]'";
		$res=ExQuery($cons,$conex);
		$fila=ExFetchArray($res);
		$Unidad=$fila['pabellon'];

///////////////// INSERCION DE DATOS A LA HISTORIA CLINICA /////////////////////////////

		$cons="Select Item,TipoDato,LimInf,LimSup,Obligatorio,LineaSola,CierraFila,Titulo,TipoControl,Mensaje,Id_Item,SubFormato,cargoxitem 
		from HistoriaClinica.ItemsxFormatos where Formato='$Formato' and TipoFormato='$TipoFormato' 
		and Pantalla=$IdPantalla and Compania='$Compania[0]' AND UPPER(TipoControl) IS DISTINCT FROM 'MEDICAMENTOS MULTILINEA' AND UPPER(TipoControl) IS DISTINCT FROM 'MEDICAMENTOS UNILINEA' AND UPPER(TipoControl) IS DISTINCT FROM 'MEDICAMENTOS FORMULA'  AND  estado='AC'  Order By Id_Item";		
		
		$res=ExQuery($cons,$conex);echo ExError($conex);
		while($fila=ExFetch($res)) {
			//validaciones---
			if($fila[7]==0&&$fila[11]==0)
			{			
				if($fila[12]==""||$fila[12]==$Cargo)
				{					
					$MatItemxCargo[$fila[10]]=array($fila[10],$fila[0],$fila[12]);
				}
				$consxx="select condedad1, edad1, condedad2, edad2, sexo, estadocivil, eps, tipousuario, nivel 
				from historiaclinica.dependenciahc where Compania='$Compania[0]' and Formato='$Formato' and Id_Item=$fila[10] 
				and Item='$fila[0]' and TipoFormato='$TipoFormato'";
				//echo $consxx."<br>";
				$resxx=ExQuery($consxx);
				while($filaxx=ExFetch($resxx))
				{					
					$MatDependenciaxItem[$fila[10]]=array($filaxx[0],$filaxx[1],$filaxx[2],$filaxx[3],$filaxx[4],$filaxx[5],$filaxx[6],$filaxx[7],$filaxx[8]);					
				}				
				$IdItem=$fila[10];
				$Campo=str_replace("/","-",$fila[0]);
				$Campo=str_replace(" ","_",$Campo);
				//--nuevos simbolos
				$Campo=str_replace(".","_",$Campo);
				$Campo=str_replace(",","_",$Campo);
				$Campo=str_replace(":","_",$Campo);
				$Campo=str_replace(";","_",$Campo);
				$Campo=str_replace("(","_",$Campo);
				$Campo=str_replace(")","_",$Campo);
				//echo $Campo."<br>";
				if(!$MatDependenciaxItem[$IdItem]&&$MatItemxCargo[$IdItem])
				{
					//echo $Campo."<br>";
					if($fila[8]=="Cuadro de Chequeo" && !(trim($_POST["$Campo"]))){$_POST["$Campo"]="No";}
					if(!$_FILES["$Campo"]['tmp_name'])
					{
						if($fila[4]=="1" && !(trim($_POST["$Campo"])))
						{													
							$Registro="Quieto";?>
							<script language="JavaScript">
								alert("No puede dejar el campo <? echo $Campo?> en blanco!!!");
								//return false;
							</script>							
							<?		
						}						
					}
					
					if($fila[1]=="N" && ($_POST[$Campo]>$fila[3] || $_POST[$Campo]<$fila[2]))
					{	$Registro="Quieto";$_POST[$Campo]="";?>
						<script language="JavaScript">
							alert("El valor del campo <? echo $Campo?> se encuentra fuera de limite!!!");
							
						</script>
		<?			}
				}				
				if($fila[8]=="PDF")
				{
					$rutaOrigen= $_FILES[$Campo]['tmp_name'];
					$raiz=$_SERVER['DOCUMENT_ROOT'];			
					$rutaFinal="$raiz/HistoriaClinica/ImgsLabs/$Paciente[1]/";
					$rutaFinal=$rutaFinal.$_FILES[$Campo]['name'];							
					if($_FILES[$Campo]['name'])
					{							
						if(!is_dir("$raiz/HistoriaClinica/ImgsLabs/$Paciente[1]/"))
						{
							mkdir("$raiz/HistoriaClinica/ImgsLabs/$Paciente[1]/",0777);	
						}
						//echo $rutaOrigen."  --  ".$rutaFinal."<br>";
						if (is_uploaded_file($_FILES["$Campo"]['tmp_name'])) 
						{			
							if(strpos($_FILES["$Campo"]['type'], "pdf"))
							{ 								
								copy("$rutaOrigen","$rutaFinal");
								$_POST[$Campo]=$rutaFinal;								
								if(is_file(${"pdf".$IdItem}))		
								{
									unlink(${"pdf".$IdItem});
									//echo ${"pdf".$IdItem};
									//exit;
								}
							}
							else
							{
								$Registro="Quieto";?>
								<script language="JavaScript">
									alert("El archivo no es tipo PDF!!!");
								</script><?
							}
						}
						else
						{							
							$Registro="Quieto";?>
							<script language="JavaScript">
								alert("No se pudo subir el archivo!!!");
							</script>
					<?	}
					}
					elseif(is_file(${"pdf".$IdItem}))	
					{
						//echo ${"pdf".$IdItem};						
						$_POST[$Campo]=${"pdf".$IdItem};						
						//exit;
					}				
				}
				if($MatItemxCargo[$fila[10]])
				{				
					$NumCampo=substr("00000",0,5-strlen($fila[10])).$fila[10];
					$ListCmpInsert=$ListCmpInsert."CMP".$NumCampo.",";					
					if($_POST["$Campo"]!="")
					{
						$CmpInsert=$CmpInsert."'".$_POST["$Campo"]."',";				
						$LisCmpUpdt=$LisCmpUpdt."CMP".$NumCampo."='".$_POST["$Campo"]."',";
					}
					else
					{
						$_POST[$Campo]='NULL';
						$CmpInsert=$CmpInsert.$_POST["$Campo"].",";				
						$LisCmpUpdt=$LisCmpUpdt."CMP".$NumCampo."=".$_POST["$Campo"].",";
					}
									
				}
				//echo $LisCmpUpdt." $Campo <br>";
			}	
			if($fila[7]==1 && $fila[0]=='Diagnostico') ///////////////////ASIGNACION DIAGNOSTICA
			{
				$Campo="TipoDx";
				$LisCmpUpdt=$LisCmpUpdt."TipoDx='".$_POST["TipoDx"]."',";
				$ListCmpInsert=$ListCmpInsert."TipoDx,";
				$CmpInsert=$CmpInsert."'".$_POST["TipoDx"]."',";
								 
				$Campo="finalidadconsult";
				$LisCmpUpdt=$LisCmpUpdt."finalidadconsult='".$_POST["FinalidadConsulta"]."',";
				$ListCmpInsert=$ListCmpInsert."finalidadconsult,";
				$CmpInsert=$CmpInsert."'".$_POST["FinalidadConsulta"]."',";
				
				$Campo="causaexterna";
				$LisCmpUpdt=$LisCmpUpdt."causaexterna='".$_POST["CausaExterna"]."',";
				$ListCmpInsert=$ListCmpInsert."causaexterna,";
				$CmpInsert=$CmpInsert."'".$_POST["CausaExterna"]."',";
				
				/*if($_POST["Dx2"])
				{
					$LisCmpUpdt=$LisCmpUpdt."dx2='".$_POST["Dx2"]."',";
					$ListCmpInsert=$ListCmpInsert."dx2,";
					$CmpInsert=$CmpInsert."'".$_POST["Dx2"]."',";					
				}
				if($_POST["Dx3"])
				{
					$LisCmpUpdt=$LisCmpUpdt."dx3='".$_POST["Dx3"]."',";
					$ListCmpInsert=$ListCmpInsert."dx3,";
					$CmpInsert=$CmpInsert."'".$_POST["Dx3"]."',";
				}*/
				if($NumServicio)
				{
					$consDxLiq="select detalleliquidacion.codigo,liquidacion.noliquidacion,detalleliquidacion.grupo,cups.grupo,numservicio,finalidad
					,causaext,dxppal,tipodxppal,dxrel1,dxrel2
					from facturacion.detalleliquidacion,contratacionsalud.cups,facturacion.liquidacion
					where cups.compania='$Compania[0]' and detalleliquidacion.compania='$Compania[0]' and liquidacion.compania='$Compania[0]'
					and cups.codigo=detalleliquidacion.codigo and detalleliquidacion.noliquidacion=liquidacion.noliquidacion
					and cups.tipo='00004' and numservicio=$NumServicio and 
					(finalidad='' or finalidad is null or causaext='' or causaext is null or dxppal='' or dxppal is null or tipodxppal='' or tipodxppal is null)";
					$resDxLiq=ExQuery($consDxLiq);
					while($filaDxLiq=ExFetch($resDxLiq))
					{	
						$FinalidDxLiq=""; $CausaExtDxLiq=""; $DxPpalLiq=""; $TipoDxLiq=""; $DxRel1lLiq=""; $DxRel2lLiq="";
						if(!$filaDxLiq[5]||$filaDxLiq[5]==''){$FinalidDxLiq=",finalidad='".$_POST["FinalidadConsulta"]."'";}
						if(!$filaDxLiq[6]||$filaDxLiq[6]==''){$CausaExtDxLiq=",causaext='".$_POST["CausaExterna"]."'";}
						if(!$filaDxLiq[7]||$filaDxLiq[7]==''){$DxPpalLiq=",dxppal='".$_POST["Dx1"]."'";}						
						if(!$filaDxLiq[8]||$filaDxLiq[8]==''){$TipoDxLiq=",tipodxppal='".$_POST["TipoDx"]."'";}
						if((!$filaDxLiq[9]||$filaDxLiq[9]=='')&&$_POST["Dx2"]&&strlen($_POST["Dx2"])<=4){$DxRel1lLiq=",dxrel1='".$_POST["Dx2"]."'";}
						if((!$filaDxLiq[10]||$filaDxLiq[10]=='')&&$_POST["Dx3"]&&strlen($_POST["Dx3"])<=4){$DxRel2lLiq=",dxrel2='".$_POST["Dx3"]."'";}
						$consDxLiq2="update facturacion.detalleliquidacion set tipo='00004',grupo='$filaDxLiq[2]' 
						$FinalidDxLiq $CausaExtDxLiq $DxPpalLiq $TipoDxLiq $DxRel1lLiq $DxRel2lLiq
						where compania='$Compania[0]' and noliquidacion=$filaDxLiq[1] and codigo='$filaDxLiq[0]'";						
						$resDxLiq2=ExQuery($consDxLiq2);						
					}
				}
				if(!$_POST['Dx1'])
				{
					$Registro="Quieto"?>
					<script language="JavaScript">
						alert("No puede dejar el Diagnostico Principal en blanco!!!");
					</script>
					
	<?			}
				$cons8="Select Detalle,CIE10,Id from historiaclinica.dxformatos where Compania='$Compania[0]' and Estado='AC' and Formato='$Formato' and TipoFormato='$TipoFormato' Order By Id";			
				$res8=ExQuery($cons8);
				$BanDx=0;
				while($fila8=ExFetch($res8))
				{					
					if($BanDx==0){
						if($Paciente[1]){
							$consServ="select numservicio,dxserv from salud.servicios where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC'";
							$resServ=ExQuery($consServ);
							$filaServ=ExFetch($resServ);
							if($filaServ[0]){
								$BanDx=1;
								if(!$filaServ[1]){
									$consActuServ="update salud.servicios set dxserv='".$_POST['Dx1']."' 
									where compania='$Compania[0]' and cedula='$Paciente[1]' and numservicio=$filaServ[0]"; 
									$resActuServ=ExQuery($consActuServ);
								}
							}
						}
					}
					$Campo="Dx".$fila8[2];
					$LisCmpUpdt=$LisCmpUpdt."Dx".$fila8[2]."='".$_POST["$Campo"]."',";

					$ListCmpInsert=$ListCmpInsert."Dx".$fila8[2].",";
					$CmpInsert=$CmpInsert."'".$_POST["$Campo"]."',";
					//ver si es DX CIE10 O DX ABIERTO
					if($fila8[1]=="0")
					{
						$ColAlter="Dx".$fila8[2];
						$consalter="ALTER TABLE HistoClinicaFrms.$Tabla ALTER  COLUMN  $ColAlter TYPE text";
						//echo $consalter;
						$res=ExQuery($consalter);							
					}

				}
				if($Paciente[1]){					
					$consServ="select numservicio from salud.servicios where compania='$Compania[10]' and cedula='$Paciente[0]' and estado='AC'";
					$resServ=ExQuery($consServ);
					$filaServ=ExFetch($resServ);
					if($filaServ[0]){
						
					}
				}
			}
			
		}
		$ListCmpInsert=substr($ListCmpInsert,0,strlen($ListCmpInsert)-1);
		$CmpInsert=substr($CmpInsert,0,strlen($CmpInsert)-1);
		$LisCmpUpdt=substr($LisCmpUpdt,0,strlen($LisCmpUpdt)-1);
		if(!$CompletarFormato){$PartSelecNu="and usuario='$usuario[1]'";}
		$cons9="Select * from HistoClinicaFrms.$Tabla where Formato='$Formato' and TipoFormato='$TipoFormato' and Id_Historia=$IdHistoria and Cedula='$Paciente[1]' 
		and Compania='$Compania[0]' $PartSelecNu";
		//echo $cons9."<br>";
		//exit;
		$res9=ExQuery($cons9,$conex);
		if(ExNumRows($res9)==0)
		{			
			$consId="select id_historia from HistoClinicaFrms.$Tabla where Formato='$Formato' and TipoFormato='$TipoFormato' and compania='$Compania[0]' order by Id_historia desc";
			$resId=ExQuery($consId);
			$filaId=ExFetch($resId);
			$IdHistoria=$filaId[0]+1;
			if(!$Ambito){$Ambito='Sin Ambito';}
			if(!$IdHistoOrigen){$IdHistoOrigen="NULL";$SFFormato="";$SFTF="";}
			if($fila[8]=="Imagen"){$Imagen=$_POST["$Campo"];}else{$Imagen="";}
			if(!$CUP || $CUP=="NULL"){$CUP="NULL";}else{$CUP="'$CUP'";}
			if(!$Unidad || $Unidad=="NULL"){$Unidad="NULL";}else{$Unidad="'$Unidad'";}
			if(!$NumServicio){$NumServicio="NULL";}if(!$fila[5]){$fila[5]="NULL";}if(!$fila[6]){$fila[6]="NULL";}if(!$fila[7]){$fila[7]="NULL";}
			if($NumProced){$Proced1=",numproced";$Proced2=",$NumProced";}
			//---valida cambo usucargo			
			$NewCampUsu="usu".strtolower(str_replace(" ","",$Cargo));								
			$consCampUsu="select column_name from information_schema.columns where table_name = '$Tabla' and column_name='$NewCampUsu';";
			$resCampUsu=ExQuery($consCampUsu);
			if(ExNumRows($resCampUsu)>0)
			{
				$ParInsUsuxCargo=", $NewCampUsu";
				$ParInsDatUsuxCargo=", '$usuario[1]'";
			}
			//---Validacion subformatos
			$conssf="select column_name from information_schema.columns where table_name = '$Tabla' and column_name='padretipoformato'";
			$ressf=ExQuery($conssf);
			if(ExNumRows($ressf)==0)
			{	
				$conssf="ALTER TABLE histoclinicafrms.$Tabla ADD COLUMN padretipoformato character varying(150), ADD COLUMN padreformato character varying(150),
				ADD COLUMN id_historia_origen integer";	
				$ressf=ExQuery($conssf);
			}	
			//----
			if(!$IdSVital){$IdSVital="NULL";}	
			//echo 	$ListCmpInsert;	
			$cons1="Insert into HistoClinicaFrms.$Tabla(Formato,Id_Historia,Usuario,Fecha,Hora,Cedula,Ambito,UnidadHosp,TipoFormato,Cargo,NumServicio,Compania,$ListCmpInsert $Proced1 $ParInsUsuxCargo , padretipoformato, padreformato, id_historia_origen,idsvital)
			values('$Formato',$IdHistoria,'$usuario[1]','$Fecha','$Hora','$Paciente[1]','$Ambito',$Unidad,'$TipoFormato','$Cargo',$NumServicio,'$Compania[0]',$CmpInsert $Proced2 $ParInsDatUsuxCargo, '$SFTF','$SFFormato',$IdHistoOrigen, $IdSVital)";
			if($IdSVital=="NULL"){$IdSVital="";}
			//echo $cons1."<br>";
		}
		else
		{			
			$NewCampUsu="usu".strtolower(str_replace(" ","",$Cargo));								
			$consCampUsu="select column_name from information_schema.columns where table_name = '$Tabla' and column_name='$NewCampUsu';";
			$resCampUsu=ExQuery($consCampUsu);
			if(ExNumRows($resCampUsu)>0)
			{
				$ParUpdUsuxCargo=", $NewCampUsu='$usuario[1]'";				
			}
			$cons1="Update HistoClinicaFrms.$Tabla set $LisCmpUpdt $ParUpdUsuxCargo
			where Formato='$Formato' and TipoFormato='$TipoFormato' and Id_Historia=$IdHistoria and Cedula='$Paciente[1]' and Compania='$Compania[0]'";
			//echo $cons1."<br>";
		}
		if($LisCmpUpdt)
		{			
			$res1=ExQuery($cons1);
			
			$MsjError=ExError();			
			if($MsjError){echo "<br><em>Favor copie este mensaje y envielo al correo interno de sistemas para su revision</em><br>";exit;}
			else{
				// Después de guardar, si es NOTA EVOLUCION verifica si tenía interconsultas
                if($Formato=='NOTAS EVOLUCION'){
                    $cons="select agendainterna.cedula,primape,segape,primnom,segnom,fecultima,fecproxima,servicios.numservicio 
                        from salud.agendainterna,salud.servicios,central.terceros 
                        where agendainterna.compania='$Compania[0]' and profecional='$usuario[1]' and terceros.compania='$Compania[0]' and servicios.cedula=identificacion
                        and fecproxima<='$ND[year]-$ND[mon]-$ND[mday]' and estado='AC' and agendainterna.numservicio=servicios.numservicio and servicios.compania='$Compania[0]'
                        and agendainterna.cedula='$Paciente[1]'";
                    $res=ExQuery($cons);
                                    
                    if(ExNumRows($res)>0){
                        $fila=ExFetchAssoc($res);
                                        
                        $tdate = date("Y-m-d", strtotime(date("Y-m-d")." +30 days"));
                                        
                        $consInterCons="update salud.agendainterna set fecultima='$ND[year]-$ND[mon]-$ND[mday]', fecproxima='$tdate'
                            where compania='$Compania[0]' and numservicio=$NumServicio and cedula='$Paciente[1]' and profecional='$usuario[1]'";
                        $resInterCons=ExQuery($consInterCons);
                        $filaInterCons=ExFetch($resInterCons);
                        //echo $consInterCons;
                    }
                }
                                
				if(!$NumServicio){$NumServicio="NULL";}
				/*$consAgendInt="select especialidad from salud.medicos where compania='$Compania[0]' and usuario='$usuario[1]'";
				$resAgendInt=ExQuery($consAgendInt); $filaAgendaInt=ExFetch($resAgendInt); $EspMed=$filaAgendaInt[0];*/
				$consAgendInt="select tiposervicio from salud.servicios where cedula='$Paciente[1]' and numservicio=$NumServicio";
				$resAgendInt=ExQuery($consAgendInt);
				$filaAgendaInt=ExFetch($resAgendInt);
				$AmbitoAgendInt=$filaAgendaInt[0];
				
				$consAgendInt="select entidad,contrato,nocontrato from salud.pagadorxservicios where compania='$Compania[0]' and fechaini<='$Fecha' 
				and (fechafin>='$Fecha' or fechafin is null) and numservicio=$NumServicio";
				$resAgendInt=ExQuery($consAgendInt);
				$filaAgendaInt=ExFetch($resAgendInt);
				//echo $consAgendInt."<br>";
				
				$consAgendInt="select frecuencia from contratacionsalud.frecagendainterna where compania='$Compania[0]' and formato='$Formato' 
				and especialidad='$TipoFormato' and entidad='$filaAgendaInt[0]' and contrato='$filaAgendaInt[1]' and numero='$filaAgendaInt[2]' and ambito='$AmbitoAgendInt'";
				//echo $consAgendInt; exit;
				$resAgendInt=ExQuery($consAgendInt);
				$filaAgendaInt=ExFetch($resAgendInt);
				$FrecAgendaInt=$filaAgendaInt[0];
				if($FrecAgendaInt)
				{
					$consAgendInt="select fecproxima from salud.agendainterna where compania='$Compania[0]' and numservicio=$NumServicio and cedula='$Paciente[1]'
					and profecional='$usuario[1]' and especialidad='$TipoFormato'";
					$resAgendInt=ExQuery($consAgendInt);
					$filaAgendaInt=ExFetch($resAgendInt);
					$FecProxAgendaInt=$filaAgendaInt[0];
					//echo $consAgendInt;
					if($FecProxAgendaInt)
					{						
						$dia = $ND[mday];
						$mes = $ND[mon];
						$anio = $ND[year];
						$sumar = $FrecAgendaInt; # cantidad de dias a sumar
						$fechaProx = date("d/m/y", mktime(0,0,0,$mes,$dia+$sumar,$anio)); 
						$FProx=$fechaProx;
						$FProx=str_replace("/","-",$FProx);
						$FProx=explode("-",$FProx); 
						$consAgendInt="update salud.agendainterna set fecultima='$ND[year]-$ND[mon]-$ND[mday]', fecproxima='20$FProx[2]-$FProx[1]-$FProx[0]'
						where compania='$Compania[0]' and numservicio=$NumServicio and cedula='$Paciente[1]'	and profecional='$usuario[1]' and especialidad='$TipoFormato'";
						$resAgendInt=ExQuery($consAgendInt); //echo $consAgendInt;
   						//exit;
					}
				}			
			}
		}
///////////////// FIN INSERCION DE DATOS A LA HISTORIA CLINICA /////////////////////////////		

////////////////////////////////////////ASIGNACION DE CUPS////////////////////////////////
		$cons="Select Id_Item,Item
		from HistoriaClinica.ItemsxFormatos where Formato='$Formato' and TipoFormato='$TipoFormato' 
		and Pantalla=$IdPantalla and (Titulo IS NULL or titulo = 0)  and Compania='$Compania[0]'  AND UPPER(TipoControl) IS DISTINCT FROM  'MEDICAMENTOS MULTILINEA' AND UPPER(TipoControl) IS DISTINCT FROM 'MEDICAMENTOS UNILINEA'  AND UPPER(TipoControl) IS DISTINCT FROM 'MEDICAMENTOS FORMULA'  and Compania='$Compania[0]'and estado='AC' Order By orden";
		$res=ExQuery($cons);echo ExError($conex);
		while($fila=ExFetch($res))
		{
			$Campo=str_replace("/","-",$fila[1]);
			$Campo=str_replace(" ","_",$Campo);
			//nuevos simbolos
			$Campo=str_replace(".","_",$Campo);
			$Campo=str_replace(",","_",$Campo);
			$Campo=str_replace(":","_",$Campo);
			$Campo=str_replace(";","_",$Campo);
			$Campo=str_replace("(","_",$Campo);			
			$Campo=str_replace(")","_",$Campo);
			if($NumServicio){
				$cons11="Delete from histoclinicafrms.cupsxfrms 
				where formato='$Formato' and tipoformato='$TipoFormato' and id_historia=$IdHistoria and cedula='$Paciente[1]' and compania='$Compania[0]'
				and numservicio=$NumServicio and id_item=$fila[0]";
				$res11=ExQuery($cons11);	
			}

			if($_POST["$Campo"])
			{
				$cons9="Select CUP from HistoriaClinica.CUPSxFormatos where TipoFormato='$TipoFormato' and Formato='$Formato' and (Cargo='$Cargo' Or Cargo='') and Compania='$Compania[0]'
				and Item=$fila[0] and (VrItem='' Or VrItem='" . $_POST["$Campo"]."')";
				$res9=ExQuery($cons9);
				if(ExNumRows($res9)>0&&$Registro!="Quieto")
				{
					
					$fila9=ExFetch($res9);
					$CUP=$fila9[0];
					$cons10="Select * from histoclinicafrms.cupsxfrms where TipoFormato='$TipoFormato' and Formato='$Formato' and Id_Historia=$IdHistoria and Cedula='$Paciente[1]' 
					and Compania='$Compania[0]'";
					$res10=ExQuery($cons10);
					if($NumSerProced){
						$cons11="Insert into histoclinicafrms.cupsxfrms (formato, tipoformato, id_historia, cedula, compania, numservicio,cup, id_item)
						values('$Formato','$TipoFormato',$IdHistoria,'$Paciente[1]','$Compania[0]',$NumSerProced,'$CUP',$fila[0])";
					}
					else
					{
						$cons11="Insert into histoclinicafrms.cupsxfrms (formato, tipoformato, id_historia, cedula, compania, numservicio,cup, id_item)
						values('$Formato','$TipoFormato',$IdHistoria,'$Paciente[1]','$Compania[0]',$NumServicio,'$CUP',$fila[0])";
					}
					
					$res11=ExQuery($cons11);	
					$cons11="select quirurgico from contratacionsalud.cups where codigo='$CUP'"; 
					$res11=ExQuery($cons11);	
					$fila11=ExFetch($res11); 					
					$Quirugico = $fila11[0];				
					
					// 13 de marzo de 2014
                    // Se comenta para que no solicite actualización de RIPS en un nuevo frame
					//$BanCUP=1; //El cupo es de tipo quirurgico, se debe seleccionar el forma de realizacion?>
					<!--
					<script language="javascript">
						document.getElementById('FrameFondo').style.position='absolute';
						document.getElementById('FrameFondo').style.top='1px';
						document.getElementById('FrameFondo').style.left='1px';
						document.getElementById('FrameFondo').style.display='';
						document.getElementById('FrameFondo').style.width='100%';
						document.getElementById('FrameFondo').style.height='100%';
				
						frames.FrameOpener.location.href="CompletaCUPSHC.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&IdHistoria=<? echo $IdHistoria?>&NumServicio=<? echo $NumServicio?>&Quirugico=<? echo $Quirugico?>&CUP=<? echo $CUP?>&SubFormato=<? echo $SFFormato?>&IdHistoOrigen=<? echo $IdHistoOrigen?>&SFTF=<? echo $SFTF?>&SoloUno=<? echo $SoloUno?>";
						document.getElementById('FrameOpener').style.position='absolute';
						document.getElementById('FrameOpener').style.top='20%';
						document.getElementById('FrameOpener').style.left='30%';
						document.getElementById('FrameOpener').style.display='';
						document.getElementById('FrameOpener').style.width='650';
						document.getElementById('FrameOpener').style.height='390';	
					</script>
					-->
			<?		
				}
			}
		}
		//////////////////////////////FIN ASIGNACION DE CUPS /////////////////////////		
		if($Registro=="Siguiente"){$IdPantalla++;}
		if($Registro=="Anterior"){$IdPantalla=$IdPantalla-1;$Edit=1;}
        }
	
		
	$cons="Select Pantalla from HistoriaClinica.ItemsxFormatos where Formato='$Formato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]' 
	and estado='AC' group By Pantalla Order By Pantalla Desc";
	$res=ExQuery($cons,$conex);
	$fila=ExFetch($res);$NPantallas=$fila[0];
	if($IdPantalla>$fila[0])
	{
		if($NumProced&&$SoloUno){
			$cons="update salud.plantillaprocedimientos 
			set fechalab='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',formato='$Formato',tipoformato='$TipoFormato'
			,id_historia=$IdHistoria where compania='$Compania[0]' and cedula='$Paciente[1]' and numservicio=$NumSerProced 
			and numprocedimiento=$NumProced and cup='$CUPProced'";
			$res=ExQuery($cons);		
			//echo $cons;
			//exit;
		}
		if($SoloUno){
			$SoloUno=$IdHistoria;
			?><script language="javascript">
				opener.document.FORMA.submit();
			</script><?
		}
		if($MedNP==1){   			
   			$cons="update salud.plantillamedicamentos 
			set tipoformato='$TipoFormato',formato='$Formato',id_historia=$IdHistoria
			where compania='$Compania[0]' and almacenppal='$AlmacenPpal' and autoidprod=$AutoIdProd and cedpaciente='$Paciente[1]' and
			tipomedicamento='$TipoMedicamento' and estado='AC' and numservicio=$NumSer";
			//echo $cons;
			$res=ExQuery($cons);
		?>	<script language="javascript">
				parent.location.href="/HistoriaClinica/Formatos_Fijos/NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>";
          	</script><?
   		}
		if($ProcedNP==1){  
		
   			$cons="update salud.plantillaprocedimientos 
			set tipoformato='$TipoFormato',formato='$Formato',id_historia=$IdHistoria
			where compania='$Compania[0]' and numservicio=$NumSer and numprocedimiento=$NumProc and cedula='$Paciente[1]' and
			cup='$CUPNP' and estado='AC' and fechaini='$FechaI'";
			//echo $cons;
			$res=ExQuery($cons);
		?>	<script language="javascript">
				parent.location.href="/HistoriaClinica/Formatos_Fijos/NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>";
          	</script><?
   		}
		else{
			if(!$BanCUP){
				if($PacienteSeguro=="Si")
				{
					$consps="select max(idsuceso) from pacienteseguro.sucesos";
					$resps=ExQuery($consps);
					$filaps=ExFetch($resps);
					if($filaps[0]==NULL){
						$filaps[0]=0;
					}
					$filaps[0]++;
				
					$Unidad=str_replace("'","",$Unidad);
					$cons="insert into pacienteseguro.sucesos (compania,usuario,fechacrea,formato,tipoformato,idhistoria,cedula,pabellon,ambito,idsuceso) values
					('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Formato','$TipoFormato',$IdHistoria,'$Paciente[1]'
					,'$Unidad','$Ambito','$filaps[0]')";
					$res=ExQuery($cons);
				}
			?>	
            <? 		if($FormatoXML)
                  	{   ?>
                    <script language="JavaScript">
                     	open('VerXML.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&IdHistoria=<? echo $IdHistoria?>','','');			</script>
              	<?	}
					$cons="select codigo,liquidacion.noliquidacion from facturacion.detalleliquidacion,facturacion.liquidacion
					where liquidacion.compania='$Compania[0]' and numservicio=$NumServicio and estado='AC' and detalleliquidacion.compania='$Compania[0]'
					and detalleliquidacion.noliquidacion=liquidacion.noliquidacion and tipo='00004' 
					and (dxppal is null or dxppal='' or finalidad='' or finalidad is null or causaext is null or causaext='')";					
					$res=ExQuery($cons);
					if(ExNumRows($res)>0){	?>
                    	
                    	<script language="JavaScript">
	                    	document.getElementById('FrameFondo').style.position='absolute';
							document.getElementById('FrameFondo').style.top='1px';
							document.getElementById('FrameFondo').style.left='1px';
							document.getElementById('FrameFondo').style.display='';
							document.getElementById('FrameFondo').style.width='100%';
							document.getElementById('FrameFondo').style.height='100%';
					
							frames.FrameOpener.location.href="CompletaCupsFacs.php?DatNameSID=<? echo $DatNameSID?>&SFFormato=<? echo $SFFormato?>&IdHistoOrigen=<? echo $IdHistoOrigen?>&SFTF=<? echo $SFTF?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&SoloUno=<? echo $SoloUno?>&NumServicio=<? echo $NumServicio?>&Frame=<? echo $Frame?>";
							document.getElementById('FrameOpener').style.position='absolute';
							document.getElementById('FrameOpener').style.top='20%';
							document.getElementById('FrameOpener').style.left='1';
							document.getElementById('FrameOpener').style.display='';
							document.getElementById('FrameOpener').style.width='100%';
							document.getElementById('FrameOpener').style.height='390';	
						</script>
				<? 	}	
					else
					{?>
                    <script language="JavaScript">
						location.href='Datos.php?DatNameSID=<? echo $DatNameSID?>&SFFormato=<? echo $SFFormato?>&IdHistoOrigen=<? echo $IdHistoOrigen?>&SFTF=<? echo $SFTF?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&SoloUno=<? echo $SoloUno?>&NumSerProced=<? echo $NumSerProced?>&Frame=<? echo $Frame?>';
					</script>              	
<?					}
			}
		}
	}
//-----Agregar Campo IdSignosVitales	
$conssv="select column_name from information_schema.columns where table_name = '$Tabla' and column_name='idsvital'";
$ressv=ExQuery($conssv);
if(ExNumRows($ressv)==0)
{	
	$conssv="ALTER TABLE histoclinicafrms.$Tabla ADD COLUMN idsvital numeric";	
	$ressv=ExQuery($conssv);
}
else
{
	if(empty($IdSVital))
	{	
		$conssv="Select idsvital from histoclinicafrms.$Tabla where  Formato='$Formato' and TipoFormato='$TipoFormato' and Id_Historia=$IdHistoria  
		and Compania='$Compania[0]' and Cedula='$Paciente[1]'";
		//echo $conssv."<br>";
		$ressv=ExQuery($conssv);
		$filasv=ExFetch($ressv);$IdSVital=$filasv[0];
	}
}
//-----	
if($IncluirSignosVitales)
{
	if($IdSVital)
	{
		$conssv="Select AutoId,Fecha,Usuario,Temperatura,Pulso,Respiracion,TensionArterial1,TensionArterial2 from historiaclinica.signosvitales 
		where Compania='$Compania[0]' and Cedula='$Paciente[1]' and autoid=$IdSVital";
	}
	else
	{
		$conssv="Select AutoId,Fecha,Usuario,Temperatura,Pulso,Respiracion,TensionArterial1,TensionArterial2 from historiaclinica.signosvitales 
		where Compania='$Compania[0]' and Cedula='$Paciente[1]' and fecha>='$ND[year]-$ND[mon]-$ND[mday] 00:00:00'
		and fecha<='$ND[year]-$ND[mon]-$ND[mday] 23:59:59'order by AutoId Desc";
	}
	//echo $conssv;
	$ressv=ExQuery($conssv);
	$filasv=ExFetch($ressv);
	if($filasv)
	{
		if(!$IdSVital){$IdSVital=$filasv[0];}
		$cons3="Select usuarios.nombre, Cargo from central.usuarios,Salud.Medicos,salud.cargos where usuarios.usuario='$filasv[2]' 
		and usuarios.usuario=medicos.usuario and Medicos.compania='$Compania[0]' and cargos.compania='$Compania[0]' and cargos.asistencial=1 
		and (tratante=1 or vistobuenojefe=1 or vistobuenoaux=1) limit 1";
		$res3=ExQuery($cons3);
		$fila3=ExFetch($res3);
		?>
		<table cellspacing="4" border="1" bordercolor="#e5e5e5" style="font : normal normal small-caps 12px Tahoma; " > 
		<tr bgcolor='#e5e5e5' style="font-weight:bold">
		<td colspan="4">SIGNOS VITALES: <? echo $filasv[1]?></td>
		<td colspan="4">Registró: <? echo $fila3[0]?> - <? echo $fila3[1]?></td>
		</tr>
		<tr >
		<td bgcolor='#e5e5e5' style="font-weight:bold">Temperatura (ºC)</td>
		<td><? echo $filasv[3]?></td>
		<td bgcolor='#e5e5e5' style="font-weight:bold">Pulso (x min)</td>
		<td><? echo $filasv[4]?></td>
		<td bgcolor='#e5e5e5' style="font-weight:bold">Respiración (x min)</td>
		<td><? echo $filasv[5]?></td>
		<td bgcolor='#e5e5e5' style="font-weight:bold">Tension Arterial</td>
		<td><? echo $filasv[6]?>/<? echo $filasv[7]?></td>
		</tr>
		</table>
		<?
	}
}?>	
	<div align="center">
		<?php 
			$anchoTabla = definirTipoControlEspeciales($Formato,$TipoFormato);
			
		?>
	<table border="1" cellspacing = "0" cellpadding="5" bordercolor="#e5e5e5" width="<?php echo $anchoTabla;?>" style="font : 12px Tahoma;"> 
	<form name="FORMA" method="POST" id="formNuevoRegistro" enctype="multipart/form-data"> 
	<?
	if($MedicamentoXX){?>
		<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
	<? 
	}
		if($IdHistoria)
		{
			$NumCampo=substr("00000",0,5-strlen($IdItem)).$IdItem;
			//---Cargo 
			$cons3="Select RM,Cargo from Salud.Medicos,salud.cargos where Usuario='$usuario[1]' and Medicos.compania='$Compania[0]' and cargos.compania='$Compania[0]'
			and cargos.asistencial=1 and (tratante=1 or vistobuenojefe=1 or vistobuenoaux=1)";
			$res3=ExQuery($cons3,$conex);
			$fila3=ExFetch($res3);
			$RM=$fila3[0];$Cargo=$fila3[1];
			if($CompletarFormato)
			{
				echo "<tr><td colspan='100' align='center' style='background:#e5e5e5; font-weight:bold'>$Cargo</td></tr>";
			}
			//---
			$cons9="Select Id_Item,Item,cargoxitem,subformato from HistoriaClinica.ItemsxFormatos where Formato='$Formato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]' and (Titulo IS NULL or titulo = 0)  AND UPPER(TipoControl) <> 'MEDICAMENTOS MULTILINEA' AND UPPER(TipoControl) <> 'MEDICAMENTOS UNILINEA' AND UPPER(TipoControl) <> 'MEDICAMENTOS FORMULA' 
			and estado='AC' Order By orden";
			//echo $cons9;
			$res9=ExQuery($cons9);		
			while($fila9=ExFetch($res9))
			{
				if($fila9[3]==0)
				{
					$NumCampo="CMP".substr("00000",0,5-strlen($fila9[0])).$fila9[0];
					$ListaCampos=$ListaCampos.$NumCampo.",";			
					$NoTotCampos++;
					$DatCampos[$fila9[0]]=array($fila9[0],1,$fila9[1],0,0);
					if($fila9[2]==""||$fila9[2]==$Cargo)
					{
						$NoTotCamposxCargo++;
						$DatItemxCargo[$fila9[0]]=array($fila9[0],$fila9[1],$fila9[2]);
					}
					$consxx="select condedad1, edad1, condedad2, edad2, sexo, estadocivil, eps, tipousuario, nivel 
					from historiaclinica.dependenciahc where Compania='$Compania[0]' and Formato='$Formato' and Id_Item=$fila9[0] 
					and Item='$fila9[1]' and TipoFormato='$TipoFormato'";
					//echo $consxx."<br>";
					$resxx=ExQuery($consxx);
					while($filaxx=ExFetch($resxx))
					{
						if((($filaxx[0]&&$filaxx[1])&&(empty($filaxx[2])&&empty($filaxx[3])))||(($filaxx[0]&&$filaxx[1]&&$filaxx[2]&&$filaxx[3]))){$DatCampos[$fila9[0]][3]++;}				
						if($filaxx[4]){$DatCampos[$fila9[0]][3]++;}
						if($filaxx[5]){$DatCampos[$fila9[0]][3]++;}
						if($filaxx[6]){$DatCampos[$fila9[0]][3]++;}
						if($filaxx[7]){$DatCampos[$fila9[0]][3]++;}
						if($filaxx[8]){$DatCampos[$fila9[0]][3]++;}
						$MatDependenciaxItem[$fila9[0]]=array($filaxx[0],$filaxx[1],$filaxx[2],$filaxx[3],$filaxx[4],$filaxx[5],$filaxx[6],$filaxx[7],$filaxx[8]);
						//echo $filaxx[0]." --> ".$filaxx[1]." --> ".$filaxx[2]." --> ".$filaxx[3]." --> ".$filaxx[4]." --> ".$filaxx[5]." --> ".$filaxx[6]." --> ".$filaxx[6]." --> ".$filaxx[7]." --> ".$filaxx[8]."<br> ";
					}
				}
			}		
			if($ListaCampos){$ListaCampos.",";}
			$ListaCampos=substr($ListaCampos,0,strlen($ListaCampos));
			$cons1="Select $ListaCampos Fecha,Hora,Dx1,Dx2,Dx3,Dx4,Dx5,TipoDx,causaexterna,finalidadconsult from HistoClinicaFrms.$Tabla where Formato='$Formato' and TipoFormato='$TipoFormato' 
			and Id_Historia=$IdHistoria  and Compania='$Compania[0]' and Cedula='$Paciente[1]'";

			$res1=ExQuery($cons1,$conex);echo ExError();
			$fila1=ExFetchArray($res1);
			$NingunaDep=0;
			foreach($DatCampos as $ListFields)
			{			
				$NoCamposCumple=0;			
				if(empty($ListFields[3])||$ListFields[3]==0)
				{
					$NomCampo=substr("00000",0,5-strlen($ListFields[0])).$ListFields[0];
					$DatosHC[$ListFields[0]]=$fila1['cmp'.$NomCampo];	
					//echo "No Condiciones --> ".$ListFields[0]." --> ".$ListFields[2]." --> ".$ListFields[3]." --> ".$DatCampos[$ListFiels[0]][4]."<br>";		
					$NingunaDep++;
				}
				else
				{			
					//echo $ListFields[0]." --> ".$ListFields[2]." --> ".$ListFields[3]."<br>";				
					if(!empty($MatDependenciaxItem[$ListFields[0]][0])&&!empty($MatDependenciaxItem[$ListFields[0]][1])&&!empty($MatDependenciaxItem[$ListFields[0]][2])&&!empty($MatDependenciaxItem[$ListFields[0]][3]))
					{					
						$Operador=$MatOperadores[$MatDependenciaxItem[$ListFields[0]][0]];					
						$Operador1=$MatOperadores[$MatDependenciaxItem[$ListFields[0]][2]];					
						eval("
						if(\$MatDatosPaciente[0] $Operador  \$MatDependenciaxItem[\$ListFields[0]][1] && \$MatDatosPaciente[0] $Operador1  \$MatDependenciaxItem[\$ListFields[0]][3])
						{
							\$NoCamposCumple++;
						}");					
					}
					elseif(!empty($MatDependenciaxItem[$ListFields[0]][0])&&!empty($MatDependenciaxItem[$ListFields[0]][1]))
					{								
						$Operador=$MatOperadores[$MatDependenciaxItem[$ListFields[0]][0]];					
						eval("
						if(\$MatDatosPaciente[0] $Operador  \$MatDependenciaxItem[\$ListFields[0]][1])
						{
							\$NoCamposCumple++;
						}");					
					}				
					if(!empty($MatDependenciaxItem[$ListFields[0]][4]))
					{
						if($MatDependenciaxItem[$ListFields[0]][4]==$MatDatosPaciente[1])
						{
							$NoCamposCumple++;
						}		
					}				
					if(!empty($MatDependenciaxItem[$ListFields[0]][5]))
					{
						if($MatDependenciaxItem[$ListFields[0]][5]==$MatDatosPaciente[2])
						{
							$NoCamposCumple++;	
						}	
					}
					if(!empty($MatDependenciaxItem[$ListFields[0]][6]))
					{
						if($MatDependenciaxItem[$ListFields[0]][6]==$MatDatosPaciente[3])
						{
							$NoCamposCumple++;
						}		
					}				
					if(!empty($MatDependenciaxItem[$ListFields[0]][7]))
					{
						if($MatDependenciaxItem[$ListFields[0]][7]==$MatDatosPaciente[4])
						{	
							$NoCamposCumple++;			
						}	
					}
					if(!empty($MatDependenciaxItem[$ListFields[0]][8]))
					{
						if($MatDependenciaxItem[$ListFields[0]][8]==$MatDatosPaciente[5])
						{
							$NoCamposCumple++;			
						}		
					}
					if($NoCamposCumple==$ListFields[3])
					{
						$NomCampo=substr("00000",0,5-strlen($ListFields[0])).$ListFields[0];
						$DatosHC[$ListFields[0]]=$fila1['cmp'.$NomCampo];		
						$DatCampos[$ListFields[0]][4]=$NoCamposCumple;
						//echo "Cumple --> ".$ListFields[0]." --> ".$ListFields[2]." --> ".$ListFields[3]." --> ".$DatCampos[$ListFields[0]][4]."<br>";		
					}				
				}			
			}
			$Fecha=$fila1['fecha'];$Hora=$fila1['hora'];
			$ValDx[1]=$fila1['dx1'];$ValDx[2]=$fila1['dx2'];$ValDx[3]=$fila1['dx3'];$ValDx[4]=$fila1['dx4'];$ValDx[5]=$fila1['dx5'];$ValTipoDx=$fila1['tipodx'];
			$Finldd=$fila['finalidadconsult']; $Cusext=$fila['causaexterna'];
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
				if(empty($DatCampos[$fila['id_item']])||($DatCampos[$fila['id_item']][3]==$DatCampos[$fila['id_item']][4])&&$DatItemxCargo[$fila['id_item']])
				{
					$Tip=$fila['tip'];
					$Mensaje=$fila['item'];
					
					echo "<td bgcolor='#e5e5e5' align='center'>" . $Mensaje . "</td>";
				}
			}
			echo "<tr>";
		}

		$res=ExQuery($cons);echo ExError();
		$NoElementosxPantalla=ExNumRows($res);
		$ContNoMuestra=0;
		while($fila=ExFetchArray($res))
		{
			// Carga en EPICRISIS (TRATAMIENTO FARMACOLOGICO) todas las ordenes medicas de MEDICAMENTOS PROGRAMADOS realizados durante el servicio activo
				if($Formato=="EPICRISIS"){
					$consServ1="select numservicio,dxserv from salud.servicios where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC'";
					$resServ1=ExQuery($consServ1);
					$filaServ1=ExFetch($resServ1);

					// Consulta los medicamentos programados para asignarlos al campo TRATAMIENTO FARMACOLÓGICO REALIZADO
					$consServ2="select * from salud.ordenesmedicas where compania='$Compania[0]' and cedula='$Paciente[1]' and numservicio=$filaServ1[0] and tipoorden='Medicamento Programado'";
					$resServ2=ExQuery($consServ2);
								
					$concatenada="";
					while($filaServ2=ExFetchAssoc($resServ2)){
						$concatenada.=$filaServ2['detalle']." ".$filaServ2['posologia']." ".$filaServ2['estado']."\n";
					}

					if($fila['item']=="TRATAMIENTO FARMACOLOGICO"){
						//echo "<script>alert('Hola mundo'+".$fila['id_item'].");</script>";
						$DatosHC[$fila['id_item']]=$concatenada;
					}
					// Finaliza consulta de medicamentos programados
								
					// Consulta las especialidades diferentes a psiquiatría que han realizado NOTAS DE EVOLUCIÓN
					$consServ2="select * from histoclinicafrms.tbl00004 where compania='$Compania[0]' and cedula='$Paciente[1]' and numservicio=$filaServ1[0] and cargo<>'PSIQUIATRA'";
					$resServ2=ExQuery($consServ2);
							   
					$concatenada2="";
					while($filaServ2=ExFetchAssoc($resServ2)){
						$concatenada2.="Tratamiento de ".$filaServ2['cargo']." realizado por ".$filaServ2['usuario']."\n";
					}
								
					if($fila['item']=="TRATAMIENTO NO FARMACOLÓGICO"){
						//echo "<script>alert('Hola mundo'+".$fila['id_item'].");</script>";
						$DatosHC[$fila['id_item']]=$concatenada2;
					}
					// Finaliza consulta de especialidades
								
					// Consulta la interpretacion de laboratorios para el servicio actual
					$consServ2="select cups.nombre as nomc,usuarios.nombre,fechaini,cargo,interpretacion,rutaimg,usuariointerpretacion,usuariorutaimg,fechainterpretacion,fecharutaimg,numprocedimiento,cups.codigo,numservicio
						from salud.plantillaprocedimientos,contratacionsalud.cups,central.usuarios,salud.medicos
						where medicos.compania='$Compania[0]' and plantillaprocedimientos.compania='$Compania[0]' and plantillaprocedimientos.cedula='$Paciente[1]' and 
						usuarios.usuario=plantillaprocedimientos.usuario and medicos.usuario=plantillaprocedimientos.usuario and cups.compania='$Compania[0]' and cups.codigo=plantillaprocedimientos.cup AND plantillaprocedimientos.interpretacion IS NOT NULL AND plantillaprocedimientos.numservicio=$filaServ1[0]
						group by cups.nombre,usuarios.nombre,fechaini,cargo,interpretacion,rutaimg,usuariointerpretacion,usuariorutaimg,fechainterpretacion,fecharutaimg,numprocedimiento,cups.codigo,numservicio	
						order by numprocedimiento desc";
					$resServ2=ExQuery($consServ2);
								
					$concatenada2="";
					while($filaServ2=ExFetchAssoc($resServ2)){
						$concatenada2.=$filaServ2['nomc']." ".strip_tags($filaServ2['interpretacion'])."\n\n";
					}
								
					if($fila['item']=="RESULTADOS DE PARACLINICOS REALIZADOS"){
						$DatosHC[$fila['id_item']]=$concatenada2;
					}
					// Finaliza la consulta de interpretacion de laboratorios
				}
				
			//echo $DatCampos[$fila['id_item']][2]." --> ".$DatCampos[$fila['id_item']][3]." --> ".$DatCampos[$fila['id_item']][4]." --> "."<br>";				
			if(empty($DatCampos[$fila['id_item']])||($DatCampos[$fila['id_item']][3]==$DatCampos[$fila['id_item']][4])&&$DatItemxCargo[$fila['id_item']])
			{			
				//---traer de
				if($fila['traerde']&&$fila['tftraerde']&&$fila['campotraerde'])
				{
					if(empty($DatosHC[$fila['id_item']]))
					{
						$consNomTabla="select tblformat from historiaclinica.formatos where Compania='$Compania[0]' and formato='".$fila['traerde']."' and tipoformato='".$fila['tftraerde']."'";	
						$resNomTabla=ExQuery($consNomTabla);
						$filaNomTabla=ExFetch($resNomTabla); $NomTablaTraerDe=$filaNomTabla[0];
						$consTraer="Select id_item from historiaclinica.itemsxformatos where Compania='$Compania[0]' and formato='".$fila['traerde']."' and tipoformato='".$fila['tftraerde']."' and Item='".$fila['campotraerde']."'";
						$resTraer=ExQuery($consTraer);
						$filaTraer=ExFetch($resTraer); $Id_ItemTraerDe=$filaTraer[0];
						
						$NumCampoTraer="CMP".substr("00000",0,5-strlen($Id_ItemTraerDe)).$Id_ItemTraerDe;	
						if($NomTablaTraerDe==$Tabla){$condTD="and Id_Historia!=$IdHistoria";}else{$condTD="";}
						$qnopos="";
						if($fechanopos!=NULL){$qnopos="and fecha='$fechanopos'";}
						if($horanopos!=NULL){$horrpos="and hora='$horanopos'";}
						$consTraer="Select $NumCampoTraer from histoclinicafrms.$NomTablaTraerDe where Compania='$Compania[0]' and cedula='$Paciente[1]' $condTD $qnopos $horrpos order by Id_historia desc limit 1";
						$resTraer=ExQuery($consTraer);$filaTraer=ExFetch($resTraer);
						$DatosHC[$fila['id_item']]=$filaTraer[0];	
						//echo $fila['traerde']." --> ".$fila['tftraerde']." --> ".$fila['campotraerde']."<br>";
						
					}
				}
				
				//echo $fila['traerde']." --> ".$fila['tftraerde']." --> ".$fila['campotraerde']."<br>";
				//---
				$Tip=$fila['tip'];
				$Mensaje=$fila['item'];
				$LogCam=$fila['longitud'];
				$Nombre=str_replace("/","-",$fila['item']);
				$Nombre=str_replace(" ","_",$Nombre);
				//--nuevos simbolos
				$Nombre=str_replace(".","_",$Nombre);
				$Nombre=str_replace(",","_",$Nombre);
				$Nombre=str_replace(":","_",$Nombre);
				$Nombre=str_replace(";","_",$Nombre);
				$Nombre=str_replace("(","_",$Nombre);
				$Nombre=str_replace(")","_",$Nombre);
				if($fila['lineasola']==1){$Ancho='100%';}else{$Ancho=$fila['ancho'];}
				if( $fila['alto'] != 0){
					$alto = ";height:".$fila['alto'];
				}
				$Stilo="width:" . $Ancho .$alto.";maxlength:" . $fila['longitud'];
			
				if($Alineacion=="Vertical")
				{
					if($CrearTabla==1 && $fila['subformato']==1){echo "</table>";$CrearTabla=0;}
					if($fila['subformato']==1){echo "<input type='hidden' name='$Nombre' value=1>";}
					if($CrearTabla==1 && $fila['titulo']==1){echo "</table>";$CrearTabla=0;}
					if($fila['item']=="Diagnostico"){$Colspan=4;}else{$Colspan=1;}
					if($fila['titulo']==1){
						if($Mensaje=="INFORMACIÓN MÉDICO" || $Mensaje=="INFORMACI&Oacute;N M&Eacute;DICO" || $Mensaje=="INFORMACION MEDICO"  || $Mensaje=="INFORMACIÓN MEDICO" ){
							$extra="style='display:none;'";
						}
						else{
							$extra="";
						}
						echo "<tr><td bgcolor='#e5e5e5' colspan='99' $extra><strong><center>".$Mensaje;}
					else
					{
			
						if($CrearTabla==1 && $fila['lineasola']==1){echo "</table>";$CrearTabla=0;}
						if($fila['subformato']==1){$Mensaje="";}
						if($fila['lineasola']==1)
						{
							echo "<tr><td colspan=99>"/*.$Mensaje*/."</td></tr><tr><td>";
						}
						elseif($fila['lineasola']==0)
						{
							if($CierraFila)
							{
								echo "<tr>";
							}
							if(!$CrearTabla)
							{
								echo "<tr><td>";
								echo "<table border=0 bordercolor='blue' style='font : 12px Tahoma;'><tr>";$CrearTabla=1;
							}
							echo "<td>".$Mensaje."</td><td>";
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
				
				if($fila['tipodato']=="N"){$Events="onKeyUp=xNumero(this) onKeyDown=xNumero(this) onBlur=campoNumero(this)"; }else{$Events="";}
		///		else{$Events="onKeyUp=xLetra(this) onKeyDown=xLetra(this)";}
		
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
				else{$Events2=NULL;}
				$XLetra="onKeyDown='xLetra(this)'	 onKeyPress='xLetra(this)' onKeyUp='xLetra(this)'";

				if($fila['tipocontrol']=="Ordenes Medicas"){echo "<textarea $Events2 $Events title='$Tip' style='height:90px;' style='$Stilo' name='$Nombre'>" . $DatosHC[$fila['id_item']];
					$cons45="Select Detalle from Salud.OrdenesMedicas where Compania='$Compania[0]' and Cedula='$Paciente[1]' and Estado='AC' and TipoOrden='Medicamento Programado'";
					$res45=ExQuery($cons45);
					while($fila45=ExFetch($res45))
					{
						$DatOrdenesMed=$DatOrdenesMed.$fila45[0]."\n";
					}
					echo $DatOrdenesMed .  "</textarea>";
				}


				if($fila['tipocontrol']=="Area de Texto"){
					$extra="";
					if($Nombre=='INFORMACION_MEDICO'){
						$extra = "display:none;";
					}
					echo "<textarea $Events2 $Events title='$Tip' style='$extra $Stilo' name='$Nombre'>" . $DatosHC[$fila['id_item']] . "</textarea>";
				}
				if($fila['tipocontrol']=='Medicamentos'){?>
					<textarea <? echo "$Events2 $Events title='$Tip' style='$Stilo' name='$Nombre' id='$Nombre'";?>><? echo $DatosHC[$fila['id_item']]?></textarea>
					<input type="button" onClick="AgregarMeds('<? echo $Nombre?>')" value="....">
			<?	}
				if($fila['tipocontrol']=='CUPS'){?>
					<textarea <? echo "$Events2 $Events title='$Tip' style='$Stilo' name='$Nombre' id='$Nombre'";?>><? echo $DatosHC[$fila['id_item']]?></textarea>
					<input type="button" onClick="AgregarCUPS('<? echo $Nombre?>')" value=".....">
			<?	}
			
				if($fila['tipocontrol']=="Cuadro de Texto"){echo "<input title='$Tip' $Events2 $Events maxlength='".$fila['longitud']."' type='Text' style='$Stilo' name='$Nombre' value='" . $DatosHC[$fila['id_item']] . "' >";}			
				if($fila['tipocontrol']=="Fecha"){?><input title="<? echo $Tip?>" <? echo "$Events2 $Events";?> maxlength="<? echo $fila['longitud']?>" type='Text' style=" <? echo $Stilo ?>" name="<? echo $Nombre?>" value="<? echo $DatosHC[$fila['id_item']]; ?>" readonly onClick="popUpCalendar(this, this, 'yyyy-mm-dd')"> <? }
				if($fila['tipocontrol']=="PDF")
				{
					if($DatosHC[$fila['id_item']])
					{
						echo "<input type='hidden' name='pdf".$fila['id_item']."' value='".$DatosHC[$fila['id_item']]."'>";
					}
					echo "<input type='file' name='$Nombre' title='$Tip' style='width:480'>";				
				}
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
					$DivFor=explode("/",$fila['item']);
					$SFTF=$DivFor[0];$SFFormato=$DivFor[1];?>
					<tr><td colspan="90"><? //echo $Formato." -- ".$TipoFormato." -- ".$SFTF." -- ".$SFFormato;?>
						<iframe style="width:<? echo $fila['ancho']?>; height:<? echo $fila['alto']?>" src="Datos.php?DatNameSID=<? echo $DatNameSID ?>&IdHistoOrigen=<? echo $IdHistoria ?>&SFFormato=<? echo $Formato ?>&SFTF=<? echo $TipoFormato ?>&TipoFormato=<? echo $SFTF ?>&Formato=<? echo $SFFormato?>&IdHistoria=<? echo $IdHistoria?>" frameborder="0"></iframe> 
						</td>
					</tr>
		<?		}
				if($fila['item']=="Medicamento No Pos")
				{	$MedNoPos=1;?>        	
					<tr>			
						<td colspan="99"><strong>Principio Activo:</strong><? echo $Medicamento?>&nbsp;</td>               
					</tr>
					<tr>
						 <td colspan="99"><strong>Posologia:</strong><? echo $Posologia?>&nbsp;</td>
					</tr>            
				   
			<?	}
				if($fila['item']=="CUP No Pos")
				{	$CUPNoPos=1;?>        	
					<tr>			
						<td colspan="99"><strong>Codigo CUP:</strong><? echo $CUPNP?>&nbsp;</td>               
					</tr>
					<tr>
						 <td colspan="99"><strong>Nombre CUP:</strong><? echo $NomCUPNP?>&nbsp;</td>
					</tr>            
				   
			<?	}
			
				if($fila['item']=="Diagnostico"){
					
					if ($Formato != "HOJA DE INGRESO" && $Formato != "EPICRISIS"){
					
						$consServ1="select numservicio,dxserv from salud.servicios where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC'";
						$resServ1=ExQuery($consServ1);
						$filaServ1=ExFetch($resServ1);
							if ($filaServ1[0] == NULL or empty($filaServ1[0])){
								$filaServ1[0] = -99;
							}
						
						// Consulta las ultimas notas de evolución para llevar el último valor del dx creado por psiquiatras
						$cons0004="select dx1 from histoclinicafrms.tbl00004 where numservicio=$filaServ1[0] and cedula='$Paciente[1]' and cargo='PSIQUIATRA' ORDER BY fecha DESC, hora DESC limit 1";
						$res0004=ExQuery($cons0004);
						$fila0004=ExFetch($res0004);

						if($fila0004[0]){
							$ValDx[1]=$fila0004[0];
						}
						else{
							if(!$ValDx[1]){
								$ValDx[1]=$filaServ1[1];
							}
						}
					}
					$cons8="Select Detalle,CIE10,Id from historiaclinica.dxformatos where Compania='$Compania[0]' and Estado='AC' and Formato='$Formato' and TipoFormato='$TipoFormato' Order By Id";
					$res8=ExQuery($cons8);
					//echo $cons8;
					$banDx=0;
					while($fila8=ExFetch($res8))
					{			
						if($fila8[2]!=1){$Colspan=2;$Width="480px";}else{$Colspan=1;$Width="300px";}
						$cons19="Select Diagnostico from Salud.CIE where Codigo='".$ValDx[$fila8[2]]."'";
						//echo $cons19;
						$res19=ExQuery($cons19);
						$fila19=ExFetch($res19);					
						$DetValDx=$fila19[0];
						//echo $cons19;
						if($fila8[1]=="0")//Abierto
						{				
							?>
							<tr><td colspan="99"><? echo $fila8[0];?></td></tr>
							<tr><td colspan="99">                       
							<textarea name="Dx<? echo $fila8[2]?>" style="width:100%"><? echo $ValDx[$fila8[2]];?></textarea>	
							</td>
							</tr>					
						<?	       
						}
						else //CIE 10
						{ 	
							?>
							<tr><td colspan="99"><? echo $fila8[0];
							if($banDx==0){
								//echo " diagnostico prin $fila8[2]<br>";

								?>
								<input readonly type="text" name="Dx<? echo $fila8[2] ?>" id="Dx<? echo $fila8[2] ?>" style="width:40px;" value="<? if($Formato=='NOTAS EVOLUCION' || $Formato=='EPICRISIS') echo $ValDx[$fila8[2]];?>"  onClick="BuscarDx('Dx<? echo $fila8[2]?>','DetDx<? echo $fila8[2]?>', 'Dx')">
								<input readonly type="text" name="DetDx<? echo $fila8[2] ?>"  id="DetDx<? echo $fila8[2] ?>" style="width:<? echo $Width?>;" value="<? if($Formato=='NOTAS EVOLUCION' || $Formato=='EPICRISIS') echo $DetValDx;?>"  onClick="BuscarDx('Dx<? echo $fila8[2]?>','DetDx<? echo $fila8[2]?>','DetDx')">
						<?		$banDx=1;
							}
							else{
								//echo " diagnostico r $fila8[2]<br>";
								?>
								<input readonly type="text" name="Dx<? echo $fila8[2] ?>" id="Dx<? echo $fila8[2] ?>"  value="" style="width:40px;" onClick="BuscarDx('Dx<? echo $fila8[2]?>','DetDx<? echo $fila8[2]?>', 'Dx')" >
								<input readonly type="text" name="DetDx<? echo $fila8[2] ?>" id="DetDx<? echo $fila8[2] ?>" value="" style="width:<?php echo $Width?>;" onClick="BuscarDx('Dx<? echo $fila8[2]?>','DetDx<? echo $fila8[2]?>', 'DetDx')">
								<!--    03 de marzo de 2014
                                                                        Se comenta para que no arrastre el Dx relacionado
                                                                <input readonly type="text" name="Dx<? echo $fila8[2] ?>" id="Dx<? echo $fila8[2] ?>"  value="<?  if($ValDx[$fila8[2]]=='000'){$ValDx[$fila8[2]]='';}echo $ValDx[$fila8[2]];?>" style="width:40px;" onClick="BuscarDx('Dx<? echo $fila8[2]?>','DetDx<? echo $fila8[2]?>', 'Dx')" >
								<input readonly type="text" name="DetDx<? echo $fila8[2] ?>" id="DetDx<? echo $fila8[2] ?>" value="<? if($DetValDx=='SIN DIAGNOSTICO'){$DetValDx='';}echo $DetValDx?>" style="width:<?php echo $Width?>;" onClick="BuscarDx('Dx<? echo $fila8[2]?>','DetDx<? echo $fila8[2]?>', 'DetDx')">
                                                                -->
						<?	}?>       
							
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
						<?
						}
					}
					$cons45="select consultaextern from salud.ambitos where compania='$Compania' and ambito='$Ambito'"; 
					$res45=ExQuery($cons);
					$fila45=ExFetch($res45);
					$SiFinalidad=$fila45[0];
					$consCausaExt="select causaextformat from historiaclinica.causaexternxformato where compania='$Compania[0]'
					and formato='$Formato' and tipoformato='$TipoFormato'";
					$resCausaExt=ExQuery($consCausaExt);
					if(ExNumRows($resCausaExt)>0)
					{$RestricCausaExt="and codigo in (select causaextformat from historiaclinica.causaexternxformato where compania='$Compania[0]'
					and formato='$Formato' and tipoformato='$TipoFormato')";}
					$cons45="select causa,codigo from salud.causaexterna where causa is not null $RestricCausaExt  Order By pordefecto Desc,causa";
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
						$consFinalidad="select finalidadformat from historiaclinica.finalidadxformato where compania='$Compania[0]'
						and formato='$Formato' and tipoformato='$TipoFormato'";
						$resFinalidad=ExQuery($consFinalidad);
						if(ExNumRows($resFinalidad)>0)
						{$RestricFinalidad="and codigo in (select finalidadformat from historiaclinica.finalidadxformato where compania='$Compania[0]'
						and formato='$Formato' and tipoformato='$TipoFormato')";}
						$cons45="select finalidad,codigo from salud.finalidadesact where tipo=1 $RestricFinalidad Order By pordefecto Desc";
						$res45=ExQuery($cons45);	?>
						<strong>
							Finalidad Consulta</strong>
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

				
				if (strtoupper($fila['tipocontrol'])=='MEDICAMENTOS MULTILINEA'){
					$nombreIframe = "MedsxFormato".$fila['id_item'];
					?>
					
					<iframe src="MedsxFormato.php" name="<?php echo $nombreIframe;?>" id="<?php echo $nombreIframe;?>" scrolling="auto" frameborder="0" width="100%" height="300px"  >
					</iframe>
					
					<script language='javascript'>
							
							var url = 'MedsxFormato.php?tipoformato=<?php echo $TipoFormato;?>&formato=<?php echo $Formato;?>&idhistoria=<?php echo $IdHistoria;?>&cedula=<?php echo $Paciente[1];?>&compania=<?php echo $Compania[0];?>&usuario=<?php echo $usuario[1];?>&iditem=<?php echo $fila['id_item'];?>&insercion=multilinea';
							var nombreIframe = '<?php echo $nombreIframe;?>';
							document.getElementById(nombreIframe).src = url;
							
						</script>
					<?
				}
				
				if (strtoupper($fila['tipocontrol'])=='MEDICAMENTOS UNILINEA'){
					$nombreIframe = "MedsxFormato".$fila['id_item'];
					?>
					
					<iframe src="MedsxFormato.php" name="<?php echo $nombreIframe;?>" id="<?php echo $nombreIframe;?>" scrolling="auto" frameborder="0" width="100%" height="200px"  >
					</iframe>
					
					<script language='javascript'>
							
							var url = 'MedsxFormato.php?tipoformato=<?php echo $TipoFormato;?>&formato=<?php echo $Formato;?>&idhistoria=<?php echo $IdHistoria;?>&cedula=<?php echo $Paciente[1];?>&compania=<?php echo $Compania[0];?>&usuario=<?php echo $usuario[1];?>&iditem=<?php echo $fila['id_item'];?>&insercion=unilinea';
							var nombreIframe = '<?php echo $nombreIframe;?>';
							document.getElementById(nombreIframe).src = url;
							
						</script>
					<?
				}
				
				if (strtoupper($fila['tipocontrol'])=='MEDICAMENTOS FORMULA'){
					$nombreIframe = "MedxFormula".$fila['id_item'];
					?>
					
					<iframe src="MedxFormula.php" name="<?php echo $nombreIframe;?>" id="<?php echo $nombreIframe;?>" scrolling="auto" frameborder="0" width="100%" height="400px"  >
					</iframe>
					
					<script language='javascript'>
							
							var url = 'MedxFormula.php?tipoformato=<?php echo $TipoFormato;?>&formato=<?php echo $Formato;?>&idhistoria=<?php echo $IdHistoria;?>&cedula=<?php echo $Paciente[1];?>&compania=<?php echo $Compania[0];?>&usuario=<?php echo $usuario[1];?>&iditem=<?php echo $fila['id_item'];?>&insercion=multilinea';
							var nombreIframe = '<?php echo $nombreIframe;?>';
							document.getElementById(nombreIframe).src = url;
							
						</script>
					<?
				}
				
			}
			else
			{
				$ContNoMuestra++;	
			}
		}
		//echo "$ContNoMuestra --> $NoElementosxPantalla --> $NingunaDep<br>";
		if($ContNoMuestra==$NoElementosxPantalla&&$NoElementosxPantalla!=0)
		{
			echo "<center>La Pantalla $IdPantalla del Formato tiene Dependencia por cada elemento,<br> ninguna de las condiciones establecidas cumple con los datos del paciente.</center>";
			if($Registro=="Anterior"){$IdPantalla--;}
			else{$IdPantalla++;}
			if($IdPantalla>$NPantallas)
			{
				?><script language="javascript">
				alert("El Formato tiene Dependencia con la Historia Clinica por cada elemento, ninguna de las condiciones establecidas cumple con los datos del paciente.");
				//location.href='Datos.php?DatNameSID=<? echo $DatNameSID?>&SubFormato=<? echo $SFFormato?>&IdHistoOrigen=<? echo $IdHistoOrigen?>&SFTF=<? echo $SFTF?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&SoloUno=<? echo $SoloUno?>';
				</script><?	
			}
			else
			{			
				?><script language="javascript">
				location.href="NuevoRegistro.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&IdPantalla=<? echo $IdPantalla?>&Fecha=<? echo $Fecha?>&Hora=<? echo $Hora?>&IdHistoria=<? echo $IdHistoria?>&DesHabAtras=1";
				</script><?
			}
		}	
	?>

	</table>
	</div>
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
<input type="Hidden" name="IdItemSF" value="<? echo $IdItemSF?>">
<input type="Hidden" name="IdHistoOrigen" value="<? echo $IdHistoOrigen?>">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID ?>">
<input type="hidden" name="SoloUno" value="<? echo $SoloUno?>">
<input type="hidden" name="CUPProced" value="<? echo $CUPProced?>">
<input type="hidden" name="FechaProced" value="<? echo $FechaProced?>">
<input type="hidden" name="NumSerProced" value="<? echo $NumSerProced?>">
<input type="hidden" name="NumProced" value="<? echo $NumProced?>">
<input type="hidden" name="MedNoPos" value="<? echo $MedNoPos?>">
<input type="hidden" name="CUPNoPos" value="<? echo $CUPNoPos?>">
<input type="hidden" name="CompletarFormato" value="<? echo $CompletarFormato?>">
<input type="hidden" name="DesHabAtras">
<input type="hidden" name="Frame" value="<? echo $Frame?>">
<input type="hidden" name="IdSVital" value="<? echo $IdSVital?>">

<? 
//echo "DeshabAtras --> ".$DesHabAtras."<br>"; &&empty($DesHabAtras)
if($IdPantalla>1)
{	
	?>
	
	<button  type="Submit"  onFocus="Registro.value='Anterior'"><img src="/Imgs/HistoriaClinica/izquierda.png"></button>
	
<? }
//echo $IdPantalla."------->>><br>";
?>

<button type="Submit" onFocus="Registro.value='Siguiente'"><img src="/Imgs/HistoriaClinica/derecha.png"></button>

</form>
</body>