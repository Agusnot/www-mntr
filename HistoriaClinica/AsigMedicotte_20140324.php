<?php
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	//echo $usuario[4];
        $Ambito="Hospitalizacion";
        
	if($Guardar){
		//echo $SiMedico['123'];
		
		if($OpcAsig=="ConMed"){
			//$cons="select identificacion from central.terceros,salud.servicios,salud.pacientesxpabellones where 
	//terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and medicotte is not null and terceros.identificacion=servicios.cedula and servicios.estado='AC' 
	//and pacientesxpabellones.compania='$Compania[0]' and pacientesxpabellones.ambito='$Ambito' and pacientesxpabellones.pabellon='$UnidadHosp' and pacientesxpabellones.estado='AC' and 
	//pacientesxpabellones.cedula=terceros.identificacion and tiposervicio='$Ambito' group by primnom,segnom,primape,segape,identificacion,medicotte order by primnom,segnom,primape,segape";
                    if($UnidadHosp=='TODOS'){
                        $cons="select terceros.identificacion,primnom,segnom,terceros.primape,segape,medicotte,fechaing,fechaegr,dxserv,aseguradoras.primape from salud.servicios,salud.pacientesxpabellones,central.terceros left join (Select Primape,identificacion from Central.Terceros where Tipo='Asegurador' and Compania = '$Compania[0]') as aseguradoras on terceros.eps=aseguradoras.identificacion where 
                            terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and medicotte is not null and terceros.identificacion=servicios.cedula and servicios.estado='AC'
                            and pacientesxpabellones.compania='$Compania[0]' and pacientesxpabellones.ambito='$Ambito' and pacientesxpabellones.estado='AC' and 
                            pacientesxpabellones.cedula=terceros.identificacion and servicios.medicotte<>'SIN DEFINIR MÉDICO' group by primnom,segnom,terceros.primape,segape,terceros.identificacion,medicotte,fechaing,fechaegr,dxserv,aseguradoras.primape order by primnom,segnom,terceros.primape,segape";
                    }
                    else{
                        $cons="select terceros.identificacion,primnom,segnom,terceros.primape,segape,medicotte,fechaing,fechaegr,dxserv,aseguradoras.primape from salud.servicios,salud.pacientesxpabellones,central.terceros left join (Select Primape,identificacion from Central.Terceros where Tipo='Asegurador' and Compania = '$Compania[0]') as aseguradoras on terceros.eps=aseguradoras.identificacion where 
                            terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and medicotte is not null and terceros.identificacion=servicios.cedula and servicios.estado='AC'
                            and pacientesxpabellones.compania='$Compania[0]' and pacientesxpabellones.ambito='$Ambito' and pacientesxpabellones.pabellon='$UnidadHosp' and pacientesxpabellones.estado='AC' and 
                            pacientesxpabellones.cedula=terceros.identificacion and servicios.medicotte<>'SIN DEFINIR MÉDICO' group by primnom,segnom,terceros.primape,segape,terceros.identificacion,medicotte,fechaing,fechaegr,dxserv,aseguradoras.primape order by primnom,segnom,terceros.primape,segape";
                    }
                    //echo $cons;
			$res=ExQuery($cons);
			if(ExNumRows($res)>0){
				while($fila=ExFetch($res)){
					$num=$fila[0];
					if($SiMedico[$num]!=''){
						$cons2="update salud.servicios set medicotte='$SiMedico[$num]' where 
						cedula='$fila[0]' and estado='AC' and compania='$Compania[0]' and tiposervicio='$Ambito' and servicios.medicotte<>'SIN DEFINIR MÉDICO'";
						$res2=ExQuery($cons2);
						//echo "$cons2<br>";
					}
				}
			}
		}
		if($OpcAsig=="SinMed"){
			//$cons="select identificacion from central.terceros,salud.servicios,salud.pacientesxpabellones where 
	//terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and medicotte is null and terceros.identificacion=servicios.cedula and servicios.estado='AC' 
	//and pacientesxpabellones.compania='$Compania[0]' and pacientesxpabellones.ambito='$Ambito' and pacientesxpabellones.pabellon='$UnidadHosp' and pacientesxpabellones.estado='AC' and 
	//pacientesxpabellones.cedula=terceros.identificacion and tiposervicio='$Ambito' group by primnom,segnom,primape,segape,identificacion,medicotte order by primnom,segnom,primape,segape";
                if($UnidadHosp=='TODOS'){
                    $cons="select terceros.identificacion,primnom,segnom,terceros.primape,segape,medicotte,fechaing,fechaegr,dxserv,aseguradoras.primape from salud.servicios,salud.pacientesxpabellones,central.terceros left join (Select Primape,identificacion from Central.Terceros where Tipo='Asegurador' and Compania = '$Compania[0]') as aseguradoras on terceros.eps=aseguradoras.identificacion where 
                    terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and terceros.identificacion=servicios.cedula and servicios.estado='AC'
                    and pacientesxpabellones.compania='$Compania[0]' and pacientesxpabellones.ambito='$Ambito' and pacientesxpabellones.estado='AC' and 
                    pacientesxpabellones.cedula=terceros.identificacion and (servicios.medicotte='SIN DEFINIR MÉDICO' or servicios.medicotte='') group by primnom,segnom,terceros.primape,segape,terceros.identificacion,medicotte,fechaing,fechaegr,dxserv,aseguradoras.primape order by primnom,segnom,terceros.primape,segape";
                }
                else{
                    $cons="select terceros.identificacion,primnom,segnom,terceros.primape,segape,medicotte,fechaing,fechaegr,dxserv,aseguradoras.primape from salud.servicios,salud.pacientesxpabellones,central.terceros left join (Select Primape,identificacion from Central.Terceros where Tipo='Asegurador' and Compania = '$Compania[0]') as aseguradoras on terceros.eps=aseguradoras.identificacion where 
                    terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and terceros.identificacion=servicios.cedula and servicios.estado='AC'
                    and pacientesxpabellones.compania='$Compania[0]' and pacientesxpabellones.ambito='$Ambito' and pacientesxpabellones.pabellon='$UnidadHosp' and pacientesxpabellones.estado='AC' and 
                    pacientesxpabellones.cedula=terceros.identificacion and (servicios.medicotte='SIN DEFINIR MÉDICO' or servicios.medicotte='') group by primnom,segnom,terceros.primape,segape,terceros.identificacion,medicotte,fechaing,fechaegr,dxserv,aseguradoras.primape order by primnom,segnom,terceros.primape,segape";
                }
			//echo $cons;
			$res=ExQuery($cons);
			if(ExNumRows($res)>0){
				while($fila=ExFetch($res)){
					$num=$fila[0];
					if($NoMedico[$num]!=''){						
						$cons2="update salud.servicios set medicotte='$NoMedico[$num]' where 
						cedula='$fila[0]' and estado='AC' and compania='$Compania[0]' and tiposervicio='$Ambito' and (servicios.medicotte='SIN DEFINIR MÉDICO' or servicios.medicotte='')";
						$res2=ExQuery($cons2);
                                                //echo "<br>".$cons2;
					}
				}
			}
		}
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
    <div style="width: 400px; height: 250px; overflow: scroll; float: right;"><table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="4">
            <tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan="2">Asignaciones de Pacientes x Médico</td>
<?php		/*$cons="select nom1, sum(sumatoria1) from
                    (select con1.nom1, con1.sumatoria1 from (select nombre as nom1, count(*)-1 as sumatoria1 from salud.medicos,central.usuarios,salud.cargos where medicos.usuario=usuarios.usuario and medicos.compania='$Compania[0]' and cargos.compania='$Compania[0]' and cargos.cargos=medicos.cargo and tratante=1 and medicos.estadomed='Activo' group by nombre) as con1
                    union all
                    select con2.nom1, con2.sumatoria1 from (select medicotte as nom1, count(*) as sumatoria1 from salud.servicios where estado='AC' group by medicotte) as con2) as tbls
                    group by nom1 order by nom1";*/
                $cons = "select nom1, sum(sumatoria1) from "
                        . "(select con1.nom1, con1.sumatoria1 from (select nombre as nom1, count(*)-1 as sumatoria1 from salud.medicos,central.usuarios,salud.cargos where medicos.usuario=usuarios.usuario and medicos.compania='$Compania[0]' and cargos.compania='$Compania[0]' and cargos.cargos=medicos.cargo and tratante=1 and medicos.estadomed='Activo' group by nombre) as con1 "
                        . "union all "
                        . "select con2.nom1, con2.sumatoria1 from (SELECT medicotte AS nom1, count(*) AS sumatoria1 FROM salud.servicios,salud.pacientesxpabellones WHERE servicios.numservicio=pacientesxpabellones.numservicio AND pacientesxpabellones.estado='AC' AND servicios.estado='AC' AND servicios.tiposervicio='Hospitalizacion' GROUP BY medicotte) as con2) as tbls "
                        . "group by nom1 order by nom1";
		//echo $cons;
		$res=ExQuery($cons);echo ExError();
		if(ExNumRows($res)>0){
			$ban=1;?>
			<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>Médico</td><td>N° Pacientes</td></tr>
	<?php
                        $sindefinirmed = 0;
                        while($fila=ExFetch($res)){
								if($fila[0]=='SIN DEFINIR MÉDICO'){
                                    $fila[1] += $sindefinirmed;
                                }
                                if($fila[0]==''){
                                    $sindefinirmed = $fila[1];
                                }
                                else{
                                    echo "<tr><td>$fila[0]</td><td>$fila[1]</td></tr>";
                                }
			}
		}?>
    	</table>
</div>
    <div style="height:250px;">
<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="4">
	<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan="15">Asignar Medico Tratante</td>
	<!--<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold">Proceso</td>-->
    <tr><td>
    <?php	
    // Comentado porque el ambito debe ser siempre Hospitalizacion
    /*if($Ambito==''){ echo "<input type='hidden' name='Ambito' value='1'>";$Ambito=1;}?>
    <select name="Ambito" onChange="document.FORMA.submit()"><option></option>    
		<?php
			$cons="select ambito from salud.ambitos where compania='$Compania[0]' and ambito!='Sin Ambito' order by ambito";
			$res=ExQuery($cons);echo ExError();	
			while($fila = ExFetch($res)){
				if($fila[0]==$Ambito){
					echo "<option value='$fila[0]' selected>$fila[0]</option>";
				}
				else{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
			}*/
                        ?>
            </select></td></tr>
  
   	<td style="font-weight:bold">Servicio</td>
   <?php if(!$Regresa){
   		if(!$UnidadHosp){
   			$consult="Select * from Salud.Pabellones where ambito='$Ambito' and Compania='$Compania[0]'";		
			$result=ExQuery($consult);
			$row = ExFetchArray($result);
			$UnidadHosp=$row[0];
   		}
		if($Ambito!=$AmbitoAnt){
			$consult="Select * from Salud.Pabellones where ambito='$Ambito' and Compania='$Compania[0]'";		
			$result=ExQuery($consult);
			$row = ExFetchArray($result);
			//$UnidadHosp=$row[0];
			$UnidadHosp='TODOS';
		}
	}	
		
   		$consult="Select * from Salud.Pabellones where ambito='$Ambito' and Compania='$Compania[0]'";		
		$result=ExQuery($consult);
		if(ExNumRows($result)>0){?>        	           
        <td><select style="width:100%;" name="UnidadHosp" onChange="document.FORMA.submit()">
                <option value="TODOS">TODOS</option>
		<?php	while($row = ExFetchArray($result)){
                                $sel = "";
				if($row[0]==$UnidadHosp){
                                        $sel = "selected";
					//echo "<option value='$row[0]'>$row[0]</option>";
				}
				//else{
					echo "<option value='$row[0]' $sel>$row[0]</option>";
				//}
			}
		?>	</select></td><?php
		}
		else{
			if($Ambito){
				echo "<input type='hidden' name='UnidadHosp' value=''>";
				if($Ambito!=1){
					echo "<td style='font-weight:bold' align='center' colspan='7'>No se han asignado unidades a este ambito</td>";
				}
			}			
		}?>
 	</tr>        
    <tr align="center">
        <td style="font-weight:bold"></td>
    	<td>
        <?	if(!$OpcAsig){$OpcAsig="SinMed";}?>
        	<select name="OpcAsig" onChange="document.FORMA.submit()">
            	<option value="SinMed" <? if($OpcAsig=="SinMed"){?> selected<?  } ?>>Pacientes Sin Medico Asignado</option>
           		<option value="ConMed" <? if($OpcAsig=="ConMed"){?> selected<? }?>>Pacientes Con Medico Asignado</option>                
            </select>           
       	</td>
  	</tr>
</table>
    </div>
<br>
<?php
if($Ambito!=''&&$UnidadHosp!='')
{
	$ban=0;
	if($OpcAsig=="ConMed"){?>
        <table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="4">
            <tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan="8">Pacientes con Medico Asignado</td>
<?php		//$cons="select primnom,segnom,terceros.primape,segape,terceros.identificacion,medicotte,fechaing,fechaegr,dxserv,aseguradoras.primape from salud.servicios,salud.pacientesxpabellones,central.terceros left join (Select Primape,identificacion from Central.Terceros where Tipo='Asegurador' and Compania = '$Compania[0]') as aseguradoras on terceros.eps=aseguradoras.identificacion where 
		//terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and terceros.identificacion=servicios.cedula and servicios.estado='AC'
		//and pacientesxpabellones.compania='$Compania[0]' and pacientesxpabellones.ambito='$Ambito' and pacientesxpabellones.pabellon='$UnidadHosp' and pacientesxpabellones.estado='AC' and 
		//pacientesxpabellones.cedula=terceros.identificacion and tiposervicio='$Ambito' and (servicios.medicotte<>'' or servicios.medicotte<>'SIN DEFINIR MÉDICO') group by primnom,segnom,terceros.primape,segape,terceros.identificacion,medicotte,fechaing,fechaegr,dxserv,aseguradoras.primape order by primnom,segnom,terceros.primape,segape";
                if($UnidadHosp=='TODOS'){
                    $cons="select primnom,segnom,terceros.primape,segape,terceros.identificacion,medicotte,fechaing,fechaegr,dxserv,aseguradoras.primape,terceros.fecnac,pabellon from salud.servicios,salud.pacientesxpabellones,central.terceros left join (Select Primape,identificacion from Central.Terceros where Tipo='Asegurador' and Compania = '$Compania[0]') as aseguradoras on terceros.eps=aseguradoras.identificacion where terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and medicotte<>'' and terceros.identificacion=servicios.cedula and servicios.estado='AC' and pacientesxpabellones.compania='$Compania[0]' and pacientesxpabellones.ambito='$Ambito' and pacientesxpabellones.estado='AC' and pacientesxpabellones.cedula=terceros.identificacion and medicotte<>'SIN DEFINIR MÉDICO' group by primnom,segnom,terceros.primape,segape,terceros.identificacion,medicotte,fechaing,fechaegr,dxserv,aseguradoras.primape,terceros.fecnac,pabellon order by primnom,segnom,terceros.primape,segape";
                }
                else{
                    $cons="select primnom,segnom,terceros.primape,segape,terceros.identificacion,medicotte,fechaing,fechaegr,dxserv,aseguradoras.primape,terceros.fecnac,pabellon from salud.servicios,salud.pacientesxpabellones,central.terceros left join (Select Primape,identificacion from Central.Terceros where Tipo='Asegurador' and Compania = '$Compania[0]') as aseguradoras on terceros.eps=aseguradoras.identificacion where terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and medicotte<>'' and terceros.identificacion=servicios.cedula and servicios.estado='AC' and pacientesxpabellones.compania='$Compania[0]' and pacientesxpabellones.ambito='$Ambito' and pacientesxpabellones.pabellon='$UnidadHosp' and pacientesxpabellones.estado='AC' and pacientesxpabellones.cedula=terceros.identificacion and medicotte<>'SIN DEFINIR MÉDICO' group by primnom,segnom,terceros.primape,segape,terceros.identificacion,medicotte,fechaing,fechaegr,dxserv,aseguradoras.primape,terceros.fecnac,pabellon order by primnom,segnom,terceros.primape,segape";
                }
                //echo $cons;
		$res=ExQuery($cons);echo ExError();
		if(ExNumRows($res)>0){
			$ban=1;?>
			<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>Cedula</td><td>Nombre</td><td>Edad</td><td>Dx (Ingreso)</td><td>Dx (Psiquiatra)</td><td>Servicio</td><td>Última Consulta Psiquiatra</td><td>Medico</td></tr>
	<?php		while($fila=ExFetch($res)){
				$edadactual = 0;
                                $tdate = strtotime(date("Y-m-d H:i:s"));
                                $dob = strtotime($fila[10]);
                                while( $tdate > $dob = strtotime('+1 year', $dob))
                                {
                                        ++$edadactual;
                                }
                                
                                // Obtener el número del servicio del paciente
                                $consp="select dxserv,numservicio from salud.servicios where servicios.estado='AC' and servicios.cedula='$Paciente[1]'";
                                $resp=ExQuery($consp);
                                $filap=ExFetch($resp);

                                // Obtener el último Dx y fecha registrados por un Psiquiatra
                                if($filap[1]){
                                    $cons0004="select dx1,fecha,hora from histoclinicafrms.tbl00004 where numservicio=$filap[1] and cedula='$Paciente[1]' and cargo='PSIQUIATRA' ORDER BY fecha,hora desc limit 1";
                                    $res0004=ExQuery($cons0004);
                                    $fila0004=ExFetchAssoc($res0004);
                                }
                                
                                echo "<tr><td>$fila[4]</td><td>$fila[0] $fila[1] $fila[2] $fila[3]</td><td>$edadactual</td><td>$fila[8]</td><td>".$fila0004['dx1']."</td><td>$fila[11]</td><td>".$fila0004['fecha']." ".$fila0004['hora']."</td>";
				$cons2="select nombre,medicos.usuario from salud.medicos,central.usuarios,salud.cargos where 
				medicos.usuario=usuarios.usuario and medicos.compania='$Compania[0]' and cargos.compania='$Compania[0]' and cargos.cargos=medicos.cargo and tratante=1 and medicos.estadomed='Activo' order by nombre";
					//echo $cons2;
					$res2=ExQuery($cons2);?>
					<td><select name="SiMedico[<? echo $fila[4]?>]">
				<?php	while($fila2=ExFetch($res2)){
						if($fila2[1]==$fila[5]){?>
							<option value="<? echo $fila2[1]?>" selected><? echo $fila2[0]?></option>
				<?php		}
						else{?>
							<option value="<? echo $fila2[1]?>"><? echo $fila2[0]?></option>
					<?php	}
					}?>
					</select></td></tr><?php
			}
		}
		else{?>
			<tr><td  align="center" colspan="3">No Hay Pacientes Con Medico Asinado</td></tr>
	<?php	}
		if($ban==1){?>
            <tr><td align="center" colspan="8"><input type="submit" name="Guardar" value="Guardar"></td></tr>	
    <?php	}?>
    	</table>
<?php	}
	elseif($OpcAsig=="SinMed")
	{?>
        <table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center" cellpadding="4">
        	<tr><td  align="center"  bgcolor="#e5e5e5" style="font-weight:bold" colspan="8">Pacientes Sin Medico Asignado</td>
    <?php	/*$cons="select primnom,segnom,primape,segape,identificacion,medicotte from central.terceros,salud.servicios,salud.pacientesxpabellones where 
        terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and medicotte is null and terceros.identificacion=servicios.cedula and servicios.estado='AC' 
        and pacientesxpabellones.compania='$Compania[0]' and pacientesxpabellones.ambito='$Ambito' and pacientesxpabellones.pabellon='$UnidadHosp' and pacientesxpabellones.estado='AC' and 
        pacientesxpabellones.cedula=terceros.identificacion and tiposervicio='$Ambito' group by primnom,segnom,primape,segape,identificacion,medicotte order by primnom,segnom,primape,segape";*/
        if($UnidadHosp=='TODOS'){
                $cons="select primnom,segnom,terceros.primape,segape,terceros.identificacion,medicotte,fechaing,fechaegr,dxserv,aseguradoras.primape,terceros.fecnac,pabellon from salud.servicios,salud.pacientesxpabellones,central.terceros left join (Select Primape,identificacion from Central.Terceros where Tipo='Asegurador' and Compania = '$Compania[0]') as aseguradoras on terceros.eps=aseguradoras.identificacion where 
		terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and terceros.identificacion=servicios.cedula and servicios.estado='AC'
		and pacientesxpabellones.compania='$Compania[0]' and pacientesxpabellones.ambito='$Ambito' and pacientesxpabellones.estado='AC' and 
		pacientesxpabellones.cedula=terceros.identificacion and tiposervicio='$Ambito' and (servicios.medicotte='SIN DEFINIR MÉDICO' or servicios.medicotte='') group by primnom,segnom,terceros.primape,segape,terceros.identificacion,medicotte,fechaing,fechaegr,dxserv,aseguradoras.primape,terceros.fecnac,pabellon order by primnom,segnom,terceros.primape,segape";
        }
        else{
                $cons="select primnom,segnom,terceros.primape,segape,terceros.identificacion,medicotte,fechaing,fechaegr,dxserv,aseguradoras.primape,terceros.fecnac,pabellon from salud.servicios,salud.pacientesxpabellones,central.terceros left join (Select Primape,identificacion from Central.Terceros where Tipo='Asegurador' and Compania = '$Compania[0]') as aseguradoras on terceros.eps=aseguradoras.identificacion where 
		terceros.compania='$Compania[0]' and servicios.compania='$Compania[0]' and terceros.identificacion=servicios.cedula and servicios.estado='AC'
		and pacientesxpabellones.compania='$Compania[0]' and pacientesxpabellones.ambito='$Ambito' and pacientesxpabellones.pabellon='$UnidadHosp' and pacientesxpabellones.estado='AC' and 
		pacientesxpabellones.cedula=terceros.identificacion and tiposervicio='$Ambito' and (servicios.medicotte='SIN DEFINIR MÉDICO' or servicios.medicotte='') group by primnom,segnom,terceros.primape,segape,terceros.identificacion,medicotte,fechaing,fechaegr,dxserv,aseguradoras.primape,terceros.fecnac,pabellon order by primnom,segnom,terceros.primape,segape";
        }
        //echo $cons;
        $res=ExQuery($cons);echo ExError();
        if(ExNumRows($res)>0){
            $ban=1;?>
                <tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>Cedula</td><td>Nombre</td><td>Edad</td><td>Dx (Ingreso)</td><td>Dx (Psiquiatra)</td><td>Servicio</td><td>Última Consulta Psiquiatra</td><td>Medico</td></tr>
    <?php		
            while($fila=ExFetch($res)){
                // Calcula la edad en años
                $edadactual = 0;
                $tdate = strtotime(date("Y-m-d H:i:s"));
                $dob = strtotime($fila[10]);
                while( $tdate > $dob = strtotime('+1 year', $dob))
                {
                        ++$edadactual;
                }
                
                // Obtener el número del servicio del paciente
                $consp="select dxserv,numservicio from salud.servicios where servicios.estado='AC' and servicios.cedula='$Paciente[1]'";
                $resp=ExQuery($consp);
                $filap=ExFetch($resp);
                
                // Obtener el último Dx y fecha registrados por un Psiquiatra
                if($filap[1]){
                    $cons0004="select dx1,fecha,hora from histoclinicafrms.tbl00004 where numservicio=$filap[1] and cedula='$Paciente[1]' and cargo='PSIQUIATRA' ORDER BY fecha,hora desc limit 1";
                    $res0004=ExQuery($cons0004);
                    $fila0004=ExFetchAssoc($res0004);
                }
                
                echo "<tr><td>$fila[4]</td><td>$fila[0] $fila[1] $fila[2] $fila[3]</td><td>$edadactual</td><td>$fila[8]</td><td>".$fila0004['dx1']."</td><td>$fila[11]</td><td>".$fila0004['fecha']." ".$fila0004['hora']."</td>";
                $cons2="select nombre,medicos.usuario from salud.medicos,central.usuarios,salud.cargos where 
				medicos.usuario=usuarios.usuario and medicos.compania='$Compania[0]' and cargos.compania='$Compania[0]' and cargos.cargos=medicos.cargo and tratante=1 and medicos.estadomed='Activo' order by nombre";
                //echo $cons2;
                $res2=ExQuery($cons2);
                ?>
                <td><select name="NoMedico[<? echo $fila[4]?>]"><option></option>
            <?php	while($fila2=ExFetch($res2)){?>
                    <option value="<? echo $fila2[1]?>"><? echo $fila2[0]?></option>
             <?php	}?>
                </select></td></tr><?
            }
        }
        else{?>
            <tr><td  align="center" colspan="3">No Hay Pacientes Sin Medico Asinado</td>
    <?php	}
        if($ban==1){?>
            <tr><td align="center" colspan="8"><input type="submit" name="Guardar" value="Guardar"></td></tr>	
    <?php	}?>
   	 	</table><?
	}
}?>
<input type="hidden" name="AmbitoAnt" value="<? echo $Ambito?>">
<input type="hidden" name="Regresa" value="">
<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
</body>
</html>
