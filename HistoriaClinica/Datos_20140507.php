<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	$ND=getdate();
	
	include("Funciones.php");
	
	// Si encuentra una variable llamada "InsertarOrdenMedica" inserta la Orden Medica
	
	function definirNumServicio($cedula){
		$consNumServ= "SELECT numservicio FROM Salud.Servicios WHERE cedula = '$cedula' AND estado = 'AC' ORDER BY fechaing DESC LIMIT 1";
		$resNumServ = ExQuery($consNumServ);
		$resNumServ = ExFetch($resNumServ);
		$Servicio = $resNumServ[0];
		return $Servicio;
	}

	function insertarOrdenMedica($compania, $fecha, $cedula, $numservicio, $detalle, $idescritura, $usuario, $tipoorden, $numorden,$estado){
		// Se crea esta funcion para que al definir algun tipo de Alta (Coducta a seguir) en el formato Hoja de Ingreso se cree una Orden Medica  de Egreso
		
		$consInsercion = "INSERT INTO Salud.OrdenesMedicas(compania, fecha, cedula, numservicio, detalle, idescritura, usuario, tipoorden, numorden, estado) VALUES ('$compania', '$fecha', '$cedula', $numservicio, '$detalle', '$idescritura', '$usuario', '$tipoorden', '$numorden' ,'$estado')";
		ExQuery($consInsercion);	
	}
	
	
	if (isset($_GET["InsOrdenMedica"])){
		$Servicio = definirNumServicio($Paciente[1]);
		$numorden = 1; 
		
			if (strtoupper($_GET["OrdenMedica"]) == "REMISION" ){
				$detalle = "Remisi&oacute;n Paciente";
			}
			
			if (strtoupper($_GET["OrdenMedica"]) == "ALTA" ){
				$detalle = "Alta Paciente";
			}
			
			if (strtoupper($_GET["OrdenMedica"]) == "ALTAVOLUNTARIA" ){
				$detalle = "Alta voluntaria Paciente";
			}
			
			if (strtoupper($_GET["OrdenMedica"]) == "DECESO" ){
				$detalle = "Deceso Paciente";
			}
		
		insertarOrdenMedica($Compania[0], "$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]", $Paciente[1], $Servicio, $detalle, $IdEscritura, $usuario[1], $_GET["OrdenMedica"], $numorden,"AC");
	
	}
	
	
	
	
	
	//echo "IdHistoriaOrigen=$IdHistoOrigen -- SFFormato=$SFFormato -- SFTF=$SFTF -- TipoFormato=$TipoFormato -- Formato=$Formato -- IdHistoria=$IdHistoria";

	$cons100="Select Medicos.usuario,cargo,rm,Nombre from Salud.Medicos,Central.Usuarios where
	Medicos.usuario=Usuarios.usuario and Compania='$Compania[0]'";
	$res100=ExQuery($cons100);
	while($fila100=ExFetch($res100))
	{
		$MatMedicos[$fila100[0]]=array($fila100[0],$fila100[1],$fila100[2],$fila100[3]);
	}	
	$cons="select interpretar,asigrutaimg from salud.cargos,salud.medicos 
	where medicos.usuario='$usuario[1]' and medicos.cargo=cargos.cargos and cargos.compania='$Compania[0]' and medicos.compania='$Compania[0]'";
	$res=ExQuery($cons);$fila=ExFetch($res);
	$Interpreta=$fila[0];
	
	if($Cerrar==1)
	{
		$cons="Update HistoClinicaFrms.$Tabla set Cerrado=1 where Cedula='$Paciente[1]' and Formato='$Formato' and TipoFormato='$TipoFormato' and Id_Historia=$Id_Historia and Compania='$Compania[0]'";
		$res=ExQuery($cons);
	}				
	
	if($VoBo)
	{
		$cons="Insert into HistoriaClinica.RegistroVoBoxFormatos(Usuario,FechaVoBo,TipoFormato,Formato,Cargo,Compania,IdHistoria)
		values('$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$TipoFormato','$Formato','".$MatMedicos[$usuario[1]][1]."','$Compania[0]',$IdHistoria)";	
		$res=ExQuery($cons);
	}
		
 	$cons="Select Ajuste,AgruparxHospi,Alineacion,CierreVoluntario,TblFormat,rutaformatant,Paguinacion,laboratorio,formatoxml,acudientes,
	reqambito, incluirsignosvitales from HistoriaClinica.Formatos where Formato='$Formato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]'";
	//echo $cons;
	$res=ExQuery($cons,$conex);
	$fila=ExFetch($res);

	$TiempoAjuste=$fila[0];

	$TAxFORM=$TiempoAjuste*60;
	$Agrupar=$fila[1];
	$Alineacion=$fila[2];
	if($TAxFORM==0){$TAxFORM=30;}
	$CierreVoluntario=$fila[3];
	$Tabla=$fila[4];
	$RutaAnt=$fila[5];
	$Paginacion=$fila[6];
	if(!$LimSup){$LimSup=$Paginacion;}
	if(!$LimInf){$LimInf=0;}
	if($SigPagina){$LimInf=$LimSup;$LimSup=$LimSup+$Paginacion;}
	if($AntPagina){$LimInf=$LimInf-$Paginacion;$LimSup=$LimSup-$Paginacion;}
	if($fila[7]){$Laboratorio=1;}
	$FormatoXML=$fila[8]; 
	$Acudientes=$fila[9];
	$ReqAmbito=$fila[10];	
	
	//--Ajuste Permanente
	$consAjuPer="Select ajustepermanentedet.perfil,ajustepermanentedet.item from HistoriaClinica.AjustePermanenteDet,
	HistoriaClinica.AjustePermanente where ajustepermanentedet.Formato='$Formato' and AjustePermanenteDet.TipoFormato='$TipoFormato' 
	and AjustePermanenteDet.Compania='$Compania[0]' and ajustepermanente.compania=ajustepermanentedet.compania and 
	ajustepermanente.Formato=ajustepermanentedet.Formato and ajustepermanente.TipoFormato=ajustepermanentedet.TipoFormato
	and ajustepermanente.perfil=ajustepermanentedet.perfil and ajustepermanente.perfil='".$MatMedicos[$usuario[1]][1]."'";
	$resAjuPer=ExQuery($consAjuPer);
	while($filaAjuPer=ExFetchArray($resAjuPer))
	{
		$MatAjustePer[$filaAjuPer['perfil']]=array($filaAjuPer['perfil'],$filaAjuPer['item']);	
	}
	//echo $consAjuPer."<br>".$MatMedicos[$usuario[1]][1];
	//--
?>
<style>
	a{color:black;text-decoration:none;}
	a:hover{color:blue;text-decoration:underline;}
	
	.button { /* Top left corner, top edge */
	float:left;
	color:#ddd; /* Text colour */
	background:#537b91 url(/Imgs/button.gif) no-repeat; /* Fallback bg colour for images off */
	font:1.2em/1.0 Georgia,serif;
	text-decoration:none;
	}
	.button * {display:block;}
	.button span { /* Top right corner */
	padding:6px 0 0;
	background:url(/Imgs/corners.gif) no-repeat right top;
	}
	.button span span { /* Bottom left corner, left and bottom edges */
	padding:0 0 0 6px;
	background:url(/Imgs/button.gif) no-repeat left bottom;
	}
	.button span span span { /* Bottom right corner */
	padding:0 0 6px;
	background:url(/Imgs/corners.gif) no-repeat right bottom;
	}
	.button span span span span { /* Right edge */
	padding:3px 12px 3px 6px; /* Extra padding (3px vertical, 6px horizontal) added to give the text some breathing room */
	background:url(/Imgs/button.gif) no-repeat right center;
	}
	.button:hover,
	.button:focus,
	.button:active { /* Help keyboard users */
	outline:2px solid #099dbd; /* Not supported by IE/Win :-( */
	color:#fff;
	}
