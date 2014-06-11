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
	
	if ($NoServicio == NULL){
		// Esto aplica para las Ordenes Medicas que no tienen un Servcios Activo (Caso Alta de Urgencias en el Formato de Hoja de Ingreso)
		$cons = "SELECT numservicio FROM Salud.Servicios WHERE cedula = '$Paciente[1]' AND UPPER(tiposervicio) = 'URGENCIAS' ORDER BY numservicio DESC LIMIT 1 ";
		$res = ExQuery($cons);
		$fila = ExFetchArray($res);
		$NoServicio = $fila['numservicio'];
					
	}
	
	if(!$NoOrden && !$Origen)
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
	if(!$Hora){ $Hora = $ND[hours];}
	if(!$Minuto){ $Minuto = $ND[minutes];}
	if(!$TMPCOD){$TMPCOD=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);}
	if($Cancelar)
	{
		$cons = "Delete from Salud.TMPHorasCantidadMedicamento where Compania='$Compania[0]' and CedPaciente='$Paciente[1]'
		and Usuario='$usuario[1]' and TMPCOD='$TMPCOD'";
		$res = ExQuery($cons);
		?><script language="javascript">location.href="NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>";</script><?
	}?>
	<iframe scrolling="no" id="FrameFondo" name="FrameFondo" frameborder="0" height="0" width="0" style="filter:Alpha(Opacity=200, FinishOpacity=40, Style=2, StartX=20, StartY=40, FinishX=0, FinishY=0);display:none;border:thin; background-color:transparent" ></iframe>
	<iframe id="FrameOpenerNP" name="FrameOpenerNP" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" scrolling="yes"></iframe>
