<?php
function dxksst_wcache($filename,$array){
$file="source/plugin/dxksst_n/cache/".$filename.".dxksst"; 
file_put_contents($file,serialize($array));//写入缓存 
}//读出缓存 
function dxksst_rcache($filename){
$file="source/plugin/dxksst_n/cache/".$filename.".dxksst"; 
$handle = fopen($file, "r"); 
$cacheArray = unserialize(fread($handle, filesize ($file)));
return $cacheArray;
}
?>