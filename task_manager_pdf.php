<?php

include_once "config.php";
include_once "Taskmanager.class.php";

if($_SESSION['userId']==''){
    header("Location: login.php");
    exit;
}

//ini_set('display_errors','on'); error_reporting(E_ALL);
$taskObj     = new Taskmanager();


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
							
							<td align="center">{$i}</td>
							<td align="center">$tStatus</td>
							<td align="center">{$indiTask['start_date']}</td>
							<td align="center">{$indiTask['due_date']}</td>
							<td align="center">{$indiTask['complete_date']}</td>
							<td align="center">{$indiTask['tasker']}</td>
							<td align="center">$taskName</td>
							<td align="center">$taskeeName</td>
						</tr>
EOD;
$i++;
}
?>



<?php

    $pdfContent = ' <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Task Manager - The Gems Solutions</title>
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />

</head>
<body>

   <div class="container" style="width:1200px;height:650px;">
	
		<div class="clr"></div>
        
        <div style="float:left;width:500px;height:105px;" > 
            <img src="'.$CFG['site']['base_url'] .'/images/gems-logo.png" border="0" width="239" height="103" >
        </div> 
		<div style="clear:both;" ></div>
		<div id="no-more-tables" style="margin: 11px 8px 10px 20px !important;width:100%;400px;">
			<table border="1" cellpadding="0" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th align="center" style="font-weight:bold;" >Task</th>
						<th align="center" style="font-weight:bold;">Status</th>
						<th align="center" style="font-weight:bold;">Start Date</th>
						<th align="center" style="font-weight:bold;">Due Date</th>
						<th align="center" style="font-weight:bold;">Completed</th>
						<th align="center" style="font-weight:bold;">Tasker</th>
						<th align="center" style="font-weight:bold;">Task</th>
						<th align="center" style="font-weight:bold;">Taskee</th>
					</tr>
				</thead>
				<tbody>
				'.$tData.'
				</tbody>
			</table>
		</div>
		
	</div>


</body>
</html>

';
   
  //echo $pdfContent;exit; 
    ob_end_clean();
    require_once('tcpdf/tcpdf.php');
    $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetHeaderData($logo, PDF_HEADER_LOGO_WIDTH, '', '');
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER); 
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    //set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    // set font
    $pdf->SetFont('helvetica', '', 10);
    // add a page
    $pdf->AddPage();
    $pdf->writeHTML($pdfContent, true, false, true, false, '');
    // reset pointer to the last page
    $pdf->lastPage();
//  echo $pdfContent;exit;
    //Close and output PDF document
    $pdf->Output('tasklist.pdf', 'D');
    
?>