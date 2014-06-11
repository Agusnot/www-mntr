<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	@require_once ("xajax/xajax_core/xajax.inc.php");
	//----- funcion actualizar
//	echo $Numero."---".$Identificacion."<br>"; 
	function ActualizaCargo($Vinc)
	{		
		$respuesta=new xajaxResponse();
		$respuesta->addScript("alert('Respondo!');");
		return $respuesta->getXML();
	}
	$obj=new xajax();
	$obj->configure('javascript URI','xajax/');//
	$obj->registerFunction("ActualizaCargo");
//	echo $New;
	if($New)
	{
		if(!$NContrato)
		{
			$cons="select numero from nomina.contratos where compania='$Compania[0]' order by numero desc";
//			echo $cons;
			$res=ExQuery($cons);$fila=ExFetch($res);
			if($fila){$Numero=$fila[0]+1;}else{$Numero=1;}
//			echo $fila[0];	
//			echo $Numero;
			$NContrato=$Numero;
		}
		else
		{
//			echo "Nuew2";
			$Numero=$NContrato;
		}
	}
	if($Guardar)
	{
		if(strlen($MesIni)==1){ $MesIni="0$MesIni";}
		if(strlen($DiaIni)==1){ $DiaIni="0$DiaIni";}
		$FecInicio= $AnioIni."-".$MesIni."-".$DiaIni;
		if($con==0)
		{
			if($TipoContrato=="Indefinido")
			{
				$cons1="select numero from nomina.contratos where compania='$Compania[0]' and identificacion='$Identificacion' and fecinicio='$FecInicio' ";
				$res1=ExQuery($cons1);
				$cont=ExNumRows($res1);
				if($cont==0)
				{
					$cons="insert into nomina.contratos(compania,identificacion,tipocontrato,estado,tipovinculacion,fecinicio,fecfin,
					cargo,seccion,grupo,hrslab,jornflexible,pactocolectivo,alimentos,cuenta,banco,numero,usuario) values
					('$Compania[0]','$Identificacion','$TipoContrato','$Estado','$TipoVinculacion','$FecInicio',NULL,'$Cargo',
					 '$Seccion','$Grupo','$HorLab','$Jornada','$PaColectivo','$Alimentacion','$Cuenta','$Banco','$Numero','$usuario[1]')";
					 $res=ExQuery($cons);
//					 echo $cons;
				}
				else
				{
					?>
					<script>alert("Ya Existe un Contrato para este Rango de Fecha")</script>
					<?		
				}
			}
			elseif($TipoContrato=="Fijo")
			{
				$cons1="select numero from nomina.contratos where compania='$Compania[0]' and identificacion='$Identificacion' and fecinicio='$FecInicio' and fecfin='$FecFin' ";
				$res1=ExQuery($cons1);
				$cont=ExNumRows($res1);
				if($cont==0)
				{
					$cons="insert into nomina.contratos(compania,identificacion,tipocontrato,estado,tipovinculacion,fecinicio,fecfin,
					cargo,seccion,grupo,hrslab,jornflexible,pactocolectivo,alimentos,cuenta,banco,numero,usuario) values
					('$Compania[0]','$Identificacion','$TipoContrato','$Estado','$TipoVinculacion','$FecInicio','$FecFin','$Cargo',
					 '$Seccion','$Grupo','$HorLab','$Jornada','$PaColectivo','$Alimentacion','$Cuenta','$Banco','$Numero','$usuario[1]')";
					 $res=ExQuery($cons);
//					 echo $cons;
					$AnioFin=substr($FecFin,0,4);
					$MesFin=substr($FecFin,5,2);
					$DiaFin=substr($FecFin,8,2);
					$cons2="select concepto from nomina.conceptosliquidacion,nomina.tiposvinculacion where conceptosliquidacion.compania='$Compania[0]' and 
					tiposvinculacion.compania=conceptosliquidacion.compania and diastr='1' and tiposvinculacion.codigo='$TipoVinculacion' and 
					conceptosliquidacion.tipovinculacion=tiposvinculacion.tipovinculacion";
//					echo $cons2;
					$res2=ExQuery($cons2);
					$fila2=ExFetch($res2);
					$cons="insert into nomina.diastrab(compania,identificacion,diastr,anio,mestr,concepto,vinculacion,numero) 
					values('$Compania[0]','$Identificacion','$DiaFin','$AnioFin','$MesFin','$fila2[0]','$TipoVinculacion','$Numero')";
//					echo $cons."<br>";
					$res=ExQuery($cons);
				}
				else
				{
					?>
					<script>alert("Ya Existe un Contrato para este Rango de Fecha")</script>
					<?		
				}
				
			}
			if($DiaIni!=01)
			{
				$DiasT=31-$DiaIni;
				$cons2="select concepto from nomina.conceptosliquidacion,nomina.tiposvinculacion where conceptosliquidacion.compania='$Compania[0]' and
				tiposvinculacion.compania=conceptosliquidacion.compania and diastr='1' and tiposvinculacion.codigo='$TipoVinculacion' and
				conceptosliquidacion.tipovinculacion=tiposvinculacion.tipovinculacion";
				$res2=ExQuery($cons2);
				$fila2=ExFetch($res2);
				$cons="insert into nomina.diastrab(compania,identificacion,diastr,anio,mestr,concepto,vinculacion,numero)
				values('$Compania[0]','$Identificacion','$DiasT','$AnioIni','$MesIni','$fila2[0]','$TipoVinculacion','$Numero')";
				$res=ExQuery($cons);
//				echo $cons;
			}
		}
		else
		{
			$cons="select numero from nomina.contratos where compania='$Compania[0]' and numero='$NContrato'";
			$res=ExQuery($cons);
			$cont=ExNumRows($res);
//			echo $cont;
//			echo $NContrato."<--".$NContAnt."<--<br>";
			if($NContrato!=$NContAnt)
			{
				if($cont==0)
				{
					if($TipoContrato=="Indefinido")
					{
						$cons="update nomina.contratos set tipocontrato='$TipoContrato',estado='$Estado',tipovinculacion='$TipoVinculacion',
						fecinicio='$FecInicio',fecfin=NULL,cargo='$Cargo',seccion='$Seccion',grupo='$Grupo',hrslab='$HorLab',jornflexible='$Jornada',
						pactocolectivo='$PaColectivo',alimentos='$Alimentacion',cuenta='$Cuenta',banco='$Banco',numero='$NContrato' where compania='$Compania[0]' numero='$NContAnt'";
//						echo $cons;
						$res=ExQuery($cons);
					}
					elseif($TipoContrato=="Fijo")
					{
						$cons="update nomina.contratos set tipocontrato='$TipoContrato',estado='$Estado',tipovinculacion='$TipoVinculacion',
						fecinicio='$FecInicio',fecfin='$FecFin',cargo='$Cargo',seccion='$Seccion',grupo='$Grupo',hrslab='$HorLab',jornflexible='$Jornada',
						pactocolectivo='$PaColectivo',alimentos='$Alimentacion',cuenta='$Cuenta',banco='$Banco',numero='$NContrato' where compania='$Compania[0]' and numero='$NContAnt'";
//						echo $cons;
						$res=ExQuery($cons);
					}
				}
				else
				{
					?>
					<script>alert("Este Numero de Contrato Ya Existe !!!")</script>
					<?
					$NContrato=$NContAnt;
				}
			}
			else
			{
				if($TipoContrato=="Indefinido")
				{
					$cons="update nomina.contratos set tipocontrato='$TipoContrato',estado='$Estado',tipovinculacion='$TipoVinculacion',
					fecinicio='$FecInicio',fecfin=NULL,cargo='$Cargo',seccion='$Seccion',grupo='$Grupo',hrslab='$HorLab',jornflexible='$Jornada',
					pactocolectivo='$PaColectivo',alimentos='$Alimentacion',cuenta='$Cuenta',banco='$Banco',numero='$NContrato' where compania='$Compania[0]' and numero='$NContAnt'";
//					echo $cons;
					$res=ExQuery($cons);
				}
				elseif($TipoContrato=="Fijo")
				{
					$cons="update nomina.contratos set tipocontrato='$TipoContrato',estado='$Estado',tipovinculacion='$TipoVinculacion',
					fecinicio='$FecInicio',fecfin='$FecFin',cargo='$Cargo',seccion='$Seccion',grupo='$Grupo',hrslab='$HorLab',jornflexible='$Jornada',
					pactocolectivo='$PaColectivo',alimentos='$Alimentacion',cuenta='$Cuenta',banco='$Banco',numero='$NContrato' where compania='$Compania[0]' numero='$NContAnt'";
//					echo $cons;
					$res=ExQuery($cons);
				}
        	}
		}
	}
	if($Editar==1)
	{
		$cons="select identificacion,tipocontrato,estado,tipovinculacion,fecinicio,fecfin,cargo,seccion,grupo,hrslab,jornflexible,pactocolectivo,alimentos,cuenta,banco,numero,motivo
		 from nomina.contratos where compania='$Compania[0]' and identificacion='$Identificacion' and numero='$Numero'";
		$res=ExQuery($cons);
		$con=ExNumRows($res);
		//echo $con;
		$fila=ExFetch($res);
		//echo $cons;
		//$NContAnt=$fila[15];
		if(!$TipoContrato){$TipoContrato=$fila[1];}
		if(!$Estado){$Estado=$fila[2];}
		if(!$TipoVinculacion){$TipoVinculacion=$fila[3];}
		if(!$AnioIni){$AnioIni=substr($fila[4],0,4);}
		if(!$MesIni){$MesIni=substr($fila[4],5,2);}
		if(!$DiaIni){$DiaIni=substr($fila[4],8,2);}
		if(!$FecFin){$FecFin=$fila[5];}
		if(!$Cargo){$Cargo=$fila[6];}
		if(!$Seccion){$Seccion=$fila[7];}
		if(!$Grupo){$Grupo=$fila[8];}
		if(!$HorLab){$HorLab=$fila[9];}
		if(!$Jornada){$Jornada=$fila[10];}
		if(!$PaColectivo){$PaColectivo=$fila[11];}
		if(!$Alimentacion){$Alimentacion=$fila[12];}
		if(!$Cuenta){$Cuenta=$fila[13];}
		if(!$Banco){$Banco=$fila[14];}
		if(!$NContrato){$NContrato=$fila[15];}
		if(!$Motivo){$Motivo=$fila[16];}
	}
	if(!$HorLab){$HorLab=8;}	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="/calendario/Calendar/calendar-win2k-cold-1.css" title="win2k-cold-1"/>
