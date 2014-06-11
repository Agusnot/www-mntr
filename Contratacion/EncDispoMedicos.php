<?php
        if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php"); 
	$ND=getdate();
	if(!$Anio){$Anio=$ND[year];}
	if(!$Mes){$Mes=$ND[mon];}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
	<table align="center" BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="4">
    <tr>
    	<?php
			$res=ExQuery("Select nombre,cargo,Medicos.usuario as usu from Salud.Medicos,central.usuarios 
					where Medicos.usuario=usuarios.usuario and Medicos.usuario='$Medico' and Compania='$Compania[0]'");
			$r=ExFetchArray($res);
		?>
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="4"><? echo $r[0]?></td>
    </tr>
    <tr>	
    	<td bgcolor="#e5e5e5" style="font-weight:bold" align="center" colspan="4"><? echo $r[1]?></td>
    </tr>
    <tr>
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Año</td><td><select name="Anio" onChange="document.FORMA.submit();">
        <?php
		$result=ExQuery("Select anio from Central.anios where compania='$Compania[0]' order by anio");
		while($row = ExFetchArray($result))
		{
			if($row[0]==$Anio)
			{
			?>
				<option selected value="<? echo $row[0]?>"><? echo $row[0]?></option>
			<?php }
			else
			{
			?>
				<option value="<? echo $row[0]?>"><? echo $row[0]?></option>
			<?php }
		}
		?>
        </select>
        </td>
        <td bgcolor="#e5e5e5" style="font-weight:bold">Mes</td><td><select name="Mes" onChange="document.FORMA.submit();">
        <?php
		$result=ExQuery("Select mes,numero from Central.meses order by numero");
		while($row = ExFetchArray($result))
		{
			if($row[1]==$Mes)
			{
		?>
		        <option selected value="<? echo $row[1]?>"><? echo $row[0]?></option>
        <?php 	}
			else
			{
			?>
		        <option value="<? echo $row[1]?>"><? echo $row[0]?></option>
			<?php
			}
		}
		?>
        </select>        
        </td>        
    </tr>
    <tr>
    	<td align="center" colspan="4">
            <input type="button" value="Disponibilidad" onClick="location.href='NewDispoMedicos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&Primero=1&Medico=<? echo $Medico?>'">
            <input type="button" value="Bloqueos" onClick="location.href='NewBloqDispMed.php?DatNameSID=<? echo $DatNameSID?>&Medico=<? echo $Medico?>'">
            <!--<img title="Editar" src="/Imgs/b_edit.png" style="cursor:hand" onClick="parent.location.href='NewBloqDispMed.php?DatNameSID=<? echo $DatNameSID?>'">-->
            <input type="button" value="Cerrar" onClick="location.href='ConfMedicos.php?DatNameSID=<? echo $DatNameSID?>'"></td>
    </tr>
    </table>
    <input type="hidden" name="Medico" value="<? echo $Medico?>">
    <input type="hidden" value="DatNameSID" name="<? echo $DatNameSID?>">
</form>   
</body>
<iframe frameborder="0" id="VerDispoMedicos" src="VerDispoMedicos.php?DatNameSID=<? echo $DatNameSID?>&Anio=<? echo $Anio?>&Mes=<? echo $Mes?>&Medico=<? echo $Medico?>" width="100%" height="85%"></iframe>
</body>
</html>
