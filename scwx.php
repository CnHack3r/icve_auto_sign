<?
 $conn = mysqli_connect('127.0.0.1','库名','库密码','库名称');
$sql= "delete from User where state = '用户密码错误！'";
          mysqli_query($conn, $sql);
          mysqli_close($conn);
          echo("执行删除成功");