<? 
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	if($ND[mon]<10){$C1="0";}if($ND[mday]<10){$C2="0";}
	if(!$PerIni){$PerIni="$ND[year]-$C1$ND[mon]-01";}
	if(!$PerFin){$PerFin="$ND[year]-$C1$ND[mon]-$C2$ND[mday]";}
	$cons="select codigo,diagnostico from salud.cie";
	//echo $cons;
	$res=ExQuery($cons);
	while($fila=ExFetch($res))
	{//if($fila[0]=="F069"){echo $fila[0]." ".$fila[1];}
		$CIE[$fila[0]]=$fila[1];	
	}
?>	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language='javascript' src="/Funciones.js"></script>
<script language="javascript">
	function Validar()
	{
		if(document.FORMA.TipoFormato.value==""){alert("Debe seleccionar el Tipo de Formato!!!");return false;}
		if(document.FORMA.Formato.value==""){alert("Debe seleccionar el Formato!!!");return false;}
		if(document.FORMA.PerIni.value==""){alert("Debe digitar la fecha inicial!!!");return false;}
		if(document.FORMA.PerFin.value==""){alert("Debe digitar la fecha final!!");return false;}
		if(document.FORMA.PerFin.value<document.FORMA.PerIni.value){alert("La fecha final debe ser mayor o igual a la fecha inicial!!!");return false;}
		if(document.FORMA.EdadIni.value!=""){
			if(document.FORMA.EdadFin.value==""){alert("Debe digitar la edad final!!!");return false;}
			if(document.FORMA.EdadFin.value<document.FORMA.EdadIni.value){alert("La edad final debe ser mayor o igual a la edad inicial!!!");return false;}
		}
	}
</script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onsubmit="return Validar()">
<table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' >
<tr bgcolor="#e5e5e5" style="font-weight:bold" align="center">
	<td colspan="15">MORBILIDAD POR FORMATOS</td>
</tr>
<tr align="center">
	<td bgcolor="#e5e5e5" style="font-weight:bold">Tipo de Formato</td><td bgcolor="#e5e5e5" style="font-weight:bold">Formato</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold">Periodo</td><td bgcolor="#e5e5e5" style="font-weight:bold">Sexo</td>
    <td bgcolor="#e5e5e5" style="font-weight:bold">Los Primeros</td><td rowspan="4"><input type="submit" name="Ver" value="Ver"></td>
</tr>
</tr>    
<tr>	
    <td>
    <?	$cons="select tipoformato from historiaclinica.formatos where compania='$Compania[0]' group by tipoformato order by tipoformato";
		$res=ExQuery($cons);?>
    	<select name="TipoFormato" onChange="document.FORMA.submit()">
        	<option></option>
 		<?
        	while($fila=ExFetch($res))
			{
				if($fila[0]==$TipoFormato){echo "<option value='$fila[0]' selected>$fila[0]</option>";}	
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}	
			}	?>        
        </select>
    </td>    
    <td>
    <?	$cons="select formato from historiaclinica.formatos where compania='$Compania[0]' and tipoformato='$TipoFormato' group by formato order by formato";
		$res=ExQuery($cons);?>
    	<select name="Formato" onChange="document.FORMA.submit()">
        	<option></option>
 		<?
        	while($fila=ExFetch($res))
			{
				if($fila[0]==$Formato){echo "<option value='$fila[0]' selected>$fila[0]</option>";}	
				else{echo "<option value='$fila[0]'>$fila[0]</option>";}	
			}	?>        
        </select>
    </td>
    <td>
    <input type="text" name="PerIni" value="<? echo $PerIni?>" style="width:80px;"><input type="text" name="PerFin" value="<? echo $PerFin?>" style="width:80px;">
    </td>
    <td align="center">
        <select name="Sexo" onchange="document.FORMA.submit();">
            <option></option>
            <option value="F" <? if($Sexo=="F"){?> selected="selected"<? }?>>Femenino</option>
            <option value="M" <? if($Sexo=="M"){?> selected="selected"<? }?>>Masculino</option>
        </select>
    </td>
    <td align="center">
		<input type="text" name="CantDx" onKeyDown="xNumero(this)" onKeyPress="xNumero(this)" onKeyUp="xNumero(this)" style="width:25" value="<? echo $CantDx?>">
	</td>
