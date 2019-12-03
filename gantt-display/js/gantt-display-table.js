function GenerateTable()
{
/*	document.write( " <table id=\"overflow\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">" );
	document.write( "<tr>" );
	document.write( "<th\">Activity ID</th> ");
	document.write( "<th>Activity Name</th> ");	
	document.write( "<th>Original<br>Duration</th> ");	
	document.write( "<th>Baseline<br>Start</th> ");	
	document.write( "<th>Baseline<br>Finish</th> ");	
	document.write( "<th bgcolor=\"#7fff7f\">Start</th> ");	
	document.write( "<th bgcolor=\"#7fff7f\">Finish</th> ");	
	document.write( "<th>% Complete</th> ");	
	document.write( "</tr>" ); */

	var parentWBSIds = GetParentWBSIds()
	
	for( var i = 0; i < parentWBSIds.length; i++ )
		InsertWBSTableEntryById( parentWBSIds[i] );
		
//	document.write( "</table>" );	
//	document.write( "<br>" );
//	document.write( "<b>Missed Tasks: </b>" + CalculateMissedTasksPct().toString() + "%" );
}

var g_WBSTableNests = 0;
function InsertWBSTableEntryById( wbsId )
{
	var wbs = GetWBSById( wbsId );
	var wbsTab = "";
	
	for( var i = 0; i < g_WBSTableNests; i++ )
		wbsTab += "&nbsp;&nbsp;&nbsp;";


	document.write( "<tr id=\"tr_wbs_"  + wbs.id + "\" onclick=\"javascript: alert( 'Selected WBS ' + '"+wbs.id+"');\">" );
	document.write( "<td class=\"left\">" + wbs.id + "</td> ");
	document.write( "<td>" + wbs.name +  "</td> ");	
	document.write( "<td>" + /* (DaysBetweenDates( wbs.endYear, wbs.endMonth, wbs.endDay, wbs.startYear, wbs.startMonth, wbs.startDay )).toString() */ "WBS Days" + "</td>");	

	document.write( "<td>" + "wbs.startDateStr" + "</td> ");	
	document.write( "<td>" + "wbs.endDateStr" + "</td> ");	

	document.write( "<td bgcolor=\"#7fff7f\">" + "wbs.actStartDateStr" + " </td> ");	
	document.write( "<td bgcolor=\"#7fff7f\">" + "wbs.actEndDateStr" + "</td> ");	

	document.write( "<td class=\"right\">" + "WBS Pct Complete" + "</td> ");	
	document.write( "</tr>" );			
			
		
/*	document.write( "<tr>" );
	document.write( "<td bgcolor=\"#c0c0c0\"><b>" );
	document.write( wbsTab + wbs.name );
	document.write( "</b></td>" );
	document.write( "</tr>" ); */

	g_WBSTableNests++;
	var childWBSIds = GetChildWBSIdsByParentId( wbsId );
	
	for ( var i = 0; i < childWBSIds.length; i++ )
		InsertWBSTableEntryById( childWBSIds[i] );

	var wbsTasks = GetWBSTasks( wbs.id, true );
	
	for ( var i = 0; i < wbsTasks.length; i++ )
		InsertTaskTableEntry( wbsTasks[i] );

	g_WBSTableNests--;
}

function InsertTaskTableEntry( task )
{
	var taskIsHovered = false; //IsTaskHovered( task );
	var isHovered = taskIsHovered? " <b><font color=\"red\">(hovered)</font></b> " : "";

	document.write( "<tr id=\"tr_task_"  + task.code + "\" onclick=\"javascript: alert( 'Selected task ' + '"+task.code+"');\">" );
	document.write( "<td class=\"left\">" + task.code + "</td> ");
	document.write( "<td>" + task.name +  "</td> ");	
	document.write( "<td>" + (DaysBetweenDates( task.endYear, task.endMonth, task.endDay, task.startYear, task.startMonth, task.startDay )).toString() + "</td>");	

	document.write( "<td>" + task.startDateStr + "</td> ");	
	document.write( "<td>" + task.endDateStr + "</td> ");	

	document.write( "<td bgcolor=\"#7fff7f\">" + task.actStartDateStr + " </td> ");	
	document.write( "<td bgcolor=\"#7fff7f\">" + task.actEndDateStr + "</td> ");	

	document.write( "<td class=\"right\">" + task.pctComplete + "</td> ");	
	document.write( "</tr>" );	
}
