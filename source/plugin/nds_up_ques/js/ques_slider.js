/*  nds_up_ques  v3.2
    Plugin FOR Discuz! X 
    WWW.NWDS.CN | NDS.西域数码工作室  版权保护 请勿抄袭
    Plugin update 20130816 BY singcee
*/
var isIE = (document.all) ? true : false;
var $nds = function (id) {
	return "string" == typeof id ? document.getElementById(id) : id;
};
var Class = {
	create: function() {
		return function() { this.initialize.apply(this, arguments); }
	}
}
var Extend = function(destination, source) {
	for (var property in source) {
	destination[property] = source[property];
	}
}

var Bind = function(object, fun) {
	var args = Array.prototype.slice.call(arguments).slice(2);
	return function() {
		return fun.apply(object, args);
	}
}

var BindAsEventListener = function(object, fun) {
	return function(event) {
		return fun.call(object, Event(event));
	}
}

function Event(e){
	var oEvent = isIE ? window.event : e;
	if (isIE) {
		oEvent.pageX = oEvent.clientX + document.documentElement.scrollLeft;
		oEvent.pageY = oEvent.clientY + document.documentElement.scrollTop;
		oEvent.preventDefault = function () { this.returnValue = false; };
		oEvent.detail = oEvent.wheelDelta / (-40);
		oEvent.stopPropagation = function(){ this.cancelBubble = true; }; 
	}
	return oEvent;
}

var CurrentStyle = function(element){
	return element.currentStyle || document.defaultView.getComputedStyle(element, null);
}

function addEventHandler(oTarget, sEventType, fnHandler) {
	if (oTarget.addEventListener) {
		oTarget.addEventListener(sEventType, fnHandler, false);
	} else if (oTarget.attachEvent) {
		oTarget.attachEvent("on" + sEventType, fnHandler);
	} else {
		oTarget["on" + sEventType] = fnHandler;
	}
};

function removeEventHandler(oTarget, sEventType, fnHandler) {
    if (oTarget.removeEventListener) {
        oTarget.removeEventListener(sEventType, fnHandler, false);
    } else if (oTarget.detachEvent) {
        oTarget.detachEvent("on" + sEventType, fnHandler);
    } else { 
        oTarget["on" + sEventType] = null;
    }
};


