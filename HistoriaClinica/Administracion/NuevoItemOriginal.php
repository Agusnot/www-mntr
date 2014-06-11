<?	if($DatNameSID){session_name("$DatNameSID");}
	session_start();
	include("Funciones.php");		
	if($Guardar)
	{ 		
		$cons="Select * from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' and TipoFormato='$TF' and Pantalla=($Pantalla-1) and Compania='$Compania[0]'";
		$res=ExQuery($cons,$conex);
		if((ExNumRows($res)>0)||($Pantalla==1))
		{
			if(!$IdItem)
			{
				$cons="Select Id_Item from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' and compania='$Compania[0]' Group By Id_Item Order By Id_Item Desc";
				$res=ExQuery($cons,$conex);
				$fila=ExFetch($res);
				$IdItem=$fila[0]+1;
				$cons="Select orden from HistoriaClinica.ItemsxFormatos 
				where compania='$Compania[0]' and tipoformato='$TF' and Formato='$NewFormato' and estado='AC' and pantalla=$Pantalla Group By orden Order By orden Desc";
				$res=ExQuery($cons);
				$fila=ExFetch($res);
				$Orden=$fila[0]+1;
			}
			if($Obligatorio==""){$Obligatorio=0;}   if($LineaSola==""){$LineaSola=0;}   if($CierraFila==""){$CierraFila=0;}			
			if($TipoDato=="N"){$Longitud=256;}
			if($TipoControl=="Cuadro de Chequeo"){$Longitud=5;}
			if($TipoControl=="Fecha"){$Longitud=10;$Ancho=80;}
			
			//Ruta de la imagen 
			if($LimInf==''){$LimInf="0";} if($LimSup==''){$LimSup="0";} if($Longitud==''){$Longitud="0";} if($Ancho==''){$Ancho="0";} if($Alto==''){$Alto="0";}  
			if($FormatoXML==''){$FormatoXML="0";}
			if(!$Modificar)
			{				
				$cons="Insert into HistoriaClinica.ItemsxFormatos 		
				(Formato,Id_Item,Item,Pantalla,TipoDato,LimInf,LimSup,Longitud,TipoControl,Ancho,Alto,Defecto,TipoFormato,Parametro,Obligatorio,Traerde,LineaSola,CierraFila,Imagen,TIP
				,Compania,TfTraerDe,CampoTraerDe,Orden,formatoxml,tagxml,etiqxml,cargoxitem)
				values 
				('$NewFormato',$IdItem,'$Item','$Pantalla','$TipoDato','$LimInf','$LimSup','$Longitud','$TipoControl','$Ancho','$Alto','$Defecto','$TF','$Parametro','$Obligatorio',
				'$Traerde','$LineaSola','$CierraFila','$Imagen','$TIP','$Compania[0]','$TfTraerDe','$CampoTraerDe','$Orden',$FormatoXML,'$TagXML','$EtiqXML','$Cargo')";
				$consPrev="Select TblFormat from HistoriaClinica.Formatos where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]'";
				$resPrev=ExQuery($consPrev);
				$filaPrev=ExFetch($resPrev);
				if($filaPrev[0])
				{					
					$NumCampo=substr("00000",0,5-strlen($IdItem)).$IdItem;
					if($TipoControl=="Area de Texto"||$TipoControl=="Medicamentos"||$TipoControl=="CUPS"){$ClasDat=" TEXT ";}
					if($TipoControl=="Cuadro de Texto"){$ClasDat=" character varying($Longitud) ";}
					if($TipoControl=="Lista Opciones"||$TipoControl=="PDF"){$ClasDat=" character varying(255) ";}
					if($TipoControl=="Cuadro de Chequeo"){$ClasDat=" character varying(5) ";}
					if($TipoControl=="Imagen"){$ClasDat=" TEXT ";}
					if($TipoControl=="Fecha"){$ClasDat=" date ";}
					//echo "Tipo control: $TipoControl --> Longitud: $Longitud<br>";
					$cons2="ALTER TABLE histoclinicafrms.$filaPrev[0] ADD COLUMN CMP".$NumCampo.$ClasDat;
					$res2=ExQuery($cons2);
				}				
			}			
			else
			{
				$consPrev="Select TblFormat from HistoriaClinica.Formatos where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]'";
				$resPrev=ExQuery($consPrev);
				$filaPrev=ExFetch($resPrev);
				if($filaPrev[0])
				{					
					$NumCampo=substr("00000",0,5-strlen($IdItem)).$IdItem;
					if($TipoControl=="Area de Texto"||$TipoControl=="Medicamentos"||$TipoControl=="CUPS"){$ClasDat=" TEXT ";}
					if($TipoControl=="Cuadro de Texto"){$ClasDat=" character varying($Longitud) ";}
					if($TipoControl=="Lista Opciones"||$TipoControl=="PDF"){$ClasDat=" character varying(255) ";}
					if($TipoControl=="Cuadro de Chequeo"){$ClasDat=" character varying(100) ";}
					if($TipoControl=="Imagen"){$ClasDat=" TEXT ";}
					if($TipoControl=="Fecha"){$ClasDat=" date ";}
					//echo "Tipo control: $TipoControl --> Longitud: $Longitud<br>";
					$cons2="ALTER TABLE histoclinicafrms.$filaPrev[0] ALTER COLUMN CMP".$NumCampo." TYPE ".$ClasDat;
					$res2=ExQuery($cons2);
				}
				if($PantallaAnt!=$Pantalla){
					$cons="Select orden from HistoriaClinica.ItemsxFormatos 
					where compania='$Compania[0]' and tipoformato='$TF' and Formato='$NewFormato' and estado='AC' and pantalla=$Pantalla Group By orden Order By orden Desc";
					$res=ExQuery($cons);
					$fila=ExFetch($res);
					$Orden=$fila[0]+1;
					$ORD=",orden=$Orden";
				}
				$cons="Update HistoriaClinica.ItemsxFormatos 
				set Pantalla=$Pantalla,TipoDato='$TipoDato',LimInf=$LimInf,LimSup=$LimSup,Longitud=$Longitud,TipoControl='$TipoControl',Ancho=$Ancho,Alto=$Alto,Defecto='$Defecto'
				,Parametro='$Parametro',Obligatorio='$Obligatorio',Traerde='$Traerde',LineaSola=$LineaSola,CierraFila=$CierraFila,Imagen='$Imagen',TIP='$TIP',
				TftraerDe='$TFTraerDe',CampoTraerDe='$CampoTraerDe',Item='$Item',formatoxml=$FormatoXML,tagxml='$TagXML',etiqxml='$EtiqXML', CargoxItem='$Cargo' $ORD
				where Formato='$NewFormato' and Id_Item=$IdItem and TipoFormato='$TF' and Compania='$Compania[0]'";
				//echo "Tipo control: $TipoControl --> Longitud: $Longitud<br>";				
			}
			$res=ExQuery($cons);
			//echo $cons;
			$consPrev="Select TblFormat from HistoriaClinica.Formatos where Formato='$NewFormato' and TipoFormato='$TF' and Compania='$Compania[0]'";
			$resPrev=ExQuery($consPrev);
			$filaPrev=ExFetch($resPrev);
			if($filaPrev[0])
			{  
				$cons="SELECT cargoxitem FROM historiaclinica.itemsxformatos where compania='$Compania[0]' and tipoformato='$TF' and Formato='$NewFormato' and cargoxitem !='' group by cargoxitem order by cargoxitem";
				$res=ExQuery($cons);
				while($filausuxcargo=ExFetch($res))
				{
					$NewCampUsu="usu".strtolower(str_replace(" ","",$filausuxcargo[0]));
					$cons2="select column_name from information_schema.columns where table_name = '$filaPrev[0]' and column_name='$NewCampUsu';";
					$res2=ExQuery($cons2);
					if(ExNumRows($res2)==0)
					{
						$cons2="ALTER TABLE histoclinicafrms.$filaPrev[0] ADD COLUMN ".$NewCampUsu." character varying(40)";
						$res2=ExQuery($cons2);	
					}
				}
			}
			$Modificar=0;?>
			<script language='JavaScript'>location.href='ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'</script><?
		}
		else
		{?>
			<script language="JavaScript">
				alert("La pantalla no tiene secuencia!!!");
			</script>
	<?	}
	}
    if($Eliminar)
	{
		$cons="Delete from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' and Id_Item='$IdItem' and TipoFormato='$TF' and Compania='$Compania[0]'";
		//echo $cons;
		$res=ExQuery($cons,$conex); echo ExError();
		$IdItem="";
		echo "<script language='JavaScript'> location.href='ItemsxFormato.php?DatNameSID=$DatNameSID&NewFormato=$NewFormato&TF=$TF'; </script>";
	}
	if($Modificar)
	{
		$cons="Select * from HistoriaClinica.ItemsxFormatos where Formato='$NewFormato' and Id_Item=$IdItem and TipoFormato='$TF' and Compania='$Compania[0]'";
		//echo $cons;
		$res=ExQuery($cons,$conex);
		$fila=ExFetchArray($res);
		$Pantalla=$fila['pantalla'];
		$PantallaAnt=$fila['pantalla'];
		$IdItem=$fila['id_item'];
		$Item=$fila['item'];
		$TipoDato=$fila['tipodato'];
		$TF=$fila['tipoformato'];

		$LimInf=$fila['liminf'];		
		$LimSup=$fila['limsup'];
		$Longitud=$fila['longitud'];
		$TipoControl=$fila['tipocontrol'];
		$Ancho=$fila['ancho'];
		$Alto=$fila['alto'];
		$Defecto=$fila['defecto'];
		$Parametro=$fila['parametro'];
		$Traerde=$fila['traerde'];
		$Obligatorio=$fila['obligatorio'];
		$CierraFila=$fila['cierrafila'];
		$LineaSola=$fila['lineasola'];
		$Imagen=$fila['imagen'];
		$TIP=$fila['tip'];
		$CampoTraerDe=$fila['campotraerde'];
		$TFTraerDe=$fila['tftraerde'];
		$FormatoXML=$fila['formatoxml'];
		$TagXML=$fila['tagxml']; 
		$EtiqXML=$fila['etiqxml'];	
		$Cargo=$fila['cargoxitem'];	 
	}
	$cons="select formatoxml from historiaclinica.formatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF'";
	$res=ExQuery($cons);
	$fila=ExFetch($res);
	$FormatoXML=$fila[0];
