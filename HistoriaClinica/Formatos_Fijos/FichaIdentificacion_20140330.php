<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	
	include("Funciones.php");	
	@require_once ("xajax/xajax_core/xajax.inc.php");  
	//include 'xajax/xajax_core/xajax.inc.php'; 
	function ActualizaMpo($Dpto){
		$respuesta=new xajaxResponse();	
		$respuesta->addScript("alert('Respondo!');");
		return $respuesta->getXML();
	}
	
	
	function ActualizaContrato($EntidadUrg){
		$respuesta=new xajaxResponse();	
		$respuesta->addScript("alert('Respondo!');");
		return $respuesta->getXML();
	}
	
	function ActualizaNoContrato($ContratoUrg){
		$respuesta=new xajaxResponse();	
		$respuesta->addScript("alert('Respondo!');");
		return $respuesta->getXML();
	}
	
	$obj=new xajax();
	$obj->configure('javascript URI','xajax/');//
	$obj->registerFunction("ActualizaMpo");
	$obj->registerFunction("ActualizaContrato");
	$obj->registerFunction("ActualizaNoContrato"); 
	
	
	
	//$obj->processRequests();	

	$ND=getdate();
	if($ND[mon]<10){$cero='0';}else{$cero='';}
	if($ND[mday]<10){$cero1='0';}else{$cero1='';}
	$FechaComp="$ND[year]-$cero$ND[mon]-$cero1$ND[mday]";		
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<? 
	$obj->printJavascript("/xajax");?>