<?	if($Guardar)
	{
		if(!$Editar)
		{
            $cons = "Select NombreProd1,UnidadMedida,Presentacion from Consumo.CodProductos where Compania='$Compania[0]'
            and Anio=$ND[year] and AlmacenPpal='$AlmacenPpal' and AutoId=$AutoIdProd";
            $res = ExQuery($cons);
            $fila = ExFetch($res);
            $Medicamento = "$fila[0] $fila[2] $fila[1]";
            $TextoOrden = "$Medicamento - #$Cantidad $TextoHora";
            
            if(!$Paquete)
            {
                if($DosisUnica == "on"){$Du = 1;}else{$Du=1;}
                $cons0 = "Insert into Salud.HoraCantidadXMedicamento (Compania,AlmacenPpal,AutoId,NoFormula,Hora,Cantidad,Nota,Paciente,Tipo,Fecha,Estado,NumOrden,IdEscritura)
                values ('$Compania[0]','$AlmacenPpal',$AutoIdProd,1,$ND[hours],$Cantidad,'$Nota','$Paciente[1]','U','$ND[year]-$ND[mon]-$ND[mday]','AC',$NoOrden,$IdEscritura)";
                $res0 = ExQuery($cons0);
                $CantDiaria = $CantDiaria + $fila[2];
				
				
                
                $cons = "Insert into Salud.OrdenesMedicas (Compania,Fecha,Cedula,NumServicio,Detalle,IdEscritura,NumOrden,Usuario,TipoOrden,Acarreo,posologia,DosisUnica,viasumin,tipodosis) values
                ('$Compania[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',$NoServicio,
                '$TextoOrden',$IdEscritura,$NoOrden,'$usuario[1]','Medicamento Urgente',1,'$Nota',$Du,'$ViadeSum','$tipodosis')";
                $res = ExQuery($cons);
                
                $Hoy =date('w',mktime(0,0,0,$ND[mon],$ND[mday],$ND[year]));
                switch($Hoy)
                {
                    case 0: $InsDia = " ,Domingo"; $ValueDia=",1"; break;
                    case 1: $InsDia = " ,Lunes"; $ValueDia=",1"; break;
                    case 2: $InsDia = " ,Martes"; $ValueDia=",1"; break;
                    case 3: $InsDia = " ,Miercoles"; $ValueDia=",1"; break;
                    case 4: $InsDia = " ,Jueves"; $ValueDia=",1"; break;
                    case 5: $InsDia = " ,Viernes"; $ValueDia=",1"; break;
                    case 6: $InsDia = " ,Sabado"; $ValueDia=",1"; break;
                }
                $CantDiaria=$Cantidad;		
                $cons = "Insert into Salud.PlantillaMedicamentos (Compania,AlmacenPpal,AutoIdProd,Usuario,FechaFormula,
                CedPaciente,FechaIni,FechaFin,CantDiaria,ViaSuministro,Justificacion,Notas,NumServicio,Detalle,TipoMedicamento,
                            posologia,numOrden,idescritura,dosisunica $InsDia)
                values ('$Compania[0]','$AlmacenPpal','$AutoIdProd','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$Paciente[1]',
                '$ND[year]-$ND[mon]-$ND[mday]',NULL,$CantDiaria,'$ViadeSum','$Justificacion','$Notas',$NoServicio,'$TextoOrden',
                            'Medicamento Urgente','$Nota',$NoOrden,$IdEscritura,$Du $ValueDia) returning *"; //Falta la Nota
                $res = ExQuery($cons);
				$fila=ExFetchAssoc($res);
				
				// Consulta tiposervicio para enviarlo a Notas Evolución
				$cons5 = "Select numservicio,tiposervicio from Salud.Servicios 
                where Compania = '$Compania[0]' and cedula='$Paciente[1]' and estado='AC' order by numservicio desc";
                //echo $cons5;
                $res5 = ExQuery($cons5);
                $fila5 = ExFetchAssoc($res5);
                
				// Consulta el id_historia para enviarlo a Notas evolución
                $consih="SELECT id_historia FROM histoclinicafrms.tbl00004 ORDER BY id_historia DESC LIMIT 1";
                $resih = ExQuery($consih);
                $filaih=ExFetchAssoc($resih);
                $filaih['id_historia']+=1;
                
				$textonota = "";
				if($fila['notas']!="")
					$textonota = "Nota";
						
				if($Unidad==""){
					//$Unidad = NULL;
					// Envía el medicamento a Notas Evolución
                $consulta="INSERT INTO histoclinicafrms.tbl00004 "
                  . "(ambito, cargo, causaexterna, cedula, cerrado, cmp00003, cmp00005, cmp00007, cmp00009, compania, dx1, dx2, dx3, dx4, dx5, fecha, fechaajuste, finalidadconsult, formato, hora, id_historia, id_historia_origen, noliquidacion, numproced, numservicio, padreformato, padretipoformato, tipodx, tipoformato, unidadhosp, usuario, usuarioajuste) "
                  . "VALUES ( '".$fila5['tiposervicio']."', '".$usuario[3]."', NULL, '".$fila['cedpaciente']."', NULL, '', '".$fila['detalle']." ".$fila['posologia']." Vía ".$fila['viasuministro']." Justificacion ".$fila['justificacion']." $textonota ".$fila['notas']."', '', '', '".$fila['compania']."', NULL, NULL, NULL, NULL, NULL, '$ND[year]-$ND[mon]-$ND[mday]', NULL, '', 'NOTAS EVOLUCION', '$ND[hours]:$ND[minutes]:$ND[seconds]', '".$filaih['id_historia']."', NULL, NULL, NULL, '".$fila['numservicio']."', '', '', NULL, 'HISTORIA CLINICA', NULL, '".$fila['usuario']."', '' );";
				}
				else{
					// Envía el medicamento a Notas Evolución
                $consulta="INSERT INTO histoclinicafrms.tbl00004 "
                  . "(ambito, cargo, causaexterna, cedula, cerrado, cmp00003, cmp00005, cmp00007, cmp00009, compania, dx1, dx2, dx3, dx4, dx5, fecha, fechaajuste, finalidadconsult, formato, hora, id_historia, id_historia_origen, noliquidacion, numproced, numservicio, padreformato, padretipoformato, tipodx, tipoformato, unidadhosp, usuario, usuarioajuste) "
                  . "VALUES ( '".$fila5['tiposervicio']."', '".$usuario[3]."', NULL, '".$fila['cedpaciente']."', NULL, '', '".$fila['detalle']." ".$fila['posologia']." Vía ".$fila['viasuministro']." Justificacion ".$fila['justificacion']." $textonota ".$fila['notas']."', '', '', '".$fila['compania']."', NULL, NULL, NULL, NULL, NULL, '$ND[year]-$ND[mon]-$ND[mday]', NULL, '', 'NOTAS EVOLUCION', '$ND[hours]:$ND[minutes]:$ND[seconds]', '".$filaih['id_historia']."', NULL, NULL, NULL, '".$fila['numservicio']."', '', '', NULL, 'HISTORIA CLINICA', '".$Unidad."', '".$fila['usuario']."', '' );";
				}
				
                $resnoev = ExQuery($consulta);
                $filanoev=ExFetch($resnoev);
				
                if(!$POS){

                    //Envio de la orden al correo						
                    $cons="select ambito,pabellon from salud.pacientesxpabellones where compania='$Compania[0]' and numservicio=$NoServicio and estado='AC'
                    and fechae is null";
                    $res = ExQuery($cons);	$fila=ExFetch($res); $Pab=$fila[1]; $Amb=$fila[0];

                    $cons="select id from central.correos where compania='$Compania[0]'  order by id desc"; 
                    $res=ExQuery($cons); $fila=ExFetch($res); $Id=$fila[0]+1;

                    $Msj="Se ha ordenado el medicamento NO POS $Medicamento al paciente $Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5]- CC $Paciente[1] 
                    ($Amb - $Pab) el dia $ND[year]-$ND[mon]-$ND[mday] a las $ND[hours]:$ND[minutes]:$ND[seconds] <br><br>Att=$usuario[0]";			
                    $cons2="select usuario from salud.medicos,salud.cargos
                    where medicos.compania='$Compania[0]' and medicos.cargo=cargos.cargos and (vistobuenojefe=1 or vistobuenofarmacia=1) and cargos.compania='$Compania[0]'";
                    $res2=ExQuery($cons2);
                    while($fila2=ExFetch($res2))
                    {
                        $cons="insert into central.correos (compania,usucrea,fechacrea,usurecive,mensaje,id,asunto) values
                        ('$Compania[0]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$fila2[0]','$Msj',$Id
                        ,'Orden Medicamento Urgente NO POS')";							
                        $res=ExQuery($cons);
                        $Id++;
                    }
                    //------------------------------
                    $cons="select formato,tipoformato,tblformat from historiaclinica.formatos 
                    where compania='$Compania[0]' and estado='AC' and nopos='Medicamentos No POS'";
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

                        frames.FrameOpenerNP.location.href="/HistoriaClinica/NuevoRegistro.php?DatNameSID=<? echo $DatNameSID?>&CedPac=<? echo $Paciente[1]?>&Fecha=<? echo "$ND[year]-$ND[mon]-$ND[mday]"?>&NumSer=<? echo $NoServicio?>&SoloUno=<? echo $IdH;?>&Formato=<? echo $fila[0]?>&TipoFormato=<? echo $fila[1]?>&Medicamento=<? echo $Medicamento?>&Posologia=<? echo $Nota?>&MedNP=1&AlmacenPpal=<? echo $AlmacenPpal?>&AutoIdProd=<? echo $AutoIdProd?>&IdEscritura=<? echo $IdEscritura?>&FechaI=<? echo "$ND[year]-$ND[mon]-$ND[mday]"?>&TipoMedicamento=Medicamento Urgente";
                        document.getElementById('FrameOpenerNP').style.position='absolute';
                        document.getElementById('FrameOpenerNP').style.top='10px';
                        document.getElementById('FrameOpenerNP').style.left='10px';
                        document.getElementById('FrameOpenerNP').style.display='';
                        document.getElementById('FrameOpenerNP').style.width='990';
                        document.getElementById('FrameOpenerNP').style.height='790';						
                    </script>	
            <?	}

                else
                {
                    
                    if(!$MasMeds)
                    {
                        ?><script language="javascript">location.href="NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>";</script><?
                    }
                    else
                    {
                        ?><script language="javascript">
                            location.href="OMMedUrgentes.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura++?>";
                        </script><?
                    }
                }
            }
            else
            {
                $cons = "Insert into Contratacionsalud.ItemsXPaquete (Compania,IdPaq,UsuCrea,FechaCrea,Codigo,Tipo,Detalle,
                Justificacion,AlmacenPpal,Cantidad,ViaSumnistro,Nota,Posologia)
                values
                ('$Compania[0]',$IdPaq,'$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
                '$AutoIdProd','Medicamentos','$TextoOrden','$Justificacion','$AlmacenPpal',$Cantidad,'$ViadeSum','$Notas','$Nota')";
                $res = ExQuery($cons);
                if(!$MasMeds)
                {
                    ?><script language="javascript">
                        location.href="/Contratacion/NewPaquete.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&IdPaquete=<?echo $IdPaq?>&Entidad=<?echo $Entidad?>&Contrato=<?echo $Contrato?>&NoContrato=<? echo $NoContrato?>";
                    </script><?
                }
                else
                {
                    ?><script language="javascript">
                        location.href="OMMedUrgentes.php?DatNameSID=<? echo $DatNameSID?>&Origen=Paquetes&IdPaq=<?echo $IdPaq?>&Paquete=<?echo $Paquete?>&IdPaquete=<?echo $IdPaq?>&Entidad=<?echo $Entidad?>&Contrato=<?echo $Contrato?>&NoContrato=<? echo $NoContrato?>";
                    </script><?
                }
            }
		}
	}
	if($Eliminar)
	{
		$cons = "Delete from Salud.TMPHorasCantidadMedicamento where Compania='$Compania[0]' and CedPaciente='$Paciente[1]'
		and Usuario='$usuario[1]' and TMPCOD='$TMPCOD' and Hora = $HoraElim and Minuto = $MinutoElim and Cantidad = $CantidadElim";
		$res = ExQuery($cons);
	}
	if($Anadir)
	{   
		$cons = "Insert into Salud.TMPHorasCantidadMedicamento(Compania,CedPaciente,Usuario,TMPCOD,Hora,Minuto,Cantidad,Nota)
		values ('$Compania[0]','$Paciente[1]','$usuario[1]','$TMPCOD',$Hora,$Minuto,$Cantidad,'$Nota')"; 
		$res = ExQuery($cons);
		//echo $cons;
	}
