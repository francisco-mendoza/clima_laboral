// ------------------------------------------------------------------
// Psicus.NET
// Unidad de Sistemas 
// Libreria con funciones Java Script 
// Version 1.1
// Domingo 3 de Octubre del 2004
// ------------------------------------------------------------------
// Funciones Utilitarias
// ------------------------------------------------------------------
String.prototype.trim = function()
{
	// Use una expresión regular para reemplazar los espacios iniciales y
	// finales con una cadena vacía
	
	return this.replace(/(^\s*)|(\s*$)/g, "");
}

function placeFocus() {
	if (document.forms.length > 0) {
		var field = document.forms[0];
		for (i = 0; i < field.length; i++) {
			if ((field.elements[i].type == "text")) {
				document.forms[0].elements[i].focus();
				break;
			}
		}
	}
}
function putFocus(formInst, elementInst) {
	if (document.forms.length > 0) {
		document.forms[formInst].elements[elementInst].focus();
	}
}

// ------------------------------------------------------------------
// Funciones de Validación
// ------------------------------------------------------------------
function isEmpty(inputStr){
	if (inputStr =="" || inputStr ==null){
		return true
	}
	return false
}

function EsNumero ( Numero ){
	var er_nro = /^([0-9])+$/

	if( !er_nro.test( Numero ) ){
		return false;
	}
	return true;
}
function isDigit (c){
	return ((c >= "0") && (c <= "9"))
}
function EsDecimal(s){
	var i;
	var dotAppeared;
	dotAppeared = false;

	for (i = 0; i < s.length; i++)
	{
		var c = s.charAt(i);
		if( i != 0 ) {
			if ( c == "." ) {
				if( !dotAppeared )
					dotAppeared = true;
				else
					return false;
			} else
				if (!isDigit(c)) return false;
		} else {
			if ( c == "." ) {
				if( !dotAppeared )
					dotAppeared = true;
				else
					return false;
			} else
				if (!isDigit(c) && (c != "-") || (c == "+")) return false;
		}
	}
	return true;
}

function isEmail(email) { 
	invalidChars = " ~\'^\`\"*+=\\|][(){}$&!#%/:,;"; 

	// Check for null 
	if (email == "") { 
		return false; 
	}

	// Check for invalid characters as defined above 
	for (i=0; i<invalidChars.length; i++) { 
		badChar = invalidChars.charAt(i); 
		if (email.indexOf(badChar,0) > -1) { 
			return false; 
		}
	}
	lengthOfEmail = email.length; 
	if ((email.charAt(lengthOfEmail - 1) == ".") || (email.charAt(lengthOfEmail - 2) == ".")) { 
		return false; 
	}
	Pos = email.indexOf("@",1); 
	if (email.charAt(Pos + 1) == ".") { 
		return false; 
	}
	while ((Pos < lengthOfEmail) && ( Pos != -1)) { 
		Pos = email.indexOf(".",Pos); 
		if (email.charAt(Pos + 1) == ".") { 
			return false; 
		}
		if (Pos != -1) {
			Pos++;
		}
	}

	// There must be at least one @ symbol 
	atPos = email.indexOf("@",1); 
	if (atPos == -1) { 
		return false; 
	}

	// But only ONE @ symbol 
	if (email.indexOf("@",atPos+1) != -1) { 
		return false; 
	} 

	// Also check for at least one period after the @ symbol 
	periodPos = email.indexOf(".",atPos); 
	if (periodPos == -1) { 
		return false; 
	} 
	if (periodPos+3 > email.length) { 
		return false; 
	} 
	return true; 
}


function VRut ( nRut, cRut ){
	var dvr = '0'
	suma = 0
	mult = 2

	for ( i = nRut.length - 1 ; i >= 0 ; i -- ) {
		suma = suma + nRut.charAt(i) * mult
		if ( mult == 7 )
			mult = 2
		else
			mult ++
	}

	res = suma % 11
	if ( res == 1 )
		dvr = 'k'
	else {
		if ( res == 0 )
			dvr = '0'
		else {
			dvi = 11 - res
			dvr = dvi + ""
			}
	}
	if ( dvr != cRut.toLowerCase() ){
		return false;
	}
	return true;
}

