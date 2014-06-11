<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();	
	include("Funciones.php");	
	$ND=getdate();	
	if($Guardar){
		if(!$Edit){
			$cons2="select usuario,nombre,cedula from facturacion.firmasrtaglosas where compania='$Compania[0]' and usuario='$UsuarioGlosa'";
			$res2=ExQuery($cons2);
			//echo $cons2;
			if(ExNumRows($res2)<=0){
	$cons="insert into facturacion.firmasrtaglosas (compania,usuario,firmadocumento) values ('$Compania[0]','$UsuarioGlosa','$Permisos')";	
				$res=ExQuery($cons); echo ExError();
				?><script language="javascript">location.href="AdminUsuarios.php?DatNameSID=<? echo $DatNameSID?>";</script><?		
			}					
			else{
				?><script language="javascript">alert("Este usuario ya ha sido ingresado!!!");</script><?
				
			}
		}				
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Validar(){
		if(document.FORMA.UsuarioGlosa.value==""){alert("Debe digitar El usuario!!!"); return false;}
		if(document.FORMA.NombreGlosa.value==""){alert("Debe digitar un nombre de usuario"); return false; }
	}
	function evitarSubmit(evento)
	{
		if(document.all){ tecla = evento.keyCode;}
		else{ tecla = evento.which;}
		return(tecla != 13);
	}
	function Pasar(evento,proxCampo)
	{
		if(evento.keyCode == 13){document.getElementById(proxCampo).focus();}
	}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table BORDER=1  style='font : normal normal small-caps 12px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="2"> 
	<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold">
	<td>usuario</td>
	<td>Permisos para</td>
    </tr>
    <tr align="center">
    	<td>        
        <?
		$cone="SELECT usuario,nombre,cedula FROM central.usuarios order by nombre" ;		
		$res=ExQuery($cone);		
		?>
       <select name="UsuarioGlosa" >
  <? 	while($fill=Exfetch($res)){
		   if($fill[0]==$UsuarioGlosa){
			echo "<option value='$fill[0]' selected> $fill[1]</option>";  			
		   }
		   else
		   {
			   echo "<option value='$fill[0]'> $fill[1]</option>";
		
		   }        
       	}
?>       </select>   
        
      	</td>
		<td>
		<select name="Permisos">
		<option value=""></option>
		<option value="SI">Firma Documentos</option>
		</select>
		
		
		</td>
     
  	</tr>
	<tr align="center" >
    	<td colspan="5"><input type="submit" value="Guardar" name="Guardar" /><input type="button" value="Cancelar" onClick="location.href='AdminUsuarios.php?DatNameSID=<? echo $DatNameSID?>'"/></td>
  	</tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
<input type="hidden" name="UserGlosa" value="<? echo $UserGlosa?>" />
</form>
</body>
</html>