</style>
<script language="javascript">
	function AbrirInterpretacion(e,NumS,NumP,Format,TipoFormat,Id_H)
	{	
		x = e.clientX; 
		y = e.clientY; 
		st = document.body.scrollTop;
		frames.FrameOpener.location.href="Formatos_Fijos/Interpretacion.php?DatNameSID=<? echo $DatNameSID?>&Laboratorio=1&Numserv="+NumS+"&NumProced="+NumP+"&Formato="+Format+"&TipoFormato="+TipoFormat+"&Id_Historia="+Id_H;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=(y)+st;
		document.getElementById('FrameOpener').style.left='10%';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='450px';
		document.getElementById('FrameOpener').style.height='300px';
	}
	function calcHeight(Obj)
	{
		document.getElementById(Obj.id).height=document.getElementById(Obj.id).contentWindow.document.body.scrollHeight;
	}
	function OpcsImprimir(e,IdH)
	{
		x = e.clientX; 
		y = e.clientY; 
		st = document.body.scrollTop;
		frames.FrameOpener.location.href="OpcImpHC.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&Id_Historia="+IdH;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=(y)+st;
		document.getElementById('FrameOpener').style.left='10%';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='450px';
		document.getElementById('FrameOpener').style.height='300px';
	}
</script>
<?
	$FechasMostrar=explode("|",$PeriodoxFormatos);

	if($FechasMostrar[1]=="0000-00-00" || !$FechasMostrar[1])
	{
		$FechasMostrar[1]="$ND[year]-$ND[mon]-$ND[mday]";
	}
	if(!$FechasMostrar[0]){$FechasMostrar[0]="1980-01-01";}
	
	if(1>2)
	{?>
        <script language="javascript">
            function clickIE() {return false;}
            function disableselect(e){return false;}
            function reEnable(){return true;}
        
            document.oncontextmenu=clickIE;
            document.onselectstart=new Function ("return false");
            if (window.sidebar)
            {
                document.onmousedown=disableselect
                document.onclick=reEnable
            }
        </script>
  <? }?>
<style type="text/css">
body {
	background-image: url(/Imgs/Fondo.jpg);
}
</style>

<script language="javascript">
	function VerPDF(Ruta)
	{
		//alert(Ruta);
		open('IntermedioPDF.php?DatNameSID=<? echo $DatNameSID?>&Ruta='+Ruta,'','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES');
	}
	function Salir()
	{
		parent.document.getElementById('FrameAcudientes').style.position='absolute';
		parent.document.getElementById('FrameAcudientes').style.top='0';
		parent.document.getElementById('FrameAcudientes').style.left='0';
		parent.document.getElementById('FrameAcudientes').style.display='';
		parent.document.getElementById('FrameAcudientes').style.width='0';
		parent.document.getElementById('FrameAcudientes').style.height='0';		
	}
	
	function Salir2()
	{
		parent.document.getElementById('FrameAdministrativo').style.position='absolute';
		parent.document.getElementById('FrameAdministrativo').style.top='0';
		parent.document.getElementById('FrameAdministrativo').style.left='0';
		parent.document.getElementById('FrameAdministrativo').style.display='';
		parent.document.getElementById('FrameAdministrativo').style.width='0';
		parent.document.getElementById('FrameAdministrativo').style.height='0';		
	}
	
</script>
<meta http-equiv="refresh" content="<?echo $TAxFORM?>">

<body>
<form name="FORMA" method="post">
<input type="hidden" name="Frame" value="<? echo $Frame?>">
<input type="hidden" name="IdHistoOrigen" value="<? echo $IdHistoOrigen?>">
<input type="hidden" name="SFFormato" value="<? echo $SFFormato?>">
<input type="hidden" name="SFTF" value="<? echo $SFTF?>">
<?	if($ND[mon]<10){$cero='0';}else{$cero='';}
	if($ND[mday]<10){$cero1='0';}else{$cero1='';}
	if($Frame==1)
	{?>
		<button name="Cerrar" title="Cerrar" style="cursor:hand; position:absolute; right:20; top:0" onClick="Salir()"><img src="/Imgs/b_drop.png"></button>		
	<?
    }
	
	if($Frame==2)
	{?>
		<button name="Cerrar" title="Cerrar" style="cursor:hand; position:absolute; right:20; top:0" onClick="Salir2()"><img src="/Imgs/b_drop.png"></button>		
	<?
    }
	
	if($CedPac)	{
	
		$cons9="Select * from Central.Terceros where Identificacion='$CedPac' and compania='$Compania[0]'";
		//echo $cons9;
		$res9=ExQuery($cons9);echo ExError();
		$fila9=ExFetch($res9);
	
		$Paciente[1]=$fila9[0];
		$n=1;
		for($i=1;$i<=ExNumFields($res9);$i++)
		{
			$n++;
			$Paciente[$n]=$fila9[$i];
			//echo "<br>$n=$Paciente[$n]";
		}
	//session_register("Paciente");	
	}
	
	$FechaCompActua="$ND[year]-$cero$ND[mon]-$cero1$ND[mday]";
	$cons="Select fecnac, sexo, ecivil, eps, tipousu, nivelusu from central.Terceros where Compania='$Compania[0]' and Identificacion='$Paciente[1]'";
	$res=ExQuery($cons);
	$MatDatosPaciente=ExFetch($res);
	$MatDatosPaciente[0]=ObtenEdad($MatDatosPaciente[0]);
	$MatOperadores["Igual a"]="==";
	$MatOperadores["Mayor a"]=">";
	$MatOperadores["Mayor Igual a"]=">=";
	$MatOperadores["Menor a"]="<";
	$MatOperadores["Menor Igual a"]="<=";	
	//if($Paciente[48]!=$FechaCompActua){echo "<em><center><br><br><br><br><br><font size=5 color='BLUE'>La Hoja de Identificacion no se ha guardado!!!";exit;}	
	$cons="Select NumServicio,TipoServicio from Salud.Servicios where Cedula='$Paciente[1]' and Estado='AC' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$NumServAct=$fila[0];
	$Ambito=$fila[1];

	$cons="Select * from HistoriaClinica.PermisosxFormato,Salud.Medicos where 
	PermisosxFormato.Perfil=Medicos.Cargo and usuario='$usuario[1]' and 
	Formato='$Formato' and TipoFormato='$TipoFormato' and Permiso='Escritura' and PermisosxFormato.Compania='$Compania[0]'";
	//echo $cons;
	$res=ExQuery($cons,$conex);
	if(ExNumRows($res)>0){$Nuevo=1;}else{$Nuevo=0;}

	
	if($Nuevo==1){
		$cons="Select * from HistoriaClinica.AmbitosxFormato where TipoFormato='$TipoFormato' and Formato='$Formato' and  (Ambito='$Ambito' Or Ambito IS NULL) and Disponible='Si' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
		if(ExNumRows($res)>=1)
		{
			$Nuevo=1;
		}
		else
		{
			$Nuevo=0;
		}
	}
	if($ReqAmbito=='Si'){
		if(!$NumServAct){
			$BanReqServ=1;
		}
	}
	$cons="select ambitoformato, incluirsignosvitales from historiaclinica.formatos where compania='$Compania[0]' and tipoformato='$TipoFormato'
	and formato='$Formato'";
	$res=ExQuery($cons);
	$fila=ExFetch($res); $AmbFormat=$fila[0];
	$IncluirSignosVitales=$fila[1];
	//-------Cambio de ambito
	if($NumServAct)
	{	/*
		$cons="select entidad,contrato,nocontrato,tiposervicio,servicios.numservicio from salud.servicios,salud.pagadorxservicios 
		where servicios.compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC' 
		and servicios.numservicio=pagadorxservicios.numservicio
		 and (fechafin>='$ND[year]-$ND[mon]-$ND[mday]' or fechafin is null) and servicios.numservicio=$NumServAct	order by fechaing desc";
		$res=ExQuery($cons);
		//echo $cons;
		$fila=ExFetch($res); $Paga=$fila[0]; $PagaCont=$fila[1]; $PagaNoCont=$fila[2]; $TipoServ=$fila[3]; $NumServ=$fila[4];
		if($Paga)
		{
			$cons="select ambitocontrato from contratacionsalud.contratos where compania='$Compania[0]' and  entidad='$Paga'
			and contrato='$PagaCont' and numero='$PagaNoCont'";	
			$res=ExQuery($cons);
			$fila=ExFetch($res);					
			if($fila[0]!=$AmbFormat)
			{ 
				//DatNameSID=SYS7045898&SubFormato=&IdHistoOrigen=&SFTF=&Formato=Control Consulta Externa&TipoFormato=Psiquiatria&SoloUno=?>
				<script language="javascript">
					location.href='CambioAmbito.php?DatNameSID=<? echo $DatNameSID?>&SubFormato=<? echo $SubFormato?>&IdHistoOrigen=<? echo $IdHistoOrigen?>&SFTF=<? echo $SFTF?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&SoloUno=<? echo $SoloUno?>&NumServ=<? echo $NumServ?>&Paga=<? echo $Paga?>&PagaCont=<? echo $PagaCont?>&PagaNoCont=<? echo $PagaNoCont?>&AmbFormat=<? echo $AmbFormat?>';
				</script>
		<?	}
		}*/
	}
	if($SoloMuestra){$Nuevo=0;}
	if($SFFormato and $IdHistoOrigen and $IdHistoOrigen!="NULL" and $SFTF){ $condSF=" and id_historia_origen=$IdHistoOrigen and padreformato='$SFFormato' and padretipoformato='$SFTF'";}
	if(!$SoloUno){		
		if ($Nuevo==1){
			if($BanReqServ==1){
				echo "<center><font size=5 color='BLUE'>Se requiere un servicio activo para diligenciar este formato<br>";
			}
			else{				
				
				?>
                <center>
                  <!--<button name="Nuevo" style="width:90px;" value="Nuevo" onClick="location.href='NuevoRegistro.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $Formato?>&amp;TipoFormato=<? echo $TipoFormato?>&Frame=<? echo $Frame?>&IdHistoOrigen=<? echo $IdHistoOrigen?>&SFFormato=<? echo $SFFormato?>&SFTF=<? echo $SFTF?>'"><img src="/Imgs/HistoriaClinica/nuevo.png"/><br>Nuevo</button>-->
				  <div style="width:100px;">
					  <a class="button" href="NuevoRegistro.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $Formato?>&amp;TipoFormato=<? echo $TipoFormato?>&Frame=<? echo $Frame?>&IdHistoOrigen=<? echo $IdHistoOrigen?>&SFFormato=<? echo $SFFormato?>&SFTF=<? echo $SFTF?>">
						<span>
							<span>
								<span>
									<span>Nuevo</span>
								</span>
							</span>
						</span>
					  </a>
				  </div>
                </center> 
		<br />
       	<?	}?>
<? 		}
	}
