import requests
import json
import pymysql as mysql
import multiprocessing as mul
import time
import Utils.MD5utils as md5
from dbutils.pooled_db import PooledDB

POOL = PooledDB(
    creator=mysql,
    maxconnections=6,
    mincached=2,
    maxcached=10,
    maxshared=3,
    blocking=True,
    maxusage=None,
    setsession=[],
    ping=0,
    host='127.0.0.1',
    port=3306,
    user='zjy',
    password='zjy',
    database='zjy'
)


#       获取全部签到账号ID
def getUserList():
    conn = POOL.connection()
    cursor = conn.cursor()
    sql = "SELECT * FROM User where state=1"
    cursor.execute(sql)
    data = cursor.fetchall()
    UserList = []
    for row in data:
        result = {
            "id": row[0],
            "username": row[1],
            "password": row[2],
            "state": row[3],
            "qq": row[4]
        }
        UserList.append(result)
    return UserList


#       执行签到
def CheckIn_Info(username, password,qq):
    getUserLoginInfo(username, password,qq)
    return {"username": username,"password":password,"qq":qq}



def getUserLoginInfo(username, password, qq):
    path = "ip/assets/api/index.php"
    data = {
        "userPwd": password,
        "userName": username,
        "qq": qq
    }
    res = requests.post(path, data=data)
    msg =res.text
    print(msg)


