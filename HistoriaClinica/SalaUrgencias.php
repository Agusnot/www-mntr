<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");	
	$ND=getdate();
	//---
	if($Eliminar)
	{
		$cons="Delete from salud.salaurgencias where Compania='$Compania[0]' and cedula='$CedPac' and fechasala='$Fec'";	
		$res=ExQuery($cons);
	}
	//---
	if($Atender)
	{
		$cons="select ambito from salud.ambitos where compania='$Compania[0]' and urgencias=1 and ambito!='Sin Ambito'";
		$res=ExQuery($cons);
		$fila=ExFetch($res); $Ambito=$fila[0];
		
		$cons="select nocarnet,tipousu,nivelusu from central.terceros where compania='$Compania[0]' and identificacion='$CedPac'";
		$res=ExQuery($cons);
		$fila=ExFetch($res); $NoCarnet=$fila[0]; $TipoUsu=$fila[1]; $NivelUsu=$fila[2];
		//echo $cons;
		$cons = "Select numservicio from Salud.Servicios where Compania = '$Compania[0]' order by numservicio desc";					
		$res = ExQuery($cons);
		$fila = ExFetch($res);			
		$AutoId = $fila[0] +1;
		
		$cons="insert into salud.servicios (cedula,numservicio,tiposervicio,fechaing,tipousu,nivelusu,estado,nocarnet,compania,medicotte,ingreso) values 
		('$CedPac',$AutoId,'$Ambito','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$TipoUsu','$NivelUsu','AC','$NoCarnet','$Compania[0]','$usuario[1]',1)";		
		$res=ExQuery($cons);
		
		$cons="select entidad,contrato,nocontrato,usuario from salud.salaurgencias where compania='$Compania[0]' and cedula='$CedPac'";
		$res=ExQuery($cons);
		$fila=ExFetch($res); $Entidad=$fila[0]; $Contra=$fila[1]; $NoContra=$fila[2]; $UsuPagador=$fila[3];
		
		$cons="insert into salud.pagadorxservicios (numservicio,compania,entidad,contrato,nocontrato,fechaini,usuariocre,fechacre) values
		($AutoId,'$Compania[0]','$Entidad','$Contra','$NoContra','$ND[year]-$ND[mon]-$ND[mday]','$UsuPagador','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]')";
		//echo $cons;
		$res=ExQuery($cons);
		
		$cons="update salud.salaurgencias set medicoatendio='$usuario[1]',numservicio=$AutoId,fechaatendio='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]'
		where compania='$Compania[0]' and cedula='$CedPac' and salaurgencias.medicoatendio is null";
		$res=ExQuery($cons);
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
		?>
        <script language='JavaScript'>
			parent.parent.location.href="ResultBuscarHC.php?DatNameSID=<? echo $DatNameSID?>&Cedula=<? echo $CedPac?>&Buscar=1";
		</script>
<?	}
	
	$cons="select asistencial,especialidad from salud.cargos,salud.medicos where cargos.compania='$Compania[0]' and medicos.compania='$Compania[0]' and medicos.cargo=cargos.cargos 
	and usuario='$usuario[1]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res); $BanAsist=$fila[0]; $Especialidad=$fila[1];
	
	$cons="select super from central.usuarios where usuario='$usuario[1]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);	
	$Super=$fila[0];
	
	if($Super!=1){$Esp="and especialidad='$Especialidad'";}
	$cons="select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom),cedula,salaurgencias.triage,fechasala,especialidad,numservicio
	from salud.salaurgencias,central.terceros where salaurgencias.compania='$Compania[0]' and salaurgencias.medicoatendio is null and cedula=identificacion 
	and terceros.compania='$Compania[0]' $Esp	order by valor desc";
	$res=ExQuery($cons);
?>

