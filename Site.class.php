<?php
/** **************************************************************************************************
  Script Name   : Site.class.php
  Package       : Admin
  Employee-code : ZIL016
  Date          : December 2nd, 2011
  Version       : 1.0
  Website       : http://www.zen-e-solutions.com
  E-mail        : info@zen-e-solutions.com
	

  Support Details:
   (1) Read Notes Before Listed Methods.
   (2) Support version >= php5
 
 
  Purpose of the file:
    - Admin classes configure here, function files included this file only

  Modification:
    Date & Reason:
	 - Change email regexp in getEmailRegExp(void) function
	 - Add htmlEntityStripSlash(array) function 
	   to set html entities & strip slashes for an array
								
*************************************************************************************************
| By using this software, you acknowledge having read this Agreement and agree to be bound thereby.
**************************************************************************************************
| Copyright (c) 2011 zen-e-solutions.com. All rights reserved.
**************************************************************************************************/

    class Site{
     var $db_con_id;
        
      
     function __construct(){
            
         global $CFG,$db_obj,$client_smarty,$admin_smarty,$client_smarty;
         
         $db_host = $CFG['db']['db_host'];
         $db_name = $CFG['db']['db_name'];
         $db_user = $CFG['db']['db_user'];
         $db_pwd  = $CFG['db']['db_pwd'];
    
         # DB connections
         $this->db_connect_id = $this->connect_db($db_host,$db_name,$db_user,$db_pwd);
         $_SESSION[db_con_id] = $this->db_connect_id;
              
    }
#-------------------------------------------------------------------------------------------------------------------
    public function connect_db($db_host,$db_name,$db_user,$db_pwd){
    	$link = mysql_connect($db_host,$db_user, $db_pwd) or die("Could not connect: " . mysql_error());
    	mysql_select_db($db_name, $link) or die ('Can\'t use  : '.$db_name . mysql_error());
    	return $link;
     }

#-------------------------------------------------------------------------------------------------------------------
    public function close_db(){
		mysql_close($_SESSION[db_con_id]);
        $_SESSION[db_con_id] = NULL;
    }
#-------------------------------------------------------------------------------------------------------------------

	public function sql_close()	{
		if($this->db_connect_id){
			if($this->query_result){
			@mysql_free_result($this->query_result);
			}
			$result = @mysql_close($this->db_connect_id);
			return $result;
		}
		else{
			return false;
		}
	}
#-------------------------------------------------------------------------------------------------------------------
    // Base query method
	public function sql_query($query = "", $transaction = FALSE){
     // Remove any pre-existing queries
 
		unset($this->query_result);
		if($query != ""){
			$this->num_queries++;
			$this->query_result = mysql_query($query, $this->db_connect_id) or die(mysql_error().$query);
			if (mysql_errno()) {
				exit;
			}
 		}
		if($this->query_result){
			unset($this->row[$this->query_result]);
			unset($this->rowset[$this->query_result]);
			return $this->query_result;
		}
		else{
			return ( $transaction == END_TRANSACTION ) ? true : false;
		}
	}
#-------------------------------------------------------------------------------------------------------------------
	public function sql_fetchrow($query_id = 0){
		if(!$query_id){
			$query_id = $this->query_result;
		}
		if($query_id){
			$this->row[$query_id] = @mysql_fetch_array($query_id);
			return $this->row[$query_id];
		}
		else{
			return false;
		}
	}

#-------------------------------------------------------------------------------------------------------------------
	public function sql_fetchrowset($query_id = 0){
		if(!$query_id){
			$query_id = $this->query_result;
		}
		if($query_id){
			unset($this->rowset[$query_id]);
			unset($this->row[$query_id]);
			while($this->rowset[$query_id] = @mysql_fetch_array($query_id)){
				$result[] = $this->rowset[$query_id];
			}
			return $result;
		}
		else {
			return false;
		}
	}
#-------------------------------------------------------------------------------------------------------------------

	public function sql_fetchobject($query_id = 0){
		if(!$query_id){
			$query_id = $this->query_result;
		}
		if($query_id){
		   $this->row[$query_id] = @mysql_fetch_object($query_id);
		   return $this->row[$query_id];
		}
		else{
			return false;
		}
	}
#-------------------------------------------------------------------------------------------------------------------

	public function sql_nextid() {
		if($this->db_connect_id){
			$result = @mysql_insert_id($this->db_connect_id);
			return $result;
		}
		else{
			return false;
		}
	}
#-------------------------------------------------------------------------------------------------------------------

	public function sql_numrows($query_id = 0) {
		if(!$query_id){
			$query_id = $this->query_result;
		}
		if($query_id){
			$result = @mysql_num_rows($query_id);
			return $result;
		}
		else{
			return false;
		}
	}
#-------------------------------------------------------------------------------------------------------------------

    public function sql_fetchfield($field, $row = -1, $query_id){
		if( !$query_id){
			$query_id = $this->result;
		}


		if( $query_id ){
			if( $row != -1 ){
				if( $this->limit_offset[$query_id] > 0 ){
					$result = ( !empty($this->limit_offset[$query_id]) ) ? @mssql_result($this->result, ($this->limit_offset[$query_id] + $row), $field) : false;
				}
				else{
					$result = @mssql_result($this->result, $row, $field);
				}
			}
			else{
				if( empty($this->row[$query_id]) ){
					$this->row[$query_id] = @mssql_fetch_array($query_id);
					$result = stripslashes($this->row[$query_id][$field]);
				}
			}
			return $result;
		}
		else{
			return false;
		}
	}
    //End database connection

#-------------------------------------------------------------------------------------------------------------------
    function writefile($filna,$content) {

        $wrfil = fopen($filna, 'w');
        fwrite($wrfil,$content);
        fclose($wrfil);
    }
#-------------------------------------------------------------------------------------------------------------------
     function readfilecontent($file) {
            global $CFG;

           $fp=fopen("$file","r") or die("Could not open the file $file");
           $filesize=filesize($file);
           $filecontent=fread($fp,$filesize);
           fclose($fp);
           return $filecontent;
     }
#-------------------------------------------------------------------------------------------------------------------
    public function get_admin_details(){
        
         global $CFG,$admin_smarty,$client_smarty;
             
         $consult   = $this->sql_query("SELECT *FROM ".$CFG['table']['admin']." where wp_admin_id = '1' ");
         $numfields = mysql_num_fields($consult);
         $SiteObj   = mysql_fetch_object($consult);

         for($i=0;$i<$numfields;$i++){
             $fieldname[$i]   = mysql_field_name($consult, $i);
             $fieldvalue[$i]  = stripslashes($SiteObj->$fieldname[$i]);

             $client_smarty->assign($fieldname[$i],$fieldvalue[$i]);
             $admin_smarty->assign($fieldname[$i],$fieldvalue[$i]);
             
             $CFG[site][$fieldname[$i]] = $fieldvalue[$i];
         }
         
         return $CFG;
    } 
#-------------------------------------------------------------------------------------------------------------------
   
    public function db_table_declarations(){
        global $CFG;
        
        $result_table = mysql_list_tables($CFG['db']['db_name']);
        $cnt_table    = 0;
        $table_prefix = $CFG['db']['table_prefix'];

        while($row_table = mysql_fetch_row($result_table)) {
           
              //claim_admin,0,6
               $with_out_prefix_tblname = str_replace( $table_prefix, '', $row_table[0] );
           
             if(substr($row_table[0], 0, strlen($table_prefix)) == $table_prefix){ 
                $table_name = $row_table[0]; 
                $CFG['table'][$with_out_prefix_tblname] = $table_name;
             }   
        }    
        
        return $CFG;
        
    } 
#-------------------------------------------------------------------------------------------------------------------

    public function get_sharing_details(){
        
         global $CFG,$admin_smarty,$client_smarty;
             
         $consult   = $this->sql_query("SELECT *FROM ".$CFG['table']['sharing_settings']." where wp_sharing_id = '1' ");
         $numfields = mysql_num_fields($consult);
         $SiteObj   = mysql_fetch_object($consult);

         for($i=0;$i<$numfields;$i++){
             $fieldname[$i]   = mysql_field_name($consult, $i);
             $fieldvalue[$i]  = stripslashes($SiteObj->$fieldname[$i]);

             $client_smarty->assign($fieldname[$i],$fieldvalue[$i]);
             $admin_smarty->assign($fieldname[$i],$fieldvalue[$i]);
             
             $CFG[share][$fieldname[$i]] = $fieldvalue[$i];
         }
         
         return $CFG;
    } 
#-------------------------------------------------------------------------------------------------------------------

    public function get_ads_settings_details(){
        
         global $CFG,$admin_smarty,$client_smarty;
             
         $consult   = $this->sql_query("SELECT *FROM ".$CFG['table']['ads_settings']." where wp_ads_id = '1' ");
         $numfields = mysql_num_fields($consult);
         $SiteObj   = mysql_fetch_object($consult);

         for($i=0;$i<$numfields;$i++){
             $fieldname[$i]   = mysql_field_name($consult, $i);
             $fieldvalue[$i]  = stripslashes($SiteObj->$fieldname[$i]);

             $client_smarty->assign($fieldname[$i],$fieldvalue[$i]);
             $admin_smarty->assign($fieldname[$i],$fieldvalue[$i]);
             
             $CFG[ads][$fieldname[$i]] = $fieldvalue[$i];
         }
         
         return $CFG;
    } 
#-------------------------------------------------------------------------------------------------------------------
    
     function mydelete($table_name,$cond){
         global $CFG,$lang;

         $sel  = "DELETE FROM $table_name WHERE $cond LIMIT 1";
         $res  = mysql_query($sel) or die(mysql_error());
    }
#-------------------------------------------------------------------------------------------------------------------

    function getvalue($table_name,$fld,$cond){
         global $CFG,$lang;

         $sel     = "SELECT $fld FROM ".$table_name." WHERE $cond";
         $res     = mysql_query($sel) or die(mysql_error().$sel);
         $sql_cnt = mysql_num_rows($res);
         $row     = mysql_fetch_array($res); 
         
         return $row[0];
    }
#-------------------------------------------------------------------------------------------------------------------

    function get_value($table_name,$fld,$cond){
         global $CFG,$lang;

         $sel     = "SELECT $fld FROM ".$table_name." WHERE $cond";
         $res     = mysql_query($sel) or die(mysql_error().$sel);
         $sql_cnt = mysql_num_rows($res);
         $row     = mysql_fetch_array($res); 
         
         return $row[0];
    }
#-------------------------------------------------------------------------------------------------------------------

    function is_duplicate($table_name,$fld,$cond){
         global $CFG,$lang;

         $sel     = "SELECT $fld FROM ".$table_name." WHERE $cond";
         $res     = mysql_query($sel) or die(mysql_error().$sel);
         $cnt     = mysql_num_rows($res);
         
         return $cnt;
    }
#-------------------------------------------------------------------------------------------------------------------

    function get_num_rows($table_name,$cond){
         global $CFG,$lang;

         $sel     = "SELECT COUNT(*) FROM {$table_name} WHERE {$cond}";
         $res     = mysql_query($sel) or die(mysql_error().$sel);
         $cnt     = mysql_num_rows($res);
         
         return $cnt;
    }
#-------------------------------------------------------------------------------------------------------------------

    function delete_single_record($tbl_name,$cond){
         global $CFG,$lang;

         $sel  = "DELETE FROM ".$tbl_name." WHERE $cond LIMIT 1";
         $res  = mysql_query($sel) or die(mysql_error().$sel);
         
         return $cnt;
    }
#-------------------------------------------------------------------------------------------------------------------

    function show_msg_board($show_msg,$msg_type='ss'){ //ss,info,error,warning,note
         global $CFG,$lang,$client_smarty;


              $tpl_path = $CFG[site][usertpl_path];

         if($msg_type == 'ss'){
            $succtemplates = $this->readfilecontent($tpl_path."/success.tpl");
            $succtemplates = str_replace("{SUCCESS}",$show_msg,$succtemplates);
            $client_smarty->assign("succ_temp",$succtemplates);
            $client_smarty->assign("success",$show_msg);

         } 

         if($msg_type == 'info'){
            $succtemplates = $this->readfilecontent($tpl_path."/success.tpl");
            $succtemplates = str_replace("{SUCCESS}",$show_msg,$succtemplates);
            $client_smarty->assign("succ_temp",$succtemplates);
            $client_smarty->assign("success",$show_msg);

         } 

         if($msg_type == 'error'){
            $errortemplates = $this->readfilecontent($tpl_path."/error.tpl");
            $errortemplates = str_replace("{ERROR}",$show_msg,$errortemplates);
            $client_smarty->assign("error_temp",$errortemplates);
            $client_smarty->assign("error",$show_msg);

         }   
 
         if($msg_type == 'warning'){
            $errortemplates = $this->readfilecontent($tpl_path."/warning.tpl");
            $errortemplates = str_replace("{WARNING}",$show_msg,$errortemplates);
            $client_smarty->assign("warning_temp",$errortemplates);
            $client_smarty->assign("warning",$show_msg);

         }   

         if($msg_type == 'note'){
            $errortemplates = $this->readfilecontent($tpl_path."/note.tpl");
            $errortemplates = str_replace("{NOTE}",$show_msg,$errortemplates);
            $client_smarty->assign("note_temp",$errortemplates);
            $client_smarty->assign("note",$show_msg);

         }   
    }
#-------------------------------------------------------------------------------------------------------------------
    function show_webpage($page){
         global $CFG,$lang,$client_smarty;

   	     $this->sql_close();
            
         //Fetch from client templates
         $main_content = $client_smarty->fetch($page);
         $client_smarty->assign("MAIN_CONTENT",$main_content);
         $client_smarty->display('main.tpl'); 
    }
#-------------------------------------------------------------------------------------------------------------------
    function show_webpage_admin($page){
         global $CFG,$lang,$admin_smarty;

   	     $this->sql_close();
            
         //Fetch from admin templates
         $main_content = $admin_smarty->fetch($page);
         $admin_smarty->assign("MAIN_CONTENT",$main_content);
         $admin_smarty->display('main.tpl');
    }
#-------------------------------------------------------------------------------------------------------------------
    function my_addslashes($value){
		
		 $mgq	= get_magic_quotes_gpc();
		 
		 if($mgq){
		 	 $string = $value;
		 }else{
		 	 $string = addslashes($value);
		 }//end if
         
         if(phpversion() >= '4.3.0') {
            $string = mysql_real_escape_string($value);
         }
         else{
            $string = mysql_escape_string($value);
         }
    	
         return htmlspecialchars($string); 
        
    }//end function
#-------------------------------------------------------------------------------------------------------------------
    function country_list($selname){
   	   global $CFG,$db_obj, $lang;

	    $sql = $db_obj->sql_query("SELECT * FROM ".$CFG['table']['country']."  ORDER BY name ASC");
        while($row = $db_obj->sql_fetchrow($sql)){

              if($selname == "$row[id]"){
                 $sel="selected";
              }
              else{
                 $sel="";
              }

                 $country = stripslashes($row[name]);
                 $var.="<option value='$row[id]' $sel>$country</option>";
       }

        return $var;
        
    }  
#-------------------------------------------------------------------------------------------------------------------

    function state_list($country_id,$state_id){
   	   global $CFG,$db_obj, $lang;

	    $sql = $db_obj->sql_query("SELECT * FROM {$CFG['table']['state']} WHERE country_id = '$country_id' ORDER BY state_name ASC");
        while($row = $db_obj->sql_fetchrow($sql)){

              if($state_id == "$row[id]"){
                 $sel="selected";
              }
              else{
                 $sel="";
              }
                 $state = stripslashes(trim($row[state_name]));
                 $var.="<option value='$row[id]' $sel>$state</option>";
       }
        return $var;  
    }
    
#-------------------------------------------------------------------------------------------------------------------
    function city_list($country_id,$state_id,$city_id){
   	   global $CFG,$db_obj, $lang;

	    $sql = $db_obj->sql_query("SELECT * FROM {$CFG['table']['city']} WHERE state_id = '$state_id' AND country_id = '$country_id' ORDER BY city_name ASC");
        while($row = $db_obj->sql_fetchrow($sql)){

              if($city_id == "$row[id]"){
                 $sel="selected";
              }
              else{
                 $sel="";
              }
                 $city = stripslashes(trim($row[city_name]));
                 $var.="<option value='$row[id]' $sel>$city</option>";
       }
        return $var;  
    }
    
#-------------------------------------------------------------------------------------------------------------------
    function city_list_find_others($country_id,$city_id){
   	   global $CFG,$db_obj, $lang;

	    $sql = $db_obj->sql_query("SELECT * FROM {$CFG['table']['city']} WHERE country_id = '$country_id' ORDER BY city_name ASC");
        while($row = $db_obj->sql_fetchrow($sql)){

              if($city_id == "$row[id]"){
                 $sel="selected";
              }
              else{
                 $sel="";
              }
                 $city = stripslashes(trim($row[city_name]));
                 $var.="<option value='$row[id]' $sel>$city</option>";
       }
        return $var;  
    }
#-------------------------------------------------------------------------------------------------------------------
    //Date options in dropdown menu
	function dd_list($sel_id) {
	 
		for($i=1;$i<=31;$i++)
		{
			if($i < 10) $i = '0'.$i;
            
			if ($sel_id == "$i"){
         		$var .= "<option value=\"$i\" selected =\"selected\">$i</option>";
         	}
         	else{
         		$var .= "<option value=\"$i\">$i</option>";		
         	}
			
	
		}
         	
    	return $var;
	}
#-------------------------------------------------------------------------------------------------------------------
	function dd_list_current($sel_id) {
	 
        if($sel_id == ""){
           $sel_id = date('d');
        }
        if($sel_id == '0'){
           $sel_zero = "selected =\"selected\"";
        }

           //$var .= "<option value=\"0\" $sel_zero>Date</option>";	
     
     
		for($i=1;$i<=31;$i++)
		{
			if($i < 10) $i = '0'.$i;
            
			if ($sel_id == "$i"){
         		$var .= "<option value=\"$i\" selected =\"selected\">$i</option>";
         	}
         	else{
         		$var .= "<option value=\"$i\">$i</option>";		
         	}
			
	
		}
         	
    	return $var;
	}

#-------------------------------------------------------------------------------------------------------------------
	//Month options in dropdown menu
	function mm_list($sel_id) {
		global $lang;
		
		for($i=1;$i<=12;$i++)
		{
		  
            if($i < 10) $i = "0".$i;
		  
			if($i=="1")
			{
				$month="$lang[January]";
			}
			elseif($i=="2")
			{
				$month="$lang[February]";
			}
			elseif($i=="3")
			{
				$month="$lang[March]";
			}
			elseif($i=="4")
			{
				$month="$lang[April]";
			}
			elseif($i=="5")
			{
				$month="$lang[May]";
			} 
			elseif($i=="6")
			{
				$month="$lang[June]";
			}
			elseif($i=="7")
			{
				$month="$lang[July]";
			}
			elseif($i=="8")
			{
				$month="$lang[August]";
			}
			elseif($i=="9")
			{
				$month="$lang[September]";
			}
			elseif($i=="10")
			{
				$month="$lang[October]";
			}
			elseif($i=="11")
			{
				$month="$lang[November]";
			}
			else
			{
				$month="$lang[December]";
			}
            
            
			
			if ($sel_id == "$i"){
         		$var .= "<option value=\"$i\" selected =\"selected\">$month</option>";
         	}
         	else{
         		$var .= "<option value=\"$i\">$month</option>";		
         	}	
		
			
		}
         	
    	return $var;
	}
#-------------------------------------------------------------------------------------------------------------------
	function mm_list_current($sel_id) {
	    global $lang;
       
		
        if($sel_id == ""){
           $sel_id = date('m');
        }
        if($sel_id == '0'){
           $sel_zero = "selected =\"selected\"";
        }

           //$var .= "<option value=\"0\" $sel_zero>Month</option>";	

		
		for($i=1;$i<=12;$i++)
		{
		  
            if($i < 10) $i = "0".$i;
            #if($i < 10) $i = "0$i";
           	
			if($i=="01")
			{
				$month="$lang[January]";
			}
			elseif($i=="02")
			{
				$month="$lang[February]";
			}
			elseif($i=="03")
			{
				$month="$lang[March]";
			}
			elseif($i=="04")
			{
				$month="$lang[April]";
			}
			elseif($i=="05")
			{
				$month="$lang[May]";
			}
			elseif($i=="06")
			{
				$month="$lang[June]";
			}
			elseif($i=="07")
			{
				$month="$lang[July]";
			}
			elseif($i=="08")
			{
				$month="$lang[August]";
			}
			elseif($i=="09")
			{
				$month="$lang[September]";
			}
			elseif($i=="10")
			{
				$month="$lang[October]";
			}
				elseif($i=="11")
			{
				$month="$lang[November]";
			}
				else
			{
				$month="$lang[December]";
			}
			
			if ($sel_id == "$i"){
         		$var .= "<option value=\"$i\" selected =\"selected\">$month</option>";
         	}
         	else{
         		$var .= "<option value=\"$i\">$month</option>";		
         	}	
		
			
		}
         	
    	return $var;
	}

#-------------------------------------------------------------------------------------------------------------------
	
	//Year options in dropdown menu
	function yy_list($sel_id) {
		
		$d  = date('Y');
        $fd = 1940; //date('Y')- 70;
		$dt = $d - 19;
		
		for($i=$fd;$i<=$dt;$i++)
		{
		if ($sel_id == "$i"){
         		$var .= "<option value=\"$i\" selected =\"selected\">$i</option>";
         	}
         	else{
         		$var .= "<option value=\"$i\">$i</option>";		
         	}
		}
         	
    	return $var;
	}
#-------------------------------------------------------------------------------------------------------------------
	function yy_list_current($sel_id) {

        if(!$sel_id){
            $sel_id = date('Y');
        }

        
		$d  = date('Y')+1;
        $fd = 1940; //date('Y')- 70;
		
		for($i=$fd;$i<=$d;$i++)
		{
		if ($sel_id == "$i"){
         		$var .= "<option value=\"$i\" selected =\"selected\">$i</option>";
         	}
         	else{
         		$var .= "<option value=\"$i\">$i</option>";		
         	}
		}
         	
    	return $var;
	}
#-------------------------------------------------------------------------------------------------------------------
	function yy_list_current_desc($sel_id) {

       if(!$sel_id){
           $sel_id = date('Y');
        }
		
		$d  = date('Y');
        $fd = 2010; //date('Y')- 70;
		
        for($i=$d;$i>=$fd;$i--)
		{
		    if($sel_id == "$i"){
         	   $var .= "<option value=\"$i\" selected =\"selected\">$i</option>";
         	}
         	else{
         	   $var .= "<option value=\"$i\">$i</option>";		
         	}
		}
         	
    	return $var;
	}
#-------------------------------------------------------------------------------------------------------------------
    function year_list_current($sel_id) {

        if(!$sel_id){
            $sel_id = date('Y');
        }

        
		$d  = date('Y')+1;
        $fd = date('Y');
		
		for($i=$fd;$i<=$d;$i++)
		{
		if ($sel_id == "$i"){
         		$var .= "<option value=\"$i\" selected =\"selected\">$i</option>";
         	}
         	else{
         		$var .= "<option value=\"$i\">$i</option>";		
         	}
		}
         	
    	return $var;
	}
#-------------------------------------------------------------------------------------------------------------------
    function checkDate($date){
        
        // Explode the date into meaningful variables
        list($Year,$Month,$Day) = explode("-", $date);
        if(checkdate($Month, $Day, $Year)){
            return true;
        }
        
    }
#-------------------------------------------------------------------------------------------------------------------
    function GetAge($Birthdate){
	
        // Explode the date into meaningful variables
        list($BirthYear,$BirthMonth,$BirthDay) = explode("-", $Birthdate);

        // Find the differences
        $dy = strlen($BirthDay);
		if($dy=="1"){
		   $BirthDay = "0".$BirthDay;	
		}
		else{
		   $BirthDay = $BirthDay;	
		}
        
		   $mth = strlen($BirthMonth);
		if($mth=="1"){
		   $BirthMonth = "0".$BirthMonth;	
		}
		else{
		   $BirthMonth = $BirthMonth;	
		}
		
        $YearDiff = date("Y") - $BirthYear;
        $MonthDiff = date("m") - $BirthMonth;

        $DayDiff = date("d") - $BirthDay;

        // If the birthday has not occured this year
        if($DayDiff < 0 or $MonthDiff < 0) {
          $age = $YearDiff--;
		}
		else{
		  $age = $YearDiff;
		}
	
       return $age;
   }
#-------------------------------------------------------------------------------------------------------------------
/**
 * @desc function to trigger page redirects when the session is timed out.
 *       The function is used at header location in after login pages 
 * @usage  header("Location:login.php?r=".redirectCurPage());
 * @param void()
 * @return The file name of the current page 
 */ 
function redirectAdminPage(){
    global $SiteObj;
   
    $redirectUrl = substr($_SERVER["REQUEST_URI"],strpos($_SERVER["REQUEST_URI"],'/',1)+1);
    $redirectUrl = substr($redirectUrl,strpos($redirectUrl,'/',1)+1);
    
    return $redirectUrl;
}
#-------------------------------------------------------------------------------------------------------------------
function checkAdminLogged(){
    global $CFG;
    
    if(!$_SESSION[faqs_admin_id] or $_SESSION[faqs_admin_id] == ""){
        
        $lRdUri = "login.php";
        //header("Location:{$CFG['site']['admin_url']}/$lRdUri?r=".$this->redirectAdminPage());
        header("Location:{$CFG['site']['admin_url']}/$lRdUri");
        exit();
    }
}
#-------------------------------------------------------------------------------------------------------------------
function redirectUserPage(){
    global $SiteObj;
   
    $redirectUrl = substr($_SERVER["REQUEST_URI"],strpos($_SERVER["REQUEST_URI"],'/',1)+1);
    return $redirectUrl;
}
#-------------------------------------------------------------------------------------------------------------------
function checkUserLogged(){
    global $SiteObj,$CFG,$url;
   
    if(!$_SESSION[prof_id] or $_SESSION[prof_id] == ""){
        
        header("Location:$url[index]?r=".$this->redirectUserPage());
        exit();
    }
}
#-------------------------------------------------------------------------------------------------------------------
function redirectErrPage(){
    global $CFG;

    if(isset($_SESSION['faqs_admin_id'])){
       header("Location: ".$CFG[site][base_url]."/admincp/404.html");
       exit; 
    }
    if(isset($_SESSION['faqs_prof_id'])){
       header("Location: ".$CFG[site][base_url]."/404.html");
       exit; 
    }

    //General
     header("Location: ".$CFG[site][base_url]."/404.html");
     exit;
       
}
#-------------------------------------------------------------------------------------------------------------------
function frontEndFilters($key,$value) {
    global $CFG,$admin_smarty,$client_smarty,$SiteObj;

    /*
    sd'fs'dfs'df'S"DS"dfDS""SD'fie29874<>?:L:":{}|*&(*&(@#$$_)+&
    SD'f"i29<>?:*&+
    s'f'd'f'S"S"df'<>?:L:":{@#$_)+
    */    
    $admin_smarty->assign($key,trim(stripslashes($this->my_addslashes(trim($value)))));
    //$client_smarty->assign($key,trim(stripslashes($this->my_addslashes(trim($value)))));
}
#-------------------------------------------------------------------------------------------------------------------
function frontEndFilter($value) {
    global $CFG,$admin_smarty,$client_smarty,$SiteObj;

    /*
    sd'fs'dfs'df'S"DS"dfDS""SD'fie29874<>?:L:":{}|*&(*&(@#$$_)+&
    SD'f"i29<>?:*&+
    s'f'd'f'S"S"df'<>?:L:":{@#$_)+
    */    
    return trim(stripslashes($this->my_addslashes(trim($value))));
}

#-----------------------------------------------------------------------------------------------------------------

    function generate_thumbnail($width,$height,$source_path,$dest_path){
        global $CFG,$lang;
        
        $imgObj = new Zubrag_image;

        $site_timage_width	= (int)$width; 
        $site_timage_height	= (int)$height; 
          
        $srcpath  = $source_path;
        $destpath = $dest_path;
	         
        #initialize
        $imgObj->max_x        = $site_timage_width;
        $imgObj->max_y        = $site_timage_height;
        $imgObj->cut_x        = 0;
        $imgObj->cut_y        = 0;
        $imgObj->quality      = 100;
        $imgObj->save_to_file = 1;
        $imgObj->image_type   = -1;

        #Generate Thumbnail
        $imgObj->GenerateThumbFile($srcpath, $destpath);
        @chmod($destpath,0777);
    }
#-------------------------------------------------------------------------------------------------------------------
//integer number
function numeric_check($string){
     global $db_obj,$CFG,$admin_smarty,$lang;
    
     $ln = strlen($string);
    
     for($i=0;$i<$ln;$i++){
         $val = trim(substr($string, $i, 1));
         if(is_numeric($val)){
            $fin_val .= $val; 
         }
         else{
            $error = "Yes";
        }
    }
    
    if($error == "Yes"){
        
       $error_vul = " <b> {$lang['WARNING_YOUR_GIVEN_VALUE_NOT_AN_INTEGER']} </b>"; 

       $errortemplates = $db_obj->readfilecontent($CFG[site][usertpl_path]."/error.tpl");
       $errortemplates = str_replace("{ERROR_MESSAGES}",$error_vul,$errortemplates);

       $admin_smarty->assign("error_temp",$errortemplates);
       $admin_smarty->assign("VULNARABILITY_ERROR",$error_vul); 

    }
   
    return $fin_val;
 
}
#-------------------------------------------------------------------------------------------------------------------
// alphabet 
function alpha_check($string){
    global $db_obj,$CFG,$admin_smarty,$lang;
    
     $ln = strlen($string);
    
    for($i=0;$i<$ln;$i++){
         $val = trim(substr($string, $i, 1));
         if(ctype_alpha($val)){
            $fin_val .= $val; 
        }
        else
        {
             $error = "Yes";
        }
    }
   
    if($error == "Yes"){
        
       $error_vul = " {$lang['WARNING_YOUR_GIVEN_VALUE_NOT_AN_AZ']} "; 

       $errortemplates = $db_obj->readfilecontent($CFG[site][usertpl_path]."/error.tpl");
       $errortemplates = str_replace("{ERROR_MESSAGES}",$error_vul,$errortemplates);

       $admin_smarty->assign("error_temp",$errortemplates);
       $admin_smarty->assign("VULNARABILITY_ERROR",$error_vul); 
        
    }
   
   
    return $fin_val;
 
}
#-------------------------------------------------------------------------------------------------------------------
function error_message_validations($suc_no,$error_msg_array,$page_info_text){
    global $db_obj,$CFG,$admin_smarty,$lang;
 
    $suc_no  = $this->numeric_check($suc_no);
    if(!in_array($suc_no, $error_msg_array)) {
        header("Location: ".$CFG[site][base_url]."/404.html");
    }
    
    if($suc_no == 101) $succ_msg = "{$lang['New']} `$page_info_text` {$lang['added_successfully']}";
    if($suc_no == 102) $succ_msg = "{$lang['Selected']} `$page_info_text` {$lang['deleted_successfully']}";
    if($suc_no == 103) $succ_msg = "{$lang['Selected']} `$page_info_text` {$lang['updated_ Successfully']}";
    if($suc_no == 104) $succ_msg = "{$lang['Selected']} `$page_info_text` {$lang['activated_Successfully']}";
    if($suc_no == 105) $succ_msg = "{$lang['Selected']} `$page_info_text` {$lang['suspended_Successfully']}";
    if($suc_no == 106) $succ_msg = "{$lang['Selected']} `$page_info_text` {$lang['applied_Successfully']}";
    if($suc_no == 107) $succ_msg = "{$lang['Selected']} `$page_info_text` {$lang['canceled_Successfully']}";
    if($suc_no == 108) $succ_msg = "{$lang['Selected']} `$page_info_text` {$lang['approved_Successfully']}";
    if($suc_no == 109) $succ_msg = "{$lang['Your']} `$page_info_text` {$lang['has_been_sent_Successfully']}";
    
    if($suc_no == 203) $succ_msg = "{$lang['Your']} `$page_info_text` {$lang['has_been_updated_Successfully']}";
    if($suc_no == 204) {
       
       if($_SESSION['import_user'] > 0){
          $succ_msg = "CSV file imported successfully and {$_SESSION['import_user']} User(s) Newly Added ";
       }else{
          $succ_msg = "CSV file imported successfully but no user  Newly added ";
       } 
       
    }   
    
    if($suc_no == 205) {
       $succ_msg = "Selected User(s) Password has been reset and mail has been sent Successfully"; 
    }   
    if($suc_no == 206) {
       $succ_msg = "Selected User(s) has been resigned Successfully"; 
    }   
    
    
    return $succ_msg;   
}    
#-------------------------------------------------------------------------------------------------------------------
function date_validations($yy=0,$mm=0,$dd=0,$info='Date of birth'){
    global $db_obj,$CFG,$admin_smarty,$lang;


       if($yy == "0" and $mm == "0" and $dd == "0"){
	       $err_msg  .= "&nbsp;&nbsp;{$lang['Select']} $info <br/>";  
      	}
        elseif(isset($dd) && $dd=='0'){
           $err_msg  .= "&nbsp;&nbsp;{$lang['Select_Date_For']} $info <br/>";               
        }
        elseif(isset($mm) && $mm=='0'){
           $err_msg .= "&nbsp;&nbsp;{$lang['Select_Month_For']} $info <br/>";                
        }
        elseif(isset($yy) && $yy=='0'){
           $err_msg .= "&nbsp;&nbsp;{$lang['Select_Year_For']} $info <br/>";               
        }
       
        #leap year
        elseif($yy != "0") {
         if($yy % 4==0 && $mm==2){
            
            if($yy % 100==0){
               if($yy % 400==0){
                  $nodays = 29;
               }
               else{
                  $nodays = 28;
               }
            }
            else{
                  $nodays = 29;
            }
            
            if($dd >$nodays) {
			   $err_msg  .= "&nbsp;&nbsp;{$lang['Select_Valid_Date_For']} $info <br/>";   
		    }
       }
       else{
        
               $nodays = 28;
               if(($mm==4) || ($mm==6) || ($mm==9) || ($mm==11)){
                   if($dd>30){
			          $err_msg  .= "&nbsp;&nbsp;{$lang['Select_Valid_Date_For']} $info <br/>";
		           }
               }
               elseif($mm==2){
                   if($dd>$nodays) {
			          $err_msg  .= "&nbsp;&nbsp;{$lang['Select_Valid_Date_For']} $info <br/>";
		           }
               }
               else{
                   if($dd>31){
			          $err_msg  .= "&nbsp;&nbsp;{$lang['Select_Valid_Date_For']} $info <br/>";
		           }
               }
        
        }
       }
    
    #zen('Res'.$err_msg);
    
    return $err_msg;    
 }
#-------------------------------------------------------------------------------------------------------------------
 function validatePhone($string) {
	$numbersOnly = ereg_replace("[^0-9]", "", $string);
	$numberOfDigits = strlen($numbersOnly);
	if ($numberOfDigits == 8 or $numberOfDigits == 10) {
		return 0;
	} else {
		return 1;
	}
 }
#-------------------------------------------------------------------------------------------------------------------
 function validateFax($string) {
	$numbersOnly = ereg_replace("[^0-9]", "", $string);
	$numberOfDigits = strlen($numbersOnly);
	if ($numberOfDigits == 8) {
		return 0;
	} else {
		return 1;
	}
 }
#-------------------------------------------------------------------------------------------------------------------
function show_language_list(){
       global $CFG,$db_obj,$admin_smarty;
    
       #LANGUAGE SETTINGS FOR DISPLAY
        $lang_sql = "SELECT * FROM {$CFG['table']['language_type']} WHERE status = '1' ORDER BY id ASC";
        $lang_res = $db_obj->sql_query($lang_sql);
        $lang_num = $db_obj->sql_numrows(); 
        
        if($lang_num > 0){
            while($lan_dat = mysql_fetch_array($lang_res)){
            
                $lang_dat[id]    = trim(stripslashes($lan_dat[id]));
                $lang_dat[name]  = ucfirst(trim(stripslashes($lan_dat[name])));
            
                $lang_details[] = $lang_dat;
            } 

            $admin_smarty->assign("lang_details",$lang_details);
        }
    
}

#-------------------------------------------------------------------------------------------------------------------

function language_choose(){
    global $language_process,$CFG,$db_obj,$admin_smarty,$client_smarty;
    
    $defalut_lang_name = "English";
    require_once $CFG[site][base_path]."/include/languages/$defalut_lang_name/lang_main.php";
    /*
    #DEFALT LANGUAGE SETTINGS 
    if( ($_SESSION[cur_lang_id] > 1) && ($_SESSION[cur_lang_name] <> "English") ) {         
         
         if($language_process == "FILE"){
            require_once $CFG[site][base_path]."/include/languages/{$_SESSION[cur_lang_name]}/lang_main.php";
         }

         if($language_process == "TABLE"){
            
            //TABLE PROCESS
            $lan_sel = "SELECT * FROM {$CFG['table']['language_process']} WHERE lang_id = '{$_SESSION[cur_lang_id]}' ORDER BY id ASC";
            $lan_res = $db_obj->sql_query($lan_sel);
            $lan_num = $db_obj->sql_numrows();
        
            if($lan_num > 0){
                
               while($lan_datax = mysql_fetch_array($lan_res)){
                
                     $key_name        = trim(stripslashes($lan_datax[lang_key]));
                     $value_name      = trim(stripslashes($lan_datax[lang_value]));

                     $lang[$key_name] = $value_name;
            
               }//end-=while
               
                     $_SESSION[lang]  = $lang;
                     
            }//end-if
            
         }//TABLE FORMAT
         
    }
    else{         
        
          $defalut_lang_id   = $CFG['site']['wp_default_lang_id'];
          $defalut_lang_name = $db_obj->get_value($CFG['table']['language_type'],"name","id = '$defalut_lang_id' "); 
        

          if($language_process == "FILE"){
             require_once $CFG[site][base_path]."/include/languages/$defalut_lang_name/lang_main.php";
          } 

         if($language_process == "TABLE"){
            
            //TABLE PROCESS
            $lan_sel = "SELECT * FROM {$CFG['table']['language_process']} WHERE lang_id = '$defalut_lang_id' ORDER BY id ASC";
            $lan_res = $db_obj->sql_query($lan_sel);
            $lan_num = $db_obj->sql_numrows();
        
            if($lan_num > 0){

               while($lan_datax = mysql_fetch_array($lan_res)){
                
                     $key_name   = trim(stripslashes($lan_datax[lang_key]));
                     $value_name = trim(stripslashes($lan_datax[lang_value]));

                     $lang[$key_name] = $value_name;
            
               }//end-=while 
               
                     #zen($lang,1);
                     $_SESSION[lang]  = $lang;
                     
                     
               
            }//end-if
            
         }//TABLE FORMAT
          
         $_SESSION[cur_lang_id]   = $defalut_lang_id;             
         $_SESSION[cur_lang_name] = $defalut_lang_name;
            
    }//END-else
    */
         $client_smarty->assign('lang',$lang);
         $admin_smarty->assign('lang',$lang);
         //return $_SESSION[lang];
         return $lang;
  }//end-funtion

#-------------------------------------------------------------------------------------------------------------------


     function choose_language_options($selname="1"){
   	   global $CFG,$db_obj, $lang;

	    $sql = $db_obj->sql_query("SELECT * FROM ".$CFG['table']['language_type']." WHERE status = '1' ORDER BY id ASC");
        while($row = $db_obj->sql_fetchrow($sql)){

              if($selname == "$row[id]"){
                 $sel="selected";
              }
              else{
                 $sel="";
              }
              
              $id   = $row[id];
              $name = ucfirst(stripslashes($row[name]));
              $var.="<option value='$id' $sel>$name</option>";
                 
       }

        return $var;
   }

#-------------------------------------------------------------------------------------------------------------------

     function choose_timezone_options($selname="28"){
   	   global $CFG,$db_obj, $lang;

	    $sql = $db_obj->sql_query("SELECT * FROM ".$CFG['table']['gmt_zones']." WHERE status = '1' ORDER BY id ASC");
        while($row = $db_obj->sql_fetchrow($sql)){

              if($selname == "$row[id]"){
                 $sel="selected";
              }
              else{
                 $sel="";
              }
              
              $id     = $row[id];
              
              $gmt      = "(&nbsp;".stripslashes($row[gmt])."&nbsp;)";
              $others   = stripslashes($row[others]);
              $show_val = $gmt ."&nbsp;".$others; 
              
              
              $var.="<option value='$id' $sel>$show_val</option>";
                 
       }

        return $var;
   }
#-------------------------------------------------------------------------------------------------------------------
    public function dateDifference($big_day,$small_day) {
	$diff = strtotime($big_day) - strtotime($small_day);

	$sec   = $diff % 60;
	$diff  = intval($diff / 60);
	$min   = $diff % 60;
	$diff  = intval($diff / 60);
	$hours = $diff % 24;
	$days  = intval($diff / 24);
    
    #return $days+1;
    return $days;
	#return array($sec,$min,$hours,$days);
}
#-------------------------------------------------------------------------------------------------------------------
    function show_msg_board_admin($show_msg,$msg_type='ss'){ //ss,info,error,warning,note
    global $CFG,$lang,$admin_smarty;


         if($msg_type == 'ss'){
            $succtemplates = $this->readfilecontent($CFG[site][admintpl_path]."/success.tpl");
            $succtemplates = str_replace("{SUCCESS}",$show_msg,$succtemplates);
            $admin_smarty->assign("succ_temp",$succtemplates);
            $admin_smarty->assign("success",$show_msg);

         } 

         if($msg_type == 'info'){
            $infotemplates = $this->readfilecontent($CFG[site][admintpl_path]."/info.tpl");
            $infotemplates = str_replace("{INFO}",$show_msg,$infotemplates);
            $admin_smarty->assign("info_temp",$infotemplates);
            $admin_smarty->assign("info_msg",$show_msg);

         } 

         if($msg_type == 'error'){
            $errortemplates = $this->readfilecontent($CFG[site][admintpl_path]."/error.tpl");
            $errortemplates = str_replace("{ERROR}",$show_msg,$errortemplates);
            $admin_smarty->assign("error_temp",$errortemplates);
            $admin_smarty->assign("error",$show_msg);

         }   
 
         if($msg_type == 'warning'){
            $errortemplates = $this->readfilecontent($CFG[site][admintpl_path]."/warning.tpl");
            $errortemplates = str_replace("{WARNING}",$show_msg,$errortemplates);
            $admin_smarty->assign("warning_temp",$errortemplates);
            $admin_smarty->assign("warning",$show_msg);

         }  
    return;

    }
#-------------------------------------------------------------------------------------------------------------------
    function data_mgmt_dropdown($table,$sel_value,$where="1"){
        global $CFG,$lang;
        
        $table_name = $CFG['db']['table_prefix'].$table;
        
        $dd_sql = "SELECT * FROM {$table_name} WHERE $where";
        $dd_res = mysql_query($dd_sql) or die(mysql_error());
        $dd_num = mysql_num_rows($dd_res);
        
        if($dd_num > 0){
            while($dd_dat = mysql_fetch_array($dd_res)){
                
                $dd_dat = array_map('stripslashes',$dd_dat);
                $dd_dat = array_map('trim',$dd_dat);
                
                if($dd_dat[id] == $sel_value){
                    $sel = "selected = 'selected'";
                }else{
                    $sel = "";
                }
                
                $option .= "<option value='$dd_dat[id]' $sel>".ucfirst($dd_dat[name])."</option>";
            }
        }
        
        return $option;
    }
#-------------------------------------------------------------------------------------------------------------------
    function url_validate($url){
        /*
        if(!preg_match("#^http(s)?://www\.[a-z0-9-_.]+\.[a-z]{2,4}$#i",$url)){
            $result = "invalid";
        }
        else{
            $result = "valid";
        }*/
        if(preg_match("#^http(s)?://www\.[a-z0-9-_.\-]+\.[a-z]{2,4}$#i",$url)){
            $result = "valid";
        }
        else{
            $result = "invalid";
        }        
       return $result;  
    }
    
#-------------------------------------------------------------------------------------------------------------------
    function url_validation($link){
        
        if(!preg_match("#^http(s)?://www\.[a-z0-9-_.\-]+\.[a-z]{2,4}$#i",$url)){
            $result = "invalid";
        } 
        else{
            $result = "valid";
        }

        return $result;
    }
#-------------------------------------------------------------------------------------------------------------------
    
    function imageCheck($SourceFile){
   
        $ch = curl_init();
   
        curl_setopt ($ch, CURLOPT_URL, $SourceFile);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 0);
        $fileContents = curl_exec($ch);
        curl_close($ch);
        $newImg = imagecreatefromstring($fileContents);
    
        if($newImg == ''){
            $var ="invalid";
        }
        else{
            $var ="valid";
            
        }
        curl_close($ch);
        return $newImg;
    }