if($RutaAnt && empty($SoloMuestra))		
	{
        $DatoPaciente=explode("-",$Paciente[1]);
		echo "<center><a style='font : 12px Tahoma;color:blue;font-weight:bold' href='$RutaAnt&CedulaPte=$DatoPaciente[0]&DatNameSID=$DatNameSID'>Ver Historia Clinica Anterior</a></center>";
		$banRutAnt1=1;
	}

	$cons="Select * from Salud.Servicios where Estado='AC' and Cedula='$Paciente[1]' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	if(ExNumRows($res)==0){$Agrupar="No";}
	if($Agrupar=="No"){$FechasMostrar[0]="1980-01-01";$FechasMostrar[1]="$ND[year]-$ND[mon]-$ND[mday]";}

	$cons="Select * from HistoriaClinica.PermisosxFormato,Salud.Medicos where 
	PermisosxFormato.Perfil=Medicos.Cargo and usuario='$usuario[1]' and 
	Formato='$Formato' and TipoFormato='$TipoFormato' and Permiso='Impresion' and PermisosxFormato.Compania='$Compania[0]'";
	$res=ExQuery($cons,$conex);
	$fila=ExFetch($res);
	$Impresion=$fila[0];

	if($Paginacion>0){$CondPag=" Limit $Paginacion Offset $LimInf ";}
	if($SoloUno!=''){$Registro="and id_historia=$SoloUno";}
	$cons="Select * from HistoClinicaFrms.$Tabla where Cedula='$Paciente[1]' and Formato='$Formato' and TipoFormato='$TipoFormato' and Compania='$Compania[0]' $condSF $Registro
	Order By Fecha Desc,Hora Desc,Id_historia Desc $CondPag";
	$res=ExQuery($cons); //echo $cons;
	$NumTotReg=ExNumRows($res);//fila tiene todos los datos
	if($SoloMuestra==1)
	{		
		if($Alineacion=="Horizontal")
		{
			$AltoSF=90+($NumTotReg*21);
		}
		else
		{
			if(ExNumFields($res)>0)
			{				
				$NumCols=ExNumFields($res)-26;								
			}
			$AltoSF=170+(($NumCols*34)*$NumTotReg);	
		}
		?>
		<script language="javascript">
		if(parent.document.getElementById("SubF_<? echo $IdHistoOrigen."_".$IdItemSF?>")!=null)
		{
			//alert(parent.document.getElementById("SubF_<? echo $IdHistoOrigen."_".$IdItemSF?>").id+" --> "+"<? echo $AltoSF?>"+" --> SubF_<? echo $IdHistoOrigen?>");
			//alert("<? echo $AltoSF?>");
			parent.document.getElementById("SubF_<? echo $IdHistoOrigen."_".$IdItemSF?>").style.height="<? echo $AltoSF?>";
			//alert(parent.document.getElementById("SubF_<? echo $IdHistoOrigen?>").style.height);
		}
		</script>
		<?	
	}	
	if($Paginacion>0)
	{
		echo "<table border='1' rules='cols' bordercolor='#e5e5e5' style='font : 12px Tahoma;text-align:justify'>";
		echo "<tr><td colspan=4 bgcolor='#e5e5e5' align='center'><strong>Paginacion</strong></td></tr>";
		echo "<tr valign='middle'>";
		if($LimInf>0){
			echo "<td><a href='Datos.php?DatNameSID=$DatNameSID&Formato=$Formato&TipoFormato=$TipoFormato&AntPagina=1&LimSup=$LimSup&LimInf=$LimInf'><img src='/Imgs/izquierda.bmp' border='0' style='width:12px;'></td><td>Anterior</a></td>";}
		if($Paginacion<=$NumTotReg)
		{
		echo "<td>Siguiente</a></td><td><a href='Datos.php?DatNameSID=$DatNameSID&Formato=$Formato&TipoFormato=$TipoFormato&SigPagina=1&LimSup=$LimSup&LimInf=$LimInf'><img src='/Imgs/derecha.bmp' border='0' style='width:12px;'></td>";}
		echo "</tr>";
		echo "</table>";
	}

