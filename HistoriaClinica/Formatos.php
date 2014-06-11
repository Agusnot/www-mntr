<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$cons="select tiposervicio,numservicio,medicotte from salud.servicios where servicios.estado='AC' and servicios.cedula='$Paciente[1]'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$Ambito=$fila[0];
	$NumServ=$fila[1];
	$consM="select Nombre from central.usuarios where usuario='$fila[2]'";
	$resM=ExQuery($consM);
	$filaM=ExFetch($resM);
	if($NumServ&&$Ambito){
		$cons="Select Pabellon from Salud.PacientesxPabellones where cedula='$Paciente[1]' and Estado='AC' and numservicio=$NumServ";
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$Unidad=$fila[0];
	}

	if($Pacie)
	{
		$Paciente[1]=$Pacie;
		if($Paciente[1]!=''){
			$cons9="Select * from Central.Terceros where Identificacion='$Paciente[1]' and compania='$Compania[0]'";
			//echo $cons9;
			$res9=ExQuery($cons9);echo ExError();
			$fila9=ExFetch($res9);
	
			$Paciente[1]=$fila9[0];
			$n=1;
			for($i=1;$i<=ExNumFields($res9);$i++)
			{
				$n++;
				$Paciente[$n]=$fila9[$i];
				//echo "<br>$n=$Paciente[$n]";
			}
			//echo $Paciente[47];
		}
		session_register("Paciente");
	}
	$cons="Select Perfil from Salud.AccesoxHC,Salud.UsuariosxHC 
	where Modulo=Perfil and Nivel=0 and Usuario='$usuario[1]'AND Modulo IS DISTINCT FROM 'GUARDAR FICHA DE IDENTIFICACIÓN' Order By Id";
	$res=ExQuery($cons);
	$NumRows=ExNumRows($res);
	$ND=getdate();
	
	//$cons100="Select nombre from HistoriaClinica.TipoFormato where compania='$Compania[0]'";
	$cons100="Select especialidad from Salud.Especialidades where compania='$Compania[0]' ORDER BY Especialidad ASC";

	$res100=ExQuery($cons100);
//	$NumRows=$NumRows+ExNumRows($res100);;
	while($filaJ=ExFetchArray($res100))
	{
		$cons2="Select * from HistoriaClinica.Formatos where TipoFormato='" . $filaJ['especialidad'] . "' and Estado='AC' and compania='$Compania[0]'";
		$res2=ExQuery($cons2);
		if(ExNumRows($res2)>0)
		{
			$NumRows++;
			$NumRows20++;
		}
	}
	$res100=ExQuery($cons100);
//	if(!$NoCerrar){$Aumento=1;}
//	else{$Aumento=0;}
	
?>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta></head>
<script language="JavaScript">
function A(s)
{
	parent.parent.document.getElementById("OpcIzquierda").cols="30,*";
	document.getElementById("Flecha_Izq").style.visibility="hidden";
	document.getElementById("Flecha_Der").style.visibility="visible";
}
function B(s)
{
	parent.parent.document.getElementById("OpcIzquierda").cols="210,*";
	document.getElementById("Flecha_Izq").style.visibility="visible";
	document.getElementById("Flecha_Der").style.visibility="hidden";
}
</script>
<body bgcolor="#666699">
<img src="/Imgs/flecha_izq.gif" id="Flecha_Izq" style="position:absolute;right:0px;top:28px;cursor:hand" onClick="A(this.src);"/>
<img src="/Imgs/flecha_der.gif" id="Flecha_Der" style="position:absolute;right:0px;top:5px;cursor:hand;visibility:hidden" onClick="B(this.src);"/>

<style type="text/css">
<?
	if($NoSistema!=1){
?>
body{background-image: url(/Imgs/Izquierda_.jpg);}<?	}?>
	a{color:white;text-decoration:none;}
	a:hover{color:yellow;text-decoration:underline;}
</style>

<font color="#ffffff">

<script language="JavaScript" type="text/javascript">
var anMenu = 300
<?	if(!$Reabrir){?>var totalMen =<?echo $NumRows+3?><?}?>

var anImas = 17
var alImas = 15
var direc = '/Imgs/'
var mas = '/mast.gif'
var menos = '/menost.gif'
var puntos = '/puntost.gif'
var puntosv = '/puntosvt.gif'
var carpeab = '/carpabiertat.gif'
var carpece = '/carpcerradat.gif'
var puntosu = '/puntosut.gif'
var doc = '/doct.gif'
var docsel = '/docselt.gif'
var carpeabsel = '/carpabiertasel.gif'
var carpecesel = '/carpcerradasel.gif'
var icHome = '/home.gif'
var icCerrar = '/b_drop.png'
var puntosh = '/puntosh.gif'