?>
    
	
<script language="JavaScript">
	
	function validar()
	{
		//alert(document.FORMA.Pantalla.value);		
		if(document.FORMA.Pantalla.value==""){alert("Debe digitar el numero de la pantalla!!!");return false;}		
		if(document.FORMA.Item.value==""){alert("Debe digitar el nombre del item!!!");return false;}
		if(document.FORMA.Item.value=='Diagnostico'){
			alert("No se pueden configurar Diagnosticos a traves de esta pantalla, debe cambiar el nombre del item!!!");return false;
		}
		if(document.FORMA.TipoDato.value==""){alert("Debe seleccionar el tipo de dato!!!");return false;}
		if(document.FORMA.TipoDato.value=="N"&&document.FORMA.LimSup.value==""&&(document.FORMA.TipoControl.value=="Area de Texto"||document.FORMA.TipoControl.value=="Cuadro de Texto"))
		{
			alert("Debe digitar el Limite Superior!!!");return false;
		}
		if(document.FORMA.TipoDato.value=="N"&&document.FORMA.LimInf.value==""&&(document.FORMA.TipoControl.value=="Area de Texto"||document.FORMA.TipoControl.value=="Cuadro de Texto"))
		{
			alert("Debe digitar el Limite Inferior!!!");return false;
		}
		if(document.FORMA.Longitud.value==""&&document.FORMA.TipoDato.value=="C"&&document.FORMA.TipoControl.value!="Cuadro de Chequeo"&&document.FORMA.TipoControl.value!="Lista Opciones"&&document.FORMA.TipoControl.value!="Imagen"&&document.FORMA.TipoControl.value!="Ordenes Medicas"&&document.FORMA.TipoControl.value!="PDF"&&document.FORMA.TipoControl.value!="Fecha"){
			alert("Debe digitar la longitud de dato!!!");return false;
		}		
		if(document.FORMA.TipoControl.value==""){alert("Debe seleccionar el tipo de control");return false;}
		if(document.FORMA.Ancho.value==""&&(document.FORMA.TipoControl.value=="Area de Texto"||document.FORMA.TipoControl.value=="Medicamentos"||document.FORMA.TipoControl.value=="CUPS"||document.FORMA.TipoControl.value=="Cuadro de Texto"||document.FORMA.TipoControl.value=="Imagen"))
		{
				alert("Debe digitar el Ancho!!!");return false;
		}
		if(document.FORMA.Alto.value==""&&(document.FORMA.TipoControl.value=="Area de Texto"||document.FORMA.TipoControl.value=="Medicamentos"||document.FORMA.TipoControl.value=="CUPS"||document.FORMA.TipoControl.value=="Imagen"))
		{
				alert("Debe digitar el Alto!!!");return false;
		}
		if(document.FORMA.LimSup.value==""){document.FORMA.LimSup.value=0}
		if(document.FORMA.LimInf.value==""){document.FORMA.LimInf.value=0}
		if(document.FORMA.TFTraerDe.value!=""&&document.FORMA.Traerde.value!=""&&document.FORMA.CampoTraerDe.value!=""){}
		else
		{
			if(document.FORMA.TFTraerDe.value==""&&document.FORMA.Traerde.value==""&&document.FORMA.CampoTraerDe.value==""){}	
			else{alert("Por favor seleccione La especialidad, Tipo de Formato y el campo a traer o simplemente deje los tres campos en blanco para no aplicar la Opcion Traer De!!!");return false;}
		}
		
			
	}
	function VrDefecto()
	{
		frames.FrameOpener.location.href="/HistoriaClinica/Administracion/ValorDefecto.php?DatNameSID=<? echo $DatNameSID?>&Defecto="+document.FORMA.Defecto.value;
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='50px';
		document.getElementById('FrameOpener').style.left='15px';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='690';
		document.getElementById('FrameOpener').style.height='180';
	}
	
	function Dependencias()
	{
		frames.FrameOpener.location.href="/HistoriaClinica/Administracion/Dependencia.php?DatNameSID=<? echo $DatNameSID?>&Formato=<? echo $NewFormato?>&IdItem=<? echo $IdItem?>&Item=<? echo $Item?>&TipoFormato=<? echo $TF?>";
		document.getElementById('FrameOpener').style.position='absolute';
		document.getElementById('FrameOpener').style.top='50px';
		document.getElementById('FrameOpener').style.left='17%';
		document.getElementById('FrameOpener').style.display='';
		document.getElementById('FrameOpener').style.width='70%';
		document.getElementById('FrameOpener').style.height='70%';	
	}
	
	function AnchoAlto(){
		if(document.FORMA.TipoControl.value==""){
			if(document.FORMA.TipoDato.value=="C"){	
				document.FORMA.LimInf.value="";			
				document.FORMA.LimSup.value="";				
				document.getElementById("LimInf").disabled=true;
				document.getElementById("LimSup").disabled=true;	
				document.getElementById("Longitud").disabled=false;
			}
			if(document.FORMA.TipoDato.value=="N"){		
				document.getElementById("Longitud").value="";									
				document.getElementById("LimInf").disabled=false;
				document.getElementById("LimSup").disabled=false;	
				document.getElementById("Longitud").disabled=true;		
			}
			if(document.FORMA.TipoDato.value=="L"){	
				document.getElementById("Longitud").value="";				
				document.getElementById("Longitud").disabled=true;	
				document.FORMA.LimInf.value="";			
				document.FORMA.LimSup.value="";
				document.getElementById("LimInf").disabled=true;
				document.getElementById("LimSup").disabled=true;	
			}
			
		}
		if(document.FORMA.TipoControl.value=="Area de Texto"||document.FORMA.TipoControl.value=="Medicamentos"||document.FORMA.TipoControl.value=="CUPS"){
			document.getElementById("Alto").disabled=false;			
			document.getElementById("Ancho").disabled=false;	
			if(document.FORMA.TipoDato.value=="C"){	
				document.FORMA.LimInf.value="";			
				document.FORMA.LimSup.value="";					
				document.getElementById("LimInf").disabled=true;
				document.getElementById("LimSup").disabled=true;	
				document.getElementById("Longitud").disabled=false;
			}
			if(document.FORMA.TipoDato.value=="L"){	
				document.getElementById("Longitud").value="";
				document.getElementById("Longitud").disabled=true;		
			}
			if(document.FORMA.TipoDato.value=="N"){							
				document.getElementById("Longitud").value="";					
				document.getElementById("LimInf").disabled=false;
				document.getElementById("LimSup").disabled=false;	
				document.getElementById("Longitud").disabled=true;
			}		
		}				
		if(document.FORMA.TipoControl.value=="PDF"){
			document.FORMA.LimInf.value="";			
			document.FORMA.LimSup.value="";	
			document.FORMA.Longitud.value="";					
			document.getElementById("LimInf").disabled=true;
			document.getElementById("LimSup").disabled=true;	
			document.getElementById("Longitud").disabled=true;	
			document.getElementById("Alto").value="";
			document.getElementById("Ancho").value="";
			document.getElementById("Alto").disabled=true;			
			document.getElementById("Ancho").disabled=true;		
		}
		if(document.FORMA.TipoControl.value=="Cuadro de Texto"){
			document.getElementById("Alto").value="";
			document.getElementById("Alto").disabled=true;			
			document.getElementById("Ancho").disabled=false;			
			if(document.FORMA.TipoDato.value=="C"){	
				document.FORMA.LimInf.value="";			
				document.FORMA.LimSup.value="";					
				document.getElementById("LimInf").disabled=true;
				document.getElementById("LimSup").disabled=true;	
				document.getElementById("Longitud").disabled=false;
			}
			if(document.FORMA.TipoDato.value=="L"){	
				//document.getElementById("Longitud").value="";
				document.getElementById("Longitud").disabled=false;		
			}
			if(document.FORMA.TipoDato.value=="N"){							
				//document.getElementById("Longitud").value="";					
				document.getElementById("LimInf").disabled=false;
				document.getElementById("LimSup").disabled=false;	
				document.getElementById("Longitud").disabled=false;
			}
		}
		if(document.FORMA.TipoControl.value=="Cuadro de Chequeo"||document.FORMA.TipoControl.value=="Lista Opciones"||document.FORMA.TipoControl.value=="Fecha"){
			document.getElementById("Longitud").value="";
			document.getElementById("Longitud").disabled=true;
			document.getElementById("Alto").value="";
			document.getElementById("Ancho").value="";
			document.getElementById("Alto").disabled=true;			
			document.getElementById("Ancho").disabled=true;
			document.FORMA.LimInf.value="";			
			document.FORMA.LimSup.value="";
			document.getElementById("LimInf").disabled=true;
			document.getElementById("LimSup").disabled=true;
		}
		
		
		
		if(document.FORMA.TipoControl.value=="Imagen"){
			document.getElementById("Alto").disabled=false;			
			document.getElementById("Ancho").disabled=false;			
			document.getElementById("Longitud").value="";
			document.getElementById("Longitud").disabled=true;		
			document.FORMA.LimInf.value="";			
			document.FORMA.LimSup.value="";
			document.getElementById("LimInf").disabled=true;
			document.getElementById("LimSup").disabled=true;
		}
		if(document.FORMA.TipoDato.value==""){	
			document.getElementById("Longitud").value="";				
			document.getElementById("Longitud").disabled=true;	
			document.FORMA.LimInf.value="";			
			document.FORMA.LimSup.value="";
			document.getElementById("LimInf").disabled=true;
			document.getElementById("LimSup").disabled=true;
		}
	}