function format (expr, decplaces) {
// raise incoming value by power of 10 times the
// number of decimal places; round to an integer; convert to string
var str = "" + Math.round (eval(expr) * Math.pow(10,decplaces))
// pad small value strings with zeros to the left of rounded number
while (str.length <= decplaces) {
str = "0" + str
}
// establish location of decimal point
var decpoint = str.length - decplaces
// assemble final result from: (a) the string up to the position of
// the decimal point; (b) the decimal point; and (c) the balance
// of the string. Return finished product.
return str.substring(0,decpoint) + "." + str.substring(decpoint,str.length);
}

// ------------------------------------------------------------------
// Funciones de Validación de campos Fecha - Hora
// ------------------------------------------------------------------
function VFecha( inputStr ){
	var months = new Array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre")
	// convert hyppen delimiters to slashes
	while ( inputStr.indexOf("-") != -1 ){
		inputStr = replaceString( inputStr, "-", "/")
	}

	//extract components of input data
	var delim1 = inputStr.indexOf("/")
	var delim2 = inputStr.lastIndexOf("/")
	if (delim1 != -1 && delim1 == delim2) {
		// there is only one delimiter in the string
		alert("Estimado Usuario, la fecha ingresada no tiene un formato aceptable.\n\nUsted puede ingresar usuando los siguientes formatos: dd/mm/yyyy o dd-mm-yyyy.")
		return false
	}

	// there are delimiters; extract component values
	var dd	= parseInt(inputStr.substring(0,delim1),10)
	var mm	= parseInt(inputStr.substring(delim1 + 1,delim2),10)
	var yyyy= parseInt(inputStr.substring(delim2 + 1, inputStr.length),10)

	if (isNaN(mm) || isNaN(dd) || isNaN(yyyy)) {
		// there is a non-numeric character in one of the component values
		alert("Estimado Usuario, la fecha ingresada no tiene un formato aceptable.\n\nUsted puede ingresar usuando los siguientes formatos: dd/mm/yyyy o dd-mm-yyyy.")
		return false
	}
	if (mm < 1 || mm > 12) {
		// month value is not 1 thru 12
		alert("Estimado Usuario, el mes debe ser ingresado entre 01 (Enero) y 12 (Diciembre).")
		return false
	}
	if ( dd < 1 ) {
		// date value is not 1 thru 31
		alert("Estimado Usuario, el dia debe ser ingresado entre 01 y como maximo 31 (dependiendo del mes y año).")
		return false
	}else{
		if (( mm == 4 || mm == 6 || mm == 9 || mm == 11 ) && dd > 30 ) {
			alert( "Estimado Usuario, el mes de " + months[mm] + " tiene 30 dias." )
			return false
		}else if ( dd > 31 ) {
			alert( "Estimado Usuario, el mes de " + months[mm] + " tiene 31 dias." )
			return false
		}
	}
	if (yyyy < 100){
		// entered value is two digits, which we allow for 1930-2029
		if ( yyyy >= 30 ){
			yyyy +=1900
		}else{
			yyyy += 2000
		}
	}
	else{
		if ( yyyy >= 100 && yyyy < 1900 ){
			alert("Estimado Usuario, por restricciones a nivel de motor de datos el año debe ser mayor que 1990.")
		}
	}
	if ( mm == 2 ){
		if ( ( yyyy % 4 ) > 0 && dd > 28 ) {
			alert( "Estimado Usuario, el mes de " + months[mm] + " del " + yyyy + " tiene 28 dias." )
			return false
		}else if ( dd > 29 ) {
			alert( "Estimado Usuario, el mes de " + months[mm] + " del " + yyyy + " tiene 29 dias." )
			return false
		}
	}
	
	//attempt to create date object from input data
	var testDate = new Date()
	testDate.setFullYear( yyyy );
	testDate.setMonth( mm - 1 );
	testDate.setDate( dd );
	
	//extract pieces from date object
	var testMo	= testDate.getMonth()+1
	//var testMo	= testDate.getMonth()

	var testDay = testDate.getDate()
	var testYr	= testDate.getFullYear()
	
	//make sure conversion to date object succeeded
	if (isNaN(testMo)||isNaN(testDay)||isNaN(testYr)){
		alert("REstimado Usuario, ,revise la fecha ingresada, no es valida.")
		return false
	}
	
	//make sure values match
	/* if (testMo !=mm ||testDay !=dd ||testYr !=yyyy){
		alert("Estimado Usuario, revise la fecha ingresada, no es valida.");
		return false
	} */
	return true
}

