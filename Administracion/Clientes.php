<?
	session_start();
	include("Funciones.php");
	mysql_select_db("Central", $conex);
	$cons="select * from Clientes";
	$res=mysql_query($cons,$conex);
	if($Guardar)
	{
		$Guardar="insert into Clientes (CodigoSGSSS,Nombre,Nit,Direccion,Telefono,Regimen,Representante,Contacto,TelContacto,Observaciones)
		values('$CodigoSGSSS','$Nombre','$Nit','$Direccion','$Telefono','$Regimen','$Representante','$Contacto','$TelContacto','$Observaciones')";
		$resGuardar=mysql_query($Guardar);
		echo mysql_error();
		if($resGuardar==1){echo "<em>Cliente Registrado!</em>";}
		echo"<script languaje='javascript'> location.href='Clientes.php';</script>";

	}
	if($Eliminar)
	{
		$drop="delete from Clientes where CodigoSGSSS='$CodigoSGSSS' and Nit='$Nit'";
		$resdrop=mysql_query($drop,$conex);
		echo mysql_error();
		if($resdrop==1){echo "<em>Cliente Eliminado!</em>";}
		echo"<script languaje='javascript'> location.href='Clientes.php';</script>";
	}
?>
<head>
<title><? echo "$NomSistema[0]"?></title>

<script language="javascript">
	function Validaform1()
	{
        if(document.form1.CodigoSGSSS.value=="")
		{
			alert("Ingrese Codigo");
			document.form1.CodigoSGSSS.focus();			
			return false;
		}
		if(document.form1.Nombre.value=="")
		{
			alert("Ingrese Nombre");
			document.form1.Nombre.focus();			
			return false;
		}
		if(document.form1.Nit.value=="")
		{
			alert("Ingrese Nit");
			document.form1.Nit.focus();			
			return false;
		}
		if(document.form1.Direccion.value=="")
		{
			alert("Ingrese Direccion");
			document.form1.Direccion.focus();			
			return false;
		}
		if(document.form1.Telefono.value=="")
		{
			alert("Ingrese Telefono");
			document.form1.Telefono.focus();			
			return false;
		}
		if(document.form1.Regimen.value=="")
		{
			alert("Ingrese Regimen");
			document.form1.Regimen.focus();			
			return false;
		}
		if(document.form1.Representante.value=="")
		{
			alert("Ingrese Representante");
			document.form1.Representante.focus();			
			return false;
		}
		if(document.form1.Contacto.value=="")
		{
			alert("Ingrese Contacto");
			document.form1.Contacto.focus();			
			return false;
		}
		if(document.form1.TelContacto.value=="")
		{
			alert("Ingrese Telefono Contacto");
			document.form1.TelContacto.focus();			
			return false;
		}
		if(document.form1.Observaciones.value=="")
		{
			alert("Ingrese Observaciones");
			document.form1.Observaciones.focus();			
			return false;
		}
	}
</script>