</head>
<body  background="/Imgs/Fondo.jpg" >
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function ValidaDocumento(Objeto)
	{	
		frames.FrameOpener.location.href="/HistoriaClinica/Formatos_Fijos/ValidaDocumento.php?DatNameSID=<? echo $DatNameSID?>&Cedula="+Objeto.value;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='90px';
		document.getElementById('FrameOpener').style.left='65px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='400';
		document.getElementById('FrameOpener').style.height='390';
	}
	function Cerrar()
	{
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='1px';
		document.getElementById('FrameOpener').style.left='1px';
		document.getElementById('FrameOpener').style.width='1';
		document.getElementById('FrameOpener').style.height='1';
		document.getElementById('FrameOpener').style.display='none';
	}

	function Foto()
	{	
		frames.FrameOpener.location.href="/HistoriaClinica/Formatos_Fijos/SubirFoto.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $Paciente[1]?>";
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='90px';
		document.getElementById('FrameOpener').style.left='100px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='266';
		document.getElementById('FrameOpener').style.height='370';
	}
	function ValidaForma()
	{		
		if(document.FORMA.No_Historia.value==1){
			if(document.FORMA.NumHa.value=="")
			{
				alert("El Numero de Historia no puede estar en blanco. Se asigna el documento de identidad automaticamente");
				document.FORMA.NumHa.value=document.FORMA.NumCed.value;
				document.FORMA.PrimApe.focus();			
				return false;
			}
		}
		if(document.FORMA.Estado_Paciente.value==1){
			if(document.FORMA.EstadoPaciente.value=="")
			{
				alert("Ingrese el estado del paciente");
				document.FORMA.EstadoPaciente.focus();			
				return false;
			}
		}
		if(document.FORMA.No_Documento.value==1){
			if(document.FORMA.NumCed.value=="")
			{
				alert("Ingrese la identificaci�n del paciente");
				document.FORMA.NumCed.focus();			
				return false;
			}
		}
		/*if(document.FORMA.Lugar_Expedicion.value==1){
			if(document.FORMA.LugarExp.value=="")
			{
				alert("Ingrese el lugar de expedicion del documento");
				document.FORMA.LugarExp.focus();			
				return false;
			}
		}*/		
		if(document.FORMA.Tipo_de_Sangre.value==1){
			if(document.FORMA.TipoSangre.value=="")
			{
				alert("Seleccione el tipo de sangre del paciente");
				document.FORMA.TipoSangre.focus();			
				return false;
			}
		}		
		if(document.FORMA.Primer_Apellido.value==1){
			if(document.FORMA.PrimApe.value=="")
			{
				alert("Ingrese el primer apellido del paciente");
				document.FORMA.PrimApe.focus();			
				return false;
			}
		}
		if(document.FORMA.Segundo_Apellido.value==1){
			if(document.FORMA.SegApe.value=="")
			{
				alert("Ingrese el segundo apellido del paciente");
				document.FORMA.SegApe.focus();			
				return false;
			}
		}
		if(document.FORMA.Primer_Nombre.value==1){
			if(document.FORMA.PrimNom.value=="")
			{
				alert("Ingrese el primer nombre del paciente");
				document.FORMA.PrimNom.focus();			
				return false;
			}
		}
		if(document.FORMA.Segundo_Nombre.value==1){
			if(document.FORMA.SegNom.value=="")
			{
				alert("Ingrese el segundo nombre del paciente");
				document.FORMA.SegNom.focus();			
				return false;
			}
		}
		if(document.FORMA.Fecha_Nacimiento.value==1){
			if(document.FORMA.AnioNac.value=="")
			{
				alert("Seleccione el año de Nacimiento");
				document.FORMA.AnioNac.focus();
				return false;
			}
		}
		if(document.FORMA.Fecha_Nacimiento.value==1){
			if(document.FORMA.MesNac.value=="")
			{
				alert("Seleccione el mes de Nacimiento");
				document.FORMA.MesNac.focus();
				return false;
			}
		}
		if(document.FORMA.Fecha_Nacimiento.value==1){
			if(document.FORMA.DiaNac.value=="")
			{
				alert("Seleccione el dia de Nacimiento");
				document.FORMA.DiaNac.focus();
				return false;
			}
		}		
		if(document.FORMA.Sexo_.value==1){
			if(document.FORMA.Sexo.value=="")
			{
				alert("Seleccione el sexo del paciente");
				document.FORMA.Sexo.focus();			
				return false;
			}
		}
		if(document.FORMA.Estado_Civil.value==1){
			if(document.FORMA.ECivil.value=="")
			{
				alert("Seleccione el estado civil del paciente");
				document.FORMA.ECivil.focus();			
				return false;
			}
		}
		if(document.FORMA.Ocupacion_.value==1){
			if(document.FORMA.Ocupacion.value=="")
			{
				alert("Digite la ocupación del paciente");
				document.FORMA.Ocupacion.focus();			
				return false;
			}
		}
		if(document.FORMA.Raza_.value==1){
			if(document.FORMA.Raza.value=="")
			{
				alert("Digite la raza del paciente");
				document.FORMA.Raza.focus();			
				return false;
			}
		}
		if(document.FORMA.Escolaridad_.value==1){
			if(document.FORMA.Escolaridad.value=="")
			{
				alert("Digite la escolaridad del paciente");
				document.FORMA.Escolaridad.focus();			
				return false;
			}
		}
		if(document.FORMA.Departamento_.value==1){
			if(document.FORMA.Depto.value=="")
			{
				alert("Seleccione el departamento de residencia del paciente");
				document.FORMA.Depto.focus();			
				return false;
			}
		}
		if(document.FORMA.Municipio_.value==1){
			if(document.FORMA.Mpo.value=="")
			{
				alert("Seleccione el municipio de residencia del paciente");
				document.FORMA.Mpo.focus();			
				return false;
			}
		}
		if(document.FORMA.Vereda.value==1){
			if(document.FORMA.VeredaUsu.value=="")
			{
				alert("Seleccione la vereda de residencia del paciente");
				document.FORMA.VeredaUsu.focus();			
				return false;
			}
		}
		if(document.FORMA.Dir_Residencia.value==1){
			if(document.FORMA.Direccion.value=="")
			{
				alert("Diligencie la dirección del paciente");
				document.FORMA.Direccion.focus();			
				return false;
			}
		}
		if(document.FORMA.Zona_Residencia.value==1){
			if(document.FORMA.Residente.value=="")
			{
				alert("Seleccion la zona de residencia!!!");
				document.FORMA.Residente.focus();			
				return false;
			}
		}
		if(document.FORMA.Procedente_De.value==1){
			if(document.FORMA.ProcedenteDe.value=="")
			{
				alert("Seleccion la procedencia del Paciente!!!");
				document.FORMA.Residente.focus();			
				return false;
			}
		}
		if(document.FORMA.Telefono_.value==1){
			if(document.FORMA.Telefono.value=="")
			{
				alert("Diligencie el teléfono del paciente");
				document.FORMA.Telefono.focus();			
				return false;
			}
		}		
		if(document.FORMA.Celular_.value==1){
			if(document.FORMA.Celular.value=="")
			{
				alert("Digitar el celular del usuario");
				document.FORMA.Celular.focus();			
				return false;
			}
		}
		if(document.FORMA.Asistencia_.value==1){
			if(document.FORMA.Asistencia.value=="")
			{
				alert("Seleccione la asistencia del usuario");
				document.FORMA.Asistencia.focus();			
				return false;
			}
		}
		if(document.FORMA.Institucionalidad_.value==1){
			if(document.FORMA.Institucionalidad.value=="")
			{
				alert("Seleccione si el paciente es institucional o no");
				document.FORMA.Institucionalidad.focus();			
				return false;
			}
		}
		if(document.FORMA.TipoUsuRips_.value==1){
			if(document.FORMA.TipoUsuRips.value=="")
			{
				alert("Seleccione el tipo de usuario");
				document.FORMA.TipoUsuRips.focus();			
				return false;
			}
		}
		if(document.FORMA.docacompanante_.value==1){
			if(document.FORMA.docacompanante.value=="")
			{
				alert("Ingrese el documento del acompañante");
				document.FORMA.docacompanante.focus();			
				return false;
			}
		}
		if(document.FORMA.Tipo_Doc_Ac_.value==1){
			if(document.FORMA.tipodocac.value=="")
			{
				alert("Ingrese el tipo de documento del acompañante");
				document.FORMA.tipodocac.focus();			
				return false;
			}
		}
		if(document.FORMA.expcedacomp_.value==1){
			if(document.FORMA.expcedacomp.value=="")
			{
				alert("Ingrese la expedición del documento del acompañante");
				document.FORMA.expcedacomp.focus();			
				return false;
			}
		}
		if(document.FORMA.Acompanante_.value==1){
			if(document.FORMA.Acompanante.value=="")
			{
				alert("Digite el acompañante!!!");
				document.FORMA.Acompanante.focus();			
				return false;
			}
		}
		if(document.FORMA.diracompanante_.value==1){
			if(document.FORMA.diracompanante.value=="")
			{
				alert("Ingrese la dirección del acompañante");
				document.FORMA.diracompanante.focus();			
				return false;
			}
		}
		if(document.FORMA.telacompanante_.value==1){
			if(document.FORMA.telacompanante.value=="")
			{
				alert("Ingrese el teléfono del acompañante");
				document.FORMA.telacompanante.focus();			
				return false;
			}
		}
		if(document.FORMA.parentescoacomp_.value==1){
			if(document.FORMA.parentescoacomp.value=="")
			{
				alert("Ingrese el parentesco/cargo del acompañante");
				document.FORMA.parentescoacomp.focus();			
				return false;
			}
		}
		if(document.FORMA.InstResponsable_.value==1){
			if(document.FORMA.InstResponsable.value=="")
			{
				alert("Ingrese la Institución Responsable");
				document.FORMA.InstResponsable.focus();			
				return false;
			}
		}
		if(document.FORMA.Entidad_.value==1){
			if(document.FORMA.EPS.value=="")
			{
				alert("Selecione la EPS del paciente");
				document.FORMA.EPS.focus();			
				return false;
			}
		}
		if(document.FORMA.Tipo_Usuario.value==1){
			if(document.FORMA.TipoUsu.value=="")
			{
				alert("Selecione el tipo de afiliacion");
				document.FORMA.TipoUsu.focus();			
				return false;
			}
		}
		if(document.FORMA.Nivel_Usuario.value==1){
			if(document.FORMA.NivelUsu.value=="")
			{
				alert("Selecione el nivel del usuario");
				document.FORMA.NivelUsu.focus();			
				return false;
			}
		}
		if(document.FORMA.No_Carnet.value==1){
			if(document.FORMA.NoCarnet.value=="")
			{
				alert("Digite el numero de carnet del usuario");
				document.FORMA.NoCarnet.focus();			
				return false;
			}
		}
		if(document.FORMA.Entidad_Urg.value==1){
			if(document.FORMA.EntidadUrg.value=="")
			{
				alert("Seleccione la entidad para urgencias");
				document.FORMA.EntidadUrg.focus();			
				return false;
			}
		}
		if(document.FORMA.Contrato_Urg.value==1){
			if(document.FORMA.ContratoUrg.value=="")
			{
				alert("Seleccione el contrato para urgencias");
				document.FORMA.ContratoUrg.focus();			
				return false;
			}
		}
		if(document.FORMA.NoContrato_Urg.value==1){
			if(document.FORMA.NoContratoUrg.value=="")
			{
				alert("Seleccione el Numero de contrato para urgencias");
				document.FORMA.NoContratoUrg.focus();			
				return false;
			}
		}		
	}
	function CalculaEdad(A,M,D,AA,MA,DA)
	{		
		if(A==AA&&M>MA&&D>DA){
			document.FORMA.AnioNac.value=document.FORMA.AuxAnios.value;
			document.FORMA.MesNac.value=document.FORMA.AuxMeses.value;
			document.FORMA.DiaNac.value=document.FORMA.AuxDias.value;
			alert("La fecha de nacimiento no puede ser mayor a la actual!!!");
		}
		else{
			var Edad;
			//if(A==AA&&M>MA&&D>DA){
			if(1==2){							
				//alert("aa:"+AA+" MA:"+MA+" DA:"+DA+" A:"+A" M:"+M+" D:"+D);
				Edad=AA-A;			
				if(MA==M)
				{
					if(DA<D)
					{
						Edad=Edad-1;
					}
				}
				else
				{				
					if(parseInt(MA)<parseInt(M))
					{					
						Edad=Edad-1;
					}
				}
				if(Edad>100){Edad="";}
				if(Edad==-1){Edad="0";}
				var MR;
				
				MR=parseInt(MA)-parseInt(M);
				if(parseInt(MA)>parseInt(M)){
					MR=parseInt(MA)-parseInt(M);
				}			
				else{
					if(MA==M){
						if(DA<D)
						{
							MR=11;
						}																		
					}
					else{
						MR=parseInt(12)-(parseInt(M)-parseInt(MA));
						if(parseInt(MR)<0)MR=parseInt(MR)*-1;
					}
				}
				DR=parseInt(DA)-parseInt(D);
				AA=parseInt(A);
				MA=parseInt(M);
				if(parseInt(D)>parseInt(DA)){
					var UDM
					if(M==1||M==3||M==5||M==7||M==8||M==10||M==12){UDM=31;}
					else{
						if(M==2){
							if(((A%4==0) && (A%100!=0)) || A%400==0){UDM=29;}else{UDM=28;}
						}
						else{
							UDM=30;
						}
					}
					//alert(UDM);		
					
					DR=parseInt(UDM)-(parseInt(D)-parseInt(DA));
				}
				document.FORMA.AuxAnios.value=document.FORMA.AnioNac.value;
				document.FORMA.AuxMeses.value=document.FORMA.MesNac.value;
				document.FORMA.AuxDias.value=document.FORMA.DiaNac.value;
				document.FORMA.Edad.value=Edad;
				document.FORMA.EdadMeses.value=MR;
				document.FORMA.EdadDias.value=DR;
			}
		}
	}
	
	
	function VerAfiliados()
	{				
		if(document.FORMA.NumCed.value==""){
			alert("Debe digitar el numero de identifiacion!!!");
		}
		else{
			
			frames.FrameOpener.location.href="/HistoriaClinica/Formatos_Fijos/VerifcaAfilacion.php?DatNameSID=<? echo $DatNameSID?>&Identifiacion="+document.FORMA.NumCed.value;
			document.getElementById('FrameOpener').style.position='absolute';
			document.getElementById('FrameOpener').style.top='90px';
			document.getElementById('FrameOpener').style.left='65px';
			document.getElementById('FrameOpener').style.display='';
			document.getElementById('FrameOpener').style.width='440';
			document.getElementById('FrameOpener').style.height='115';
		}
	}
	function Guardar()
	{
		//alert();
		Result=ValidaForma();
		if(Result!=false){
			document.FORMA.BotonG.value=1;
			//document.getElementById('FrameFondo').style.position='absolute';
			//document.getElementById('FrameFondo').style.top='1px';
			//document.getElementById('FrameFondo').style.left='1px';
			//document.getElementById('FrameFondo').style.display='';
			//document.getElementById('FrameFondo').style.width='100%';
			//document.getElementById('FrameFondo').style.height='100%';
			
			//frames.FrameOpener.location.href="/HistoriaClinica/Formatos_Fijos/VerificaPyP.php?DatNameSID=<? echo $DatNameSID?>&Entidad="+document.FORMA.EPS.value+"&Edad="+document.FORMA.Edad.value+"&Sexo="+document.FORMA.Sexo.value;
			//document.getElementById('FrameOpener').style.position='absolute';
			//document.getElementById('FrameOpener').style.top='20%';
			//document.getElementById('FrameOpener').style.left='30%';
			//document.getElementById('FrameOpener').style.display='';
			//document.getElementById('FrameOpener').style.width='400';
			//document.getElementById('FrameOpener').style.height='200';		
			
			document.FORMA.submit();
		}				
	}
	function SalaUrgencias()
	{
		frames.FrameOpener.location.href="/HistoriaClinica/Formatos_Fijos/ActivarCitaPREURG.php?DatNameSID=<? echo $DatNameSID?>";
			document.getElementById('FrameOpener').style.position='absolute';
			document.getElementById('FrameOpener').style.top='50%';
			document.getElementById('FrameOpener').style.left='25%';
			document.getElementById('FrameOpener').style.display='';
			document.getElementById('FrameOpener').style.width='300';
			document.getElementById('FrameOpener').style.height='150';	
	}
	function MostrarFormatoAcu(FormatoAcu,TipoFormatoAcu,TablaAcu)
	{
		if(FormatoAcu!=''&&TipoFormatoAcu!='')
		{			
			frames.FrameAcudientes.location.href="/HistoriaClinica/Datos.php?DatNameSID=<? echo $DatNameSID?>&Frame=1&TipoFormato="+TipoFormatoAcu+"&Formato="+FormatoAcu;
			document.getElementById('FrameAcudientes').style.position='absolute';
			document.getElementById('FrameAcudientes').style.top='2%';
			document.getElementById('FrameAcudientes').style.left='0';
			document.getElementById('FrameAcudientes').style.display='';
			document.getElementById('FrameAcudientes').style.width='100%';
			document.getElementById('FrameAcudientes').style.height='95%';
		}
		else
		{
			alert("No se ha configurado un Formato unico para Acudientes!!!\nPor favor consulte con su coordinador medico"); 
		}	
	}
	
	
	function enfocarFormulario() {
		document.getElementById('EntidadUrg').focus();	
	
	}