<? $obj->printJavascript("/xajax");?>
<script language="javascript" src="/Funciones.js"></script>
<script type="text/javascript" src="/calendario/Calendar/calendar.js"></script>
<script type="text/javascript" src="/calendario/Calendar/calendar-es.js"></script>
<script type="text/javascript" src="/calendario/Calendar/calendar-setup.js"></script>
<script language='javascript' src="/calendario/popcalendar.js"></script>
<script language="javascript">
function FinalCont(Valor)
{
	frames.FrameOpener.location.href="FinalizarContra.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Numero=<? echo $Numero?>&FecFin=<? echo $FecFin?>&FecIni=<? echo $fila[4]?>&TipoVinculacion=<? echo $TipoVinculacion?>&TipoContrato=<? echo $TipoContrato?>";
	document.getElementById('FrameOpener').style.position='absolute';
	document.getElementById('FrameOpener').style.top='10px';
	document.getElementById('FrameOpener').style.right='10px';
	document.getElementById('FrameOpener').style.display='';
	document.getElementById('FrameOpener').style.width='450px';
	document.getElementById('FrameOpener').style.height='300px';
}

function ConfigCal(Campo)
{
	//alert(Campo.name)
	Calendar.setup({
	inputField     :    Campo.name, 	      
	ifFormat       :    "%Y-%m-%d",       
	showsTime      :    true,            
	//button         :    "calendario",   
	singleClick    :    false,           
	step           :    1                
	});	
}

