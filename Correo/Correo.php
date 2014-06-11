<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php"); 
	$ND=getdate();
	if($Recivido){
		if($Actualiza){
			$cons="update central.correos set fechalee='$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]' where compania='$Compania[0]' and id=$Id";
			$res=ExQuery($cons);

		}
		$cons="select nombre,fechacrea,asunto,mensaje from central.correos,central.usuarios where compania='$Compania[0]' and id=$Id and usuario=usucrea";
	}
	else{		
		$cons="select nombre,fechacrea,asunto,mensaje from central.correos,central.usuarios where compania='$Compania[0]' and id=$Id and usuario=usurecive";
	}
	$res=ExQuery($cons);
	$fila=ExFetch($res);
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table BORDER=1 bordercolor="#e5e5e5" cellpadding="2" style='font : normal normal small-caps 12px Tahoma;' width="80%" height="100px">	
	<tr height="10px">
    	<td colspan="2"><input type="button" value="Regresar" onClick="location.href='BandejaEntrada.php?DatNameSID=<? echo $DatNameSID?>&Ver=<? echo $Ver?>'"></td>
    </tr>
	<tr height="10px">  
    	<td bgcolor="#e5e5e5" width="10px"><strong>De:</strong></td>      
        <td><? if($Recivido){echo $fila[0];}else{echo $usuario[0];}?></td>
    </tr>
    <tr height="10px">  
    	<td bgcolor="#e5e5e5"  width="10px"><strong>Para:</strong></td>
        <td><? if($Recivido){ echo $usuario[0];}else{ echo $fila[0];}?></td>
    </tr>
    <tr height="10px">
    	<td bgcolor="#e5e5e5" width="10px"><strong>Asunto:</strong></td>
        <td><? echo $fila[2]?>&nbsp;</td>
    </tr>
    <tr  height="200px">
    	<td colspan="2" valign="top"><br>    
        	<? echo $fila[3]?>
        </td>
    </tr>    
</table>    
</body>
</html>