</script>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript" src="/salud/funciones.js"></script>
<center>

<table rules="groups" cellpadding="2" cellspacing="0"  border="1" style='font : normal normal small-caps 13px Tahoma;'>


<?php

	//verificar si el paciente ya tiene datos en Historia Clinica
	//Fin..		
$ND=getdate();
	if($BotonG)
	{
		if($Auto==1)
		{
			$cons="Update NoActualHa set Numero=$AutoNoHaC";
			echo $cons;
			$resultado=ExQuery($cons);
		}
		$cons = "SELECT * FROM Central.Terceros Where Identificacion='$NumCed' and compania='$Compania[0]'";
		//echo $cons;
		$resultado = ExQuery($cons,$conex);
		if(ExNumRows($resultado)==0)
		{
			if($DiaNac){$FN=",FecNac";$FN2=",'$AnioNac-$MesNac-$DiaNac'";}
			$cons="INSERT INTO Central.Terceros(Tipo,Identificacion,TipoDoc,LugarExp,NumHa,TipoSangre,PrimApe,SegApe,PrimNom,SegNom,Telefono,Departamento,Municipio,Sexo,ECivil,EPS,TipoUsu,NivelUsu,
			UsuarioCreador,FechaCreacion,Direccion,NaturalDe,ZonaRes,Escolaridad,Religion,Ocupacion,ViveCon,NoCarnet,Triage,Compania,vereda,ultactuliza,
			acompanante,celular,estadopaciente,procedentede,asistenciapaciente,institucionalidad $FN ,entidadurg, contratourg,nocontratourg,tiposusurips,comedor,
			docacompanante,expcedacomp,diracompanante,telacompanante,parentescoacomp,instresponsable,raza,tipodocac)
					Values('Paciente','$NumCed','$TipoDoc','$LugarExp','$NumHa','$TipoSangre','$PrimApe','$SegApe','$PrimNom','$SegNom','$Telefono','$Depto',
					'$Mpo','$Sexo','$ECivil','$EPS','$TipoUsu','$NivelUsu','$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',
					'$Direccion','$Natu','$Residente','$Escolaridad','$Religion','$Ocupacion','$ViveCon','$NoCarnet','$Triage','$Compania[0]','$VeredaUsu',
					'$ND[year]-$ND[mon]-$ND[mday]','$Acompanante','$Celular','$EstadoPaciente','$ProcedenteDe','$Asistencia','$Institucionalidad' $FN2, 
					'$EntidadUrg', '$ContratoUrg', '$NoContratoUrg','$TipoUsuRips','$Comedor', '$docacompanante', '$expcedacomp', '$diracompanante',
					'$telacompanante', '$parentescoacomp', '$InstResponsable', '$Raza', '$tipodocac')";
				$resultado = ExQuery($cons,$conex);
		}
		elseif(ExNumRows($resultado)==1)
		{
			$cons="Update Central.Terceros Set ";
			$cons=$cons . "NumHa='$NumHa', ";
			$cons=$cons . "PrimApe='$PrimApe', ";
			$cons=$cons . "SegApe='$SegApe', ";
			$cons=$cons . "PrimNom='$PrimNom',";
			$cons=$cons . "SegNom='$SegNom',";
			$cons=$cons . "Direccion='$Direccion',";
			$cons=$cons . "Telefono='$Telefono',";
			$cons=$cons . "TipoDoc='$TipoDoc',";
//			$cons=$cons . "LugarExp='$LugarExp',";
			$cons=$cons . "TipoSangre='$TipoSangre',";
			if($DiaNac){$cons=$cons . "FecNac='$AnioNac-$MesNac-$DiaNac',";}
			$cons=$cons . "Departamento='$Depto',";
			$cons=$cons . "Municipio='$Mpo',";
			$cons=$cons . "Sexo='$Sexo',";
			$cons=$cons . "ECivil='$ECivil',";
			$cons=$cons . "UsuarioMod='$usuario[0]',";
			$cons=$cons ."NaturalDe='$Natu',";
			$cons=$cons . "ZonaRes='$Residente',";
			$cons=$cons . "Escolaridad='$Escolaridad',";
			$cons=$cons . "ViveCon='$ViveCon',";
			$cons=$cons . "Religion='$Religion',";
			$cons=$cons . "Ocupacion='$Ocupacion',";
			$cons=$cons . "EPS='$EPS',";
			$cons=$cons . "TipoUsu='$TipoUsu',";
			$cons=$cons . "NivelUsu='$NivelUsu',";
			$cons=$cons . "NoCarnet='$NoCarnet',";
			$cons=$cons . "Tipo='Paciente',";
			$cons=$cons . "Triage='$Triage',";
			$cons=$cons . "vereda='$VeredaUsu',";
			$cons=$cons . "ultactuliza='$ND[year]-$ND[mon]-$ND[mday]',";  
			$cons=$cons . "acompanante='$Acompanante',";
			$cons=$cons . "celular='$Celular',";
			$cons=$cons . "estadopaciente='$EstadoPaciente',";
			$cons=$cons . "procedentede='$ProcedenteDe',";
			$cons=$cons . "asistenciapaciente='$Asistencia',";
			$cons=$cons . "institucionalidad='$Institucionalidad',";
			$cons=$cons . "entidadurg='$EntidadUrg',";
			$cons=$cons . "contratourg='$ContratoUrg',";
			$cons=$cons . "nocontratourg='$NoContratoUrg',";
			$cons=$cons . "tiposusurips='$TipoUsuRips',";
			$cons=$cons . "expcedacomp='$expcedacomp',";
			$cons=$cons . "diracompanante='$diracompanante',";
			$cons=$cons . "telacompanante='$telacompanante',";
			$cons=$cons . "parentescoacomp='$parentescoacomp',";
			$cons=$cons . "docacompanante='$docacompanante',";
            $cons=$cons . "InstResponsable='$InstResponsable',";
            $cons=$cons . "Raza='$Raza',";
            $cons=$cons . "tipodocac='$tipodocac'";
			
			//$cons=$cons . "comedor='$Comedor'";
			$cons=$cons . " Where Identificacion='$NumCed' and Compania='$Compania[0]'";
			//echo $cons;
			$resultado = ExQuery($cons,$conex);

		}

		$cons9="Select * from Central.Terceros where Identificacion='$NumCed' and compania='$Compania[0]'";		
		$res9=ExQuery($cons9);echo ExError();
		$fila9=ExFetch($res9);

		$Paciente[1]=$fila9[0];
		$n=1;
		for($i=1;$i<=ExNumFields($res9);$i++)
		{
			$n++;
			$Paciente[$n]=$fila9[$i]; //echo "$n - > $Paciente[$n]<br>";
		}
		//--Urgencias
		
		//--
		?>
        <script language="javascript">
        parent(1).location.href=parent(1).location.href;
		</script>
        <?

	}
	else{
		if($Pacie){$Paciente[1]=$Pacie;}
		if($Paciente[1]!=''){
			$cons9="Select * from Central.Terceros where Identificacion='$Paciente[1]' and compania='$Compania[0]'";
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
			//echo $Paciente[47];
			session_register("Paciente");
		}
	}
	//--Acudientes
	$cons="SELECT formato, tipoformato, tblformat, acudientes FROM historiaclinica.formatos where Compania='$Compania[0]' and Acudientes='Si'";
	$res=ExQuery($cons);
	if(ExNumRows($res)>0)
	{
		$fila=ExFetch($res);
		$FormatoAcu=$fila[0];$TipoFormatoAcu=$fila[1];$TablaAcu=$fila[2];
	}
	//------
	echo "<form name='FORMA' onSubmit='return ValidaForma()'>";
	$cons="select campo,obligatorio from historiaclinica.fichaid where compania='$Compania[0]'";
	$res=ExQuery($cons); echo ExError();
	while($fila=ExFetch($res))
	{
		$Aux[$fila[0]]=$fila[1];
		//echo " $fila[0] ";
		?><input type="hidden" name="<? echo trim($fila[0])?>" value="<? echo $fila[1]?>" id="<? echo $fila[0]?>"><?
	}	
	$buscnum="Select * From HistoriaClinica.NoActualHa";
	//echo $buscnum;
	$resultbusc=ExQuery($buscnum);echo ExError();
	$NoHaClinica=ExFetch($resultbusc);
	$NoHaClinica=$NoHaClinica[0]+1;
	echo "<input type='Hidden' name='AutoNoHaC' value='$NoHaClinica'>";
	if(!$Auto){$Auto=0;}
	if($Paciente[23]){
		$Edad=ObtenEdad($Paciente[23]);
		$EdadMeses=ObtenMesesEnEdad($Paciente[23]);
		$EdadDias=ObtenDiasEnEdad($Paciente[23]);
	}
