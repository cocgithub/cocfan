<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$plugin_information=lang('plugin/liangjian','plugin_information');
$info_1=lang('plugin/liangjian','info_1');
$info_2=lang('plugin/liangjian','info_2');
$info_3=lang('plugin/liangjian','info_3');
$info_4=lang('plugin/liangjian','info_4');
$info_5=lang('plugin/liangjian','info_5');
echo "<table class=\"tb tb2 \">
  <tbody>
    <tr>
      <th class=\"partition\">$plugin_information</th>
    </tr>
    <tr>
      <td>
      [*]$info_1
      </td>
    </tr>
    <tr>
      <td>
      [*]$info_2
      </td>
    </tr>
    <tr>
      <td>
      [*]$info_3
      </td>
    </tr>
    <tr>
      <td>
      [*]$info_4
      </td>
    </tr>
    <tr>
      <td>
      [*]<b>$info_5</b>
      </td>
    </tr>
  </tbody>
</table>";
?>