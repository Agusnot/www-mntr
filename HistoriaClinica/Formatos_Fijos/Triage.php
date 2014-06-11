<?php
	if($DatNameSID){session_name("$DatNameSID");}	
	session_start();		
	include("Funciones.php");	
	$ND=getdate();
	$cons="Select entidadurg,contratourg,nocontratourg from central.terceros where Compania='$Compania[0]' and identificacion='$Paciente[1]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$EntidadUrg=$fila[0];$ContratoUrg=$fila[1];$NoContratoUrg=$fila[2];
	//if(empty($EntidadUrg)||empty($ContratoUrg)||empty($NoContratoUrg))
	if (($EntidadUrg==null)||($ContratoUrg==null)||($NoContratoUrg==null))
	{
		echo "<center><strong>No se ha guardado la entidad, contrato y numero de contrato para el servicio de Urgencias!!!</strong></center>";	
		
	}
	else
	{
		if($Agregar){
			$cons="select valor from salud.triage,salud.prioridadtriage where triage.compania='$Compania[0]' and triage.prioridad=prioridadtriage.prioridad 
			and prioridadtriage.compania='$Compania[0]' and triage.triage='$Triage'";
			$res=ExQuery($cons);
			$fila=ExFetch($res);
			$cons2="insert into salud.salaurgencias (compania,triage,valor,fechasala,usuario,cedula,entidad,contrato,nocontrato,especialidad) values 
			('$Compania[0]','$Triage',$fila[0],'$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$usuario[1]','$Paciente[1]','$EntidadUrg','$ContratoUrg','$NoContratoUrg','$Especilaidad')";
			$res2=ExQuery($cons2);?>
			<script language="javascript">
				parent.document.FORMA.submit();
			</script>
	<?	}
	}
	$cons="select eps from central.terceros where compania='$Compania[0]' and identificacion='$Paciente[1]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	if(!$Entidad){$Entidad=$fila[0];}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function CerrarThis()
	{	
		//parent.document.FORMA.submit();
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
	function Validar()
	{
		/*if(document.FORMA.Entidad.value==""){alert("Debe seleccionar una entidad!!!");return false;}
		if(document.FORMA.Entidad.value==""){alert("Debe seleccionar un contrato!!!");return false;}
		if(document.FORMA.Entidad.value==""){alert("Debe seleccionar un numero de contrato!!!");return false;}*/
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="button" value=" X " onClick="CerrarThis()" style="position:absolute;top:1px;right:1px;" title="Cerrar esta ventana">
<?
$EntidadUrg=$_GET['EntidadUrg'];
$ContratoUrg=$_GET['ContratoUrg'];
$NoContratoUrg=$_GET['NoContratoUrg'];
if(($EntidadUrg!=NULL)&&($ContratoUrg!=NULL)&&($NoContratoUrg!=NULL))
{?>

<table bordercolor="#e5e5e5" border="1" style='font : normal normal small-caps 12px Tahoma;' align="center">
<tr>
<td></td>
</tr>
<tr>
	<td bgcolor="#e5e5e5" align="center" style="font-weight:bold">Triage</td>
	<td colspan="3"><select name="Triage" style=" width: 400px"><option></option>
	<?	echo $cons="Select triage,prioridad from Salud.Triage where compania='$Compania[0]' order by triage";
        $res=ExQuery($cons);
        while($fila=ExFetch($res))
        {
            if($Triage==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
            else{echo "<option value='$fila[0]'>$fila[0]</option>";}
        }?>
		</select>
    </td>
    <td bgcolor="#e5e5e5" style="font-weight:bold">Especialidad</td>
    <td colspan="3">
    <?	$cons="select especialidad from salud.especialidades where compania='$Compania[0]' order by especialidad";
        $res=ExQuery($cons);?>
        <select name="Especilaidad">
        <?	while($fila=ExFetch($res))
            {
                 if($Especialidad==$fila[0]){echo "<option value='$fila[0]' selected>$fila[0]</option>";}   
                 else{echo "<option value='$fila[0]'>$fila[0]</option>";}   
            }?>
        </select>
    </td>
    </tr>
</table>
<center><input type="submit" value="Agregar a Sala de Espera" name="Agregar"></center>
<?
}?>
</form>
</body>
</html>
