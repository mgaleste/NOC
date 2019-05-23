<?
$modulerecord 		=	new record('albumitems');
$coreFunc	 		=	new coreFunctions();
$currentUserId		=	$_SESSION['gp_username'];
$currentGroupId 	=	$coreFunc->getUsersGroup($currentUserId);
$currentModuleId	=	$coreFunc->getAvailableModule($currentGroupId);

$recon 					= 	new recordUpdate();


//get pagination results then assign to $article list
$list 					= $modulerecord->get_paginated_array(20);
$result 				= $list['result'];
$num_rows 				= $list['num_rows'];
$PAGINATION_LINKS 		= $list['PAGINATION_LINKS'];
$PAGINATION_INFO 		= $list['PAGINATION_INFO'];
$PAGINATION_TOTALRECS 	= $list['PAGINATION_TOTALRECS'];
$left_content 			= "";
  
/** ---------------------------------------------TEMPLATE PLUGIN MODULES---------------------------------------------* */

        
$left_content  .= "<table width=\"100%\" cellspacing=\"0\" align=\"center\" cellpadding=\"0\" border=\"0\">";
	$left_content .= "<tr><td height=\"5\" colspan=\"3\">&nbsp;</td></tr>";
 //Start container for Notifications
 	$left_content .= "<tr><td valign=\"top\" width=\"45%\">";
 	$left_content .= "<div class=\"info-tile\">";
 		$left_content .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"95%\" align=\"center\">";
 			$left_content .= "<tr><td align=\"left\" colspan=\"10\"><h2>SITES</h2></td><tr>";
                        $retrieveSites = $recon->retrieveCustomQuery("SELECT count(*),name FROM `site` m join status using (statusid) where m.statusId <>'-1' group by m.statusid");
                        if(!empty($retrieveSites)){
                        $cnt=0;
                        foreach($retrieveSites as $sites){
                            $site = explode("|",$sites);
                            $style= ($cnt<(count($retrieveSites)-1))? 'style="border-right:solid"' : '';
                            $statusColor = $recon->GetValue('color','status','name="'.$site[1].'" and type="site"');
                            $left_content .= "<td align=\"left\" valign=\"center\" $style >";
                                $left_content .= "<b style=\"color:#{$statusColor};font-size:15pt\">{$site[0]}</b> <span>".stripslashes(ucwords($site[1]))."</span>&nbsp;";
                            $left_content .= "</td><td width=\"2%\"></td>";
                            $cnt++;
                        }
                        }else{
                          $left_content .= "<td width=\"2%\"><b style=\";font-size:15pt\">No Sites</b></td>";
                        }
                        
                        
                       
 			
 		$left_content .= "</tr></table>";
 	$left_content .= "</div>";
 	$left_content .= "</td> <td width=\"2%\"></td>";
        $left_content .= "<td valign=\"top\" width=\"45%\">";
 	$left_content .= "<div class=\"info-tile\">";
 		$left_content .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"95%\" align=\"center\">";
 			$left_content .= "<tr><td align=\"left\" colspan=\"10\"><h2>CUSTOMERS</h2></td><tr>";
                        

                        $retrieveSites = $recon->retrieveCustomQuery("SELECT count(*),name FROM `customer` m join status using (statusid) where m.statusId <>'-1' group by m.statusid");
                        if(!empty($retrieveSites)){
                        $cnt=0;
                        foreach($retrieveSites as $sites){
                            $site = explode("|",$sites);
                            $style= ($cnt<(count($retrieveSites)-1))? 'style="border-right:solid"' : '';
                            $statusColor = $recon->GetValue('color','status','name="'.$site[1].'" and type="customer"');
                            $left_content .= "<td align=\"left\" valign=\"center\" $style>";
                                $left_content .= "<b style=\"color:#{$statusColor};font-size:15pt\">{$site[0]}</b> <span>".stripslashes(ucwords($site[1]))."</span>&nbsp;";
                            $left_content .= "</td><td width=\"2%\"></td>";
                            $cnt++;
                        }
                        }else{
                          $left_content .= "<td width=\"2%\"><b style=\";font-size:15pt\">No Customers</b></td>";
                        }



 		$left_content .= "</table>";
 	$left_content .= "</div>";
 	$left_content .= "</td></tr>";
        $left_content .= "<tr><td height=\"5\" colspan=\"3\">&nbsp;</td></tr>";

 	$left_content .= "<tr><td valign=\"top\" width=\"45%\">";
 	$left_content .= "<div class=\"info-tile\">";
 		$left_content .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"95%\" align=\"center\">";
 			$left_content .= "<tr><td align=\"left\" colspan=\"10\"><h2>PAYMENTS</h2></td><tr>";
                        $retrieveSites = $recon->retrieveCustomQuery("SELECT count(*),name FROM `subscriptionpayment` m join status using (statusid) where m.statusId <>'-1' group by m.statusid");
                        
                        if(!empty($retrieveSites)){
                        
                        $cnt=0;
                        foreach($retrieveSites as $sites){
                            $site = explode("|",$sites);
                            $style= ($cnt<(count($retrieveSites)-1))? 'style="border-right:solid"' : '';
                            $statusColor = $recon->GetValue('color','status','name="'.$site[1].'" and type="payment"');
                            $left_content .= "<td align=\"left\" valign=\"center\" $style >";
                                $left_content .= "<b style=\"color:#{$statusColor};font-size:15pt\">{$site[0]}</b> <span>".stripslashes(ucwords($site[1]))."</span>&nbsp;";
                            $left_content .= "</td><td width=\"2%\"></td>";
                            $cnt++;
                        }
                        }else{
                          $left_content .= "<td width=\"2%\"><b style=\";font-size:15pt\">No Payments</b></td>";
                        }





 		$left_content .= "</tr></table>";
 	$left_content .= "</div>";
 	$left_content .= "</td> <td width=\"2%\"></td>";
        $left_content .= "<td valign=\"top\" width=\"45%\">";
 	$left_content .= "<div class=\"info-tile\">";
 		$left_content .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"95%\" align=\"center\">";
 			$left_content .= "<tr><td align=\"left\" colspan=\"10\"><h2>TICKETS</h2></td><tr>";


                        $retrieveSites = $recon->retrieveCustomQuery("SELECT count(*),name FROM `ticket` m join status using (statusid) where m.statusId <>'-1' group by m.statusid");
                        
                        if(!empty($retrieveSites)){
                        
                        $cnt=0;
                        foreach($retrieveSites as $sites){
                            $site = explode("|",$sites);
                            $style= ($cnt<(count($retrieveSites)-1))? 'style="border-right:solid"' : '';
                            $statusColor = $recon->GetValue('color','status','name="'.$site[1].'" and type="ticket"');
                            $left_content .= "<td align=\"left\" valign=\"center\" $style>";
                                $left_content .= "<b style=\"color:#{$statusColor};font-size:15pt\">{$site[0]}</b> <span>".stripslashes(ucwords($site[1]))."</span>&nbsp;";
                            $left_content .= "</td><td width=\"2%\"></td>";
                            $cnt++;
                        }
                        }else{
                          $left_content .= "<td width=\"2%\"><b style=\";font-size:15pt\">No Tickets</b></td>";
                        }



 		$left_content .= "</table>";
 	$left_content .= "</div>";
 	$left_content .= "</td></tr>";
 //End container for Notifications
 	$left_content .= "<tr><td height=\"5\" colspan=\"3\">&nbsp;</td></tr>";
 //Start container for Modules	
 		