?>
<br>
<style>
	input.Texto
	{
		background:transparent;
		width:140px;border:1px solid;
	}
</style>
<input type="Hidden" name="Auto" value=<? echo $Auto ?>>

<tbody>
    <tr>
        <td colspan=4 bgcolor="<? echo $Estilo[1]?>" style="color:white; background-color: #7a98ba;"><center><strong>INFORMACIÓN BÁSICA DEL PACIENTE</td></tr>
</tbody>
<tbody>
    <tr>
        <td>No. Documento <? if($Aux['No_Documento']==1){?><font color="red">*</font><? }?></td>
        <td>
            <input style="width:180px;" type="Text" class="Texto" name="NumCed" value="<?php if($Paciente[1]){echo $Paciente[1];} else{echo $_GET['cedula'];}?>" <? if(!$Paciente[21]){?>onBlur="NumHa.value=this.value" onKeyUp="document.FORMA.NumHa.value=document.FORMA.NumCed.value" <? }else{?> readonly<? }?>
            <?	if($Paciente[1]){?> readonly<? } ?>>
        </td>
        <td><input type="button" value="Asignar HC Automatico"  disabled style="width:180px;" onclick=document.FORMA.NumHa.value=document.FORMA.AutoNoHaC.value;document.FORMA.Auto.value=1></td>
        <td></td>
    </tr>
    <tr>
        <td>Estado <? if($Aux['Estado_Paciente']==1){?><font color="red">*</font><? }?></td>
        <td>
            <select name="EstadoPaciente" style="width:180px;" onFocus="Cerrar();">
                <option value="Vivo" <? if($Paciente[52]=="Vivo"||empty($Paciente[52])){echo "Selected";}?> >Vivo</option>
                <option value="Muerto" <? if($Paciente[52]=="Muerto"){echo "Selected";}?> >Muerto</option>
            </select>
        </td>
        <td>Tipo de sangre <?if($Aux['Tipo_de_Sangre']==1){?><font color="red">*</font><?}?></td><td>
            <select name="TipoSangre" style="width:180px;" onFocus="Cerrar()">
                <option></option>
<?php
		$cons = "SELECT TipoSangre FROM Central.TiposSangre";
		$resultado = ExQuery($cons,$conex);
		while ($fila = ExFetch($resultado))
		{
			if($Paciente[22]==$fila[0])
			{
				echo "<option value='$fila[0]' selected>$fila[0]</option>";
			}
			else
			{
				echo "<option value='$fila[0]'>$fila[0]</option>";
			}
		}?>
            </select>
        </td>
    </tr>
<tr>
<td>Tipo Documento <? if($Aux['Tipo_Documento']==1){?><font color="red">*</font><? }?></td><td>
<select name="TipoDoc" style="width:180px;"  onFocus="Cerrar()">

<?php
	
		$cons = "SELECT TipoDoc FROM Central.TiposDocumentos";
		$resultado = ExQuery($cons,$conex);echo ExError();
		while ($fila = ExFetch($resultado))
		{
			if(!$Paciente[19]){$Paciente[19]="Cedula de ciudadania";}
			if($Paciente[19]==$fila[0])
			{
				echo "<option value='$fila[0]' selected>$fila[0]</option>";
			}
			else
			{
				echo "<option value='$fila[0]'>$fila[0]</option>";
			}
		}?>
</select>
</td>
<td>No. Historia <? if($Aux['No_Historia']==1){?><font color="red">*</font><? }?></td>
<td>
   	<input style="width:180px;" type="Text" name="NumHa" class="Texto" value="<?php if($Paciente[21]){echo $Paciente[21];} else{echo $_GET['cedula'];}?>">
