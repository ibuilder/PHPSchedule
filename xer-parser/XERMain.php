<?php
        // For debugging purposes.
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');

	require_once( "./XERString.php");
	require_once( "./XERProject.php");

	require_once( "../generic/GenericTask.php");
	require_once( "../gantt-display/GanttDisplay.php");

	
	$fp = fopen( "./myschedule.xer", "r" );
	$project = new XERProject();
	$project->Init();
	$project->Parse( $fp );
	fclose( $fp );
	
	if( @$_GET["action"] == "debug" )
	{
		echo "<html>
				<head>
				 <title>XER Parser</title>
				</head>
				<body>";
				
					$project->Debug();

		echo "</body>
			   </html>";
	}
	
	else if( @$_GET["action"] == "gantt" )
	{
		$genericTasks = $project->ToGenericTasks();
		
		$ganttDisplay = new GanttDisplay();
		$ganttDisplay->Init();		
		$ganttDisplay->SetTasks( $genericTasks );
		$ganttDisplay->Run();
	}
	
	else
	{
		echo "<a href=\"?action=debug\">Debug</a><br>";
		echo "<a href=\"?action=gantt\">Gantt display</a>";	
	}
?>