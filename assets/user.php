<?php
//  if(!isset($_POST["submit"])){
//   echo "[{\"result\":\"???\"}]";
//  }//检测是否有submit操作 
 

header("Content-type:application/json");
// 账号验证

$userName = $_POST["userName"];//获取表单POST过来的用户名
$userPwd = $_POST["userPwd"];//获取表单POST过来的密码
$email = $_POST["qq"];//获取表单POST过来的密码
// $code = $_POST["code"];



//表单过滤

$userName = trim($userName);//过滤空格
$userPwd = trim($userPwd);//过滤空格
$email = trim($email);//过滤空格
//开始判断

if($userName == "" && $userPwd == "" ){
    echo "[{\"result\":\"啥都还没填呢！\"}]";
}else if($userName == "" && $userPwd == ""){
    echo "[{\"result\":\"账号和密码不能为空\"}]";
}else if ($userName == "") {
    echo "[{\"result\":\"账号不能为空\"}]";
}else if ($userPwd == "") {
    echo "[{\"result\":\"密码不能为空\"}]";
}else if ($email == "") {
    echo "[{\"result\":\"QQ不能为空\"}]";
}else if (strlen($email) < 7) {
    echo "[{\"result\":\"QQ不能小于7位\"}]";
}else if (strlen($userName) < 5){
    echo "[{\"result\":\"账号不能小于5位\"}]";
}else if (strlen($userPwd) < 6){
    echo "[{\"result\":\"密码不能小于6位\"}]";
}else if (preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $userName)>0){
    echo "[{\"result\":\"账号不能为中文\"}]";
}else if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $userName)>0){
    echo "[{\"result\":\"账号不能存在中文\"}]";
}else{

//      session_start();
//  if ($_SESSION['code']==null) {
//   echo "[{\"result\":\"非法访问，请访问https://github.com/CnHack3r/icve_auto_sign"}]";
// } else {
    

//  if ($_POST['code'] == $_SESSION['code']) {

    // 数据库连接
 $conn = mysqli_connect('127.0.0.1','库名','密码','库名称');
      $sql_select = "SELECT username FROM User WHERE username = '$userName'"; //执行SQL语句
      $ret = mysqli_query($conn, $sql_select);
      $row = mysqli_fetch_array($ret); //判断用户名是否已存在
      if ($userName == $row['username']) { //用户名已存在，显示提示信息
          echo "[{\"result\":\"账号已经在服务器挂机了，请勿重复添加\"}]";
    }else{
        date_default_timezone_set("PRC");
/*
**跟随更新协议头
*/
$emit=time()."000";
//echo date('Y-m-d H:i:s');
$equipmentModel="Xiaomi Redmi K20 Pro";
$equipmentApiVersion="10";
$equipmentAppVersion=getver();
$device=getDevice($equipmentModel,$equipmentApiVersion,$equipmentAppVersion,$emit);
//header        
$headers = array('Content-Type:'.'application/x-www-form-urlencoded','emit:'.$emit,'device:'.$device);       
$date = new DateTime();
$date =date("Y-m-d H:i:s" ,strtotime( $srcDataStr ));
//============================================
//login       开始模拟登陆         
$url="https://zjyapp.icve.com.cn/newMobileAPI/MobileLogin/newSignIn";
$data=array("clientId"=>"d902c875d5f34c0f93362139f5af0c4c","sourceType"=>"2","userPwd"=>$userPwd,"userName"=>$userName,"appVersion"=>$equipmentAppVersion,"equipmentAppVersion"=>$equipmentAppVersion,"equipmentApiVersion"=>$equipmentApiVersion,"equipmentModel"=>$equipmentModel);
//print_r($data);
$output=httppost($url,$headers,$data);
//print_r($output);;

        
    if($output['code'] == "-1"){
 echo "[{\"result\":\"账号或密码错误，请重新输入\"}]";
}
if($output['code'] == "1"){
   
              $sql_insert = "INSERT INTO User(username,password,qq,state,number,date) 
  VALUES('$userName','$userPwd','$email','1',0,now())"; //执行SQL语句')
          mysqli_query($conn, $sql_insert);
         echo "[{\"result\":\"账号添加成功\"}]";
         //echo "[{\"result\":\"账号成功录入数据库,如需激活自动签到联系站长支付5元一学期\"}]";
    }
 }}
//   }
//  }





  unset($_SESSION['code']);



/*
**核心函数 请勿更改
*/

function httppost($url,$headers,$data){
                $curl=curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_HEADER, 0); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
                
                curl_setopt($curl, CURLOPT_POST, 1);
                        
        curl_setopt($curl, CURLOPT_POSTFIELDS,http_build_query($data));
                
        $output = curl_exec($curl);
                
        curl_close($curl);
                $output=json_decode($output,true);
        return $output;
                //print_r($output);
} 

function curl_get($url,$headers,$data,$cookie){
                $curl=curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
                curl_setopt($curl,CURLOPT_COOKIE,$cookie);
                //curl_setopt($curl, CURLOPT_POST, 1);
                
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                
        $output = curl_exec($curl);
                
        curl_close($curl);
                $output=json_decode($output,true);
        return $output;
                //print_r($output);
}

function curl_post($url,$headers,$data,$cookie){
                $curl=curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
                curl_setopt($curl,CURLOPT_COOKIE,$cookie);
                curl_setopt($curl, CURLOPT_POST, 1);
                
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                
        $output = curl_exec($curl);
                
        curl_close($curl);
                $output=json_decode($output,true);
        return $output;
                //print_r($output);
}






function post_curl($url, $params=[], $headers=[]){   
    $httpInfo = array();   
    $ch = curl_init();      
    curl_setopt($ch, CURLOPT_HEADER, 1);   
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );   
    curl_setopt( $ch, CURLOPT_TIMEOUT , 30);   
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );      
    curl_setopt( $ch , CURLOPT_POST , true );   
    curl_setopt( $ch , CURLOPT_POSTFIELDS , http_build_query($params));   
    curl_setopt( $ch , CURLOPT_URL , $url );        
    $response = curl_exec( $ch );   
    if ($response === FALSE) {      
        return false;   
        
    }        
    curl_close( $ch );    
    return $response;
    
}
//
function object_array($array) {  
    if(is_object($array)) {  
        $array = (array)$array;  
     } if(is_array($array)) {  
         foreach($array as $key=>$value) {  
             $array[$key] = object_array($value);  
             }  
     }  
     return $array;  
}

function getDevice($model,$vsersionAndroid,$versionName,$timeStamp){
    $tmp=md5($model);
    //echo $tmp."<br>";
    $tmp1=$tmp.$vsersionAndroid;
    //echo $tmp."<br>";
    $tmp=md5($tmp1);
    //echo $tmp."<br>";
    $tmp1=$tmp.$versionName;
    //echo $tmp."<br>";
    $tmp=md5($tmp1);
    //echo $tmp."<br>";
    $tmp1=$tmp.$timeStamp;
    //echo $tmp."<br>";
    return md5($tmp1);
}

function getver(){
    $url="https://zjy2.icve.com.cn/portal/AppVersion/getLatestVersionInfo";
    $output = file_get_contents($url);
    $version = json_decode($output,true)['appVersionInfo']['VersionCode'];
    return $version;
}


?>