function ObjFecha ( inputFecha ){
	// convert hyppen delimiters to slashes
	while ( inputFecha.indexOf("-") != -1 ){
		inputFecha = replaceString( inputFecha, "-", "/")
	}
	//extract components of input data
	var delim1 = inputFecha.indexOf("/")
	var delim2 = inputFecha.lastIndexOf("/")

	// there are delimiters; extract component values
	var dd	= parseInt(inputFecha.substring(0,delim1),10)
	var mm	= parseInt(inputFecha.substring(delim1 + 1,delim2),10)
	var yyyy= parseInt(inputFecha.substring(delim2 + 1, inputFecha.length),10)

	if (yyyy < 100){
		// entered value is two digits, which we allow for 1930-2029
		if ( yyyy >= 30 ){
			yyyy +=1900
		}else{
			yyyy += 2000
		}
	}
	var vDate	= new Date();
	vDate.setFullYear( yyyy );
	vDate.setMonth( mm );
	vDate.setDate( dd );

	vDate.setHours(0,0,0,0);
	return vDate;
}
function makeDDMMYYYY(inputFecha){
	// convert hyppen delimiters to slashes
	while ( inputFecha.indexOf("-") != -1 ){
		inputFecha = replaceString( inputFecha, "-", "/")
	}
	//extract components of input data
	var delim1 = inputFecha.indexOf("/")
	var delim2 = inputFecha.lastIndexOf("/")

	// there are delimiters; extract component values
	var dd	= parseInt(inputFecha.substring(0,delim1),10)
	var mm	= parseInt(inputFecha.substring(delim1 + 1,delim2),10)
	var yyyy= parseInt(inputFecha.substring(delim2 + 1, inputFecha.length),10)

	if (yyyy < 100){
		// entered value is two digits, which we allow for 1930-2029
		if ( yyyy >= 30 ){
			yyyy +=1900
		}else{
			yyyy += 2000
		}
	}
	var sRes = padNmb(dd, 2, "0") + "/" + padNmb(mm, 2, "0") + "/" + padNmb(yyyy, 4, "0");
	return sRes;
}

function VHora ( hora ) {
	var hh	= hora.substring( 0,2 );
	var se	= hora.substring( 2,3 );
	var mm	= hora.substring( 3,5 );
	
	if ( !EsNumero ( hh ) ) {
		return false;
	} 
	if ( se != ":" ) {
		return false;
	} 
	if ( !EsNumero ( mm ) ) {
		return false;
	} 
	
	if ( ( hh  < 0 ) || ( hh > 24 ) ) {
		return false;
	}
	if ( ( mm  < 0 ) || ( mm > 59 ) ) {
		return false;
	}
	return true;
}
function numDias(mes,agno) {
	if (mes==4 || mes==6 || mes==9 || mes==11) return 30;
	else if ((mes==2) && AgnoBisiesto(agno)) return 29;
	else if (mes==2) return 28;
	else return 31;
}
function AgnoBisiesto(agno) {
	if (((agno % 4 == 0) && agno % 100 != 0) || agno % 400 == 0)
		return true;
	else
		return false;
}	

function difFecha ( dInicio, dFinal  ){
	var un_dia  = 1000*60*60*24;
	var difdia  = Math.ceil((dFinal.getTime()- dInicio.getTime())/(un_dia));
	
	return difdia;
}

