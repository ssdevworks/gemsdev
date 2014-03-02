<?php

include_once "config.php";
include_once "Taskmanager.class.php";

if($_SESSION['userId']==''){
    header("Location: login.php");
    exit;
}

//ini_set('display_errors','on'); error_reporting(E_ALL);
$taskObj     = new Taskmanager();

if($_REQUEST['do']=='delete'){
    $delTask = $taskObj->deactivateTask($_REQUEST['taskId']);   
    header("Location: task_manager.php?suc=1");
    //header("Location : task_manager.php?suc=1");
    exit;
}

$taskDetails = $taskObj->getAllTasks();
$tData       = '';
$i=1;
foreach($taskDetails as $indiTask)
{
    //echo "<pre>";print_r($indiTask);echo "</pre>";        
    $tCls  = $i % 2 ==0 ? 'even' : 'odd';
    if($indiTask['task_status'] ==1)
    $tStatus = 'Initiated';
    elseif($indiTask['task_status'] ==2)
    $tStatus = 'In-Process';
    elseif($indiTask['task_status'] ==3)
    $tStatus = 'Complete';
    elseif($indiTask['task_status'] ==4)
    $tStatus = 'Cancelled';
    $taskeeName = $TMPTASKER[$indiTask['taskee']];
    $taskName = strlen($indiTask['task_detail']) >34 ? substr($indiTask['task_detail'],0,34) .'..' : $indiTask['task_detail'];
    $tData .= <<<EOD

	<tr class="$tCls">
							<td data-title="Select"><input type="radio" class="taskClk" name="seltask" value="{$indiTask['id']}" /></td>
							<td data-title="Task">{$i}</td>
							<td data-title="Status">$tStatus</td>
							<td data-title="Start Date">{$indiTask['start_date']}</td>
							<td data-title="Due Date">{$indiTask['due_date']}</td>
							<td data-title="Completed">{$indiTask['complete_date']}</td>
							<td data-title="Tasker">{$indiTask['tasker']}</td>
							<td data-title="Task">$taskName</td>
							<td data-title="Taskee">$taskeeName</td>
						</tr>
EOD;
$i++;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<title>Task Manager - The Gems Solutions</title>
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.min.css" />
<link rel="stylesheet" type="text/css" href="css/reset.css" />
<link rel="stylesheet" type="text/css" href="css/gems.css" />
<script src="js/jquery-1.10.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
$(document).ready(function(){
    
    $(document).on('click', '.taskedit', function(){
        
        
        if($('input[name="seltask"]:checked').length <  1)
        {
            alert("Select atleast one record and then proceed");
            return false;
        }
        var selElem = $('input[name="seltask"]:checked').val();
        window.location.href='managetask.php?taskId=' + selElem;
    });
    
    $(document).on('click', '.taskdel', function(){
        
        
        
        if($('input[name="seltask"]:checked').length <  1)
        {
            alert("Select atleast one record and then proceed");
            return false;
        }
        if(confirm("Are you sure want to delete selected task?")){
            var selElem = $('input[name="seltask"]:checked').val();
            window.location.href='task_manager.php?do=delete&taskId=' + selElem;
        }
        
    });
    
    
    $(document).on('click', '.taskClk', function(){
        
        $('#del-btn').removeClass('dsbl-grey-btn');
        $('#del-btn').addClass('grey-btn');
        $('#edit-btn').removeClass('dsbl-grey-btn');
        $('#edit-btn').addClass('grey-btn');
        
    });
    
    
    
    
})
</script>
</head>
<body>
<div class="headr-container">
	<div class="container">
		<div class="span3">
			<a href=""><img src="images/gems-logo.png" /></a>
		</div>
		<div class="span9">
			<div class="usr-profile">
				<div class="ph-block">
					<img src="images/usr-profile-img.png" />
				</div>
				<div class="rgt-block">
					<p>Matthew</p>
					<span>The Hasentree Club</span>
					<a href="logout.php">Logout</a>
				</div>
			</div>
			<ul>
				<li><a href="">Global</a></li>
				<li class="active"><a href="">Main</a></li>
				<li><a href="">Calendar</a></li>
				<li><a href="">Members</a></li>
				<li><a href="">Config</a></li>
				<li><a href="">Help</a></li>
			</ul>
		</div>
	</div>
</div>
<div class="mem-container navbar navbar-inverse navbar-static-top" role="navigation">
	<div class="container">
		<div class="navbar-inner">
			<button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<div class="lft-navi-block">
				<ul class="nav nav-collapse collapse">
					<li><a href="" class="dash"><span></span><p>Dashboard</p></a></li>
					<li><a href="" class="tee"><span></span><p>Tee and Play</p></a></li>
					<li><a href="" class="evnt"><span></span><p>Events</p></a></li>
					<li><a href="" class="ordr"><span></span><p>Order Tracker</p></a></li>
					<li><a href="" class="demo"><span></span><p>Demo Tracker</p></a></li>
					<li><a href="" class="taskmanagr active"><span></span><p>Task Manager</p></a></li>
					<li><a href="" class="exp"><span></span><p>Experience</p></a></li>
					<li><a href="" class="qury"><span></span><p>Member Query</p></a></li>
					<li><a href="" class="rpts"><span></span><p>Reports</p></a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="content-container">
			<div class="profile-top-container">
				<div class="span6"><div class="prof-name">
                    <span>
                        Task Manager 
                    </span></div></div>
				<div class="span6">
					<input type="button" class="grey-btn" value="Email" />
				</div>
			</div>
			<div class="clr"></div>
			<?php
                if($_REQUEST['suc']=='1'){
                                
            ?>                    
    			<div class="succ-msg">
    			    &nbsp;&nbsp; 
                    <font color='blue' >Task deleted successfully</font>
    			</div>
			<?php
                 }
             ?>  
             <div class="clr"></div>
			<div id="no-more-tables">
				<table border="0" cellpadding="1" cellspacing="1">
					<thead>
						<tr>
							<th class="slect">Select</th>
							<th class="taskid">Task</th>
							<th class="status">Status</th>
							<th class="start-date">Start Date</th>
							<th class="due">Due Date</th>
							<th class="complet">Completed</th>
							<th class="taskr">Tasker</th>
							<th class="task">Task</th>
							<th class="taskee">Taskee</th>
						</tr>
					</thead>
					<tbody>
					<?php echo $tData;?>
					</tbody>
				</table>
			</div>
			<div class="btns-block">
			    
			    <!--
				<input type="button" id="del-btn" class="dsbl-grey-btn taskdel" value="Delete Task" />
				<input type="button" id="edit-btn" class="dsbl-grey-btn  taskedit" value="Edit Task" />
				<input type="button" class="grey-btn" value="Add Task" onclick="window.location.href='managetask.php'" />
				<input type="button" class="grey-btn" onclick="window.location.href='task_manager_pdf.php';" value="Export" />
				-->
				
				 
				    <a href="javascript:void(0);" id="del-btn" class="dsbl-grey-btn taskdel">Delete Task</a> 
				    <a href="javascript:void(0);" id="edit-btn" class="dsbl-grey-btn  taskedit">Edit Task</a> 
				    <a href="managetask.php" class="grey-btn">Add Task</a> 
				    <a href="task_manager_pdf.php" class="grey-btn">Export</a> 				    
				 
				
			</div>
		</div>
	</div>
</div>
<div class="main-footer">
	<div class="container">
		<div class="footmenu">
			<a href="">About Argus Intelligence</a> | <a href="">Licensing</a> | <a href="">Privacy Policy</a> | <a href="">Terms &amp; Conditions</a><br /><span>Copyright &copy; 2009 Argus Intelligence, LLC. All rights reserved.</span>
		</div>
		<img src="images/argus-logo2.png" />
	</div>
</div>
<div class="loginbg-container">
	<img src="images/login-wrapper02.jpg" />
</div>
</body>
</html>
