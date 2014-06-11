<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$cons="select interpreta from historiaclinica.interpretacionlabs where compania='$Compania[0]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	if($fila[0]=="Si"){$SiLabs=1;}else{$SiLabs=0;}
	$RutaSistAnt="http://10.18.176.100:8080/salud/HistoriaClinica/ExLaboratorio.php?CedPaciente=$Paciente[1]";
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function AbrirInterpretacion(e,NumS,NumP)
	{	
		x = e.clientX; 
		y = e.clientY; 
		st = document.body.scrollTop;
		frames.FrameOpener.location.href="Interpretacion.php?DatNameSID=<? echo $DatNameSID?>&Numserv="+NumS+"&NumProced="+NumP;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=(y)+st;
		document.getElementById('FrameOpener').style.left=x-235;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='450px';
		document.getElementById('FrameOpener').style.height='300px';

	}
	function BuscarImg(e,NumS,NumP,CodCup)
	{	
		y = e.clientY; 
		st = document.body.scrollTop;
		frames.FrameOpener.location.href="BuscarImg.php?DatNameSID=<? echo $DatNameSID?>&Numserv="+NumS+"&NumProced="+NumP+"&CodCup="+CodCup;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top=(y)+st;
		document.getElementById('FrameOpener').style.left=373;
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='450px';
		document.getElementById('FrameOpener').style.height='90px';		
	}
