<?
	session_start();
	include("Funciones.php");
	if($Guardar)
	{
		$cons="Delete from Alertas.AlertasxModulos where Id='$Id'";
		$res=mysql_query($cons);
		while (list($val,$cad) = each ($Option)) 
		{
			$Cond=split("_",$val);
			$Modulo=$Cond[1];$Madre=$Cond[0];
			if(!$Modulo){$Modulo=$Madre;$Madre="";}
			$cons="Insert into Alertas.AlertasxModulos(Id,Modulo,Madre) values ('$Id','$Modulo','$Madre')";
			$res=mysql_query($cons);
			echo mysql_error();
		}
		?>
        <script language="javascript">
			alert("Las Alertas se han reprogramado Correctamente");
			window.close();
		</script>
        <?
	}
	
?>
<script language="JavaScript">
	function Marcar()
	{
		if(document.FORMA.Marcacion.checked==1){MarcarTodo();}
		else{QuitarTodo();}
	}

	function MarcarTodo()
	{
		for (i=0;i<document.FORMA.elements.length;i++) 
    	if(document.FORMA.elements[i].type == "checkbox") 
        document.FORMA.elements[i].checked=1 
	}
	function QuitarTodo()
	{
		for (i=0;i<document.FORMA.elements.length;i++) 
    	if(document.FORMA.elements[i].type == "checkbox") 
        document.FORMA.elements[i].checked=0
	}
</script>
<title><?echo $Sistema[$NoSistema]?></title>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post">
	<table border="0" border="white" rules="group" style="font-family:<? echo $Estilo[8] ?>;font-size:12;font-style:<? echo $Estilo[10]?>">
    <tr style="font-weight:bold"><td>Id: <? echo $Id?></td><td><input type="checkbox" name="Marcacion" onClick="Marcar()"></td></tr>
<?
	$cons="Select Perfil from Central.AccesoxModulos 
	where Nivel=0 Order By Id";
	$res=mysql_query($cons);
	while($fila=ExFetch($res))
	{
		$consV1="Select * from Alertas.AlertasxModulos where Id='$Id' and Modulo='$fila[0]'";
		$resV1=mysql_query($consV1);
		if(mysql_num_rows($resV1)==1){$Check1="checked";}else{$Check1="";}
		echo "<tr bgcolor='#666699' style='color:white'><td><strong>$fila[0]</td><td><input name='Option[$Madre_$fila[0]]' $Check1 type='checkbox'></td></tr>";
		$cons1="Select Perfil from Central.AccesoxModulos
		where AccesoxModulos.Madre='$fila[0]'
		and ModuloGr='$fila[0]' Order By Id";
		$res1=mysql_query($cons1);
		while($fila1=ExFetch($res1))
		{
			$consV2="Select * from Alertas.AlertasxModulos where Id='$Id' and Modulo='$fila1[0]' and Madre='$fila[0]'";
			$resV2=mysql_query($consV2);
			if(mysql_num_rows($resV2)==1){$Check2="checked";}else{$Check2="";}

			echo "<tr><td><ul>$fila1[0]</td><td><input $Check2 type='checkbox' name='Option[$fila[0]_$fila1[0]]'></td></tr>";

			$cons2="Select Perfil from Central.AccesoxModulos
			where AccesoxModulos.Madre='$fila1[0]' and ModuloGr='$fila[0]' Order By Id";
			$res2=mysql_query($cons2);
			while($fila2=ExFetch($res2))
			{
				$consV3="Select * from Alertas.AlertasxModulos where Id='$Id' and Modulo='$fila2[0]' and Madre='$fila[0]'";
				$resV3=mysql_query($consV3);
				if(mysql_num_rows($resV3)==1){$Check3="checked";}else{$Check3="";}
				echo "<tr><td><ul><ul>$fila2[0]</td><td><input $Check3 type='checkbox' name='Option[$fila[0]_$fila2[0]]'></td></tr>";

				$cons3="Select Perfil from Central.AccesoxModulos
				where AccesoxModulos.Madre='$fila2[0]' and ModuloGr='$fila[0]' Order By Id";
				$res3=mysql_query($cons3);
				while($fila3=ExFetch($res3))
				{
					$consV4="Select * from Alertas.AlertasxModulos where Id='$Id' and Modulo='$fila3[0]' and Madre='$fila[0]'";
					$resV4=mysql_query($consV4);
					if(mysql_num_rows($resV4)==1){$Check3="checked";}else{$Check3="";}
					echo "<tr><td><ul><ul><ul>$fila3[0]</td><td><input $Check3 type='checkbox' name='Option[$fila[0]_$fila3[0]]'></td></tr>";
				}		
			}
		}
	}
?>
</table>
<br>
<center>
<input type="hidden" name="Usuario" value="<? echo $Usuario?>">
<input type="submit" name="Guardar" value="Guardar">
</form>
</body>