</script>
<script language="javascript" src="/Funciones.js"></script>
<body background="/Imgs/Fondo.jpg" onLoad="document.FORMA.Pantalla.focus();">

<form name="FORMA"  onSubmit="return validar()" method="post">	
  <table style='font:normal 13px Tahoma;'  border="1" bordercolor="#e5e5e5" align="center" >    
    <tr>
      <td>Pantalla</td>
      <td><input type="text" maxlength="3" name="Pantalla" style="width:30" value="<?echo $Pantalla?>" onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"></td>
      <td>Item</td>
      <td colspan="5"><input type="text" name="Item" style="width:500" value="<?echo $Item?>" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"></td>      
    </tr>
    <tr>
    	<td>Cargo</td>
		<td>
        <select name="Cargo">
        <option value="">NA</option>
		<?
        $conscar="Select cargos from Salud.Cargos where Compania='$Compania[0]' order by cargos";
		$rescar=ExQuery($conscar);
		while($filacar=ExFetch($rescar))
		{
			if($Cargo==$filacar[0])
			{
				echo "<option value='$filacar[0]' selected>$filacar[0]</option>";	
			}
			else
			{
				echo "<option value='$filacar[0]'>$filacar[0]</option>";
			}
		}
		?>
        </select>
        Valor Defecto <input type="Button" value="..." onClick="VrDefecto()">
        </td>
    	<td>Tipo Dato</td>
      	<td><select name="TipoDato" onChange="AnchoAlto()"><option></option>
        <?
			  		$cons="Select * from HistoriaClinica.TiposDatos Order By Tipo";
					$res=ExQuery($cons,$conex);
					while($fila=ExFetch($res))
					{
						if($fila[0]==$TipoDato){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
						else{echo "<option value='$fila[0]'>$fila[0]</option>";}
					}
				?>
      </select></td>
      <td>Tipo de Control</td>
	  	<td><select name="TipoControl" onChange="AnchoAlto()"><option></option>
        <?
					$cons="Select * from HistoriaClinica.TipoControl Order By Tipo";
					$res=ExQuery($cons,$conex);
					while($fila=ExFetch($res))
					{
						if($fila[0]==$TipoControl){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
						else{echo "<option value='$fila[0]'>$fila[0]</option>";}
					}
				?>
      </select></td>
      <td>Limite Inferior</td>
      <td><input type="text" name="LimInf" id="LimInf" style="width:30" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)"
	  <? if($TipoDato=='N'){?> value="<? echo $LimInf?>"<? }else{?> disabled value="" <? }?> ></td>           
    </tr>
    <tr>    	
    	<td>Limite Superior</td>
      <td><input type="text" name="LimSup" id="LimSup" style="width:30" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)"
	  <? if($TipoDato=='N'){?> value="<? echo $LimSup?>"<? }else{?> disabled value=""<? }?> ></td>   		
      	<td>Ancho</td>
      	<td><input type="text" name="Ancho" style="width:30" id="Ancho" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)"
      <?	if($TipoControl=='Area de Texto'||$TipoControl=='Medicamentos'||$TipoControl=='CUPS'||$TipoControl=='Cuadro de Texto'||$TipoControl=='Imagen')
	  		{?>value="<? echo $Ancho?>"<? }else{?> disabled value=""<? }?>>
      	</td>
      	<td>Alto</td>
      	<td><input type="text" name="Alto" style="width:30" id="Alto" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)"
      <?	if($TipoControl=='Area de Texto'||$TipoControl=='Medicamentos'||$TipoControl=='CUPS'||$TipoControl=='Cuadro de Texto'||$TipoControl=='Imagen')
	  		{?>value="<? echo $Alto?>"<? }else{?> disabled value=""<? }?>></td>
   		<td>Longitud</td>
      	<td><input type="text" name="Longitud" id="Longitud" style="width:30" onKeyDown="xNumero(this)" onKeyUp="xNumero(this)"
	  <? if($TipoDato!='L'){?>value="<? echo $Longitud?>"<? }else{?> disabled value=""<? }?>></td>
    </tr>
    <tr>
      
      <?		if($Modificar){?><script language="javascript">AnchoAlto();</script><? }?>


    	<td>Imagen</td><td><input name="Imagen" type="text" value="<? echo $Imagen?>"/></td>
		<td>Parametros</td>
	    <td colspan="5"><input type="text" name="Parametro" style="width:500" value="<?echo $Parametro?>" ></td>
	<tr>      
		<td>Traer de</td>      
      <td >
 	<?	$cons11="Select especialidad from Salud.especialidades where compania='$Compania[0]' Order By especialidad";
		//echo $cons11;
		$res11=ExQuery($cons11);?>
      <select name="TFTraerDe" onChange="frames.FrameOpener.location.href='BuscadorHC.php?DatNameSID=<? echo $DatNameSID?>&BuscaFormatos=1&TipoFormato='+this.value;">
      <option></option>
	<?	while($fila11=ExFetch($res11))
		{
			if($TFTraerDe==$fila11[0]){echo "<option selected value='$fila11[0]'>$fila11[0]</option>";}
			else{echo "<option value='$fila11[0]'>$fila11[0]</option>";}
		}
	?>      
      </select>
      
      <select name="Traerde" id="Traerde" onChange="frames.FrameOpener.location.href='BuscadorHC.php?DatNameSID=<? echo $DatNameSID?>&BuscaItems=1&Formato='+this.value+'&TipoFormato='+TFTraerDe.value">
      <option></option>
        <?
					$cons="Select Formato from HistoriaClinica.Formatos where TipoFormato='$TFTraerDe' and Compania='$Compania[0]'";

					$res=ExQuery($cons,$conex);
					while($fila=ExFetch($res))
					{
						if($fila[0]==$Traerde){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
						else{echo "<option value='$fila[0]'>$fila[0]</option>";}
					}
				?>
                  </select>

      <select name="CampoTraerDe">
      <option></option>
      <?
	  	$cons="Select Item from HistoriaClinica.ItemsxFormatos where TipoFormato='$TFTraerDe' and Formato='$Traerde' and Compania='$Compania[0]' 
		and estado='AC'	Order By Pantalla,Id_Item";
		$res=ExQuery($cons,$conex);
		while($fila=ExFetch($res))
		{
			if($fila[0]==$CampoTraerDe){echo "<option selected value='$fila[0]'>$fila[0]</option>";}
			else{echo "<option value='$fila[0]'>$fila[0]</option>";}
		}
      ?>
      </select>
                  
		</td>
		<td>TIP</td>
        <td colspan="5"><input name="TIP" type="text" value="<? echo $TIP?>" onKeyUp="xLetra(this)" onKeyDown="xLetra(this)"/>    
        Obligar
      <? if ($Obligatorio==1){?><input name="Obligatorio" type="checkbox" value="1" checked><? }else{?><input name="Obligatorio" type="checkbox" value="1"><? }?>
		Linea Sola
       <? if($LineaSola==1){?><input name="LineaSola" type="checkbox"  value="1" checked><? }else{?><input name="LineaSola" type="checkbox" value="1"><? }?>
       Cierra
       <? if($CierraFila==1){?><input name="CierraFila" type="checkbox" value="1" checked><? }else{?><input name="CierraFila" type="checkbox" value="1"><? }?>
       </td>
    </tr>
	
    <tr>
    	<td>Formato XML</td>
        <td colspan="8">TAG           
        	<input type="hidden" name="FormatoXML" value="<? echo $FormatoXML?>">
      	<?	if($FormatoXML==NULL){$FormatoXML="0";}
			$consXML="select tag from historiaclinica.tagsxml where compania='$Compania[0]' and formato=$FormatoXML order by orden";
			$resXML=ExQuery($consXML);?>
            <select name="TagXML" 
           	onChange="frames.FrameOpener.location.href='BuscadorHC.php?DatNameSID=<? echo $DatNameSID?>&BuscaEtiqXML=1&Formato=<? echo $NewFormato?>&TipoFomarto=<? echo $TF?>&FormatoXML='+FormatoXML.value+'&TagXML='+this.value+'&EtiqXML='+AuxEtiqXML.value;">
          		<option></option>
          	<?	while($filaXML=ExFetch($resXML))
				{
					if($TagXML==$filaXML[0]){echo "<option value='$filaXML[0]' selected>$filaXML[0]</option>";}
					else{ echo "<option value='$filaXML[0]'>$filaXML[0]</option>";}
				}?>
            </select> Etiqueta
     	<?	$consXML="select etiqxml from  historiaclinica.itemsxformatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF' and 
			etiqxml!='$EtiqXML' and tagxml='$TagXML'";
			$resXML=ExQuery($consXML);
			$consXML2="select etiquetaxml from  historiaclinica.dxformatos where compania='$Compania[0]' and formato='$NewFormato' and tipoformato='$TF' and 
			etiquetaxml!='$EtiqXML' and etiquetaxml!='' and tagxml='$TagXML'";
			$resXML2=ExQuery($consXML2);			
			$consXML="select etiqueta from historiaclinica.etiquetasxformatoxml where compania='$Compania[0]' and formato=$FormatoXML and tag='$TagXML'
			and etiqueta not in ('0'";
				while($filaNoxml=ExFetch($resXML)){$consXML=$consXML.",'$filaNoxml[0]'";}
				while($filaNoxml2=ExFetch($resXML2)){$consXML=$consXML.",'$filaNoxml2[0]'";}
			$consXML=$consXML.") order by orden";	
			$resXML=ExQuery($consXML);?>
            <select name="EtiqXML" 
   			 onChange="frames.FrameOpener.location.href='BuscadorHC.php?DatNameSID=<? echo $DatNameSID?>&CambiarValXML=1&FormatoXML='+FormatoXML.value+'&TagXML='+TagXML.value+'&EtiqXML='+this.value;">
            	<option></option>
       		<?	while($filaXML=ExFetch($resXML))
				{
					if($EtiqXML==$filaXML[0]){echo "<option value='$filaXML[0]' selected>$filaXML[0]</option>";}
					else{ echo "<option value='$filaXML[0]'>$filaXML[0]</option>";}
				}?>
            </select>
            <input type="hidden" name="AuxEtiqXML" value="<? echo $EtiqXML?>">
        </td>
    </tr>
    <tr align="center"><td colspan="8" scope="row"><input type="submit" value="Guardar" name="Guardar">
	<?
	if($NewFormato&&$TF&&$IdItem&&$Item)
	{?>
    <input type="button" name="Dependencia" value="Dependencia" onClick="Dependencias();" >
    <?
	}?>
    <input type="button" value="Cancelar" onClick="location.href='ItemsxFormato.php?DatNameSID=<? echo $DatNameSID?>&NewFormato=<? echo $NewFormato?>&TF=<? echo $TF?>'">
    </td></tr>
  </table>
<input type="Hidden" name="IdItem" value="<? echo $IdItem?>">
<input type="Hidden" name="NewFormato" value="<? echo $NewFormato?>">
<input type="Hidden" name="Defecto" value="<? echo $Defecto?>">
<input type="Hidden" name="TF" value="<? echo $TF?>">
<input type="Hidden" name="PantallaAnt" value="<? echo $PantallaAnt?>">
<input type="Hidden" name="DatNameSID" value="<? echo $DatNameSID?>">

</form>

<iframe id="FrameOpener" name="FrameOpener" style="display:none" frameborder="0" height="1" style="border:#e5e5e5 ridge">
