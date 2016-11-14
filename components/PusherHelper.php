<?php

namespace app\components;
 
 
use Yii;
use yii\base\Component;
use \Pusher;
/**
 * Description of Pusher
 *
 * @author Oracle
 */
class PusherHelper extends Component{
   
    private $APP_ID = 'f61b79b5a60a9cf8df35';
    private $APP_KEY = '3ad5caaab9e9b53bb5cd';
    private $APP_SECRET = '267257';


    public function __construct($config = array()) {
        
        parent::__construct($config);
    }
    
    public function sendKotNotification($message, $event)
    {
        $options = array('encrypted' => true, 'scheme'=>'http');
        
        $pusher = new Pusher($this->APP_ID, $this->APP_KEY, $this->APP_SECRET, $options);
        
        $data['message'] = $message;
        
        $pusher->trigger('kot_channel', $event, $data);
           
    }
    
    public function sendBotNotification($message, $event)
    {
        $options = array('encrypted' => true, 'scheme'=>'http');
        
        $pusher = new Pusher($this->APP_ID, $this->APP_KEY, $this->APP_SECRET, $options);
        
        $data['message'] = $message;
        
        $pusher->trigger('bot_channel', $event, $data);
           
    }
}

#https://dashboard.pusher.com/apps/267257/console/realtime_messages
        #https://dashboard.pusher.com/apps/267257/getting_started
        #https://pusher.com/docs/javascript_quick_start
        #https://github.com/pusher/pusher-http-php#push-notifications-beta
//        $options = array(
//            'encrypted' => true,
//            'scheme'=>'http',
//        );
//        $pusher = new Pusher(
//            'f61b79b5a60a9cf8df35',
//            '3ad5caaab9e9b53bb5cd',
//            '267257',
//            $options
//        );
//
//        VarDumper::dump($pusher);
//
//        $data['message'] = 'You have 1 new message';
//        $x = $pusher->trigger('kot_channel', 'my_event', $data);
//        var_dump($x);
//        echo 'ok';
//    }
?>
