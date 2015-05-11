<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{subtemplate common/header}-->
<!--{eval $list = array();}-->
<!--{eval $wheresql = category_get_wheresql($cat);}-->
<!--{eval $list = category_get_list($cat, $wheresql, $page);}-->
<link rel="stylesheet" href="$_G['style']['tpldir']/touch/images/dsm_pd.css" type="text/css">
<div class="wps cl">
<div class="pt"><a href="forum.php">论坛</a> <em> &gt; </em> <!--{loop $cat[ups] $value}--><a href="{echo getportalcategoryurl($value[catid])}">$value[catname]</a> <em> &gt; </em> <!--{/loop}-->$cat[catname]</div>

   <!--{if $cat[subs] || $cat[others]}-->
   <div class="dsm_fengexian">
   <ul class="ttp bm cl">
   <!--{if $cat[subs]}-->
   <a href="javascript:;" id="cats" class="a" onclick="dbox('cats','cats');">{lang sub_category}</a>
   <!--{/if}-->   
   <!--{if $cat[others]}-->  
   <a href="javascript:;" id="cato" class="a" onclick="dbox('cato','cato');">{lang category_related}</a>
   <!--{/if}-->
   <!--{if !$cat[subs] && $cat[others]}--><a href="javascript:history.back();" class="rb">返回</a><!--{/if}-->
   </ul>
   
   
  
   </div>
   <!--{/if}--> 

 <!--{if $cat[subs]}-->
			<ul id="cats_menu" class="ccat ttp bm cl" style="display:none;">			
				<!--{loop $cat[subs] $value}-->
                <li><a href="{echo getportalcategoryurl($value[catid]);}">$value[catname]</a></li>
				<!--{/loop}-->
			</ul>
			<!--{/if}-->
            
    <!--{if $cat[others]}-->
            <ul id="cato_menu" class="ccat ttp bm cl" style="display:none;">
				<!--{loop $cat[others] $value}-->
				<li><a href="{echo getportalcategoryurl($value[catid]);}">$value[catname]</a></li>
				<!--{/loop}-->
            </ul>
     <!--{/if}-->   
            
            
			<div id="alist">
			<!--{eval $i=1}-->
            <!--{loop $list['list'] $value}-->

                <div class="clt">
					
                    <h1><a href="portal.php?mod=view&aid=$value[aid]">$value[title]</a> <!--{if $value[status] == 1}--><span>({lang moderate_need})</span><!--{/if}--></h1>                    
					<p class="mess">
					<!--{if $value[pic]}--><a href="portal.php?mod=view&aid=$value[aid]"><img src="$value[pic]" alt="$value[title]" /></a><!--{/if}-->
					$value[summary]
					</p>                    
					<div class="sp xg1">                 
					<a href="home.php?mod=space&uid=$value[uid]" >$value[username]</a><span class="pipe">-</span>$value[dateline]<!--{if $value[catname] && $cat[subs]}--><span class="pipe">-</span><a href="{echo getportalcategoryurl($value[catid]);}">$value[catname]</a><!--{/if}--><!--{if $_G['group']['allowmanagearticle'] || ($_G['group']['allowpostarticle'] && $value['uid'] == $_G['uid'] && (empty($_G['group']['allowpostarticlemod']) || $_G['group']['allowpostarticlemod'] && $value['status'] == 1)) || $categoryperm[$value['catid']]['allowmanage']}--><span class="pipe">-</span><a href="portal.php?mod=portalcp&ac=article&op=delete&aid=$value[aid]">{lang delete}</a><!--{/if}-->
					</div>                    
                    
                    </div>
				
			<!--{/loop}-->
            </div>

<!--{if $list['multi']}-->           
        

<div class="pgbox">{$list['multi']}</div> 

<!--{/if}--> 

</div>

<!--{subtemplate common/footer}-->