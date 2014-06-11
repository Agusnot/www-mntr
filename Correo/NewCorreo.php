<?
	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");
	if($Enviar){		
		$ND=getdate();	
				
		$cons="select id from central.correos where compania='$Compania[0]' order by id desc";
		//echo $cons;
		$res=ExQuery($cons);
		$fila=ExFetch($res);
		$AutoId=$fila[0]+1; 
		if(!$Asunto){$Asunto="Sin asunto";}
		//$elm1=str_replace("../tinymce/jscripts/tiny_mce/plugins/emotions/img/","/tinymce/jscripts/tiny_mce/plugins/emotions/img/",$elm1);
		//$elm1=str_replace("\"","\'",$elm1);
		$UsusDes=explode(";",$AuxPara);
		foreach($UsusDes as $UDest){
			if($UDest!=""){				
				$cons="insert into central.correos (compania,id,asunto,usucrea,fechacrea,usurecive,mensaje) values 
				('$Compania[0]',$AutoId,'$Asunto','$usuario[1]','$ND[year]-$ND[mon]-$ND[mday] $ND[hours]:$ND[minutes]:$ND[seconds]','$UDest','$elm1')";
				//echo $cons."<br>";			
				$res=ExQuery($cons);
				$AutoId++;
			}			
		}?>
        	<script language="javascript">
				location.href='BandejaEntrada.php?DatNameSID=<? echo $DatNameSID?>';
			</script>
        <?
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">
	function Destinatarios(Destinatarios)
	{		
		//alert(Destinatarios);
		frames.FrameOpener.location.href='Destinatarios.php?DatNameSID=<? echo $DatNameSID?>&Destinatarios='+Destinatarios;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='10px';
		document.getElementById('FrameOpener').style.left='20px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='700';
		document.getElementById('FrameOpener').style.height='500';		
	}
</script>
<script type="text/javascript" src="/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "style,advimage,advlink,emotions,iespell,insertdatetime,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,xhtmlxtras,template,wordcount,advlist,autosave",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,|,search,replace,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,image,|,insertdate,inserttime,|,forecolor,backcolor",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		//theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
<script language="javascript">
	function Validar(Msj)
	{		
		if(document.FORMA.Para.value==""){alert("Debe digitar el/los destinatario(s)");return false;}	
		if(document.FORMA.Asunto.value==""){document.FORMA.Asunto.value="Sin Asunto";}	
		//if(document.FORMA.elm1.value==""){alert("Debe digitar un mensaje!!!");return false;}
	}
</script>
<script language='javascript' src="/Funciones.js"></script>
</head>

<body background="/Imgs/Fondo.jpg">
<form name="FORMA" method="post" onSubmit="return Validar(elm1.value)">
<table BORDER=1  border="1" bordercolor="#e5e5e5" cellpadding="4" style='font : normal normal small-caps 12px Tahoma;'>	
	<tr>	
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Para: </td>
        <td><input type="text" name="Para" readonly style="width:600" onClick="Destinatarios(AuxPara.value)" >
        	<input type="hidden" name="AuxPara">
        </td>        
	</tr>
    <tr>	
    	<td bgcolor="#e5e5e5" style="font-weight:bold">Asunto: </td>
        <td><input type="text" name="Asunto" style="width:600" onKeyDown="xLetra(this)" onKeyUp="xLetra(this)" onFocus="xLtra(this)"></td>        
	</tr>
    <tr align="center" bgcolor="#e5e5e5" style="font-weight:bold">
    	<td colspan="2">Mensaje</td>
    </tr>
    <tr>
    	<td colspan="2" align="center">
        	<textarea id="elm1" name="elm1" cols="100" rows="20" onKeyDown="xLetra(this)" onKeyPress="xLetra(this)" onKeyUp="xLetra(this)"><? echo $elm1?></textarea>
      	</td>
    </tr>
    <tr align="center">
    	<td colspan="2">
        	<input type="submit" value="Enviar" name="Enviar">
            <input type="button" value="Cancelar" onClick="location.href='BandejaEntrada.php?DatNameSID=<? echo $DatNameSID?>'">
        </td>
    </tr>
</table>
<input type="hidden" name="DatNameSID" value="<? echo $DatNameSID?>">
</form> 
<iframe scrolling="yes" id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge"></iframe>
</body>
</html>