#-------------------------------------------------------------------------------------------------------------------
    function data_mgmt_checkbox($table,$checkbox_name,$limit,$checked_options){
        global $CFG,$lang;
        
        $checked_array = explode(",",$checked_options);
        
        $table_name = $CFG['db']['table_prefix'].$table;
        
        $cc_sql = "SELECT * FROM {$table_name} WHERE 1";
        $cc_res = mysql_query($cc_sql)or die(mysql_error());
        $cc_num = mysql_num_rows($cc_res);
        
        $check_result .="<div class='formRight'>";
        
            $i=1;
            while($cc_dat = mysql_fetch_array($cc_res)){
                
                $cc_dat = array_map('stripslashes',$cc_dat);
                $cc_dat = array_map('trim',$cc_dat);
                
                $checked ="";
                if(in_array($cc_dat[id],$checked_array)){
                    $checked = "checked='checked'";
                }
                
                //$width = floor(100/$limit);

                $check_result .="
                    <input type='checkbox' name='$checkbox_name' value='$cc_dat[id]' $checked id=\"$checkbox_name$cc_dat[id]\"/><label for='$checkbox_name$cc_dat[id]' style='width:25%;'>$cc_dat[name]</label>
                ";
       
                if($i%$limit==0 or $i%$cc_num==0){
                    $check_result .="</div><div class='clear'></div>"; 
                }
                if($i%$limit==0 and $i!=$cc_num){
                    $check_result .="<div class='formRight'>"; 
                }     
            $i++;
            }
    
            return $check_result;  
    }
