<?
	session_start();
	include("Funciones.php");

	mysql_select_db('HistoriaClinica',$conex);
	
	if($Guardar)
	{ 
		$cons="Select * from ItemsxFormatos where Formato='$NewFormato' and TipoFormato='$TF' and Pantalla=($Pantalla-1)";
		$res=ExQuery($cons,$conex);
		if((mysql_num_rows($res)>0)||($Pantalla==1))
		{
			if(!$IdItem)
			{
				$cons="Select Id_Item from ItemsxFormatos where Formato='$NewFormato' Group By Id_Item Order By Id_Item Desc";
				$res=ExQuery($cons,$conex);
				$fila=ExFetch($res);
				$IdItem=$fila[0]+1;
			}
			
			$cons="Insert into ItemsxFormatos (Formato,Id_Item,Item,Pantalla,TipoDato,LimInf,LimSup,Longitud,TipoControl,Ancho,Alto,Defecto,TipoFormato,Parametro) values ('$NewFormato',$IdItem,'$Item','$Pantalla','$TipoDato','$LimInf','$LimSup','$Longitud','$TipoControl','$Ancho','$Alto','$Defecto','$TF','$Parametro')";
			$res=ExQuery($cons,$conex);
			echo ExError($conex);
			$Pantalla="";
			$Item="";
			$TipoDato="";
			$LimInf="";
			$LimSup="";
			$Longitud="";
			$TipoControl="";
			$Ancho="";
			$Alto="";
			$Defecto="";
			$IdItem="";
			$Parametro="";
			$Modificar=0;
		}
		else
		{?>
			<script language="JavaScript">
				alert("La pantalla no tiene secuencia!!!");
			</script>
	<?	}
	}
	if($Eliminar)
	{
		$cons="Delete from ItemsxFormatos where Formato='$NewFormato' and Id_Item='$IdItem' and TipoFormato='$TF' LIMIT 1";
		$res=ExQuery($cons,$conex);$IdItem="";
	}
	if($Modificar)
	{
		$cons="Select * from ItemsxFormatos where Formato='$NewFormato' and Id_Item=$IdItem and TipoFormato='$TF'";
		$res=ExQuery($cons,$conex);
		$fila=mysql_fetch_array($res);
		$Pantalla=$fila['Pantalla'];
		$IdItem=$fila['Id_Item'];
		$Item=$fila['Item'];
		$TipoDato=$fila['TipoDato'];
		$TF=$fila['TipoFormato'];

		$LimInf=$fila['LimInf'];
		$LimSup=$fila['LimSup'];
		$Longitud=$fila['Longitud'];
		$TipoControl=$fila['TipoControl'];
		$Ancho=$fila['Ancho'];
		$Alto=$fila['Alto'];
		$Defecto=$fila['Defecto'];

		$cons="Delete from ItemsxFormatos where Formato='$NewFormato' and Id_Item=$IdItem and TipoFormato='$TF' LIMIT 1";
		$res=ExQuery($cons,$conex);
	
	}
?>


<style>
<!--
.style1 {
	font-size: 11px;
	font-weight: bold;
	font-family:Tahoma
}
-->
</style>
<script language="JavaScript">
	
	function validar()
	{
		if(document.FORMA.Defecto.value==""){document.FORMA.Defecto.value=0}
		if(document.FORMA.LimSup.value==""){document.FORMA.LimSup.value=0}
		if(document.FORMA.LimInf.value==""){document.FORMA.LimInf.value=0}
		
		if((document.FORMA.Pantalla.value=="")||(document.FORMA.Item.value=="")||(document.FORMA.TipoDato.value=="")||(document.FORMA.Longitud.value=="")||(document.FORMA.Alto.value==""))
		{
				alert("Por Favor Ingrese Todos los Datos!");return false;
		}
		
		if((document.FORMA.Ancho.value==0)||(document.FORMA.Ancho.value==""))
		{
			alert("El ancho debe ser mayor que cero");return false;
		}

	}
	
</script>

