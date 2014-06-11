<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($Guardar)
	{
		$AnioX=doubleval($AnioX);$AnioY=doubleval($AnioY);$MesX=doubleval($MesX);$MesY=doubleval($MesY);$DiaX=doubleval($DiaX);$DiaY=doubleval($DiaY);
		$ValorX=doubleval($ValorX);$ValorY=doubleval($ValorY);$TerceroX=doubleval($TerceroX);$TerceroY=doubleval($TerceroY);$LetrasX=doubleval($LetrasX);
		$LetrasY=doubleval($LetrasY);
		$cons="Update Contabilidad.EstructuraCheques set AnioX='$AnioX',AnioY='$AnioY',MesX='$MesX',MesY='$MesY',DiaX='$DiaX',DiaY='$DiaY',ValorX='$ValorX',ValorY='$ValorY',
		TerceroX='$TerceroX',TerceroY='$TerceroY',LetrasX='$LetrasX',LetrasY='$LetrasY' where Compania='$Compania[0]' and Cuenta='$Cuenta' and Anio=$AnioAc";

		$res=ExQuery($cons);
		echo ExError($res);
	}
?>
<html>
<head>
	<title>Formato de Cheque</title>
</head>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
<body background="/Imgs/cuadricula.png">
<form name="FORMA">
	<input type="Hidden" name="AnioX">
	<input type="Hidden" name="AnioY">
	<input type="Hidden" name="MesX">
	<input type="Hidden" name="MesY">
	<input type="Hidden" name="DiaX">
	<input type="Hidden" name="DiaY">
	<input type="Hidden" name="ValorX">
	<input type="Hidden" name="ValorY">
	<input type="Hidden" name="TerceroX">
	<input type="Hidden" name="TerceroY">
	<input type="Hidden" name="LetrasX">
	<input type="Hidden" name="LetrasY">
	<input type="Hidden" name="Cuenta">
	<input type="Hidden" name="Guardar">
    <input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">

</form>
<?
	$cons="Select * from Contabilidad.EstructuraCheques where Cuenta='$Cuenta' and Compania='$Compania[0]'";
	$res=ExQuery($cons);
	if(ExNumRows($res)==0)
	{
		$cons="Insert into Contabilidad.EstructuraCheques(Compania,Cuenta,Anio) values('$Compania[0]','$Cuenta',$AnioAc)";
		$res=ExQuery($cons);
		
		$cons="Select * from Contabilidad.EstructuraCheques where Cuenta='$Cuenta' and Compania='$Compania[0]'";
		$res=ExQuery($cons);
	}
	$fila=ExFetchArray($res);
?>
	<div on id="Anio" style="text-align:center;width:35px;position:absolute;top:<?echo $fila['aniox']?>;left:<?echo $fila['anioy']?>;border:1px solid"
	onmousedown="comienzoMovimiento(event, this.id);" onMouseOver="this.style.cursor='move'">AAAA</div>

	<div on id="Mes" style="text-align:center;width:25px;position:absolute;top:<?echo $fila['mesx']?>;left:<?echo $fila['mesy']?>;border:1px solid"
	onmousedown="comienzoMovimiento(event, this.id);" onMouseOver="this.style.cursor='move'">MM</div>

	<div on id="Dia" style="position:absolute;top:<?echo $fila['diax']?>;left:<?echo $fila['diay']?>;border:1px solid"
	onmousedown="comienzoMovimiento(event, this.id);" onMouseOver="this.style.cursor='move'">DD</div>

	<div on id="Valor" style="position:absolute;top:<?echo $fila['valorx']?>;left:<?echo $fila['valory']?>;border:1px solid"
	onmousedown="comienzoMovimiento(event, this.id);" onMouseOver="this.style.cursor='move'">0000000</div>

	<div on id="Tercero" style="position:absolute;top:<?echo $fila['tercerox']?>;left:<?echo $fila['terceroy']?>;border:1px solid"
	onmousedown="comienzoMovimiento(event, this.id);" onMouseOver="this.style.cursor='move'">NOMBRE COMPLETO DEL TERCERO</div>

	<div on id="Letras" style="position:absolute;top:<?echo $fila['letrasx']?>;left:<?echo $fila['letrasy']?>;border:1px solid"
	onmousedown="comienzoMovimiento(event, this.id);" onMouseOver="this.style.cursor='move'">VALOR EN LETRAS A PAGAR EN CHEQUE </div>