function tunMen(tex,enl,dest,subOp,an){
this.tex = tex;
this.enl = enl;
this.dest = dest;
this.subOp = subOp;
this.an = an;
this.secAc = false
}

	function IrLista(){
		window.open("../Principal.php?DatNameSID=<? echo $DatNameSID?>&volver=1&UnidadIraLista=<? echo $Unidad?>", "_top");
	}



	var Op_0 = new tunMen("FICHA IDENTIFICACIÓN","Formatos_Fijos/FichaIdentificacion.php?DatNameSID=<? echo $DatNameSID?>","Datos",0);
<?
	while($fila=ExFetch($res))
	{
		$cons1="Select Perfil,Ruta,Frame from Salud.AccesoxHC,Salud.UsuariosxHC
		where Modulo=Perfil and AccesoxHC.Madre='$fila[0]' and UsuariosxHC.Madre='$fila[0]' and Usuario='$usuario[1]'
		and ModuloGr='$fila[0]' Order By Id";
		$res1=ExQuery($cons1);
		$NumRows2=ExNumRows($res1);
		$a++;$b=-1;
		?>
		var Op_<?echo $a?> = new tunMen("<?echo $fila[0]?>",null,null,<?echo $NumRows2?>)		
		<?
		while($fila1=ExFetch($res1))
		{
			$b++;$c=-1;
			$cons2="Select Perfil,Ruta,Frame from Central.AccesoxModulos,Central.UsuariosxModulos where Modulo=Perfil and AccesoxModulos.Madre='$fila1[0]' and UsuariosxModulos.Madre='$fila[0]' and Usuario='$usuario[1]' and ModuloGr='$fila[0]' Order By Id";
			$res2=ExQuery($cons2);
			$NumRows3=ExNumRows($res2);
			$Ruta=$fila1[1];$Target=$fila1[2];
		?>	
			var Op_<?echo $a?>_<?echo $b?> = new tunMen("<? echo $fila1[0]?>","<? echo $Ruta."?DatNameSID=".$DatNameSID?>","<?echo $Target?>",<?echo $NumRows3?>)			
<?
			while($fila2=ExFetch($res2))
			{
				$Ruta=$fila2[1];$Target=$fila2[2];
				$c++;$d=-1;
				$cons3="Select Perfil,Ruta,Frame from Central.AccesoxModulos,Central.UsuariosxModulos 
				where Modulo=Perfil and AccesoxModulos.Madre='$fila2[0]' and UsuariosxModulos.Madre='$fila[0]' and Usuario='$usuario[1]' and ModuloGr='$fila[0]' Order By Id";				
				$res3=ExQuery($cons3);
				$NumRows4=ExNumRows($res3);
				$Ruta=$fila2[1];$Target=$fila2[2];
				?>				
				var Op_<?echo $a?>_<?echo $b?>_<?echo $c?> = new tunMen("<? echo $fila2[0]?>","<?echo $fila2[1]?>","<?echo $fila2[2]?>?DatNameSID=<? echo $DatNameSID?>",<?echo $NumRows4?>);
				<?
				while($fila3=ExFetch($res3))
				{
					$Ruta=$fila3[1];$Target=$fila3[2];
					$d++;?>
					var Op_<?echo $a?>_<?echo $b?>_<?echo $c?>_<?echo $d?> = new tunMen("<?echo $fila3[0]?>","<? echo $fila3[1]?>","<?echo $fila3[2]?>?DatNameSID=<? echo $DatNameSID?>",0)
					<?
				}
			}
		}
	}


/////////////// AQUI MONTAMOS LA HC
$ii=$a;
	//$cons100="Select nombre from HistoriaClinica.TipoFormato where compania='$Compania[0]'";
	$cons100="Select especialidad from Salud.Especialidades where compania='$Compania[0]' ORDER BY Especialidad ASC";
	$res100=ExQuery($cons100);
	while($fila101=ExFetch($res100))
	{
		$cons2="Select * from HistoriaClinica.Formatos where TipoFormato='" . $fila101[0] . "' and Estado='AC' and compania='$Compania[0]' order by formato";
		$res2=ExQuery($cons2);
		$num_filas_subformato=ExNumRows($res2);
		if($num_filas_subformato>0){
		$ii++;
	?>
		var Op_<?echo $ii?> = new tunMen("<? echo $fila101[0] ?>",null,null,<? echo $num_filas_subformato ?>);
		<? for($j=0;$j<=$num_filas_subformato;$j++)
		{
			$fila2=ExFetchArray($res2);
			if($fila2[5]==2){$Destino=$fila2[6];}
			else{$Destino="Datos.php?Formato=".$fila2['formato']."&TipoFormato=".$fila2['tipoformato'];}
		?>
			var Op_<?echo $ii?>_<?echo $j?> = new tunMen("<? echo $fila2['formato']?>","<? echo $Destino?>&DatNameSID=<? echo $DatNameSID?>","Datos",0);
	<? 	}}
	
	}
	
	$ii++;
	?>
	var Op_<?echo $ii?> = new tunMen("CERRAR HISTORIA","/Principal.php?DatNameSID=<? echo $DatNameSID?>","_top",0);
	<? $ii++; ?>
