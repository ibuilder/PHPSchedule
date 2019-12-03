var g_WBSs = new Array();
var wbsCntr = 0;

function WBS () {}
WBS.prototype.name = "";
WBS.prototype.id = 0;
WBS.prototype.seqNum = 0;
WBS.prototype.parentId = 0;

function AddWBS( name, id, seqNum, parentId )
{
	var wbs = new WBS();
	wbs.name     = name;
	wbs.id 		 = id;
	wbs.seqNum 	 = seqNum;
	wbs.parentId = parentId;
	
	g_WBSs[wbsCntr++] = wbs;
}

function Task () {}
Task.prototype.name = "";
Task.prototype.wbsId = 0;
Task.prototype.code = "";
Task.prototype.pctComplete = 0;

Task.prototype.startDateStr = "";
Task.prototype.endDateStr = "";

Task.prototype.startYear  = 0;
Task.prototype.startMonth = 0;
Task.prototype.startDay   = 0;

Task.prototype.endYear  = 0;
Task.prototype.endMonth = 0;
Task.prototype.endDay   = 0;

Task.prototype.actStartDateStr = "";
Task.prototype.actEndDateStr = "";

Task.prototype.actStartYear  = 0;
Task.prototype.actStartMonth = 0;
Task.prototype.actStartDay   = 0;

Task.prototype.actEndYear  = 0;
Task.prototype.actEndMonth = 0;
Task.prototype.actEndDay   = 0;

	
//Task.prototype.fullName = function () {
//console.log (this.firstName + " " + this.lastName); 
// };

function AddTask( taskName, wbsId, code, pctComplete, 
					startDateStr, endDateStr,  
					actStartDateStr, actEndDateStr,  							
					taskType )
{
	var task = new Task();
	task.name = taskName;
	task.wbsId = parseInt( wbsId, 10 );
	task.code = code;
	task.pctComplete = parseInt( pctComplete, 10 );
	
	//
	
	task.startDateStr = startDateStr;		
 	task.startYear  = GetYear( startDateStr );
	task.startMonth = GetMonth( startDateStr );
	task.startDay   = GetDay( startDateStr );
	
	task.endDateStr = endDateStr;
	task.endYear  = GetYear( endDateStr );
	task.endMonth = GetMonth( endDateStr );
	task.endDay   = GetDay( endDateStr );
		
	task.actStartDateStr = actStartDateStr;
 	task.actStartYear  = GetYear( actStartDateStr );
	task.actStartMonth = GetMonth( actStartDateStr );
	task.actStartDay   = GetDay( actStartDateStr );

	task.actEndDateStr   = actEndDateStr;
	task.actEndYear  = GetYear( actEndDateStr );
	task.actEndMonth = GetMonth( actEndDateStr );
	task.actEndDay   = GetDay( actEndDateStr );
	
	g_Tasks[taskCntr++] = task;
} 

function WBSIdExists( wbsId )
{
	for( var i = 0; i < wbsCntr; i++ )
		if( g_WBSs[i].id === wbsId )
			return true;
	return false;
}

function GetParentWBSIds()
{
	var result = new Array();
	var resultCntr = 0;

	for( var i = 0; i < wbsCntr; i++ )
		if( WBSIdExists( g_WBSs[i].parentId ) === false )
			result[ resultCntr++ ] = g_WBSs[i].id;

	return result;
}

function GetWBSById( wbsId )
{
	for( var i = 0; i < wbsCntr; i++ )
		if( g_WBSs[i].id === wbsId )
			return g_WBSs[i];
	return null;
}

function GetChildWBSIdsByParentId( parentId, sort = true )
{
	var result = new Array();
	var resultCntr = 0;

	for( var i = 0; i < wbsCntr; i++ )
		if( g_WBSs[i].parentId === parentId )
			result[ resultCntr++ ] = g_WBSs[i].id;

	if( sort !== true )
		return result;
			
	var orderedIds = OrderWBSIdsByStartDate( result );	
	return orderedIds;
}

// Sort WBS IDs by start date		
function OrderWBSIdsByStartDate( wbsIds )
{
	var minDates = new Array();
	
	for( var i = 0; i < wbsIds.length; i++ )
	{
		var wbsTasks =  GetExtendedWBSTasks( wbsIds[i] );
		minDates[i] = GetTasksMinDate( wbsTasks );
	}
		
	return SortArray( wbsIds, minDates );
}

