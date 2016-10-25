<?php

namespace app\components;
 
 
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Description of AgentTypes
 *
 * @author Oracle
 */
class GrcUtilities extends Component{
    
    public static function getAgentTypes(){
        
        return array(
            'DIRECT_BOOKING'=>'DIRECT BOOKING',
            'TRAVEL_AGENT'=>'TRAVEL_AGENT',
            'BROKER'=>'BROKER',
            'THIRD_PARTY_WEB'=>'THIRD PARTY WEB SITES'
        );
    }
}

?>
