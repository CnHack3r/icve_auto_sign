<?php
header('content-type:text/html;charset=UTF-8');
date_default_timezone_set("PRC");

 $userPwd=$_POST['userPwd'];//密码
 
 $qq=$_POST['qq'];//账号
$userName=$_POST['userName'];//账号


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




/*
**自动签到部分
*/
//login       开始模拟登陆         
$url="https://zjyapp.icve.com.cn/newMobileAPI/MobileLogin/newSignIn";
$data=array("clientId"=>"d902c875d5f34c0f93362139f5af0c4c","sourceType"=>"2","userPwd"=>$userPwd,"userName"=>$userName,"appVersion"=>$equipmentAppVersion,"equipmentAppVersion"=>$equipmentAppVersion,"equipmentApiVersion"=>$equipmentApiVersion,"equipmentModel"=>$equipmentModel);
//print_r($data);
$output=httppost($url,$headers,$data);
print_r($output["schoolName"]);
print_r($output["displayName"]);
$schoolName=$output["schoolName"];
if($output['code'] == "1"){
    $stuId = $output["userId"];
    $newtoken=$output['newToken'];
        $faceDate = date("Y-m-d");
        //echo $stuId;
        
//get jrkt        
$url2="https://zjyapp.icve.com.cn/newmobileapi/faceteach/getStuFaceTeachList";
$data=array("stuId"=>$stuId,"faceDate"=>$faceDate,"newToken"=>$newtoken);
$output=httppost($url2,$headers,$data);
        $todayClassInfo=$output["dataList"];
        
        
        
$url3="https://zjyapp.icve.com.cn/newmobileapi/faceteach/newGetStuFaceActivityList";
if(!empty($todayClassInfo)){
                foreach($todayClassInfo as $i){
                        $data=array("activityId"=>$i['Id'],"stuId"=>$stuId,"classState"=>$i['state'],"openClassId"=>$i['openClassId'],"newToken"=>$newtoken);
$output=httppost($url3,$headers,$data);
$inClassInfo=$output["dataList"];

//
$url4="https://zjyapp.icve.com.cn/newmobileapi/faceteach/isJoinActivities";
        if(count($inClassInfo) != "0"){
                        foreach($inClassInfo as $n){
                                 if ($n["DataType"] == "签到" and $n["State"] !== "3"){
                                 $attendData = array("activityId"=>$i['Id'],"openClassId"=>$i['openClassId'],"stuId"=>$stuId,"typeId"=>$n['Id'],"type"=>"1","newToken"=>$newtoken);
        $output=httppost($url4,$headers,$attendData);
    //print_r($output);
        $attendInfo=$output;
        
        

        
        
        $conn = mysqli_connect('127.0.0.1','库名','密码','库名称');

$url5="https://zjyapp.icve.com.cn/newmobileapi/faceteach/saveStuSignNew";
                        if($attendInfo["isAttend"] != "1"){
                        $signInData = array("signId"=>$n['Id'],"stuId"=>$stuId,"openClassId"=>$i['openClassId'],"sourceType"=>"3","checkInCode"=>$n['Gesture'],"activityId"=>$i['Id'],"newToken"=>$newtoken);
                                        $output=httppost($url5,$headers,$signInData);
                                        //print_r($output);
                                        $time=date("Y-m-d H:i:s");
                                        echo '账号:'.$userName.'的'.$i["courseName"]." ".$time." ".$output["msg"];
if($output['msg']=='签到成功！'){
                                            $msg=$i["courseName"]." ".$time." ".$output["msg"];
 $sql_insert = "INSERT INTO msg(name,msg) VALUES('$userName','$msg')"; //执行SQL语句
        mysqli_query($conn, $sql_insert);
        
        
               
        $tuisong="https://qmsg.zendee.cn/group/QMSG的KEY";
         $sql = "select * from msg";
$r = $conn->query($sql);
         $sqls = "select * from User where state=1";
$rs = $conn->query($sqls);
 $sql_i = "update `User` set number=number+1 where username='$userName'"; //执行SQL语句
        mysqli_query($conn, $sql_i);
//update `User` set number=number+1 where username="nmslhh"
       $ts = array("qq"=>"1872732473","msg"=>"TA来自.$schoolName.的@at=$qq@课程:.$msg.正在挂机.$rs->num_rows.人，执行成功.$r->num_rows.次");
       $output=httppost($tuisong,$headers,$ts);
    mysqli_close($conn);
}
                         }else{
                                                        echo"账号:".$userName."的".$n['DateCreated'].$i["courseName"]."已经签到";
                                                        
                                                };
                        }else{
                                                // if($n["DataType"] == "签到"){
                                                // echo  "状态:".$output['code']."账号:".$userName."的".$n['DateCreated'].$i["courseName"]."的签到已经签到"."<br>";
                                                    
                                                // };
                                        };
                                };
                        };
                };        
        }else{
                echo "状态:".$output['code']."账号:".$userName."今天没有要签到的课程";
        };
        }else{  
           $state=$output['msg'];
              //print_r($output);
                if($output['msg'] == "用户密码错误！"){
                           echo $userName.$state;

                       $conn = mysqli_connect('127.0.0.1','库名','库密码','库名称');
$sql= "update User set state='$state' where username='$userName'";
          mysqli_query($conn, $sql);
          mysqli_close($conn);
        }
        

        }

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