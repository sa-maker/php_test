<?php
// 2019/03/01 15:00:00
define ('DATE_DEFAULT', "Y/m/d H:i:s");
$date = new \DateTime();


class TimeOutput{

    public $name;       //a string stating the stage that the original time spans are in
    public $box_time;   //an array of dictionaries
    public $call_time;  //an array of dictionaries
    
    function __construct($name = "There is no stages", $box_time = [[]], $call_time= [[]] ){
        $this->name = $name;
        $this->box_time = $box_time;
        $this->call_time = $call_time;
    }
    
    function setName($new_name) {
        $this->name = $new_name;
    }
    function setBoxTime($new_box_time) {
        $this->box_time = $new_box_time;
    }
    function setCallTime($new_call_time) {
        $this->call_time = $new_call_time;
    }
    
    function stringifyTime($time_string){
        
        if($time_string === ""){
            return "";
        }else{
            return $time_string->format(DATE_DEFAULT);
        }
    }
    
    function printLine($heading, $time){  
        $start = $this->stringifyTime( $time['Start'] );
        $end =  $this->stringifyTime( $time['END'] );
        
        return "".$heading.": " . $start . " - " . $end . "<br>";
    }
    
    function printThis(){
        $ret_val = "";
        $ret_val .= $this->name . "<br>";

        foreach($this->box_time as $time ){
            $ret_val .= $this->printLine("Box Time", $time);
        }

        foreach($this->call_time as $time ){
            $ret_val .= $this->printLine("Call Time", $time);
        }

        return $ret_val;

    }
}

function timeCalculator($box_times, $call_times){
 /* This function assumes: 
  *   The start time is before the end time
  *   All times are in the format: 'yyyy/mm/dd hh:mm:ss'
  *     example: '2019/03/01 10:00:00'
  *   There will only be sent one call timespan and one box timespan
  *   The call timespan does not overlap 100% with the box timespan
  *   The Call time overrides the Box times
  * 
  * There are 6 stages for the times to overlap in:
  * 
 * 1. The phone timespan starts and ends before the box timespan start
 * 2. The phone timespan starts before the box timespan start 
 *    and ends after the box timespan start
 * 3. The phone timespan starts after the box timespan start 
 *    and ends before the box timespan ends
 * 4. The phone timespan starts after the box timespan start 
 *    and ends after the box timespan ends
 * 5. The phone timespan starts and ends after after the box timespan end 
 * 6. The call timespan starts before the box timespan 
  * and ends after the box timespan ends
 */    
  
    $time_output = new TimeOutput();
    
    $box_times_start = date_create($box_times['Start']);
    $box_times_end = date_create($box_times['End']);
    $call_times_start = date_create($call_times['Start']);
    $call_times_end = date_create($call_times['End']);
    
    
    //Stage 1 and 5
    if($call_times_end <= $box_times_start || $box_times_end <= $call_times_start ){
        $time_output->setName("stage 1 or stage 5");
        $time_output->setBoxTime(array(array('Start' => $box_times_start, 'END' =>$box_times_end)));
        $time_output->setCallTime(array(array('Start' => $call_times_start, 'END' =>$call_times_end)));
    }  
    //Stage 2
    if($call_times_start <= $box_times_start && $box_times_start < $call_times_end
            && $call_times_end < $box_times_end){
        $time_output->setName("stage 2");
        $time_output->setBoxTime(array(array('Start' => $call_times_end, 'END' =>$box_times_end)));
        $time_output->setCallTime(array(array('Start' => $call_times_start, 'END' =>$call_times_end)));    
        
    }
    //Stage 3
    if($box_times_start < $call_times_start && $call_times_end < $box_times_end){
        $time_output->setName("stage 3");
        $time_output->setBoxTime(array(
            array('Start' => $box_times_start, 'END' =>$call_times_start),
            array('Start' => $call_times_end, 'END' =>$box_times_end)));
        $time_output->setCallTime(array(array('Start' => $call_times_start, 'END' =>$call_times_end)));    
    }
    //Stage 4 
    if($box_times_start < $call_times_start && $call_times_start < $box_times_end
            && $box_times_end <= $call_times_end){
        $time_output->setName("stage 4");
        $time_output->setBoxTime(array(array('Start' => $box_times_start, 'END' =>$call_times_start)));
        $time_output->setCallTime(array(array('Start' => $call_times_start, 'END' =>$call_times_end)));        
    }    
    //Stage 6
    if($call_times_start < $box_times_start && $box_times_end < $call_times_end ){
        $time_output->setName("stage 6");
        $time_output->setBoxTime(array(array('Start' => "", 'END' => "")));
        $time_output->setCallTime(array(array('Start' => $call_times_start, 'END' =>$call_times_end)));        
    }
    
    return $time_output;
}



//----TEST DATA

//===Stage testing==============================================================
$box_times  = array('Start' => '2019/03/01 10:00:00', 'End' => '2019/03/01 10:30:00');

$call_times1  = array('Start' => '2019/03/01 09:15:00', 'End' => '2019/03/01 09:25:00');// Stage 1
$call_times2  = array('Start' => '2019/03/01 09:45:00', 'End' => '2019/03/01 10:05:00');// Stage 2
$call_times3  = array('Start' => '2019/03/01 10:05:00', 'End' => '2019/03/01 10:15:00');// Stage 3
$call_times4 = array('Start' => '2019/03/01 10:25:00', 'End' => '2019/03/01 10:45:00');// Stage 4
$call_times5  = array('Start' => '2019/03/01 10:45:00', 'End' => '2019/03/01 10:50:00');// Stage 5
$call_times6  = array('Start' => '2019/03/01 09:45:00', 'End' => '2019/03/01 10:50:00');// Stage 6
$call_times_list = array($call_times1, $call_times2, $call_times3, $call_times4, $call_times5, $call_times6);


//====Test testing==============================================================
$test_box_time1 = array('Start' => '2019/03/01 14:30:00', 'End' => '2019/03/01 15:00:00');
$test_box_time2 = array('Start' => '2019/03/01 18:00:00', 'End' => '2019/03/01 18:46:13');
$test_box_time3 = array('Start' => '2019/03/01 10:00:00', 'End' => '2019/03/01 10:30:00');
$test_box_list = array($test_box_time1, $test_box_time2, $test_box_time3);

$test_call_times1 = array('Start' => '2019/03/01 10:15:00', 'End' => '2019/03/01 10:25:00');
$test_call_times2 = array('Start' => '2019/03/01 14:15:00', 'End' => '2019/03/01 14:35:00');
$test_call_times3 = array('Start' => '2019/03/01 15:00:00', 'End' => '2019/03/01 19:00:00');
$test_call_list = array($test_call_times1, $test_call_times2, $test_call_times3);

//==============================================================================


//echo "<br><br>Testing the stages<br>";
//foreach ($call_times_list as $call_time) {
//    $output_time = timeCalculator($box_times, $call_time);
//    echo $output_time->printThis();
//    echo "<br>";
//}


echo "<br><br>Testing all test vallues against each other<br>";
foreach ($test_box_list as $test_box_time) {
    foreach($test_call_list as $test_call_time){
        //All this can be removed
        echo "<br>Input values<br>";
        echo "Box Time: Start - " . $test_box_time["Start"] . " End - " . $test_box_time["End"] . "<br>"; 
        echo "Call Time: Start - " . $test_call_time["Start"] . " End - " . $test_call_time["End"] . "<br>"; 
        echo "Output values <br>";
        //All this can be removed
        
        $output_time = timeCalculator($test_box_time, $test_call_time);
        echo $output_time->printThis();
        echo "<br>";        
    }
}

?>