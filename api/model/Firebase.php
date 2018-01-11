<?php
Abstract class FIREBASESDK
{
    private static $firebase,
                   $DEFAULT_URL,
                   $DEFAULT_TOKEN,
                   $DEFAULT_PATH;

    function __construct()
    {
        $config = explode(",", file_get_contents("config/API/firebase.ini", true));
        self::$DEFAULT_URL = $config[0];
        self::$DEFAULT_TOKEN = $config[1];
        self::$DEFAULT_PATH = $config[2];
        self::$firebase = new \Firebase\FirebaseLib(self::$DEFAULT_URL);
    }

    function __destruct()
    {
         unset($this);
         flush();
    }

    protected function getData($path = null, $data)
    {
        if(empty($data)):
            $this->response = self::$firebase->get(self::$DEFAULT_PATH.$path);
        
        endif;
            $this->response = self::$firebase->get(self::$DEFAULT_PATH.$path, $data);

        return $this->response;
    }

    protected function setData($path = null, $data)
    {
        $dateTime = new DateTime();
        $this->response = self::$firebase->set(self::$DEFAULT_PATH.$path, $data);
        return $this->response;
    }

    protected function pushData($path = null, $data)
    {
        $dateTime = new DateTime();
        $this->response = self::$firebase->push(self::$DEFAULT_PATH.$path, $data);
        return $this->response;
    }

    protected function putData($path = null, $data)
    {
        $this->response = self::$firebase->put(self::$DEFAULT_PATH.$path, $data);
        return $this->response;
    }

    protected function updateData($path = null, $data)
    {
        $this->response = self::$firebase->update(self::$DEFAULT_PATH.$path, $data);
        return $this->response;
    }

    protected function deleteData($path)
    {
        $this->response = self::$firebase->delete(self::$DEFAULT_PATH.$path);
        return $this->response;
    }
}


Trait FCM
{
    function __construct()
    {
        $this->limits = parse_ini_file(Server_Root."config/Database/limits.ini");
        $this->config = parse_ini_file(Server_Root."config/Database/config.ini");
    }

    function __destruct()
    {
        unset($this);
    }

    protected function saveUserID($uname, $token)
    {
        R::setup("mysql:host={$this->config['host']};dbname={$this->config['dbname']}", $this->config['user'], $this->config['password']);
        $res = R::getRow('select * from fcm where uname =?', [$uname]);
        if(!empty($res)):
            $response = R::exec("update fcm SET fcmtoken='$token' where uname = '$uname';");
            R::close();
            return "Inserted";

        endif;
            $data = R::dispense('fcm');
            $data->fcmtoken = $token;
            $data->uname = $uname;
            if(is_numeric(R::store($data))):
                R::close();
                return "Inserted";
            
            endif;
                R::close();
                return "Not Inserted";
    }

    protected function deleteUser($uname)
    {
        R::setup("mysql:host={$this->config['host']};dbname={$this->config['dbname']}", $this->config['user'], $this->config['password']);
        $data = R::exec('delete from fcm where uname =?', [$uname]);
        R::close();
        return !empty($data)?"Deleted":"Not Deleted";
    }

    protected function updateUser($uname, $token, $previous)
    {
        R::setup("mysql:host={$this->config['host']};dbname={$this->config['dbname']}", $this->config['user'], $this->config['password']);
        $this->data = R::exec("update fcm SET fcmtoken ='$token', uname ='$uname' where uname = '$previous';");
        R::close();
        return !empty($this->data)?"Updated":"Not Updated";
    }

    protected function getAllUserID()
    {
        R::setup("mysql:host={$this->config['host']};dbname={$this->config['dbname']}", $this->config['user'], $this->config['password']);
        $response = R::getAll('select * from fcm order by id desc');
        R::close();
        return $response;
    }

    protected function getUserID($uname = null)
    {
        R::setup("mysql:host={$this->config['host']};dbname={$this->config['dbname']}", $this->config['user'], $this->config['password']);
        $response = R::getRow('select * from fcm where uname =?', [$uname]);
        R::close();
        return $response;
    }

    protected function sendManyFCM($data, $message, $title, $icon)
    {
        R::setup("mysql:host={$this->config['host']};dbname={$this->config['dbname']}", $this->config['user'], $this->config['password']);
        $this->resp = [];
        $msg['message'] = $message;
        $msg['title'] = $title;
        $msg['icon'] = $icon;
        foreach($data as $key => $arr)
        {
            $msg['fcmtoken'] = R::getRow('select * from fcm where uname =?', [$arr])['fcmtoken'];
            $this->resp[$arr] = self::sendFCM($msg);
        }
        R::close();
        return $this->resp;
    }

    protected function sendFCM($data)
    {
        $config = explode(",", file_get_contents(Server_Root."config/API/fcm.ini"));
        $headers = [
            'Authorization:KEY='.$config[1],
            'Content-Type:application/json'
        ];
        $payload = json_encode([
            'to' => $data['fcmtoken'],
            'notification' => [
                'title' => $data['title'],
                'body' => $data['message'],
                'icon' => $data['icon']
            ]
        ]);

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, $config[0] );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result['status'] = curl_exec($ch );

        if($result['status'] === FALSE):
            $result['status'] = "Failed";
            $result['Error'] = 'Push failed because '.curl_error($ch);
            curl_close( $ch );
            return $result;
        
        endif;
            $result['status'] = "Success";
            curl_close( $ch );
            return $result;
    }
}
?>