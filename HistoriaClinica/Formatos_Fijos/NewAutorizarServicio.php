<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND = getdate();	
	
	
	
	if(!$TMPCOD)
	{
		$TMPCOD=strtotime("$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]").rand(1,9999);
		
		if(!$Numservicio){
		/*	$cons="select entidad,pagadorxservicios.fechaini,pagadorxservicios.fechafin,pagadorxservicios.contrato,pagadorxservicios.nocontrato
			from salud.pagadorxservicios,central.terceros,salud.servicios where pagadorxservicios.numservicio=servicios.numservicio and servicios.compania='$Compania[0]'  
			and pagadorxservicios.entidad=terceros.identificacion and terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' order by fechaini";		*/
			$cons="select * from salud.servicios where estado='rrr'";
		}
		else{
			$cons="select entidad,pagadorxservicios.fechaini,pagadorxservicios.fechafin,pagadorxservicios.contrato,pagadorxservicios.nocontrato
			,pagadorxservicios.tipo
			from salud.pagadorxservicios,central.terceros,salud.servicios where pagadorxservicios.numservicio=servicios.numservicio and servicios.compania='$Compania[0]' 
			and pagadorxservicios.entidad=terceros.identificacion and terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and servicios.numservicio=$Numservicio
			order by fechaini ";			
		}
		
		$res=ExQuery($cons);
			//echo $cons;
		if(ExNumRows($res)>0){
			while($fila=ExFetch($res)){
				if($fila[2]!=''){
					$cons2="insert into salud.tmppagadorxfactura (compania,tmpcod,cedula,entidad,fechaini,fechafin,contrato,nocontrato,tipo) values
					('$Compania[0]','$TMPCOD','$Paciente[1]','$fila[0]','$fila[1]','$fila[2]','$fila[3]','$fila[4]',$fila[5])";
					$res2=ExQuery($cons2);
				}
				else{
					$cons2="insert into salud.tmppagadorxfactura (compania,tmpcod,cedula,entidad,fechaini,contrato,nocontrato,tipo) values
					('$Compania[0]','$TMPCOD','$Paciente[1]','$fila[0]','$fila[1]','$fila[3]','$fila[4]',$fila[5])";
					$res2=ExQuery($cons2);
				}
			}
		}
	}
	
	if($Cancelar==1)
	{
		$cons="delete from salud.tmppagadorxfactura where tmpcod='$TMPCOD' and compania='$Compania[0]' and cedula='$Paciente[1]'";
		$res=ExQuery($cons);
		?><script language="javascript">location.href='AutorizaServicio.php?DatNameSID=<? echo $DatNameSID?>';</script><?
	}
	global $ban1;
	global $ban2;
	global $ban3;
	global $today;	
	$ban1=0;
	$ban2=0;
	$ban3=0;	
	
	if($Insertar)
	{			
		if(!$Hasta){
			$cons2="insert into salud.tmppagadorxfactura (compania,tmpcod,cedula,entidad,fechaini,contrato,nocontrato) values
			('$Compania[0]','$TMPCOD','$Paciente[1]','$Entidad','$Desde','$Contrato','$Nocontrato')";
		}
		else{
			$cons2="insert into salud.tmppagadorxfactura (compania,tmpcod,cedula,entidad,fechaini,fechafin,contrato,nocontrato) values
			('$Compania[0]','$TMPCOD','$Paciente[1]','$Entidad','$Desde','$Hasta','$Contrato','$Nocontrato')";
		}
		$res2=ExQuery($cons2);		
	}
	
	if($Eliminar){
		$cons="Delete from salud.tmppagadorxfactura where compania='$Compania[0]' and tmpcod='$TMPCOD' and cedula='$Paciente[1]' and entidad='$EPS' and contrato='$Contra' and 
		nocontrato='$NoContra' and fechaini='$Ini'";
		$res=ExQuery($cons);		
	}
	
	if($Guarda==1)
	{	
		//if(!$Fechaerg){$Fechaerg='';}else{$Fechaerg="',$Fechaerg'";}
		
		//AUTORIZACIÓN URGENCIAS
		if($Ambito='Urgencias'){
			$consA="update salud.salasintriage set autorizacion=1, usuarioatu='$usuario[1]' where cedula='$Paciente[1]' and estado=1";
				$resA=ExQuery($consA);
		}
		//	
		
		if(!$NumservicioAnt)
		{			
			$cons = "Select numservicio from Salud.Servicios where Compania = '$Compania[0]' order by numservicio desc";					
			$res = ExQuery($cons);
			$fila = ExFetch($res);			
			$AutoId = $fila[0] +1;
			if(!$Fechae){
				$cons="Insert into 			
				Salud.servicios(Cedula,numservicio,tiposervicio,Fechaing,tipousu,nivelusu,autorizac1,autorizac2,autorizac3,estado,nocarnet,compania,medicotte,usuarioingreso,tipousunarino,clinica,usucreaserv,fecreaserv,pagina) 
				values 		 		
			('$Paciente[1]',$AutoId,'$Ambito','$Fechaing','$Tipousu','$Nivelusu','$Autorizac1','$Autorizac2','$Autorizac3','$Estado','$Nocarnet','$Compania[0]','$Medicotte','$usuario[1]','$TipoUsuNarino','$Clinica','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','NewAutorizaServicio.php')";	
			}
			else{
				$cons="Insert into Salud.servicios  
				(Cedula,numservicio,tiposervicio,Fechaing,Fechaegr,tipousu,nivelusu,autorizac1,autorizac2,autorizac3,estado,nocarnet,compania,medicotte,usuarioingreso,tipousunarino,clinica,usucreaserv,fecreaserv,pagina) 
				values 
('$Paciente[1]',$AutoId,'$Ambito','$Fechaing','$Fechae','$Tipousu','$Nivelusu','$Autorizac1','$Autorizac2','$Autorizac3','$Estado','$Nocarnet','$Compania[0]','$Medicotte','$usuario[1]','$TipoUsuNarino','$Clinica','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','NewAutorizaServicio.php')";	
			 }	
			
			$cons3="delete from salud.pagadorxservicios where numservicio=$AutoId and compania='$Compania[0]'";	
			//echo $cons3;
			$res3=ExQuery($cons3);echo ExError();		 
			$cons3="select entidad,tmppagadorxfactura.contrato,tmppagadorxfactura.nocontrato,tmppagadorxfactura.fechaini,tmppagadorxfactura.fechafin,tipo
			from salud.tmppagadorxfactura where tmpcod='$TMPCOD' and tmppagadorxfactura.compania='$Compania[0]' and cedula='$Paciente[1]'";	
			$res3=ExQuery($cons3);echo ExError();						
			while($fila3=ExFetch($res3))
			{
				if($fila3[4]!=''){
					$cons4="insert into salud.pagadorxservicios (numservicio,compania,entidad,contrato,nocontrato,fechaini,fechafin,usuariocre,fechacre,tipo) values
				($AutoId,'$Compania[0]','$fila3[0]','$fila3[1]','$fila3[2]','$fila3[3]','$fila3[4]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$fila3[5])";
				}
				else{
					$cons4="insert into salud.pagadorxservicios (numservicio,compania,entidad,contrato,nocontrato,fechaini,usuariocre,fechacre,tipo) values
				($AutoId,'$Compania[0]','$fila3[0]','$fila3[1]','$fila3[2]','$fila3[3]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$fila3[5])";
				}
				$res4=ExQuery($cons4);echo ExError();
				//echo $cons4."<br>";
			}		 					 							
		}
		elseif($Edit==1)
		{	
			if($Fechae==''){
				$cons="Update Salud.servicios set 		
				tiposervicio='$Ambito',Fechaing='$Fechaing',tipousu='$Tipousu',nivelusu='$Nivelusu',autorizac1='$Autorizac1',autorizac2='$Autorizac2',autorizac3='$Autorizac3',
				estado='$Estado',nocarnet='$Nocarnet',medicotte='$Medicotte',tipousunarino='$TipoUsuNarino',clinica='$Clinica'
				,usumodserv='$usuario[1]',fecmodserv='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',pagmodif='NewAutorizaServicio.php'
				where Cedula='$Paciente[1]' and Compania='$Compania[0]' and numservicio=$NumservicioAnt";
			}
			else{				
				$cons="Update Salud.servicios set 
				tiposervicio='$Ambito',Fechaing='$Fechaing',Fechaegr='$Fechae',tipousu='$Tipousu',nivelusu='$Nivelusu',autorizac1='$Autorizac1',autorizac2='$Autorizac2',
				autorizac3='$Autorizac3',estado='$Estado',nocarnet='$Nocarnet',medicotte='$Medicotte',tipousunarino='$TipoUsuNarino',clinica='$Clinica'
				,usumodserv='$usuario[1]',fecmodserv='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',pagmodif='NewAutorizaServicio.php'
				where Cedula='$Paciente[1]' and Compania='$Compania[0]' and numservicio=$NumservicioAnt";
			}	
			$cons3="delete from salud.pagadorxservicios where numservicio=$NumservicioAnt and compania='$Compania[0]'";	
			$res3=ExQuery($cons3);echo ExError();						
			$cons3="select entidad,tmppagadorxfactura.contrato,tmppagadorxfactura.nocontrato,tmppagadorxfactura.fechaini,tmppagadorxfactura.fechafin,tipo
			from salud.tmppagadorxfactura where tmpcod='$TMPCOD' and tmppagadorxfactura.compania='$Compania[0]' and cedula='$Paciente[1]'";	
			//echo $cons3;
			$res3=ExQuery($cons3);echo ExError();						
			while($fila3=ExFetch($res3))
			{
				if($fila3[4]!=''){
					$cons4="insert into salud.pagadorxservicios (numservicio,compania,entidad,contrato,nocontrato,fechaini,fechafin,usuariocre,fechacre,tipo) values
			($NumservicioAnt,'$Compania[0]','$fila3[0]','$fila3[1]','$fila3[2]','$fila3[3]','$fila3[4]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$fila3[5])";					
				}
				else
				{
					$cons4="insert into salud.pagadorxservicios (numservicio,compania,entidad,contrato,nocontrato,fechaini,usuariocre,fechacre,tipo) values
			($NumservicioAnt,'$Compania[0]','$fila3[0]','$fila3[1]','$fila3[2]','$fila3[3]','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$fila3[5])";
				}
				$res4=ExQuery($cons4);echo ExError();
				//echo $cons4."<br>";
			}
			//echo $cons3;
		}
		//echo $cons;							
		$res=ExQuery($cons);echo ExError();	
		
		$cons2="update central.terceros set  tipousu='$Tipousu',nivelusu='$Nivelusu',nocarnet='$Nocarnet' where identificacion='$Paciente[1]' and compania='$Compania[0]'";
		
		$res2=ExQuery($cons2);echo ExError();	
		$cons="delete from salud.tmppagadorxfactura where tmpcod='$TMPCOD' and compania='$Compania[0]' and cedula='$Paciente[1]'";
		$res=ExQuery($cons);
      ?>  <script language="javascript">
	      	location.href='AutorizaServicio.php?DatNameSID=<? echo $DatNameSID?>';
        </script>        
        <? 
	}
	
	if($Edit)
	{	
		if($Numservicio!=''){			
			$cons2="Select tiposervicio,estado,medicotte,fechaing,fechaegr,nocarnet,tipousu,nivelusu,autorizac1,autorizac2,autorizac3,tipousunarino,clinica
			from Salud.servicios where Cedula='$Paciente[1]' and Compania='$Compania[0]' and Numservicio=$Numservicio";								
			//echo $cons2;
			$res2=ExQuery($cons2);
			$row=ExFetch($res2); echo ExError();
			
			$Ambito=$row[0];			
			$Estado=$row[1];
			$Medicotte=$row[2];	
			$Fechaing=$row[3];	
			$Fechae=$row[4];	
			$Nocarnet=$row[5];
			$Tipousu=$row[6];		
			$Nivelusu=$row[7];	
			$Autorizac1=$row[8];
			$Autorizac2=$row[9];
			$Autorizac3=$row[10];
			$TipoUsuNarino=$row[11];				
			$Clinica=$row[12];
			
			$Edit=0;	
			
		}
		$ban3=1;		
		
	}	
	elseif(!$Fechaing){		
		$Fechaing = date("Y-m-d");      		
	}
	$Fechaing=substr($Fechaing,0,11);
	$Fechae=substr($Fechae,0,11);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function salir(){
		 //location.href='AutorizaServicio.php';
		 document.FORMA.Cancelar.value=1;
		 document.FORMA.submit();
	}
	function Validar()
	{
						
		if(document.FORMA.Nocarnet.value==""){
			alert("Debe digitar el numero del Carnet");return false;
		}
		if(document.FORMA.Autorizac1.value==""){
			alert("Debe digitar la Autorizacion 1!!!");return false;
		}		
		document.FORMA.Guarda.value=1;
		document.FORMA.Edit.value=1;		
		
	}
	function Inserta(){
		if(document.FORMA.Entidad.value==""){
			alert("Debe seleccionar una Entidad!!!");return false;
		}
		if(document.FORMA.Contrato.value==""){
			alert("Debe haber un Contrato!!!");return false;
		}
		if(document.FORMA.Nocontrato.value==""){
			alert("Debe haber un numero de Contrato!!!");return false;
		}
		if(document.FORMA.Desde.value==""){
			alert("Debe seleccionar la fecha inicial !!!");return false;
		}		
		else{
			if(document.FORMA.Desde.value<=document.FORMA.Fin.value){
				alert("La fecha inicial debe ser mayor a la ultima fecha final!!");return false;
			}
			else{			
				if(document.FORMA.Hasta.value!=""){
					if(document.FORMA.Hasta.value<document.FORMA.Desde.value){
						alert("La fecha final debe ser mayor o igual a la fecha inicial !!!");return false;
					}
				}
			}
		}
		document.FORMA.Insertar.value=1;
		document.FORMA.submit();
	}
	
	function Elimina(E,C,N,I)
    {
    	document.FORMA.EPS.value=E;
        document.FORMA.Contra.value=C;
        document.FORMA.NoContra.value=N;
        document.FORMA.Ini.value=I;      
        document.FORMA.Eliminar.value=1;
		document.FORMA.submit();
    }
	
	function Finalizar(e,Eps,C,N,I,T)	
	{
		x = e.clientX;
		y = e.clientY; 
		st = document.body.scrollTop;
		frames.FrameOpener.location.href="FinPagador.php?DatNameSID=<? echo $DatNameSID?>&EPS="+Eps+"&Contra="+C+"&NoContra="+N+"&Inicio="+I+"&TMPC="+T;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=(y)+st+10;
		document.getElementById('FrameOpener').style.left=x-50;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='310px';
		document.getElementById('FrameOpener').style.height='280px';
	}
	function EditarEPS(e,Eps,C,N,I,T)	
	{
		x = e.clientX;
		y = e.clientY; 
		st = document.body.scrollTop;
		frames.FrameOpener.location.href="EditarPagador.php?DatNameSID=<? echo $DatNameSID?>&EPS="+Eps+"&Contra="+C+"&NoContra="+N+"&Inicio="+I+"&TMPC="+T;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=(y)+st+10;
		document.getElementById('FrameOpener').style.left=100;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='610px';
		document.getElementById('FrameOpener').style.height='140px';
	}
	function DoblePagador(e,Eps,C,N,I,T,NS)
	{
		x = e.clientX;
		y = e.clientY; 
		st = document.body.scrollTop;
		frames.FrameOpener.location.href="DoblePagador.php?DatNameSID=<? echo $DatNameSID?>&EPS="+Eps+"&Contra="+C+"&NoContra="+N+"&Inicio="+I+"&TMPC="+T+"&NumServ="+NS;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=(y)+st+10;
		document.getElementById('FrameOpener').style.left=5;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='100%';
		document.getElementById('FrameOpener').style.height='140px';
	}
	function GenerarLiq(e,NumServ)
	{
		x = e.clientX;
		y = e.clientY;
		st = document.body.scrollTop;
		frames.FrameOpener.location.href="PerLiqServ.php?DatNameSID=<? echo $DatNameSID?>&NumServ="+NumServ+"&FechaIni="+document.FORMA.Fechaing.value;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=(y)+st-100;
		document.getElementById('FrameOpener').style.left=1;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='100%';
		document.getElementById('FrameOpener').style.height='300px';
	}
	function MoverFecha(e,NumServ)
	{
		x = e.clientX;
		y = e.clientY;
		st = document.body.scrollTop;
		frames.FrameOpener.location.href="CambiaFechaServicio.php?DatNameSID=<? echo $DatNameSID?>&NumServ="+NumServ+"&FecIng="+document.FORMA.Fechaing.value+"&FecEgr="+document.FORMA.Fechae.value;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=(y)+st-100;
		document.getElementById('FrameOpener').style.left=1;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='100%';
		document.getElementById('FrameOpener').style.height='300px';
	}
	
	function formatoAdministrativo(){
			frames.FrameAdministrativo.location.href="/HistoriaClinica/Datos.php?DatNameSID=<? echo $DatNameSID?>&Frame=2&TipoFormato=ADMINISTRATIVOS&Formato=NOTAS ADMINISTRATIVAS";
			document.getElementById('FrameAdministrativo').style.position='absolute';
			document.getElementById('FrameAdministrativo').style.top='2%';
			document.getElementById('FrameAdministrativo').style.left='0';
			document.getElementById('FrameAdministrativo').style.display='';
			document.getElementById('FrameAdministrativo').style.width='100%';
			document.getElementById('FrameAdministrativo').style.height='95%';
	
	}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4" align="center">
	<tr>
		<td colspan="6" bgcolor="#e5e5e5" style="font-weight:bold" align="center">Seguridad Social</td>
    </tr>	
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Proceso</td>
        
		<td><select name="Ambito">
		<?
			$cons="Select Ambito from salud.Ambitos where  compania='$Compania[0]' and ambito!='Sin Ambito' order by ambito";
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($fila[0]==$Ambito){ 
				//if($fila[0]=="Urgencias"){ //CORRECCIÓN PARA LA CLÍNICA DE CALI - GENERÒ CAOS -
					echo "<option value='$fila[0]' selected>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
			}
		?></select></td>     
		
	
    
        <td bgcolor="#e5e5e5" style="font-weight:bold">Estado</td>
        <td><select name="Estado">
        <? if($Estado=='AN') 
		{		
		?>  			
        	<option value="AN" selected>Inactivo</option>
        	<option value="AC">Activo</option>
        <? } else {?>        	
        	<option value="AC">Activo</option>
            <option value="AN">Inactivo</option>
         <? }?>
        </select></td>
       <!-- <td bgcolor="#e5e5e5" style="font-weight:bold">Medico Tratante</td>
        <td><select name="Medicotte">
        	<option></option>
		<?
		/* $cons="select nombre,medicos.usuario from salud.cargos,salud.medicos,central.usuarios where salud.cargos.compania='$Compania[0]' and salud.medicos.compania='$Compania[0]' and salud.cargos.cargos=salud.medicos.cargo and Medicos.usuario=usuarios.usuario and asistencial=1 order by nombre";
		echo $cons;
		$res=ExQuery($cons);		
		while($fila=ExFetch($res))
		{	
					
			if($fila[1]==$Medicotte){
				?><option selected value="<? echo $fila[1];?>"><? echo $fila[0];?></option><? 
			}
			else{ */?>			
			<option value="<? /* echo $fila[1]; */?>"><? /*echo $fila[0]; */?></option>			
			<? /* }			 
		} */?>
		</select></td> -->
	</tr>
	<tr>
	    <td bgcolor="#e5e5e5" style="font-weight:bold">Fecha Inicio</td>
    	<td><input type="Text" name="Fechaing"  readonly onClick="popUpCalendar(this, FORMA.Fechaing, 'yyyy-mm-dd')" value="<? echo $Fechaing?>"></td>       
        
        <td bgcolor="#e5e5e5" style="font-weight:bold">Fecha Fin</td>
        <td><input type="Text" name="Fechae"  readonly onClick="popUpCalendar(this, FORMA.Fechae, 'yyyy-mm-dd')" value="<? echo $Fechae?>"></td>
        
        <? $cons3="select tipousu,nivelusu,nocarnet from central.terceros where compania='$Compania[0]' and identificacion='$Paciente[1]'";	
	 	$res3=ExQuery($cons3);$fila3=ExFetch($res3);
		if(!$Tipousu){$Tipousu=$fila3[0];}
		if(!$Nivelusu){$Nivelusu=$fila3[1];}
		if(!$Nocarnet){$Nocarnet=$fila3[2];}?>  
       	<td bgcolor="#e5e5e5" style="font-weight:bold">No. Carné</td>
        <td><input type="text" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" name="Nocarnet" value="<? echo $Nocarnet?>"></td>  	        
	</tr>
    <tr>
		<td bgcolor="#e5e5e5" style="font-weight:bold">Tipo Usuario</td>
        <td><select name="Tipousu">
        <?	$cons="select * from salud.tiposusuarios"; 
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			if($fila[0]==$Tipousu){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
			else{echo "<option value='$fila[0]'>$fila[0]</option>";}
		}
		?>
        </select></td>
		<td bgcolor="#e5e5e5" style="font-weight:bold">Nivel Usuario</td><td><select name="Nivelusu">
         <?	$cons="select * from salud.nivelesusu"; 
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			if($fila[0]==$Nivelusu){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
			else{echo "<option value='$fila[0]'>$fila[0]</option>";}
		}
		?>
        </select></td>
       <td bgcolor="#e5e5e5" style="font-weight:bold">Autorización 1</td>
       <td><input type="Text" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" name="Autorizac1" value="<? echo $Autorizac1?>"></td>
	</tr>
	<tr>		
		<td bgcolor="#e5e5e5" style="font-weight:bold">Autorización 2</td>
        <td><input type="Text" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" name="Autorizac2" value="<? echo $Autorizac2?>"></td>
		<td bgcolor="#e5e5e5" style="font-weight:bold">Autorización 3</td>
        <td><input type="Text" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" name="Autorizac3" value="<? echo $Autorizac3?>"></td>
        <!-- <td bgcolor="#e5e5e5" style="font-weight:bold">Usuario de Nariño</td>
        <td>
        <?	$cons="select tipousunarino from salud.tiposusunarino";
			$res=ExQuery($cons);?>
        	<select name="TipoUsuNarino"><option></option>
            <?	while($fila=ExFetch($res))
				{
					if($TipoUsuNarino==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
					else{echo "<option value='$fila[0]'>$fila[0]</option>";}
				}?>
            </select>
        </td>
		-->
	</tr> 
	
    <tr>
    	<!--
		<td bgcolor="#e5e5e5" style="font-weight:bold">Clinica</td>   
	<?	$cons="select clinica from salud.clinicashc where compania='$Compania[0]' order by clinica";
        $res=ExQuery($cons);?>
        <td colspan="5">
            <select name="Clinica">
                <option></option>
            <?	while($fila=ExFetch($res))
                {
                    if($fila[0]==$Clinica){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
                    else{echo "<option value='$fila[0]'>$fila[0]</option>";}
                }?>
            </select>
		</td>
		-->
    </tr>
    <tr align="center">
    	<td colspan="6">
			<input type="button" value="Notas Administrativas" onClick = "formatoAdministrativo()">
        	<input type="button" value="Generar Liquidación" <? if(!$Numservicio){?> disabled<? }?> onClick="GenerarLiq(event,'<? echo $Numservicio?>')">
            <input type="button" value="Mover Fecha Servicio" <? if(!$Numservicio){?> disabled<? }?> onClick="MoverFecha(event,'<? echo $Numservicio?>')">
        </td>
    </tr>
</table>
<br>
 
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center">
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="8">Pagador</td>        
    </tr>
    <tr align="center" bgcolor="#e5e5e5" style="font-weight:bold">
    	<td>Entidad Aseguradora (EPS)</td><td>Contrato</td><td>No. Contrato</td><td>Desde</td><td>Hasta</td><td></td>
  	</tr>
<?	
	$cons="select 
	primnom,segnom,primape,segnom,tmppagadorxfactura.contrato,tmppagadorxfactura.nocontrato,tmppagadorxfactura.fechaini,tmppagadorxfactura.fechafin,entidad
	,tmppagadorxfactura.tipo
	from salud.tmppagadorxfactura,central.terceros where tmppagadorxfactura.entidad=terceros.identificacion and tmpcod='$TMPCOD'
	and terceros.compania='$Compania[0]' and tmppagadorxfactura.compania='$Compania[0]' order by fechaini,tmppagadorxfactura.tipo desc";							
	//echo $cons;
	$res=ExQuery($cons); echo ExError();
	if(ExNumRows($res)>0)
	{
		$BanNew=1;
		while($fila=ExFetch($res))
		{?>
			<tr>
            	<td align="center"><? echo "$fila[0] $fila[1] $fila[2] $fila[3]"?></td><td align="center"><? echo $fila[4]?></td><td align="center"><? echo $fila[5]?></td>
                <td align="center"><? echo $fila[6]?></td><td align="center"><? echo $fila[7]?>&nbsp;</td>
           <?	$Fin=$fila[7]; $EPS=$fila[8]; $Contra=$fila[4]; $NoContra=$fila[5]; $Ini=$fila[6];
		   		if($fila[9]==1){?>
                	<td><button type="button" onClick="EditarEPS(event,'<? echo $EPS?>','<? echo $Contra?>','<? echo $NoContra?>','<? echo $Ini?>','<? echo $TMPCOD?>')" title="Editar"> 
                		<img src="/Imgs/b_edit.png"/>
                    	</button><?		
				}
				else{ ?>
                    <td><button type="button" 
                    onClick="if(confirm('Desea eliminar esete registro!')){Elimina('<? echo $EPS?>','<? echo $Contra?>','<? echo $NoContra?>','<? echo $Ini?>');}" 
                    title="Eliminar">
                        <img src="/Imgs/b_drop.png"/>
                    </button>
			<?	}
		}
		if($Fin!=''){?>
			<button type="button" 
            onClick="if(confirm('Desea eliminar esete registro!')){Elimina('<? echo $EPS?>','<? echo $Contra?>','<? echo $NoContra?>','<? echo $Ini?>');}" 
            title="Eliminar">
            	<img src="/Imgs/b_drop.png"/>
         	</button>
<?		}
		else{?>
			<button type="button" 
            onClick="Finalizar(event,'<? echo $EPS?>','<? echo $Contra?>','<? echo $NoContra?>','<? echo $Ini?>','<? echo $TMPCOD?>')" title="Finalizar">
            	<img src="/Imgs/b_usredit.png"/>
            </button>
	<?	}?>	
			<button type="button" title="Agregar Doble Pagador"
            onClick="DoblePagador(event,'<? echo $EPS?>','<? echo $Contra?>','<? echo $NoContra?>','<? echo $Ini?>','<? echo $TMPCOD?>','<? echo $Numservicio?>')">
            	<img src="/Imgs/b_import.png">
          	</button>
        </td>	
<?	}
	if($Fin!=''||$BanNew!=1){
		if($BanNew==''){$Fin='';}	?>      	  
    	<input type="hidden" name="Fin" value="<? echo $Fin?>">
	    <tr>
    	    <td><select name="Entidad" onChange="document.FORMA.submit();"><option></option>
	<? 			$cons="Select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as Nombre  from Central.Terceros 
				where Tipo='Asegurador' and Compania='$Compania[0]' order by primape";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{
					if($fila[0]==$Entidad&&$Insertar==''){
						echo "<option selected value='$fila[0]'>$fila[1]</option>";
					}
					else{
						echo "<option value='$fila[0]'>$fila[1]</option>";
					}
				}
			?> 	</select>
			</td>
	  		<input type="hidden" name="AuxAntAseg" value="<? echo $Entidad?>">
    	    
        	<td>
      	<?	$cons="select contrato from contratacionsalud.contratos where compania='$Compania[0]' and estado='AC' and Entidad='$Entidad' Group By Contrato"; 
		 	//echo $cons;
			$res=ExQuery($cons);?>      
            <select name="Contrato" onChange="document.FORMA.submit()"><option></option>
         <?	$banContrato=0;
			while($fila=ExFetch($res))
			{				
				if($Contrato==$fila[0]&&$Insertar==''){
						echo "<option selected value='$fila[0]'>$fila[0]</option>";
					}
					else{
						echo "<option value='$fila[0]'>$fila[0]</option>";
					}
			}		?>        
	        </select></td>        
		<? 	if($Entidad!=$AuxAntAseg){$Contrato='';}
			if($Contrato==''){
				$cons="select contrato from contratacionsalud.contratos where compania='$Compania[0]' and estado='AC' and Entidad='$Entidad' Group By Contrato"; 
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$Contrato=$fila[0];
			}?>
			<td>&nbsp;<select name="Nocontrato"><option></option>
    	 <?	$cons="select numero from contratacionsalud.contratos where compania='$Compania[0]' and estado='AC' and Entidad='$Entidad' and Contrato='$Contrato' and estado='AC'
		 	and fechaini<='$ND[year]-$ND[mon]-$ND[mday]' and (fechafin>='$ND[year]-$ND[mon]-$ND[mday]' or fechafin is null)"; 
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				if($NoContrato==$fila[0]&&$Insertar==''){
					echo "<option selected value='$fila[0]'>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
			}				?>
	        </select></td>
       	<? 	if(!$Fin){	
				if($ND[mon]<10){ $ce1="0";}else{$ce1="";}
				if($ND[mday]<10){$ce2="0";}else{$ce2="";}
				$Desde="$ND[year]-$ce1$ND[mon]-$ce2$ND[mday]"; 
			}
			else{ 				
				$timestamp= strtotime("+1 day", strtotime($Fin)); 
				$FecVencAux = date("Y-m-d", $timestamp); 
				$FecVenc=explode("-",$FecVencAux);
				//$FecVenc= strtotime("$Fin + 1 day");
				//$FecVenc=strtotime($FecVenc);
				//$FecVenc=getdate($FecVencAux);
				//if($FecVenc[1]<10){$ce1="0";}else{$ce1="";}
				//if($FecVenc[2]<10){$ce2="0";}else{$ce2="";}
				$Desde="$FecVenc[0]-$ce1$FecVenc[1]-$ce2$FecVenc[2]";
				//$Desde=$FecVencAux;
			}?>
    	    <td align="center"><input type="text" name="Desde" readonly <? if(!$Fin){?>onClick="popUpCalendar(this, FORMA.Desde, 'yyyy-mm-dd')"<? }?>
            value="<? if($Desde!=''){echo $Desde;}?>">
            </td>
        	<td align="center"><input type="text" name="Hasta" readonly onClick="popUpCalendar(this, FORMA.Hasta, 'yyyy-mm-dd')" value="<? if($Insertar==''){echo $Hasta;}?>"></td>
	        <td><button type="button" onClick="Inserta()" title="Añadir"><img src="/Imgs/b_check.png" /></button></td>
  <?	}?>
	</tr>
    
</table>
<br>
<table  style='font : normal normal small-caps 12px Tahoma;'  cellpadding="4" align="center">
<tr>
    <input type="hidden" name="NumservicioAnt" value="<? echo $Numservicio?>">
    <input type="hidden" name="Numservicio" value="<? echo $Numservicio?>"> 
    <input type="hidden" name="Edit" value="<? echo $Edit?>">
    <input type="hidden" name="Guarda" value="">
    <input type="hidden" name="TMPCOD" value="<? echo $TMPCOD?>">
    <input type="hidden" name="TMP" value="<? echo $TMP?>">
    <input type="hidden" name="Insertar" value="">
    <input type="hidden" name="Cancelar" value="">
    <input type="hidden" name="EPS" value="">
    <input type="hidden" name="Contra" value="">
    <input type="hidden" name="NoContra" value="">
    <input type="hidden" name="Ini" value="">
    <input type="hidden" name="Eliminar" value="">
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
    
   	<td align="center" colspan="8"><input type="submit" value="Guardar"><input type="button" value="Cancelar" onClick="salir()"></td>        
    </tr>
</table>
<form>
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe> 
<iframe id="FrameAdministrativo" name="FrameAdministrativo" style="display:none" frameborder="0" height="0" style="border:#e5e5e5 solid" ></iframe>

</body>
</html>