var Slider = Class.create();
Slider.prototype = {

  initialize: function(container, bar, options) {
	this.Bar = $nds(bar);
	this.Container = $nds(container);
	this._timer = null;
	this._ondrag = false;

	this._IsMin = this._IsMax = this._IsMid = false;

	this._drag = new Drag(this.Bar, { Limit: true, mxContainer: this.Container,
		onStart: Bind(this, this.DragStart), onStop: Bind(this, this.DragStop), onMove: Bind(this, this.Move)
	});
	
	this.SetOptions(options);
	
	this.WheelSpeed = Math.max(0, this.options.WheelSpeed);
	this.KeySpeed = Math.max(0, this.options.KeySpeed);
	
	this.MinValue = this.options.MinValue;
	this.MaxValue = this.options.MaxValue;
	
	this.RunTime = Math.max(1, this.options.RunTime);
	this.RunStep = Math.max(1, this.options.RunStep);
	
	this.Ease = !!this.options.Ease;
	this.EaseStep = Math.max(1, this.options.EaseStep);
	
	this.onMin = this.options.onMin;
	this.onMax = this.options.onMax;
	this.onMid = this.options.onMid;
	
	this.onDragStart = this.options.onDragStart;
	this.onDragStop = this.options.onDragStop;
	
	this.onMove = this.options.onMove;
	
	this._horizontal = !!this.options.Horizontal;
	

	this._drag[this._horizontal ? "LockY" : "LockX"] = true;
	

	addEventHandler(this.Container, "click", BindAsEventListener(this, function(e){ this._ondrag || this.ClickCtrl(e);}));

	addEventHandler(this.Bar, "click", BindAsEventListener(this, function(e){ e.stopPropagation(); }));
	
	
	this.WheelBind(this.Container);
	
	this.KeyBind(this.Container);
	
	var oFocus =  this.Container;
	addEventHandler(this.Bar, "mousedown", function(){ oFocus.focus(); });

  },

  SetOptions: function(options) {
	this.options = {
		MinValue:	0,
		MaxValue:	100,
		WheelSpeed: 5,
		KeySpeed: 	50,
		Horizontal:	true,
		RunTime:	20,
		RunStep:	2,
		Ease:		false,
		EaseStep:	5,
		onMin:		function(){},
		onMax:		function(){},
		onMid:		function(){},
		onDragStart:function(){},
		onDragStop:	function(){},
		onMove:		function(){}
	};
	Extend(this.options, options || {});
  },

  DragStart: function() {
  	this.onDragStart();
	this._ondrag = true;
  },

  DragStop: function() {
  	this.onDragStop();
	setTimeout(Bind(this, function(){ this._ondrag = false; }), 10);
  },

  Move: function() {
  	this.onMove();
	
	var percent = this.GetPercent();

	if(percent > 0){
		this._IsMin = false;
	}else{
		if(!this._IsMin){ this.onMin(); this._IsMin = true; }
	}

	if(percent < 1){
		this._IsMax = false;
	}else{
		if(!this._IsMax){ this.onMax(); this._IsMax = true; }
	}

	if(percent > 0 && percent < 1){
		if(!this._IsMid){ this.onMid(); this._IsMid = true; }
	}else{
		this._IsMid = false;
	}
  },

  ClickCtrl: function(e) {
	var o = this.Container, iLeft = o.offsetLeft, iTop = o.offsetTop;
	while (o.offsetParent) { o = o.offsetParent; iLeft += o.offsetLeft; iTop += o.offsetTop; }

	this.EasePos(e.pageX - iLeft - this.Bar.offsetWidth / 2, e.pageY - iTop - this.Bar.offsetHeight / 2);
  },

  WheelCtrl: function(e) {
	var i = this.WheelSpeed * e.detail;
	this.SetPos(this.Bar.offsetLeft + i, this.Bar.offsetTop + i);

	e.preventDefault();
  },

  WheelBind: function(o) {

	addEventHandler(o, isIE ? "mousewheel" : "DOMMouseScroll", BindAsEventListener(this, this.WheelCtrl));
  },

  KeyCtrl: function(e) {
	if(this.KeySpeed){
		var iLeft = this.Bar.offsetLeft, iWidth = (this.Container.clientWidth - this.Bar.offsetWidth) / this.KeySpeed
			, iTop = this.Bar.offsetTop, iHeight = (this.Container.clientHeight - this.Bar.offsetHeight) / this.KeySpeed;

		switch (e.keyCode) {
			case 37 :
				iLeft -= iWidth; break;
			case 38 :
				iTop -= iHeight; break;
			case 39 :
				iLeft += iWidth; break;
			case 40 :
				iTop += iHeight; break;
			default :
				return;
		}
		this.SetPos(iLeft, iTop);

		e.preventDefault();
	}
  },

  KeyBind: function(o) {
	addEventHandler(o, "keydown", BindAsEventListener(this, this.KeyCtrl));

	o.tabIndex = -1;

	isIE || (o.style.outline = "none");
  },

  GetValue: function() {

	return this.MinValue + this.GetPercent() * (this.MaxValue - this.MinValue);
  },

  SetValue: function(value) {

	this.SetPercent((value- this.MinValue)/(this.MaxValue - this.MinValue));
  },

  GetPercent: function() {

	return this._horizontal ? this.Bar.offsetLeft / (this.Container.clientWidth - this.Bar.offsetWidth)
		: this.Bar.offsetTop / (this.Container.clientHeight - this.Bar.offsetHeight)
  },

  SetPercent: function(value) {

	this.EasePos((this.Container.clientWidth - this.Bar.offsetWidth) * value, (this.Container.clientHeight - this.Bar.offsetHeight) * value);
  },

  Run: function(bIncrease) {
	this.Stop();

	bIncrease = !!bIncrease;

	var percent = this.GetPercent() + (bIncrease ? 1 : -1) * this.RunStep / 100;
	this.SetPos((this.Container.clientWidth - this.Bar.offsetWidth) * percent, (this.Container.clientHeight - this.Bar.offsetHeight) * percent);

	if(!(bIncrease ? this._IsMax : this._IsMin)){
		this._timer = setTimeout(Bind(this, this.Run, bIncrease), this.RunTime);
	}
  },

  Stop: function() {
	clearTimeout(this._timer);
  },

  EasePos: function(iLeftT, iTopT) {
	this.Stop();

	iLeftT = Math.round(iLeftT); iTopT = Math.round(iTopT);

	if(!this.Ease){ this.SetPos(iLeftT, iTopT); return; }

	var iLeftN = this.Bar.offsetLeft, iLeftS = this.GetStep(iLeftT, iLeftN)
	, iTopN = this.Bar.offsetTop, iTopS = this.GetStep(iTopT, iTopN);

	if(this._horizontal ? iLeftS : iTopS){

		this.SetPos(iLeftN + iLeftS, iTopN + iTopS);

		if(this._IsMid){ this._timer = setTimeout(Bind(this, this.EasePos, iLeftT, iTopT), this.RunTime); }
	}
  },

  GetStep: function(iTarget, iNow) {
    var iStep = (iTarget - iNow) / this.EaseStep;
    if (iStep == 0) return 0;
    if (Math.abs(iStep) < 1) return (iStep > 0 ? 1 : -1);
    return iStep;
  },

  SetPos: function(iLeft, iTop) {
	this.Stop();
	this._drag.SetPos(iLeft, iTop);
  }
};


