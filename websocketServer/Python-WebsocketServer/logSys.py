################################################
# Simple loggingsystem                         #
################################################
import os

class LogSys: 
    
    @staticmethod
    def writeData(file, data):
        try:
            f = open(file+".log", "a")
            f.write(data + "\r\n")
        except:
            print("An error occuried while writing to the log file.")
            
                
                
                

            
        
            