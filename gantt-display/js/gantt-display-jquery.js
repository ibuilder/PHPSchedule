/*
	List of Modes 
*/
var MODE_NONE     = 0;
var MODE_RESIZE   = 1;
var g_Mode = MODE_NONE;

$(document).ready(function()
{
	SetControlHeights();
		
	// Initialize widths.
	var ganttSeparatorRight = $("#gantt-separator-div").offset().left + $("#gantt-separator-div").width();
	var ganttSeparatorLeft = $("#gantt-separator-div").offset().left;
	var tableLeft = $("#gantt-table-div").offset().left;
		
	$("#gantt-table-div").width( ganttSeparatorLeft );
	$("#gantt-chart-div").offset({ left: ganttSeparatorRight }).width( screen.availWidth - ganttSeparatorRight - tableLeft );	

	$("#gantt-chart-canvas").offset({ left: ganttSeparatorRight });	
//	$("#gantt-chart-canvas").setAttribute( 'width', 1000 );
	
	$("#gantt-separator-div").mousedown
	(	
		function()
		{
			SeparatorMouseDownEvent();
		}			
	) 

	$("#gantt-separator-div").mouseover
	(	
		function()
		{
			$(document.body).css({'cursor' : 'e-resize'});

		}			
	) 
	
	$("#gantt-separator-div").mouseout
	(	
		function()
		{
			if( g_Mode === MODE_NONE )
				$(document.body).css({'cursor' : 'default'});

		}			
	) 
	
    $("#gantt-separator-div").hover(function()
	{
        $("#gantt-separator-div").data('hover',1); 
    },
    function()
	{
        $("#gantt-separator-div").data('hover',0); 
    });	
	
	$(document).mouseup
	(	
		function()
		{
			ResetMode();
		}			
	) 
		
	/*
	$("#gantt-separator-div").click
	(	
		function()
		{
			SeparatorMouseDownEvent();
		}			
	)
	*/ 
		
	$(document).mousemove
	(	
		function(e)
		{
			// TODO: move to a better place.
			SetControlHeights();
		
			// This stores the mouse location.
			g_MouseX = e.pageX;
			g_MouseY = e.pageY;
			
			// This stores the mouse location, corrected for pixel shift.			
			g_ShiftedMouseX = g_MouseX  - $("#gantt-chart-div").offset().left;
			g_ShiftedMouseY = g_MouseY  - $("#gantt-chart-div").offset().top;
					
			if( g_Mode === MODE_RESIZE )
			{
				var ganttSeparatorRight = e.pageX + $("#gantt-separator-div").width() / 2;
				var ganttSeparatorLeft = e.pageX - $("#gantt-separator-div").width() / 2;
				var tableLeft = $("#gantt-table-div").offset().left;
				
				// Make sure we don't pass the left of the table.
				if( ganttSeparatorLeft > $("#gantt-table-div").offset().left )
				{			
					$("#gantt-table-div").width( ganttSeparatorLeft );
					$("#gantt-separator-div").offset({ left: ganttSeparatorLeft });
					$("#gantt-chart-div").offset({ left: ganttSeparatorRight }).width( screen.availWidth - ganttSeparatorRight - tableLeft );	

					$("#gantt-chart-canvas").offset({ left: ganttSeparatorRight });	
					GetCanvas().setAttribute( 'width',  screen.availWidth - ganttSeparatorRight - tableLeft );
					//DisplayGantt();
				} // if
			} // if 
			
			// TODO: only if selected or hovered tasks have changed.
			// TODO: clear canvas.
			DisplayGantt();
		}			
	)	
});

function SetControlHeights()
{
	var screenHeight = screen.availHeight - $("#gantt-chart-div").offset().top * 2;
	//var controlHeight = screenHeight;
	
	var controlHeight = Math.max( screenHeight, $("#gantt-table").height() );
	controlHeight = Math.max( controlHeight, $("#gantt-chart-div").height() );
	
	$("#gantt-chart-div").height( controlHeight );
	$("#gantt-table-div").height( controlHeight );
	$("#gantt-separator-div").height( controlHeight );
	
	//	window.innerHeight = controlHeight;
	
}

function SeparatorMouseDownEvent()
{
	if( g_Mode === MODE_NONE )
	{
		g_Mode = MODE_RESIZE;
		$(document.body).css({'cursor' : 'e-resize'});
	}
	else	
		ResetMode();
	
}

function ResetMode()
{
	g_Mode = MODE_NONE;
	if( !$("#gantt-separator-div").data('hover') )
		$(document.body).css({'cursor' : 'default'});
}


function AddHSlider( name )
{
	console.log("Adding H Slider: " + name );
}

function AddVSlider( name )
{
	console.log("Adding V Slider: " + name );
}
