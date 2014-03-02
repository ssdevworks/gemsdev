<?php

       /********************************************************************************************************* 
   Date: 16th May 2012
   Support: info@zen-e-solutions.com 

   Note: Dont Change this file for any reason. 
   **********************************************************************************************************/ 

   $CFG['site']['base_path']        = "/home/jcomp1/public_html/new/html";
   $CFG['site']['base_url']         = "http://www.gemscomp.com/new/html";
   $CFG['db']['db_host']            = "localhost";
   $CFG['db']['db_user']            = "jcomp1";
   $CFG['db']['db_pwd']             = "9LlY&U?B";
   $CFG['db']['db_name']            = "jcomp1_empdb";
   $CFG['db']['table_prefix']       = "gems_";
  
   include_once "dbconnection.php";
   include_once "User.class.php";
  // include_once "Taskmanager.class.php";
   
   $dbObj = new Site();      
  
   /***********************************************************************************************************/

     ob_start();//Turn on output buffering 
     ob_implicit_flush(0); //Turn implicit flush on/off 
     session_start(); //Initialize session data 

     ini_set('default_charset','UTF-8'); 
     setlocale(LC_ALL, 'UTF-8'); 
     ini_set('log_errors', 'Off');
     ini_set('display_errors','Off');
     ini_set("post_max_size", 0);
     ini_set ("safe_mode", 0);
     set_time_limit(0);
     

       define('ERROR_LOGGING', true);
    if(defined('ERROR_LOGGING') && ERROR_LOGGING == '1') { 
       error_reporting(E_ERROR | E_PARSE); //Sets which PHP errors are reported
       
	   ini_set('error_log', 'error.log');
	   ini_set('log_errors', 'On');
    }
    
       define('DEV_MODE', true);
       define('SHOW_ERRORS', false);
    if(defined('DEV_MODE') && DEV_MODE == '1' && SHOW_ERRORS == '1'){ 
	   error_reporting(E_ALL);
       ini_set('display_errors','On');
    }
    # BACK LINK ISSUE FIXED ON BROWSERS ( 20th June 2012 )
    header("Cache-Control: no-cache, must-revalidate");
    $TMPTASKER = array(19=>"Beverage, Food",16=>"Bird, Hilton",1=>"Broadway, Jimmy",26=>"Carte, Jason",4=>"Crotts, Matt",14=>"DeJong, Craig",80=>"fgdfg, fdgf",13=>"Finn, Ryan", 3=>"Goellner, Karl", 12=>"Gordon, Jerry", 28=>"Guest, GEMS", 8=>"Holden, Jeff", 20=>"Johnson, Mark", 18=>"Kennedy, Brad", 24=>"Kirchner, Craig" , 7=>"Kirchner, Ryan", 9=>"Peak, Jeff", 6=>"Seither, Patrick", 25=>"Strickland, Jimmy",	5=>"Walker, Matt" , 2=>"Webster, Jeff", 11=>"Whitley, Ben");        
?>