</tr>
<tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td>Grupo Etareo</td><td>Proceso</td><td>Entidad</td><td>Contrato</td><td>Especialidad</td></tr>
<tr>
    <td><strong>Desde:</strong>
        <input type="text" name="EdadIni" onkeypress="xNumero(this)" onkeyup="xNumero(this)" value="<? echo $EdadIni?>" style="width:20px"/>
        <strong>Hasta:</strong>
        <input type="text" name="EdadFin" onkeypress="xNumero(this)" onkeyup="xNumero(this)" value="<? echo $EdadFin?>" style="width:20px"/>
    </td>
    <td>
    <?	
        $cons="select ambito from salud.ambitos where compania='$Compania[0]' order by ambito";
        $res=ExQuery($cons);?>
        <select name="Ambito" onchange="document.FORMA.submit()">    	
            <option></option>
        <? 	while($fila=ExFetch($res))
            {
                if($fila[0]==$Ambito){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
                else{echo "<option value='$fila[0]'>$fila[0]</option>";}
            }
        ?>
        </select>
    </td>
    <td>
    <? 	$cons="select identificacion,primape,segape,primnom,segnom from central.terceros where compania='$Compania[0]' and tipo='Asegurador' 
        order by primape,segape,primnom,segnom";
        $res=ExQuery($cons);?>
         <select name="Entidad" onchange="document.FORMA.submit()">
            <option></option>
        <? 	while($fila=ExFetch($res))
            {
                if($fila[0]==$Entidad){echo "<option value='$fila[0]' selected>$fila[1] $fila[2] $fila[3] $fila[4]</option>";}
                else{echo "<option value='$fila[0]'>$fila[1] $fila[2] $fila[3] $fila[4]</option>";}
            }
        ?>
        </select>
    </td>
    <td>
    <?	$cons="select contrato from contratacionsalud.contratos where compania='$Compania[0]' and entidad='$Entidad' group by contrato order by contrato";
        $res=ExQuery($cons);?>
        <select name="Contrato" onchange="document.FORMA.submit()">
            <option></option>
        <? 	while($fila=ExFetch($res))
            {
                if($fila[0]==$Contrato){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
                else{echo "<option value='$fila[0]'>$fila[0]</option>";}
            }
        ?>
        </select>
    </td>
   	<td>
    <?	$cons="select especialidad from salud.especialidades where compania='$Compania[0]' order by especialidad";
		$res=ExQuery($cons);?>
        <select name="Especialidad" onchange="document.FORMA.submit()">
            <option></option>
        <? 	while($fila=ExFetch($res))
            {
                if($fila[0]==$Especialidad){echo "<option value='$fila[0]' selected>$fila[0]</option>";}
                else{echo "<option value='$fila[0]'>$fila[0]</option>";}
            }
        ?>
        </select>
    </td>
</tr>
</table><?
if($Ver){?>
	<br>
    <table border="1" bordercolor="#e5e5e5" style='font : normal normal small-caps 12px Tahoma;' >
<?	$cons="select id_item from historiaclinica.itemsxformatos where compania='$Compania[0]' and tipoformato='$TipoFormato' and formato='$Formato' and 
	item='Diagnostico' and tipodato is null";
	$res=ExQuery($cons);
	//echo $cons;
	if(ExNumRows($res)>0)
	{
		$cons="select tblformat from historiaclinica.formatos where compania='$Compania[0]' and tipoformato='$TipoFormato' and formato='$Formato'";
		$res=ExQuery($cons);
		$Tabla=ExFetch($res);
		if($Sexo){$Genero=" and sexo='$Sexo'";}else{$Genero="";}
		if($EdadFin){$EIni=$ND[year]-$EdadIni; $EFin=$ND[year]-$EdadFin; $Edad="and fecnac<='$EIni-$ND[mon]-$ND[mday]' and fecnac>='$EFin-$ND[mon]-$ND[mday]'";}
		if($Ambito){$Amb="and ambito='$Ambito'";}else{$Amb="";}
		if($CantDx){$CantDx=ceil($CantDx); $CDx=" limit $CantDx";}
		if($Contrato){$Contr="and contrato='$Contrato'";}else{$Contr="";}
		if($Especialidad){$Esp="and especialidad='$Especialidad'";}
		if($Entidad){
			$cons="select numservicio from salud.pagadorxservicios where pagadorxservicios.compania='$Compania[0]' 
			and numservicio in (select numservicio from histoclinicafrms.$Tabla[0], central.terceros
								where $Tabla[0].compania='$Compania[0]' and terceros.compania='$Compania[0]' and  dx1 !='' and fecha>='$PerIni' and fecha<='$PerFin'
								and identificacion= $Tabla[0].cedula $Genero $Edad $Amb group by numservicio)
			and entidad='$Entidad' $Contr";
			$res=ExQuery($cons);
			//echo $cons;
			$banPag=0;
			while($fila=ExFetch($res))
			{
				$Pagadores[$fila[0]]=array($fila[1],$fila[2],$fila[3]);	
				if($banpag==0){$Pags="'$fila[0]'"; $banpag=1;}else{$Pags=$Pags.",'$fila[0]'";}
			}
			if($Pags){
				$PagsIn="and numservicio in ($Pags)";
			}
			else{$PagsIn="and numservicio in ('-1','-2')";}
		}
		else{$PagsIn="";}
		
		$cons="select count(dx1),dx1 from histoclinicafrms.$Tabla[0], central.terceros,salud.medicos
		where $Tabla[0].compania='$Compania[0]' and terceros.compania='$Compania[0]' and  dx1 !='' and fecha>='$PerIni' and fecha<='$PerFin'
		and identificacion= $Tabla[0].cedula and medicos.compania='$Compania[0]' and medicos.usuario=$Tabla[0].usuario $Esp $Genero $Edad $Amb $PagsIn
		group by dx1 order by count(dx1) desc $CDx";
		//echo $cons;
		$res=ExQuery($cons);?>
        <tr align="center" bgcolor="#e5e5e5" style="font-weight:bold"><td></td><td>Codigo</td><td>Dx</td><td>Cantidad</td></tr>
<?		$cont=1;
		while($fila=ExFetch($res))
		{?>
        	<tr onMouseOver="this.bgColor='#AAD4FF'" onMouseOut="this.bgColor=''">
		<?	echo "<td>$cont</td><td>$fila[1]</td><td>".$CIE[$fila[1]]."</td><td>$fila[0]</td></tr>";	
			$cont++;
		}
	}
	else{?>
    	<tr><td>ESTE FORMATO NO CUENTA CON DIAGNOSTICOS PARA SER DILIGENCIADOS</td></tr>
<?	}?>
    </table><?	
}?>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>" />
</form>
</body>
</html>