?>
<font size="-1">
<?
	if($FechasMostrar[0]!="1980-01-01"){
?>


<em>Mostrando Informaci&oacute;n de: <?echo $FechasMostrar[0]?> a <?echo $FechasMostrar[1]?></em>
</font>
<?	}
		$cons99="Select * from HistoriaClinica.ItemsxFormatos where  TipoFormato='$TipoFormato' and Formato='$Formato' and Compania='$Compania[0]' and Estado='AC' Order By Pantalla,Orden";
		$res99=ExQuery($cons99);
		//echo $cons99;
		while($fila99=ExFetchArray($res99))
		{			
			$MatItems[$fila99['id_item']]=array($fila99['id_item'],$fila99['item'],$fila99['lineasola'],$fila99['cierrafila'],$fila99['titulo'],$fila99['imagen'],$fila99['subformato'],$fila99['tipocontrol'],$fila99['cargoxitem'],$fila99['alto'],$fila99['ancho']);
			$NumTotCmps++;	
			//--cargosxitem
			if($fila99['cargoxitem']==$MatMedicos[$usuario[1]][1])
			{
				$HabBotonCargoxItem=1;	
			}		
			//Dependencia
			$DatCampos[$fila99['id_item']]=array($fila99['id_item'],1,$fila99['item'],0,0);
			$consxx="select condedad1, edad1, condedad2, edad2, sexo, estadocivil, eps, tipousuario, nivel 
			from historiaclinica.dependenciahc where Compania='$Compania[0]' and Formato='$Formato' and Id_Item=".$fila99['id_item']." 
			and Item='".$fila99['item']."' and TipoFormato='$TipoFormato'";
			//echo $consxx."<br>";
			$resxx=ExQuery($consxx);
			while($filaxx=ExFetch($resxx))
			{
				if((($filaxx[0]&&$filaxx[1])&&(empty($filaxx[2])&&empty($filaxx[3])))||(($filaxx[0]&&$filaxx[1]&&$filaxx[2]&&$filaxx[3]))){$DatCampos[$fila99['id_item']][3]++;}				
				if($filaxx[4]){$DatCampos[$fila99['id_item']][3]++;}
				if($filaxx[5]){$DatCampos[$fila99['id_item']][3]++;}
				if($filaxx[6]){$DatCampos[$fila99['id_item']][3]++;}
				if($filaxx[7]){$DatCampos[$fila99['id_item']][3]++;}
				if($filaxx[8]){$DatCampos[$fila99['id_item']][3]++;}
				$MatDependenciaxItem[$fila99['id_item']]=array($filaxx[0],$filaxx[1],$filaxx[2],$filaxx[3],$filaxx[4],$filaxx[5],$filaxx[6],$filaxx[7],$filaxx[8]);
				//echo $filaxx[0]." --> ".$filaxx[1]." --> ".$filaxx[2]." --> ".$filaxx[3]." --> ".$filaxx[4]." --> ".$filaxx[5]." --> ".$filaxx[6]." --> ".$filaxx[6]." --> ".$filaxx[7]." --> ".$filaxx[8]."<br> ";
			}
			//---
		}		
		
		if($Alineacion=="Horizontal")
		{
			//IdHistoOrigen=4&SFFormato=Formato general&SFTF=Medicina General&TipoFormato=Psiquiatria&Formato=Epricrisis&IdHistoria=&SoloMuestra=1
			if($SoloMuestra)
			{
				$margensf="border='1' cellpadding='0' cellspacing='0' ";
			}
			else
			{
				$margensf="border='0' ";
			}
			echo "<table $margensf bordercolor='#e5e5e5' style='font : 12px Tahoma;text-align:justify'>
				<tr style='font-weight:bold;text-align:center' bgcolor='#e5e5e5'><td>Fecha</td><td>Hora</td><td>Usuario</td>";
			foreach($MatItems as $Tits)
			{
				echo "<td>".$Tits[1]."</td>";
			}
			echo "<td></td></tr><tr>";
		}
		if(ExNumRows($res)==0)
		{
			//--aqui valido tamaño frame
			if($SoloMuestra==1&&$Alineacion!="Horizontal")
			{												
				$AltoSF=110;	
				?>
				<script language="javascript">
				if(parent.document.getElementById("SubF_<? echo $IdHistoOrigen."_".$IdItemSF?>")!=null)
				{
					//alert("asss");
					parent.document.getElementById("SubF_<? echo $IdHistoOrigen."_".$IdItemSF?>").style.height="<? echo $AltoSF?>";
					//alert(parent.document.getElementById("SubF_<? echo $IdHistoOrigen?>").style.height);
				}
				</script>
				<?																	
			}
			//---
		}
		while($fila=ExFetchArray($res))
		{
			if($IncluirSignosVitales)
			{			
				//$IdSVital=$fila['idsvital']
				if($fila['idsvital'])
				{
					$conssv="Select AutoId,Fecha,Usuario,Temperatura,Pulso,Respiracion,TensionArterial1,TensionArterial2 from historiaclinica.signosvitales 
					where Compania='$Compania[0]' and Cedula='$Paciente[1]' and autoid=".$fila['idsvital'];
					$ressv=ExQuery($conssv);				
					$filasv=ExFetch($ressv);
				}
				else
				{
					$filasv="";	
				}
				if($filasv)
				{					
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
			}			
			$banRutAnt2=1;
			if($Alineacion=="Vertical")
			{
				echo "<table width='100%' border='0' bordercolor='#e5e5e5' rules='groups' style='font : 12px Tahoma;text-align:justify'>";
			}

			if($Alineacion=="Vertical")
			{
				// si el registro médico no aplica
                if($MatMedicos[$fila['usuario']][2]=='NA'){
                    $fm_registromedico=' ';
                }
                else{
                    $fm_registromedico=' R.M. '.$MatMedicos[$fila['usuario']][2];
                }
								
				echo "<tr bgcolor='#002147' style='color:#FFF;'><td colspan='11'><strong>";
					
					
						
						// Esta condicion aplica para mostrar informacionluego de la migracion de la Clinica San Juan de Dios 
						if ((strtoupper($Compania[0])== "CLINICA SAN JUAN DE DIOS") and (strtotime($fila['fecha']) <= strtotime($fechaMigracionCSJDM) )){
							echo $fila['usuario']." - " . $fila['fecha'] . " - " . $fila['hora'] . "<br>";
							echo $fila['cargo']."<br>";
						} 
						else{
							echo $MatMedicos[$fila['usuario']][3] . " - " . $fila['fecha'] . " - " . $fila['hora'] . "<br>";
							echo "" .  $MatMedicos[$fila['usuario']][1]. $fm_registromedico  . "<br>";
						}
					
				
	
				$cons1="Select Cargo,Usuario,FechaVoBo,IdHistoria from HistoriaClinica.RegistroVoBoxFormatos 
				where TipoFormato='$TipoFormato' and Formato='$Formato' and Compania='$Compania[0]' and IdHistoria=".$fila['id_historia'];
				$res1=ExQuery($cons1);
				while($fila1=ExFetch($res1))
				{
					$FirmasVoBo[$fila1[0]][$fila1[3]]=$fila1[2];
					if ((strtoupper($Compania[0])== "CLINICA SAN JUAN DE DIOS") and (strtotime($fila['fecha']) <= strtotime($fechaMigracionCSJDM) )){
						echo "<font size=-2><em>Vo.Bo. ".$fila1[1]."  - $fila1[0] ($fila1[2])<br></em></font>";
					} else {
						echo "<font size=-2><em>Vo.Bo. ".$MatMedicos[$fila1[1]][3]."  - $fila1[0] ($fila1[2])<br></em></font>";
					}	
				}
				$cons2="Select Cargo from HistoriaClinica.VoBoxFormatos where TipoFormato='$TipoFormato' and Formato='$Formato' and Compania='$Compania[0]' $CondAd9";
				$res2=ExQuery($cons2);
		
				while($fila2=ExFetch($res2))
				{
					if($FirmasVoBo[$fila2[0]][$fila['id_historia']]==""){
					if($MatMedicos[$usuario[1]][1]==$fila2[0] && $usuario[1]!=$fila['usuario'])
					{
						if($fila2[0]!='PSIQUIATRA')
							echo "<a name='".$fila['id_historia']."' href='Datos.php?DatNameSID=$DatNameSID&VoBo=1&Formato=$Formato&TipoFormato=$TipoFormato&LimSup=$LimSup&LimInf=$LimInf&IdHistoria=".$fila['id_historia']."#".$fila['id_historia']."'><font size=-2 color='green'><em>Requiere Vo.Bo. de $fila2[0]<br></em></font></a>";	
					}
					else
					{
						if($fila2[0]!='PSIQUIATRA'){
							echo "<font size=-2><em>Requiere Vo.Bo. de $fila2[0]<br></em></font>";
						}	
					}}
				}
				/*$cons1="Select Cargo,Usuario,FechaVoBo,IdHistoria from HistoriaClinica.RegistroVoBoxFormatos 
				where TipoFormato='$TipoFormato' and Formato='$Formato' and Compania='$Compania[0]' and IdHistoria=".$fila['id_historia'];
				$res1=ExQuery($cons1);
				while($fila1=ExFetch($res1))
				{
					$FirmasPacSeg[$fila1[0]][$fila1[3]]=$fila1[2];
					echo "<font size=-2><em>Vo.Bo. ".$MatMedicos[$fila1[1]][3]."  - $fila1[0] ($fila1[2])<br></em></font>";
				}*/
				$cons1="select cargo,usuariocrea,fechacrea,IdHistoria from historiaclinica.regpacienteseg where TipoFormato='$TipoFormato' and Formato='$Formato' and Compania='$Compania[0]' and IdHistoria=".$fila['id_historia'];
				$res1=ExQuery($cons1);
				//echo $cons1;
				while($fila1=ExFetch($res1))
				{
					//$FirmasPacSeg[$fila1[0]][$fila1[3]]=$fila1[2];  $banPSeg=1;
					//echo "<font size=-2><em>Registro Paciente Seg. ".$MatMedicos[$fila1[1]][3]."  - $fila1[0] ($fila1[2])<br></em></font>";
				}
				
				$cons2="Select Cargo from HistoriaClinica.pacienteseg where TipoFormato='$TipoFormato' and Formato='$Formato' and Compania='$Compania[0]' $CondAd9";
				$res2=ExQuery($cons2);
		
				while($fila2=ExFetch($res2))
				{
					if(!$banPSeg){
						if($FirmasPacSeg[$fila2[0]][$fila['id_historia']]==""){						
							if($MatMedicos[$usuario[1]][1]==$fila2[0] && $usuario[1]!=$fila['usuario'])
							{
							//	echo "<a name='".$fila['id_historia']."' href='RegPacienteSeg.php?DatNameSID=$DatNameSID&VoBo=1&Formato=$Formato&TipoFormato=$TipoFormato&LimSup=$LimSup&LimInf=$LimInf&IdHistoria=".$fila['id_historia']."'><font size=-2 color='green'><em>Realizar Registro Paciente Seg. $fila2[0]<br></em></font></a>";	
							}
							else
							{							
								//echo "<font size=-2><em>Realizar Registro Paciente Seg. $fila2[0]<br></em></font>";	
							}
						}
					}
				}
			if($SoloMuestra){$Impresion=0;}
			
			
			if($Impresion){
	/*onClick="open('ImpHistoria.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&IdHistoria=<? echo $fila['id_historia']?>','','')"*/
				?>
				<button  title="Imprimir" onClick="OpcsImprimir(event,'<? echo $fila['id_historia']?>')"><img src="/Imgs/HistoriaClinica/printer.png"></img></button><?
			}
			if($usuario[1]==$fila['usuario'])
			{				
				if($CierreVoluntario=="Si" && $fila['cerrado']==0)
				{
					$date1="2030-12-31 12:00"; $dateLab="2030-12-31 12:00";
				?>
					<button onClick="location.href='Datos.php?DatNameSID=<? echo $DatNameSID?>&Cerrar=1&Formato=<?echo $Formato?>&TipoFormato=<?echo $TipoFormato?>&Id_Historia=<? echo $fila['id_historia']?>'"><img style="width:26px;" src="/Imgs/b_drop.png"></img></button>
				<?	
				}
				elseif($CierreVoluntario && $fila['cerrado']==1){
					$date1="1980-01-01 12:00"; $dateLab="1980-01-01 12:00";
				}
				else{
					$date1=$fila['fecha'] ." " . $fila['hora'];
					if($Laboratorio&&$fila['numproced']){
						$consLab="select fechainterpretacion from histoclinicafrms.ayudaxformatos 
						where cedula='$Paciente[1]' and compania='$Compania[0]' and numservicio=".$fila['numservicio']."
						and numproced=".$fila['numproced']."and formato='$Formato' and tipoformato='$TipoFormato' and id_historia=".$fila['id_historia'];						
						$resLab=ExQuery($consLab);
						$filaLab=ExFetch($resLab);		
						if($filaLab[0]){
							$dateLab=$filaLab[0];
						}			
						else{
							$NoRegInterp=1;
							if($fila['cerrado']==0){
								$dateLab="2030-12-31 12:00";
							}
							else{
								$dateLab="1980-01-01 12:00";
							}
						}
					}
					else{
						$NoRegInterp=1;
						if($fila['cerrado']==0){
							$dateLab="2030-12-31 12:00";
						}
						else{
							$dateLab="1980-01-01 12:00";
						}
					}
				}
				$date2="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";
				$s = strtotime($date2)-strtotime($date1);
				$d = intval($s/86400);
				$s -= $d*86400;
				$d = $d*1440;
				$m = intval($s/60) + $d;
				
				
				if($m<$TiempoAjuste&&empty($SoloMuestra))
				{
					?>
					<button title="Editar" onClick="location.href='NuevoRegistro.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato ?>&IdHistoria=<? echo $fila['id_historia']?>&Frame=<? echo $Frame?>'"><img src="/Imgs/HistoriaClinica/change.png"></img></button>                    				
			 <?	}			 	
			//Interpretacion
				if($NoRegInterp&&$fila['numproced']&&empty($SoloMuestra)){								
					if($Interpreta==1&&$Laboratorio==1){?>
						<button title="Interpretar" onClick="AbrirInterpretacion(event,'<? echo $fila['numservicio']?>','<? echo $fila['numproced']?>','<? echo $Formato?>','<? echo $TipoFormato?>','<? echo $fila['id_historia']?>')">
							<img src="/Imgs/HistoriaClinica/interpretar.jpg" style=" width:26; height:26">                           
						</button>					
				<?	}	
				}
				elseif($fila['numproced']&&empty($SoloMuestra)){
					
					$dateLab2="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";
					$sLab = strtotime($dateLab2)-strtotime($dateLab);
					$dLab = intval($sLab/86400);
					$sLab -= $dLab*86400;
					$dLab = $dLab*1440;
					$mLab = intval($sLab/60) + $dLab;
					if($mLab<$TiempoAjuste){
						if($Interpreta==1&&$Laboratorio==1){?>
							<button title="Interpretar" onClick="AbrirInterpretacion(event,'<? echo $fila['numservicio']?>','<? echo $fila['numproced']?>','<? echo $Formato?>','<? echo $TipoFormato?>','<? echo $fila['id_historia']?>')">
								<img src="/Imgs/HistoriaClinica/interpretar.jpg" style=" width:26; height:26">                           
							</button>					
					<?	}	
					}
				}								
			}
			else
			{
				if($HabBotonCargoxItem)
				{
					$NewCampUsu="usu".strtolower(str_replace(" ","",$MatMedicos[$usuario[1]][1]));								
					$cons2="select column_name from information_schema.columns where table_name = '$Tabla' and column_name='$NewCampUsu';";
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)>0)
					{
						$date2="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";
						$s = strtotime($date2)-strtotime($date1);
						$d = intval($s/86400);
						$s -= $d*86400;
						$d = $d*1440;
						$m = intval($s/60) + $d;
						//echo $MatMedicos[$usuario[1]][1]." --> ".$NewCampUsu;
						if(empty($fila[$NewCampUsu])&&!$SoloMuestra)
						{
							//echo $MatMedicos[$usuario[1]][1]." --> ".$NewCampUsu;	
							?>						
							<button title="Completar Formato - <? echo $MatMedicos[$usuario[1]][1]?>" onClick="location.href='NuevoRegistro.php?DatNameSID=<? echo $DatNameSID?>&Formato=<?echo $Formato?>&TipoFormato=<?echo $TipoFormato?>&IdHistoria=<?echo $fila['id_historia']?>&CompletarFormato=1'"><img src="/Imgs/HistoriaClinica/completarxcargo.png" style="width:26; height:26"></img></button>
						<?
						}					
						elseif($m<$TiempoAjuste&&!$SoloMuestra)
						{
							//echo $m." -- ".$TiempoAjuste;
							?>
							 <button title="Completar Formato - <? echo $MatMedicos[$usuario[1]][1]?>" onClick="location.href='NuevoRegistro.php?DatNameSID=<? echo $DatNameSID?>&Formato=<?echo $Formato?>&TipoFormato=<?echo $TipoFormato?>&IdHistoria=<?echo $fila['id_historia']?>&CompletarFormato=1&Frame=<? echo $Frame?>'"><img src="/Imgs/HistoriaClinica/completarxcargo.png" style="width:26; height:26"></img></button>
						<?
						}
											
					}
					//echo $MatMedicos[$usuario[1]][1]." --> ".$NewCampUsu;//aki fue*/
				}
			}
			//--Ajuste Permanente
			if($MatAjustePer[$MatMedicos[$usuario[1]][1]]&&empty($SoloMuestra))
			{?>
				<button title="Ajuste Permanente - <? echo $MatMedicos[$usuario[1]][1]?>" onClick="location.href='DatosAjustePermanente.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&IdHistoria=<? echo $fila['id_historia']?>&AjustePermanente=1'"><img src="/Imgs/HistoriaClinica/change2.png" style="width:26; height:26; cursor:hand;"></img></button>	
			<?
            }
			//--
			if($FormatoXML!=""&&$FormatoXML!="0"&&empty($SoloMuestra)){?>
				<button title="Generar XML" onClick="open('VerXML.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $Formato?>&TipoFormato=<? echo $TipoFormato?>&IdHistoria=<? echo $fila['id_historia']?>','','')">
              		<img src="/Imgs/FormatoXML.jpg" style="width:26; height:26">
           		</button>
		<?	}
				echo  "</td></tr>";
			}
			if($Alineacion=="Horizontal"){echo "<td>".$fila['fecha']."</td><td>".$fila['hora']."</td><td>".$fila['usuario']."</td>";}		
			$iii = 0;
			foreach($MatItems as $IndItems)
			{	//echo var_dump($IndItems);
				
				//--cargo
				//echo $IndItems[0]." -- ".$IndItems[8]."<br>";
				$DiligItem="usu".strtolower(str_replace(" ","",$IndItems[8]));												
				if($fila[$DiligItem])
				{
					$ProfesionalDilig="(".$MatMedicos[$fila[$DiligItem]][3]." - $IndItems[8])";					
				}	
				else{$ProfesionalDilig="";}			
				//--Dependencia HC
				$NoCamposCumple=0;			
				if(empty($DatCampos[$IndItems[0]][3])||$DatCampos[$IndItems[0]][3]==0)
				{
					//nadaaa
				}
				else
				{			
					//echo $DatCampos[$IndItems[0]][0]." --> ".$DatCampos[$IndItems[0]][2]." --> ".$DatCampos[$IndItems[0]][3]."<br>";				
					if(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][0])&&!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][1])&&!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][2])&&!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][3]))
					{
						$Operador=$MatOperadores[$MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][0]];					
						$Operador1=$MatOperadores[$MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][2]];					
						eval("
						if(\$MatDatosPaciente[0] $Operador  \$MatDependenciaxItem[\$DatCampos[\$IndItems[0]][0]][1] && \$MatDatosPaciente[0] $Operador1  \$MatDependenciaxItem[\$DatCampos[\$IndItems[0]][0]][3])
						{
							\$NoCamposCumple++;
						}");					
					}
					elseif(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][0])&&!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][1]))
					{								
						$Operador=$MatOperadores[$MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][0]];					
						eval("
						if(\$MatDatosPaciente[0] $Operador  \$MatDependenciaxItem[\$DatCampos[\$IndItems[0]][0]][1])
						{
							\$NoCamposCumple++;
						}");					
					}				
					if(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][4]))
					{
						if($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][4]==$MatDatosPaciente[1])
						{
							$NoCamposCumple++;
						}		
					}				
					if(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][5]))
					{
						if($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][5]==$MatDatosPaciente[2])
						{
							$NoCamposCumple++;	
						}	
					}
					if(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][6]))
					{
						if($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][6]==$MatDatosPaciente[3])
						{
							$NoCamposCumple++;
						}		
					}				
					if(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][7]))
					{
						if($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][7]==$MatDatosPaciente[4])
						{	
							$NoCamposCumple++;			
						}	
					}
					if(!empty($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][8]))
					{
						if($MatDependenciaxItem[$DatCampos[$IndItems[0]][0]][8]==$MatDatosPaciente[5])
						{
							$NoCamposCumple++;			
						}		
					}
					if($NoCamposCumple==$DatCampos[$IndItems[0]][3])
					{							
						$DatCampos[$DatCampos[$IndItems[0]][0]][4]=$NoCamposCumple;
						//echo "Cumple --> ".$DatCampos[$IndItems[0]][0]." --> ".$DatCampos[$IndItems[0]][2]." --> ".$DatCampos[$IndItems[0]][3]." --> ".$DatCampos[$DatCampos[$IndItems[0]][0]][4]."<br>";		
					}				
				}	
				//--
				if(empty($DatCampos[$IndItems[0]])||($DatCampos[$IndItems[0]][3]==$DatCampos[$IndItems[0]][4]))	{										
					$IdItem=$IndItems[0];$Item=$IndItems[1];$LineaSola=$IndItems[2];$Titulo=$IndItems[4];$Imagen=$IndItems[5];$SubFormato=$IndItems[6];	$tipoControl = $IndItems[7]	;
					$NumCamp="cmp".substr("00000",0,5-strlen($IdItem)).$IdItem;
					$Mensaje=$Item;					
					if($CrearTabla==1 && $Titulo==1 && $Alineacion=="Vertical"){echo "</table>";$CrearTabla=0;}
					
					
					if(($Titulo==1)){	
						
							if($Alineacion=="Vertical")	{
								
								
								$extra="display:block;";
								
								
								
								/*$oculto = "";
								if(($fila['cmp00003']=="") && ($fila['cmp00005']=="") && ($fila['cmp00007']=="") && ($fila['formato']=="NOTAS EVOLUCION")){
									$oculto = "visibility:hidden; display:none;";
								}
												   
								if(($Mensaje=="<b> PLAN </b>")&&($fila['formato']=="NOTAS EVOLUCION")){
									$oculto = "";
								}*/
								if($fila['formato']=="NOTAS EVOLUCION"){
									if($iii==0){
										$iii = 1;
										echo "<tr>";
											echo "<td>";
												$fila['cmp00003'] = trim($fila['cmp00003']) ;
												$fila['cmp00005'] = trim($fila['cmp00005']) ;
												$fila['cmp00007'] = trim($fila['cmp00007']) ;
												$fila['cmp00009'] = trim($fila['cmp00009']) ;
												
												if (strlen($fila['cmp00003'])>1){
													echo "<span style='font-weight:bold;margin-top:5px;'>Subjetivo: </span>".$fila['cmp00003']."<br>";
													
												}
												
												if (strlen($fila['cmp00005'])>1){												
													echo "<span style='font-weight:bold;margin-top:5px;'>Objetivo:  </span>".$fila['cmp00005']."<br>";
													
												}	
												
												if (strlen($fila['cmp00007'])>1){												
													echo "<span style='font-weight:bold;margin-top:5px;'>An&aacute;lisis:   </span>".$fila['cmp00007']."<br>";
													
												}
												if (strlen($fila['cmp00009'])>1){												
													echo "<span style='font-weight:bold;margin-top:5px;'>Plan:      </span>".$fila['cmp00009']."<br>";
													
												}
											echo "</td>";
										echo "<tr>";
									}
								}
								else{
									echo "<tr><td colspan='99' style='padding:5px;vertical-align:middle; background-color:#BBCDE1;$extra $oculto'><strong><center>".$Mensaje."</td></tr>";
								}
								
								// Aplica si el item es un Diagnostico
								if($Mensaje=="Diagnostico")
								{
									echo "<table border='1' bordercolor='#e5e5e5' style='font : 12px Tahoma;text-align:justify' >";
									$cons8="Select Detalle,CIE10,Id from historiaclinica.dxformatos where Compania='$Compania[0]' and Estado='AC' and Formato='$Formato' and TipoFormato='$TipoFormato' Order By Id";
								//	echo $cons8;
									$res8=ExQuery($cons8);
									while($fila8=ExFetch($res8)){
									
										if($fila8[1]!=1){$Colspan=3;$Width="480px";}else{$Colspan=1;$Width="300px";}
										$cons19="Select Diagnostico from Salud.CIE where Codigo='".$fila['dx'.$fila8[2]]."'";
										$res19=ExQuery($cons19);
										$fila19=ExFetch($res19);
										$DetValDx=$fila19[0]; 
										if($fila['dx'.$fila8[2]])
										{
											//echo $fila['dx'.$fila8[2]]." --> ".$fila8[2]."<br>";
											?>
											<tr>
												<td><? echo $fila8[0]?></td>
												<td colspan="<? echo $Colspan?>" <? if($Colspan==1){ echo "style='background:#e5e5e5'";}?> align="center">
													<strong ><? echo $fila['dx'.$fila8[2]] ?></strong>
												</td>
												<?
												if($fila8[1]==1)
												{											
													?>
												<td>
													<? echo $DetValDx?>
												</td>
												<?
												}
											if($fila8[2]==1)
											{
												$cons45="Select TipoDiagnost from Salud.TiposDiagnostico where Compania='$Compania[0]' and Codigo='".$fila['tipodx']."'";
												$res45=ExQuery($cons45);
												$fila45=ExFetch($res45);
												$TipDx=$fila45[0];
												
												?>
												<td style="background:#e5e5e5; " align="center">
												<strong><? echo $TipDx; ?></strong>											
												<?											
											}
											else{
												//echo "<td>&nbsp";
											}
											echo "</td></tr>";
										}
									}
									echo "</table><table border='1' bordercolor='#e5e5e5' style='font : 12px Tahoma;text-align:justify' >";?>
									<tr>
									<?	$cons45="select causa from salud.causaexterna where codigo='".$fila['causaexterna']."'";
										$res45=ExQuery($cons45);
										$fila45=ExFetch($res45);
										$CausaExterna=$fila45[0];
										
										$cons45="select finalidad from salud.finalidadesact where codigo='".$fila['finalidadconsult']."' and tipo=1";
										$res45=ExQuery($cons45);
										$fila45=ExFetch($res45);
										$FinalidadConsulta=$fila45[0];
										
										/*$oculto = "";
										if(($fila['finalidadconsult']=="") && ($fila['causaexterna']=="")){
											$oculto = "visibility:hidden; display:none; width:0px;";
										}
										else{
										?>
										<td style="background:#e5e5e5">
											<strong>Causa Externa: </strong></td>
										<td><? echo $CausaExterna?></td>
										<td style="background:#e5e5e5">
											<strong>Finalidad Consulta: </strong></td>
										<td>	
											<? echo $FinalidadConsulta?>
										</td>
										<? }*/ ?>
									</tr>
								<?	echo "</table><table border='0' bordercolor='#e5e5e5' style='font : 12px Tahoma;text-align:justify'  width='100%'>";
								}
								
								if($Mensaje=="Medicamento No Pos"){
									$cons19="select detalle,posologia from salud.plantillamedicamentos where compania='$Compania[0]' and tipoformato='$TipoFormato' and cedpaciente='$Paciente[1]' and formato='$Formato' and id_historia=".$fila['id_historia'];
									$res19=ExQuery($cons19);
									$fila19=ExFetch($res19);
									echo "<tr><td><strong>Principio Activo:</strong> $fila19[0]</td></tr><tr><td><strong>Posologia: </strong>$fila19[1]</td></tr>";
								}
								
								if($Mensaje=="CUP No Pos"){
									$cons19="select cup,nombre from salud.plantillaprocedimientos,contratacionsalud.cups
									where plantillaprocedimientos.compania='$Compania[0]' and tipoformato='$TipoFormato' and cedula='$Paciente[1]' and formato='$Formato' 
									and cup=codigo and cups.compania='$Compania[0]' and id_historia=".$fila['id_historia'];
									$res19=ExQuery($cons19);
									$fila19=ExFetch($res19);
									echo "<tr><td><strong>Codigo CUP:</strong> $fila19[0]</td></tr><tr><td><strong>Nombre CUP: </strong>$fila19[1]</td></tr>";
								}
								
							
								
								
							}
						
					} 
					elseif ($Titulo != 1)
					{
						if($Alineacion=="Vertical")
						{							
							if($CrearTabla==1 && $LineaSola==1){echo "</table>";$CrearTabla=0;
							}
							if($LineaSola==1)
							{
								if($fila['formato']!=="NOTAS EVOLUCION"){
									// Se modifica para que no aparezca el nombre del campo
									echo "<tr><td colspan='99' ><strong> "./*$Mensaje.*/"</strong> <em>$ProfesionalDilig</em> </td></tr><tr><td colspan='99'>";
								}
							}
							elseif($LineaSola==0)
							{
								if($CierraFila){echo "<tr>";}
								if(!$CrearTabla)
								{
									echo "<tr><td>";
									if($SubFormato==1){$Ww=" width='100%'";}									
									echo "<br><br><br><table border='0' $Ww cellpadding=4  bordercolor='#e5e5e5' cellpadding=2 style='font : 12px Tahoma;'><tr>";$CrearTabla=1;
								}
								if(!$SubFormato)
								{									
									echo "<td><strong>".$Mensaje.":</strong><br><em> $ProfesionalDilig</em> </td><td>";									
								}
								else
								{
									$DivFor=explode("/",$IndItems[1]);
									$SFTF=$DivFor[0];$SFFormato=$DivFor[1];							
									echo "</tr>";	
									?> 
                                    <tr><td colspan="99">
										<iframe name="SubF_<? echo $fila['id_historia']."_".$IndItems[0]?>" id="SubF_<? echo $fila['id_historia']."_".$IndItems[0]?>" style="width:<? echo $IndItems[10]?>; height:<? echo $IndItems[9]?>" src="Datos.php?DatNameSID=<? echo $DatNameSID ?>&IdHistoOrigen=<? echo $fila['id_historia']?>&IdItemSF=<? echo $IndItems[0]?>&SFFormato=<? echo $Formato ?>&SFTF=<? echo $TipoFormato ?>&TipoFormato=<? echo $SFTF ?>&Formato=<? echo $SFFormato?>&IdHistoria=<? echo $IdHistoria?>&SoloMuestra=1" frameborder="1"></iframe>
                                        <script language="javascript">//alert("SubF_<? echo $fila['id_historia'].$IndItems[0]?>");</script>
										</td>
									</tr>
                                   <?	
								}
							}
						}
					}
					
					
						if (strtoupper($tipoControl) == "MEDICAMENTOS MULTILINEA" or strtoupper($tipoControl) == "MEDICAMENTOS UNILINEA"){									
									
							mostrarMedsxFormato($Formato,$TipoFormato,$fila['id_historia'], $Paciente[1], $Compania[0], $IdItem);
									
						}
						
						if (strtoupper($tipoControl) == "PROCEDIMIENTOS MULTILINEA" or strtoupper($tipoControl) == "PROCEDIMIENTOS UNILINEA"){									
									
							mostrarProcedxFormato($Formato,$TipoFormato,$fila['id_historia'], $Paciente[1], $Compania[0], $IdItem);
									
						}
					
					
	
					$CierraFila=$IndItems[3];
					if($fila['imagen']){echo "<img src='".$fila['imagen']."'>";}
					$fila[$NumCamp]=str_replace("\n","<br>",$fila[$NumCamp]);
					if($Titulo!=1 && !$Imagen && !$SubFormato && $Alineacion=="Vertical"){
						if($IndItems[7]=="PDF"){
							if($fila[$NumCamp]){
								$Mostrar=str_replace("C:/AppServ/www/HistoriaClinica/ImgsLabs/"," ",$fila[$NumCamp]);?>
								<ul><div style="cursor:hand" title="Ver" onClick="VerPDF('<? echo $fila[$NumCamp]?>')"><? echo $Mostrar?></a>
							<?	
							}
						}
						else{
							if($fila['formato']!=="NOTAS EVOLUCION"){
								// Se incluye la visualizacion de la descripcion de  la prioridad del Triage
								if ((strtoupper($Formato) == "TRIAGE") AND (strtoupper($Mensaje) == "PRIORIDAD")){
									$descTriage = descripcionTriage($fila[$NumCamp]);
									echo "<ul>".$fila[$NumCamp]."  ".$descTriage ;
									
									
								} else{							
									echo "<ul>".$fila[$NumCamp];
								}
							}
						}
					}					
					if($Titulo!=1 && !$Imagen && !$SubFormato && $Alineacion=="Horizontal")	{												
						if($usuario[1]==$fila['usuario'])
						{
							$date1=$fila['fecha'] ." " . $fila['hora'];
							$date2="$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]";
							$s = strtotime($date2)-strtotime($date1);
							$d = intval($s/86400);
							$s -= $d*86400;
							$d = $d*1440;
							$m = intval($s/60) + $d;
							if($m<$TiempoAjuste&&empty($SoloMuestra)){$Lapiz="<a href='NuevoRegistro.php?DatNameSID=$DatNameSID&Formato=$Formato&TipoFormato=$TipoFormato&IdHistoria=".$fila['id_historia'] ."&IdHistoOrigen=$IdHistoOrigen&SFFormato=$SFFormato&SFTF=$SFTF'><img border=0 src='/Imgs/b_edit.png'></a>";}
							else{$Lapiz="&nbsp;";}
						}
						else
						{$Lapiz="&nbsp;";}						
						if(!$fila[$NumCamp]){$fila[$NumCamp]="&nbsp;";}
						if($IndItems[7]=="PDF"){
							if($fila[$NumCamp]){
								$Mostrar=str_replace("C:/AppServ/www/HistoriaClinica/ImgsLabs/"," ",$fila[$NumCamp]);?>
								<ul><div style="cursor:hand" title="Ver" onClick="VerPDF('<? echo $fila[$NumCamp]?>')"><? echo $Mostrar?></a>
					<?		}
						}
						else{
							echo "<td>".$fila[$NumCamp]."</td>";
						}
					}
				
				}				
			}			
			if($Laboratorio){
				if($fila['numproced']){
					if($NumSerProced){
						$consLab="select interpretacion from histoclinicafrms.ayudaxformatos where cedula='$Paciente[1]' and compania='$Compania[0]' and numservicio=$NumSerProced
					and numproced=".$fila['numproced']."and formato='$Formato' and tipoformato='$TipoFormato' and id_historia=".$fila['id_historia'];
					}
					else{
						$consLab="select interpretacion from histoclinicafrms.ayudaxformatos 
						where cedula='$Paciente[1]' and compania='$Compania[0]' and numservicio=".$fila['numservicio']."
						and numproced=".$fila['numproced']."and formato='$Formato' and tipoformato='$TipoFormato' and id_historia=".$fila['id_historia'];
						//echo "<br>".$consLab;
					}
					$resLab=ExQuery($consLab);
					$filaLab=ExFetch($resLab);
					//echo $consLab;
				}
					?>     
            	<tr><td><strong>Interpretacion Laboratorio:&nbsp;</strong><? echo $filaLab[0]?></td></tr>
					<?
			}
			
			
			

			if($Alineacion=="Vertical"){echo "</table><br><br>";}else{if($Lapiz){echo "<td>$Lapiz</td>";}echo "</tr>";}
		}

	if($Paginacion>0)
	{
		echo "<table border='1' rules='cols' bordercolor='#e5e5e5' style='font : 12px Tahoma;text-align:justify'>";
		echo "<tr><td colspan=4 bgcolor='#e5e5e5' align='center'><strong>Paginacion</strong></td></tr>";
		echo "<tr valign='middle'>";
		if($LimInf>0){
			echo "<td><a href='Datos.php?DatNameSID=$DatNameSID&Formato=$Formato&TipoFormato=$TipoFormato&AntPagina=1&LimSup=$LimSup&LimInf=$LimInf'><img src='/Imgs/izquierda.bmp' border='0' style='width:12px;'></td><td>Anterior</a></td>";}
		if($Paginacion<=$NumTotReg)
		{
		echo "<td>Siguiente</a></td><td><a href='Datos.php?DatNameSID=$DatNameSID&Formato=$Formato&TipoFormato=$TipoFormato&SigPagina=1&LimSup=$LimSup&LimInf=$LimInf'><img src='/Imgs/derecha.bmp' border='0' style='width:12px;'></td>";}
		echo "</tr>";
		echo "</table>";
	}

	if($RutaAnt&&empty($SoloMuestra))		
	{
		if($banRutAnt1==1&&$banRutAnt2==1){
        	$DatoPaciente=explode("-",$Paciente[1]);
			echo "<center><a style='font : 12px Tahoma;color:blue;font-weight:bold' href='$RutaAnt&CedulaPte=$DatoPaciente[0]&DatNameSID=$DatNameSID'>Ver Historia Clinica Anterior</a></center>";
		}
	}
?>
<input type="hidden" name="SoloUno" value="<? echo $SoloUno?>">
</form>
<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe> 
</body>
