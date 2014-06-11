<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include ("Funciones.php");
	$cons="Select codigo,grupo from contratacionsalud.gruposservicio where compania='$Compania[0]' order by grupo";
	$res=ExQuery($cons);
?>	
<style>
.Tit1{color:white;background:<?echo $Estilo[1]?>;font-weight:bold;}
</style>
<style>
a{color:<?echo $Estilo[1]?>;text-decoration:none;}
a:hover{color:blue;text-decoration:underline;}
</style>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar()">
<table height="100%" rules="groups" border="1" width="100%" bordercolor="black" cellpadding="2" cellspacing="0" style="font-family:<?echo $Estilo[8]?>;font-size:12;font-style:<?echo $Estilo[10]?>">
<tr><td   style="height:10px;" class="Tit1" align="center">Grupo</td></tr>
<?
while($fila=ExFetch($res))
{?>
	<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''" style="cursor:hand" 
    onClick="parent.document.FORMA.GrupoFact.value='<? echo $fila[1]?>';
    		 parent.document.FORMA.AuxGrupFact.value='<? echo $fila[0]?>'">
    	<td><? echo $fila[1]?></td>
    </tr>
<?
}
?>
</table>
</form>
</body>
</html>