</td>
</tr>
<tr>
<!--
	<td>Lugar expedicion <?if($Aux['Lugar_Expedicion']==1){?><font color="red">*</font><?}?></td>
	<td><input type="Text" class="Texto" name="LugarExp" value=<?echo $Paciente[20]?>></td>-->
<TR>
	<td>Primer apellido <? if($Aux['Primer_Apellido']==1){?><font color="red">*</font><? }?></td><td><input style="width:180px;" type="Text" class="Texto" name="PrimApe" value=<?php echo "'$Paciente[2]'" ?> onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"  onFocus="Cerrar()"></td>
	<td>Segundo apellido <? if($Aux['Segundo_Apellido']==1){?><font color="red">*</font><? }?></td><td><input type="Text" style="width:180px;" class="Texto" name="SegApe" value=<?php echo "'$Paciente[3]'" ?> onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"  onFocus="Cerrar()"></td></tr>
	<tr>
    <td>Primer Nombre <? if($Aux['Primer_Nombre']==1){?><font color="red">*</font><? }?></td><td><input type="Text" style="width:180px;" class="Texto" name="PrimNom" value=<?php echo "'$Paciente[4]'" ?> onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"  onFocus="Cerrar()"></td>
	<td>Segundo Nombre <? if($Aux['Segundo_Nombre']==1){?><font color="red">*</font><? }?></td><td><input type="Text" style="width:180px;" class="Texto" name="SegNom" value=<?php echo "'$Paciente[5]'" ?> onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"  onFocus="Cerrar()"></td></tr>
	<td>Fecha nacimiento <br> (aaaa-mm-dd)<? if($Aux['Fecha_Nacimiento']==1){?><font color="red">*</font><? }?></td><td>
<?
		$cons99="Select Edad from Salud.EdadMinima where Compania='$Compania[0]'";
		$res99=ExQuery($cons99);
		$fila99=ExFetch($res99);

?>
	<select name="AnioNac" onChange="CalculaEdad(FORMA.AnioNac.value,FORMA.MesNac.value,FORMA.DiaNac.value,'<? echo $ND[year]?>','<? echo $ND[mon]?>','<? echo $ND[mday]?>')">
    	<option></option>
    <?
		$EdadMinima=$fila99[0];
		
    	for($i=$ND[year]-$EdadMinima;$i>=($ND[year]-100);$i--)
		{
			if(substr($Paciente[23],0,4)==$i){echo "<option selected value='$i'>$i</option>";}
			else{echo "<option value='$i'>$i</option>";}
		}
	?>
    </select>-
    <select name="MesNac" onChange="CalculaEdad(FORMA.AnioNac.value,FORMA.MesNac.value,FORMA.DiaNac.value,'<? echo $ND[year]?>','<? echo $ND[mon]?>','<? echo $ND[mday]?>')">
    	<option></option>
    <?	
    	for($i=1;$i<=12;$i++)
		{
			if(substr($Paciente[23],5,2)==$i){echo "<option selected value=$i>".$NombreMesC[$i]."</option>";}
			else{echo "<option value=$i>".$NombreMesC[$i]."</option>";}
		}
	?>
    </select>-
    <select name="DiaNac" onChange="CalculaEdad(FORMA.AnioNac.value,FORMA.MesNac.value,this.value,'<? echo $ND[year]?>','<? echo $ND[mon]?>','<? echo $ND[mday]?>')"><option></option>
    <?
    	for($i=1;$i<=31;$i++)
		{
			if(substr($Paciente[23],8,2)==$i){echo "<option selected value='$i'>$i</option>";}
			else{echo "<option value='$i'>$i</option>";}
		}
	?>
    </select>    
    <input type="hidden" name="AuxAnios">    
    <input type="hidden" name="AuxMeses" value="document.FORMA.MesNac.value">
    <input type="hidden" name="AuxDias" value="document.FORMA.DiaNac.value">
    <script language="javascript">
		document.FORMA.AuxAnios.value=document.FORMA.AnioNac.value;
		document.FORMA.AuxMeses.value=document.FORMA.MesNac.value;
		document.FORMA.AuxDias.value=document.FORMA.DiaNac.value;
	</script>
	</td><td>Edad</td>
    <td><input type="Text" name="Edad" readonly style="border:0px;width:15px;" value="<? echo $Edad?>"> a&ntilde;os
    	<input type="Text" name="EdadMeses" readonly style="border:0px;width:15px;" value="<? echo $EdadMeses?>"> meses
        <input type="Text" name="EdadDias" readonly style="border:0px;width:15px;" value="<? echo $EdadDias?>"> días
    </td>
<tr>
	<td>Sexo <? if($Aux['Sexo_']==1){?><font color="red">*</font><? }?></td>
    <td>

        <select name="Sexo" style="width:180px;">
        <option value=""></option>
        <?php
                $cons = "SELECT * FROM Central.ListaSexo Order By Sexo Desc";
                $resultado = ExQuery($cons,$conex);
                while ($fila = ExFetch($resultado))
                {
                    if($Paciente[24]==$fila[1])
                    {
                        echo "<option value='$fila[1]' selected>$fila[0]</option>";
                    }
                    else
                    {
                        echo "<option value='$fila[1]'>$fila[0]</option>";
                    }
                }?>
        </select>	</td>

	<td>Estado civil <? if($Aux['Estado_Civil']==1){?><font color="red">*</font><? }?></td><td>

        <select name="ECivil" style="width:180px;">
        <option value=""></option>
        <?php
                $cons = "SELECT * FROM Central.EstadosCiviles";
                $resultado = ExQuery($cons,$conex);
                while ($fila = ExFetch($resultado))
                {
                    if($Paciente[25]==$fila[0])
                    {
                        echo "<option value='$fila[0]' selected>$fila[0]</option>";
                    }
                    else
                    {
                        echo "<option value='$fila[0]'>$fila[0]</option>";
                    }
                }?>
        </select>
        
        
        
	</td>
 </tr>
<?php /*
<tbody>
<tr>
  <td>vive con <? if($Aux['Vive_Con']==1){?><font color="red">*</font><? }?></td>
  <td><input name="ViveCon" type="text" class="Texto" value="<? echo "$Paciente[36]" ?>" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
  <td>escolaridad <? if($Aux['Escolaridad_']==1){?><font color="red">*</font><? }?></td>
  <td><input name="Escolaridad" type="text" class="Texto" value="<? echo "$Paciente[33]" ?>" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
</tr>
<tr>
  <td>ocupacion <? if($Aux['Ocupacion_']==1){?><font color="red">*</font><? }?></td>
  <td><input name="Ocupacion" type="text" class="Texto" value="<? echo "$Paciente[35]" ?>" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
  <td>religion c <? if($Aux['Religion_']==1){?><font color="red">*</font><? }?></td>
  <td><input name="Religion" type="text" class="Texto" value="<? echo "$Paciente[34]" ?>" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
</tr>
<tbody>*/?>
<tr>
	<td>Ocupación <? if($Aux['Ocupacion_']==1){?><font color="red">*</font><? }?></td>
	<td>
            <select name="Ocupacion" style="width:180px;" onFocus="Cerrar()"><option></option>
            <?php
		$cons = "SELECT empleo FROM historiaclinica.empleosOIT
		order by id asc";
		$resultado = ExQuery($cons,$conex);
		while ($fila = ExFetch($resultado))
		{
			if($Paciente[35]==$fila[0])
			{
				echo "<option value='$fila[0]' selected>$fila[0]</option>";
			}
			else
			{
				echo "<option value='$fila[0]'>$fila[0]</option>";
			}
		}?>
            </select>
        </td>
        <td>Raza <? if($Aux['Raza_']==1){?><font color="red">*</font><? }?></td>
	<td>
            <select name="Raza" style="width:180px;" onFocus="Cerrar()"><option></option>
            <?php
		$cons = "SELECT nombre FROM historiaclinica.raza order by id asc";
		$resultado = ExQuery($cons,$conex);
		while ($fila = ExFetch($resultado))
		{
			if($Paciente[71]==$fila[0])
			{
				echo "<option value='$fila[0]' selected>$fila[0]</option>";
			}
			else
			{
				echo "<option value='$fila[0]'>$fila[0]</option>";
			}
		}?>
            </select>
        </td>