#-------------------------------------------------------------------------------------------------------------------
    function height_feet($sel_val){
        
        for($i=3;$i<=8;$i++){
            
            if($sel_val == $i){
                $sel = "selected='selected'";
            }
            else{
                $sel = "";
            }
            
            $res .= "<option value='$i' $sel>$i FT</option>";
        }
        return $res;
    }
#-------------------------------------------------------------------------------------------------------------------
    function height_inches($sel_val){
        
        for($i=1;$i<=11;$i++){
            
            if($sel_val == $i){
                $sel = "selected='selected'";
            }
            else{
                $sel = "";
            }
            
            $res .= "<option value='$i' $sel>$i IN</option>";
        }
        return $res;
    }
#-------------------------------------------------------------------------------------------------------------------
    function user_thumb_image($id){
        global $CFG,$lang;
        
        $image_name = $this->getvalue("{$CFG['table']['profile_image']}","image_name","prof_id = '$id' AND primary_image = '1'");
        if($image_name != ""){
            $result = "{$CFG['site']['prof_img_thumb_url']}/$image_name";
        }
        else{
            $result = "{$CFG['site']['prof_img_thumb_url']}/no.jpg";
        }
        
        return $result;
    }
#-------------------------------------------------------------------------------------------------------------------
    
    function get_flag($country_id){
        global $CFG,$lang;
        
        $count_code = $this->getvalue("{$CFG['table']['country']}","country_code","id = '$country_id'");
        $flag_url   = "{$CFG['site']['base_url']}/images/flags/$count_code.png";
        
        return $flag_url;
    }
