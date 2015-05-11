/*!
 *  Item: 996手机游戏
 *  Copyright:  Teiron Network Technology Co.,Ltd
 *  Author: Joel(2013.12.21)
 *  Update: 
 */
(function(){$(function(){a()});function a(){var d=$("body").width(),b=document.getElementById("banner"),f=document.getElementById("bpic"),e=$(".tempWrap"),g=f.getElementsByTagName("img");b.style.width=f.style.width=e[0].style.width=d+"px";b.style.height=f.style.height=(Math.ceil(d/2.3))+"px";for(var c=0;c<g.length;c++){g[c].style.width=d+"px";g[c].style.height=(Math.ceil(d/2.3))+"px"}TouchSlide({slideCell:"#banner",titCell:"#indexBar li",mainCell:"#bpic",effect:"left",autoPlay:true,delayTime:300,titOnClassName:"current"})}})();