function Validar()
{
   if(document.FORMA.TipoContrato.value==""){alert("Por favor ingrese el Tipo de Contrato !!!");return false;}
   if(document.FORMA.Estado.value==""){alert("Por favor ingrese el Estado !!!");return false;}
   if(document.FORMA.TipoVinculacion.value==""){alert("Por favor ingrese el Tipo de Vinculacion !!!"); return false;}
   if(document.FORMA.Cargo.value==""){alert("Por favor ingrese el Cargo !!!");return false;}
   //if(document.FORMA.Seccion.value==""){alert("Por favor ingrese la Seccion !!!");return false;}
   //if(document.FORMA.Grupo.value==""){alert("Por favor ingrese el Grupo !!!");return false;}   
   if(document.FORMA.AnioIni.value==""){alert("Por favor ingrese la Fecha de Inicio !!!");return false;}
   if(document.FORMA.MesIni.value==""){alert("Por favor ingrese la Fecha de Inicio !!!");return false;}
   if(document.FORMA.DiaIni.value==""){alert("Por favor ingrese la Fecha de Inicio !!!");return false;}
   if(document.FORMA.TipoContrato.value=="Fijo")
   {
   if(document.FORMA.FecFin.value==""){alert("Por favor ingrese la Fecha de Finalizacion !!!");return false;}   
   }
   if(document.FORMA.HorLab.value==""){alert("Por favor ingrese el Numero de Horas Laborales Diarias !!!");return false;}
   //if(document.FORMA.Jornada.value==""){alert("Por favor ingrese el Tipo de Jornada !!!");return false;}   
   //if(document.FORMA.PaColectivo.value==""){alert("Por favor ingrese el Pacto Colectivo !!!");return false;}
   //if(document.FORMA.Alimentacion.value==""){alert("Por favor ingrese la Alimentacion !!!");return false;}
   if(document.FORMA.Cuenta.value==""){alert("Por favor ingrese el Numero de Cuenta !!!");return false;}
   if(document.FORMA.Banco.value==""){alert("Por favor ingrese el Banco al cual pertenece la Cuenta !!!");return false;}
}
function CambiarSrc(Valor)
{
	if(Valor == "EPS")
	{document.getElementById('Info').src="Empresas.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NContrato?>&Opc=EPS&FecIni=<? echo $FecInicio?>";}
	if(Valor == "Cesantias")
	{document.getElementById('Info').src="Empresas.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NContrato?>&Opc=Cesantias&FecIni=<? echo $FecInicio?>";}
	if(Valor == "Pensiones")
	{document.getElementById('Info').src="Empresas.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NContrato?>&Opc=Pensiones&FecIni=<? echo $FecInicio?>";}
	if(Valor == "ARP")
	{document.getElementById('Info').src="ARPF.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NContrato?>&FecIni=<? echo $FecInicio?>";}	
	if(Valor == "Centro de Costos")
	{document.getElementById('Info').src="CentroCostos.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NContrato?>&FecIni=<? echo $FecInicio?>";}
	if(Valor == "Salarios")
	{document.getElementById('Info').src="Salarios.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&NumContrato=<? echo $NContrato?>&FecIni=<? echo $FecInicio?>";}	
	if(Valor == "")
	{document.getElementById('Info').src="about:blank";}			
}
function validarvalor(Valor)
{	
	var Fec = Valor.value.split('-');
	if(Fec[0]>9999)
	{
		alert("El Año no debe ser mayor de 9999");
		document.FORMA.FecInicio.focus();
	}
	if(Fec[1]>12)
	{
		alert("El Mes no debe ser mayor de 12");
		document.FORMA.FecInicio.focus();
	}
	if(Fec[2]>31)
	{
		alert("El Dia no debe ser mayor de 31");
		document.FORMA.FecInicio.focus();
	}
	
}

