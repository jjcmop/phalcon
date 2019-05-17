<?php
if (!function_exists('p')) {
    function p($data) {
        if(is_bool($data) || is_null($data)){
            var_dump($data);
        }

        if(is_array($data) || is_object($data) || is_resource($data)){
            echo "<pre>";
            print_r($data);
            echo "</pre>";
        }

        if(is_int($data) || is_string($data) || is_float($data)){
            echo $data;
        }

        exit;
    }
}

if(!function_exists('showPage')){
    function showPage($page,$url){
        $start = ($page->current>2)?($page->current-2):1;
        $end = ($page->current+1)>$page->total_pages?($page->total_pages-1):$page->current+1;
        $strpage = "";
        $strpage .= "<div id='pages'>";
        $strpage .= \Phalcon\Tag::linkTo($url."?page=".$page->first, "首页");
        if($page->current == $page->first){
            $strpage .= \Phalcon\Tag::linkTo([$url.'?page='.$page->before, '<i class="layui-icon"></i>','class'=>'layui-disabled']);
        }else{
            $strpage .= \Phalcon\Tag::linkTo($url.'?page='.$page->before, '<i class="layui-icon"></i>');
        }
        for ($i=$start;$i<=$end;$i++){
            if($i == $page->current){
                $strpage .= \Phalcon\Tag::linkTo([$url.'?page='.$i, $i,'class' => 'active']);
            }else{
                $strpage .= \Phalcon\Tag::linkTo($url.'?page='.$i, $i);
            }
        }
        if($page->current == $page->total_pages){
            $strpage .= \Phalcon\Tag::linkTo([$url.'?page='.$page->next, '<i class="layui-icon"></i>','class'=>'layui-disabled']);
        }else{
            $strpage .= \Phalcon\Tag::linkTo($url.'?page='.$page->next, '<i class="layui-icon"></i>');
        }
        $strpage .= \Phalcon\Tag::linkTo($url.'?page='.$page->last, '末页');
        $strpage .= "共".$page->total_items."条总共".$page->current." / ".$page->total_pages;
        $strpage .= "</div>";
        return $strpage;
    }
}

if (!function_exists('alertText')){
    function alertText($data,$url) {
        echo "<script>
    var divNode = document.createElement('div');
    divNode.setAttribute('id','msg');
    divNode.style.position = 'fixed';
    divNode.style.top = '50%';
    divNode.style.width = '400px';
    divNode.style.left = '50%';
    divNode.style.marginLeft = '-220px';
    divNode.style.height = '30px';
    divNode.style.lineHeight = '30px';
    divNode.style.marginTop = '-35px';
    var pNode = document.createElement('p');
    pNode.style.background = 'rgba(0,0,0,0.6)';
    pNode.style.width = '300px';
    pNode.style.color = '#fff';
    pNode.style.textAlign = 'center';
    pNode.style.padding = '20px';
    pNode.style.margin = '0 auto';
    pNode.style.fontSize = '16px';
    pNode.style.borderRadius = '4px';
    pNode.innerText = '".$data."';
    divNode.appendChild(pNode);
    var htmlNode = document.documentElement;
    htmlNode.style.background = 'rgba(0,0,0,0)';
    htmlNode.appendChild(divNode);
    var t = setTimeout(next,2000);
    function next(){
        htmlNode.removeChild(divNode);
        window.location.href='".$url."';
    }
    </script>";
    }
}
if (!function_exists('success')) {
    function success($msg,$url){
        echo "<script>alert('".$msg."');window.location.href='".$url."';</script>";
    }
}

if (!function_exists('error')) {
    function error($msg){
        echo "<script>alert('".$msg."');window.history.back();</script>";
    }
}
if (!function_exists('statusUrl')) {
    function statusUrl($bool,string $success_msg, string $success_url,string $error_msg){
        if($bool){
            success($success_msg,$success_url);
        }else{
            error($error_msg);
        }
    }
}
if (!function_exists('server')) {
    function server($data = null){
        if(is_null($data)){
            return $_SERVER;
        }else{
            $key = strtoupper($data);
            return $_SERVER[$key];
        }
    }
}
if (!function_exists('request')) {
    function request($data = null){
        if(is_null($data)){
            return $_REQUEST;
        }else{
            return $_REQUEST[$data];
        }
    }
}
if (!function_exists('post')) {
    function post($data = null){
        if(is_null($data)){
            return $_POST;
        }else{
            return $_POST[$data];
        }
    }
}
if (!function_exists('get')) {
    function get($data = null){
        if(is_null($data)){
            return $_GET;
        }else{
            return $_GET[$data];
        }
    }
}

