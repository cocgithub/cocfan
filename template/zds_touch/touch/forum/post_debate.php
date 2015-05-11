<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<div class="dsm_postbian">
<div class="inbox">
    <p><label for="affirmpoint">{lang debate_square_point}<strong class="xi1">*</strong></label><li><textarea name="affirmpoint" id="affirmpoint" class="txt"  rows="1" >$debate[affirmpoint]</textarea></li></p>

    <p><label for="negapoint">{lang debate_opponent_point}<strong class="xi1">*</strong></label><li><textarea name="negapoint" id="negapoint" class="txt"  rows="1">$debate[negapoint]</textarea></li></p>
</div>
<div class="inbox pbn">
    <p><label for="endtime">{lang endtime} <font class="xg1">({lang threadsort_calendar})</font></label></dt>
    <input type="text" name="endtime" id="endtime" class="txt" autocomplete="off" value="$debate[endtime]"  /></p>
    <p><label for="umpire">{lang debate_umpire}:</label>
    <input type="text" name="umpire" id="umpire" class="txt" value="$debate[umpire]"  /></p>
</div>
</div>