?>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function AbrirMedicamentos()
	{
		frames.FrameOpener.location.href="Medicamentos.php?DatNameSID=<? echo $DatNameSID?>&Formulacion=Urgentes&Paquete=<?echo $Paquete?>&Entidad=<?echo $Entidad?>&Contrato=<?echo $Contrato?>&NoContrato=<?echo $NoContrato?>";
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='20px';
		document.getElementById('FrameOpener').style.left='30px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='600px';
		document.getElementById('FrameOpener').style.height='450px';
	}
    function Ligar_Paquetes(AutoId,NoContrato,Entidad,Contrato)
	{
		frames.FrameOpener.location.href="CargarPaquetes.php?DatNameSID=<? echo $DatNameSID?>&NumServicio=<?echo $NoServicio?>&NoOrden=<?echo $NoOrden?>&IdEscritura=<?echo $IdEscritura?>&Tipo=Medicamentos&Codigo="+AutoId+"&NoContrato="+NoContrato+"&Entidad="+Entidad+"&Contrato="+Contrato;
		//document.getElementById('FrameOpener').scrolling='yes';
        document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='20px';
		document.getElementById('FrameOpener').style.left='30px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='600px';
		document.getElementById('FrameOpener').style.height='450px';
        
	}
	function Validar(Origen)
	{
		if(document.FORMA.Cantidad.value==""){
			alert("Debe seleccionar una cantidad!!!");
		}
		else{
			if(document.FORMA.Nota.value==""){
				alert("Debe digitar la posologia!!!");
			}		
			else{
				if(document.FORMA.tipodosis.value==""){
					alert("Debe seleccionar el tipo de dosis!!!");
				}
				else{
					if(document.FORMA.NoFormato.value==1&&document.FORMA.NoValidar.value!="1"){
						alert("Este medicamento no puede ser ordenado ya que no se encuentra el formato para justificacion de Medicamentos No POS");
					}
					else{
						document.FORMA.Guardar.value=1;
						if(Origen=="MasMeds")
						{
							document.FORMA.MasMeds.value=1;
						}
						document.FORMA.submit();
					}
				}
			}
		}
	}