#-------------------------------------------------------------------------------------------------------------------
    function get_country_name($country_id){
        global $CFG,$lang;
        
        $country_name = $this->getvalue("{$CFG['table']['country']}","name","id = '$country_id'");
        
        return ucfirst($country_name);
    }
#-------------------------------------------------------------------------------------------------------------------
    
    public function dateToLocal($date,$format,$tz=1) {
        global $CFG,$lang;
     
        if($format == '') $format = 'M j,Y';
     
        //server time converted to local time zone
        $gmt = new Gmt();
     	$gmt->setZoneId($CFG['site']['wp_display_timezone']);
	    $gmt->setDateToConvert($date);
        $new_time = $gmt->getByGMT();

        //Date-time-convertor
        if($tz == 1){
           $original_date = $new_time;
        }else{
           $original_date = $date; 
        }   
        $origal_mask   = 'Y-m-d H:i:s';
        $new_mask      = $format; 
    
        $obj = new DateTimeConverter($original_date, $origal_mask);
        $formatted_date = $obj->convert($new_mask);
         
        
        return $formatted_date;
    }
#-------------------------------------------------------------------------------------------------------------------
    public function page_navigator($page, $tpages, $reload, $addn_params) {
	   global $CFG;
       
	   $firstlabel = "&laquo;";// first page
	   $prevlabel  = "&lsaquo;&nbsp;";// previous page
	   $nextlabel  = "&nbsp;&rsaquo;";// next page
	   $lastlabel  = "&raquo;";// last page
	
        $adjacents  = 2;
	
	   // first
	   if($page>1) {
	   	   $out.= "<li><a href=\"{$CFG['site']['base_url']}/" . $reload . "?page=1" .$addn_params.  "\" title=\"First\">" . $firstlabel . "</a></li>\n";
	   }
	   else {
		  //$out.= "<li><a>" . $firstlabel . "</a></li>";
	   }
	
	   // previous
	   if($page==1) {
		  //$out.= "<li><a>" . $prevlabel . "</a></li>";
	   }
       elseif($page==2) {
          $out.= "<li><a href=\"{$CFG['site']['base_url']}/" . $reload . "?page=1" . $addn_params . "\" title=\"Previous\">" . $prevlabel . "</a></li>\n";
	   }
	   else {
		  $out.= "<li><a href=\"{$CFG['site']['base_url']}/" . $reload . "?page=" . ($page-1) . $addn_params . "\" title=\"Previous\">" . $prevlabel . "</a></li>\n";
	   }
	
	   // 1 2 3 4 etc
	   $pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
	   $pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
	   for($i=$pmin; $i<=$pmax; $i++) {
		  if($i==$page) {
			 $out.= "<li><a class=\"current\" title=\"\">" . $i . "</a></li>\n";
		  }
		  elseif($i==1) {
			 $out.= "<li><a href=\"{$CFG['site']['base_url']}/" . $reload . "?page=". $i . $addn_params . "\" title=\"\">" . $i . "</a></li>\n";
		  }
		  else {
			 $out.= "<li><a href=\"{$CFG['site']['base_url']}/" . $reload . "?page=" . $i . $addn_params . "\" title=\"\">" . $i . "</a></li>\n";
		  }
	   }
	
	   // next
	   if($page<$tpages) {
		  $out.= "<li><a href=\"{$CFG['site']['base_url']}/" . $reload . "?page=" .($page+1) . $addn_params . "\" title=\"Next\">" . $nextlabel . "</a></li>\n";
	   }
	   else {
		  //$out.= "<li><a>" . $nextlabel . "</a></li>";
	   }
	
	   // last
	   if($page<$tpages) {
		  $out.= "<li><a href=\"{$CFG['site']['base_url']}/" . $reload . "?page=" . $tpages . $addn_params . "\" title=\"Last\">" . $lastlabel . "</a></li>\n";
	   }
	   else {
		  //$out.= "<li><a>" . $lastlabel . "</a></li>";
	   }
	
	   return $out;
    }