</tr>
	<tr>
	<td>Escolaridad <? if($Aux['Escolaridad_']==1){?><font color="red">*</font><? }?></td>
	<td colspan="5">
<select name="Escolaridad" style="width:180px;" onFocus="Cerrar()"><option></option>
<?php
		$cons = "SELECT escolaridad FROM historiaclinica.escolaridad
		order by escolaridad asc";
		$resultado = ExQuery($cons,$conex);
		while ($fila = ExFetch($resultado))
		{
			if($Paciente[33]==$fila[0])
			{
				echo "<option value='$fila[0]' selected>$fila[0]</option>";
			}
			else
			{
				echo "<option value='$fila[0]'>$fila[0]</option>";
			}
		}?>
</select>
</td>
	
	
	
</tr>
<?php /*  
<tr>
  <td>natural de <? if($Aux['Natural_de']==1){?><font color="red">*</font><? }?></td>
  <td><input name="Natu" type="text"class="Texto" value="<? echo "$Paciente[31]"?>" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
  <td>Zona Residencia <? if($Aux['Zona_Residencia']==1){?><font color="red">*</font><? }?></td>
  <td><select name="Residente" style="width:152px;"><option>
<?  
                $cons = "SELECT * FROM Central.ZonasResidencia";
                $resultado = ExQuery($cons,$conex);
                while ($fila = ExFetch($resultado))
                {
                    if($Paciente[32]==$fila[0])
                    {
                        echo "<option value='$fila[0]' selected>$fila[0]</option>";
                    }
                    else
                    {
                        echo "<option value='$fila[0]'>$fila[0]</option>";
                    }
                }?>
  
  </select>
  </td>
</tr>*/?>


<td>Departamento <? if($Aux['Departamento_']==1){?><font color="red">*</font><? }?></td><td>

<select name="Depto" style="width:180px;" onChange="xajax_ActualizaMpo(this.value);BuscaMpo.location.href='BuscarMunicipios.php?Depto='+this.value+'&DatNameSID=<? echo $DatNameSID?>'">
<option value=""></option>
<?php
		$cons = "SELECT * FROM Central.Departamentos Order By Departamento";
		$resultado = ExQuery($cons,$conex);
		while ($fila = ExFetch($resultado))
		{
			if($Paciente[10]==$fila[0])
			{
				echo "<option value='$fila[0]' selected>$fila[0]</option>";
			}
			else
			{
				echo "<option value='$fila[0]'>$fila[0]</option>";
			}
		}?>
</select>
<?
 
?>


</td>
<td>Municipio <? if($Aux['Municipio_']==1){?><font color="red">*</font><? }?></td><td>

<select name="Mpo" style="width:180px;" onChange="BuscaVereda.location.href='BuscarVereda.php?Depto='+Depto.value+'&Mpo='+this.value+'&DatNameSID=<? echo $DatNameSID?>'">
<option value=""></option>
<?php echo $Paciente[10];
	$cons="Select municipio from Central.Municipios,Central.Departamentos where Departamentos.Departamento='$Paciente[10]'
	 and Departamentos.Codigo=Municipios.Departamento order by codmpo";
		$resultado = ExQuery($cons);
		while ($fila = ExFetch($resultado))
		{
			if($Paciente[11]==$fila[0])
			{
				echo "<option value='$fila[0]' selected>$fila[0]</option>";
			}
			else
			{
				echo "<option value='$fila[0]'>$fila[0]</option>";
			}
		}?>
</select>

<? 
if(!$Depto){$Depto=$Paciente[10];}
if(!$Mpo){$Mpo=$Paciente[11];}?>
</td></tr>
<tr>
<?	$cons="select departamento,codigo from central.departamentos";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$Dptos[$fila[0]]=$fila[1];
	}?>
	<td>Vereda <? if($Aux['Vereda']==1){?><font color="red">*</font><? }?></td>
	<td><input style="width:180px;" class="Texto" type="Text" name="VeredaUsu" value=<?php echo "'$Paciente[47]'" ?> onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
   <!-- <td>
    <? 	/* $cons="select vereda from central.veredas,central.municipios,central.departamentos
		where veredas.departamento=departamentos.codigo and departamentos.departamento='$Paciente[10]' and codmpo=veredas.municipio 
		and municipios.municipio='".$Paciente[11]."'";   		
		$resultado = ExQuery($cons); */?>
    	<select name="VeredaUsu" style="width:152px;">
        <option value=""></option>        
  	<?	
		/* while ($fila = ExFetch($resultado))
		{
			if($Paciente[47]==$fila[0])
			{
				echo "<option value='$fila[0]' selected>$fila[0]</option>";
			}
			else
			{
				echo "<option value='$fila[0]'>$fila[0]</option>";
			}
		} */ ?>
        </select>  
        <? //echo $cons;?>     
    </td> -->
	<td>Dir. residencia <? if($Aux['Dir_Residencia']==1){?><font color="red">*</font><? }?></td><td><input class="Texto" type="Text" name="Direccion" value=<?php echo "'$Paciente[7]'" ?> onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"  style="width:180px;"></td>
</tr>
<tr>
<td>Zona Residencia <? if($Aux['Zona_Residencia']==1){?><font color="red">*</font><? }?></td>
  <td><select name="Residente" style="width:180px;"  onFocus="Cerrar()"><option>
	<?  
        $cons = "SELECT * FROM Central.ZonasResidencia";
        $resultado = ExQuery($cons,$conex);
        while ($fila = ExFetch($resultado))
        {
            if($Paciente[32]==$fila[0])
            {
                echo "<option value='$fila[0]' selected>$fila[0]</option>";
            }
            else
            {
                echo "<option value='$fila[0]'>$fila[0]</option>";
            }
        }?>
  
  </select>
  </td>
<td>Procedente de <? if($Aux['Procedente_De']==1){?><font color="red">*</font><? }?></td>
<td><input style="width:180px;" type="Text" class="Texto" name="ProcedenteDe" value=<?php echo "'$Paciente[53]'" ?> onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
</tr>
<tr>
<td>Teléfono <? if($Aux['Telefono_']==1){?><font color="red">*</font><? }?></td>
<td><input type="Text" style="width:180px;" class="Texto" name="Telefono" value=<?php echo "'$Paciente[8]'" ?> onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
</td>

<td>Celular <? if($Aux['Celular_']==1){?><font color="red">*</font><? }?></td>
<td><input type="Text" style="width:180px;" class="Texto" name="Celular" value=<?php echo "'$Paciente[50]'" ?> onKeyUp="xNumero(this)" onKeyDown="xNumero(this)"></td>
</tr>
<tr>
<td>Asistencia <? if($Aux['Asistencia_']==1){?><font color="red">*</font><? }?></td>
<td><select style="width:180px;" name="Asistencia">
	<option></option>
    <option value="Primera Vez" <? if($Paciente[54]=="Primera Vez"){echo "selected";}?>>Primera Vez</option>
    <option value="Reingreso" <? if($Paciente[54]=="Reingreso"){echo "selected";}?>>Reingreso</option>
     <!--<option value="Recaida" <? /*if($Paciente[54]=="Recaida"){echo "selected";}*/?>>Recaida</option>-->
    </select>
</td>
</td>

<td>Institucionalizar <? if($Aux['Institucionalidad_']==1){?><font color="red">*</font><? }?></td>
<td><select style="width:180px;" name="Institucionalidad">
	<option></option>
    <option value="Si" <? if($Paciente[55]=="Si"){echo "selected";}?>>Si</option>
    <option value="No" <? if($Paciente[55]=="No"){echo "selected";}?>>No</option>
    </select>
 </td>
