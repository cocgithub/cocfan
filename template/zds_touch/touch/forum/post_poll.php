<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<div class="pd2">
    <input type="hidden" name="polls" value="yes" />
    <input type="hidden" name="fid" value="$_G[fid]" />

    <!--{if $_GET[action] == 'newthread'}-->
        <input type="hidden" name="tpolloption" value="2" />
        <p>{lang post_poll_options}:{lang post_poll_comment}</p>
        <li><textarea name="polloptions" class="txt"  rows="2" /></textarea></li>
        <p class="xi1">{lang post_poll_comment_s}</p>
    <!--{else}-->
        <!--{loop $poll['polloption'] $key $option}-->
            <p>
                <input type="hidden" name="polloptionid[{$poll[polloptionid][$key]}]" value="$poll[polloptionid][$key]" />
                <input type="text" name="displayorder[{$poll[polloptionid][$key]}]" class="txt_s" autocomplete="off"  value="$poll[displayorder][$key]" />
                <input type="text" name="polloption[{$poll[polloptionid][$key]}]" class="txt_s" autocomplete="off" style="width:290px;"  value="$option"{if !$_G['group']['alloweditpoll']} readonly="readonly"{/if} />
            </p>
        <!--{/loop}-->
    <!--{/if}-->


    <div >
        <p class="ptn">
            <label for="maxchoices">{lang post_poll_allowmultiple}</label>
            <input type="text" name="maxchoices" id="maxchoices" class="txt_s" value="{if $_GET[action] == 'edit' && $poll[maxchoices]}$poll[maxchoices]{else}1{/if}"  /> {lang post_option}
        </p>
        <p class="ptn">
            <label for="polldatas">{lang post_poll_expiration}</label>
            <input type="text" name="expiration" id="polldatas" class="txt_s" value="{if $_GET[action] == 'edit'}{if !$poll[expiration]}0{elseif $poll[expiration] < 0}{lang poll_close}{elseif $poll[expiration] < TIMESTAMP}{lang poll_finish}{else}{echo (round(($poll[expiration] - TIMESTAMP) / 86400))}{/if}{/if}"  /> {lang days}
        </p>
        <p class="ptn">
            <input type="checkbox" name="visibilitypoll" id="visibilitypoll" class="pc" value="1"{if $_GET[action] == 'edit' && !$poll[visible]} checked{/if}  />
            <label for="visibilitypoll">{lang poll_after_result}</label>
        </p>
        <p class="ptn">
            <input type="checkbox" name="overt" id="overt" class="pc" value="1"{if $_GET[action] == 'edit' && $poll[overt]} checked{/if}  />
            <label for="overt">{lang post_poll_overt}</label>
        </p>
    </div>
</div>