if (!function_exists('files')) {
    function files($data = null){
        if(is_null($data)){
            return $_FILES;
        }else{
            return $_FILES[$data];
        }
    }
}

if (!function_exists('load_view')) {
    function load_view($filename=null){
        include_once APP_PATH."/views/{$filename}.phtml";
    }
}
//用户经验
if (!function_exists("user_bp")){
   function user_bp($user,$bp,$optime,$bp_type,$jf){
       $ptime = date("Y-m-d",$optime); //时间戳转为日期
       $zs_user_bp = new Zs_user_bp();
       $zs_user = new Zs_user();
       $result = $zs_user_bp::findFirst(" user = {$user} and optime = '{$ptime}' ");
       $user_result = $zs_user::findFirst(" id = {$user} ");
       if(!empty($user_result)){
           if(empty($result)){
               //用户积分变动
               $user_result->user_exp = (int)$user_result->user_exp + $jf;
               $user_result->user_level = switch_user_level($user_result->user_exp);
               $user_result->save();
               //用户积分日志
               $zs_user_bp->user = $user;
               $zs_user_bp->bp = $bp;
               $zs_user_bp->optime = $ptime;
               $zs_user_bp->bp_type = $bp_type;
               $zs_user_bp->save();
           }
       }else{
           echo json_encode(['code'=>1004,'message'=>"手机号不存在，请注册"]);exit;
       }
   }
}
//用户等级
if (!function_exists('switch_user_level')) {
    function switch_user_level($level){
        $user_level = 1;
        switch(true){
            case $level>=0 && $level<50: $user_level = 1; break;
            case $level>=50 && $level<130: $user_level = 2; break;
            case $level>=130 && $level<290: $user_level = 3; break;
            case $level>=290 && $level<610: $user_level = 4; break;
            case $level>=610 && $level<1250: $user_level = 5; break;
            case $level>=1250 && $level<2530: $user_level = 6; break;
            case $level>=2530 && $level<5090: $user_level = 7; break;
            case $level>=5090 && $level<10210: $user_level = 8; break;
            case $level>=10210 && $level<20450: $user_level = 9; break;
            case $level>=20450 : $user_level = 10; break;
            default:$user_level = 1; break;
        }
        return $user_level;
    }
}

if(!function_exists('get_zs_type_name')){
    function get_zs_type_name($data){
       $zs_card_type = new Zs_card_type();
       $arrdata = $zs_card_type::findFirstById($data)->toArray();
       return $arrdata['title'];
    }
}

if(!function_exists('get_zs_user_name')){
    function get_zs_user_name($data){
       $zs_user = new Zs_user();
       $arrdata = $zs_user::findFirstById($data)->toArray();
       return $arrdata['account'];
    }
}

if(!function_exists('get_zs_news_type_name')){
    function get_zs_news_type_name($data){
        $str = "";
        switch ($data){
            case 0:$str = "首页N格";break;
            case 1:$str = "首页单格";break;
            case 2:$str = "首页视频";break;
        }
        return $str;
    }
}

if(!function_exists('get_zs_merc_name')){
    function get_zs_merc_name($data){
        $str = "";
        switch ($data){
            case "commercial":$str = "经营性";break;
            case "sex":$str = "性别";break;
            case "mobile_num":$str = "手机号";break;
            case "real_name":$str = "姓名";break;
            case "id_card":$str = "身份证号";break;
            case "address":$str = "所在地";break;
            case "info":$str = "说明";break;
            case "idcard":$str = "身份证正面";break;
            case "reidcard":$str = "身份证反面";break;
            case "handidcard":$str = "手持身份证";break;
            case "permit":$str = "营业执照";break;
            case "field1":$str = "场地照1";break;
            case "field2":$str = "场地照2";break;
            case "field3":$str = "场地照3";break;
            case "field4":$str = "场地照4";break;
        }
        return $str;
    }
}

if(!function_exists('get_zs_user_merc_status')){
    function get_zs_user_merc_status($id,$data){
        $zs_user_merc = new Zs_user_merc();
        $result = $zs_user_merc::findFirst(['columns'=>[$data],'conditions'=>'id = '.$id]);
        return $result->$data;
    }
}






