<?
	session_start();
	include("Funciones.php");

	
	if($Guardar)
	{
		if($Tratante=="Si")
		{
			$cons="Update AgendaInterna set Tratante='Si' where Formato='$NewFormato' and TipoFormato='$TF' and Perfil='$Perfil'";
		}
		else
		{
			$cons="Update AgendaInterna set Tratante='No' where Formato='$NewFormato' and TipoFormato='$TF' and Perfil='$Perfil'";
		}
		$res=ExQuery($cons,$conex);
	}
?>
<head>
<title><? echo "$Sistema[$NoSistema]"?></title>
</head>
<body background="/Imgs/Fondo.jpg">
  <table width="100%" border="1" style="font : normal normal small-caps 18px Tahoma;" align="center">
<tr>
      <td><div align="center">
        <form name="FORMA">
        <table border="1" style="font : normal normal small-caps 11px Tahoma;" >
            <tr class="style1">
              <td width="124"><strong>Perfil:</strong></td>
              <td width="112" >
                <select name="Perfil" onChange="document.FORMA.submit();" >
                	<?
						$cons="Select * from Central.Perfiles";
						$res=ExQuery($cons,$conex);
						while($filas=ExFetch($res))
						{
							if($filas[0]==$Perfil){echo "<option selected value='$filas[0]'>$filas[0]</option>";}
							else{echo "<option value='$filas[0]'>$filas[0]</option>";}
						}
                    ?>
                </select>
              </td>
              <td width="94"><strong>Tratante</strong></td>
              <td width="120">
              		<?
						$cons="Select * from AgendaInterna where Formato='$NewFormato' and TipoFormato='$TF' and Perfil='$Perfil'";
						$res=ExQuery($cons,$conex);
						$filas=ExFetch($res);
						if($filas[5]=="Si"){$var="checked";}
							echo "<input name='Tratante' type='checkbox' value='Si' $var>";
				    ?>
              </td>
              
				
            </table>
        <iframe src="AgendaInterna.php?NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>&Perfil=<? echo $Perfil?>" width="100%" height="250"></iframe>
<br>
<input type="Hidden" name="NewFormato" value="<? echo $NewFormato?>">
<input type="Hidden" name="TF" value="<? echo $TF?>">
<input type="Submit" value="Guardar Registro" name="Guardar"><br>
</form>
</body>