//End container for Modules
//$left_content .= "<tr><td height=\"5\">&nbsp;</td></tr>";
//Start container for Charts
 	$left_content .= "<tr><td valign=\"top\">";
 	$left_content .= "<div class=\"info-tile\">";	
 		$left_content .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"95%\" align=\"center\">";
 		$left_content .= "<tr>";

                $left_content .= "<td>";
$retrieveSites = $recon->retrieveCustomQuery("SELECT count(*),name FROM `site` m join status using (statusid) where m.statusId <>'-1' group by m.statusid");
foreach($retrieveSites as $site){
    $data = explode("|",$site);
    $dataPoints['y'] = $data[0];
    $dataPoints['label'] = $data[1];

    $dataPoint[] = json_encode($dataPoints, JSON_NUMERIC_CHECK);
    $dataPointStr = implode(',',$dataPoint);
}
                $left_content .= '
<div id="chartContainer" style="width:98%;height: 220px;display: inline-block;"></div>

<script type="text/javascript">
$(function () {
    var chart = new CanvasJS.Chart("chartContainer", {
        theme: "light2",
        zoomEnabled: true,
        animationEnabled: false,
        title: {
            text: "Sites"
        },
        data: [
        {
            type: "column",
            dataPoints: ['.$dataPointStr.']
        }
        ]
    });
    chart.render();
});
</script>
';

                $left_content .= "</td>";
                $left_content .= "<td>";
$retrieveSites = $recon->retrieveCustomQuery("SELECT count(*),name FROM `subscriptionpayment` m join status using (statusid) where m.statusId <>'-1' group by m.statusid");
foreach($retrieveSites as $site){
    $data = explode("|",$site);
    $dataPoints['y'] = $data[0];
    $dataPoints['label'] = $data[1];

    $dataPoint2[] = json_encode($dataPoints, JSON_NUMERIC_CHECK);
    $dataPointStr2 = implode(',',$dataPoint2);
}
                $left_content .= '
