<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");		
	//echo $TMPCOD;
	$ND=getdate();		
	if($Guardar)
	{
		if(!$PriCond||$PriCond=="NA"){$PriCond=NULL;$PriEdad='NULL';}
		if($Agrega)
		{
			if(!$SegCond||$SegCond=="NA"){$SegCond=NULL;$SegEdad='NULL';}
		}
		else
		{
			$SegCond=NULL;$SegEdad='NULL';
		}
		if(!$Sexo||$Sexo=="NA"){$Sexo=NULL;}
		if(!$ECivil||$ECivil=="NA"){$ECivil=NULL;}
		if(!$EPS||$EPS=="NA"){$EPS=NULL;}
		if(!$TipoUsu||$TipoUsu=="NA"){$TipoUsu=NULL;}
		if(!$NivelUsu||$NivelUsu=="NA"){$NivelUsu=NULL;}
		$cons="Select formato, id_item, item, tipoformato from historiaclinica.dependenciahc where Compania='$Compania[0]'
		and Formato='$Formato' and Id_Item=$IdItem and Item='$Item' and TipoFormato='$TipoFormato'";
		$res=ExQuery($cons);
		if(ExNumRows($res)==0)
		{
			if($PriCond==NULL&&$PriEdad=='NULL'&&$Sexo==NULL&&$ECivil==NULL&&$EPS==NULL&&$TipoUsu==NULL&&$NivelUsu==NULL){}
			else
			{
				$cons="Insert into HistoriaClinica.DependenciaHC (Compania,Formato,Id_Item,Item,TipoFormato,condedad1,edad1,condedad2,edad2,sexo,estadocivil,eps,
				tipousuario,nivel) values('$Compania[0]','$Formato',$IdItem,'$Item','$TipoFormato','$PriCond',$PriEdad,'$SegCond',$SegEdad,'$Sexo','$ECivil',
				'$EPS','$TipoUsu','$NivelUsu')";	
				$res=ExQuery($cons);
			}
		}
		else
		{
			if($PriCond==NULL&&$PriEdad=='NULL'&&$Sexo==NULL&&$ECivil==NULL&&$EPS==NULL&&$TipoUsu==NULL&&$NivelUsu==NULL)
			{
				$cons="Delete from HistoriaClinica.DependenciaHC  where Compania='$Compania[0]' and Formato='$Formato' and Id_Item=$IdItem and Item='$Item' 
				and TipoFormato='$TipoFormato'";
				$res=ExQuery($cons);
			}
			else
			{
				$cons="Update HistoriaClinica.DependenciaHC set CondEdad1='$PriCond', Edad1=$PriEdad, CondEdad2='$SegCond', Edad2=$SegEdad, Sexo='$Sexo',
				EstadoCivil='$ECivil', EPS='$EPS', TipoUsuario='$TipoUsu', Nivel='$NivelUsu'  where Compania='$Compania[0]'	and Formato='$Formato' 
				and Id_Item=$IdItem and Item='$Item' and TipoFormato='$TipoFormato'";
				$res=ExQuery($cons);
			}
		}
		if($PriEdad=='NULL'){$PriEdad='';}
		if($SegEdad=='NULL'){$SegEdad='';}		
		?><script language="javascript">		
		parent.document.getElementById('FrameOpener').style.display='none';
        </script><?
		
		
	}
	$cons="Select condedad1,edad1,condedad2,edad2,sexo,estadocivil,eps,	tipousuario,nivel 
	from historiaclinica.dependenciahc where Compania='$Compania[0]' and Formato='$Formato' and Id_Item=$IdItem and Item='$Item' and TipoFormato='$TipoFormato'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	if(!$PriCond&&!$PriEdad&&!$Agrega&&!$SegCond&&!$SegEdad&&!$Sexo&&!$ECivil&&!$EPS&&!$TipoUsu&&!$NivelUsu)
	{
		if(!$PriCond){$PriCond=$fila[0];}
		if(!$PriEdad){$PriEdad=$fila[1];}
		if(!$SegCond){$SegCond=$fila[2];}
		if(!$SegEdad){$SegEdad=$fila[3];}
		if($SegCond&&$SegEdad){$Agrega="y";}
		if(!$Sexo){$Sexo=$fila[4];}
		if(!$ECivil){$ECivil=$fila[5];}
		if(!$EPS){$EPS=$fila[6];}
		if(!$TipoUsu){$TipoUsu=$fila[7];}
		if(!$NivelUsu){$NivelUsu=$fila[8];}
	}
	
