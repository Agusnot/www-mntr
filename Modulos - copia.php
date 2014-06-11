<?php //REVISIÓN 001
 
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	$ND=getdate();
	$cons = "Select InstruccionSQL,MsjAlerta,Estado,Archivo,Id from Alertas.AlertasProgramadas where Compania='$Compania[0]' and estado='Activo'  and bloqueante='Si'";
	//echo $cons;
	$res = ExQuery($cons);
	while ($fila=ExFetch($res))
	{
		$cons3="select usuario from alertas.usuariosxalertas where compania='$Compania[0]' and idalerta=$fila[4]";
		$res3=ExQuery($cons3);
		if(ExNumRows($res3)>0){
			$BanUsus=1;
			$cons4="select usuario from alertas.usuariosxalertas where compania='$Compania[0]' and idalerta=$fila[4] and usuario='$usuario[1]'";
			$res4=ExQuery($cons4);
			if(ExNumRows($res4)>0){
				$BanUsuSi=1; 				
			}
		}
		$cons2="SELECT Id from Alertas.AlertasxModulos,Central.UsuariosxModulos where AlertasxModulos.Modulo=UsuariosxModulos.Modulo and AlertasxModulos.Id=$fila[4] 
		and UsuariosxModulos.Usuario='$usuario[1]' and Alertas.AlertasxModulos.Compania='$Compania[0]' and UsuariosxModulos.Compania='$Compania[0]'";

		//echo $cons2;
		$res2=ExQuery($cons2);
		if(ExNumRows($res2)>0)
		{	
			//echo $cons1."<br>";
			$cons1=str_replace("|","'",$fila[0]);			
			$cons1=str_replace("[COMPANIA]","$Compania[0]",$cons1);
			$cons1=str_replace("[FEC_ACTUAL]","$ND[year]-$ND[mon]-$ND[mday]",$cons1);
			$cons1=str_replace("[USU]","$usuario[1]",$cons1); //echo $cons1."<br>";
			$cons1=str_replace("+","||",$cons1);
			$res1=ExQuery($cons1);			
			if(ExNumRows($res1)>0){
				if($BanUsus==1){
					if($BanUsuSi==1){$BanMsj=1;}
				}
				else{
					$BanMsj=1;
				}
			}	
		}
	}
	$cons="Select Perfil from Central.AccesoxModulos,Central.UsuariosxModulos 
	where Modulo=Perfil and Nivel=0 and Usuario='$usuario[1]' and Compania='$Compania[0]' Order By Id";
	$res=ExQuery($cons);
	$NumRows=ExNumRows($res);
	$ND=getdate();

	
?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta>
	<link href="/css/modulos.css" type="text/css" rel="stylesheet">
</head>
<!--
<script language="JavaScript">
function A(s)
{
	parent.document.getElementById("Principal").cols="30,*";
	document.getElementById("Flecha_Izq").style.visibility="hidden";
	document.getElementById("Flecha_Der").style.visibility="visible";
}
function B(s)
{
parent.document.getElementById("Principal").cols="215,*";
	document.getElementById("Flecha_Izq").style.visibility="visible";
	document.getElementById("Flecha_Der").style.visibility="hidden";
}
</script>
<body bgcolor="#666699">
<img src="<?php //REVISIÓN 001
 echo"$root"; //REVISIÓN 001
 ?>/Imgs/flecha_izq.gif" id="Flecha_Izq" style="position:absolute;right:0px;top:28px;cursor:hand" onClick="A(this.src);"/>
<img src="<?php //REVISIÓN 001
 echo"$root"; //REVISIÓN 001
 ?>/Imgs/flecha_der.gif" id="Flecha_Der" style="position:absolute;right:0px;top:5px;cursor:hand;visibility:hidden" onClick="B(this.src);"/>
-->
<style type="text/css">
<?php //REVISIÓN 001
 
if($NoSistema==0){?>
body{background-image: url(<?php //REVISIÓN 001
 echo"$root"; //REVISIÓN 001
 ?>/Imgs/Izquierda.jpg);}<?php //REVISIÓN 001
 	}?>
</style>
<?php //REVISIÓN 001
 
	$cons2="Select CambioClave from Central.Usuarios where Usuario='$usuario[1]' and (CambioClave<='$ND[year]-$ND[mon]-$ND[mday]' Or CambioClave Is Null)";
	$res2=ExQuery($cons2);
	if(ExNumRows($res2)==1)
	{
		echo "<br><br><br><br><br><br><br><br><br><br><br>";
		echo "<strong><center><a style='font-size:13px;text-align:justify;font-family: Tahoma;' target='Derecha' href='".$root."/Administracion/CambioClave.php?DatNameSID=$DatNameSID'><font color='#0098d8'>Se requiere cambio de clave</a></font>";
		echo "<strong><center><a style='font-size:13px;text-align:justify;font-family: Tahoma;' target='_top' href='/'><font color='#0098d8'>Cerrar Sesion</a></font>";
		exit;
	}