</script>
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
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="Hidden" name="AutoIdProd" value="<? echo $AutoIdProd?>" />
<input type="hidden" name="AlmacenPpal" value="<? echo $AlmacenPpal?>" />
<input type="Hidden" name="IdEscritura" value="<? echo $IdEscritura?>" />
<input type="hidden" name="NoServicio" value="<? echo $NoServicio?>" />
<input type="hidden" name="NoOrden" value="<? echo $NoOrden?>" />
<input type="hidden" name="POS" value="<? echo $POS?>" />
<input type="hidden" name="MedNoPos" value="<? echo $MedNoPos?>" />
<input type="hidden" name="TipoFormato" value="<? echo $TipoFormato?>">
<input type="hidden" name="Formato" value="<? echo $Formato?>">
<input type="hidden" name="AbrirForm" value="<? echo $AbrirForm?>" />
<input type="hidden" name="Paquete" value="<?echo $Paquete?>" />
<input type="hidden" name="Entidad" value="<?echo $Entidad?>" />
<input type="hidden" name="Contrato" value="<?echo $Contrato?>" />
<input type="hidden" name="NoContrato" value="<?echo $NoContrato?>" />
<input type="hidden" name="IdPaq" value="<?echo $IdPaq?>" />
	<table rules="groups" width="60%" align="center" cellpadding="2" border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 13px Tahoma;'>
        <tbody><tr>
        	<td colspan="4" align="center"  style="padding-bottom:25px; padding-top:10px;">
			<!--<button onclick="AbrirMedicamentos()" value="Escoger Medicamento" style="width:200px;" name="Escoger Medicamento"><img src="/Imgs/HistoriaClinica/bigfolder.png"><br>Escoger Medicamento</button>-->
			<div style="width:100px;">
                              <a class="button" href="#" onclick="AbrirMedicamentos()">
                                    <span>
                                            <span>
                                                    <span>
                                                            <span>Escoger Medicamento</span>
                                                    </span>
                                            </span>
                                    </span>
                              </a>
                        </div>
			<br>
			
			</td>
        </tr>
		<tr>
			<td colspan="4" align="center"  style="padding-bottom:25px;">
			<input type="Text" name="Medicamento" value="<? echo $Medicamento?>" readonly size="90" style="text-align:center;border-style:solid;border-width:0px;"/>
			</td>
		</tr>
		</tbody>
        <? if($Medicamento)
		{
			?>
			<input type="hidden" name="Eliminar" />
            <input type="hidden" name="HoraElim" />
            <input type="hidden" name="MinutoElim" />
            <input type="hidden" name="CantidadElim" />
            <input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>" />
			<?
			  	$cons = "Select Hora,Minuto,Cantidad,Nota from Salud.TMPHorasCantidadMedicamento where Compania='$Compania[0]'
				and CedPaciente='$Paciente[1]' and Usuario='$usuario[1]' and TMPCOD='$TMPCOD' order by Hora asc, Minuto asc";
				//echo $cons;
				$res = ExQuery($cons);
				if(ExNumRows($res)>0)
				{ $j=0; $ExisteHoras = 1;
				?><td colspan="4"><table align="center" 
                cellpadding="2" border="1" bordercolor="<? echo $Estilo[1]?>" style='font : normal normal small-caps 13px Tahoma;'>
                	<? while($fila = ExFetch($res))
					{
						if($j==8){$j=0;echo"</tr><tr>";}
						?><td align="center" onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" title="<? echo $fila[3]?>">
						<? echo "$fila[0]:$fila[1] ($fila[2])"; ?>
                        <img src="/Imgs/b_drop.png" style="cursor:hand" title="Eliminar Hora"
                        onClick="FORMA.Eliminar.value='1';
                        FORMA.HoraElim.value='<? echo $fila[0]?>';
                        FORMA.MinutoElim.value='<? echo $fila[1]?>';
                        FORMA.CantidadElim.value='<? echo $fila[2]?>';FORMA.submit();" /></td><? 
						$j++;
					}?>
                </table></td><?
				}
				else{ unset($ExisteHoras);}
			?><tbody><tr bgcolor="#e5e5e5" align="center">
            	<td width="40%">Cantidad</td><td width="60%">Hora de suministro</td>
			</tr>            
            <tr align="center">
            <? /*
            	<td><select name="Hora" onChange="FORMA.submit()">
                <? for($i=$ND[hours];$i<24;$i++)
                {
					if($Hora==$i){echo "<option selected value='$i'>$i</option>";}
					else{echo "<option value='$i'>$i</option>";}
				}?>
                </select></td>
                <td><select name="Minuto" onChange="FORMA.submit()">
                <? 
				if($Hora==$ND[hours])
				{
					for($i=$ND[minutes];$i<60;$i++)
                	{
						if($Minuto==$i){echo "<option selected value='$i'>$i</option>";}
						else{echo "<option value='$i'>$i</option>";}
					}
				}
				else
				{
					for($i=0;$i<60;$i++)
                	{	
						if($Minuto==$i){echo "<option selected value='$i'>$i</option>";}
						else{echo "<option value='$i'>$i</option>";}
					}
				}
				?>
                </select><? */ ?></td>
                <td>
                <input type="text" name="Cantidad" onKeyDown="xNumero(this)" onKeyPress="xNumero(this)" onKeyUp="xNumero(this)" value="<? echo $Cantidad?>" style="width:60">
                &nbsp;&nbsp;
       		<?	
				
				
			
			
			
                if(!$Paquete)
                {	
					$consAmb="select consultaextern from salud.servicios,salud.ambitos
                    where numservicio=$NoServicio and cedula='$Paciente[1]' and servicios.compania='$Compania[0]' and ambito=tiposervicio";
                    
					
					//echo $consAmb;
                    $resAmb=ExQuery($consAmb);				
                    $filaAmb=ExFetch($resAmb);
                    if($filaAmb[0]!=1){?>
	                 <input type="checkbox" name ="DosisUnica" disabled="disabled" value="on" checked style = "display: none;"/>
    	            <? //if($Cantidad){echo "<button type='submit' title='A&ntilde;adir Hora' name='Anadir'><img src='/Imgs/b_check.png' /></button>";} ?>
        	        </td>
                    <?	}
                    else{?>
					<input type="text" name="DosisUnica" value="on">
                    <?	}
                }
                ?>
	      		<td><textarea name="Nota" style="width:100%"
                onKeyDown="xLetra(this)" onKeyUp="xLetra(this)"><? echo $Nota?></textarea></td>
            </tr>
            </tbody><?
			//if($ExisteHoras){
				unset($MostrarCancelar);
				?>
				<tbody><tr><td colspan="4">
                <table width="100%" border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 13px Tahoma;'>
				<tr align="center">
             	<td colspan="2">Via de Suministro:
                <select name="ViadeSum">
                <?
                	$cons = "Select NombreVia from Salud.ViadeSuministro where Compania='$Compania[0]' ORDER BY prioridad";
					$res = ExQuery($cons);
					while($fila = ExFetch($res))
					{
						if($ViadeSum==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
						else {echo "<option value='$fila[0]'>$fila[0]</option>";}
					}
				?>
                </select></td>
				<td colspan="2">Tipo de Dosis:
                <select name="tipodosis"><option></option>
                <?
                	$cons = "Select tipo from Salud.tiposdosis where Compania='$Compania[0]'";
					$res = ExQuery($cons);
					while($fila = ExFetch($res))
					{
						if($tipodosis==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
						else {echo "<option value='$fila[0]'>$fila[0]</option>";}
					}
				?>
                </select></td>
                <td>&nbsp;</td>
         	<?	/*
                <td colspan="4">Traido X:
                <select name="TraidoX"><option value=""></option>
                <?
                	$cons = "Select CedAcudiente,NombreAcudiente,Parentesco from ContratacionSalud.Acudientes where Compania='$Compania[0]' and
					CedPaciente='$Paciente[1]'";
					$res = ExQuery($cons);
					while($fila = ExFetch($res))
					{
						if($TraidoX==$fila[0]){echo "<option selected value='$fila[0]'>$fila[1]($fila[2])</option>";}
						else{ echo "<option value='$fila[0]'>$fila[1]($fila[2])</option>";}
					}
				?>
                </select></td>
				*/?>
                </tr>
                <tr>
                	<td colspan="7" bgcolor="#e5e5e5" align="center">Justificaci&oacute;n</td>
                </tr>
                <tr>
                	<td colspan="7">
                    	<textarea name="Justificacion" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" style="width:100%"><? echo $Justificacion?></textarea>
                    </td>
                </tr>
                <tr>
                	<td colspan="7" bgcolor="#e5e5e5" align="center">Notas</td>
                </tr>
                <tr>
                	<td colspan="7">
                    	<textarea name="Notas" style="width:100%" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"><? echo $Notas?></textarea>
                    </td>
                </tr></table>
                </td></tr>
                <tr><td colspan="4" align="center">
                <input type="button" value="Guardar Medicamento" onClick="Validar('Guardar')"/>
                <input type="button" value="Mas Medicamentos" onClick="Validar('MasMeds')"/>
                <input type="button" name="BtnMostrarCancelar" value="Cancelar" 
                       <?if(!$Paquete)
                       {
                           ?>onClick="location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"<?
                       }
                       else
                       {
                           ?>onClick="location.href='/Contratacion/NewPaquete.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&IdPaquete=<?echo $IdPaq?>&Entidad=<?echo $Entidad?>&Contrato=<?echo $Contrato?>&NoContrato=<? echo $NoContrato?>'"
                       <?
                       }
                       ?>
                    />
                </td></tr></tbody>
				<?
			//}
		} ?>
	</table>
<?
	if($MostrarCancelar)
	{?><center><input type="button" name="BtnMostrarCancelar" value="Cancelar" 
    <?if(!$Paquete)
   {
       ?>onClick="location.href='NuevaOrdenMedica.php?DatNameSID=<? echo $DatNameSID?>&IdEscritura=<? echo $IdEscritura?>'"<?
   }
   else
   {
       ?>onClick="location.href='/Contratacion/NewPaquete.php?DatNameSID=<? echo $DatNameSID?>&Editar=1&IdPaquete=<?echo $IdPaq?>&Entidad=<?echo $Entidad?>&Contrato=<?echo $Contrato?>&NoContrato=<? echo $NoContrato?>'"
   <?
   }
   ?> /></center><? }
?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Guardar" value="<? echo $Guardar?>" />
<input type="hidden" name="MasMeds" value="<? echo $MasMeds?>" />
<input type="hidden" name="NoFormato" />
<input type="hidden" name="NoValidar" />
</form>
<script language="javascript">
if(document.FORMA.POS.value!="1"&&document.FORMA.MedNoPos.value!=1){document.FORMA.NoFormato.value="1";}else{document.FORMA.NoFormato.value="";}
</script> 
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge;"></iframe>
</body>
