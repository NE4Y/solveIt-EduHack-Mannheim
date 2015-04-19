################################################
# Websocketclass for broadcastserver           #
################################################

import socket, hashlib, base64, threading
from logSys import LogSys
from time import gmtime, strftime
from user import User
from userManagment import UserManagment
 
class WebSock:
    MAGIC = '258EAFA5-E914-47DA-95CA-C5AB0DC85B11'
    HSHAKE_RESP = "HTTP/1.1 101 Switching Protocols\r\n" + \
                "Upgrade: websocket\r\n" + \
                "Connection: Upgrade\r\n" + \
                "Sec-WebSocket-Accept: %s\r\n" + \
                "\r\n"
                
    LOCK = threading.Lock()
    
 
    ################################################
    # Recieves the data, byte per byte             #
    ################################################
    def recv_data (self, client, addr=0):
        data = bytearray(client.recv(512))
        if(len(data) < 6):
            raise Exception("Error reading data")
        # FIN bit
        assert(0x1 == (0xFF & data[0]) >> 7)
        
        assert(0x1 == (0xF & data[0]))
        
        # assert that data is masked
        assert(0x1 == (0xFF & data[1]) >> 7)
        datalen = (0x7F & data[1])
        
        
        str_data = ''
        if(datalen > 0):
            mask_key = data[2:6]
            masked_data = data[6:(6+datalen)]
            unmasked_data = [masked_data[i] ^ mask_key[i%4] for i in range(len(masked_data))]
            str_data = str(bytearray(unmasked_data))
        
        if self.hasPattern(str_data):
            if not self.checkIdentification(str_data):                
                UserManagment.activateUser(client, str_data)
            else:
                LogSys.writeData('clientLog', 'Connection restricted from: ' + addr[0] + ' due to double used ID: '+ str_data.split(':')[1])
                UserManagment.removeUser(client)
                client.close() 
        return str_data
    
 
    ################################################
    # Sends the message as broadcast to all        #
    # connected users                              #
    ################################################
    def broadcast_resp(self, data):
        resp = bytearray([0b10000001, len(data)])
        # append the data bytes
        for d in bytearray(data):
            resp.append(d)
            
        self.LOCK.acquire()
        for client in UserManagment.users:
            try:
                if client.getID() != -1 and not self.hasPattern(data):
                    client.getCon().send(resp)
            except:
                print("error sending to a client")
        self.LOCK.release()
        
    #sends data to user 
    def sendData(self, data):
        split = data.split('@')
        resp = bytearray([0b10000001, len(data)])
        # append the data bytes
        for d in bytearray(data):
            resp.append(d)
            
        self.LOCK.acquire()
        print("acquire")
        for client in UserManagment.users:
            print("for")
            try:
                print("getID: " + str(client.getID()))
                print("to: " + str(split[3]).strip())
                if str(client.getID()).strip() == str(split[3]).strip():
                    client.getCon().send(resp)
            except:
                print("error sending to a client")
        self.LOCK.release()
        
        
    ################################################
    # Checking headers                             #       
    ################################################
    def parse_headers (self, data):
        headers = {}
        lines = data.splitlines()
        for l in lines:
            parts = l.split(": ", 1)
            if len(parts) == 2:
                headers[parts[0]] = parts[1]
        headers['code'] = lines[len(lines) - 1]
        return headers
 
    ################################################
    # Do standard websock handshake                #
    ################################################
    def handshake (self, client):
        data = client.recv(2048)
        headers = self.parse_headers(data)
        
        key = headers['Sec-WebSocket-Key']
        resp_data = self.HSHAKE_RESP % ((base64.b64encode(hashlib.sha1(key+self.MAGIC).digest()),))
     
        return client.send(resp_data)
    
    ################################################
    # Checks wheter a recv message is a            #
    # activation msg                               #
    ################################################
    def hasPattern(self, msg):
        split = msg.split(':')
        
        return True if len(split) == 2 else False
        
    
    def isServerOrigin(self, data):
        print("Data: " + str(data))
        split = data.split('@')
        return True if len(split) == 5 and split[0] == "SOK" else False
    
    ################################################
    # Checks whether a client is allowed to connect#
    ################################################
    def checkIdentification(self, msg):
        split = msg.split(':')
        return UserManagment.existsUser(split[1])
        
    ################################################
    # Server client                                #
    ################################################
    def handle_client (self, client, addr):
        self.handshake(client)
        try:
            while 1:            
                data = self.recv_data(client, addr)
                print("data kommt rein")
                if  self.isServerOrigin(data) == True:
                    LogSys.writeData('signalLog', strftime("%Y-%m-%d %H:%M:%S", gmtime()) + ' ' + str(data))
                    print("Signal recieved @ " + strftime("%Y-%m-%d %H:%M:%S", gmtime()))                
                    self.sendData(data)
                    client.close()
        except Exception:
            pass
        
        #Client logging
        LogSys.writeData('clientLog', 'Client disconnected: ' + addr[0])
        self.LOCK.acquire()
        UserManagment.remove(client)
        self.LOCK.release()
        client.close()
        
    ################################################
    # Starts the server on a certain port          #
    ################################################
    def start_server(self, port):
        s = socket.socket()
        s.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
        s.bind(('', port))
        s.listen(5)
        print('Server started. Listening on port: ' + str(port))
        
        #Logging Server start
        LogSys.writeData('serverLog', 'Server started on: ' + strftime("%Y-%m-%d %H:%M:%S", gmtime()))
        while(1):
            conn, addr = s.accept()
            
            #client Logging
            print("Client connected");
            LogSys.writeData('clientLog', "Client connection from: " + addr[0])
            threading.Thread(target = self.handle_client, args = (conn, addr)).start()
            self.LOCK.acquire()
            UserManagment.append(-1, conn)
            self.LOCK.release()
 
ws = WebSock()
ws.start_server(9999)