</script>	
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<?
$cons="select numservicio from salud.servicios where cedula='$Paciente[1]' and compania='$Compania[0]' and estado='AC' order by numservicio desc";
$res=ExQuery($cons);$fila=ExFetch($res);
$Numserv=$fila[0];
?>
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2" align="center"> 
<tr><td colspan="4" align="center"><a style="color:blue" href="<? echo $RutaSistAnt?>">Ver Ayudas DX Sistema Anterior</a></td></tr>
<?
//if($Numserv){
	$cons="select interpretar,asigrutaimg from salud.cargos,salud.medicos 
	where medicos.usuario='$usuario[1]' and medicos.cargo=cargos.cargos and cargos.compania='$Compania[0]' and medicos.compania='$Compania[0]'";
	$res=ExQuery($cons);$fila=ExFetch($res);
	//echo $cons;
	$Interpreta=$fila[0];
	$AsignaRuta=$fila[1];
	if($SiLabs==0){$Lab="and laboratorio is null";}else{$Lab="and laboratorio is not null and laboratorio !=''";}
	
	$cons="select cups.nombre,usuarios.nombre,fechaini,cargo,interpretacion,rutaimg,usuariointerpretacion,usuariorutaimg,fechainterpretacion,fecharutaimg,numprocedimiento,cups.codigo,numservicio
	from salud.plantillaprocedimientos,contratacionsalud.cups,central.usuarios,salud.medicos
	where medicos.compania='$Compania[0]' and plantillaprocedimientos.compania='$Compania[0]' and plantillaprocedimientos.cedula='$Paciente[1]' and 
	usuarios.usuario=plantillaprocedimientos.usuario and medicos.usuario=plantillaprocedimientos.usuario and cups.compania='$Compania[0]' and cups.codigo=plantillaprocedimientos.cup and 
	numservicio=$Numserv 
	group by cups.nombre,usuarios.nombre,fechaini,cargo,interpretacion,rutaimg,usuariointerpretacion,usuariorutaimg,fechainterpretacion,fecharutaimg,numprocedimiento,cups.codigo,numservicio	
	order by numprocedimiento desc";
	
	$cons="select cups.nombre,usuarios.nombre,fechaini,cargo,interpretacion,rutaimg,usuariointerpretacion,usuariorutaimg,fechainterpretacion,fecharutaimg,numprocedimiento,cups.codigo,numservicio
	from salud.plantillaprocedimientos,contratacionsalud.cups,central.usuarios,salud.medicos
	where medicos.compania='$Compania[0]' and plantillaprocedimientos.compania='$Compania[0]' and plantillaprocedimientos.cedula='$Paciente[1]' and 
	usuarios.usuario=plantillaprocedimientos.usuario and medicos.usuario=plantillaprocedimientos.usuario and cups.compania='$Compania[0]' and cups.codigo=plantillaprocedimientos.cup  
	group by cups.nombre,usuarios.nombre,fechaini,cargo,interpretacion,rutaimg,usuariointerpretacion,usuariorutaimg,fechainterpretacion,fecharutaimg,numprocedimiento,cups.codigo,numservicio	
	order by numprocedimiento desc";
	//echo $cons;
	$res=ExQuery($cons);
	if(ExNumRows($res)>0){
		while($fila=ExFetch($res)){?>
			<tr><td  bgcolor="#e5e5e5" align="center">
    	    	<strong><div><? echo $fila[0]?></div></strong>
                <br>
        	    <div align="right"><em><? echo $fila[1]." ".$fila[3]?></em></div>
				<div align="right"><em><? echo $fila[2]?></em></div>
	        </td></tr>
    	    <tr><td align="center">
            	<strong>Interpretacion Clinica</strong>
                <div>&nbsp;</div>
            <?	$cons2="select nombre,cargo from salud.medicos,central.usuarios where compania='$Compania[0]' and medicos.usuario='$fila[6]' and usuarios.usuario=medicos.usuario";
				$res2=ExQuery($cons2);$fila2=ExFetch($res2);
				if(ExNumRows($res2)>0){?>
	                <div align="right"><em><? echo $fila2[0]." ".$fila2[1]?></em></div>
                    <div align="right"><em><? echo $fila[8]?></em></div>
                    <br>
            <?	}				
				if($fila[4]!=''){
					if($Interpreta==1){
						?>
						<div style="cursor:hand" title="Modificar" onClick="AbrirInterpretacion(event,'<? echo $fila[12]?>','<? echo $fila[10]?>')">
						<? echo $fila[4]?></div>
             	<?	}
                    else{
						echo "<div>$fila[4]</div>";
					}
				}
				else{
					if($Interpreta==1){?>
						<div style="cursor:hand">
    	                <a onClick="AbrirInterpretacion(event,'<? echo $fila[12]?>','<? echo $fila[10]?>')"><em style="color:#0066FF">Interpretar</em></a></div>
			<?		}
					else{
						echo "<div><em>Interpretar</em></div>";
					}
				}
				?>
            </td></tr>
            <tr><td align="center">
            	<strong>Imagen</strong>
                <div>&nbsp;</div>
            <?	$cons2="select nombre,cargo from salud.medicos,central.usuarios where compania='$Compania[0]' and medicos.usuario='$fila[7]' and usuarios.usuario=medicos.usuario";
				$res2=ExQuery($cons2);$fila2=ExFetch($res2);
				if(ExNumRows($res2)>0){?>
	                <div align="right"><em><? echo $fila2[0]." ".$fila2[1]?></em></div>
                    <div align="right"><em><? echo $fila[9]?></em></div>
                    <br>
            <?	}
			if($fila[5]!=''){?>
					<div>
                <?  if($AsignaRuta==1){?>
                    	<a title="Examinar" onClick="BuscarImg(event,'<? echo $fila[12]?>','<? echo $fila[10]?>','<? echo $fila[11]?>')">
                        <em style="color:#0066FF" style="cursor:hand">Ruta</em></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                <?	}
					else{?>
						<em>Ruta</em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?	}?>
                    <a href='MostrarImg.php?DatNameSID=<? echo $DatNameSID?>&Numserv=<? echo $fila[12]?>&NumProced=<? echo $fila[10]?>'><em>Ver</em></a></em></div>
			<?	}
				else{
					if($AsignaRuta==1){?>
						<div style="cursor:hand" >
    	                <a title="Examinar" onClick="BuscarImg(event,'<? echo $fila[12]?>','<? echo $fila[10]?>','<? echo $fila[11]?>')"><em style="color:#0066FF">Ruta</em></a></div>
			<?		}
					else{
						echo "<div><em>Ruta</em></div>";
					}
				}
				?>
            </td></tr>
<?		}
	}
	else
	{?>
		<tr><td colspan="7" align="center"  bgcolor="#e5e5e5" style="font-weight:bold">El Paciente No Tiene Procedimientos Activos</td></tr>
<?	}
/*}
else{?>
	<tr><td colspan="7" align="center"  bgcolor="#e5e5e5" style="font-weight:bold">El Paciente No Tiene Servicios Activos</td></tr><?
}*/
?>	
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form>
<iframe scrolling="no" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge" ></iframe> 
</body>
</html>