var aFinMes = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

function finMes(nMes, nAno){
	return aFinMes[nMes - 1] + (((nMes == 2) && (nAno % 4) == 0)? 1: 0);
}

function padNmb(nStr, nLen, sChr){
	var sRes = String(nStr);
	for (var i = 0; i < nLen - String(nStr).length; i++)
		sRes = sChr + sRes;
	return sRes;
}

function makeDateFormat(nDay, nMonth, nYear){
	var sRes;
	sRes = padNmb(nDay, 2, "0") + "/" + padNmb(nMonth, 2, "0") + "/" + padNmb(nYear, 4, "0");
	return sRes;
}

function incDate(sFec0){
	var nDia = parseInt(sFec0.substr(0, 2), 10);
	var nMes = parseInt(sFec0.substr(3, 2), 10);
	var nAno = parseInt(sFec0.substr(6, 4), 10);
	nDia += 1;
	if (nDia > finMes(nMes, nAno)){
		nDia = 1;
		nMes += 1;
		if (nMes == 13){
			nMes = 1;
			nAno += 1;
		}
	}
	return makeDateFormat(nDia, nMes, nAno);
}

function decDate(sFec0){
	var nDia = Number(sFec0.substr(0, 2));
	var nMes = Number(sFec0.substr(3, 2));
	var nAno = Number(sFec0.substr(6, 4));
	nDia -= 1;
	if (nDia == 0){
		nMes -= 1;
		if (nMes == 0){
			nMes = 12;
			nAno -= 1;
		}
		nDia = finMes(nMes, nAno);
	}
	return makeDateFormat(nDia, nMes, nAno);
}

function addToDate(sFec0, sInc){
var nInc = Math.abs(parseInt(sInc));
var sRes = sFec0;
	if (parseInt(sInc) >= 0)
		for (var i = 0; i < nInc; i++) sRes = incDate(sRes);
	else
		for (var i = 0; i < nInc; i++) sRes = decDate(sRes);
	return sRes;
}




function recalcF1(){
	with (document.formulario){
		fecha1.value = addToDate(fecha0.value, increm.value);
	}
}
// extract front part of string prior to searchString
function getFront(mainStr,searchStr){
	foundOffset = mainStr.indexOf(searchStr)
	if (foundOffset == -1) {
		return null
	}
	return mainStr.substring(0,foundOffset)
}

// extract back end of string after searchString
function getEnd(mainStr,searchStr) {
	foundOffset = mainStr.indexOf(searchStr)
	if (foundOffset == -1) {
		return null
	}
	return mainStr.substring(foundOffset+searchStr.length,mainStr.length)
}

// insert insertString immediately before searchString
function insertString(mainStr,searchStr,insertStr) {
	var front = getFront(mainStr,searchStr)
	var end = getEnd(mainStr,searchStr)
	if (front != null && end != null) {
		return front + insertStr + searchStr + end
	}
	return null
}

// remove deleteString
function deleteString(mainStr,deleteStr) {
	return replaceString(mainStr,deleteStr,"")
}

// replace searchString with replaceString
function replaceString(mainStr,searchStr,replaceStr) {
	var front = getFront(mainStr,searchStr)
	var end = getEnd(mainStr,searchStr)
	if (front != null && end != null) {
		return front + replaceStr + end
	}
	return null
}

// ------------------------------------------------------------------
// Trabajando con Images
// ------------------------------------------------------------------
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function MM_findObj(n, d) { //v4.0
	var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
		d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
	if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
	for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
	if(!x && document.getElementById) x=document.getElementById(n); return x;
}
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
	if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
	document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
	else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
// -->
function MM_showHideLayers() { //v3.0
	var i,p,v,obj,args=MM_showHideLayers.arguments;
	for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
	if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
	obj.visibility=v; }
}

function MM_swapImgRestore() { //v3.0
	var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
	var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
	var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
	if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImage() { //v3.0
	var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
	if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