?>

<?php //REVISIÓN 001
 
	$cons2="Select CambioClave from Central.Usuarios where Usuario='$usuario[1]' and (CambioClave<='$ND[year]-$ND[mon]-$ND[mday]' Or CambioClave Is Null)";
	$res2=ExQuery($cons2);
	if(ExNumRows($res2)==null)
	{

                echo "<font color='#ffffff'><hr><center><img src='".$root."/Imgs/b_home.png'><img src='".$root."/Imgs/b_tblops.png' alt='Herramientas Administrativas'>";
		echo "<a target='Derecha' href='".$root."/Administracion/CambioClave.php?DatNameSID=$DatNameSID'><img src='".$root."/Imgs/s_rights.png' alt='cambiar la Clave de ingreso al sistema ' border='0'></a>";
                echo "<img src='".$root."/Imgs/b_docs.png' alt='Ayuda en linea'><a href='CerrarSesion.php?CerrarSesion=1' target='_top'>";
                echo "<img src='".$root."/Imgs/b_drop.png' border='0' alt='Cerrar la aplicaci&oacute;n'></a>";
				$consExtra="select extra from salud.extraordinario order by fecha desc limit 1";
				$resExtra=ExQuery($consExtra);
				$filaExtra=ExFetch($resExtra);
				if($filaExtra[0]==1){
				echo "<img src='".$root."/Imgs/botonparpadea.gif' border='0' width='15' alt='Cerrar la aplicaci&oacute;n'></a>";
				echo "<script>alert('El sistema se encuentra en situacion extraordinaria, en este modo los Residentes, Internos y Psiquiatras también podrán atender pacientes sin Triage');</script>";
				}				
                echo "<hr></center>";
		//exit;
	}


