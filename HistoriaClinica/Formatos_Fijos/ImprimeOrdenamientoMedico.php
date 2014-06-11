<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND=getdate();
	if($ND[mon]<10){$C1="0";}else{$C1="";}
	if($ND[mday]<10){$C2="0";}else{$C2="";}
	//--
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
	//--
	$AnioNac=substr($Paciente[23],0,4);
	$MesNac=substr($Paciente[23],5,2);
	$DiaNac=substr($Paciente[23],8,2);
	$FecAct=getdate();
	$AnioAct=$FecAct[year];
	$MesAct=$FecAct[mon];
	$DiaAct=$FecAct[mday];
	$Edad=$AnioAct-$AnioNac;
	if($MesAct==$MesNac)
	{
		if($DiaAct<$DiaNac)
		{
			$Edad=$Edad-1;
		}
	}
	elseif($MesAct<$MesNac)
	{
		$Edad=$Edad-1;
	}
	if($Edad>100){$Edad="";}
	else{$Edad=$Edad . " A&Ntilde;OS";}
	//--
	$fechaActual="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";
   	$DatoPaciente=explode("-",$Paciente[1]);
	$cons="select modulo from salud.ususxordmeds where usuario='$usuario[1]'";
	$res=ExQuery($cons);
	if(ExNumRows($res)==0){$NoNewOrd=1;}
	//Matriz de medicos
	$cons="select nombre,usuarios.usuario,especialidad,cargo from central.usuarios,salud.medicos 
	where medicos.usuario=usuarios.usuario and medicos.compania='$Compania[0]'";
	$res=ExQuery($cons);
	//echo $cons;
	while($fila=ExFetch($res)){
		$Medicos[$fila[1]]=array($fila[0],$fila[3]);
	}
	//--	
	$consServ="select numservicio from salud.servicios where compania='$Compania[0]' and cedula='$Paciente[1]' order by numservicio desc";
	//echo $consServ;
	$resServ=ExQuery($consServ);
	$filaS=ExFetch($resServ);
	$NServ=$filaS[0];		
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<?
if($ND[mon]<10){$cero='0';}else{$cero='';}
if($ND[mday]<10){$cero1='0';}else{$cero1='';}
$FechaCompActua="$ND[year]-$cero$ND[mon]-$cero1$ND[mday]";
//if($Paciente[48]!=$FechaCompActua){echo "<em><center><br><br><br><br><br><font size=5 color='BLUE'>La Hoja de Identificacion no se ha guardado!!!";exit;}	
if($Paciente[1]&&$Paciente[21])
{
	//--Condiciones impresion
	if($Opcion=="Actual"){$ParteCons="and idescritura=$IdEsc";}
	elseif($Opcion=="Periodo"){$ParteCons="and fecha>='$FechaIni 00:00:01' and fecha<='$FechaFin 23:59:59'";}
	elseif($Opcion=="Todas"){$ParteCons="";}
	
	$cons="select super from central.usuarios where usuario='$usuario[1]'";
	$res=ExQuery($cons);$fila=ExFetch($res);
	$Super=$fila[0];
	$subcons2="select vistobuenojefe,vistobuenoaux from salud.medicos,salud.cargos 
	where cargos.compania='$Compania[0]' and medicos.compania='$Compania[0]' and usuario='$usuario[1]' and medicos.cargo=cargos.cargos";
	//echo $subcons2;
	$subres2=ExQuery($subcons2);
	$row2=ExFetch($subres2);
	$VoBoJefe=$row2[0];
	$VoBoAux=$row2[1];
	//--Datos Formula Medica Encabezado
	?>
	      
	<?
    //--
	if($Radios=="ServActivo")
	{	
		$consServ="select numservicio,medicotte from salud.servicios where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC'
		order by numservicio desc";
		//echo $consServ;
		$resServ=ExQuery($consServ);
		$filaS=ExFetch($resServ);
		$NServ=$filaS[0];
		$MedT=$filaS[1];
		if(!$NServ){?>
			<center><strong>El Paciente No Tiene Servicios Activos</strong></center>
	<?	}
		else
		{	
			$cons3="select ordenesmedicas.detalle,ordenesmedicas.numservicio,ordenesmedicas.numorden,posologia,viasumin,tipoorden,fechareprog
			,salud.ordenesmedicas.usuario,fecha,revisadopor,fecharevisado,jefeenfermeria,fechajefe,idescritura,substring(cast(fecha as text),1,10),observacionDieta,consistenciaDieta from salud.ordenesmedicas	
			--LEFT JOIN salud.plantilladietas ON salud.plantilladietas.cedula=salud.ordenesmedicas.cedula 
			--and salud.ordenesmedicas.tipoorden='Dieta' and salud.plantilladietas.estado='AC'
			where salud.ordenesmedicas.compania='$Compania[0]' and salud.ordenesmedicas.cedula='$Paciente[1]' and salud.ordenesmedicas.numservicio='$NServ' $ParteCons
			order by substring(cast(fecha as text),1,10)desc,idescritura desc,numorden asc";
			//echo $cons3;
			$res3=ExQuery($cons3);				
			while($fila3=ExFetch($res3))
			{
				$OrdMeds[$fila3[13]][$fila3[2]]=array($fila3[0],$fila3[1],$fila3[2],$fila3[3],$fila3[4],$fila3[5],$fila3[6],$fila3[7],$fila3[8],$fila3[9],$fila3[10],$fila3[11],$fila3[12],$fila3[13],$fila3[15],$fila3[16]);
				//echo $OrdMeds [$fila3[13]][$fila3[2]][1]."<br>";
				//echo $fila3[1]
				//Ords[Idesc][numord]=detalle,numserv,numord,posolog,viasum,tipoord,fechaprog,usu,fecha,fevisadopo,fecharev,jefe,fechajefe,idesc
				//                   =    0  ,    1  ,   2  ,    3  ,  4   ,   5   ,     6   , 7 ,  8  ,    9     ,  10    , 11 ,   12    ,  13 
			}   
			
			$cons="select idescritura,substring(cast(fecha as text),1,10)  from salud.ordenesmedicas
			where compania='$Compania[0]' and cedula='$Paciente[1]' and numservicio=$NServ $ParteCons
			group by idescritura,substring(cast(fecha as text),1,10) order by substring(cast(fecha as text),1,10) desc ,idescritura desc";
			//echo $cons;
			$res=ExQuery($cons);
			while($fila=ExFetch($res))
			{
				//echo "<br>$fila[0] -- $fila[1] -- esta vaina -- <br>";		
				//----
				$consxyz = "Select Eps from Central.Terceros Where Compania='$Compania[0]' and Identificacion='$Paciente[1]'";
				$resxyz = ExQuery($consxyz);
				$filaxyz = ExFetch($resxyz);
				if($NServ){
					$consServ="select fechaing,fechaegr,numservicio from salud.servicios 
					where compania='$Compania[0]' and servicios.cedula='$Paciente[1]' and fechaIng<='$fila[1] 23:59:59' order by fechaIng desc ";
					//echo $consServ;
					$resServ=ExQuery($consServ);
					while($filaServ=ExFetch($resServ))
					{
						if($filaServ[2]==$NServ){break;}	
					}			
					//echo $consServ;		
					if($filaServ[2]){
					$consPxS="select primape,segape,primnom,segnom,tipoasegurador,Identificacion,contrato,nocontrato
					 from central.terceros,salud.pagadorxservicios 
					where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and identificacion=entidad	and numservicio=$filaServ[2] order by fechaini desc";
					$resPxS=ExQuery($consPxS);
					$filaPxS=Exfetch($resPxS);
					$Ent=$filaPxS[5];
					$Contrat=$filaPxS[6];
					$NoCont=$filaPxS[7];}
					
				}	
				if($filaServ[0])
				{
					$consdi="SELECT diagnosticos.diagnostico,cie.diagnostico FROM salud.diagnosticos,salud.cie where Compania='$Compania[0]' 
					and cedula='$Paciente[1]' and numservicio=$filaServ[2] and clasedx='Ingreso' and cie.codigo=diagnosticos.diagnostico";
					//echo $consdi." $NumServ entra di<br>";
					$resdi=ExQuery($consdi);
					$filadi=ExFetch($resdi);
				}
				if($filaServ[1])
				{
					$consde="SELECT diagnosticos.diagnostico,cie.diagnostico FROM salud.diagnosticos,salud.cie where Compania='$Compania[0]' 
					and cedula='$Paciente[1]' and numservicio=$filaServ[2] and clasedx='Egreso' and cie.codigo=diagnosticos.diagnostico";
					//echo $consde." $NumServ entra di<br>";
					$resde=ExQuery($consde);
					$filade=ExFetch($resde);
				}
				?>  
                <table width="100%" border="1" bordercolor="#ffffff" style='font : normal normal small-caps 13px Tahoma;'>
                <tr><td align="center"><img src="/Imgs/Logo.jpg" alt="" width="50" height="50" style="position:absolute;left:50px;">
                
                <strong><font size="4"><?echo strtoupper($Compania[0])?></font><br>
                <?	if($Compania[0]=="Hospital San Rafael de Pasto"||$Compania[0]=="Clinica San Juan de Dios"){echo "HERMANOS HOSPITALARIOS DE SAN JUAN DE DIOS";}?></strong><br>
                <font size="1"><? echo $Compania[1]."<br>".$Compania[2]?> <br> Telefonos <? echo $Compania[3]?>       
                <br>
                </td>     
                </tr>
                </table>       
                <?
				/*$c=0;
                foreach($Paciente as $P)
				{
					echo "$c ".$P."<br>";	
					$c++;
				}*/
				?>
                <table border="1" width="100%">
                <tr><td>
                    <table style='font : normal normal small-caps 10px Tahoma;'>
                    <tr><td>NOMBRE:</td><td><? echo "$Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5]"?></td></tr>
                    <tr><td>IDENTIFICACION:</td><td><? echo "$Paciente[1]"?></td></tr>
                    <tr><td>FECHA DE NACIMIENTO:</td><td><? echo "$Paciente[23] ($Edad)"?></td></tr>
                    <tr><td>DIRECCION:</td><td><? echo $Paciente[7];?></td></tr>
                    <tr><td>FECHA DE INGRESO:</td><td><? echo $filaServ[0];?></td></tr>  		
                    <?
					if($filadi)
					{?>
                    <tr><td>DX DE INGRESO:</td><td><? echo "$filadi[0] - $filadi[1]";?></td></tr>  		
                    <?
                    }?>
                    <tr><td>FECHA DE EGRESO:</td><td><? echo $filaServ[1];?></td></tr>
                    <?
					if($filade)
					{?>
                    <tr><td>DX DE EGRESO:</td><td><? echo "$filade[0] - $filade[1]";?></td></tr>
                    <?
					}?>
                    <tr><td>FECHA DE REGISTRO:</td><td><?  echo $OrdMeds[$fila[0]][1][8];?></td></tr>
                    </table>
                <td>
                    <table style='font : normal normal small-caps 10px Tahoma;'>
                    <tr><td>MEDICO TRATANTE:</td><td><? echo $Medicos[$MedT][0]?></td></tr>
                    <tr><td>ENTIDAD:</td><td><? echo "$filaPxS[0] $filaPxS[1] $filaPxS[2] $filaPxS[3]"?></td></tr>
                    <tr><td>REGIMEN:</td><td><? echo "$filaPxS[4]"?></td></tr>
                    <tr><td>TIPO DE USUARIO:</td><td><? echo "$Paciente[27]"?></td></tr>
                    <tr><td>NIVEL DE USUARIO:</td><td><? echo "$Paciente[28]"?></td></tr>
                    </table>
                </td>
                </table>
                <br>            
				<table border="1" style="font : normal normal small-caps 12px Tahoma;" align="center" cellpadding="4" width="100%">
				<tr align="center"  bgcolor="#e5e5e5">
					<td colspan="4">
					<strong>ORDENES MEDICAS<? //echo $Medicos[$OrdMeds[$fila[0]][1][7]][0]." <br>".$Medicos[$OrdMeds[$fila[0]][1][7]][1]." - ".$OrdMeds[$fila[0]][1][8]?></strong>
					</td>					
				</tr>	
			<?	$cont=1;
				foreach($OrdMeds[$fila[0]] as $Ordens)
				{?>
					
					<tr>
						<td><strong><? echo $cont;?> </strong></td>
						<td><i><? echo $Ordens[5];  if($Ordens[6]){ echo " (Reprogramado)";}?></i></td>
						<td><?	echo "$Ordens[0] $Ordens[3] $Ordens[15] $Ordens[14]"; if($Ordens[4]){ echo " Via $Ordens[4]";}?></td>
					</tr>						
			<?		$cont++;
				}?>
				</table>
                <?
                $consced="select cedula from central.usuarios where usuario='".$OrdMeds[$fila[0]][1][7]."'";			
				$resced=ExQuery($consced);
				$filaced=ExFetch($resced);
				$consRM="select rm from salud.medicos where compania='$Compania[0]' and usuario='".$OrdMeds[$fila[0]][1][7]."'";
				$resRM=ExQuery($consRM);
				$filaRM=ExFetch($resRM);
				$RM=$filaRM[0];
				?><br>
                <table align="center" style="font : normal normal small-caps 12px Tahoma;">
                <tr align="center" >
					<td><img src='/Firmas/<? echo "$filaced[0].GIF"?>' style='width:160px; height:100px'></td>				
                 </tr>
                 <tr>
                    <td align="center">
					<strong><? echo $Medicos[$OrdMeds[$fila[0]][1][7]][0]." <br>".$Medicos[$OrdMeds[$fila[0]][1][7]][1]."<br>Registro Medico: $RM"?></strong>
					</td>                 
				</tr>
                </table><br><br>
				<?							
			}
		}
	}
	elseif($Radios=='TodasOrdenes')
	{
		$cons="select numservicio,tiposervicio,fechaing,fechaegr,medicotte from salud.servicios where compania='$Compania[0]' and cedula='$Paciente[1]' order by numservicio desc";
		$res=ExQuery($cons);
		if(ExNumRows($res)>0)
		{		
			while($filaS=ExFetch($res))
			{
				?>
                <!--<table border="1" style="font : normal normal small-caps 12px Tahoma;" align="center" cellpadding="4" width="100%">
				<tr bgcolor="#e5e5e5" align="center">
					<td colspan="4"><strong>SERVICIO DE <? echo strtoupper($filaS[1])?> - FECHA INGRESO: <? echo $filaS[2]?> FECHA EGRESO: <? echo $fila[3]?></strong></td>   	
				</tr>-->				
			<?	$cons="select idescritura,substring(cast(fecha as text),1,10) from salud.ordenesmedicas 
				where compania='$Compania[0]' and cedula='$Paciente[1]' $ParteCons
				group by idescritura,substring(cast(fecha as text),1,10) order by substring(cast(fecha as text),1,10) desc,idescritura desc";
				$res=ExQuery($cons);
				while($fila=ExFetch($res))
				{							
					$consSS="select numservicio,fecha from salud.ordenesmedicas where idescritura=$fila[0] and compania='$Compania[0]' and cedula='$Paciente[1]'";
					$resSS=ExQuery($consSS);
					$filaSS=ExFetch($resSS);
					$NServ=$filaSS[0];
					$FechaR=$filaSS[1];
					//--
					  	
					//----
					$consxyz = "Select Eps from Central.Terceros Where Compania='$Compania[0]' and Identificacion='$Paciente[1]'";
					$resxyz = ExQuery($consxyz);
					$filaxyz = ExFetch($resxyz);
					if($NServ){
						$consServ="select fechaing,fechaegr,numservicio,medicotte from salud.servicios 
						where compania='$Compania[0]' and servicios.cedula='$Paciente[1]' and fechaIng<='$filaS[2]' order by fechaIng desc ";
						//echo $consServ;
						$resServ=ExQuery($consServ);
						while($filaServ=ExFetch($resServ))
						{
							if($filaServ[2]==$NServ){$MedT=$filaServ[3];break;}	
						}			
						//echo $consServ;	
						//echo $filaServ[2];	
						if($filaServ[2]){
						$consPxS="select primape,segape,primnom,segnom,tipoasegurador,Identificacion,contrato,nocontrato
						 from central.terceros,salud.pagadorxservicios 
						where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and identificacion=entidad	and numservicio=$filaServ[2] order by fechaini desc";
						//echo $consPxS;
						$resPxS=ExQuery($consPxS);
						$filaPxS=Exfetch($resPxS);
						$Ent=$filaPxS[5];
						$Contrat=$filaPxS[6];
						$NoCont=$filaPxS[7];}
						
					}	
					if($filaServ[0])
					{
						$consdi="SELECT diagnosticos.diagnostico,cie.diagnostico FROM salud.diagnosticos,salud.cie where Compania='$Compania[0]' 
						and cedula='$Paciente[1]' and numservicio=$filaServ[2] and clasedx='Ingreso' and cie.codigo=diagnosticos.diagnostico";
						//echo $consdi." $NumServ entra di<br>";
						$resdi=ExQuery($consdi);
						$filadi=ExFetch($resdi);
					}
					if($filaServ[1])
					{
						$consde="SELECT diagnosticos.diagnostico,cie.diagnostico FROM salud.diagnosticos,salud.cie where Compania='$Compania[0]' 
						and cedula='$Paciente[1]' and numservicio=$filaServ[2] and clasedx='Egreso' and cie.codigo=diagnosticos.diagnostico";
						//echo $consde." $NumServ entra di<br>";
						$resde=ExQuery($consde);
						$filade=ExFetch($resde);
					}
					?>  
                    <table width="100%" border="1" bordercolor="#ffffff" style='font : normal normal small-caps 13px Tahoma;'>
                    <tr><td align="center"><img src="/Imgs/Logo.jpg" alt="" width="50" height="50" style="position:absolute;left:50px;">
                    
                    <strong><font size="4"><?echo strtoupper($Compania[0])?></font><br>
                    <?	if($Compania[0]=="Hospital San Rafael de Pasto"||$Compania[0]=="Clinica San Juan de Dios"){echo "HERMANOS HOSPITALARIOS DE SAN JUAN DE DIOS";}?></strong><br>
                    <font size="1"><? echo $Compania[1]."<br>".$Compania[2]?> <br> Telefonos <? echo $Compania[3]?>       
                    <br>
                    </td>     
                    </tr>
                    </table>       
                    <?
                    /*$c=0;
                    foreach($Paciente as $P)
                    {
                        echo "$c ".$P."<br>";	
                        $c++;
                    }*/
                    ?>
                    <table border="1" width="100%">
                    <tr><td>
                        <table style='font : normal normal small-caps 10px Tahoma;'>
                        <tr><td>NOMBRE:</td><td><? echo "$Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5]"?></td></tr>
                        <tr><td>IDENTIFICACION:</td><td><? echo "$Paciente[1]"?></td></tr>
                        <tr><td>FECHA DE NACIMIENTO:</td><td><? echo "$Paciente[23] ($Edad)"?></td></tr>
                        <tr><td>DIRECCION:</td><td><? echo $Paciente[7];?></td></tr>
                        <tr><td>FECHA DE INGRESO:</td><td><? echo $filaServ[0];?></td></tr>  		
                        <?
                        if($filadi)
                        {?>
                        <tr><td>DX DE INGRESO:</td><td><? echo "$filadi[0] - $filadi[1]";?></td></tr>  		
                        <?
                        }?>
                        <tr><td>FECHA DE EGRESO:</td><td><? echo $filaServ[1];?></td></tr>
                        <?
                        if($filade)
                        {?>
                        <tr><td>DX DE EGRESO:</td><td><? echo "$filade[0] - $filade[1]";?></td></tr>
                        <?
                        }?>
                        <tr><td>FECHA DE REGISTRO:</td><td><? echo $filaSS[1]?><!--<input type="text" name="fr" style="border:none; font:normal normal small-caps 10px Tahoma;" readonly>--></td></tr>
                        </table>
                    <td>
                        <table style='font : normal normal small-caps 10px Tahoma;'>
                        <tr><td>MEDICO TRATANTE:</td><td><? echo $Medicos[$MedT][0]?></td></tr>
                        <tr><td>ENTIDAD:</td><td><? echo "$filaPxS[0] $filaPxS[1] $filaPxS[2] $filaPxS[3]"?></td></tr>
                        <tr><td>REGIMEN:</td><td><? echo "$filaPxS[4]"?></td></tr>
                        <tr><td>TIPO DE USUARIO:</td><td><? echo "$Paciente[27]"?></td></tr>
                        <tr><td>NIVEL DE USUARIO:</td><td><? echo "$Paciente[28]"?></td></tr>
                        </table>
                    </td>
                    </table>                
                	<br>
                <?
					$cons2="select usuario,fecha,revisadopor,fecharevisado,jefeenfermeria,fechajefe from salud.ordenesmedicas where compania='$Compania[0]' 
					and cedula='$Paciente[1]' and numservicio=$NServ and idescritura=$fila[0] order by numorden desc";
					$res2=ExQuery($cons2);
					$fila2=ExFetch($res2);?>
                    <table border="1" style="font : normal normal small-caps 12px Tahoma;" align="center" cellpadding="4" width="100%">
                    <tr align="left"  bgcolor="#e5e5e5">						
						<td colspan="4" align="center">						
							<strong>ORDENES MEDICAS</strong>
						</td>
					</tr>	
				<?	$cons3="select ordenesmedicas.detalle,ordenesmedicas.numservicio,ordenesmedicas.numorden,posologia,viasumin,tipoorden,fechareprog,substring(cast(fecha as text),1,10),observacionDieta,
					consistenciaDieta from salud.ordenesmedicas
                    --LEFT JOIN salud.plantilladietas ON salud.plantilladietas.cedula=salud.ordenesmedicas.cedula 
					--and salud.ordenesmedicas.tipoorden='Dieta' and salud.plantilladietas.estado='AC'					
					where salud.ordenesmedicas.compania='$Compania[0]' and salud.ordenesmedicas.cedula='$Paciente[1]' and salud.ordenesmedicas.numservicio='$NServ' and idescritura=$fila[0] order by substring(cast(fecha as text),1,10)";
					$res3=ExQuery($cons3);
					$cont=1;
					while($fila3=ExFetch($res3))
					{?>                    	
						<tr>							
							<td><strong><? echo $cont?></strong></td>
							 <td><i><? echo $fila3[5]; if($fila3[6]){ echo " (Reprogramado)";}?></i></td>
							<td><?	echo "$fila3[0] $fila3[3] $fila3[9] $fila3[8]"; if($fila3[4]){ echo " Via $fila3[4]";}?></td>
							<?								
								if($usuario[1]==$fila2[0] && ($fila3[7]=="$ND[year]-$ND[mon]-$ND[mday]"))
								{
							?>
								<td style="cursor:hand" onClick="if(confirm('Eliminar esta orden?')){location.href='OrdenamientoMedico.php?DatNameSID=<? echo $DatNameSID?>&Radios=<? echo $Radios?>&EliminaOrden=1&NumServicio=<? echo $fila3[1]?>&NumOrden=<? echo $fila3[2]?>&IdEscritura=<? echo $fila[0]?>&OpcMostrar=<? echo $OpcMostrar?>'}"><img src="/Imgs/b_drop.png"></td>
							<?
								}
							?>							
						</tr>
				<?		$cont++;
					}		
					$consced="select cedula from central.usuarios where usuario='".$fila2[0]."'";			
					$resced=ExQuery($consced);
					$filaced=ExFetch($resced);
					$consRM="select rm from salud.medicos where compania='$Compania[0]' and usuario='".$fila2[0]."'";
					$resRM=ExQuery($consRM);
					$filaRM=ExFetch($resRM);
					$RM=$filaRM[0];
					?>
                    </table>
                    <br>
                    <table align="center" style="font : normal normal small-caps 12px Tahoma;">
                    <tr align="center" >
                        <td><img src='/Firmas/<? echo "$filaced[0].GIF"?>' style='width:160px; height:100px'></td>				
                     </tr>
                     <tr>
                        <td align="center">
                        <strong><? echo $Medicos[$fila2[0]][0]."<br>".$Medicos[$fila2[0]][1]."<br> Registro Medico: $RM"?></strong>
                        </td>                 
                    </tr>
                    </table><br><br>
                    <?
				}
			}				              
		}
		else{?>
			<tr><td><strong>El Paciente No Tiene Servicios Registrados</strong></td></tr>
<?		}		
	}
	elseif($Radios==="Activos")
	{
		//echo "<tr><td colspan='4'>&nbsp;</td></tr>";
		$consServ="select numservicio,medicotte from salud.servicios where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC'
		order by numservicio desc";
		//echo $consServ;
		$resServ=ExQuery($consServ);
		$filaS=ExFetch($resServ);
		$NServ=$filaS[0];
		$MedT=$filaS[1];
		//----
		
        $cons3="select usuario,fecha
		from salud.ordenesmedicas	
		where compania='$Compania[0]' and cedula='$Paciente[1]' and estado='AC' order by idescritura desc,numorden desc";
		$res3=ExQuery($cons3);
		$fila3=ExFetch($res3);
		//--
		$consxyz = "Select Eps from Central.Terceros Where Compania='$Compania[0]' and Identificacion='$Paciente[1]'";
		$resxyz = ExQuery($consxyz);
		$filaxyz = ExFetch($resxyz);
		if($NServ){
			$consServ="select fechaing,fechaegr,numservicio from salud.servicios 
			where compania='$Compania[0]' and servicios.cedula='$Paciente[1]' and fechaIng<='$fila3[1]' order by fechaIng desc ";
			//echo $consServ;
			$resServ=ExQuery($consServ);
			while($filaServ=ExFetch($resServ))
			{
				if($filaServ[2]==$NServ){break;}	
			}			
			//echo $consServ;		
			if($filaServ[2]){
			$consPxS="select primape,segape,primnom,segnom,tipoasegurador,Identificacion,contrato,nocontrato
			 from central.terceros,salud.pagadorxservicios 
			where terceros.compania='$Compania[0]' and pagadorxservicios.compania='$Compania[0]' and identificacion=entidad	and numservicio=$filaServ[2] order by fechaini desc";
			$resPxS=ExQuery($consPxS);
			$filaPxS=Exfetch($resPxS);
			$Ent=$filaPxS[5];
			$Contrat=$filaPxS[6];
			$NoCont=$filaPxS[7];}
			
		}	
		if($filaServ[0])
		{
			$consdi="SELECT diagnosticos.diagnostico,cie.diagnostico FROM salud.diagnosticos,salud.cie where Compania='$Compania[0]' 
			and cedula='$Paciente[1]' and numservicio=$filaServ[2] and clasedx='Ingreso' and cie.codigo=diagnosticos.diagnostico";
			//echo $consdi." $NumServ entra di<br>";
			$resdi=ExQuery($consdi);
			$filadi=ExFetch($resdi);
		}
		if($filaServ[1])
		{
			$consde="SELECT diagnosticos.diagnostico,cie.diagnostico FROM salud.diagnosticos,salud.cie where Compania='$Compania[0]' 
			and cedula='$Paciente[1]' and numservicio=$filaServ[2] and clasedx='Egreso' and cie.codigo=diagnosticos.diagnostico";
			//echo $consde." $NumServ entra di<br>";
			$resde=ExQuery($consde);
			$filade=ExFetch($resde);
		}
		?>  
		<table width="100%" border="1" bordercolor="#ffffff" style='font : normal normal small-caps 13px Tahoma;'>
		<tr><td align="center"><img src="/Imgs/Logo.jpg" alt="" width="50" height="50" style="position:absolute;left:50px;">
		
		<strong><font size="4"><?echo strtoupper($Compania[0])?></font><br>
		<?	if($Compania[0]=="Hospital San Rafael de Pasto"||$Compania[0]=="Clinica San Juan de Dios"){echo "HERMANOS HOSPITALARIOS DE SAN JUAN DE DIOS";}?></strong><br>
		<font size="1"><? echo $Compania[1]."<br>".$Compania[2]?> <br> Telefonos <? echo $Compania[3]?>       
		<br>
		</td>     
		</tr>
		</table>       
		
		<table border="1" width="100%">
		<tr><td>
			<table style='font : normal normal small-caps 10px Tahoma;'>
			<tr><td>NOMBRE:</td><td><? echo "$Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5]"?></td></tr>
			<tr><td>IDENTIFICACION:</td><td><? echo "$Paciente[1]"?></td></tr>
			<tr><td>FECHA DE NACIMIENTO:</td><td><? echo "$Paciente[23] ($Edad)"?></td></tr>
			<tr><td>DIRECCION:</td><td><? echo $Paciente[7];?></td></tr>
			<tr><td>FECHA DE INGRESO:</td><td><? echo $filaServ[0];?></td></tr>  		
			<?
			if($filadi)
			{?>
			<tr><td>DX DE INGRESO:</td><td><? echo "$filadi[0] - $filadi[1]";?></td></tr>  		
			<?
			}?>
			<tr><td>FECHA DE EGRESO:</td><td><? echo $filaServ[1];?></td></tr>
			<?
			if($filade)
			{?>
			<tr><td>DX DE EGRESO:</td><td><? echo "$filade[0] - $filade[1]";?></td></tr>
			<?
			}?>
			<tr><td>FECHA DE REGISTRO:</td><td><?  echo $fila3[1];?></td></tr>
			</table>
		<td>
			<table style='font : normal normal small-caps 10px Tahoma;'>
			<tr><td>MEDICO TRATANTE:</td><td><? echo $Medicos[$MedT][0]?></td></tr>
			<tr><td>ENTIDAD:</td><td><? echo "$filaPxS[0] $filaPxS[1] $filaPxS[2] $filaPxS[3]"?></td></tr>
			<tr><td>REGIMEN:</td><td><? echo "$filaPxS[4]"?></td></tr>
			<tr><td>TIPO DE USUARIO:</td><td><? echo "$Paciente[27]"?></td></tr>
			<tr><td>NIVEL DE USUARIO:</td><td><? echo "$Paciente[28]"?></td></tr>
			</table>
		</td>
		</table>
		<br>
		<table border="1" style="font : normal normal small-caps 12px Tahoma;" align="center" cellpadding="4" width="100%">
		<tr align="left"  bgcolor="#e5e5e5">
			<td colspan="4" align="center">
				<strong>ORDENES MEDICAS</strong>
			</td>
		</tr>            
	<?	$consom="select ordenesmedicas.detalle,ordenesmedicas.numservicio,ordenesmedicas.numorden,posologia,viasumin,tipoorden,fechareprog,observacionDieta, 
		consistenciaDieta from salud.ordenesmedicas	
		--LEFT JOIN salud.plantilladietas ON salud.plantilladietas.cedula=salud.ordenesmedicas.cedula 
		--and salud.ordenesmedicas.tipoorden='Dieta' and salud.plantilladietas.estado='AC'
		where salud.ordenesmedicas.compania='$Compania[0]' and salud.ordenesmedicas.cedula='$Paciente[1]' and salud.ordenesmedicas.estado='AC' order by idescritura desc,numorden desc";
		$resom=ExQuery($consom);
		$cont=1;
		while($filaom=ExFetch($resom))
		{?>                    	
			<tr>
				<a name="<? echo $filaom[1];?>">
				<td><strong><? echo $cont?></strong></td>
				<td><i><? echo $filaom[5]; if($filaom[6]){ echo " (Reprogramado)";}?></i></td>
				<td><?	echo "$filaom[0] $filaom[3] $filaom[8] $filaom[7]"; if($filaom[4]){ echo " Via $filaom[4]";}?></td>
				</a>
			</tr>
	<?		$cont++;
		}
		?>
        </table>
        <?
		$consced="select cedula from central.usuarios where usuario='".$fila3[0]."'";			
		$resced=ExQuery($consced);
		$filaced=ExFetch($resced);
		$consRM="select rm from salud.medicos where compania='$Compania[0]' and usuario='".$fila2[0]."'";
		$resRM=ExQuery($consRM);
		$filaRM=ExFetch($resRM);
		$RM=$filaRM[0];
		?>
		</table>
		<br>
		<table align="center" style="font : normal normal small-caps 12px Tahoma;">
		<tr align="center" >
			<td><img src='/Firmas/<? echo "$filaced[0].GIF"?>' style='width:160px; height:100px'></td>				
		 </tr>
		 <tr>
			<td align="center">
			<strong><? echo $Medicos[$fila3[0]][0]." <br>".$Medicos[$fila3[0]][1]."<br>Registro Medico: $RM"?></strong>
			</td>                 
		</tr>
		</table><br><br>
        <?
	}      

}
else{
	echo "<center><font face='Tahoma' color='#0066FF' size='+2' ><b>No hay un paciente seleccionado, Debe guardarse la ficha de identificacion!!! </b></font></center>";
}?>  	
</form>
</body>
</html>