#-------------------------------------------------------------------------------------------------------------------
    public function admin_page_navigator($page, $tpages, $reload, $addn_params) {
	   global $CFG;
       
	   $firstlabel = "&laquo;";// first page
	   $prevlabel  = "&lsaquo;&nbsp;";// previous page
	   $nextlabel  = "&nbsp;&rsaquo;";// next page
	   $lastlabel  = "&raquo;";// last page
	
        $adjacents  = 2;
	
	   // first
	   if($page>1) {
	   	   $out.= "<li><a href=\"{$CFG['site']['admin_url']}/" . $reload . "?page=1" .$addn_params.  "\" title=\"First\">" . $firstlabel . "</a></li>\n";
	   }
	   else {
		  //$out.= "<li><a>" . $firstlabel . "</a></li>";
	   }
	
	   // previous
	   if($page==1) {
		  //$out.= "<li><a>" . $prevlabel . "</a></li>";
	   }
       elseif($page==2) {
          $out.= "<li><a href=\"{$CFG['site']['admin_url']}/" . $reload . "?page=1" . $addn_params . "\" title=\"Previous\">" . $prevlabel . "</a></li>\n";
	   }
	   else {
		  $out.= "<li><a href=\"{$CFG['site']['admin_url']}/" . $reload . "?page=" . ($page-1) . $addn_params . "\" title=\"Previous\">" . $prevlabel . "</a></li>\n";
	   }
	
	   // 1 2 3 4 etc
	   $pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
	   $pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
	   for($i=$pmin; $i<=$pmax; $i++) {
		  if($i==$page) {
			 $out.= "<li><a class=\"current\" title=\"\">" . $i . "</a></li>\n";
		  }
		  elseif($i==1) {
			 $out.= "<li><a href=\"{$CFG['site']['admin_url']}/" . $reload . "?page=". $i . $addn_params . "\" title=\"\">" . $i . "</a></li>\n";
		  }
		  else {
			 $out.= "<li><a href=\"{$CFG['site']['admin_url']}/" . $reload . "?page=" . $i . $addn_params . "\" title=\"\">" . $i . "</a></li>\n";
		  }
	   }
	
	   // next
	   if($page<$tpages) {
		  $out.= "<li><a href=\"{$CFG['site']['admin_url']}/" . $reload . "?page=" .($page+1) . $addn_params . "\" title=\"Next\">" . $nextlabel . "</a></li>\n";
	   }
	   else {
		  //$out.= "<li><a>" . $nextlabel . "</a></li>";
	   }
	
	   // last
	   if($page<$tpages) {
		  $out.= "<li><a href=\"{$CFG['site']['admin_url']}/" . $reload . "?page=" . $tpages . $addn_params . "\" title=\"Last\">" . $lastlabel . "</a></li>\n";
	   }
	   else {
		  //$out.= "<li><a>" . $lastlabel . "</a></li>";
	   }
	
	   return $out;
    }