</tr>
<!--<tr>
	<td>Tipo de Usuario <? if($Aux['TipoUsuRips_']==1){?><font color="red">*</font><? }?></td>
	<td colspan="3">
    <?	if(!$TipoUsuRips){$TipoUsuRips=$Paciente[63]; }
		$cons="select tipusuxrips,codtipousuxrips from salud.tipousuarioxrips order by codtipousuxrips";
		$res=ExQuery($cons);?>
    	<select name="TipoUsuRips">
        	<option></option>
		<?	while($fila=ExFetch($res))
			{?>            
            	<option value="<? echo $fila[1]?>" <? if($fila[1]==$TipoUsuRips){echo "selected";}?>><? echo $fila[0]?></option>
		<?	}?>
        </select>
    </td>
</tr>-->
<tr>
	<!--<td>Comedor <? //if($Aux['Comedor_']==1){?><!--<font color="red">*</font><? //}?><!--</td>
	<td colspan="3">-->
    <?	/*if(!$Comedor){$TipoUsuRips=$Paciente[64]; }
		$cons="select comedor from central.terceros where identificacion='$Paciente[1]' and compania='$Compania[0]'";
		$res=ExQuery($cons);
		$fila=ExFetch($res); 
		if(!$Comedor){$Comedor=$fila[0];}
		$cons="select pabellon from salud.pabellones where compania='$Compania[0]'";
		$res=ExQuery($cons);*/?>
    	<!--<select name="Comedor">
        	<option></option>
		<?	/*while($fila=ExFetch($res))
			{*/?>            
            	<!--<option value="<? //echo $fila[0]?>" <? //if($fila[0]==$Comedor){echo "selected";}?>><? //echo $fila[0]?><!--</option>
		<?	//}?>
        </select>
    </td>-->
</tr>

<tbody>
<tr><td colspan=4 bgcolor="<? echo $Estilo[1]?>" style="color:white; background-color: #7a98ba;" ><center><strong>INFORMACIÓN DEL ACUDIENTE<br>(Persona responsable del ingreso y del egreso del paciente)</br></td></tr>

<tr>
	<td>No. Documento<? if($Aux['docacompanante_']==1){?><font color="red">*</font><? }?></td>
	<td><input style="width:180px;" type="Text" class="Texto" name="docacompanante" size="500" value=<?php echo "'$Paciente[65]'" ?> onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
        <td>Tipo Documento <? if($Aux['Tipo_Doc_Ac_']==1){?><font color="red">*</font><? }?></td><td>
            <select name="tipodocac" style="width:180px;"  onFocus="Cerrar()">
            <?php
                            $cons = "SELECT TipoDoc FROM Central.TiposDocumentos";
                            $resultado = ExQuery($cons,$conex);echo ExError();
                            while ($fila = ExFetch($resultado))
                            {
                                    if(!$Paciente[74]){
                                        $Paciente[74]="Cedula de ciudadania";
                                    }
                                    if($Paciente[74]==$fila[0])
                                    {
                                            echo "<option value='$fila[0]' selected>$fila[0]</option>";
                                    }
                                    else
                                    {
                                            echo "<option value='$fila[0]'>$fila[0]</option>";
                                    }
                            }?>
            </select>
        </td>
</tr>
<tr>
        <td>Lugar Expedición<? if($Aux['expcedacomp_']==1){?><font color="red">*</font><? }?></td>
	<td><input style="width:180px;" type="Text" class="Texto" name="expcedacomp" size="500" value=<?php echo "'$Paciente[66]'" ?> onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
	<td>Nombre Completo<? if($Aux['Acompanante_']==1){?><font color="red">*</font><? }?></td>
	<td><input style="width:180px;" type="Text" class="Texto" name="Acompanante" size="500" value=<?php echo "'$Paciente[49]'" ?> onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
</tr>
<tr>
        <td>Dirección Residencia<? if($Aux['diracompanante_']==1){?><font color="red">*</font><? }?></td>
	<td><input style="width:180px;" type="Text" class="Texto" name="diracompanante" size="500" value=<?php echo "'$Paciente[67]'" ?> onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
	<td>Teléfonos<? if($Aux['telacompanante_']==1){?><font color="red">*</font><? }?></td>
	<td><input style="width:180px;" type="Text" class="Texto" name="telacompanante" size="500" value=<?php echo "'$Paciente[68]'" ?> onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>	
</tr>
<tr>
        <td>Parentesco o cargo<? if($Aux['parentescoacomp_']==1){?><font color="red">*</font><? }?></td>
	<td><input style="width:180px;" type="Text" class="Texto" name="parentescoacomp" size="500" value=<?php echo "'$Paciente[69]'" ?> onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
        <td>Institución Responsable <? if($Aux['InstResponsable_']==1){?><font color="red">*</font><? }?></td>
	<td><input style="width:180px;" class="Texto" type="Text" name="InstResponsable" value=<?php echo "'$Paciente[70]'" ?> onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
</tr>
</tbody>


<tbody>
<tr><td colspan=4 bgcolor="<? echo $Estilo[1]?>" style="color:white; background-color: #7a98ba;" ><center><strong>ASEGURAMIENTO SOCIAL</td></tr>
<? if($Paciente[1]==""){
		$cons = "SELECT PrimApe,SegApe,PrimNom,SegNom,Identificacion FROM Central.Terceros where Tipo='Asegurador' and compania='$Compania[0]'";
		$resultado = ExQuery($cons,$conex);
        $EPSNum=0;
		while ($fila = ExFetch($resultado))
		{			
	        $EPSNum++;
			$EPSVer[$fila[4]]=$EPSNum;
			
		}?> 
		<!--<input type="button" value="..."  onClick="VerAfiliados('<? /*echo $EPSVer*/?>')"/>-->
<? }?>
<tr>
	<td>Entidad <? if($Aux['Entidad_']==1){?><font color="red">*</font><? }?></td>
    <td>
        <select name="EPS" id="EPS" style="width:180px;"><option>
            <?
		$cons = "SELECT PrimApe,SegApe,PrimNom,SegNom,Identificacion FROM Central.Terceros where Tipo='Asegurador' and compania='$Compania[0]'
		order by primape,segape,primnom,segnom";
		$resultado = ExQuery($cons,$conex);		
		while ($fila = ExFetch($resultado))
		{	    	
			if($Paciente[26]==$fila[4])
			{
				echo "<option value='$fila[4]' selected>$fila[0] $fila[1] $fila[2] $fila[3]</option>";
			}
			else
			{
				echo "<option value='$fila[4]'>$fila[0] $fila[1] $fila[2] $fila[3]</option>";
			}
		}?>

        </select>
    </td>
    <td style="text-align:left;">Tipo Afiliación <? if($Aux['Tipo_Usuario']==1){?><font color="red">*</font><? }?></td>
    <td>
        <select name="TipoUsu" style="width:180px;"><option>
            <?
		$cons = "SELECT * FROM Salud.TiposUsuarios Order By Tipo";
		$resultado = ExQuery($cons,$conex);
		while ($fila = ExFetch($resultado))
		{
			if($Paciente[27]==$fila[0])
			{
				echo "<option value='$fila[0]' selected>$fila[0]</option>";
			}
			else
			{
				echo "<option value='$fila[0]'>$fila[0]</option>";
			}
		}?>
        </select>
    </td>