</script>
<script language="javascript" src="/calendario/popcalendar.js"></script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar();">
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
    <input type="hidden" name="PermiteCrear" value="<? echo $PermiteCrear?>">
    <input type="hidden" name="con" value="<? echo $con?>">
    <input type="hidden" name="NContAnt" value="<? echo $NContrato?>">
    <input type="hidden" name="FecFinA" value="<? echo $FecFin?>" >
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center" >
    	<tr>
		<td colspan="8" bgcolor="#666699" style="color:white" align="center" >DATOS DEL CONTRATO</td>
        </tr>
        <tr>
        	<td colspan="2">Numero de Contrato</td>
            <td colspan="2"><input type="text" name="NContrato" value="<? echo $NContrato?>" style="width:100%" maxlength="100" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" <? if($Nuevo==1){echo "disabled";}?>/></td>
            <td>Tipo de Contrato</td>
            <td colspan="3"><select name="TipoContrato"  style="width:100%" onChange="FORMA.submit()" <? if($Nuevo==1){echo "disabled";}?> />
            <option></option>
            <?
            	$cons = "select tipo from nomina.tipocontrato where compania='$Compania[0]'";
                $resultado = ExQuery($cons);
                while ($fila = ExFetch($resultado))
                {                        
					if($fila[0]==$TipoContrato)
					{
						echo "<option value='$fila[0]' selected>$fila[0]</option>"; 
					}
					else{echo "<option value='$fila[0]'>$fila[0]</option>";}						 
                }