<style type="text/css">
<!--
.Estilo3 {font-family: Tahoma; font-size: 11px; }
.Estilo9 {font-family: Tahoma; font-size: 11px; font-weight: bold; color: #FFFFFF; }
.Estilo11 {
	font-family: Tahoma;
	font-size: 11px;
	color: #000000;
	font-weight: bold;
}
-->
</style>
</head>
<body background="/Imgs/Fondo.jpg">
<? if(!$Nuevo){?> 
<table width="100%" border="1" align="center">
  <tr>
                      
    <td background="/Imgs/encabezado.jpg"><span class="Estilo9">CodigoSGSSS</span></td>
    <td background="/Imgs/encabezado.jpg"><span class="Estilo9">Nombre</span></td>
    <td background="/Imgs/encabezado.jpg"><span class="Estilo9">Nit</span></td>
    <td background="/Imgs/encabezado.jpg"><span class="Estilo9">Direccion</span></td>
    <td background="/Imgs/encabezado.jpg"><span class="Estilo9">Telefono</span></td>
    <td background="/Imgs/encabezado.jpg"><span class="Estilo9">Regimen</span></td>
    <td background="/Imgs/encabezado.jpg"><span class="Estilo9">Representante</span></td>
    <td background="/Imgs/encabezado.jpg"><span class="Estilo9">Contacto</span></td>
    <td background="/Imgs/encabezado.jpg"><span class="Estilo9">TelContacto</span></td>
    <td background="/Imgs/encabezado.jpg"><span class="Estilo9">Observaciones</span></td>
  </tr>
  <? while($fila=ExFetch($res)){?>
  <tr>
    <td><span class="Estilo3"><? echo $fila[0]?></span></td>
    <td><span class="Estilo3"><? echo $fila[1]?></span></td>
    <td><span class="Estilo3"><? echo $fila[2]?></span></td>
    <td><span class="Estilo3"><? echo $fila[3]?></span></td>
    <td><span class="Estilo3"><? echo $fila[4]?></span></td>
    <td><span class="Estilo3"><? echo $fila[5]?></span></td>
    <td><span class="Estilo3"><? echo $fila[6]?></span></td>
    <td><span class="Estilo3"><? echo $fila[7]?></span></td>
    <td><span class="Estilo3"><? echo $fila[8]?></span></td>
    <td><span class="Estilo3"><? echo $fila[9]?></span></td>
    <td><img src="/Imgs/b_drop.png" onClick="if(confirm('Eliminar?')){location.href='Clientes.php?Eliminar=1&CodigoSGSSS=<? echo $fila[0]?>&Nit=<? echo $fila[2]?>'}"style="cursor:hand"></td>
  </tr>
  <? }?>
</table>
<center><input type="button" name="Nuevo" value="Nuevo" onClick="location.href='Clientes.php?Nuevo=1'"></center>
<? }
   else{?>
	<form name="form1" action=""  onSubmit="return Validaform1()">
   
    	<table width="30%" border="1" align="center">
    <tr><td><span class="Estilo11">CodigoSGSSS</span></td>
    <td><input type="text" name="CodigoSGSSS"></td></tr>
                <tr><td><span class="Estilo11">Nombre</span></td>
          <td><input type="text" name="Nombre"></td></tr>
                <tr><td><span class="Estilo11">Nit</span></td>
          <td><input type="text" name="Nit"></td></tr>
                <tr><td><span class="Estilo11">Direccion</span></td>
          <td><input type="text" name="Direccion"></td></tr>
                <tr><td><span class="Estilo11">Telefono</span></td>
          <td><input type="text" name="Telefono"></td></tr>
                <tr><td><span class="Estilo11">Regimen</span></td>
               	  <td><select name="Regimen">
					<?
						$cons1="Select Regimen from Central.Regimenes order by Cod";
						$res1=mysql_query($cons1);
						while($fila1=ExFetch($res1))
						{
							if($fila1[0]==$Regimen){echo "<option selected value='$fila1[0]'>$fila1[0]</option>";}
							else{echo "<option value='$fila1[0]'>$fila1[0]</option>";}
						}
					?>
		 		</select></td>
                </tr>
                <tr><td><span class="Estilo11">Representante</span></td>
          <td><input type="text" name="Representante"></td></tr>
                <tr><td><span class="Estilo11">Contacto</span></td>
          <td><input type="text" name="Contacto"></td></tr>
                <tr><td><span class="Estilo11">TelContacto</span></td>
          <td><input type="text" name="TelContacto"></td></tr>
                <tr><td><span class="Estilo11">Observaciones</span></td>
          <td><input type="text" name="Observaciones"></td></tr>
                <tr>
                  <td colspan="2" background="/Imgs/encabezado.jpg"><center><input type="submit" name="Guardar" value="Guardar">
                  </center></td>
          </tr>
           </table>
    <? }?>
</form>
</body>