?>
<html>
<head>
<script language="javascript" src="/Funciones.js"></script>
<script language="javascript">
function Cerrar()
{
	parent.document.getElementById('FrameOpener').style.display='none';	
}
function LimpiarDatos()
{
	//alert();
	document.FORMA.PriCond.value="";
	document.FORMA.PriEdad.value="";		
	document.FORMA.Sexo.value="";
	document.FORMA.ECivil.value="";
	document.FORMA.EPS.value="";	
	document.FORMA.TipoUsu.value="";
	document.FORMA.NivelUsu.value="NA";
	if(document.FORMA.Agrega.value!="")
	{
		document.FORMA.Agrega.value="";
		document.FORMA.submit();	
	}
}
function Validar()
{
	if(document.FORMA.PriCond.value==''&&document.FORMA.PriEdad.value!=''){alert("Por favor Ingrese la primera Condicion para el rango de la edad!!!");return false;}
	if(document.FORMA.PriCond.value!=''&&document.FORMA.PriEdad.value==''){alert("Por favor Ingrese la edad para la primera Condicion!!!");return false;}	
	if(document.FORMA.Agrega.value!='')
	{		
		if(document.FORMA.SegCond.value==''){alert("Por favor ingrese la segunda condicion para el rango de edades");return false;}		
		if(document.FORMA.SegCond.value!=''&&document.FORMA.SegEdad.value==''){alert("Por favor Ingrese la edad para la segunda Condicion del rango!!!");return false;}			
		if(document.FORMA.PriCond.value.substring(0,6)==document.FORMA.SegCond.value.substring(0,6))
		{
			alert("La Primera y Segunda Condición de la Edad no deben ser semejantes");return false;
		}
	}
}
</script>
</head>
<body background="/Imgs/Fondo.jpg" onFocus="if(document.FORMA.PriEdad.value==''||document.FORMA.PriCond.value==''||document.FORMA.PriCond.value=='Igual a'){document.FORMA.Agrega.disabled=true;}else{document.FORMA.Agrega.disabled=false;}" onLoad="if(document.FORMA.PriEdad.value==''||document.FORMA.PriCond.value==''||document.FORMA.PriCond.value=='Igual a'){document.FORMA.Agrega.disabled=true;}else{document.FORMA.Agrega.disabled=false;}">
<form name="FORMA" method="post" onSubmit="return Validar();" >
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
<input type="hidden" name="Formato" value="<? echo $Formato?>" />
<input type="hidden" name="IdItem" value="<? echo $IdItem?>" />
<input type="hidden" name="Item" value="<? echo $Item?>" />
<input type="hidden" name="TipoFormato" value="<? echo $TipoFormato?>" />
<!--<button name="Guardar" style="position:absolute;top:1px; left:1px; font-size:11px" onClick="Validar();" title="Guardar Interventor" <? echo $DisaG?>><img src="/Imgs/b_save.png" style="width:15px; height:15px"/></button>-->
<input type="button" name=" X " value=" X " style="position:absolute; right:1px; top:1px; font-weight:bold; font-size:11px; cursor:hand;" onClick="Cerrar()" title="Cerrar Ventana" />
<table border="1" bordercolor="#ffffff" style="font : normal normal small-caps 11px Tahoma;" align="center" >
<tr align="center"  bgcolor="#e5e5e5" style="font-weight:bold"><td>Tipo Formato</td><td>Formato</td><td>Item</td></tr>
<tr align="center"><td><? echo $TipoFormato?></td><td><? echo $Formato?></td><td><? echo $Item?></td></tr>
</table>
<center><hr></center>
<table border="1" bordercolor="#ffffff" style="font : normal normal small-caps 11px Tahoma;" align="center" onFocus="if(document.FORMA.PriEdad.value==''||document.FORMA.PriCond.value==''||document.FORMA.PriCond.value=='Igual a'){document.FORMA.Agrega.disabled=true;}else{document.FORMA.Agrega.disabled=false;}" >
<tr >
<td bgcolor="#e5e5e5" style="font-weight:bold">Edad</td>
<td><select name="PriCond" onChange="if(document.FORMA.PriEdad.value==''||document.FORMA.PriCond.value==''||document.FORMA.PriCond.value=='Igual a'){document.FORMA.Agrega.disabled=true;}else{document.FORMA.Agrega.disabled=false;}" title="Seleccione una Opcion" onBlur="if(document.FORMA.Agrega.value!=''&&(document.FORMA.PriEdad.value==''||document.FORMA.PriCond.value==''||document.FORMA.PriCond.value=='Igual a')){document.FORMA.Agrega.value='';document.FORMA.Agrega.disabled=true;FORMA.submit();}">
	<option value="">NA</option>
    <option value="Mayor a" <? if($PriCond=="Mayor a"){echo "selected";}?>>Mayor a</option>
    <option value="Menor a" <? if($PriCond=="Menor a"){echo "selected";}?>>Menor a</option>
    <option value="Mayor Igual a" <? if($PriCond=="Mayor Igual a"){echo "selected";}?>>Mayor Igual a</option>
    <option value="Menor Igual a" <? if($PriCond=="Menor Igual a"){echo "selected";}?>>Menor Igual a</option>
    <option value="Igual a" <? if($PriCond=="Igual a"){echo "selected";}?>>Igual a</option>
    </select>
