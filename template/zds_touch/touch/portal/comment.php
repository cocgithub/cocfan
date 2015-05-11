<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{subtemplate common/header}-->
<style>
.titls {
padding: 10px 10px 8px;
background-color: #f4f4f4;
line-height: 15px;
font-size: 15px;
}
.celi {
padding: 0px 10px 8px 48px;
min-height: 70px;
position: relative;
border-top: 1px solid #fff;
border-bottom: 1px solid #e5e5e5;
}
.celi .avatar img {
width: 28px;
height: 28px;
position: absolute;
left: 10px;
top: 10px;
}
.celi .user {
padding: 8px 0px;
}
.user .p_dl {
color: #bbb;
}
.celi .mess {
padding-bottom: 3px;
line-height: 20px;
color: #555;
}
.celi .vtrim {
margin-top: 5px;
height: 22px;
overflow: hidden;
}
.vtrim a {
float: right;
margin-left: 5px;
padding: 5px 7px;
color: #fff;
background: #ccc;
}

.ipc {
font-size: 15px;
padding: 10px 10px 15px;
}
.ipcc {
min-height: 100px;
padding: 5px;
margin-bottom: 12px;
background: #fff;
border: 2px solid #eaeaea;
border-radius: 5px;
}
.ipcc textarea {
float: left;
height: 100px;
width: 100%;
font-size: 15px;
color: #777;
border: none;
background: none;
}
.inbox {
padding: 0;
overflow: hidden;
}
.ibt {
float: left;
height: 36px;
line-height: 36px;
font-size: 18px;
padding: 0 32px;
margin: 0 10px 0 0;
color: #fff;
background: #f60;
border: none;
color: #fff;
text-shadow: 0 0 1px #930;
width: 100%;
padding: 0;
text-align: center;
}
</style>
<div class="ct">
<div class="titls">
<a href="$url" class="xi2">&laquo;正文</a>
<span class="y">共有$csubject[commentnum]条{lang comment}</span>
</div>

            <div id="alist">
			<!--{loop $commentlist $comment}-->
				<!--{subtemplate portal/comment_li}-->
			<!--{/loop}-->
			</div>
<!--{if $pagestyle == 1}-->                        
<!--{if $csubject['commentnum'] > $perpage}-->  
<!--{eval $nextpage = $page + 1; }-->  
<div id="ajaxshow"></div>        
<script type="text/javascript">
var page=$_G['page'];
var allpage={echo $thispage = ceil($csubject['commentnum'] / $perpage);};
function ajaxpage(url){
						$("ajaxld").style.display='block';
						$("ajnt").style.display='none';
						var x = new Ajax("HTML");
						page++;
						url=url+'&page='+page;
						x.get(url, function (s) {
							s = s.replace(/\\n|\\r/g, "");//alert(s);
							s = s.substring(s.indexOf("<div id=\"alist\""), s.indexOf("<div id=\"ajaxshow\"></div>"));//alert(s);
							$('ajaxshow').innerHTML+=s;
							$("ajaxld").style.display='none';
						if(page==allpage){
							$("ajnt").style.display='none';
						}else{
							$("ajnt").style.display='block';
						}							
						});

						return false;
}
</script>
<div class="a_pg" id="a_pg"> 
<div id="ajaxld"><img src="./mplus/img/load.gif" /> {echo m_lang('load_pic')}</div>
<div id="ajnt"><a href="portal.php?mod=comment&id=$_GET['id']&idtype=aid&page=$nextpage" onclick="return ajaxpage(this.href);">{echo m_lang('load')}</a></div>
</div>
<!--{/if}-->
<!--{else}-->
<div class="pgbox">$multi</div>
<!--{/if}-->            
      </div>
      
            <!--{if $csubject['allowcomment'] == 1}-->            
             <div class="ct ctpd">         
            <div class="ipc">            
				<form name="cform" action="portal.php?mod=portalcp&ac=comment" method="post" autocomplete="off">
					<div class="ipcc mtn">
					<textarea name="message" cols="60" rows="3" id="message" ></textarea>
					</div>
					<!--{if checkperm('seccode') && ($secqaacheck || $seccodecheck)}-->
						<!--{subtemplate common/seccheck}-->
					<!--{/if}-->

					<!--{if $idtype == 'topicid' }-->
						<input type="hidden" name="topicid" value="$id">
					<!--{else}-->
						<input type="hidden" name="aid" value="$id">
					<!--{/if}-->
					<input type="hidden" name="formhash" value="{FORMHASH}">
					<div class="inbox"><button type="submit" name="commentsubmit" value="true" class="ibt ibtp">{lang comment}</button></div>
				</form>                
                </div>
                </div>
			<!--{/if}-->

<!--{subtemplate common/footer}-->