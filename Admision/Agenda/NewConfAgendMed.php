<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");		
	$ND=getdate();
	$F=explode("-",$Fecha);	
	$cons="select tiempo from salud.tiemposintervalocitas where compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res); if($fila[0]){$TiempInt=$fila[0];}else{$TiempInt="10";}
	
	$cons="select intervaloagenda from salud.medicos where compania='$Compania[0]' and usuario='$Profecional'";
	$res=ExQuery($cons);
	$fila=ExFetch($res); if($fila[0]){$TiempInt=$fila[0];}
	
	if($CedDef&&$Ban==''){
		$cons="Select cedula,primape,segape,primnom,segnom,cup,telefono,entidad,contrato,nocontrato,fecnac,fechasolicita,solicitadapor from central.terceros,salud.agenda where
 terceros.identificacion=agenda.cedula and estado='Pendiente' and cedula='$CedDef' and hrsini='$HrIR' and minsini='$MinIR' and fecha='$FechR' and agenda.compania='$Compania[0]' and terceros.compania='$Compania[0]' and id=$Id";
 		$res=ExQuery($cons);echo ExError();
		$fila = ExFetchArray($res);
		$Procedimiento=$fila[5];
		$Asegurador=$fila[7];
		$Contrato=$fila[8];
		$Nocontrato=$fila[9];
		$Cedula=$fila[0];
		$Fech=explode("-",$fila[10]);
		$AnioNac=$Fech[0];
		$MesNac=$Fech[1];
		$DiaNac=$Fech[2];
		$PrimApe=$fila[1];
		$SegApe=$fila[2];
		$PrimNom=$fila[3];
		$SegNom=$fila[4];
		$Telefono=$fila[6];
		$FechaSolicita=$fila[11];
		$solicitadapor=$fila[12];
		$Ban=1;
	}
	if($Guardar)
	{	
		$cons4="select valor from salud.multas where compania='$Compania[0]' and cedula='$Cedula' and estado='AC'";
		$res4=ExQuery($cons4);
		if(ExNumRows($res4)>0)
		{?>
        	<script language="javascript">
			if(confirm("El usuario tiene multas pendientes, desea realizar la asignacion?")){								
			<?	$banMult="0";?>            
			}
			else{		
				alert();
				xajax_Multa(); 		
			<?	//$banMult="1";?>
			}
			</script>           
	<?	}
		if($CupBloq)
		{
			if($CupBloq!=$Procedimiento){
				$BanCupRes=1;
			}
		}
		if($BanCupRes!=1){
			$cons4="select * from salud.agenda where 
			compania='$Compania[0]' and cedula='$Cedula' and hrsini='$HrIni' and minsini='$MinIni' and estado!='Cancelada' and fecha='$Fecha' and medico='$Profecional'";
			$res4=ExQuery($cons4);		
			if(ExNumRows($res4)>0){
				$YaEsta=1;
			}
			else
			{
				
				$YaEsta=0;
				$cons1="Select eps from Central.Terceros where identificacion='$Cedula' and Compania='$Compania[0]'";		
				//echo $cons1;
				$res1=ExQuery($cons1);		
				if(ExNumRows($res1)>0)
				{			
					$Aux=ExFetch($res1);
					if($Aux[0]==''){$EPS=",eps='$Asegurador'";}
					$cons2="Update central.terceros set identificacion='$Cedula',primape='$PrimApe',segape='$SegApe',primnom='$PrimNom',segnom='$SegNom',telefono='$Telefono',tipo='Paciente',
					fecnac='$AnioNac-$MesNac-$DiaNac',regimen='Persona Natural',tipopersona='Persona Natural',usuariomod='$usuario[1]' $EPS
					where identificacion='$Cedula' and compania='$Compania[0]'";					
				}
				else
				{			
					$cons2="Insert into central.terceros
					(identificacion,primape,segape,primnom,segnom,telefono,tipo,regimen,compania,tipopersona,fecnac,usuariocreador,fechacreacion,eps) 
					values ('$Cedula','$PrimApe','$SegApe','$PrimNom','$SegNom','$Telefono','Paciente','Persona Natural','$Compania[0]','Persona Natural','$AnioNac-$MesNac-$DiaNac','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday]','$Asegurador')";			
				}	
				//echo $cons2;	
				$res2=ExQuery($cons2);echo ExError();
				$MFin=$MinIni+$TiemProced;
				//echo "TiemProced=$TiemProced";
				if($MFin>=60){			
					$HFin=$HrIni+1;
					$MFin=$MFin-60;
					//echo "HFin=$HFin,HrIni=$HrIni,MFin=$MFin<br>\n";
				}
				else{			
					$HFin=$HrIni;
					//echo "HFin=$HFin,HrIni=$HrIni";
				}
				if(!$CedDef)
				{
					$cons3 = "Select id from salud.agenda where compania='$Compania[0]' order by id desc";
					$res3 = ExQuery($cons3);
					$fila3 = ExFetch($res3);
					$Id = $fila3[0] +1;
					if($TimConsulta!=''){$Sobrecupo=1;}else{$Sobrecupo=0;}
					if($FechaSolicita){$FecSol1=",fechasolicita";$FecSol2=",'$FechaSolicita'";}
					$cons3="Insert into salud.agenda(compania,cup,hrsini,minsini,hrsfin,minsfin,tiempocons,entidad,contrato,nocontrato,cedula,estado,medico,fecha,usucrea,fechacrea,id,sobrecupo $FecSol1,solicitadapor) 				
					values ('$Compania[0]','$Procedimiento','$HrIni','$MinIni','$HFin','$MFin','$TiemProced','$Asegurador','$Contrato','$Nocontrato','$Cedula','Pendiente','$Profecional','$Fecha','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]',$Id,$Sobrecupo $FecSol2,'$solicitadapor')";
							
				}
				else
				{
					if($FechaSolicita){$FecSol=",fechasolicita='$FechaSolicita'";}
					$cons3="update salud.agenda set 		
					cup='$Procedimiento',hrsini='$HrIni',minsini='$MinIni',hrsfin='$HFin',minsfin='$MFin',tiempocons='$TiemProced',entidad='$Asegurador',
					contrato='$Contrato',nocontrato='$Nocontrato',medico='$Profecional',fecha='$Fecha',reasignada=1,usumodif='$usuario[1]',
					fechareasig='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]' $FecSol, solicitadapor='$solicitadapor'
					where compania='$Compania[0]' and hrsini='$HrIR' and minsini='$MinIR' and fecha='$FechR' and cedula='$CedDef' and id=$Id";
				}			
				//echo $cons3;
				$res3=ExQuery($cons3);echo ExError();			
				$cons3="";
				?>	       	 		
				<script language="javascript">				
				   location.href='ConfAgendMed.php?DatNameSID=<? echo $DatNameSID?>&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&AnioCalend=<? echo $F[0]?>&MesCalend=<? echo $F[1]?>&DiaCalend=<? echo $F[2]?>';
				</script>        
			<?	
			}
		}
		else{
			$cons="select nombre from contratacionsalud.cups where compania='$Compania[0]' and codigo='$CupBloq'";
			$res=ExQuery($cons); $fila=ExFetch($res);?>
			<script language="javascript">
				alert("En este horario solo se puede asignar el cup "+"<? echo "$CupBloq - $fila[0]"?>");
			</script>
	<?	}
	}
	if($DeAfiliados){
		$Conectar=mysql_connect('localhost','root','Server*1492') or die ('no establecida');
		mysql_select_db("BDAfiliados", $Conectar);
		$Consul="select entidad from Afiliados where identificacion='$Cedula'";		
		$R=mysql_query($Consul,$Conectar);	
		if($R){
			$Result=mysql_fetch_row($R);
		}
		$Asegurador=$Result[0];
		//echo $Asegurador;		
	}
	
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" src="/Funciones.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
function Cambio(){
	document.FORMA.Cambia.value=1;
	document.FORMA.submit();
}
function CargarxDefecto(Ced){
	if(document.FORMA.Cedula.value==""){
		alert("Debe digitar el numero de la cedula!!!");
	}
	else{
		open('/Afiliados/cargartxt/verafiliados.php?DatNameSID=<? echo $DatNameSID?>&Documento='+Ced+'&PrimApe='+document.FORMA.PrimApe.value+'&SegApe='+document.FORMA.SegApe.value+'&PrimNom='+document.FORMA.PrimNom.value+'&SegNom='+document.FORMA.SegNom.value+'','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES,resizable=1')
	}
}
function VerficarCamb(CedPac)
{
	//if(document.FORMA.auxCambia.value==1){
		//document.FORMA.Cambia.value=1;
		//alert();
	//}
	document.FORMA.submit();
}
function ValidaDocumento(Objeto){
	frames.FrameOpener.location.href="/Admision/Agenda/ValidaDocumentoAgenda.php?DatNameSID=<? echo $DatNameSID?>&Cedula="+Objeto.value;
	document.getElementById('FrameOpener').style.position='absolute';
	document.getElementById('FrameOpener').style.top='90px';
	document.getElementById('FrameOpener').style.left='325px';
	document.getElementById('FrameOpener').style.display='';
	document.getElementById('FrameOpener').style.width='400';
	document.getElementById('FrameOpener').style.height='390';
}