#-------------------------------------------------------------------------------------------------------------------
    // convert a date into a string that tells how long ago that date was.... eg: 2 days ago, 3 minutes ago.
    public function time_since($d) {
	   $c = getdate();
	   $p = array('year', 'mon', 'mday', 'hours', 'minutes', 'seconds');
	   $display = array('year', 'month', 'day', 'hour', 'minute', 'second');
	   $factor = array(0, 12, 30, 24, 60, 60);
	   $d = $this->datetoarr($d);
	   for ($w = 0; $w < 6; $w++) {
		  if ($w > 0) {
			 $c[$p[$w]] += $c[$p[$w-1]] * $factor[$w];
			 $d[$p[$w]] += $d[$p[$w-1]] * $factor[$w];
		  }
		  if ($c[$p[$w]] - $d[$p[$w]] > 1) { 
			 return ($c[$p[$w]] - $d[$p[$w]]).' '.$display[$w].'s ago';
		  }
	   }
	   return '';
    }

    // you can replace this if need be. This converts my dates returned from a mysql date string into 
    //   an array object similar to that returned by getdate().
    public function datetoarr($d) {
	   preg_match("/([0-9]{4})(\\-)([0-9]{2})(\\-)([0-9]{2}) ([0-9]{2})(\\:)([0-9]{2})(\\:)([0-9]{2})/", $d, $matches);
        return array( 
		  'seconds' => $matches[10], 
		  'minutes' => $matches[8], 
		  'hours' => $matches[6],  
		  'mday' => $matches[5], 
		  'mon' => $matches[3],  
		  'year' => $matches[1], 
	   );
    }
