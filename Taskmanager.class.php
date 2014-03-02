<?php

class  Taskmanager
{
    var $tasker ;
    
    var $start_date;
    
    var $taskee;
    
    var $due_date;
    
    var $task_status;
    
    var $private;
    
    var $complete_date;
    
    var $current_view;
    
    var $get_report;
    
    var $task_detail;
    
    var $tasker_notes;
    
    var $taskee_notes;
    
    var $taskId;
    
    function __construct($p_array=array())
    {
         global $CFG,$db_obj, $lang;
         
         if(is_array($p_array) && count($p_array)>0)
         {
                 $this->tasker = $p_array['tasker'] ;
    
                $this->start_date = $p_array['start_date'] ;
                
                $this->taskee = $p_array['taskee'] ;
                
                $this->due_date = $p_array['due_date'];
                
                $this->task_status = $p_array['task_status'] ;
                
                $this->private = $p_array['private'] ;
                
                $this->complete_date = $p_array['complete_date'] ;
                
                $this->current_view = $p_array['current_view'] ;
                
                $this->get_report = $p_array['get_report'] ;
                
                $this->task_detail = $p_array['task_detail'] ;
                
                $this->tasker_notes = $p_array['tasker_notes'] ;
                
                $this->taskee_notes = $p_array['taskee_notes'] ;
                 $this->taskId = $p_array['taskId'] ;
         }
         
    }
    
    function ValidateTask()
    {
         global $CFG,$db_obj, $lang;
         $errMsg='';
         if($this->tasker=='')
         {
            $errMsg = 'Tasker should not be empty';
         }
         elseif($this->start_date=='')
         {
            $errMsg = 'Select tasker start date';
         }
         elseif($this->taskee<1)
         {
            $errMsg = 'Select a taskee';
         }
         elseif($this->due_date=='')
         {
            $errMsg = 'Select a due date';
         }
         elseif($this->task_status<1)
         {
            $errMsg = 'Select a task status';
         }
         elseif($this->start_date > $this->due_date)
         {
            $errMsg = 'Due date should be greater than start date';
         }
         elseif($this->task_status!=4) // If task status is cancelled meeans no need to check complete date
         {
             if($this->complete_date<1){
                 $errMsg = 'Select a completion date';
             }     
             elseif($this->due_date > $this->complete_date)
             {
                $errMsg = 'Complete date should be greater than due date';
             }       
         }
         
         
          
          /*
         elseif($this->current_view=='')
         {
            $errMsg = 'Enter a current view';
         }
         elseif($this->get_report=='')
         {
            $errMsg = 'Select get report';
         }*/
         elseif($this->task_detail=='')
         {
            $errMsg = 'Describe about task';
         }
         elseif($this->tasker_notes=='')
         {
            $errMsg = 'Describe about tasker notes';
         }
         elseif($this->taskee_notes=='')
         {
            $errMsg = 'Describe about tasked notes';
         }
         
        
         return $errMsg;
    }
    
    function saveTaskDetails()
    {
        global $CFG,$db_obj, $lang;
       
        
        $dbFields['tasker']        = $this->tasker;
        $dbFields['start_date']    = $this->start_date !='' ? date('Y-m-d',strtotime($this->start_date)):'';
        $dbFields['due_date']      = $this->due_date !='' ? date('Y-m-d',strtotime($this->due_date)):'';
        $dbFields['taskee']        = $this->taskee;
        $dbFields['task_status']   = $this->task_status;
        $dbFields['private']       = $this->private;
        $dbFields['complete_date'] = $this->complete_date !='' ? date('Y-m-d',strtotime($this->complete_date)):'';
        $dbFields['current_view']  = $this->current_view;
        $dbFields['get_report']    = $this->get_report;
        $dbFields['task_detail']   = $this->task_detail;
        $dbFields['tasker_notes']  = $this->tasker_notes;
        $dbFields['taskee_notes']  = $this->taskee_notes;
        foreach($dbFields as $k => $v)
        {
            $sqlParam[] = "$k = '$v'";
        }
         $sqlAddn = implode(', ',  $sqlParam);
        if($this->taskId > 0)
            $sql = "UPDATE gems_task SET $sqlAddn WHERE id= $this->taskId";
        else
            $sql = "INSERT INTO gems_task SET $sqlAddn";
            
         $res    = mysql_query($sql) or die( mysql_error());
         if($this->taskId > 0)
         $aff    = mysql_affected_rows();
         else
         $aff    = mysql_insert_id();
        return $aff;
    }
    
    public function getTaskDetails($taskId)
    {
        global $CFG,$db_obj, $lang;
        $resData  = array();
        $sql      = "SELECT * FROM gems_task WHERE id=$taskId  ";
        $res      = mysql_query($sql) or die(mysql_error());
        $cnt      = mysql_num_rows($res);
        if($cnt > 0)
        {
            $resData = mysql_fetch_assoc($res);
            $resData['start_date']    = $resData['start_date'] !='0000-00-00' ? date('m/d/Y',strtotime($resData['start_date'])):'';
            $resData['due_date']      = $resData['due_date'] !='0000-00-00' ? date('m/d/Y',strtotime($resData['due_date'])):'';
            $resData['complete_date'] = $resData['complete_date'] !='0000-00-00' ? date('m/d/Y',strtotime($resData['complete_date'])):'';
        }
        //echo "<pre>";print_r($resData);echo "</pre>";
        return $resData;
        
    }
    
     public function getAllTasks()
    {
        global $CFG,$db_obj, $lang;
        $resInfo  = array();
        $sql      = "SELECT * FROM gems_task WHERE is_active =1  ";
        $res      = mysql_query($sql) or die(mysql_error());
        $cnt      = mysql_num_rows($res);
        if($cnt > 0)
        {
            while($resData = mysql_fetch_assoc($res))
            {
                $resData['start_date']    = $resData['start_date'] !='0000-00-00' ? date('m-d-Y',strtotime($resData['start_date'])):'';
                $resData['due_date']      = $resData['due_date'] !='0000-00-00' ? date('m-d-Y',strtotime($resData['due_date'])):'';
                $resData['complete_date'] = $resData['complete_date'] !='0000-00-00' ? date('m-d-Y',strtotime($resData['complete_date'])):'';
                $resInfo[] = $resData;
            }
        }
        //echo "<pre>";print_r($resData);echo "</pre>";
        return $resInfo;
        
    }
    
    
    function deactivateTask($taskId)
    {
         global $CFG,$db_obj, $lang;
       
         $sql = "UPDATE gems_task SET is_active=0 WHERE id= $taskId";
         
         $res    = mysql_query($sql) or die( mysql_error());
         
         $aff    = mysql_affected_rows();
         
        return $aff;
    }
    
    
    
       
 }
?>