//					echo $Mes;
				?>
            </select></td>
            </tr>
        	<tr>
            <td>Estado</td>
            <td><select name="Estado"   style="width:100%" <? if($Nuevo==1){echo "disabled";}?> />
            		<option value="Activo" <? if($Estado=="Activo"){echo "selected";}?>>Activo</option>
                    <option value="Inactivo" <? if($Estado=="Inactivo"){echo "selected";}?>>Inactivo</option>
                    </select>
            </td>
            <td>Tipo Vinculacion</td>
            <td><select name="TipoVinculacion" onChange="xajax_ActualizaCargo(this.value);BuscaCargo.location.href='BuscarCargo.php?Vin='+this.value+'&DatNameSID=<? echo $DatNameSID?>'" <? if($Nuevo==1){echo "disabled";}?> />
            	<option></option>
                <?
				$cons="select codigo,tipovinculacion from nomina.tiposvinculacion where compania='$Compania[0]'";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{
					if($fila[0]==$TipoVinculacion)
					{
						echo "<option value='$fila[0]' selected>$fila[1]</option>";
					}
					else
					{ echo "<option value='$fila[0]'>$fila[1]</option>";}
				}?>
                </select></td>
        	<td>Cargo</td>
            <td colspan="3"><select name="Cargo" onChange="FORMA.submit()" style="width:auto" <? if($Nuevo==1){echo "disabled";}?> />
            	<option></option>
                <?
				$cons="select cargos.codigo,cargos.cargo from nomina.cargos,nomina.tiposvinculacion where cargos.compania='$Compania[0]' and tiposvinculacion.compania=cargos.compania and cargos.vinculacion='$TipoVinculacion' and tiposvinculacion.codigo=cargos.vinculacion order by cargos.cargo";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{
					if($fila[0]==$Cargo)
					{
						echo "<option value='$fila[0]' selected>$fila[1]</option>";
					}
					else
					{
						echo "<option value='$fila[0]'>$fila[1]</option>";
					}
				}
                ?></select></td>
		</tr>
        <tr>        
			<td>Seccion</td>
        	<td><select name="Seccion"  style="width:100%" <? if($Nuevo==1){echo "disabled";}?> />
            	<option></option>
                <?
                $cons="select codigo,seccion from nomina.secciones where compania='$Compania[0]' order by seccion";
				$res=ExQuery($cons);
				while($fila=Exfetch($res))
				{
					if($fila[1]==$Seccion)
					{
						echo "<option value='$fila[1]' selected>$fila[1]</option>";
					}
					else
					{
						echo "<option value='$fila[1]'>$fila[1]</option>";
					}
				}
				?></select></td>
            <td>Grupo</td>
            <td><select name="Grupo"  style="width:100%" <? if($Nuevo==1){echo "disabled";}?> />
            	<option></option>
                <?
                $cons="select codigo,grupo from nomina.grupos where compania='$Compania[0]'";
				$res=ExQuery($cons);
				while($fila=Exfetch($res))
				{
					if($fila[0]==$Grupo)
					{
						echo "<option value='$fila[0]' selected>$fila[1]</option>";
					}
					else
					{
						echo "<option value='$fila[0]'>$fila[1]</option>";
					}
				}
				?>
                </select>
            </td>
        	<td>Fecha Inicio<br><font color="#000099" size="-4">AAAA-MM-DD</font></td>
        	<td><select name="AnioIni" style="width:55px" onChange="FORMA.submit()">
            	<option></option>
                <?
                    $cons = "select Anio from central.anios where compania='$Compania[0]' order by anio desc";
                    $resultado = ExQuery($cons);
                    while ($fila = ExFetch($resultado))
                    {                        
						if($AnioIni==$fila[0])
                        {
                            echo "<option value='$fila[0]' selected>$fila[0]</option>";
                        }
                        else
                        {
                            echo "<option value='$fila[0]'>$fila[0]</option>";
                        }
                    }
				?>
                </select>
            </td>
            <td><select name="MesIni" style="width:100px" onChange="FORMA.submit()">
            	<option></option>
                <?
                    $cons = "select numero,mes,numdias from central.meses order by numero asc";
                    $resultado = ExQuery($cons);
                    while ($filaM = ExFetch($resultado))
                    {                        
						if($MesIni==$filaM[0])
                        {
                            echo "<option value='$filaM[0]' selected>$filaM[1]</option>";
							$dias=$filaM[2];
                        }
                        else
                        {
                            echo "<option value='$filaM[0]'>$filaM[1]</option>";
                        }
                    }
				?>
                </select>
            </td>
            <td><select name="DiaIni" style="width:50px">
            <option></option>
            <?
			$I=1;
			while($I<=$dias)
			{
				if($DiaIni==$I)
				{
					echo "<option value='$I' selected>$I</option>";
				}
				else
				{
					echo "<option value='$I'>$I</option>";
				}
				$I++;
			}
			?>
            </select>
            </td>
        </tr>
        <tr>
            <td>Fecha Fin<br><font color="#000099" size="-4">AAAA-MM-DD</font></td>
            <td><input type="text" name="FecFin" value="<? echo $FecFin?>" onClick="popUpCalendar(this,this,'yyyy-mm-dd')" maxlength="10" readonly <? if($TipoContrato=="Indefinido"){echo "Disabled";}?> <? if($Nuevo==1){echo "disabled";}?> /></td>
            <td>Horas Laboradas X dia</td>
            <td><select  name="HorLab"  <? if($Nuevo==1){echo "disabled";}?> />
            	<option value="1" <? if($HorLab=="1"){echo "selected";}?>>1</option>
            	<option value="2" <? if($HorLab=="2"){echo "selected";}?>>2</option>
               	<option value="3" <? if($HorLab=="3"){echo "selected";}?>>3</option>
                <option value="4" <? if($HorLab=="4"){echo "selected";}?>>4</option>
                <option value="5" <? if($HorLab=="5"){echo "selected";}?>>5</option>
                <option value="6" <? if($HorLab=="6"){echo "selected";}?>>6</option>
                <option value="7" <? if($HorLab=="7"){echo "selected";}?>>7</option>
                <option value="8" <? if($HorLab=="8"){echo "selected";}?>>8</option>
                <option value="9" <? if($HorLab=="9"){echo "selected";}?>>9</option>
                <option value="10" <? if($HorLab=="10"){echo "selected";}?>>10</option>
                <option value="11" <? if($HorLab=="11"){echo "selected";}?>>11</option>
                <option value="12" <? if($HorLab=="12"){echo "selected";}?>>12</option>
                </select>
            </td>
	       	<td>Jornada Flexible</td>
            <td><select name="Jornada"  <? if($Nuevo==1){echo "disabled";}?> />
            	<option></option>
            	<option value="Si" <? if($Jornada=="Si"){echo "selected";}?>>Si</option>
                <option value="No" <? if($Jornada=="No"){echo "selected";}?>>No</option>
                <option value="NA" <? if($Jornada=="NA"){echo "selected";}?>>NA</option>
                </select>
            </td>
            <td>Pacto Colectivo</td>
            <td><select name="PaColectivo"  <? if($Nuevo==1){echo "disabled";}?> />
            	<option></option>
            	<option value="Si" <? if($PaColectivo=="Si"){echo "selected";}?>>Si</option>
                <option value="No" <? if($PaColectivo=="No"){echo "selected";}?>>No</option>
                <option value="NA" <? if($PaColectivo=="NA"){echo "selected";}?>>NA</option>
                </select>
            </td>
		</tr>
        <tr>           
            <td>Alimentacion</td>
            <td><select name="Alimentacion"  <? if($Nuevo==1){echo "disabled";}?> />
            	<option></option>
            	<option value="Si" <? if($Alimentacion=="Si"){echo "selected";}?>>Si</option>
                <option value="No" <? if($Alimentacion=="No"){echo "selected";}?>>No</option>
                <option value="NA" <? if($Alimentacion=="NA"){echo "selected";}?>>NA</option>
                </select>
            </td>
        	<td>Cuenta No.</td>
        	<td><input type="text" name="Cuenta" value="<? echo $Cuenta?>" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)" maxlength="80" style="width:100%" <? if($Nuevo==1){echo "disabled";}?> /></td>
            <td>Banco</td>
        	<td colspan="3"><select name="Banco"  style="width:100%" <? if($Nuevo==1){echo "disabled";}?> />
            	<option></option>
                <?
                $cons="select banco from nomina.bancos where compania='$Compania[0]'";
