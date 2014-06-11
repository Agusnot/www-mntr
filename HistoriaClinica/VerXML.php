<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
    $ND = getdate();

        require_once('lib/nusoap.php');$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
        $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
        $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
        $useCURL = isset($_POST['usecurl']) ? $_POST['usecurl'] : '0';
        $client = new soapclient('http://190.143.98.112/wscomips/WShistoriaclinica.asmx?WSDL', true,
                                $proxyhost, $proxyport, $proxyusername, $proxypassword);
        $err = $client->getError();
        if ($err)
        {
            echo '<h2>Constructor error</h2><pre>--------' . $err . '--------</pre>';
        }
        //$client->setUseCurl($useCURL);
    
    $cons="select identificacion,primape,segape,primnom,segnom,tiposangre,fecnac,sexo,direccion,telefono,codws
	from central.terceros,central.tiposdocumentos where compania='$Compania[0]' and identificacion='$Paciente[1]' and tiposdocumentos.tipodoc=terceros.tipodoc";
	//echo $cons;
	$res=ExQuery($cons);
	$PacienteXML=ExFetch($res);
	if($PacienteXML[5]=="A Pos"||$PacienteXML[5]=="AB Po"||$PacienteXML[5]=="B Pos"||$PacienteXML[5]=="O Pos"){$RH="+";}else{$RH="-";}
	$FecNac=explode("-",$PacienteXML[6]);	
	$cons="select tblformat from historiaclinica.formatos where compania='$Compania[0]' and formato='$Formato' and tipoformato='$TipoFormato'";
	$res=ExQuery($cons);
	$Tbl=ExFetch($res);
	$cons="select numservicio,fechaing,medicotte,autorizac1 from salud.servicios where compania='$Compania[0]' and cedula='$Paciente[1]' 
	order by numservicio desc";
	$res=ExQuery($cons);
	$Servicio=ExFetch($res); 
	if($Servicio[2]){
		$cons="select nombre,especialidad,cedula,rm from central.usuarios,salud.medicos 
		where usuarios.usuario='$Servicio[2]' and compania='$Compania[0]'";	
		$res=ExQuery($cons);		
		$Medico=ExFetch($res);
	}
	if($Servicio[0]){
		$cons="select fecha,cup from salud.agenda where numservicio=$Servicio[0] and compania='$Compania[0]'";
		$res=ExQuery($cons);		
		$Agenda=Exfetch($res);
		$fechaCita=explode("-",$Agenda[0]);
	}
	$cons="select fecha from histoclinicafrms.".$Tbl[0]." where compania='$Compania[0]' and formato='$Formato' and tipoformato='$TipoFormato'
	and id_historia=$IdHistoria";
	$res=ExQuery($cons);
	$FechaAtencion=ExFetch($res);
	$FechaAte=explode("-",$FechaAtencion[0]);
	
	$XMLFinal2=htmlspecialchars("<HC>");
		$XMLFinal2=$XMLFinal2."<dd>".htmlspecialchars("<TipoEsquema>HC</TipoEsquema>")."<br><dd>";
		$XMLFinal2=$XMLFinal2.htmlspecialchars("<EntReporta>891200274</EntReporta>")."<br><dd>";
		$XMLFinal2=$XMLFinal2.htmlspecialchars("<UsrReporta>891200274</UsrReporta>")."<br><dd>";	
		$XMLFinal2=$XMLFinal2.htmlspecialchars("<PACIENTE>")."<br>";	
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<TpIdPaciente>".$PacienteXML[10]."</TpIdPaciente>")."<br>";
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<NumIdPaciente>".$PacienteXML[0]."</NumIdPaciente>")."<br>";
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<NombrePaciente>$PacienteXML[1] $PacienteXML[2] $PacienteXML[3] $PacienteXML[4]</NombrePaciente>")."<br>";
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<FactorRH>".$RH."</FactorRH>")."<br>";			
		$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<GrpSanguineo>".substr($PacienteXML[5],0,1)."</GrpSanguineo>")."<br>";
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<FechaNac>$FecNac[2]/$FecNac[1]/$FecNac[0]</FechaNac>")."<br>";
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<Sexo>".$PacienteXML[7]."</Sexo>")."<br>";
    		$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<Direccion>".$PacienteXML[8]."</Direccion>")."<br>";
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<Telefono>".$PacienteXML[9]."</Telefono>")."<br>";
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<Ocupacion></Ocupacion>")."<br>";
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<Estrato></Estrato>")."<br>";
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<Riesgo></Riesgo>")."<br>";
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<Responsable></Responsable>")."<br>";
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<Acompanante></Acompanante>")."<br>";
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<Celular></Celular>")."<br>";
		$XMLFinal2=$XMLFinal2."<dd>".htmlspecialchars("</PACIENTE>")."<br>";	
		$XMLFinal2=$XMLFinal2."<dd>".htmlspecialchars("<PROFESIONAL>")."<br>";
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<TpIdProfesional>CC</TpIdProfesional>")."<br>";
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<NumIdProfesional>$Medico[2]</NumIdProfesional>")."<br>";
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<NombreProfesional>$Medico[0]</NombreProfesional>")."<br>";
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<Registro>$Medico[3]</Registro>")."<br>";
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<Especialidad>$Medico[1]</Especialidad>")."<br>";
		$XMLFinal2=$XMLFinal2."<dd>".htmlspecialchars("</PROFESIONAL>")."<br>";	
    	$XMLFinal2=$XMLFinal2."<dd>".htmlspecialchars("<ATENCION>")."<br>";	
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<FechaCita>$fechaCita[2]/$fechaCita[1]/$fechaCita[0]</FechaCita>")."<br>";
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<FechaAtencion>$FechaAte[2]/$FechaAte[1]/$FechaAte[0]</FechaAtencion>")."<br>";
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<CUPS>$Agenda[1]</CUPS>")."<br>";
			$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<Autorizacion>$Servicio[3]</Autorizacion>")."<br>";
   
    $XMLFinal="<HC>";
		$XMLFinal=$XMLFinal."<TipoEsquema>HC</TipoEsquema>";
		$XMLFinal=$XMLFinal."<EntReporta>891200274</EntReporta>";
		$XMLFinal=$XMLFinal."<UsrReporta>891200274</UsrReporta>";	
		$XMLFinal=$XMLFinal."<PACIENTE>";	
			$XMLFinal=$XMLFinal."<TpIdPaciente>".$PacienteXML[10]."</TpIdPaciente>";
			$XMLFinal=$XMLFinal."<NumIdPaciente>".$PacienteXML[0]."</NumIdPaciente>";
			$XMLFinal=$XMLFinal."<NombrePaciente>$PacienteXML[1] $PacienteXML[2] $PacienteXML[3] $PacienteXML[4]</NombrePaciente>";
			$XMLFinal=$XMLFinal."<FactorRH>".$RH."</FactorRH>";			
			$XMLFinal=$XMLFinal."<GrpSanguineo>".substr($PacienteXML[5],0,1)."</GrpSanguineo>";
			$XMLFinal=$XMLFinal."<FechaNac>$FecNac[2]/$FecNac[1]/$FecNac[0]</FechaNac>";
			$XMLFinal=$XMLFinal."<Sexo>".$PacienteXML[7]."</Sexo>";
			$XMLFinal=$XMLFinal."<Direccion>".$PacienteXML[8]."</Direccion>";
			$XMLFinal=$XMLFinal."<Telefono>".$PacienteXML[9]."</Telefono>";
			$XMLFinal=$XMLFinal."<Ocupacion></Ocupacion>";
			$XMLFinal=$XMLFinal."<Estrato></Estrato>";
			$XMLFinal=$XMLFinal."<Riesgo></Riesgo>";
			$XMLFinal=$XMLFinal."<Responsable></Responsable>";
			$XMLFinal=$XMLFinal."<Acompanante></Acompanante>";
			$XMLFinal=$XMLFinal."<Celular></Celular>";
		$XMLFinal=$XMLFinal."</PACIENTE>";	
		$XMLFinal=$XMLFinal."<PROFESIONAL>";
			$XMLFinal=$XMLFinal."<TpIdProfesional>CC</TpIdProfesional>";
			$XMLFinal=$XMLFinal."<NumIdProfesional>$Medico[2]</NumIdProfesional>";
			$XMLFinal=$XMLFinal."<NombreProfesional>$Medico[0]</NombreProfesional>";
			$XMLFinal=$XMLFinal."<Registro>$Medico[3]</Registro>";
			$XMLFinal=$XMLFinal."<Especialidad>$Medico[1]</Especialidad>";
		$XMLFinal=$XMLFinal."</PROFESIONAL>";	
		$XMLFinal=$XMLFinal."<ATENCION>";	
			$XMLFinal=$XMLFinal."<FechaCita>$fechaCita[2]/$fechaCita[1]/$fechaCita[0]</FechaCita>";
			$XMLFinal=$XMLFinal."<FechaAtencion>$FechaAte[2]/$FechaAte[1]/$FechaAte[0]</FechaAtencion>";
			$XMLFinal=$XMLFinal."<CUPS>$Agenda[1]</CUPS>";
			$XMLFinal=$XMLFinal."<Autorizacion>$Servicio[3]</Autorizacion>";
		
		$cons="select codigoxml from historiaclinica.formatosxml where compania='$Compania[0]'";
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			$cons2="select tag from historiaclinica.tagsxml where compania='$Compania[0]' and formato=$fila[0] order by orden";
			$res2=Exquery($cons2);
			while($fila2=ExFetch($res2))
			{
				$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("<$fila2[0]>")."<br>";
				$XMLFinal=$XMLFinal."<$fila2[0]>";
				$cons3="select etiqueta from historiaclinica.etiquetasxformatoxml where compania='$Compania[0]' and formato=$fila[0] and tag='$fila2[0]' 
				order by orden";					
				$res3=ExQuery($cons3);
				while($fila3=Exfetch($res3))
				{
					$XMLFinal2=$XMLFinal2."<dd><dd><dd>".htmlspecialchars("<$fila3[0]>");
					$XMLFinal=$XMLFinal."<$fila3[0]>";
					$cons4="select id_item from historiaclinica.itemsxformatos where compania='$Compania[0]' and formato='$Formato' and tipoformato='$TipoFormato'
					and formatoxml=$fila[0] and tagxml='$fila2[0]' and etiqxml='$fila3[0]'"; 
					$res4=ExQuery($cons4);
					while($fila4=ExFetch($res4))
					{
						$ItemEtiq=substr("00000",0,5-strlen($fila4[0])).$fila4[0];
						$cons5="select CMP$ItemEtiq from histoclinicafrms.".$Tbl[0]." where compania='$Compania[0]' and formato='$Formato' 
						and tipoformato='$TipoFormato'	and id_historia=$IdHistoria";
						$res5=ExQuery($cons5);					
						while($fila5=ExFetch($res5))
						{
							$XMLFinal2=$XMLFinal2.$fila5[0];
                            $XMLFinal=$XMLFinal.$fila5[0];
							//$Valoretiq[$ItemEtiq]=array($fila[0],$fila2[0]);
						}
						
					}
					$cons5="select id,etiquetaxml from  historiaclinica.dxformatos where compania='$Compania[0]' and formato='$Formato' and tipoformato='$TipoFormato'
					and tagxml='$fila2[0]' and etiquetaxml='$fila3[0]'";
					$res5=Exquery($cons5);					
					while($fila5=ExFetch($res5))
					{
						$cons6="select dx".$fila5[0]." from histoclinicafrms.".$Tbl[0]." where compania='$Compania[0]' and formato='$Formato' 
						and tipoformato='$TipoFormato'	and id_historia=$IdHistoria";							
						//echo $cons6."<br>";
						$res6=ExQuery($cons6);
						$fila6=ExFetch($res6);
                        $XMLFinal2=$XMLFinal2.$fila6[0];
						$XMLFinal=$XMLFinal.$fila6[0];
					}
					$XMLFinal2=$XMLFinal2.htmlspecialchars("</$fila3[0]>");
                    $XMLFinal=$XMLFinal."</$fila3[0]>";
				}
				
				$XMLFinal2=$XMLFinal2."<dd><dd>".htmlspecialchars("</$fila2[0]>")."<br>";	
                $XMLFinal=$XMLFinal."</$fila2[0]>";	
			}
		}
		$XMLFinal2=$XMLFinal2."<dd>".htmlspecialchars("</ATENCION>")."<br>";	
        $XMLFinal=$XMLFinal."</ATENCION>";	
	$XMLFinal2=$XMLFinal2.htmlspecialchars("</HC>"); 
    $XMLFinal=$XMLFinal."</HC>"; 
	/*$Fichero = fopen("HC.XML", "w+") or die('Error de apertura');
    fwrite($Fichero, $XMLFinal);
   	fclose($Fichero);
	echo "<br><br><a target='_PARENT' href='HC.XML'><br>Archivo XML<br></a>";  */
	echo $XMLFinal2;
    $client->setUseCurl($useCURL);
    $Matriz=array('strCargaHistoriaClinica'=>$XMLFinal);
    $result = $client->call('CargarHistoriaClinica', $Matriz, '', '', false, true);
    //print_r($soapclient); 
    if ($client->fault)
    {
        echo '<h2>Fault</h2><pre>';
        $ResultCompensar = $ResultCompensar." ".print_r($result,true);
        print_r($result);
        echo '</pre>';
    } 
    else
    {
        $ResultCompensar = $ResultCompensar." ".$client->getError();
        $ResultCompensar = $ResultCompensar." ".print_r($result,true);
        
        $err = $client->getError();
        echo "<br><br><br><br>";
        //print_r($client);
        if ($err)
        {
            echo '<h2>Error</h2><pre>' . $err . '</pre>';
        }
        else
        {
            echo '<h2>Result</h2><pre>';
            print_r($result);
            echo utf8_decode_seguro($result['CargarHistoriaClinicaResult']);
            echo '</pre>';
        }
    }
    
/////////////Se debe Guardar el Resultado en la Base de Datos
$result['CargarHistoriaClinicaResult'] = str_replace("ó","o",$result['CargarHistoriaClinicaResult']);
$result['CargarHistoriaClinicaResult'] = str_replace("Ó","o",$result['CargarHistoriaClinicaResult']);
$result['CargarHistoriaClinicaResult'] = str_replace("Í","i",$result['CargarHistoriaClinicaResult']);
$result['CargarHistoriaClinicaResult'] = str_replace("í","i",$result['CargarHistoriaClinicaResult']);
$cons = "Insert into salud.ResultadoCompensar
(Compania,TipoFormato,Formato,IdHistoria,CedPte,usuarioreporta,fechareporta,xmlreporta,resultadoreporta)
values
('$Compania[0]','$TipoFormato','$Formato',$IdHistoria,'$Paciente[1]','$usuario[0]',
'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$XMLFinal','".$result['CargarHistoriaClinicaResult']."')";
$res = ExQuery($cons);

?>	
<script language="javascript">
    window.close();
</script>