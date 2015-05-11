function $(id) {
		return !id ? null : document.getElementById(id);
} 
	function validate_form()
		{
		  var ndsdiv_all = document.getElementsByName("ndsdiv");
		  var  check_all=true;
		  for (i=0 ;i < ndsdiv_all.length; i++)
		  	{
			   if (ndsdiv_all[i].getAttribute("flag") == "t")
			   {
			    var old = ndsdiv_all[i].getAttribute("old");
				var istyep = ndsdiv_all[i].getAttribute("istyep");
				if (istyep == "4" || istyep  == "5" || istyep  == "9" || istyep  == "10" )  
				  {
		    	       var inputtype =  document.getElementsByName('suboption['+old+'][1]')[0].type;  
				   }else{
				       var inputtype =  document.getElementsByName('suboption['+old+']')[0].type;  
				   }
           switch (inputtype) {
                       case "text":
					    if ( document.getElementsByName('suboption['+old+']')[0].value == "" ) {
					        check_all=false; 
						   alert(nds_js_no+(i+1)+nds_js_tk);
                           document.getElementsByName('suboption['+old+']')[0].focus();
                           
						}
				    break;
					  case "textarea":
					   if ( document.getElementsByName('suboption['+old+']')[0].value == "" ) {
					         check_all=false;
						   alert(nds_js_no+(i+1)+nds_js_jd);
                           document.getElementsByName('suboption['+old+']')[0].focus();
						}
				    break;
						  case "radio":
						    var  check_all=false;
						  if (istyep == "3" || istyep == "2"  ){ 
								var suboptionradio = document.getElementsByName('suboption['+old+']');
								for (j=0 ;j < suboptionradio.length; j++)
									{
										if (suboptionradio[j].checked) 
										 {
										   check_all=true;
											break;
										 } 
								 
									}//for j
								 if (!check_all)  {
									  alert(nds_js_no+(i+1)+nds_js_dx);
									  document.getElementsByName('suboption['+old+']')[0].focus();
								 } 
						  }else {
						    var check_n = 0;
						    var chmax  = ndsdiv_all[i].getAttribute("jchmax");
    					    for (p=1;p<=chmax;p++) { 	  
							 var suboptionradio = document.getElementsByName('suboption['+old+']['+p+']');
						   	 for (j=0 ;j < suboptionradio.length; j++)
								{
							    	if (suboptionradio[j].checked) 
							     	{
							     	  	check_n++;
			                        	break;
								 	}   
							   } //for j
							  if (check_n < p)  break;
					 	   }//for p
					  	    if (check_n == chmax){
								check_all=true;
							}else{
								  alert(nds_js_no+(i+1)+nds_js_dx);
                           		  document.getElementsByName('suboption['+old+']['+p+']')[0].focus();
								}    
	                   } //else
     			    break;
						  case "checkbox":
						   check_all=false;
						   var chmax  = ndsdiv_all[i].getAttribute("jchmax");
						   var  j=1;
						   var  p=0;
						    while (document.getElementsByName('suboption['+old+']['+j+']')[0])   
							{
							    if (document.getElementsByName('suboption['+old+']['+j+']')[0].checked) 
							     {
							       check_all=true;
			                       p++; 
								 }
								j++; 
							 }
						   
					   if (!check_all)  {
					       alert(nds_js_no+(i+1)+nds_js_tx);
                           document.getElementsByName('suboption['+old+'][1]')[0].focus();
						}else if (p < chmax) {
						   check_all=false;
					       alert(nds_js_no+(i+1)+nds_js_min+chmax+nds_js_min2);
                           document.getElementsByName('suboption['+old+'][1]')[0].focus();
						}
				    break;
				    default:
            	    break;    	  
	
     		      } //  switch (ndsdivtype)      
   			    } //if (ndsdiv_all[i]   
	                if  (!check_all) 
				    {
				      break; 
					 return check_all;        
                     }
			 } //for ndsdiv
			   return check_all;

			 
		}//function validate_form
		
  function chmax_checkbox(oid,chmax,obj) {
	var  p=0;
   	var  i=1;
			while (document.getElementsByName('suboption['+oid+']['+i+']')[0])   
			{
				if (document.getElementsByName('suboption['+oid+']['+i+']')[0].checked) 
				 {
                   p++; 
				   if (p > chmax){
				     alert(nds_js_max+chmax+nds_js_max2);
					obj.checked = false;
				   }     
				 } 
				 i++;  
			 }
  }  //function chmax_checkbox 
  function pt_checkbox(oid,obj,oth) {
   if (oth != 2) {  
	 if (obj.checked) {
	    	var  i=1;
			while (document.getElementsByName('suboption['+oid+']['+i+']')[0])   
			{
 
			     document.getElementsByName('suboption['+oid+']['+i+']')[0].checked = false; 
				 document.getElementsByName('suboption['+oid+']['+i+']')[0].disabled=true;  
				 i++;  
			 }
		  obj.disabled=false; 	 
	      obj.checked = true;
		   if (oth == 3)  $('otinput['+oid+']').disabled = false;
	  }else{
	       var  i=1;
	       	 if (oth == 3)  { $('otinput['+oid+']').value= '';  $('otinput['+oid+']').disabled = true;}
			while (document.getElementsByName('suboption['+oid+']['+i+']')[0])   
			{
 				 document.getElementsByName('suboption['+oid+']['+i+']')[0].disabled=false;  
				 i++;  
			 }
	    }	
	} // oth != 2
	 if (oth == 2) {
	     if (obj.checked) {
		    $('otinput['+oid+']').disabled = false;
		  
		 }else{
		  $('otinput['+oid+']').value= ''; 
		   $('otinput['+oid+']').disabled = true;
    	 }
      } //oth == 2	 
  }  //function pt_checkbox 		
		
  function pt_radiocl(oid) {
        $('otinput['+oid+']').value= '';
		 $('otinput['+oid+']').disabled = true;
	 
  }  
    function pt_radio(oid) {
 	 $('otinput['+oid+']').disabled = false;
	 
  }
    function ndstag(id){
    	 var istd = document.getElementById('td'+id);
    	   if (istd.className=="td1"){
    		 // istd.setAttribute("class","td2");
    		  istd.className = "td2";
    	   }else{
    		  istd.className = "td1"; 
    		   // istd.setAttribute("class","td1");
    	   }
    }

 function intval(v)
 {
 	v = parseInt(v);
 	return isNaN(v) ? 0 : v;
 }
 function getPos(eo)
 {
 	var l = 0;
 	var t  = 0;
 	var w = intval(eo.style.width);
 	var h = intval(eo.style.height);
 	var wb = eo.offsetWidth;
 	var hb = eo.offsetHeight;
 	while (eo.offsetParent)
 	{
 		l += eo.offsetLeft + (eo.currentStyle?intval(eo.currentStyle.borderLeftWidth):0);
 		t += eo.offsetTop  + (eo.currentStyle?intval(eo.currentStyle.borderTopWidth):0);
 		eo = eo.offsetParent;
 	}
 	l += eo.offsetLeft + (eo.currentStyle?intval(eo.currentStyle.borderLeftWidth):0);
 	t  += eo.offsetTop  + (eo.currentStyle?intval(eo.currentStyle.borderTopWidth):0);
 	return {x:l, y:t, w:w, h:h, wb:wb, hb:hb};
 }
 function getScroll()
 {
 	var t, l, w, h;
 	if (document.documentElement && document.documentElement.scrollTop) 
 	{
 		t = document.documentElement.scrollTop;
 		l = document.documentElement.scrollLeft;
 		w = document.documentElement.scrollWidth;
 		h = document.documentElement.scrollHeight;
 	}
 	else if (document.body)
 	{
 		t = document.body.scrollTop;
 		l = document.body.scrollLeft;
 		w = document.body.scrollWidth;
 		h = document.body.scrollHeight;
 	}
 	return { t: t, l: l, w: w, h: h };
 }
 function scroller(el, duration)
 {       firefoxel=el;
 	if(typeof el != 'object') { el = document.getElementById(el); }
 	if(!el) { el = document.getElementsByName(firefoxel).item(0)}
         if(!el) return;
 	var z = this;
 	z.el = el;
 	z.p = getPos(el);
 	z.s = getScroll();
 	z.clear = function(){window.clearInterval(z.timer);z.timer=null};
 	z.t=(new Date).getTime();
 	z.step = function(){
 	var t = (new Date).getTime();
 	var p = (t - z.t) / duration;
 	if (t >= duration + z.t) {
 	z.clear();
 	window.setTimeout(function(){z.scroll(z.p.y, z.p.x)},13);
 	} else {
 	st = ((-Math.cos(p*Math.PI)/2) + 0.5) * (z.p.y-z.s.t) + z.s.t;
 	sl = ((-Math.cos(p*Math.PI)/2) + 0.5) * (z.p.x-z.s.l) + z.s.l;
 	z.scroll(st, sl);
 	}
 	};
 	z.scroll = function (t, l){window.scrollTo(l, t)};
 	z.timer = window.setInterval(function(){z.step();},13);
 }

 function ViewEOTable(orderTmp)
 {
 var EOValTmp= $('EOVal').value;
 var oldclass = $('EOVal').getAttribute("isclass");
 var newclass = document.getElementById('EOV'+orderTmp).className;

 if (EOValTmp>0 && oldclass!='')
 {		
	 document.getElementById('EOV'+EOValTmp).className = oldclass;
 }
 if(EOValTmp != orderTmp)
 { $('EOVal').setAttribute("isclass",newclass);
   $('EOVal').value=orderTmp;
 }
 $('EOV'+orderTmp).className='ExamOrderViewHover';
 }
 
 function clickTable(orderTmp)
 {
 var EOValTmp= $('EOVal').value;
 var oldclass = $('EOVal').getAttribute("isclass");
  if (EOValTmp>0 && oldclass!='')
 {		
	  $('EOV'+orderTmp).className = oldclass;
 }
   if(EOValTmp>0  && EOValTmp != orderTmp){
     
 	  $('EOV'+EOValTmp).className = oldclass;
   }
  $('EOV'+orderTmp).className='ExamOrderViewVisited';
  $('EOVal').setAttribute("isclass","ExamOrderViewVisited");
  $('EOVal').value=orderTmp;
 }


 var testTm=timemm;testTs=timess;
 function AutoTime()
 {   
	 if(stopautotime){return;}
	 if (testTs==-1)
	 {
	 testTm-=1;
	 testTs=59;
	 }
	 if (testTm == 3 && testTs == 0)
	 {
		if (confirm(''+nds_timeleft3m+'')) {

		}
	 } 
	 if (testTm==-1)
	 {
	 if (testTm==-1) {kssjCnt.innerHTML='<font style="color:red;font-size:25px;">'+nds_timeout+'</font>';}
	  $('postques').submit()
      return false;
	 }
	 var mm,ss,mmm;
	 mm=testTm;
	 mmm = '0'+testTm;
	 ss='0'+testTs;
	 if (ss.length==3) {ss=testTs};
	 var TimeMsg=':';
	 TimeMsg=':';
	 if(mmm.length == 4)
	 { 
	 kssjCnt.innerHTML='<font color=gray>'+nds_timeleft+'</font>&nbsp;<span style="color:red;font-size:20px;font-family:Arial;"><b id="timesysj">'+mm+TimeMsg+ss+'</b></span>';
	 }else{	
	 kssjCnt.innerHTML='<font color=gray>'+nds_timeleft+'</font>&nbsp;<span style="color:red;font-size:25px;font-family:Arial;"><b id="timesysj">'+mm+TimeMsg+ss+'</b></span>';
	 }
	 testTs-=1;
	 if(!stopautotime){
	 	setTimeout("AutoTime()",1000)
	 }
 }
 var timerTm=intval(timermm);timerTs=intval(timerss);
 function NDSTimeR()
 {
   if(stoptimer){return;} 
    if (timerTs==60)		 
	 {
	  timerTm+=1;
	  timerTs=0;
	 }
	 var mm,ss;
	 var TimeMsg=':';
	 ss=  timerTs;
	 if (timerTs < 10)  ss='0'+timerTs;
	 mm = timerTm;
	 timerid.innerHTML=' '+mm+TimeMsg+ss+'';
	 $('timerinput').value=mm+TimeMsg+ss;
	 timerTs+=1;
	  if(!stoptimer){
		setTimeout("NDSTimeR()",1000)
	  }
 }