//				echo $cons;
				$res=ExQuery($cons);
				while($fila=Exfetch($res))
				{
					if($fila[0]==$Banco)
					{
						echo "<option value='$fila[0]' selected>$fila[0]</option>";
					}
					else
					{
						echo "<option value='$fila[0]'>$fila[0]</option>";
					}
				}
				
				?>
                </select></td>
        </tr> 
        <tr>
	      	<td colspan="8" bgcolor="#666699"style="color:white" align="center">Motivos de finalizacion de contrato</td>
        </tr>
        <tr>
        	<td colspan="8" ><textarea name="Motivo" style=" width:100%" rows="2" disabled><? echo $Motivo;?></textarea></td>
        </tr>
</table>
<center><input type="submit" value="Guardar" name="Guardar" <? if($Nuevo==1){echo "disabled";}?> ><!--<input type="button" name="Pdf" value="PDF" />-->
<input type="button" name="Finalizar" value="Finalizar" onClick="FinalCont(this)"/></center>
</form>
<table align="center" style="width:80%"  style='font : normal normal small-caps 12px Tahoma;' />
	<tr bordercolor="#e5e5e5" >
    	<td bgcolor="#666699" style='color:white; width:200px ' align="center" >Opciones</td>
    	<td align="left"><select name="Opciones" id="Opciones" onChange="CambiarSrc(this.value)" style="width:400px" <? if($Act==1){echo "disabled";}?> >
        		<option value=""></option>
            	<option <? if($Opciones=="EPS"){ echo " selected ";}?> value="EPS">EPS</option>
                <option <? if($Opciones=="Cesantias"){ echo " selected ";}?> value="Cesantias">Cesantias</option>
                <option <? if($Opciones=="Pensiones"){ echo " selected ";}?> value="Pensiones">Pensiones</option>
                <option <? if($Opciones=="ARP"){ echo " selected ";}?> value="ARP">ARP</option>
                <option <? if($Opciones=="Centro de Costos"){ echo " selected ";}?> value="Centro de Costos">Centro de Costos</option>
                <option <? if($Opciones=="Salarios"){ echo " selected ";}?> value="Salarios">Salarios</option>
			</select>
        </td>
    </tr>
    <tr >
    	<td colspan="2">
            <iframe id="Info" name="Info" frameborder="0" width="100%" height="300px"
                        <?
                        	if($Opciones=="EPS"){ echo "src='Empresas.php?DatNameSID=$DatNameSID&Identificacion=$Identificacion'&Opc='EPS'";}
							if($Opciones=="Cesantias"){ echo "src='Empresas.php?DatNameSID=$DatNameSID&Identificacion=$Identificacion'&Opc='Cesantias'";}
							if($Opciones=="Pensiones"){echo "src='Empresas.php?DatNameSID=$DatNameSID&Identificacion=$Identificacion'&Opc='Pensiones'";}
							if($Opciones=="ARP"){echo "src='ARPF.php?DatNameSID=$DatNameSID&Identificacion=$Identificacion&FecIni=$FecInicio'";}
							if($Opciones=="Centro de Costos"){echo "src='CentroCostos.php?DatNameSID=$DatNameSID&Identificacion=$Identificacion&FecIni=$FecInicio'";}
							if($Opciones=="Salarios"){echo "src='Salarios.php?DatNameSID=$DatNameSID&Identificacion=$Identificacion&FecIni=$FecInicio'";}
						?>></iframe>
        <td>
    </tr>
</table>
</body>
<iframe name="BuscaCargo" id="BuscaCargo" src="BuscarCargo.php?DatNameSID=<? echo $DatNameSID?>" style="visibility:hidden;position:absolute;top:1px"></iframe>
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe>
</html>