function CitaxReasignar(){
	frames.FrameOpener.location.href="/Admision/Agenda/CitaxReasignarAgenda.php?DatNameSID=<? echo $DatNameSID?>&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&Fecha=<? echo $Fecha?>&HrIni=<? echo $HrIni?>&MinIni=<? echo $MinIni?>&Tiempo=<? echo $Tiempo?>&HrFin=<? echo $HrFin?>&MinFin=<? echo $MinFin?>";
	document.getElementById('FrameOpener').style.position='absolute';
	document.getElementById('FrameOpener').style.top='90px';
	document.getElementById('FrameOpener').style.left='50px';
	document.getElementById('FrameOpener').style.display='';
	document.getElementById('FrameOpener').style.width='1120';
	document.getElementById('FrameOpener').style.height='390';
}
function Validar(){ 	
	
	if(document.FORMA.Procedimiento.value==''||document.FORMA.TiemProced.value==''||document.FORMA.Asegurador.value==''||document.FORMA.Contrato.value==''||document.FORMA.Nocontrato.value==''||document.FORMA.Cedula.value==''||document.FORMA.AnioNac.value==''||document.FORMA.MesNac.value==''||document.FORMA.DiaNac.value==''||document.FORMA.PrimApe.value==''||document.FORMA.PrimNom.value==''||document.FORMA.Telefono.value==''||document.FORMA.FechaSolicita.value==""){
		alert("No deben quedar espacios en blanco");return false;
	}
	else{
		document.FORMA.submit();
	}
	
}
</script>
</head>
<?php
		$consdesh="select cedula,estado from salud.pacientesxpabellones where cedula='$Cedula' and estado='AC'";
		//echo $consdesh;
		$resdesh=ExQuery($consdesh);
		$alerta="";
		$boton="";
		while($filadesh=ExFetch($resdesh)){
			$alerta='onLoad="javascript:alert('."'".'El paciente con cédula No. '.$Cedula.' se encuentra actualmente hospitalizado'."'".');"';
			$boton='disabled="true"';
		}
