<? 
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if(!$Servicios||$Servicios=="Servicio Actual"){$ParteCons="and NumServicio=$NumServicio";}	
	$cons="Select AutoId,NumRegistro,FechaFormato,FechaRegistro,FechaCierre,Hora,ParClase,ParCantidad,OralClase,OralCantidad,
	Orina,MateriaFecal,Vomito,Drenaje,Succion,UsuarioCrea,UsuarioRegistra,UsuarioCierra,Estado,Observaciones 
	from historiaclinica.CtrlLiquidos where Compania='$Compania[0]'	and Cedula='$Paciente[1]' $ParteCons order by AutoId Desc";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$MatFormato[$fila[0]]=array($fila[0],$fila[2],$fila[4]);	
		$MatCtrlLiquidos[$fila[0]][$fila[1]]=array($fila[1],$fila[2],$fila[3],$fila[4],$fila[5],$fila[6],$fila[7],$fila[8],$fila[9],$fila[10],$fila[11],$fila[12],$fila[13],$fila[14],$fila[15],$fila[16],$fila[17],$fila[18],$fila[19]);		
	}
	//echo "$NumServicio --> $Servicios";
?>
<head>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="NumServicio" value="<? echo $NumServicio?>" />
<input type="hidden" name="Servicios" value="<? echo $Servicios?>" />
<center><? echo "<b>CONTROL DE LIQUIDOS <BR>$Paciente[2] $Paciente[3] $Paciente[4] $Paciente[5] - $Paciente[1]</b><br>";?></center>
<?
if($MatFormato&&$MatCtrlLiquidos)
{
	foreach($MatFormato as $Auto)
	{
		$Observaciones="";$TotAdministrados=0;$TotEliminados=0;$Diferencia=0;		
		?>
        <hr><br />
    <table align="center" border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' >       
        <tr bgcolor="#e5e5e5" style="font-weight:bold; color:#0080C0" align="center"><td colspan=13 ><? echo "Fecha y Hora de Inicio: $Auto[1]";?></td></tr>
		<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td rowspan=3>Fecha registro</td><td rowspan=3>Hora</td><td colspan=4>Liquidos administrados</td><td colspan=5>Liquidos eliminados</td><td rowspan=3>Observaciones</td><td rowspan=3>Usuario</td></tr>
		<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td colspan=2>Parenteral</td><td colspan=2>Oral</td><td rowspan=2>Orina</td><td rowspan=2>Materia fecal</td><td rowspan=2>Vomito</td><td rowspan=2>Drenaje</td><td rowspan=2>Succion</td></tr>
		<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center"><td>Clase</td><td>Cantidad</td><td>Clase</td><td>Cantidad</td></tr>
        <?
		foreach($MatCtrlLiquidos[$Auto[0]] as $NumR)
		{
			if($NumR[0]!="0")
			{
				?>
				<tr align="center">
				<td><? echo $NumR[2]?></td>
				<td><? echo $NumR[4]?></td>       
				<td><? echo $NumR[5]?></td>
				<td><? echo $NumR[6]?></td>
				<td><? echo $NumR[7]?></td>
				<td><? echo $NumR[8]?></td>
				<td><? echo $NumR[9]?></td>
				<td><? echo $NumR[10]?></td>
				<td><? echo $NumR[11]?></td>
				<td><? echo $NumR[12]?></td>
				<td><? echo $NumR[13]?></td>
                <td><? echo $NumR[18]?></td>
				<td><? echo $NumR[14]?></td>
				</tr>
				<?
				$Cant[$Auto[0]][0]=$Auto[0];
				$Cant[$Auto[0]][1]=$Cant[$Auto[0]][1]+$NumR[6];
				$Cant[$Auto[0]][2]=$Cant[$Auto[0]][2]+$NumR[8];
				$Cant[$Auto[0]][3]=$Cant[$Auto[0]][3]+$NumR[9];
				$Cant[$Auto[0]][4]=$Cant[$Auto[0]][4]+$NumR[10];
				$Cant[$Auto[0]][5]=$Cant[$Auto[0]][5]+$NumR[11];
				$Cant[$Auto[0]][6]=$Cant[$Auto[0]][6]+$NumR[12];
				$Cant[$Auto[0]][7]=$Cant[$Auto[0]][7]+$NumR[13];
				//if($NumR[18]){$Observaciones=$Observaciones."<li>".$NumR[18]."</li><br>";}
			}
		}
		$TotAdministrados=$Cant[$Auto[0]][1]+$Cant[$Auto[0]][2];
		$TotEliminados=$Cant[$Auto[0]][3]+$Cant[$Auto[0]][4]+$Cant[$Auto[0]][5]+$Cant[$Auto[0]][6]+$Cant[$Auto[0]][7];
		$Diferencia=$TotAdministrados-$TotEliminados;
		?>
        <tr  bgcolor="#e5e5e5" style="font-weight:bold" align="center">
        <td colspan="3">Totales</td><td><? echo number_format($Cant[$Auto[0]][1],0);?></td><td>---</td><td><? echo number_format($Cant[$Auto[0]][2],0);?></td><td><? echo number_format($Cant[$Auto[0]][3],0);?></td><td><? echo number_format($Cant[$Auto[0]][4],0);?></td><td><? echo number_format($Cant[$Auto[0]][5],0);?></td><td><? echo number_format($Cant[$Auto[0]][6],0);?></td><td><? echo number_format($Cant[$Auto[0]][7],0);?></td><td colspan="2"></td>
        </tr>
    </table>
    <br >
    <table align="center" border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' >       
        <tr bgcolor="#e5e5e5" style="font-weight:bold" ><td colspan=7><? echo "Fecha y Hora de Terminacion: $Auto[2]";?></td></tr>
    	<tr ><td bgcolor="#e5e5e5" style="font-weight:bold" >Balance:</td><td bgcolor="#e5e5e5" style="font-weight:bold" >Administrados:</td><td><strong><? echo $TotAdministrados;?> C.C.</td>
        <td bgcolor="#e5e5e5" style="font-weight:bold" >Eliminados:</td><td><strong><? echo $TotEliminados;?> C.C.</td><td bgcolor="#e5e5e5" style="font-weight:bold" >Diferencia</td><td>
        <? if($Diferencia<0){echo "<font color=red>";}
        else{echo "<font color=blue>";}?>
        <strong><? echo $Diferencia;?> C.C.</td>
        </tr>
        <!--<tr><td colspan=7><b>OBSERVACIONES:</b><br><? echo $Observaciones;?></td></tr>-->
	</table><br>          
	<?
	}
}
else
{?>
<center><hr /><br />No se Encontrarón Formatos para Control de Liquidos</center>
<?
}?>
</form>
</body>