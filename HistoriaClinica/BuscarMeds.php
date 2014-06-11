<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
?>
<htm>
<title>Buscar Medicamentos</title>
<head>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
	function CerrarThis()
	{
		parent.document.getElementById('FrameOpener').style.position='absolute';
		parent.document.getElementById('FrameOpener').style.top='1px';
		parent.document.getElementById('FrameOpener').style.left='1px';
		parent.document.getElementById('FrameOpener').style.width='1';
		parent.document.getElementById('FrameOpener').style.height='1';
		parent.document.getElementById('FrameOpener').style.display='none';
	}
	
	function Asignar()
	{
		if(document.FORMA.CodSeleccionado.value==""||document.FORMA.MedSeleccionado.value==""){
			alert("Debe seleccionar un medicamento!!");
		}
		else{
			if(document.FORMA.Cant.value==""){
				alert("Debe digitar la cantidad!!!");
			}
			else{
				if(document.FORMA.Indicacion.value==""){
					alert("Debe digitar la posologia!!!");
				}
				else{
					var Msj=document.FORMA.MedSeleccionado.value+', #'+document.FORMA.Cant.value+' ,'+document.FORMA.Indicacion.value;					
					if(parent.document.getElementById('<? echo $NomCampo?>').value==""){
						parent.document.getElementById('<? echo $NomCampo?>').value=Msj;
					}
					else
					{	
						parent.document.getElementById('<? echo $NomCampo?>').value=parent.document.getElementById('<? echo $NomCampo?>').value+'\n\r'+Msj;
					}
					CerrarThis();
				}
			}
		}
	}
</script>
</head>
<body background="/Imgs/Fondo.jpg">
<form name="FORMA">
<table border="1" bordercolor="#ffffff" style='font : normal normal small-caps 13px Tahoma;'>
<tr align="right">
	<td colspan="4">
    	<input type="button" value=" X " onClick="CerrarThis()" title="Cerrar esta ventana">
    </td>
</tr>
<tr bgcolor="#e5e5e5"><td><strong>Almacen</td>
<td colspan="3">
<select name="AlmacenPpal">
<?
	$cons="Select AlmacenPpal from Consumo.AlmacenesPpales where Compania='$Compania[0]' and SSFarmaceutico=1";
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{
		echo "<option value='$fila[0]'>$fila[0]</option>";
	}
?>
</select>

<tr bgcolor="#e5e5e5" style="font-weight:bold;text-align:center"><td>Codigo</td><td colspan="3">Medicamento</td></tr>
<tr>
<td><input type="Text" name="Codigo" style="width:50px;" onKeyUp="xLetra(this);frames.ListaMeds.location.href='ListaMeds.php?DatNameSID=<? echo $DatNameSID?>&Codigo='+this.value+'&AlmacenPpal='+AlmacenPpal.value+'&Medicamento='+Medicamento.value" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)"></td>
<td colspan="3"><input type="Text" name="Medicamento" style="width:500px;" onKeyUp="xLetra(this);frames.ListaMeds.location.href='ListaMeds.php?DatNameSID=<? echo $DatNameSID?>&Medicamento='+this.value+'&AlmacenPpal='+AlmacenPpal.value+'&Codigo='+Codigo.value" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)"></td></tr>
<tr><td colspan="4">
<iframe name="ListaMeds" id="ListaMeds" src="ListaMeds.php" name="ListaMeds" style="height:180px;width:550px;" frameborder="0">

</iframe>
</td></tr>
<tr><td>Cod</td><td>Medicamento</td><td>Cantidad</td><td>Posologia</td></tr>
<tr>
<td><input type="Text" name="CodSeleccionado" style="width:50px;border:0px;background:#e5e5e5;font : 13px Tahoma;" readonly></td>
<td><input type="Text" name="MedSeleccionado" style="width:300px;border:0px;background:#e5e5e5;font : 13px Tahoma;" readonly></td>
<td><input type="text" name="Cant" style="width:30"  onKeyDown="xNumero(this)" onKeyPress="xNumero(this)" onKeyUp="xNumero(this)"></td>
<td><input type="Text" name="Indicacion" value="Tomar # cada # horas" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)"> 
</td>

</tr>
</table>
<center><br>
<input type="Button" value="Asignar Medicamento" onClick="Asignar()">
</form>
</body>
</html>