if($BanMsj==1){
	echo "<br><br><br><br><br><br><br><br><br><br><br>";?>
	<strong onClick="parent.frames(1).window.location.reload();window.location.reload()" style="cursor:hand" title="De click aqui para recargar el menu">
    	<center><font color='#0098d8'>Debe resolver los pendientes señalados en las alertas para poder continuar</font></center>
    <?php //REVISIÓN 001
 
}
else{
?>
<font face="Tahoma" color="#0098d8" size="2">
Seleccione Aplicación:<br><br>
</font>


<script language="JavaScript" type="text/javascript">
var anMenu = 300
var totalMen =<?php //REVISIÓN 001
 echo $NumRows+1?>

var anImas = 17
var alImas = 15
var direc = '<?php //REVISIÓN 001
 echo"$root"; //REVISIÓN 001
 ?>/Imgs/'
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
var puntosh = '/puntosh.gif'
function tunMen(tex,enl,dest,subOp,an){
this.tex = tex;
if(this.tex=="Radicado")
window.open('../home.php?root=<?=$root;?>DatNameSID=<?=$DatNameSID;?>&Compania=<?=$Compania[0];?>&usuario=<?=$usuario[1];?>','_',"toolbar=0,location=no,status=0,menubar=0,scrollbars=yes,resizable=yes");
else 
this.enl = enl;
this.dest = dest;
this.subOp = subOp;
this.an = an;
this.secAc = false
}

	var Op_0 = new tunMen("<?php //REVISIÓN 001
 echo $Compania[0]?>",null,null,0)
<?php //REVISIÓN 001
 
	while($fila=ExFetch($res))
	{
		$cons1="Select Perfil,Ruta,Frame from Central.AccesoxModulos,Central.UsuariosxModulos 
		where Modulo=Perfil and AccesoxModulos.Madre='$fila[0]' and UsuariosxModulos.Madre='$fila[0]' and Usuario='$usuario[1]' and Compania='$Compania[0]'
		and ModuloGr='$fila[0]' Order By Id";
		$res1=ExQuery($cons1);
		$NumRows2=ExNumRows($res1);
		$a++;$b=-1;
		?>
		var Op_<?php //REVISIÓN 001
 echo $a?> = new tunMen("<?php //REVISIÓN 001
 echo $fila[0]?>",null,null,<?php //REVISIÓN 001
 echo $NumRows2?>)
		<?php //REVISIÓN 001
  while($fila1=ExFetch($res1))
		{
			$b++;$c=-1;
			$cons2="Select Perfil,Ruta,Frame from Central.AccesoxModulos,Central.UsuariosxModulos 
			where Modulo=Perfil and AccesoxModulos.Madre='$fila1[0]' and UsuariosxModulos.Madre='$fila[0]' and Usuario='$usuario[1]' and ModuloGr='$fila[0]'
                        and Compania='$Compania[0]' Order By Id";
			$res2=ExQuery($cons2);
			$NumRows3=ExNumRows($res2);
			if(substr($fila1[1],strlen($fila1[1])-4,strlen($fila1[1]))==".php"){$Separa="?";}else{$Separa="&";}
			$Ruta=$root.$fila1[1].$Separa."DatNameSID=$DatNameSID";$Target=$fila1[2];
		?>
			var Op_<?php //REVISIÓN 001
 echo $a?>_<?php //REVISIÓN 001
 echo $b?> = new tunMen("<?php //REVISIÓN 001
 /*if($fila1[0]=="Radicacion")
 echo "";*/ echo $fila1[0]?>","<?php //REVISIÓN 001
 if($fila1[0]=="Recepcion de Glosas"||$fila1[0]=="Respuesta Glosa"||$fila1[0]=="Radicacion De Respuesta"||$fila1[0]=="Recepcion de Respuesta"||$fila1[0]=="Conciliacion"||$fila1[0]=="Informe"
  ||$fila1[0]=="Apertura de Buzon"||$fila1[0]=="Registrar Solicitudes"||$fila1[0]=="Direccionamiento"||$fila1[0]=="Argumentacion"||$fila[0]=="Acciones Propuestas")
 echo "home.php?root=".$root."&DatNameSID=".$DatNameSID."&Compania=".$Compania[0]."&usuario=".$usuario[1]."&home=".$fila[0].""; else echo $Ruta?>","<?php //REVISIÓN 001
 if($fila1[0]=="Recepcion de Glosas"||$fila1[0]=="Respuesta Glosa"||$fila1[0]=="Radicacion De Respuesta"||$fila1[0]=="Recepcion de Respuesta"||$fila1[0]=="Conciliacion"||$fila1[0]=="Informe"
 ||$fila1[0]=="Apertura de Buzon"||$fila1[0]=="Registrar Solicitudes"||$fila1[0]=="Direccionamiento"||$fila1[0]=="Argumentacion"||$fila[0]=="Acciones Propuestas")
 echo "_parent"; else echo $Target?>",<?php //REVISIÓN 001
 echo $NumRows3?>)
<?php //REVISIÓN 001
 
			while($fila2=ExFetch($res2))
			{
				$c++;$d=-1;
				$cons3="Select Perfil,Ruta,Frame from Central.AccesoxModulos,Central.UsuariosxModulos 
				where Modulo=Perfil and AccesoxModulos.Madre='$fila2[0]' and UsuariosxModulos.Madre='$fila[0]' and Usuario='$usuario[1]' and Compania='$Compania[0]' and ModuloGr='$fila[0]' Order By Id";
				$res3=ExQuery($cons3);
				$NumRows4=ExNumRows($res3);

				if(substr($fila2[1],strlen($fila2[1])-4,strlen($fila2[1]))==".php"){$Separa="?";}else{$Separa="&";}
				$Ruta=$root.$fila2[1].$Separa."DatNameSID=$DatNameSID";$Target=$fila2[2];
				?>
				var Op_<?php //REVISIÓN 001
 echo $a?>_<?php //REVISIÓN 001
 echo $b?>_<?php //REVISIÓN 001
 echo $c?> = new tunMen("<?php //REVISIÓN 001
 echo $fila2[0]?>","<?php //REVISIÓN 001
 echo $Ruta?>","<?php //REVISIÓN 001
 echo $Target?>",<?php //REVISIÓN 001
 echo $NumRows4?>);
				<?php //REVISIÓN 001
 
				while($fila3=ExFetch($res3))
				{
					if(substr($fila3[1],strlen($fila3[1])-4,strlen($fila3[1]))==".php"){$Separa="?";}else{$Separa="&";}
					$Ruta=$root.$fila3[1].$Separa."DatNameSID=$DatNameSID";$Target=$fila3[2];
					$d++;?>
					var Op_<?php //REVISIÓN 001
 echo $a?>_<?php //REVISIÓN 001
 echo $b?>_<?php //REVISIÓN 001
 echo $c?>_<?php //REVISIÓN 001
 echo $d?> = new tunMen("<?php //REVISIÓN 001
 echo $fila3[0]?>","<?php //REVISIÓN 001
 echo $Ruta?>","<?php //REVISIÓN 001
 echo $Target?>",0)
					<?php //REVISIÓN 001
 
				}
			}
		}
	}
?>


var anchoTotal = 912
var tunIex=navigator.appName=="Microsoft Internet Explorer"?true:false;
if(tunIex && navigator.userAgent.indexOf('Opera')>=0){tunIex = false}
var manita = tunIex ? 'hand' : 'pointer'
var subOps = new Array()
function construye(){
cajaMenu = document.createElement('div')
cajaMenu.style.width = anMenu + "px"
document.getElementById('tunMe').appendChild(cajaMenu)
for(m=0; m < totalMen; m++){
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
</head>

<body bgcolor="#666699"  bgproperties="fixed">

<div style="margin-left: auto; margin-right: auto; width: 70%; font-family: trebuchet ms">
<div id="tunMe"></div>
</div>
<?php //REVISIÓN 001
 
}?>
</body>
</html>
</body>

