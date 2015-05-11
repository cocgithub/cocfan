/*  nds_up_ques  v3.2
    Plugin FOR Discuz! X 
    WWW.NWDS.CN | NDS.西域数码工作室  版权保护 请勿抄袭
    Plugin update 20130816 BY singcee
*/
  function Showmax(oid,s,m)
{ 
  var chmaxsp = document.getElementById("chmaxsp"+oid);
  var ohtinputsp = document.getElementById("ohtinputsp"+oid);
  var textsizesp = document.getElementById("textsizesp"+oid);
  var textareawidthsp = document.getElementById("textareawidthsp"+oid);
  var tktsp = $("tktsp"+oid);
  var selstylesp = $("selstylesp"+oid);
  textsizesp.style.display ="none";
  chmaxsp.style.display ="none";
  ohtinputsp.style.display="none";
  textareawidthsp.style.display ="none";
  tktsp.style.display ="none";
  selstylesp.style.display ="none";
 if (m == 1) {
  var imgttsp = document.getElementById("imgttsp"+oid);
  var imginsp = document.getElementById("imginsp"+oid);
  var imgstylesp = document.getElementById("imgstylesp"+oid);
  var hdtsp =  document.getElementById("hdtsp"+oid);
  var jzdxtsp = document.getElementById("jzdxtsp"+oid);
  imgttsp.style.display ="none";
  imginsp.style.display ="none";
  imgstylesp.style.display ="none";
  hdtsp.style.display ="none";
  jzdxtsp.style.display ="none";
 }
  switch(s) {
         case "2": 
		   ohtinputsp.style.display="inline";
		   selstylesp.style.display="inline";
    	    break;
		 case "3":  
		   ohtinputsp.style.display="inline";
    	    break;
          case "4": 
		    chmaxsp.style.display="inline";
			ohtinputsp.style.display="inline";  
			selstylesp.style.display="inline"; 
    	    break;
		  case "5":
		    chmaxsp.style.display="inline";
			ohtinputsp.style.display="inline"; 
    	    break;
		   case "6":
			 textsizesp.style.display ="inline";
			 tktsp.style.display ="inline";	
    	    break;
		   case "7":
             textsizesp.style.display ="inline";
			 textareawidthsp.style.display ="inline";
    	    break;
		    case "8":
			  hdtsp.style.display = "inline";
    	    break;
			case "9":
              chmaxsp.style.display="inline"; 
			  imgttsp.style.display="inline";
			  imginsp.style.display="inline";
			  imgstylesp.style.display="inline";
    	    break;
			case "10":
			  jzdxtsp.style.display ="inline";
			  imginsp.style.display="inline";
    	    break;
		    default:
            break;
	}
}
function Showmax2(ndsdiv1,s,e,m)
{  
     var td2=ndsdiv1.parentNode.parentNode.parentNode;
     var tindex2=td2.rowIndex;
	  if(e == 'newques' )  tindex2++;
    var chmaxsp = getElementsByName2("span","chmaxsp")[tindex2];
    var ohtinputsp = getElementsByName2("span","ohtinputsp")[tindex2];
    var textsizesp = getElementsByName2("span","textsizesp")[tindex2];
    var textareawidthsp = getElementsByName2("span","textareawidthsp")[tindex2];
	var tktsp = getElementsByName2("span","tktsp")[tindex2];
	var selstylesp = getElementsByName2("span","selstylesp")[tindex2];
  textsizesp.style.display ="none";
  chmaxsp.style.display ="none";
  ohtinputsp.style.display="none";
  textareawidthsp.style.display ="none";
  tktsp.style.display ="none";
  selstylesp.style.display ="none";
if (m == 1){
    var imgttsp = getElementsByName2("span","imgttsp")[tindex2];
    var imginsp = getElementsByName2("span","imginsp")[tindex2];
    var imgstylesp = getElementsByName2("span","imgstylesp")[tindex2];
    var hdtsp =  getElementsByName2("span","hdtsp")[tindex2];
	var jzdxtsp =  getElementsByName2("span","jzdxtsp")[tindex2];
  imgttsp.style.display ="none";
  imginsp.style.display ="none";
  imgstylesp.style.display ="none";
  hdtsp.style.display ="none";
  jzdxtsp.style.display ="none";
 } 
  switch(s) {
          case "2": 
		   ohtinputsp.style.display="inline";
		   selstylesp.style.display="inline";
    	    break;
		  case "3":  
		   ohtinputsp.style.display="inline";
    	    break;
		  case "4": 
		    chmaxsp.style.display="inline";
			ohtinputsp.style.display="inline";  
			selstylesp.style.display="inline";  
    	    break;
		  case "5":
		    chmaxsp.style.display="inline";
			ohtinputsp.style.display="inline";
    	    break;
		   case "6":
			 textsizesp.style.display ="inline";
			 tktsp.style.display ="inline";	
    	    break;
		   case "7":
             textsizesp.style.display ="inline";
			 textareawidthsp.style.display ="inline";
    	    break;
		    case "8":
			  hdtsp.style.display = "inline";
    	    break;
			case "9":
              chmaxsp.style.display="inline"; 
			  imgttsp.style.display="inline";
			  imginsp.style.display="inline";
			  imgstylesp.style.display="inline";
    	    break;
			case "10":
			  jzdxtsp.style.display="inline";
			  imginsp.style.display="inline";
     	    break;
		    default:
            break;
	}		

}
function deleteRow(input1)
{
     var td1=input1.parentNode;
     var tr1=td1.parentNode;
     var tindex=tr1.rowIndex;
     document.getElementById("quesbody").deleteRow(tindex);
	 node--; 
}
  function addRow(isbody){
     var tableObj=document.getElementById(isbody);
  	 var newRowObj=tableObj.insertRow(tableObj.rows.length);
     var newColName=newRowObj.insertCell(newRowObj.cells.length);
     //var newColtd=newRowObj.insertCell(newRowObj.cells.length);
  	  newRowObj.height ="118";
	 //newRowObj.colSpan ="2";
	 newColName.innerHTML =document.getElementById("quesbodyhidden").innerHTML;
	 node++;
  }
  
  function doShow(ck,tk)
{  
  var a = document.getElementById(ck);
  var b = document.getElementById(tk);
  if(a.checked){
         b.style.display="inline";
  }else{
	     b.style.display ="none";
  }
}
var getElementsByName2 = function(tag, name){
    returns = new Array();
    var e = document.getElementsByTagName(tag);
    for(var i = 0; i < e.length; i++){
        if(e[i].getAttribute("name") == name){
            returns[returns.length] = e[i];
        }
    }
    return returns;
}
function NDSCheckAll(delid){
 var ck = document.getElementsByName(delid);
 var cb = document.getElementsByName("chkall");
	for(i=0;i<ck.length;i++){
		ck[i].checked = cb[0].checked;
	}
}
function addCurentTime(addd){ 
    	var now = new Date();
		    now = now.valueOf();
            now = now + addd * 24 * 60 * 60 * 1000;
		    now = new Date(now);
        var year = now.getFullYear();  
        var month = now.getMonth() + 1; 
        var day = now.getDate(); 
        var hh = now.getHours();  
        var mm = now.getMinutes();
       
        var clock = year + "-";
          clock += month + "-";
          clock += day + " ";
          clock += hh + ":";
		  clock += mm; 
        return(clock); 
    } 