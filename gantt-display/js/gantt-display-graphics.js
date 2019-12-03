const MONTH_NAMES = ["January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December"];

var   MONTH_BOX_HOR_SIZE         	     = 15; 
const MONTH_BOX_VERT_SIZE   	         = 20;
const MONTH_BOX_VERT_MONTH_NAME_OFFSET   = 15;
const DAY_NUMBER_VERT_SIZE				 = 13;
const TASK_SEPARATOR          		     = 25;
const TASK_VERT_SIZE			  		 = 20;
const TASK_HOR_OFFSET             		 = 20;
const TASK_NAME_HOR_OFFSET         		 = 5;
const TASK_NAME_VERT_OFFSET        		 = 14;
const TASK_REAL_DURATION_SEPARATOR 		 = 10;

    
var g_Offset = new Array();
var g_Tasks = new Array();
var taskCntr = 0; 
var g_HorizontalPixelShift = -300;	
var g_MouseX;
var g_MouseY;
var g_ShiftedMouseX;
var g_ShiftedMouseY;
var g_PixelOffset = {};

function DisplayGantt()
{
//	SetupGantt();

	var c = GetCanvas();
	//c.width = window.screen.availWidth;
	//c.height = window.screen.availHeight;
	var ctx = c.getContext("2d");
	
	ctx.clearRect( 0, 0, c.width, c.height );

	DisplayCalendarHeader();	
	DisplaySliders();
	DisplayWBSs(); // Includes tasks
	DisplayLinks();
}

function ZoomIn()
{
	MONTH_BOX_HOR_SIZE *= 1.1;
	DisplayGantt();
}

function ZoomOut()
{
	MONTH_BOX_HOR_SIZE /= 1.1;
	DisplayGantt();
}

function SlideLeft()
{
	g_HorizontalPixelShift -= 100;
	DisplayGantt();
}

function SlideRight()
{
	g_HorizontalPixelShift += 100;
	DisplayGantt();
}

function DisplayTask( task )
{
	//createDiv
}

function DisplayDivContainer()
{
}

function GanttOnSlider()
{

}

function DisplaySliders()
{

}

function DisplayWBSs()
{
	var y = 50;
	
	var parentWBSIds = GetParentWBSIds()
	
	for( var i = 0; i < parentWBSIds.length; i++ )
		y = DisplayWBSById( parentWBSIds[i], y );
		
	var c = GetCanvas()
	if( c.height < y )
	{
		c.height = y;
		DisplayGantt();
	}	
}

function DisplayWBSById( wbsId, y )
{
	var wbs = GetWBSById( wbsId );
	var tasks = GetWBSTasks( wbs.id, true );
	
	var xOffset;
	if( tasks.length > 0 )
		xOffset = GetDayOffset( tasks[0].startMonth, tasks[0].startDay );
	else 
		xOffset = 5 + GetHorizontalPixelShift();
		
	PrintMediumText( wbs.name,  xOffset, y + TASK_NAME_VERT_OFFSET );

	// Display Sub-tasks	
	y = DisplayWBSTasks( tasks, y );
	y += TASK_SEPARATOR;
		
	// Display Sub-WBSs	
	var childWBSIds = GetChildWBSIdsByParentId( wbs.id );
	for( var i = 0; i < childWBSIds.length; i++ )
		y = DisplayWBSById( childWBSIds[i], y );
	
	return y;
}

function DisplayWBSTasks( tasks, y )
{
	SetGanttHoveredTask( null );

	for( var i = 0; i < tasks.length; i++ )
		y = DisplayWBSTask( tasks[i], y );
	
	return y;
}

function DisplayWBSTask( task, y )
{
	var startOffset = GetDayOffset( task.startMonth, task.startDay );
	var endOffset = GetDayOffset( task.endMonth, task.endDay );	
	y += TASK_SEPARATOR;		
	
	// Green line for actual start/finish
	if( task.actStartDateStr != "" && task.actEndDateStr != "" )
	{
		var actStartOffset = GetDayOffset( task.actStartMonth, task.actStartDay );
		var actEndOffset = GetDayOffset( task.actEndMonth, task.actEndDay );			
		DrawLine( actStartOffset, y + TASK_REAL_DURATION_SEPARATOR, actEndOffset, y + TASK_REAL_DURATION_SEPARATOR, "#00ff00" ); 
	}
	
	var hovered = (g_ShiftedMouseX >= startOffset && g_ShiftedMouseX <= endOffset + TASK_HOR_OFFSET ) && (g_ShiftedMouseY >= y && g_ShiftedMouseY <= y + TASK_VERT_SIZE );
	
	var dispCount = hovered === true ? 20 : 1;		
	for( var i = 0; i < dispCount; i++ )
	{
		DrawRectangle( startOffset, y, endOffset + TASK_HOR_OFFSET, y + TASK_VERT_SIZE );		
		PrintMediumText( task.name, startOffset + TASK_NAME_HOR_OFFSET, y + TASK_NAME_VERT_OFFSET );
	}
	
	SetGanttHoveredTask( task );
	
	return y;
}

function DisplayLinks()
{

}