#-------------------------------------------------------------------------------------------------------------------
    public function is_blocked($blocked,$blocked_by){
        global $CFG,$lang;
        
            $sql = "SELECT * FROM {$CFG['table']['user_blocked']} WHERE blocked_by = '$blocked_by' AND blocked_id = '$blocked' ";
            $res = $this->sql_query($sql);
            $num = mysql_num_rows($res);
            
            return $num;
    }
#-------------------------------------------------------------------------------------------------------------------
    function inner_category_checkbox($table,$checkbox_name,$limit,$checked_options){
        global $CFG,$lang;
        
        $checked_array = explode(",",$checked_options);
        
        $table_name = $CFG['db']['table_prefix'].$table;
        
        $cc_sql = "SELECT * FROM {$table_name} WHERE 1";
        $cc_res = mysql_query($cc_sql)or die(mysql_error());
        $cc_num = mysql_num_rows($cc_res);
        
        $check_result .="<div class='formRight'>";
        
            $i=1;
            while($cc_dat = mysql_fetch_array($cc_res)){
                
                $cc_dat = array_map('stripslashes',$cc_dat);
                $cc_dat = array_map('trim',$cc_dat);
                
                $checked ="";
                if(in_array($cc_dat[id],$checked_array)){
                    $checked = "checked='checked'";
                }
                
                //$width = floor(100/$limit);

                $check_result .="
                    <div id=\"uniform-check1\" class=\"checker\"><span><input style=\"opacity: 0;\" type='checkbox' name='$checkbox_name' value='$cc_dat[id]' $checked/></span></div><label style='width:25%;'>$cc_dat[name]</label>
                ";
       
                if($i%$limit==0 or $i%$cc_num==0){
                    $check_result .="</div><div class='clear'></div>"; 
                }
                if($i%$limit==0 and $i!=$cc_num){
                    $check_result .="<div class='formRight'>"; 
                }     
            $i++;
            }
    
            return $check_result;  
    }
#-------------------------------------------------------------------------------------------------------------------
    function my_connection_users(){
        global $CFG,$lang;
        
        $sql = "SELECT * FROM {$CFG['table']['connections']} WHERE from_id = '$_SESSION[prof_id]' OR to_id = '$_SESSION[prof_id]'";
        $res = $this->sql_query($sql);
        $num = mysql_num_rows($res);
        
        if($num > 0){
            while($dat = mysql_fetch_array($res)){
                
                if($_SESSION[prof_id] == $dat[to_id]){
                    $dat[conn_id] = $dat[from_id];
                }
                elseif($_SESSION[prof_id] == $dat[from_id]){
                    $dat[conn_id] = $dat[to_id];
                }
                
                $conn .= $dat[conn_id].",";
            }
            
        }
        return trim($conn,",");
    }
#-------------------------------------------------------------------------------------------------------------------
    function get_http_referer(){
        $bb = basename($_SERVER['HTTP_REFERER']);
        $ss = explode('?',$bb);
        return $ss[0];
    }
#-------------------------------------------------------------------------------------------------------------------
    
    function curlCheck($SourceFile) {
   
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $SourceFile);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 0);
        $fileContents = curl_exec($ch);
        curl_close($ch);
    
        return $fileContents;
    }
#-------------------------------------------------------------------------------------------------------------------
    function get_username($id){
        global $CFG;
        
        $sql = "SELECT first_name,last_name,group_name,organization_name,business_name,user_type FROM {$CFG['table']['profile']} WHERE prof_id = '$id'";
        $res = mysql_query($sql) or die(mysql_error());
        $dat = mysql_fetch_array($res);
        
        $dat = array_map('stripslashes',$dat);
        $dat = array_map('trim',$dat);
        $dat = array_map('ucfirst',$dat);
        
        if($dat[user_type] == "P"){
            $user[firstname]  = $dat[first_name];
            $user[lastname]   = $dat[last_name];
            $user[fullname]   = $user[firstname]." ".$user[lastname];
        }
        elseif($dat[user_type] == "O"){
            $user[firstname]   = $dat[organization_name];
            $user[lastname]    = $dat[organization_name];
            $user[fullname]    = $dat[organization_name];
        }
        elseif($dat[user_type] == "G"){
            $user[firstname]   = $dat[group_name];
            $user[lastname]    = $dat[group_name];
            $user[fullname]    = $dat[group_name];
        }
        elseif($dat[user_type] == "B"){
            $user[firstname]   = $dat[business_name];
            $user[lastname]    = $dat[business_name];
            $user[fullname]    = $dat[business_name];
        }
        
        return $user;
    } 
#-------------------------------------------------------------------------------------------------------------------
    function amount_format($amount){
        global $CFG;
        
        $format = number_format(round($amount, 2), 2, '.', '');
        
        return $format;
    }
#-------------------------------------------------------------------------------------------------------------------
    function zipDownload($file_names,$archive_file_name,$file_path){
    
	   $zip = new ZipArchive();
	   //create the file and throw the error if unsuccessful
	   if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
    	   exit("cannot open <$archive_file_name>\n");
	   }
	   //add each files of $file_name array to archive
       
	   foreach($file_names as $files){
  		    if(!$zip->addFile($file_path.$files,$files)){
  		        echo "failed";exit;
  		    }
	   }exit;
	   $zip->close();
	   //then send the headers to foce download the zip file
	   header("Content-type: application/zip"); 
	   header("Content-Disposition: attachment; filename=$archive_file_name"); 
       header("Pragma: no-cache"); 
	   header("Expires: 0"); 
	   readfile("$archive_file_name");
	   exit;
    }

#Delete the unpurchased products from shop_cart table after 30 minutes ---------------------------------------------
    
    function delete_temp_cart(){
        global $CFG;
        
        $tm     = 1800; //1800 = 1800 seconds(that is 30 minutes)
        $sql    = "DELETE FROM {$CFG['table']['shopping_cart']} WHERE payment_status = '0' AND (TIME_TO_SEC(TIMEDIFF(NOW(),purchase_date)) > $tm )";
        $res    = $this->sql_query($sql);

        return;
    }
    
#-------------------------------------------------------------------------------------------------------------------
    function left_side_connections(){
        global $CFG;
        
        $UserProfileObj = new UserProfile();
        
        $sql = "SELECT * FROM {$CFG['table']['connections']} WHERE from_id = '$_SESSION[prof_id]' OR to_id = '$_SESSION[prof_id]' ORDER BY RAND() LIMIT 4";
        $res = mysql_query($sql) or die(mysql_error());
        $num = mysql_num_rows($res);
        
        if($num > 0){
            
            while($dat = mysql_fetch_array($res)){
                
                if($_SESSION[prof_id] == $dat[to_id]){
                    $dat[conn_id] = $dat[from_id];
                }
                elseif($_SESSION[prof_id] == $dat[from_id]){
                    $dat[conn_id] = $dat[to_id];
                }
                 
                $user_details  = $UserProfileObj->get_user_profile_details($dat[conn_id]);
                $connections[] = $user_details;
            }
        }
        
        return $connections;
    }

#-------------------------------------------------------------------------------------------------------------------
    function get_user_profile_url($user_id){
        global $CFG;
        
        if($CFG[site][wp_htaccess] == "1"){
            $Url = "{$CFG[site][base_url]}/profile/$user_id";
        }
        else{
            $Url = "{$CFG[site][base_url]}/user_profile.php?id=$user_id";
        }
        
        return $Url;
    }

#-------------------------------------------------------------------------------------------------------------------
    function get_email_note_array($prof_id){
        global $CFG,$lang;
        
        $gets_email = $this->get_value("{$CFG['table']['profile']}","email_notification","prof_id = '$prof_id'");
        $send_mail_note = explode(',',$gets_email);
        
        return $send_mail_note;
    }
#-------------------------------------------------------------------------------------------------------------------
    function get_available_file_category($sel_value){
        global $CFG,$lang;
        
        $dd_sql = " SELECT A.*,B.media_type FROM
        
                        {$CFG['table']['faqsfile_category']} AS A
                        
                    INNER JOIN
                    
                        {$CFG['table']['file_manager']} AS B ON A.id = B.category
                        
                    WHERE A.prof_id = '$_SESSION[prof_id]' GROUP BY B.category";
        
        $dd_res = mysql_query($dd_sql) or die(mysql_error());
        $dd_num = mysql_num_rows($dd_res);
        
        if($dd_num > 0){
            while($dd_dat = mysql_fetch_array($dd_res)){
                
                $dd_dat = array_map('stripslashes',$dd_dat);
                $dd_dat = array_map('trim',$dd_dat);
                
                if($dd_dat[id] == $sel_value){
                    $sel = "selected = 'selected'";
                }else{
                    $sel = "";
                }
                
                $option .= "<option value='$dd_dat[id]' $sel>".ucfirst($dd_dat[name])."</option>";
            }
        }
        
        return $option;
    }
