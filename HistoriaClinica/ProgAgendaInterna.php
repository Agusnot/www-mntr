<?
	session_start();
	include("Funciones.php");
	mysql_select_db("HistoriaClinica", $conex);
	
	if($Asignar)
	{
		while (list($val,$cad) = each ($Check)) 
		{
			$cons2="Select FechaUlt,FechaProx from  HistoriaClinica.EjecucionAgendaInterna where Cedula='$cad' and Perfil='$Perfil' and Formato='$Formato'";
			$res2=ExQuery($cons2,$conex);
			$num=mysql_num_rows($res2);
			if($num==0)
			{
				$cons="insert into EjecucionAgendaInterna (Cedula,Perfil,Formato,TipoFormato,FechaUlt,FechaProx,Usuario,Tratante) 
				values('$cad','$Perfil','$Formato','$TF','0000-00-00','$FechaAsignar','$Usuario','SI')";
			}
			else
			{
				$cons="Update EjecucionAgendaInterna set FechaProx='$FechaAsignar' where Perfil='$Perfil' 
				and Formato='$Formato' and TipoFormato='$TF' and Cedula='$cad'";
			}
			$res=ExQuery($cons,$conex);
		}
	 }
	
?>
<script language="javascript">location.href=location.href+"#<?echo $Cedula?>";</script>
<script language='javascript' src="/calendario/popcalendar.js"></script> 

<body background="/Imgs/Fondo.jpg">
<form name="forma" method="post">
<table border="1" align="center" style="font-family:Tahoma; font-size:11px; font-weight:normal;">
    <tr bgcolor="#CCCCCC">
    	<td><strong>Tipo de Formato</strong></td>
        <td><strong>Formato</strong></td>
        <td><strong>Perfil</strong></td>
        <td><strong>Profesional</strong></td>
        <td colspan="2"><strong>Unidad</strong></td>
   	</tr>
    <tr>
    	<td><select name="TF" onChange="location.href='ProgAgendaInterna.php?TF=' + document.forma.TF.value ">
    	  <?
  				$consulta="select Nombre from  HistoriaClinica.TipoFormato ORDER BY Nombre ASC";
				$resconsulta=ExQuery($consulta);
				
				while($fila=ExFetch($resconsulta))
				{
					if($fila[0]==$TF)
					{
						echo "<option value='$fila[0]' selected>$fila[0]</option>";
					}
					else
					{
						echo "<option value='$fila[0]'>$fila[0]</option>";
					}
			}?>
  	  </select></td>
        <td><select name="Formato" onChange="location.href='ProgAgendaInterna.php?Formato=' + document.forma.Formato.value + '&TF=' + document.forma.TF.value">
          <?
  				$consulta="select Formato from  HistoriaClinica.Formatos where Estado='AC' and TipoFormato='$TF' ORDER BY TipoFormato ASC";
				$resconsulta=ExQuery($consulta);
				
				while($fila=ExFetch($resconsulta))
				{
					if($fila[0]==$Formato)
					{
						echo "<option value='$fila[0]' selected>$fila[0]</option>";
					}
					else
					{
						echo "<option value='$fila[0]'>$fila[0]</option>";
					}
			}?>
        </select>
    <td><select name="Perfil" onChange="location.href='ProgAgendaInterna.php?Formato=' + document.forma.Formato.value + '&TF=' + document.forma.TF.value + '&Perfil=' + document.forma.Perfil.value" >
      <?
						$cons="Select * from Central.Perfiles";
						$res=ExQuery($cons,$conex);
						echo "<option selected value=''>-</option>";
						while($filas=ExFetch($res))
						{
							if($filas[0]==$Perfil){echo "<option selected value='$filas[0]'>$filas[0]</option>";}
							else{echo "<option value='$filas[0]'>$filas[0]</option>";}
						}
                    ?>
    </select>   
    <td><select name="Usuario" onChange="location.href='ProgAgendaInterna.php?Formato=' + document.forma.Formato.value + '&TF=' + document.forma.TF.value + '&Perfil=' + document.forma.Perfil.value + '&Usuario=' + document.forma.Usuario.value" >
         <?
        	$Perfil=str_replace(" ","_",$Perfil);	
			$cons3 = "SELECT * FROM salud.usuarios Where $Perfil=1 Order By usuario";
			$resultado3 = ExQuery($cons3,$conex);
			while ($fila3 = ExFetch($resultado3))
			{
				if($fila3[0]=="Administrador"){echo"<option selected value='-'> - </option>";}
				else{if($Usuario==$fila3[0]){echo "<option selected value='$fila3[0]'>$fila3[0]</option>";}	else{echo "<option value='$fila3[0]'>$fila3[0]</option>";}}
			}
		?>
      </select></td>
      <td><select name="Pabellon" onChange="location.href='ProgAgendaInterna.php?Formato=' + document.forma.Formato.value + '&TF=' + document.forma.TF.value + '&Perfil=' + document.forma.Perfil.value + '&Usuario=' + document.forma.Usuario.value + '&Pabellon=' + document.forma.Pabellon.value" >
         <?
        	$cons4 = "SELECT Pabellon from  salud.pabellones Order By Pabellon";
			$resultado4 = ExQuery($cons4,$conex);
			echo "<option selected value=''>Todas las Unidades</option>";
			while ($fila4 = ExFetch($resultado4))
			{
 				if($Pabellon==$fila4[0])
				{echo "<option selected value='$fila4[0]'>$fila4[0]</option>";}
				else{echo "<option value='$fila4[0]'>$fila4[0]</option>";}
			}
		?>
      </select></td>
    
      <td><input type="button" name="cerrar" value="Cerrar" onClick="parent.location.href='/salud/Portada.php'"></td>
    
    </tr>
		
