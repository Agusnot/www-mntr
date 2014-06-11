<?
	include("Funciones.php");//#000066 
	$ND=getdate();
?>
<HTML>
<head>
<meta http-equiv="refresh" content="5">
</head>

<body bgcolor="#6699FF">
<br><br>
<table border="5" bordercolor="#000066" cellspacing="0" cellpadding="8" width="100%" >
<tr>
<td bgcolor="#000066" align="center"><font face="Trebuchet MS, Arial, Helvetica, sans-serif" style=" font-size:35px;color:yellow">Profesional</font></td>
<td bgcolor="#000066"  align="center"><font face="Trebuchet MS, Arial, Helvetica, sans-serif" style=" font-size:35px;color:yellow">Atendiendo a</font></td>
<td bgcolor="#000066"  align="center"><font face="Trebuchet MS, Arial, Helvetica, sans-serif" style=" font-size:35px;color:yellow">En espera</font></td>
</tr>
<?
	$cons="
	select dispoconsexterna.usuario,consultorio,especialidad from salud.dispoconsexterna,salud.medicos 
	where dispoconsexterna.compania='Hospital San Rafael de Pasto' and medicos.Compania='Hospital San Rafael de Pasto'
	and medicos.usuario=dispoconsexterna.usuario and Fecha='$ND[year]-$ND[mon]-$ND[mday]' and HoraIni<=$ND[hours] and HorasFin>=$ND[hours]  and dispoconsexterna.usuario!=''
	and Consultorio!=''
	Group By dispoconsexterna.usuario,consultorio,especialidad
	";
	
//	$cons="Select NombreMod,Definicion,IdModulo from modulos where Tipo='Derecha'";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		$cons20="Select Nombre from Central.Usuarios where Usuario='$fila[0]'";
		$res20=ExQuery($cons20);
		$fila20=ExFetch($res20);
		$i++;
		if(!$nx){$nx=1;$BG="";}
		else{$nx="";$BG="";}?>
		<tr style="border-bottom-color:#FF0"><td align="right">
		<font face="Trebuchet MS, Arial, Helvetica, sans-serif" style=" font-size:28px;color:white">
<?		
		$Prof=explode(" ",$fila20[0]);
		echo "$Prof[0] $Prof[1] $Prof[2]<br>$fila[1]";?>
		</font></td><td valign="middle" style='font-size:33px;color:yellow' align='center'>

<?      
		$cons4="Select Agenda.Cedula,PrimApe,SegApe,PrimNom,SegNom from Salud.Agenda,Central.Terceros,Salud.Servicios where 
		Servicios.NumServicio=Agenda.NumServicio and
		Agenda.Cedula=Terceros.Identificacion and Agenda.Compania=Terceros.Compania and 
		Terceros.Compania=Servicios.Compania and
		Fecha='$ND[year]-$ND[mon]-$ND[mday]' and Agenda.Estado='Atendida' and Medico='$fila[0]'
		and Servicios.Estado='AC'";
		$res4=ExQuery($cons4);
		$fila4=ExFetch($res4);
		echo "$fila4[3] $fila4[1] $fila4[2]";
		echo "</td>
		<td style='font-size:33;color:yellow' align='center'>";
		
		$cons4="Select Cedula,PrimApe,SegApe,PrimNom,SegNom from Salud.Agenda,Central.Terceros where 
		Agenda.Cedula=Terceros.Identificacion and Agenda.Compania=Terceros.Compania and 
		Fecha='$ND[year]-$ND[mon]-$ND[mday]' and Estado='Activa' and Medico='$fila[0]'";
		$res4=ExQuery($cons4);
		$fila4=ExFetch($res4);
		echo "$fila4[3] $fila4[1] $fila4[2]";
		echo "</td>";
?>
        
        
		</center></td></tr>
<?	}
?>
</table>