#-------------------------------------------------------------------------------------------------------------------
    function relative_date(/*$date*/$d){
        /*
        $localFormat     = new DateTime($date, new DateTimeZone(date_default_timezone_get()));
        $added_date      = strtotime($date);
        $added_time      = date("Y-m-d h:i:s A",$added_date);
        $added_date_time = strtotime($added_time);
        $today           = strtotime(date("Y-m-d h:i:s A"));
           
        $iSec = $today - $added_date_time;
        #$iSec = $iSec-3600;
  		$s = '';

        if($iSec>0){
            if($iSec < 3600){
                $i = round($iSec/60);
                $s .= (0 == $i || 1 == $i) ? self::_t('{0} minute{1} ago', '1') : self::_t('{0} minute{1} ago', $i, 's');
            }
            elseif($iSec < 86400){
                $i          = intval($iSec/60/60);
                $remainsOfI = $iSec%3600;
                $mins       = floor($remainsOfI/60);
                $s .= (0 == $i || 1 == $i) ? self::_t('{0} hour{1} ', '1') : self::_t('{0} hour{1} ', $i, 's');
                $s .= (0 == $mins || 1 == $mins) ? self::_t('and {0} minute{1} ago ', '1') : self::_t('and {0} minute{1} ago ', $mins, 's');
            }
            else{
                $i = round($iSec/60/60/24);
                if($i<=4){
                    $s .= (0 == $i || 1 == $i) ? self::_t('{0} day{1} ago ', '1') : self::_t('{0} day{1} ago ', $i, 's');
                    $formatted_date = $localFormat->format('g:i a');
                    $s .= 'at '.$formatted_date;
                }
                else{
                    if($i<7)
                    $s .= $formatted_date = $localFormat->format('l, j F \a\t g:i a');
                    else{
                        $p_year = $formatted_date = $localFormat->format('Y');
                        $c_year = date('Y');
                        if($p_year!=$c_year)
                        $s .= $formatted_date = $localFormat->format('F j, Y \a\t g:i a');
                        else
                        $s .= $formatted_date = $localFormat->format('F j \a\t g:i a');
                        #$s .= $formatted_date = $localFormat->format('m/d/Y \a\t g:i a');
                    }
                }
            }
        }
        else{
            if ($iSec > -3600) {
                $i = round($iSec/60);
                $s .= (0 == $i || 1 == $i) ? self::_t('In {0} minute{1}', '1') : self::_t('in {0} minute{1}', -$i, 's');
            }
            else if ($iSec > -86400) {
                $i          = intval($iSec/60/60);
                $remainsOfI = $iSec%3600;
                $mins       = floor($remainsOfI/60);
                $s .= (0 == $i || 1 == $i) ? self::_t('in {0} hour{1}', '1') : self::_t('in {0} hour{1}', -$i, 's');
                $s .= (0 == $mins || 1 == $mins) ? self::_t('and {0} minute{1} ago ', '1') : self::_t('and {0} minute{1} ago ', $mins, 's');
            }
            elseif ($iSec < -86400) {
                $i = round($iSec/60/60/24);
                if($i<=4){
                    $s .= (0 == $i || 1 == $i) ? self::_t('in {0} day{1} ', '1') : self::_t('in {0} day{1} ', -$i, 's');
                    $formatted_date = $localFormat->format('g:i a');
                    $s .= 'at '.$formatted_date;
                }
                else{
                    if($i<7)
                    $s .= $formatted_date = $localFormat->format('l, j F \a\t g:i a');
                    else {
                        $p_year = $formatted_date = $localFormat->format('Y');
                        $c_year = date('Y');
                        if($p_year!=$c_year)
                            $s .= $formatted_date = $localFormat->format('F j, Y \a\t g:i a');
                        else
                            $s .= $formatted_date = $localFormat->format('F j \a\t g:i a');
                            #$s .= $formatted_date = $localFormat->format('m/d/Y \a\t g:i a');
                    }
                }
            }   
	   }
	return $s;*/
  $c = getdate();
	   $p = array('year', 'mon', 'mday', 'hours', 'minutes', 'seconds');
	   $display = array('year', 'month', 'day', 'hour', 'minute', 'second');
	   $factor = array(0, 12, 30, 24, 60, 60);
	   $d = $this->datetoarr($d);
	   for ($w = 0; $w < 6; $w++) {
		  if ($w > 0) {
			 $c[$p[$w]] += $c[$p[$w-1]] * $factor[$w];
			 $d[$p[$w]] += $d[$p[$w-1]] * $factor[$w];
		  }
		  if ($c[$p[$w]] - $d[$p[$w]] > 1) { 
			 return ($c[$p[$w]] - $d[$p[$w]]).' '.$display[$w].'s ago';
		  }
	   }
	   return '';
    }

    function _t($str, $arg0 = "", $arg1 = "", $arg2 = "") {

		$str = str_replace('{0}', $arg0, $str);
		$str = str_replace('{1}', $arg1, $str);
		$str = str_replace('{2}', $arg2, $str);
		return $str;
    }
#-------------------------------------------------------------------------------------------------------------------
    function ad_display(){
        global $CFG;
        
        $UserProfileObj = new UserProfile();
        
        $profile = $UserProfileObj->get_user_profile_details($_SESSION[prof_id]);
        $date    = date('Y-m-d');
        
        #check login user 
        $sub_sql .= " AND ( prof_id != '$_SESSION[prof_id]' )";
    
        
        #check country
        $sub_sql .= " AND (
                        ( country = '$profile[country]' ) or ( country like '$profile[country],%') or ( country like '%,$profile[country]') or ( country like '%,$profile[country],%')
                        )";
        
        #check state
        $sub_sql .= " AND (
                        ( state = '$profile[state]' ) or ( state = '0' )
                        )";
        
        #check city
        $sub_sql .= " AND (
                        ( city = '$profile[city]' ) or ( city = '0' )
                        )";                   
        
        #check gender
        $sub_sql .= " AND (
                        ( gender = '$profile[gender]') or ( gender = 'B' )
                        )";
        
        #check user_type
        $sub_sql .= " AND ( user_type like '$profile[user_type]' OR user_type like '$profile[user_type],%' OR user_type like '%,$profile[user_type],%' OR user_type like '%,$profile[user_type]' )";
        
        
        #check age
        if($profile[user_age] < 24)
        $select_age = 18;
        elseif($profile[user_age] >= 24 and $profile[user_age] < 34)
        $select_age = 25;
        elseif($profile[user_age] >= 34 and $profile[user_age] < 44)
        $select_age = 35;
        elseif($profile[user_age] >= 44)
        $select_age = 44;
        
        #check age like
        #$sub_sql .= " AND ( age like '$select_age' OR age like '$select_age,%' OR age like '%,$select_age,%' OR age like '%,$select_age' )";

        
        $sql = "SELECT * FROM {$CFG['table']['advertisement']} WHERE id > 0 AND payment_status = '1' AND display_status = '1' $sub_sql  LIMIT 5";
        $res = $this->sql_query($sql);
        $num = mysql_num_rows($res);
        
        if($num > 0){
            
            while($dat = mysql_fetch_array($res)){
                
                $dat = array_map('stripslashes',$dat);
                $dat = array_map('trim',$dat);
                
                $dat[title]     = ucfirst($dat[title]);
                $dat[image_url] = "{$CFG[site][ad_img_thumb_url]}/$dat[image]";
                
                
                
                #check daily avl record in monitor table
                #check is exists
                $sel_xx = "SELECT * FROM {$CFG['table']['ads_monitor']} WHERE ad_id = '$dat[id]' AND date = '$date'";
                $res_xx = $this->sql_query($sel_xx);
                $num_xx = mysql_num_rows($res_xx);
                
                #update
                if($num_xx == 0){

                    $per_day_click   = 0;
                    $per_day_impress = 0;
                    $impress_price   = 0; 
                    $daily_budget    = 0;  
                    $per_day_imp     = 0; 
                    $per_day_impress = 0;
                    
                    $click_type   = $this->get_value($CFG['table']['advertisement'],'pay_type',"id ='$dat[id]'"); #click / impress
                    $daily_budget = $this->get_value($CFG['table']['advertisement'],'daily_budget',"id ='$dat[id]'"); #20$

                    if($click_type == 'click'){
                        $sigle_click     = $this->get_value($CFG['table']['advertisement'],'click_amount',"id ='$dat[id]'");
                        $per_day_click   = round(($daily_budget/$sigle_click)); // 5
                    }
                    else{
                        $impress_price   = $this->get_value($CFG['table']['advertisement'],'impression_amount',"id ='$dat[id]'"); #10$
                        $per_day_imp     = ($daily_budget/$impress_price); //  10/2 = 5
                        $per_day_impress = round($per_day_imp * 1000); // 5 * 1000 = 5000
                    }
                    
                    #----------------------------------------------------------------------------------------------------
                    $insert_first = "INSERT INTO {$CFG['table']['ads_monitor']} SET
                                        
                                        ad_id           = '$dat[id]',
                                        date            = '$date',
                                        daily_budget    = '$daily_budget',
                                        x_impress_price = '$per_day_impress',                                   	
                                        x_click_price   = '$per_day_click',
                                        clicks          = '$per_day_click',
                                        impressions     = '$per_day_impress',
                                        used_clicks     = '0',
                                        used_impressions= '1'
                                    ";
                    $result_first = $this->sql_query($insert_first);
                }
                else{
                    
                    $old_impress = $this->get_value($CFG['table']['ads_monitor'],'used_impressions',"ad_id ='$dat[id]' AND date = '$date'"); #2
                    $new_impress = $old_impress+1;
             
                    $update_first = "UPDATE {$CFG['table']['ads_monitor']} SET used_impressions = '$new_impress' WHERE ad_id = '$dat[id]' AND date = '$date'";
                    $result_first  = $this->sql_query($update_first);
                }
                
                $ads[] = $dat;
            }
        }
        
        return $ads;
    }   
}//end class
?>