var anchoTotal = 912
var tunIex=navigator.appName=="Microsoft Internet Explorer"?true:false;
if(tunIex && navigator.userAgent.indexOf('Opera')>=0){tunIex = false}
var manita = tunIex ? 'hand' : 'pointer'
var subOps = new Array()
function construye(){
cajaMenu = document.createElement('div')
cajaMenu.style.width = anMenu + "px"
document.getElementById('tunMe').appendChild(cajaMenu)
for(m=0; m < totalMen-1; m++){
	opchon = eval('Op_'+m)
	ultimo = false
	try{
	eval('Op_' + (m+1))
	}
	catch(error){
	ultimo = true
	}
	boton = document.createElement('div')
	boton.style.position = 'relative'
	boton.className = 'botones'
	boton.style.paddingLeft= 0
	carp = document.createElement('img')
	carp.style.marginRight = 5 + 'px'	
	carp.style.verticalAlign = 'middle'
	carp2 = document.createElement('img')
	carp2.style.verticalAlign = 'middle'


	enla = document.createElement('a')
	if(opchon.subOp > 0){
		carp2.style.cursor = manita
		carp2.src = direc + mas
		boton.secAc = opchon.secAc
		}
	else{
		carp2.style.cursor = 'default'
		enla.className = 'enls'
		if(ultimo){carp2.src = direc + puntosu}
		else{carp2.src = direc + puntos}
		}
		if(m == 0){
		carp.src = direc + icHome
		carp2.src = direc + puntosh
		}
	else{
		carp.src = direc + carpece
		if(m==totalMen-2){
            carp.src = direc + icCerrar
        }
		}
	boton.appendChild(carp2)
	boton.appendChild(carp)
	enla.className = 'enls'
	enla.style.cursor = manita
	boton.appendChild(enla)
	enla.appendChild(document.createTextNode(opchon.tex))
	if(tunIex){
		enla.onmouseover = function(){this.className = 'botonesHover'}
		enla.onmouseout = function(){this.className = 'enls'}
		}
	if(opchon.enl != null && opchon.subOp == 0){
			enla.href = opchon.enl
			}
		if(opchon.dest != null && opchon.subOp == 0){
			enla.target = opchon.dest;
			}
	boton.id = 'op_' + m
	
	cajaMenu.appendChild(boton)
	if(opchon.subOp > 0 ){
		carp2.onclick= function(){
		abre(this.parentNode,this,this.nextSibling)
		}
		subOps[subOps.length] = boton.id.replace(/o/,"O")
		enla.onclick = function(){
			abre(this.parentNode,this.parentNode.firstChild,this.previousSibling)
			}
		}
	}
if(subOps.length >0){subMes()}
}
function subMes(){
lar = subOps.length
for(t=0;t<subOps.length;t++){
	opc =eval(subOps[t])
	for(v=0;v<opc.subOp;v++){
		if(eval(subOps[t] + "_" + v + ".subOp") >0){
			subOps[subOps.length] = subOps[t] + "_" + v
			}
		}
	}
construyeSub()
}
var fondo = true
function construyeSub(){
for(y=0; y<subOps.length;y++){
opchon = eval(subOps[y])
capa = document.createElement('div')
capa.className = 'subMe'
capa.style.position = 'relative'
capa.style.display = 'none'
if(!fondo){capa.style.backgroundImage = 'none'}
document.getElementById(subOps[y].toLowerCase()).appendChild(capa)
	for(s=0;s < opchon.subOp; s++){
		sopchon = eval(subOps[y] + "_" + s)
		ultimo = false
		try{
			eval(subOps[y] + "_" + (s+1))
			}
		catch(error){
			ultimo = true
			}
			if(ultimo && sopchon.subOp > 0){
			fondo = false
			}
		opc = document.createElement('div')
		opc.className = 'botones'
		opc.id = subOps[y].toLowerCase() + "_" + s
		if(tunIex){
			}
		enla = document.createElement('a')
		enla.className = 'enls'
		enla.style.cursor = manita
		if(sopchon.enl != null && sopchon.subOp == 0){
			enla.href = sopchon.enl
			if(sopchon.dest != null && sopchon.subOp == 0){
				enla.target = sopchon.dest
				}
			}
		
		enla.appendChild(document.createTextNode(sopchon.tex))
		capa.appendChild(opc)
		carp = document.createElement('img')
		carp.src = direc + carpece
		carp.style.verticalAlign = 'middle'
		carp.style.marginRight = 5 + 'px'
		carp2 = document.createElement('img')
		carp2.style.verticalAlign = 'middle'
		if(sopchon.subOp > 0){
			opc.secAc = sopchon.secAc
			carp2.style.cursor = manita
			carp2.src = direc + mas
				enla.onclick = function(){
				abre(this.parentNode,this.parentNode.firstChild,this.previousSibling)
				}
			carp2.onclick= function(){
			abre(this.parentNode,this,this.nextSibling)
			}
			if(tunIex){
			enla.onmouseover = function(){this.className = 'botonesHover'}
			enla.onmouseout = function(){this.className = 'enls'}
			}
			}
		else{
			carp2.style.cursor = 'default'
			carp.src = direc + doc
			if(ultimo){carp2.src = direc + puntosu; 
			if(sopchon.subOp > 0){alert('hola');capa.style.backgroundImage = 'none'}
			}
			else{carp2.src = direc + puntos}
				}
		opc.appendChild(carp2)
		opc.appendChild(carp)
		opc.appendChild(enla)
		
		}
	}
Seccion()
}
function abre(cual,im,car){
abierta = cual.lastChild.style.display != 'none'? true:false;
if(abierta){
	cual.lastChild.style.display = 'none'
	im.src = direc + mas
	if(cual.secAc){
		car.src = direc + carpecesel
		
		}
	else{car.src = direc + carpece}
	}
else{
	cual.lastChild.style.display = 'block'
	im.src = direc + menos
	if(cual.secAc){car.src = direc + carpeabsel}
	else{car.src = direc + carpeab}
	}
}
var seccion = null
function Seccion(){
if (seccion != null){
	if(seccion.length == 4){
		document.getElementById(seccion.toLowerCase()).firstChild.nextSibling.src = direc + carpeabsel
		document.getElementById(seccion.toLowerCase()).lastChild.className = 'secac2'
		document.getElementById(seccion.toLowerCase()).lastChild.onmouseover = function(){
			this.className = 'enls'
			}
		document.getElementById(seccion.toLowerCase()).lastChild.onmouseout = function(){
			this.className = 'secac2'
			}
		}
	else{
		document.getElementById(seccion.toLowerCase()).firstChild.nextSibling.src = direc + docsel
		document.getElementById(seccion.toLowerCase()).firstChild.nextSibling.nextSibling.className = 'secac'
		document.getElementById(seccion.toLowerCase()).parentNode.parentNode.lastChild.previousSibling.className = 'secac2' 
		//
			document.getElementById(seccion.toLowerCase()).parentNode.parentNode.lastChild.previousSibling.onmouseout = function(){
			this.className = 'secac2'
			}
			if(!tunIex){
			document.getElementById(seccion.toLowerCase()).parentNode.parentNode.lastChild.previousSibling.onmouseover = function(){
			this.className = 'enls'
			}
		}
		document.getElementById(seccion.toLowerCase()).parentNode.parentNode.secAc = true
		seccion = seccion.substring(0,seccion.length - 2)
		seccionb = document.getElementById(seccion.toLowerCase())
		abre(seccionb,seccionb.firstChild,seccionb.firstChild.nextSibling)
		if(seccion.length > 4){
		lar = seccion.length
			for(x = lar; x > 4; x-=2){
				seccion = seccion.substring(0,seccion.length - 2)
				seccionb = document.getElementById(seccion.toLowerCase())
				abre(seccionb,seccionb.firstChild,seccionb.firstChild.nextSibling)
				}
			}
		}
	}
}
onload = construye
</script>
<style type="text/css">
<!--
a.enls:link, a.enls:visited{
color: "white";
text-decoration: none;
}
a.enls:hover{
color: yellow;
text-decoration: underline;

}
a.secac2{
	color: #B87070;
	text-decoration: none;
}
a.secac{
	color: "white";
	text-decoration: none;
}
a.secac:hover{
	color: "yellow";
	text-decoration: underline;
}
.botones {
	color: "white";
	margin: 0;
	padding-left: 18px;
	text-decoration: none;
	text-align: left;
}
.botonesHover {
text-decoration: underline;
color: yellow;
}
/* Atenci&oacute;n, evitar alterar la clase .subMe */
.subMe{
	display: none;
	margin: 0;
	background-image: url(imasmenu/puntosvt.gif);
	background-repeat:  repeat-y;
}
/* Atenci&oacute;n, evitar alterar la clase .subMe */
body {

	font-family: verdana, tahoma, arial, sans serif;
	font-size: 13px;
}
-->
</style>
</head>

<body bgproperties="fixed">

<div style="margin-left: auto; margin-right: auto; width: 70%; font-family: trebuchet ms">
<div id="tunMe"></div>

<img src = "/Imgs/puntosut.gif"> <a href="javascript:IrLista();"> IR A LISTA </a>
</div>
</body>
</html>
</body>

