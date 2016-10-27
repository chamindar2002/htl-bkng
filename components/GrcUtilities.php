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
    
    public static function computeDatesAllocation($start, $end){
         $datediff = strtotime($end) - strtotime($start);
         $no_of_nights = $datediff / (60 * 60 * 24);
         
         $days_allocation = array();
            
         for($i=0;$i<$no_of_nights;$i++){
              $days_allocation[] = date('Y-m-d', strtotime($start));
              $start = date('Y-m-d', strtotime($start . ' +1 day'));
         }
         
         return array(
             'no_of_nights' => $no_of_nights,
             'date_allocation' => $days_allocation  
         );
    }
}

?>