<div id="chartContainer2" style="width:98%;height: 220px;display: inline-block;"></div>

<script type="text/javascript">
$(function () {
    var chart2 = new CanvasJS.Chart("chartContainer2", {
        theme: "light2",
        zoomEnabled: true,
        animationEnabled: false,
        title: {
            text: "Payments"
        },
        data: [
        {
            type: "column",
            dataPoints: ['.$dataPointStr2.']
        }
        ]
    });
    chart2.render();
});
</script>
';
                $left_content .= "</td>";
                $left_content .= "</tr>";
                $left_content .= "<tr>";
 		$left_content .= "</table>";
 	$left_content .= "</div>";	
 	$left_content .= "</td><td></td>";
        
        $left_content .= "<td valign=\"top\"><div class=\"info-tile\">";
        $left_content .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"95%\" align=\"center\">";
        
$retrieveSites = $recon->retrieveCustomQuery("SELECT ticketId, problem, s.siteName FROM `ticket` m join site s using (siteId) where m.statusId ='8' order by m.startDate");
if(!empty($retrieveSites)){
        $left_content .= "<tr><th colspan=\"3\" align=\"center\">New Tickets</th></tr>";
        $left_content .= "<tr><td>Ticket Id</td><td>Problem</td><td>Site</td></tr>";
foreach($retrieveSites as $site){
        $details = explode("|",$site);
        $bgcolor = ($bgcolor != "#FFFFFF")? "#FFFFFF" : "#EFEFEF";
        $left_content .= "<tr bgcolor=\"$bgcolor\"><td>{$details[0]}</td><td>{$details[1]}</td><td>{$details[2]}</td></tr>";
}
}
        
$retrieveSites = $recon->retrieveCustomQuery("SELECT ticketId, problem, s.siteName FROM `ticket` m join site s using (siteId) where m.statusId ='21' order by m.startDate");
if(!empty($retrieveSites)){
    $left_content .= "<tr><th colspan=\"3\" align=\"center\">Open Tickets</th></tr>";
     $left_content .= "<tr><td>Ticket Id</td><td>Problem</td><td>Site</td></tr>";

foreach($retrieveSites as $site){
        $details = explode("|",$site);
        $bgcolor = ($bgcolor != "#FFFFFF")? "#FFFFFF" : "#EFEFEF";
        $left_content .= "<tr bgcolor=\"$bgcolor\"><td>{$details[0]}</td><td>{$details[1]}</td><td>{$details[2]}</td></tr>";
}

}
        
$retrieveSites = $recon->retrieveCustomQuery("SELECT ticketId, problem, s.siteName FROM `ticket` m join site s using (siteId) where m.statusId ='11' order by m.startDate");
if(!empty($retrieveSites)){
$left_content .= "<tr><th colspan=\"3\" align=\"center\">Pending Tickets</th></tr>";
        $left_content .= "<tr><td>Ticket Id</td><td>Problem</td><td>Site</td></tr>";
foreach($retrieveSites as $site){
        $details = explode("|",$site);
        $bgcolor = ($bgcolor != "#FFFFFF")? "#FFFFFF" : "#EFEFEF";
        $left_content .= "<tr bgcolor=\"$bgcolor\"><td>{$details[0]}</td><td>{$details[1]}</td><td>{$details[2]}</td></tr>";
}
}
        $left_content .= "</div></td>";
        $left_content .= "</tr>";
        $left_content .= "</table>";
$left_content .= "</td></tr>";
$left_content .= "<tr><td height=\"5\" colspan=\"3\">&nbsp;</td></tr>";


$left_content .= "<tr><td><div class=\"info-tile\">";
$left_content .= '<div id="chartContainer3" style="height: 400px; width: 100%;"></div>
<script type="text/javascript">

    $(function () {
        var dps = [];  //dataPoints.

        var chart3 = new CanvasJS.Chart("chartContainer3", {
            title: {
                text: "Site Speed Monitoring"
            },
            axisY: {
                title: "Speed (kbps)"
            },
            data: [{
                type: "spline",
                dataPoints: dps
            }]
        });

        chart3.render();

        // Updating the Chart
        var xVal = dps.length + 1;
        var yVal = 20;
        var updateInterval = 500;

        var updateChart = function () {
            yVal = yVal + Math.round(5 + Math.random() * (-5 - 5));
            dps.push({ x: xVal, y: yVal });
            xVal++;
            chart3.render();
        };


        var timeoutId;
            function startLiveChart() {
            timeoutId = setInterval(function () { updateChart() }, updateInterval);
            }

       
        startLiveChart();

    });
</script>
';
$left_content .= "</div></td></tr>";
//End container for Charts	
$left_content .= "</td></tr></table>";

 echo $left_content;