var Drag = Class.create();
Drag.prototype = {

  initialize: function(drag, options) {
	this.Drag = $nds(drag);
	this._x = this._y = 0;
	this._marginLeft = this._marginTop = 0;

	this._fM = BindAsEventListener(this, this.Move);
	this._fS = Bind(this, this.Stop);
	
	this.SetOptions(options);
	
	this.Limit = !!this.options.Limit;
	this.mxLeft = parseInt(this.options.mxLeft);
	this.mxRight = parseInt(this.options.mxRight);
	this.mxTop = parseInt(this.options.mxTop);
	this.mxBottom = parseInt(this.options.mxBottom);
	
	this.LockX = !!this.options.LockX;
	this.LockY = !!this.options.LockY;
	this.Lock = !!this.options.Lock;
	
	this.onStart = this.options.onStart;
	this.onMove = this.options.onMove;
	this.onStop = this.options.onStop;
	
	this._Handle = $nds(this.options.Handle) || this.Drag;
	this._mxContainer = $nds(this.options.mxContainer) || null;
	
	this.Drag.style.position = "absolute";

	if(isIE && !!this.options.Transparent){

		with(this._Handle.appendChild(document.createElement("div")).style){
			width = height = "100%"; backgroundColor = "#fff"; filter = "alpha(opacity:0)"; fontSize = 0;
		}
	}

	this.Repair();
	addEventHandler(this._Handle, "mousedown", BindAsEventListener(this, this.Start));
  },

  SetOptions: function(options) {
	this.options = {
		Handle:			"",
		Limit:			false,
		mxLeft:			0,
		mxRight:		9999,
		mxTop:			0,
		mxBottom:		9999,
		mxContainer:	"",
		LockX:			false,
		LockY:			false,
		Lock:			false,
		Transparent:	false,
		onStart:		function(){},
		onMove:			function(){},
		onStop:			function(){}
	};
	Extend(this.options, options || {});
  },

  Start: function(oEvent) {
	if(this.Lock){ return; }
	this.Repair();

	this._x = oEvent.clientX - this.Drag.offsetLeft;
	this._y = oEvent.clientY - this.Drag.offsetTop;

	this._marginLeft = parseInt(CurrentStyle(this.Drag).marginLeft) || 0;
	this._marginTop = parseInt(CurrentStyle(this.Drag).marginTop) || 0;

	addEventHandler(document, "mousemove", this._fM);
	addEventHandler(document, "mouseup", this._fS);
	if(isIE){

		addEventHandler(this._Handle, "losecapture", this._fS);

		this._Handle.setCapture();
	}else{

		addEventHandler(window, "blur", this._fS);

		oEvent.preventDefault();
	};

	this.onStart();
  },

  Repair: function() {
	if(this.Limit){

		this.mxRight = Math.max(this.mxRight, this.mxLeft + this.Drag.offsetWidth);
		this.mxBottom = Math.max(this.mxBottom, this.mxTop + this.Drag.offsetHeight);

		!this._mxContainer || CurrentStyle(this._mxContainer).position == "relative" || CurrentStyle(this._mxContainer).position == "absolute" || (this._mxContainer.style.position = "relative");
	}
  },

  Move: function(oEvent) {

	if(this.Lock){ this.Stop(); return; };

	window.getSelection ? window.getSelection().removeAllRanges() : document.selection.empty();

	this.SetPos(oEvent.clientX - this._x, oEvent.clientY - this._y);
  },

  SetPos: function(iLeft, iTop) {

	if(this.Limit){

		var mxLeft = this.mxLeft, mxRight = this.mxRight, mxTop = this.mxTop, mxBottom = this.mxBottom;

		if(!!this._mxContainer){
			mxLeft = Math.max(mxLeft, 0);
			mxTop = Math.max(mxTop, 0);
			mxRight = Math.min(mxRight, this._mxContainer.clientWidth);
			mxBottom = Math.min(mxBottom, this._mxContainer.clientHeight);
		};

		iLeft = Math.max(Math.min(iLeft, mxRight - this.Drag.offsetWidth), mxLeft);
		iTop = Math.max(Math.min(iTop, mxBottom - this.Drag.offsetHeight), mxTop);
	}

	if(!this.LockX){ this.Drag.style.left = iLeft - this._marginLeft + "px"; }
	if(!this.LockY){ this.Drag.style.top = iTop - this._marginTop + "px"; }

	this.onMove();
  },

  Stop: function() {

	removeEventHandler(document, "mousemove", this._fM);
	removeEventHandler(document, "mouseup", this._fS);
	if(isIE){
		removeEventHandler(this._Handle, "losecapture", this._fS);
		this._Handle.releaseCapture();
	}else{
		removeEventHandler(window, "blur", this._fS);
	};

	this.onStop();
  }
};
      function Showstar(e,chmax)
        {
		  var imax = parseInt(chmax);  
            document.write("<input type=\"hidden\" id=\"suboption["+e+"]\" name=\"suboption["+e+"]\" value=\"1\">");
            for(var i=0;i<imax;i++)
            {
                document.write('<img id="' + e + i + '" width="25" height="27" alt="' + (i + 1) + '" src="source/plugin/nds_up_ques/images/star01.jpg" style="cursor:pointer" onmousemove="ChangeImg(\'' + e + '\',' + (i + 1) + ','+imax+')" onmouseout="InitImg(\'' + e + '\','+imax+')" onclick="SetScore(\'' + e + '\',' + (i + 1) + ','+imax+')" />');
            }
            InitImg(e);
        }
        function ChangeImg(e,x,imax)
        {
                for(var i=0;i<imax;i++)
                {
                    if (i<x)
                        document.getElementById(e + i).src = "source/plugin/nds_up_ques/images/star02.jpg";
                    else
                        document.getElementById(e + i).src = "source/plugin/nds_up_ques/images/star01.jpg";
                }
        }
        function InitImg(e,imax)
        {
            var x = parseInt(document.getElementById("suboption["+e+"]").value);
			
            for (var i=0;i<imax;i++)
            {
                  var s = i.toString();
                if (i<x){
                  
                    document.getElementById(e + s).src = "source/plugin/nds_up_ques/images/star02.jpg";
                }else{

					document.getElementById(e + s).src = "source/plugin/nds_up_ques/images/star01.jpg";
                     }
			}    
        }
        function SetScore(e,x,imax)
        {
            document.getElementById("suboption["+e+"]").value=x;
            for (var i=0;i<imax;i++)
            {
                if (i<x)
                    document.getElementById(e + i).src = "source/plugin/nds_up_ques/images/star02.jpg";
                else
                    document.getElementById(e + i).src = "source/plugin/nds_up_ques/images/star01.jpg";
            }
        }