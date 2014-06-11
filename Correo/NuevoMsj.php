<?
	session_start();
	include("Funciones.php");
	if(!$usuario[0]){exit;}
	mysql_select_db("salud", $conex);
	$ND=getdate();
	if($Enviar)
	{
		mysql_select_db("Correo", $conex);
		$Mensaje=str_replace("\n","<br />",$Mensaje);
	
		$cons="Select Id from Mensajes Group By Id Order By Id Desc;";
		$res=mysql_query($cons);
		$fila=ExFetch($res);
		$Id=$fila[0]+1;

		
		if($Usuario=="Todos")
		{
			$Perfil=str_replace(" ","_",$Perfil);
			mysql_select_db("salud", $conex);
			if($Perfil=="T")
			{
				$consPrev = "SELECT Usuario FROM usuarios Order By usuario";
			}
			else
			{
				$consPrev = "SELECT Usuario FROM usuarios Where $Perfil=1 Order By usuario";
			}
			$resPrev = mysql_query($consPrev);
			mysql_select_db("Correo", $conex);
			while ($filaPrev=ExFetch($resPrev))
			{
				$cons="Insert into Mensajes(Id,UsuarioCre,Fecha,PerfilDest,UsuarioDest,Mensaje,Leido,Borrar,Asunto)
				values($Id,'$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]','$Perfil','$filaPrev[0]','$Mensaje',0,0,'$Asunto')";
				$res=mysql_query($cons);
				echo mysql_error();
				$Id++;
				}
		}
		else
		{
			$cons="Insert into Mensajes(Id,UsuarioCre,Fecha,PerfilDest,UsuarioDest,Mensaje,Leido,Borrar,Asunto)
			values($Id,'$usuario[0]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]','$Perfil','$Usuario','$Mensaje',0,0,'$Asunto')";
			$res=mysql_query($cons);
			echo mysql_error();
		}
		?>
		<script language="JavaScript"> location.href='Inicio.php'; </script>
		<?
	}
?>
<script language="JavaScript">
	function Validar()
	{
		if(document.FORMA.Perfil.value=="-")
		{
			alert ("Seleccione un perfil");document.FORMA.Perfil.focus();return false;
		}

		if(document.FORMA.Usuario.value=="-")
		{
			alert ("Seleccione un Usuario");document.FORMA.Usuario.focus();return false;
		}
		if(document.FORMA.Asunto.value=="")
		{
			alert ("Escriba un asunto");document.FORMA.Asunto.focus();return false;
		}
		if(document.FORMA.Mensaje.value=="")
		{
			alert ("Escriba un mensaje");document.FORMA.Mensaje.focus();return false;
		}
		
		txt=document.FORMA.Mensaje.value;
	    txt = txt.replace(/\n/g, "<br>");
		document.FORMA.Mensaje.value=txt;
	}
</script>
<html>
<head><title>Correo electrónico Institucional</title> </head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" onSubmit="return Validar()" method="post">
<table width="200" border="0" style="font-family:Tahoma; font-size:11px; font-variant:small-caps; color:#FFF; font-weight:bold">
  <tr align="center"><td colspan="4" background="/Imgs/encabezado.jpg">Datos Destinatario</td></tr>
  <tr>
  	<td background="/Imgs/encabezado.jpg">Perfil</td>
    <td><select name="Perfil" onChange="location.href='NuevoMsj.php?Perfil='+this.value">
           <option value="-">-</option>
            <?
			if($Perfil=="T")
			{ 
			  	echo"<option value='T' selected>Todos</option>"; }else{
			  	echo"<option value='T'>Todos</option>";}
           		$cons="Select * from CentralOld.Perfiles Order By Perfil";
				$res=mysql_query($cons);
				while($fila=ExFetch($res))
				{
					if($Perfil==$fila[0]){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
					else{echo "<option value='$fila[0]'>$fila[0]</option>";}
				}?>
		 </select>
	</td>
	<td background="/Imgs/encabezado.jpg">Usuario</td>
	<td><select name="Usuario">
			<option value="-">-</option>
			<option value="Todos">Todos</option>
			<?
				$Perfil=str_replace(" ","_",$Perfil);
				if($Perfil=="T")
				{
					$cons="Select * from usuarios Order By Usuario";
				}
				else
				{
					$cons = "SELECT * FROM usuarios Where $Perfil=1 Order By usuario";
				}
				$resultado = mysql_query($cons,$conex);
				while ($fila = ExFetch($resultado))
				{
					echo "<option value='$fila[0]'>$fila[0]</option>";
				}
			?>
          </select>
     </td>
    </tr>     
      <tr align="center"><td colspan="4" background="/Imgs/encabezado.jpg">Redactar Mensaje</td></tr>
      <tr>
      	<td background="/Imgs/encabezado.jpg">Asunto:</td>
        <td colspan="3"><input name="Asunto" type="text" size="50"></td>
      </tr>
      
      <tr><td colspan="4"><textarea style="width:550px;height:120px;" name="Mensaje"></textarea></td></tr>
      <tr align="left"><td colspan="4" align="left" background="/Imgs/encabezado.jpg"><input type="Submit" name="Enviar" value="Enviar"></td>
 </table>
</form>
</body>
</html>