<head>
<title><?echo "$Sistema[0] - $NomClinica[0]"?></title>
</head>
<body background="/Imgs/Fondo.jpg" onLoad="document.FORMA.Pantalla.focus();">
        <div align="center">
		<form name="FORMA" method="post" onSubmit="return validar()">
          <table width="100%" border="0" style="font : normal normal small-caps 11px Tahoma;">
            <tr bgcolor="#CCCCCC" class="style1" align="center">
              <td>id </td>
              <td><strong>Pant</strong></td>
              <td><strong>Item </strong></td>
              <td><strong>TDato </strong></td>
              <td><strong>Lim Inf</strong></td>
              <td><strong>Lim Sup </strong></td>
              <td><strong>Long</strong></td>
              <td><strong>Tipo Control </strong></td>
              <td><strong>Ancho </strong></td>
              <td><strong>Alto </strong></td>
              <td><strong>Def</strong></td>
              <td><strong>Parametros</strong></td>
            </tr>
			<?
			$cons="Select * from ItemsxFormatos where Formato='$NewFormato' and TipoFormato='$TF' order by Pantalla,Id_Item";
			$res=ExQuery($cons,$conex);
			while($fila=mysql_fetch_array($res))
			{
				if($Fondo==1){$BG="#EEF6F6";$Fondo=0;}
				else{$BG="";$Fondo=1;}
			?>
				<tr bgcolor="<?echo $BG?>" align="center">
				  <td><?echo $fila['Id_Item']?></td>
				  <td><?echo $fila['Pantalla']?></td>
                  <td><?echo $fila['Item']?></td>
                  <td><?echo $fila['TipoDato']?></td>
                  <td><?echo $fila['LimInf']?></td>
				  <td><?echo $fila['LimSup']?></td>
                  <td><?echo $fila['Longitud']?></td>
                  <td><?echo $fila['TipoControl']?></td>
                  <td><?echo $fila['Ancho']?></td>
				  <td><?echo $fila['Alto']?></td>
				  <td><?echo $fila['Defecto']?></td>
  				  <td><?echo $fila['Parametro']?></td>
				  <td><a href="ItemsxFormato.php?Modificar=1&NewFormato=<? echo $NewFormato?>&IdItem=<?echo $fila['Id_Item']?>&TF=<?echo $TF?>"><img src="/Imgs/HistoriaClinica/b_edit.png" border="0"></a></td>
				<td><a href="ItemsxFormato.php?Eliminar=1&NewFormato=<? echo $NewFormato?>&IdItem=<? echo $fila['Id_Item']?>&TF=<? echo $TF?>"onClick="if(confirm('Eliminar?')){location.href='ItemsxFormato.php?Eliminar=1&NewFormato=<? echo $NewFormato?>&IdItem=<? echo $fila['Id_Item']?>&TF=<? echo $TF?>'}"><img src="/Imgs/HistoriaClinica/b_drop.png" border="0"></a></td>
				</tr>
<?			}?>
			  <tr align="center">
			    <td>&nbsp;</td>
              <td><input type="text" name="Pantalla" style="width:30" value="<?echo $Pantalla?>"></td>
              <td><input type="text" name="Item" style="width:100" value="<?echo $Item?>"></td>
              <td><select name="TipoDato">
			  	<?
			  		$cons="Select * from TiposDatos Order By Tipo";
					$res=ExQuery($cons,$conex);
					while($fila=ExFetch($res))
					{
						if($fila[0]==$TipoDato){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
						else{echo "<option value='$fila[0]'>$fila[0]</option>";}
					}
				?></select></td>
              <td><input type="text" name="LimInf" style="width:30" value="<?echo $LimInf?>"></td>
              <td><input type="text" name="LimSup" style="width:30" value="<?echo $LimSup?>"></td>
              <td><input type="text" name="Longitud" style="width:30" value="<?echo $Longitud?>"></td>
              <td>
                <select name="TipoControl">
                  <?
					$cons="Select * from TipoControl Order By Tipo";
					$res=ExQuery($cons,$conex);
					while($fila=ExFetch($res))
					{
						if($fila[0]==$TipoControl){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
						else{echo "<option value='$fila[0]'>$fila[0]</option>";}
					}
				?>
                </select></td>
              <td><input type="text" name="Ancho" style="width:30" value="<?echo $Ancho?>"></td>
              <td><input type="text" name="Alto" style="width:30" value="<?echo $Alto?>"> </td>
              <td><input type="text" name="Defecto" style="width:30" value="<?echo $Defecto?>" ></td>
              <td><input type="text" name="Parametro" style="width:120" value="<?echo $Parametro?>" ></td>
			  <td><input type="submit" value="G" name="Guardar"></td>
          </tr>
         </table>
        </td>
    </tr>
  </table>

  <input type="Hidden" name="IdItem" value="<?echo $IdItem?>">
  <input type="Hidden" name="NewFormato" value="<?echo $NewFormato?>">
  <input type="Hidden" name="TF" value="<? echo $TF?>">
</form>
        <p>&nbsp;</p>
<p><br>
</p>