function GetTasksMinDate( tasks )
{
	if( tasks.length === 0 )
		return 0;

	var minDate = 2147483647;

	for( var i = 0; i < tasks.length; i++ )
	{
		var taskMinDate = DaysBetweenDates( tasks[i].startYear, tasks[i].startMonth, tasks[i].startDay, 1970, 1, 1 );
		if( taskMinDate < minDate )
			minDate = taskMinDate;
	}
	
	return minDate;
}

function SortArray( keys, values )
{	
	var result = new Array( keys.length );
	var resultCntr = 0;
	
	var isIndexed = new Array( keys.length );
	for( var i = 0; i < keys.length; i++ )
		isIndexed[i] = false;

	var minValue = 2147483647;
	for( var i = 0; i < keys.length; i++ )
		if( values[i] < minValue )
			minValue = values[i];

	for( var j = 0; j < keys.length; j++ )
	{		
		if( values[j] === minValue && isIndexed[j] === false )
		{	
			result[resultCntr++] = keys[j];
			isIndexed[j] = true;
			j = -1;
		
				
			minValue = 2147483647;			
			for( var i = 0; i < keys.length; i++ )
				if( values[i] < minValue && isIndexed[i] === false )
					minValue = values[i];
		}
		
	}
	
	return result;
}

function GetWBSTasks( wbsId, sort = false )
{
	var result = new Array();
	var resultCntr = 0;
	
	for( var i = 0; i < taskCntr; i++ )
		if( g_Tasks[i].wbsId == wbsId )
			result[ resultCntr++ ] = g_Tasks[i];
		
	if( sort === true )
		return SortTasksByStartDate( result );
				
	return result;	
}

function SortTasksByStartDate( tasks )
{
	var taskDates = Array( tasks.length );
	var taskDateCntr = 0;
	
	for( var i = 0; i < tasks.length; i++ )
	{
		var taskDate = DaysBetweenDates( tasks[i].startYear, tasks[i].startMonth, tasks[i].startDay, 1970, 1, 1 );
		taskDates[ taskDateCntr++ ] = taskDate;
	}
	
	return SortArray( tasks, taskDates );
}

function GetExtendedWBSTasks( wbsId )
{
	var result = GetWBSTasks( wbsId );	
	var resultCntr = result.length;
	
	var childWBSIds = GetChildWBSIdsByParentId( wbsId, false );	
	
	for( var i = 0; i < childWBSIds.length; i++ )
	{
		var childWBSTasks = GetExtendedWBSTasks( childWBSIds[i] );
		for( var j = 0; j < childWBSTasks.length; j++ )
			result[ resultCntr++ ] = childWBSTasks[ j ];
	}
 
	return result;	
}

/* Date parsing */

function GetDay( date )
{
	if( date.length === 0 )
		return "";
		
	var parsedDate = date.split( "-" );	
	parsedDate = parsedDate[2].split( " " );			
	return parseInt( parsedDate[0], 10 );	
}

function GetMonth( date )
{
	if( date.length === 0 )
		return "";

		var parsedDate = date.split( "-" ); 
	return parseInt( parsedDate[1], 10 );
}

function GetYear( date )
{
	if( date.length === 0 )
		return "";

	var parsedDate = date.split( "-" ); 
	return parseInt( parsedDate[0], 10 );
}

// Sort startDateStr and endDateStr
function CalculateVerticalOrder( startYear, startMonth, startDay, endYear, endMonth, endDay  )
{
	
}

/* Actions */
function TaskMoved()
{

}

/* Analyses */
function CalculateMissedTasksPct()
{
	// Per page 37 of DCMA-PAM-200-1.pdf
	
	var num = 0; // Count of tasks act finish past baseline
	var den = 0; // Count of baseline dates before status dates.
	
	for( var i = 0; i < g_Tasks.length; i++ )
	{
		if( g_Tasks.actEndDateStr == "" )
			continue;
	
		var endDate = DaysBetweenDates( g_Tasks[i].endYear, g_Tasks[i].endMonth, g_Tasks[i].endDay, 1970, 1, 1 );
		var actEndDate = DaysBetweenDates( g_Tasks[i].actEndYear, g_Tasks[i].actEndMonth, g_Tasks[i].actEndDay, 1970, 1, 1 );
		
		if( actEndDate > endDate )		
			num++;		
		else
			den++;
	}
	
	return ( num/den * 100).toFixed(2);
}


/*
	Hovering and selections
*/
var g_GanttHoveredTask;
function SetGanttHoveredTask( task )
{
	g_GanttHoveredTask = task;
}

function GetGanttHoveredTask()
{
	return g_GanttHoveredTask;
}

//function IsTaskGanttHovered( task )