</table>
</form>
<hr>
<?
	if($Perfil)
	{
		if($Usuario){$esp=1;}
		if($Perfil=="Psicologo"){$Valor="PsicologoTte";}
		if($Perfil=="Psiquiatra"){$Valor="MedicoTratante";}
		if($Perfil=="Medico_General"){$Valor="MedGralTte";}
		if($Perfil=="Terapia_Ocupacional"){$Valor=" TerapeutaTte";}
		if($Perfil=="Nutricionista"){$Valor="NutTratante";}
		
		$cons="select hospitalizacion.Cedula,PrimNom,SegNom,PrimApe,SegApe from salud.hospitalizacion,salud.admision,salud.pacientesxpabellones
		where hospitalizacion.Cedula=admision.NumCed 
		and hospitalizacion.Cedula=pacientesxpabellones.Cedula
		and pacientesxpabellones.Estado='A'
		and hospitalizacion.Estado='A' 
		and $Valor ilike '%$Usuario%'
		and pacientesxpabellones.Pabellon ilike  '%$Pabellon%'
		order by PrimNom,SegNom ASC";
		$res=ExQuery($cons,$conex);
	?>
		<form name="FORMA" method="post">
		<table border="1" align="center" style="font-family:Tahoma; font-size:11px; font-weight:normal">
		<tr><td colspan="3" align="right"><strong>Asignar Proxima Agenda en</td><td><input type="text" name="FechaAsignar" value="<?echo $FechaAsignar?>" onClick="popUpCalendar(this, FORMA.FechaAsignar, 'yyyy-mm-dd');" /></td>
        <td><input type="submit" name="Asignar" value="Asignar" /> Ocultar
		<?if($Ocultar){?><input type="checkbox" checked="yes" name="Ocultar"/><?}
		else{?><input type="checkbox" name="Ocultar"/><?}?>
		</td></tr>
		
        <tr bgcolor="#CCCCCC">
        <td></td><td></td>
        	<td><strong>Nombre</strong></td>
            <td><strong>Fecha Ultima Valoracion</strong></td>
          	<td colspan="2"><strong>Fecha Proxima Valoracion</strong></td>
        </tr>
        	
	<? 
		$conteo=mysql_num_rows($res);$i=1;
		while($filas=ExFetch($res))
		{
			$Perfil=str_replace("_"," ",$Perfil);
			$cons2="Select FechaUlt,FechaProx from  HistoriaClinica.EjecucionAgendaInterna where Cedula='$filas[0]' and Perfil='$Perfil' and Formato='$Formato'";
			$res2=ExQuery($cons2,$conex);
			$num=mysql_num_rows($res2);
			$filas2=ExFetch($res2);
			if($Ocultar && ($filas2[1]=='0000-00-00' || $filas2[1]==""))
			{
				$Skip=0;
			}
			else{$Skip=1;}
			if(!$Ocultar){$Skip=0;}

			if($Skip==0)
			{?>
				<tr><td><?echo $i?></td><td><input type="checkbox" name="<?echo "Check[$i]"?>" value="<?echo $filas[0]?>"/>
				<td><? echo "$filas[1] $filas[2] $filas[3] $filas[4]"; ?></td>
                <? if(!$filas2[0]){$filas2[0]="0000-00-00";}?>
				<td><? echo "$filas2[0]";?></td>
				<td><? echo $filas2[1]?></td>
				</tr>
				<? $i++?>
		<? } }?>
    	</table>
<?	} ?>
</form>
</body>