</tr>
<tr>
	<td style="text-align:left;">Nivel de Usuario <? if($Aux['Nivel_Usuario']==1){?><font color="red">*</font><? }?></td>
        <td><select name="NivelUsu" style="width:180px;"><option>
            <?
		$cons = "SELECT Nivel FROM Salud.NivelesUsu Order By Nivel";
		$resultado = ExQuery($cons,$conex);
		while ($fila = ExFetch($resultado))
		{
			if($Paciente[28]==$fila[0])
			{
				echo "<option value='$fila[0]' selected>$fila[0]</option>";
			}
			else
			{
				echo "<option value='$fila[0]'>$fila[0]</option>";
			}
		}?>

        </select></td>
	<td style="text-align:left;">No. Carné <? if($Aux['No_Carnet']==1){?><font color="red">*</font><? }?></td>
        <td><input style="width:180px;" type="Text" name="NoCarnet" value="<? echo $Paciente[37]?>" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>
</tr>
<tr>
	<?  
	$consServAC="select numservicio from salud.servicios where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC'";
	$resServAC=ExQuery($consServAC);	
	
	$consTriage="select triage from salud.salaurgencias where compania='$Compania[0]' and numservicio is null and medicoatendio is null and cedula='$Paciente[1]'";
	$resTriage=ExQuery($consTriage);
	//echo $consTriage;
	if($ND[mon]<10){$cero='0';}else{$cero='';}
	if($ND[mday]<10){$cero1='0';}else{$cero1='';}
	$FechaCompActua="$ND[year]-$cero$ND[mon]-$cero1$ND[mday]";?>
        <td colspan="4" align="center"><input type="button" value="Urgencia" style="width:200px;" <? if($Paciente[48]!=$FechaCompActua){?> disabled<? }?>
            onClick=" <? if(ExNumRows($resServAC)>0){?> alert('El paciente no puede ingresar a urgencias debido a que tiene un servicio activo') <? }elseif(ExNumRows($resTriage)>0){?> alert('El paciente esta activo en sala de espera'); <? }else{?> SalaUrgencias();<? }?>" >    
        </td>
</tr>
<tr>
    
</tr>
</tbody>


<!-- Comentado el 2014-03-06 
<tbody>
<tr>
    <td colspan=4 bgcolor="<? echo $Estilo[1]?>" style="color:white; background-color: #7a98ba;" ><center><strong>CONTRATO EN CASO DE URGENCIA<br></td>
</tr>
<tr>
    <?
		if(!$EntidadUrg){$EntidadUrg=$Paciente[60];}
		if(!$ContratoUrg){$ContratoUrg=$Paciente[61];}
		if(!$NoContratoUrg){$NoContratoUrg=$Paciente[62];}

	 	$cons="Select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as Nombre,cuotamoderadora  from Central.Terceros where Tipo='Asegurador' and Compania='$Compania[0]' order by primape";		
		$res=ExQuery($cons);echo ExError();	?>
    	<td style="">Entidad <? if($Aux['Entidad_Urg']==1){?><font color="red">*</font><? }?></td>
        <td colspan="3"><select name="EntidadUrg" id="EntidadUrg" onChange="xajax_ActualizaContrato(this.value);BuscaContrato.location.href='BuscaContrato.php?EntidadUrg='+this.value+'&DatNameSID=<? echo $DatNameSID?>'" > <option value="empty"></option>
     <?	while($fila=ExFetch($res))
		{		
			if($fila[0]==$EntidadUrg){echo "<option selected value='$fila[0]'>$fila[1]</option>";
			}
			else{
			echo "<option value='$fila[0]'>$fila[1]</option>";
			}						
	}?>
        </select></td>
</tr>
<tr>
    <td>Contrato <? if($Aux['Contrato_Urg']==1){?><font color="red">*</font><? }?></td>
        <td><select style="width:180px;" name="ContratoUrg" onChange="xajax_ActualizaNoContrato(this.value);BuscaNoContrato.location.href='BuscaNoContrato.php?ContratoUrg='+this.value+'&EntidadUrg='+document.FORMA.EntidadUrg.value+'&DatNameSID=<? echo $DatNameSID?>'">
        <option value="empty"></option>
    <?	$cons="select contrato from contratacionsalud.contratos where compania='$Compania[0]' and Entidad='$EntidadUrg' and estado='AC' Group By Contrato"; 
	
		$res=ExQuery($cons);
		$banContrato=0;
		while($fila=ExFetch($res))
		{	
			if($ContratoUrg==$fila[0]){
					echo "<option selected value='$fila[0]'>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}		
		}		
		?> 
        </select></td>
	<? 			 	
        $cons="select numero from contratacionsalud.contratos where compania='$Compania[0]' and Entidad='$EntidadUrg' and estado='AC' 
		and  Contrato='$ContratoUrg'
		and estado='AC'	and fechaini<='$ND[year]-$ND[mon]-$ND[mday]' and (fechafin>='$ND[year]-$ND[mon]-$ND[mday]' or fechafin is null)";
		 ?>
    	<td>No. Contrato <? if($Aux['NoContrato_Urg']==1){?><font color="red">*</font><? }?></td>
        <td><select name="NoContratoUrg" style="width:180px;"> <option value="empty"></option>
   <?	$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			if($NoContratoUrg==$fila[0]){
					echo "<option selected value='$fila[0]'>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
		}?>
        </select></td>
</tr>
<tr>
	
</tr>
<tr>
    
</tr>
</tbody>
-->

</tr>
</thead>
</table>

<tr><td colspan="4" align="right"><?
	$cons="Select * from HistoriaClinica.FormatoRedApoyo where Compania='$Compania[0]'";
	$res=ExQuery($cons);
	if(ExNumRows($res)==1)
	{
		echo "<input type='Button' value='Ver Red de Apoyo'>";
	}
	
?>
</td>
</tr>

<? 
/*if($Paciente[1])

{*/?>
<?
/*}*/?>
<tr><td colspan="4"><em><font color="#ff0000">*</font> Campos obligatorios</em></td></tr>
</tbody><center>
</table>
<br>
<input type="Hidden" name="NoEdit" value=<? echo $NoEdit ?>>
<input type="hidden" name="BAN">
<center>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<?php 
$consGuardarHC="select * from salud.usuariosxhc where usuario='$usuario[1]' and modulo='GUARDAR FICHA DE IDENTIFICACIÓN'";
$resGuardarHC=ExQuery($consGuardarHC);
if(ExNumRows($resGuardarHC)>0){
?>
<input type="button" value="Guardar" onClick="Guardar()">
<input type="button" value="Subir Foto" onClick="Foto()">
<?php
}
?>
<input type="button" name="Acudientes" value="Datos de Familiares" onClick="MostrarFormatoAcu('<? echo $FormatoAcu?>','<? echo $TipoFormatoAcu?>','<? echo $TablaAcu?>')">
<input type="hidden" name="BotonG" value="">
</form></center>
</body>
<iframe scrolling="no" id="FrameFondo" name="FrameFondo" frameborder="0" height="0" width="0" style="filter:Alpha(Opacity=200, FinishOpacity=40, Style=2, StartX=20, StartY=40, FinishX=0, FinishY=0);display:none;border:thin; background-color:transparent" ></iframe>
<iframe name="BuscaMpo" id="BuscaMpo" src="BuscarMunicipios.php?DatNameSID=<? echo $DatNameSID?>" style="visibility:hidden;position:absolute;top:1px"></iframe>
<iframe name="BuscaContrato" id="BuscaContrato" src="BuscaContrato.php?DatNameSID=<? echo $DatNameSID?>" style="visibility:hidden;position:absolute;top:1px"></iframe>
<iframe name="BuscaNoContrato" id="BuscaNoContrato" src="BuscaNoContrato.php?DatNameSID=<? echo $DatNameSID?>" style="visibility:hidden;position:absolute;top:1px"></iframe>
<iframe name="BuscaVereda" id="BuscaVereda" src="BuscarVereda.php?DatNameSID=<? echo $DatNameSID?>" style="visibility:hidden;position:absolute;top:1px"></iframe>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" scrolling="no" ></iframe>
<iframe id="FrameAcudientes" name="FrameAcudientes" style="display:none" frameborder="0" height="0" style="border:#e5e5e5 solid" ></iframe>
</html>