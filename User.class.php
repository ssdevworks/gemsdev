<?php

class  User
{
    var $firstName ;
    
    var $lastName;
    
    var $email;
    
    var $userId;
    
    var $password;
    
    var $membertId;
    
    var $membershipDate;
    
    var $salutation;
    
    var $type;
    
    var $status;
    
    var $ride;
    
    var $suffix;
    
    var $handicap;
    
    var $nickname;
    
    var $lastLession;
   
    var $gender;
    
    var $title;
    
    var $birthday;
    
    var $underGrade;
    
    var $martialStatus;
    
    var $occupation;
    
    var $anniversary;
    
    var $note;
    
    
    function __construct($p_array)
    {
         global $CFG,$db_obj, $lang;
         
         if(is_array($p_array))
         {
               $this->userId    =  $p_array['userid'];   
               $this->password  =  $p_array['password']; 
               $this->email     =  $p_array['email'];  
         }
         
    }
    
    function loginValidateUser()
    {
         global $CFG,$db_obj, $lang;
         $errMsg='';
         if($this->userId==''){
            $errMsg = 'User Id should not be empty';
         }
         elseif($this->password==''){
            $errMsg = 'Password should not be empty';
         }
         else{
            $sql_ems = "SELECT * FROM gems_user WHERE is_active = '1' AND userid='$this->userId' AND password='$this->password' ";
            //echo $sql_ems;exit;
            $res_ems = mysql_query($sql_ems);
            $cnt     = mysql_num_rows($res_ems);
            
             if($cnt==0)
             {
                $errMsg = 'Invalid Userid / Password';
             }
         }
         return $errMsg;
    }
    
    function getUserDetails()
    {
        global $CFG,$db_obj, $lang;
         
        $sql_ems = "SELECT * FROM gems_user WHERE is_active = '1' AND userid='$this->userId' AND password='$this->password' ";
        $res_ems = mysql_query($sql_ems);
        $userDetails = mysql_fetch_array($res_ems);
        //print_r($userDetails);exit;
        return $userDetails;
    }
    
       
 }
?>