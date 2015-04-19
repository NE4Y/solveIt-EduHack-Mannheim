import base64
from user import User
class UserManagment:
    users = [];
    
    ################################################
    # Appends a user to the active client list     #
    ################################################
    @staticmethod
    def append(id, conn):
        UserManagment.users.append(User(id, conn))
        
    
    ################################################
    # Just a debug function                        #
    ################################################
    @staticmethod
    def printUsers():
        for i in UserManagment.users:
            print(i.getID())
            
    
    ################################################
    # Checks whether a userid is connected         #
    # prevents double usage of registered id       #
    ################################################
    @staticmethod
    def existsUser(id):
        for i in UserManagment.users:
            if i.getID() == base64.b64decode(id):
                return True
        
        return False
    
    ################################################
    # Checks whether the client is allowed to      #
    # connect                                      #
    ################################################
    @staticmethod
    def checkAccess(id):
        pass
    ################################################
    # Activates a client, so that he can recieve   #
    # new messages                                 #
    ################################################
    @staticmethod
    def activateUser(client, id):
        for i in UserManagment.users:
            if i.getCon() == client:
                i.id = base64.b64decode(id.split(':')[1])
                return
   
    ################################################
    # Returns a id from a certrain client          #
    ################################################
    @staticmethod
    def getIDByClient(client):
	for i in UserManagment.users:
            if i.getCon() == client:
                return i.getID()
        return -1
 
    ################################################
    # Removes a certain client from active client  #
    # list                                         #
    ################################################
    @staticmethod
    def remove(client):
        for i in UserManagment.users:
            if i.getCon() == client:
                UserManagment.users.remove(i)
                return
