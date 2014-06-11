<?	
	session_start();
	include("Funciones.php");
	mysql_select_db('HistoriaClinica',$conex);
?>
<style type="text/css">body {
	background-image: url(/Imgs/HistoriaClinica/encabezado2.jpg);
	background-repeat: no-repeat;
}</style>
<?
	$cons="Select * from PermisosxFormato where Formato='$Formato' and TipoFormato='$TipoFormato' and Perfil='$usuario[3]' and Permiso='Escritura'";

	$res=ExQuery($cons,$conex);
	if(mysql_num_rows($res)>0)
	{?>
        <center>
              <button name="Nuevo" value="Nuevo" onclick="parent(2).location.href='NuevoRegistro.php?Formato=<?echo $Formato?>&amp;TipoFormato=<?echo $TipoFormato?>'"><img src="/Imgs/HistoriaClinica/nuevo.png"/></button>
        </center>
 
 <? }?>
     
	