?>
<body background="/Imgs/Fondo.jpg" <?php echo $alerta;?>>
<form name="FORMA" method="post" onSubmit="return Validar()" ><!-- onSubmit="return Validar()"-->
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"/>
<? if($YaEsta==''){$YaEsta=0;}
$cons="select fecha from salud.agenda where compania='$Compania[0]' and cedula='$Cedula' and estado='Cancelada'";
$res=ExQuery($cons);
$fila=ExFetch($res);
if($fila){?>
	<font color="#FF0000">ESTE PACIENTE TIENE CITAS PREVIAS CANCELADAS</font><?
}?>
<input type="hidden" name="YaEsta" value="<? echo $YaEsta?>">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4"> 
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Cedula</td>
   <?   if($CedDef){?>
   		   <td><input type="text" name="Cedula" value="<? echo $Cedula?>" readonly>
    <?  }
		else{?>
        	<td><input type="text" name="Cedula" value="<? echo $Cedula?>"  onFocus="ValidaDocumento(this)"  onKeyUp="ValidaDocumento(this);xLetra(this)" onKeyDown="xLetra(this)">
   <?	}
   		if($Cedula){?>
   			<button title="Ver Historico de Citas"
        	onClick="open('HistoricoCitas.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $Cedula?>','','left=10,top=10,width=790,height=600,menubar=yes,scrollbars=YES,resizable=1')">
        		<img src="/Imgs/b_tblexport.png">
       		</button>
  	<?	}?>
   		</td>        
        <td bgcolor="#e5e5e5" align="center" style="font-weight:bold">Fecha de Nacimiento</td>
        <td>
        <?
		$cons="Select Edad from Salud.EdadMinima where Compania='$Compania[0]'";
		$res=ExQuery($cons);
		$fila=ExFetch($res);?>
        <select name="AnioNac"><option></option>
    <?
		$EdadMinima=$fila[0];		
    	for($i=$ND[year]-$EdadMinima;$i>=($ND[year]-100);$i--)
		{
			if($AnioNac==$i){echo "<option selected value='$i'>$i</option>";}
			else{echo "<option value='$i'>$i</option>";}
		}
		?>
    	</select>-
	    <select name="MesNac"><option></option>
    	<?
    		for($i=1;$i<=12;$i++)
			{	
				if($MesNac==$i){echo "<option selected value=$i>".$NombreMesC[$i]."</option>";}
				else{echo "<option value=$i>".$NombreMesC[$i]."</option>";}
			}
		?>
	    </select>-
    	<select name="DiaNac"><option></option>
	    <?
    		for($i=1;$i<=31;$i++)
			{
				if($DiaNac==$i){echo "<option selected value='$i'>$i</option>";}
				else{echo "<option value='$i'>$i</option>";}
			}
		?>
    	</select>
      	</td>
    </tr>    
	<tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Primer Apellido</td><td><input type="text" name="PrimApe" onKeyUp="ExLetra(this)" onKeyDown="ExLetra(this)" value="<? echo $PrimApe?>"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Segundo Apellido</td><td><input type="text" name="SegApe" onKeyUp="ExLetra(this)" onKeyDown="ExLetra(this)" value="<? echo $SegApe?>"></td>
 	</tr>
    <tr>       
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Primer Nombre</td><td><input type="text" name="PrimNom" onKeyUp="ExLetra(this)" onKeyDown="ExLetra(this)" value="<? echo $PrimNom?>"></td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Segundo Nombre</td><td><input type="text" name="SegNom" onKeyUp="ExLetra(this)" onKeyDown="ExLetra(this)" value="<? echo $SegNom?>"></td>
    </tr>
    <tr>
    	<td  bgcolor="#e5e5e5" style="font-weight:bold" align="center">Telefono</td><td><input type="text" onBlur="campoNumero(this)" name="Telefono" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" value="<? echo $Telefono?>"></td>    
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Fecha Deseada</td>
        <td><input type="Text" name="FechaSolicita"  readonly onClick="popUpCalendar(this, FORMA.FechaSolicita, 'yyyy-mm-dd')" 
        	value="<? echo $FechaSolicita?>"></td>               
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" align="center" style="font-weight:bold">Asegurador</td>
	      	<td colspan="4"><? 
			$cons="Select identificacion,(primape || ' ' || segape || ' ' || primnom || ' ' || segnom) as Nombre  from Central.Terceros where Tipo='Asegurador' and Compania='$Compania[0]' 			  			order by primape";
			$res=ExQuery($cons);?>
    	    <select name="Asegurador" onChange="VerficarCamb()"><option></option>
		<?	while($fila=ExFetch($res))
			{	           
				if($fila[0]==$Asegurador){
					echo "<option selected value='$fila[0]'>$fila[1]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[1]</option>";
				}?>
		<?	}?>        	
            </select>
      	<?	if($Cedula){?>
        		<input type="hidden" name="DeAfiliados">
                <button onClick="CargarxDefecto(document.FORMA.Cedula.value)" title="Buscar Asegurador por defecto">
	                <img src="/Imgs/b_search.png" />        		
              	</button>
                
		<?	}?>
        </td>
    </tr>
    
    <tr>
    <td bgcolor="#e5e5e5" style="font-weight:bold">Contrato</td>
    <td><select name="Contrato" onChange="document.FORMA.submit()"><option></option>
         <?	$cons="select contrato from contratacionsalud.contratos where compania='$Compania[0]' and estado='AC' and Entidad='$Asegurador' Group By Contrato order by contrato"; 
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{	if(!$Contrato){$Contrato=$fila[0];}

			if($Contrato==$fila[0]){
					echo "<option selected value='$fila[0]'>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
		}
		?>
        </select></td>
		<td bgcolor="#e5e5e5" style="font-weight:bold" align="center">No. Contrato</td>
        <td><select name="Nocontrato" onChange="document.FORMA.submit();"><option></option>
        <?	$cons="select numero from contratacionsalud.contratos where compania='$Compania[0]' and estado='AC' and Entidad='$Asegurador' and Contrato='$Contrato'
		and fechaini<='$ND[year]-$ND[mon]-$ND[mday]' and (fechafin>='$ND[year]-$ND[mon]-$ND[mday]' or fechafin is null) order by numero"; 
		$res=ExQuery($cons);
		while($fila=ExFetch($res))
		{
			if($Nocontrato==$fila[0]){
					echo "<option selected value='$fila[0]'>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
		}
		?>
        </select></td>
    </tr>
	<tr>
    	<td bgcolor="#e5e5e5" align="center" style="font-weight:bold" >Procedimiento</td>
    	<td><? 
			$consPlan="select planbeneficios,plantarifario from contratacionsalud.contratos where entidad='$Asegurador' and contrato='$Contrato' and numero='$Nocontrato' 
			and compania='$Compania[0]'";
			$resPlan=ExQuery($consPlan);
			$filaPlan=ExFetch($resPlan);
			if(!$filaPlan[0]){$filaPlan[0]="-0";}
			//echo $consPlan;
			$cons="select nombre,cupsxconsulextern.codigo,timeconsulsuge from contratacionsalud.cupsxconsulextern,contratacionsalud.cups 
			where cupsxconsulextern.codigo=cups.codigo and cupsxconsulextern.compania='$Compania[0]' and cups.compania='$Compania[0]' and cargo='$Especialidad'
			and  cupsxconsulextern.codigo in (select cup from contratacionsalud.cupsxplanservic where autoid='$filaPlan[0]' and contratacionsalud.cupsxplanservic.compania='$Compania[0]')
			order by nombre";
			//echo $cons;
			$res=ExQuery($cons);echo ExError();?>
        	<select name="Procedimiento" onChange="Cambio()" style=" width: 500px"><option></option>
        <?	while($fila = ExFetchArray($res)){
				if($fila[1]==$Procedimiento){
					echo "<option value='$fila[1]' selected title='$fila[0]'>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[1]'  title='$fila[0]'>$fila[0]</option>";
				}
			} ?>
            <select> 
         </td> 
        </td>    
    	<td bgcolor="#e5e5e5" align="center" style="font-weight:bold">Tiempo</td> 
		     <td> 
		<? 
			$cons="select timeconsulsuge from contratacionsalud.cupsxconsulextern where compania='$Compania[0]' and codigo='$Procedimiento'";
			$res=ExQuery($cons);echo ExError();	
			$fila = ExFetchArray($res);
			$time=((($HrFin-$HrIni)*60)-$MinIni)+$MinFin;	
				//echo "(((HrFin($HrFin)-HrIni($HrIni) )* 60 - MinIni($MinIni))+MinFin($MinFin)=time=$time";
			//echo "Tiempo=$Tiempo,HrIni=$HrIni,MinIni=$MinIni,HrFin=$HrFin,MinFin=$MinFin,time=$time\n<br>";	
			$cons2="Select hrsini,minsini,hrsfin,minsfin,cedula,primape,segape,primnom,segnom,telefono,entidad,estado,tiempocons from central.terceros,salud.agenda where
            terceros.identificacion=agenda.cedula and estado!='Cancelada' and medico='$Profecional' and fecha='$Fecha' and agenda.compania='$Compania[0]' 
			and terceros.compania='$Compania[0]'";
			$res2=ExQuery($cons2);echo ExError();//consulta de la agenda
			$stop=0;
			while($fila2=ExFetchArray($res2)){		
				//$fila2=ExFetchArray($res2);
				//echo "fila2[0]=$fila2[0],fila2[1]=$fila2[1]";
				$HI=$HrIni;$MI=$MinIni;
				for($i=0;$i<$time;$i=$i+$TiempInt){
					$MI=$MI+$TiempInt;
					if($MI==(60-$TiempInt)){
						$HF++;$MF=0;
					}else{
						if($MI==60){
							$HI++;$MI=0;
						}
						$MF=$MF+$TiempInt;
					}	
					if($stop==0){
						if($HI==$fila2[0]){
							if($MI==$fila2[1]){
								$HoraF=$HI;$MinF=$MI;
								//echo "<br>\nHoraF=$HoraF,HI=$HI,Minf=$MinF,MI=$MI";
								$stop=1;							
							}
						}
					}						
				}
			}			
			if($HoraF!=''){				
				$tim=((($HoraF-$HrIni)*60)-$MinIni)+$MinF;					
				//echo "tim=((($HoraF-$HrIni)*60)-$MinIni)+$MinF=";
				//echo $tim;					
				if($tim>=60){
					$fin=60;
				}
				else{
					$fin=$tim;
				}
			}
			else{
					
				if($tim<=60){
					$fin=$time;
				}
				else{
					$fin=60;
				}
			}			
			if($fin>60){$fin=60;}
			//echo $Cambia;?>            
        	<select name="TiemProced">  
        <?	if($TimConsulta!=''){
				echo "<option value='$TimConsulta' selected>$TimConsulta</option>";
			}
			else{
				for($i=$TiempInt;$i<$fin+$TiempInt;$i=$i+$TiempInt){
					if($Procedimiento!=''){
						/*if($TiemProced==$i&&$Cambia==0){
							echo "<option value='$i' selected>$i</option>";
						}
						else{						
							if($fila[0]==$i){
								echo "<option value='$i' selected>$i</option>";
							}
							else{
								echo "<option value='$i'>$i</option>";
							}
						}*/
						if($Cambia){
							if($fila[0]==$i){
								echo "<option value='$i' selected>$i</option>";
							}
							else{
								echo "<option value='$i'>$i</option>";
							}
						}
						else{
							if($TiemProced==$i){
								echo "<option value='$i' selected>$i</option>";
							}
							else{
								echo "<option value='$i'>$i</option>";
							}
						}
					}
				}
			}?>
            </select>     
	     </td>      
	</tr>
    <tr> <td bgcolor="#e5e5e5" style="font-weight:bold" align="center">Solicitada por</td><td><input type="text" name="solicitadapor" SIZE='78' value="<? echo $solicitadapor?>"></td></tr>

    
    <tr>
    	<td align="center" colspan="4">
      	  <input type="submit" value="Guardar" name="Guardar" <?php echo $boton ?>>
          <input type="button" value="Cancelar" onClick="location.href='ConfAgendMed.php?DatNameSID=<? echo $DatNameSID?>&Especialidad=<? echo $Especialidad?>&Profecional=<? echo $Profecional?>&AnioCalend=<? echo $F[0]?>&MesCalend=<? echo $F[1]?>&DiaCalend=<? echo $F[2]?>'">
          <input type="button" value="Cita Reasignada" onClick="CitaxReasignar()"></td>
    </tr>        
</table>
	<script language="javascript">
		if(<? echo $YaEsta?>==1){alert("El paciente ya tiene una cita asignada en este horario!!!");$YaEsta=0;}
	</script>
<input type="hidden" name="Profecional" value="<? echo $Profecional?>">
<input type="hidden" name="Especialidad" value="<? echo $Especialidad?>">
<input type="hidden" name="Cambia" value="0">
<input type="hidden" name="Reasignar" value="<? echo $Reasignar?>">
<input type="hidden" name="Fecha" value="<? echo $Fecha?>">
<input type="hidden" name="CedDef" value="<? echo $CedDef?>">
<input type="hidden" name="HrIR" value="<? echo $HrIR?>">
<input type="hidden" name="MinIR" value="<? echo $MinIR?>">
<input type="hidden" name="FechR" value="<? echo $FechR?>">
<input type="hidden" name="Ban" value="<? echo $Ban?>">
<input type="hidden" name="Id" value="<? echo $Id?>">
<input type="hidden" name="TimConsulta" value="<? echo $TimConsulta?>">
<input type="hidden" name="CupBloq" value="<? echo $CupBloq?>">

</form>
<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge">
<iframe id="FrameOpener2" name="FrameOpener2" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge">
</body>
</html>
