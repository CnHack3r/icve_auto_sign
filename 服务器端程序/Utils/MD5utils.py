import hashlib


#       登录参数加密
def getMd5(msg):
    md5 = hashlib.md5()
    md5.update(msg.encode("utf-8"))
    return md5.hexdigest()


#       登录参数加密
def getDevice(equipmentModel, equipmentApiVersion, equipmentAppVersion, emit):
    tmp = getMd5(equipmentModel) + equipmentApiVersion
    tmp = getMd5(tmp) + equipmentAppVersion
    tmp = getMd5(tmp) + emit
    return getMd5(tmp)
