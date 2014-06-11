<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Estado){$Estado="Null";}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
    function AsistBusqueda(Valor,Tipo)
	{
		parent(1).frames.FrameOpener.location.href="AsistenteHV.php?DatNameSID=<? echo $DatNameSID?>&Valor="+Valor.value+"&Tipo="+Tipo;
		parent(1).document.getElementById('FrameOpener').style.position='absolute';
		parent(1).document.getElementById('FrameOpener').style.top='10px';
		parent(1).document.getElementById('FrameOpener').style.right='10px';
		parent(1).document.getElementById('FrameOpener').style.display='';
		parent(1).document.getElementById('FrameOpener').style.width='300px';
		parent(1).document.getElementById('FrameOpener').style.height='450px';

	}
    function Ocultar()
	{
		parent(1).document.getElementById('FrameOpener').style.display='none';
		parent(1).document.getElementById('FrameOpener').style.width='0';
		parent(1).document.getElementById('FrameOpener').style.height='0';
	}
</script>	
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" target="Abajo" action="ResultBusHojaVida.php" >
<input type="hidden" name="Codigo" >
<table border="1" bordercolor="white" bgcolor="#e5e5e5"  style="font-family:Tahoma;font-size:13">
	<tr style="text-align:center;">
    <td colspan="2">Nombres</td>
    <td colspan="2">Apellidos</td>
    <td>Identificacion</td>
    <td>Cargo</td>
    <td>Estado</td>
    <td>Vinculacion</td>
    </tr>
    <tr>
    <td><input type="Text" name="PrimNom" style="width:90px;" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" onFocus="Ocultar()"></td>
    <td><input type="Text" name="SegNom" style="width:90px;" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" onFocus="Ocultar()"></td>
    <td><input type="Text" name="PrimApe" style="width:90px;" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" onFocus="Ocultar()"></td>
    <td><input type="Text" name="SegApe" style="width:90px;"  onkeydown="xLetra(this)" onKeyUp="xLetra(this)" onFocus="Ocultar()"></td>
    <td><input type="Text" name="Identificacion" style="width:90px;" onFocus="AsistBusqueda(this,'Identificacion')" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);AsistBusqueda(this,'Identificacion')"></td>
    <td><input type="text" name="Cargo" style="width:90px;" onFocus="AsistBusqueda(this,'Cargo')" onKeyDown="xLetra(this)" onKeyUp="xLetra(this);AsistBusqueda(this,'Cargo')"></td>
    <td>
      <select name="Estado" id="Estado">
        <option></option>
        <option value="Activo" selected <? if ($Estado=="Activo"){ echo "selected";}?>>Activo</option>
        <option value="Inactivo" <? if ($Estado=="Inactivo"){ echo "selected";}?>>Inactivo</option>
       </select>
    </td>
        <td><select name="Vinculacion" >
            <option></option>
            <?
            	$cons = "select codigo,tipovinculacion from nomina.tiposvinculacion where compania='$Compania[0]' order by codigo";
                $resultado = ExQuery($cons);
                while ($fila = ExFetch($resultado))
                {                        
					if($fila[0]==$Vinculacion)
					{
						echo "<option value='$fila[0]' selected>$fila[1]</option>"; 
					}
					else{echo "<option value='$fila[0]'>$fila[1]</option>";}						 
                }
				?>
            </select>
        </td>
    <td><input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>"></td>
    <td><input type="Submit" name="Buscar" value="Buscar"></td>
    </tr>
</table>
</form>
</body>
</html>