<?
if($DatNameSID){session_name("$DatNameSID");}
session_start();
include("Funciones.php");
if($Eliminar)
{
	$cons="delete from nomina.contratos where compania='$Compania[0]' and identificacion='$Identificacion' and numero='$Numero'";
	//echo $cons;
	$res=ExQuery($cons);
	$cons="delete from nomina.epsxc where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$Numero'";
	$res=ExQuery($cons);
	$cons="delete from nomina.arpxc where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$Numero'";
	$res=ExQuery($cons);
	$cons="delete from nomina.pensionesxc where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$Numero'";
	$res=ExQuery($cons);
	$cons="delete from nomina.cesantiasxc where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$Numero'";
	$res=ExQuery($cons);
	$cons="delete from nomina.salarios where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$Numero'";
	$res=ExQuery($cons);
	$cons="delete from nomina.centrocostos where compania='$Compania[0]' and identificacion='$Identificacion' and numcontrato='$Numero'";
	$res=ExQuery($cons);
}
//------------------------------------------------------------
$cont=0;
//echo $cont;
$cons="select identificacion,primnom,segnom,primape,segape,tipodoc,lugarexp,pais,departamento,municipio,direccion,compania,email,fecnac,tiposangre,sexo,ecivil,telefono from central.terceros where Compania='$Compania[0]' and identificacion='$Identificacion' and (tipo='Empleado' or regimen='Empleado')";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	if(!$fila[1]){$cont++;}
	if(!$fila[3]){$cont++;}
	if(!$fila[6]){$cont++;}
	if(!$fila[7]){$cont++;}
	if(!$fila[8]){$cont++;}
	if(!$fila[9]){$cont++;}
	if(!$fila[10]){$cont++;}
	if(!$fila[13]){$cont++;}
	if(!$fila[14]){$cont++;}
	if(!$fila[15]){$cont++;}
	if(!$fila[16]){$cont++;}
//	echo $cont;
if($cont==0)
{
?>
    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script language='javascript' src="/Funciones.js"></script>
    </head>
    <body background="/Imgs/Fondo.jpg">
    
    <? 
    $cons="select numero,fecinicio,fecfin,estado,cargo,tipocontrato,tipovinculacion from nomina.contratos where identificacion='$Identificacion' and compania='$Compania[0]' order by numero,fecinicio";
    $res=Exquery($cons);
    $cont=(ExNumRows($res));
    if($cont>0)
    {
        ?>
        <table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' align="center">
        <tr bgcolor="#666699"style="color:white" align="center"><td colspan="10">LISTADO DE CONTRATOS</td>
        <tr align="center"><td>Numero</td><td>Fecha Inicio</td><td>Fecha Fin</td><td>Estado</td><td>Cargo</td><td>Tipo Contrato</td><td>Tipo Vinculacion</td><td colspan="3">&nbsp;</td>
        </tr>
    <?
        while ($fila = ExFetch($res))
        {
            $cons1="select cargo from nomina.cargos where compania='$Compania[0]' and codigo='$fila[4]'";
            $res1=ExQuery($cons1);
            $fila1=ExFetch($res1);
            $cons2="select tipovinculacion from nomina.tiposvinculacion where compania='$Compania[0]' and codigo='$fila[6]'";
            $res2=ExQuery($cons2);
            $fila2=ExFetch($res2);
            ?>
            <tr><td align="center"><? echo $fila[0]; ?></td><td><? echo $fila[1]; ?></td><td><? echo $fila[2]; ?></td><td><? echo $fila[3] ?></td>
            <td><? echo $fila1[0]; ?></td><td><? echo $fila[5]; ?></td><td><? echo $fila2[0]; ?></td>
            <?
			$consnom="select anio from nomina.nomina where numero='$fila[0]'";
			$resnom=ExQuery($consnom);
			$ConContr=ExNumRows($resnom);
//			echo $consnom;
?>			<td width="16px"><a href="Contrato.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Editar=1&Numero=<? echo $fila[0]?>"/><img src="/Imgs/b_edit.png" border="0" title="Editar" /></td>
<?
			if($ConContr==0)
			{
			?>
                <td width="16px"><a href="#" onClick="if(confirm('Desea Eliminar este Contrato ?')){location.href='InicioContrato.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&Eliminar=1&Numero=<? echo $fila[0]?>'};"/><img src="/Imgs/b_drop.png" border="0" title="Eliminar"/></td>
    <?		}
			?>
<!--			<td width="16px"><img src="/Imgs/b_print.png" border="0" title="Editar" /></td></tr> -->
<?			
		}
    }
    else
    {?>
        <center>No hay Contratos para este Empleado !!!</center>
    <? }
    $cont1=0;
    $ConsN="select estado from nomina.contratos where compania='$Compania[0]' and identificacion='$Identificacion'";
    //echo $ConsN;
    $resN=ExQuery($ConsN);
    while($fila=ExFetch($resN))
    {
        if($fila[0]=="Activo")
        { 
            $cont1=$cont1+1;
        }
    }
    
    ?>
    </table>
    <center><input type="button" name="Nuevo" value="Nuevo" onClick="location.href='Contrato.php?DatNameSID=<? echo $DatNameSID?>&Identificacion=<? echo $Identificacion?>&New=1';" <? if($cont1>0){ echo "Disabled";} ?>/>
    </center>
    </body>
    </html>
<?
}
else
{
	?>
        <center>No Esta Completo los Datos Personales del Empleado !!!</center>
    <?
//	echo $cont;
}
?>