import Utils.UserUtils as utils
import multiprocessing as mul
from apscheduler.schedulers.background import BackgroundScheduler
from apscheduler.triggers.interval import IntervalTrigger
import time

print("CnHack3r")

def mStart(UserList):
    pool = mul.Pool(50)
    for result in pool.map(getUserName, UserList):
        print(result)
    pool.close()
    # pool.join()
    return


def getUserName(UserInfo):
    return utils.CheckIn_Info(UserInfo['username'], UserInfo['password'],UserInfo['qq'])
    print("UserInfo OK!!!")



def timer():
    mStart(utils.getUserList())


if __name__ == '__main__':
    scheduler = BackgroundScheduler()
    intervalTrigger = IntervalTrigger(seconds=1)
    scheduler.add_job(timer,intervalTrigger,id='my_job')
    scheduler.start()
    while True:
        time.sleep(1)