</td>
<td ><input type="text" name="PriEdad" value="<? echo $PriEdad?>" size="2" maxlength="3" onKeyDown="xNumero(this);" onKeyUp="xNumero(this);if(document.FORMA.PriEdad.value==''||document.FORMA.PriCond.value==''||document.FORMA.PriCond.value=='Igual a'){document.FORMA.Agrega.disabled=true;}else{document.FORMA.Agrega.disabled=false;}" onBlur="if(document.FORMA.Agrega.value!=''&&(document.FORMA.PriEdad.value==''||document.FORMA.PriCond.value==''||document.FORMA.PriCond.value=='Igual a')){document.FORMA.Agrega.value='';document.FORMA.Agrega.disabled=true;FORMA.submit();}"  title="Edad 1" ></td>
<?
if(empty($Agrega))
{
	$est="style=\" width:335px\"";
	$cpec="colspan='2'";	
}
?>
<td <? echo "$cpec $est";?>><select name="Agrega" onChange="FORMA.submit();" title="Agregar Rango de Edades">
	<option value=""></option>
    <option value="y" <? if($Agrega=="y"){echo "selected";}?>>Y</option>
    </select>
</td>
<?
if($Agrega)
{?>
<td><select name="SegCond" title="Seleccione una Opcion">
	<option value=""></option>
    <option value="Mayor a" <? if($SegCond=="Mayor a"){echo "selected";}?>>Mayor a</option>
    <option value="Menor a" <? if($SegCond=="Menor a"){echo "selected";}?>>Menor a</option>
    <option value="Mayor Igual a" <? if($SegCond=="Mayor Igual a"){echo "selected";}?>>Mayor Igual a</option>
    <option value="Menor Igual a" <? if($SegCond=="Menor Igual a"){echo "selected";}?>>Menor Igual a</option>    
    </select>
</td>
<td style=" width:190px"><input type="text" name="SegEdad" value="<? echo $SegEdad?>" size="2" maxlength="3" onKeyDown="xNumero(this);" onKeyUp="xNumero(this);" title="Edad 2"></td>
<?
}?>
</tr>
<tr>
<td bgcolor="#e5e5e5" style="font-weight:bold">Sexo</td>
<td >
<select name="Sexo">
<option value="">NA</option>
<?php
$cons = "SELECT * FROM Central.ListaSexo Order By Sexo Desc";
$resultado = ExQuery($cons,$conex);
while ($fila = ExFetch($resultado))
{
    if($Sexo==$fila[1])
    {
        echo "<option value='$fila[1]' selected>$fila[0]</option>";
    }
    else
    {
        echo "<option value='$fila[1]'>$fila[0]</option>";
    }
}?>
</select>
</td>
<td colspan="2" bgcolor="#e5e5e5" style="font-weight:bold">Estado Civil</td>
<td >
<select name="ECivil">
<option value="">NA</option>
<?php
$cons = "SELECT * FROM Central.EstadosCiviles";
$resultado = ExQuery($cons,$conex);
while ($fila = ExFetch($resultado))
{
    if($ECivil==$fila[0])
    {
        echo "<option value='$fila[0]' selected>$fila[0]</option>";
    }
    else
    {
        echo "<option value='$fila[0]'>$fila[0]</option>";
    }
}?>
</select>
</td>
</tr>
<tr>
<td bgcolor="#e5e5e5" style="font-weight:bold">Entidad</td>
<td colspan="5">
<select name="EPS" id="EPS">
<option value="">NA</option>
<?
$cons = "SELECT PrimApe,SegApe,PrimNom,SegNom,Identificacion FROM Central.Terceros where Tipo='Asegurador' and compania='$Compania[0]'";
$resultado = ExQuery($cons,$conex);		
while ($fila = ExFetch($resultado))
{	    	
	if($EPS==$fila[4])
	{
		echo "<option value='$fila[4]' selected>$fila[0] $fila[1] $fila[2] $fila[3]</option>";
	}
	else
	{
		echo "<option value='$fila[4]'>$fila[0] $fila[1] $fila[2] $fila[3]</option>";
	}
}?>
</select>
</td>
</tr>
<tr>
<td bgcolor="#e5e5e5" style="font-weight:bold">Tipo Usuario</td>
<td >
<select name="TipoUsu">
<option value="">NA</option>
<?
$cons = "SELECT * FROM Salud.TiposUsuarios Order By Tipo";
$resultado = ExQuery($cons,$conex);
while ($fila = ExFetch($resultado))
{
	if($TipoUsu==$fila[0])
	{
		echo "<option value='$fila[0]' selected>$fila[0]</option>";
	}
	else
	{
		echo "<option value='$fila[0]'>$fila[0]</option>";
	}
}?>
</select>
</td>
<td colspan="2" bgcolor="#e5e5e5" style="font-weight:bold">Nivel</td>
<td >
<select name="NivelUsu">
<?
if(empty($NivelUsu)){$NivelUsu="NA";}
$cons = "SELECT Nivel FROM Salud.NivelesUsu Order By Nivel";
$resultado = ExQuery($cons,$conex);
while ($fila = ExFetch($resultado))
{
	if($NivelUsu==$fila[0])
	{
		echo "<option value='$fila[0]' selected>$fila[0]</option>";
	}
	else
	{
		echo "<option value='$fila[0]'>$fila[0]</option>";
	}
}?>
</select>
</td>
</tr>
</table>
<center><input type="submit" name="Guardar" value="Guardar" style="cursor:hand;">
<input type="button" name="Limpiar" value="Limpiar Datos" onClick="LimpiarDatos()"></center>
</form>
</body>
</html>