function DaysInMonth( month, year ) 
{ 
	return new Date( year, month, 0 ).getDate(); 
}

function DaysBetweenDates( year2, month2, day2, year1, month1, day1 )
{
	var first  = new Date( year1, month1, day1 );
	var second = new Date( year2, month2, day2 );
    return Math.round( (second - first)/( 1000 * 60 * 60 *24 ) );
}
			
function DisplayCalendarHeader()
{
	var x = 15 + GetHorizontalPixelShift();
	if( g_LastEndMonth < 12)
		x = DisplayMonths( g_FirstStartYear, g_FirstStartMonth, g_FirstStartDay, g_LastEndYear, g_LastEndMonth + 1, x );
	else
		x = DisplayMonths( g_FirstStartYear, g_FirstStartMonth, g_FirstStartDay, g_LastEndYear + 1, 1, x );

	/* var c = GetCanvas();
	if( c.width < x )
		c.width = x; */		
}

function DisplayMonths( yearFrom, monthFrom, startDay, yearTo, monthTo, x )
{
	for( var y = yearFrom; y <= yearTo; y++ )
	{
		var loopStartMonth;
		var loopEndMonth;
		
		if( y === yearFrom )
			loopStartMonth = monthFrom;
		else 
			loopStartMonth = 1;

		if( y === yearTo )
			loopEndMonth = monthTo;
		else 
			loopEndMonth = 12;
			
		for( var m = loopStartMonth; m <= loopEndMonth; m++ )
		{		
			var loopStartDay;
			
			if( m === monthFrom )
				loopStartDay = startDay;
			else
				loopStartDay = 1;
			
			x = DisplayMonth( y, m, loopStartDay, x );		
		}
	}	
	return x;
}

function DisplayMonth( year, month, startDay, x )
{
	var xOrig = x;
	var daysInMonth = DaysInMonth( month, year );
	
	var y = 5;
	
	var totalWidth = ( daysInMonth - startDay + 1 ) * MONTH_BOX_HOR_SIZE;
		
	DrawRectangle( x, y, x + totalWidth, y + MONTH_BOX_VERT_SIZE );
	
	y += MONTH_BOX_VERT_MONTH_NAME_OFFSET;
	
	// Display month name
	PrintMediumTextHCentered( MONTH_NAMES[ month - 1 ] + " " + year.toString(), x, x + totalWidth, y );

	y += 5;
	
	// Horizontal separator above and beneath month days.
	var yLine1 = y;
	var yLine2 = y + DAY_NUMBER_VERT_SIZE;
	DrawLine( x, yLine2, x + totalWidth, yLine2 );
	
	y += 10	;

	for( var day = startDay; day <= daysInMonth; day++ )
	{
		SetDayOffset( month, day, x );
		PrintSmallTextHCentered( day, x, x + MONTH_BOX_HOR_SIZE, y );

		// Vertical separator between two consecutive month days.
		DrawLine( x, yLine1, x, yLine2 );
		x += MONTH_BOX_HOR_SIZE;
	}
	
	y += 5;
		
	return xOrig + totalWidth;		
}

/*
	Sets pixel offset of a given day.
*/
function SetDayOffset( month, day, x )
{
	g_PixelOffset[ month.toString() + "-" + day.toString() ] = x;
}

function GetHorizontalPixelShift()
{
	return g_HorizontalPixelShift;
}

function GetVerticalPixelShift()
{
	return 0;
}

function GetDayOffset( month, day )
{
	return  g_PixelOffset[ month.toString() + "-" + day.toString() ];
}

/*
	Primitive graphics functions 
*/
function GetCanvas()
{
	return document.getElementById("gantt-chart-canvas");
}

function DrawLine( x0, y0, xF, yF, color = "#000000" )
{
	var c = GetCanvas();
	var ctx = c.getContext("2d");
	ctx.strokeStyle = color;
	ctx.beginPath();
	ctx.moveTo( x0, y0 );
	ctx.lineTo( xF, yF );
	ctx.stroke();
}

function DrawRectangle( x0, y0, xF, yF )
{
	DrawLine( x0, y0, x0, yF );
	DrawLine( x0, yF, xF, yF );
	DrawLine( xF, yF, xF, y0 );
	DrawLine( xF, y0, x0, y0 );
}

function PrintMediumText( str, x, y )
{
	PrintText( str, x, y, "14px" );
}

function PrintMediumTextHCentered( str, x0, xF, y )
{
	PrintText( str, CenterX( str, x0, xF, 5), y, "14px" );
}

function PrintSmallText( str, x, y )
{
	PrintText( str, x, y, "10px" );
}

function PrintSmallTextHCentered( str, x0, xF, y )
{
	PrintText( str, CenterX( str, x0, xF, 5 ), y, "10px" );
}

function PrintText( str, x, y, size )
{
	var c = GetCanvas();
	var ctx = c.getContext("2d");
	ctx.font = size + " 'Arial Narrow'";
	ctx.fillText( str, x, y);
}

function CenterX( str, x0, xF, multiplier )
{
	return x0 + (xF - x0 - str.toString().length * multiplier)/ 2 
}

function GetHeight()
{
	return GetCanvas().height; 
}