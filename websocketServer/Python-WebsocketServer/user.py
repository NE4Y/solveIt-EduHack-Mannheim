################################################
# User class for active clients                #
################################################
class User:
    verified = False
    
    def __init__(self, id, con):
        self.id = id
        self.con = con
        
    def getID(self):
        return self.id
    
    def getCon(self):
        return self.con
        