<html>
<head>
<meta http-equiv="refresh" content="90" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<?
if($BanAsist||$Super){
		if(ExNumRows($res)>0){?>
    	<table border="1" bordercolor="#e5e5e5"  align="center" style='font : normal normal small-caps 13px Tahoma;' width="100%">      	
        <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center" >
            <td colspan="6">SALA DE ESPERA - URGENCIAS</td>
        </tr>
            <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center" >
                <td>Identificacion</td><td>Nombre</td><td>Triage</td><td>Especialidad</td><td>Llegada</td><td></td>
            </tr>
    <?		while($fila=ExFetch($res))
            {?>
                <tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
                    <td><? echo $fila[1]?></td><td><? echo $fila[0]?> </td><td><? echo $fila[2]?></td><td><? echo $fila[4]?></td><td><? echo $fila[3]?></td>
                    <td><button title="Atender" style="cursor:hand" onClick="location.href='SalaUrgencias.php?DatNameSID=<? echo $DatNameSID?>&CedPac=<? echo $fila[1]?>&Atender=1'">
                            <img src="/Imgs/b_check.png">
                        </button>
                        <?
                        if($Super==1)
						{?>
                        <button title="Eliminar" style="cursor:hand" onClick="if(confirm('Se va a elimiar el paciente <? echo $fila[0]?> de la sala de espera, desea continuar?')){location.href='SalaUrgencias.php?DatNameSID=<? echo $DatNameSID?>&CedPac=<? echo $fila[1]?>&Fec=<? echo $fila[3]?>&Eliminar=1'}">
                            <img src="/Imgs/b_drop.png">
                        </button>
                        <?
						}?>
                    </td>
                </tr>		
    <?		}
			?>
            </table>
            <?
        }
		else{
			echo "<tr><td><br><font size=5 color='BLUE'><center><em>No Hay Pacientes en Sala de Espera</em></center></font><br></td></tr>";
		}?>       
	
	<hr align="center" width="100%">
	<?
	//--------------Atendidos----
	if($Super!=1){$Esp="and especialidad='$Especialidad'";}
	$cons="select cedula, (primape || ' ' || segape || ' ' || primnom || ' ' || segnom),salaurgencias.triage,especialidad,fechasala,
	fechaatendio,entidad,contrato,nocontrato
	from salud.salaurgencias,central.terceros where salaurgencias.compania='$Compania[0]' and salaurgencias.medicoatendio is NOT null 
	and cedula=identificacion and terceros.compania='$Compania[0]' $Esp and fechaatendio>='$ND[year]-$ND[mon]-$ND[mday] 00:00:00' and 
	fechaatendio<='$ND[year]-$ND[mon]-$ND[mday] 23:59:59'	order by fechaatendio desc";
	$res=ExQuery($cons);
	?>
	<table border="1" bordercolor="#e5e5e5"  align="center" style='font : normal normal small-caps 12px Tahoma;' width="100%">
    <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"  ><td colspan="9">PACIENTES ATENDIDOS - URGENCIAS (hoy)</td></tr>
    <?
    if(ExNumRows($res)>0)
	{?>
     <tr bgcolor="#e5e5e5" style="font-weight:bold" align="center" >
	 <td>Identificacion</td><td>Nombre</td><td>Triage</td><td>Especialidad</td><td>Entidad</td><td>Contrato</td><td>No. Contrato</td><td>Llegada</td><td>Atendido</td>
     </tr>
	<?
		while($fila=ExFetch($res))
		{
			$cons1="Select (primape || ' ' || segape || ' ' || primnom || ' ' || segnom) from central.terceros where compania='$Compania[0]' and
			identificacion='$fila[6]'";
			$res1=ExQuery($cons1);
			$fila1=ExFetch($res1);
			?>
			<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
             <td><? echo $fila[0]?></td><td><? echo $fila[1]?></td><td><? echo $fila[2]?></td><td><? echo $fila[3]?></td><td><? echo $fila1[0]?></td><td><? echo $fila[7]?></td><td><? echo $fila[8]?></td><td><? echo $fila[4]?></td><td><? echo $fila[5]?></td>
            </tr>
			<?
		}
    }
	else
	{?>
	<tr  align="center"  ><td colspan="6">No se han atendido pacientes en el transcurso del dia!!!</td></tr>	
	<?
    }	?>
    </table>
    <?	
}?>    
</form>
</body>
</html>
