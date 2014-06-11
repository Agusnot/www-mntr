	/*<script language="javascript" src="/Funciones.js"></script>
	onKeyUp="xNumero(this)" onKeyDown="xNumero(this)" onBlur="campoNumero(this)"*/
	function xNumero(Valor)
	{
		Long = Valor.value.length;
		UltDigito = Valor.value.substr(Long-1,1);
		if(isNaN(UltDigito) || UltDigito == " "){
			if(UltDigito != "."){Valor.value = Valor.value.substr(0,Long-1);}}
	}
	function campoNumero(Valor)
	{if(isNaN(Valor.value)){Valor.value = "";}}
	/*onKeyUp="xLetra(this)" onKeyDown="xLetra(this)" onBlur="campoTexto(this)"*/
	function xLetra(Valor)
	{
		Long = Valor.value.length;
		UltLetra = Valor.value.substr(Long-1,1);
		if(UltLetra != " ")
		{
			if( 	 UltLetra == "`" || UltLetra == "~" || UltLetra == "!" || 
					 UltLetra == "@" || UltLetra == "#" ||
					 UltLetra == "^" || UltLetra == "&" || UltLetra == "*" || 
					 UltLetra == "_" || 
					 UltLetra == "]" || UltLetra == "}" || UltLetra == "[" || 
					 UltLetra == "{" || UltLetra == "\"" || UltLetra == "\'" || 
					 UltLetra == "/" || 
					 UltLetra == ">" || UltLetra == "<" || UltLetra == "\\" || UltLetra == "|") 
				{
					Valor.value = Valor.value.substr(0,Long-1);
				}	
		}
		
	}
	/*onKeyUp="ExLetra(this)" onKeyDown="ExLetra(this)" onBlur="campoTexto(this)"*/
	function ExLetra(Valor)
	{
		Long = Valor.value.length;
		UltLetra = Valor.value.substr(Long-1,1);
		if(UltLetra != " ")
		{
			if(isNaN(UltLetra) == false || UltLetra == "`" || UltLetra == "~" || UltLetra == "!" || 
					 UltLetra == "@" || UltLetra == "#" || UltLetra == "$" || UltLetra == "%" || 
					 UltLetra == "^" || UltLetra == "&" || UltLetra == "*" || UltLetra == "(" || 
					 UltLetra == ")" || UltLetra == "-" || UltLetra == "_" || UltLetra == "=" || 
					 UltLetra == "+" || UltLetra == "]" || UltLetra == "}" || UltLetra == "[" || 
					 UltLetra == "{" || UltLetra == "\"" || UltLetra == "\'" || UltLetra == ";" || 
					 UltLetra == ":" || UltLetra == "?" || UltLetra == "/" || UltLetra == "." || 
					 UltLetra == ">" || UltLetra == "," || UltLetra == "<" || UltLetra == "\\" || UltLetra == "|" ) 
				{
					Valor.value = Valor.value.substr(0,Long-1);
				}	
		}
		
	}
	function evitarSubmit(evento)
	{
		//alert("Entra");
		//if(document.all){alert("Entra document.all"); tecla = evento.keyCode;}
		//else{ alert("else");tecla = evento.which;}
		if(evento.keyCode==13){return false;}
		//return(tecla != 13);
	}
	function evitarBack(evento)
	{
		
	}	
	/*onKeyUp="evitarSubmit(event)"*/
	
	/*onKeyUp="Pasar(evento,'siguienteCampo')"*/
	function Pasar(evento,proxCampo)
	{
		if(evento.keyCode == 13){document.getElementById(proxCampo).focus();}
	}
	
	function UltimoDiajs(aaaa,mm)
	{ 
		var dias_febrero;
		if(((aaaa%4==0) && (aaaa%100!=0)) || aaaa%400==0) 
		{ 
			dias_febrero = 29; 
		} 
		else 
		{ 
			dias_febrero = 28; 
		} 
		switch(mm) 
		{ 
			case 01: return 31; break; 
			case 02: return dias_febrero; break; 
			case 03: return 31; break; 
			case 04: return 30; break; 
			case 05: return 31; break; 
			case 06: return 30; break; 
			case 07: return 31; break; 
			case 08: return 31; break; 
			case 8: return 31; break; 
			case 09: return 30; break; 
			case 9: return 30; break; 
			case 10: return 31; break; 
			case 11: return 30; break; 
			case 12: return 31; break; 
		} 
	}
	//----Sumar Dias a Fechas ---//
	var aFinMesSD = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31); 
	
	function finMesSD(nMes, nAnio){ 
	return aFinMesSD[nMes - 1] + (((nMes == 2) && (nAnio % 4) == 0)? 1: 0); 
	} 
	
	function padNmbSD(nStr, nLen, sChr){ 
	var sRes = String(nStr); 
	for (var i = 0; i < nLen - String(nStr).length; i++) 
	sRes = sChr + sRes; 
	return sRes; 
	} 
	
	function makeDateFormatSD(nYear,nMonth,nDay){ 
	var sRes; 
	sRes = padNmbSD(nYear, 4, "0") + "-" + padNmbSD(nMonth, 2, "0") + "-" + padNmbSD(nDay, 2, "0");
	return sRes; 
	} 
	
	function incDateSD(sFec0){ 	
	var nDia = parseInt(sFec0.substr(8, 2), 10); 
	var nMes = parseInt(sFec0.substr(5, 2), 10); 
	var nAnio = parseInt(sFec0.substr(0, 4), 10); 
	nDia += 1; 
	if (nDia > finMesSD(nMes, nAnio)){ 
	nDia = 1; 
	nMes += 1; 
	if (nMes == 13){ 
	nMes = 1; 
	nAnio += 1; 
	} 
	} 
	return makeDateFormatSD(nAnio,nMes,nDia); 
	} 
	
	function decDateSD(sFec0){ 
	var nDia = Number(sFec0.substr(8, 2)); 
	var nMes = Number(sFec0.substr(5, 2)); 
	var nAnio = Number(sFec0.substr(0, 4)); 
	nDia -= 1; 
	if (nDia == 0){ 
	nMes -= 1; 
	if (nMes == 0){ 
	nMes = 12; 
	nAnio -= 1; 
	} 
	nDia = finMesSD(nMes, nAnio); 
	} 
	return makeDateFormatSD(nAnio,nMes,nDia); 
	} 
	
	function addToDateSD(sFec0, sInc){ 
	var nInc = Math.abs(parseInt(sInc)); 
	var sRes = sFec0; 
	if (parseInt(sInc) >= 0) 
	for (var i = 0; i < nInc; i++) sRes = incDateSD(sRes); 
	else 
	for (var i = 0; i < nInc; i++) sRes = decDateSD(sRes); 
	return sRes; 
	} 
	
	function SumaDiasFecha(ObjFecha,ObjInc)
	{		    
		if(ObjFecha.value!=''&&ObjInc.value!=''&&parseInt(ObjInc.value)>=0)
		{
		 	//alert(ObjFecha.value+" "+ObjInc.value);
			NuevaF = addToDateSD(ObjFecha.value, ObjInc.value); 
			//alert(NuevaF);
			return (NuevaF);			
		}
		else
		{
			//alert(ObjFecha.value);
			return (ObjFecha.value);				
		}  			
	}
	
function mascara(d,sep,pat,nums)
{
	if(d.valant != d.value){
	val = d.value
	largo = val.length
	val = val.split(sep)
	val2 = ''
	for(r=0;r<val.length;r++){
		val2 += val[r]	
	}
	if(nums){
		for(z=0;z<val2.length;z++){
			if(isNaN(val2.charAt(z))){
				letra = new RegExp(val2.charAt(z),"g")
				val2 = val2.replace(letra,"")
			}
		}
	}
	val = ''
	val3 = new Array()
	for(s=0; s<pat.length; s++){
		val3[s] = val2.substring(0,pat[s])
		val2 = val2.substr(pat[s])
	}
	for(q=0;q<val3.length; q++){
		if(q ==0){
			val = val3[q]
		}
		else{
			if(val3[q] != ""){
				val += sep + val3[q]
				}
		}
	}
	d.value = val
	d.valant = val
	}
}

	
