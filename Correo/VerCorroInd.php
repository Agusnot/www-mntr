<?	
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>	
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Correo</title>
</head>

<body background="/Imgs/Fondo.jpg">
<?	$cons="select id,mensaje,asunto,fechacrea,usucrea,nombre from central.correos,central.usuarios 
	where compania='$Compania[0]' and id =$IdCorreo and usucrea=usuario";
	//echo $cons;
	$res=ExQuery($cons); $fila=ExFetch($res);?>
	<table style='font : normal normal small-caps 11px Tahoma;' border="1" bordercolor="#e5e5e5" cellpadding="1" cellspacing="1" align="center"> 
	    <tr>
        	<td  bgcolor="#e5e5e5" style="font-weight:bold" style="width:15%">Asunto</td><td><? echo $fila[2]?></td>
        </tr>
        <tr>
        	<td  bgcolor="#e5e5e5" style="font-weight:bold">Fecha Creacion</td><td><? echo $fila[3]?></td>
        </tr>
         <tr>
        	<td  bgcolor="#e5e5e5" style="font-weight:bold">Enviado Por</td><td><? echo $fila[5]?></td>
        </tr>
        <tr>
        	<td colspan="2"  bgcolor="#e5e5e5" style="font-weight:bold" align="center">Mensaje</td>
        </tr>
        <tr>
        	<td colspan="2">
            	<? echo $fila[1]?>
            </td>
        </tr>
    </table>
</body>
</html>