<input type="Button" value="Guardar" onClick="AveriguarPos()" style="position:absolute;width:100px;left:650px;top:200px;">
<script language="JavaScript">
	function AveriguarPos()
	{
		document.FORMA.AnioX.value=Anio.style.top;
		document.FORMA.AnioY.value=Anio.style.left;

		document.FORMA.MesX.value=Mes.style.top;
		document.FORMA.MesY.value=Mes.style.left;

		document.FORMA.DiaX.value=Dia.style.top;
		document.FORMA.DiaY.value=Dia.style.left;

		document.FORMA.ValorX.value=Valor.style.top;
		document.FORMA.ValorY.value=Valor.style.left;

		document.FORMA.TerceroX.value=Tercero.style.top;
		document.FORMA.TerceroY.value=Tercero.style.left;

		document.FORMA.LetrasX.value=Letras.style.top;
		document.FORMA.LetrasY.value=Letras.style.left;

		document.FORMA.Cuenta.value=<?echo $Cuenta?>;
		document.FORMA.Guardar.value=1;
		document.FORMA.submit();
	}
</script>
<script language="JavaScript" type="text/javascript">
function carga()
{
    posicion=0;
    
    // IE
    if(navigator.userAgent.indexOf("MSIE")>=0) navegador=0;
    // Otros
    else navegador=1;
}
 
function evitaEventos(event)
{
    // Funcion que evita que se ejecuten eventos adicionales
    if(navegador==0)
    {
        window.event.cancelBubble=true;
        window.event.returnValue=false;
    }
    if(navegador==1) event.preventDefault();
}
 
function comienzoMovimiento(event, id)
{
    elMovimiento=document.getElementById(id);
    
     // Obtengo la posicion del cursor
    if(navegador==0)
     {
        cursorComienzoX=window.event.clientX+document.documentElement.scrollLeft+document.body.scrollLeft;
        cursorComienzoY=window.event.clientY+document.documentElement.scrollTop+document.body.scrollTop;
 
        document.attachEvent("onmousemove", enMovimiento);
        document.attachEvent("onmouseup", finMovimiento);
    }
    if(navegador==1)
    {    
        cursorComienzoX=event.clientX+window.scrollX;
        cursorComienzoY=event.clientY+window.scrollY;
        
        document.addEventListener("mousemove", enMovimiento, true); 
        document.addEventListener("mouseup", finMovimiento, true);
    }
    
    elComienzoX=parseInt(elMovimiento.style.left);
    elComienzoY=parseInt(elMovimiento.style.top);
    // Actualizo el posicion del elemento
    elMovimiento.style.zIndex=++posicion;
    
    evitaEventos(event);
}
 
function enMovimiento(event)
{  
    var xActual, yActual;
    if(navegador==0)
    {    
        xActual=window.event.clientX+document.documentElement.scrollLeft+document.body.scrollLeft;
        yActual=window.event.clientY+document.documentElement.scrollTop+document.body.scrollTop;
		
    }  
    if(navegador==1)
    {
        xActual=event.clientX+window.scrollX;
        yActual=event.clientY+window.scrollY;
    }
    
    elMovimiento.style.left=(elComienzoX+xActual-cursorComienzoX)+"px";
    elMovimiento.style.top=(elComienzoY+yActual-cursorComienzoY)+"px";
 
    evitaEventos(event);
}
 
function finMovimiento(event)
{
    if(navegador==0)
    {    
        document.detachEvent("onmousemove", enMovimiento);
        document.detachEvent("onmouseup", finMovimiento);
    }
    if(navegador==1)
    {
        document.removeEventListener("mousemove", enMovimiento, true);
        document.removeEventListener("mouseup", finMovimiento, true);
    }
}
window.onload=carga;
</script>
</body>
</html>
