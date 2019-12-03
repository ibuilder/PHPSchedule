<?php
	class GanttDisplay
	{	
		protected $m_GenericTasks;
		protected $m_Output;
		
		public function Init()
		{
			$this->m_Output = "";
		}

		public function Output( $str )
		{
			$this->m_Output .= $str;
		}
		
		public function SetTasks( $tasks ) 
		{
			$this->m_GenericTasks = $tasks;
		}
		
		public function Run()
		{		
			$this->DisplayHTML(); 
		}
	
		public function GenerateJS()
		{
			$output = "";
			
			$output .= "var g_FirstStartDay = ".$this->m_GenericTasks->GetFirstStartDay().";\n";
			$output .= "var g_FirstStartMonth = ".$this->m_GenericTasks->GetFirstStartMonth().";\n";
			$output .= "var g_FirstStartYear = ".$this->m_GenericTasks->GetFirstStartYear().";\n";

			$output .= "var g_LastEndMonth = ".$this->m_GenericTasks->GetLastEndMonth().";\n";
			$output .= "var g_LastEndYear = ".$this->m_GenericTasks->GetLastEndYear().";\n";

			/*
				AddTasks function 
			*/			
			$output .= "function AddTasks() {\n";
			
			foreach( $this->m_GenericTasks->m_Tasks as $task )
			{
				$argumentArray =  array( $task->GetName(), 
											$task->GetWBSId(),
											$task->GetCode(),
											$task->GetPctComplete(),
											
											$task->GetStartDate(), $task->GetEndDate(),
											$task->GetActStartDate(), $task->GetActEndDate()											
											);
									
				$argumentString = "";				
				foreach( $argumentArray as $argument )
					$argumentString .= "\"". $argument . "\", ";
			
				$argumentString .= "\"Task\"";			
				$output .= "AddTask( $argumentString );\n";
			}
			
			$output .= "}\n";
			
			/*
				AddWBSs function
				*/
			$output .= "function AddWBSs() {\n";
			
			foreach( $this->m_GenericTasks->m_WBSs as $wbs )
			{
				$argumentArray =  array( $wbs->GetName(), 
											$wbs->GetId(),
												$wbs->GetSequenceNumber(),
													$wbs->GetParentId() );
									
				$argumentString = "";				
				foreach( $argumentArray as $argument )
					$argumentString .= "\"". $argument . "\", ";
			
				$argumentString .= "\"Wbs\"";			
				$output .= "AddWBS( $argumentString );\n";
			}
			
			$output .= "}\n";
				
			$output .= "AddWBSs();\n";
			$output .= "AddTasks();\n";
							
			return $output;
		}
	
		public function DisplayHTML()
		{
			echo"
			<html>
				<head>				
					<title>Gantt Chart Test</title>
					<script type=\"text/javascript\" src=\"../js/jquery-3.4.1.min.js\"></script>				
					<script type=\"text/javascript\" src=\"../js/colResizable-1.6.min.js\"></script>
					<script type=\"text/javascript\" src=\"../gantt-display/js/gantt-display-graphics.js\"></script>
					<script type=\"text/javascript\" src=\"../gantt-display/js/gantt-display-table.js\"></script>								
					<script type=\"text/javascript\" src=\"../gantt-display/js/gantt-display-task.js\"></script>
					<script type=\"text/javascript\" src=\"../gantt-display/js/gantt-display-jquery.js\"></script>				
					<link rel=\"stylesheet\" type=\"text/css\" href=\"../gantt-display/css/gantt-display.css\">

					  <script type=\"text/javascript\">
						$(function()
						{
							$(\"#gantt-table\").colResizable({
							  liveDrag:true, 
							  gripInnerHtml:\"<div class='grip'></div>\", 
							  draggingClass:\"dragging\", 
							  resizeMode:'overflow'
							});
							
						});	
					  </script>
					</head>
				<body>
					<script type=\"text/javascript\">
						".$this->GenerateJS()."
					</script>
					
					<div id=\"gantt-spacer-div\" style=\"background-color: #0000c0; width: 100%; height: 50px\">&nbsp;</div>
					<div id=\"gantt-toolbar-div\" style=\"background-color: #FFFFFF; width: 100%; height: 50px\">
					<button onclick=\"javascript:ZoomIn();\">Zoom In</button> <button onclick=\"javascript:ZoomOut();\">Zoom Out</button>
					
					<button onclick=\"javascript:SlideLeft();\">Slide Left</button> <button onclick=\"javascript:SlideRight();\">Slide Right</button>
					
					</div>

					<!-- Table -->
					<div id=\"gantt-table-div\">
						<table id=\"gantt-table\" width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
							<tr>
								<th>Activity ID</th> 
								<th>Activity Name</th> 
								<th>Original<br>Duration</th>	
								<th>Baseline<br>Start</th> 
								<th>Baseline<br>Finish</th> 
								<th bgcolor=\"#7fff7f\">Start</th> 
								<th bgcolor=\"#7fff7f\">Finish</th> 
								<th>% Complete</th> 
							</tr>
								<script type=\"text/javascript\">
									GenerateTable();
								</script>							
						</table>
					</div>
					
					<!-- Separator -->
					<div id=\"gantt-separator-div\">
					</div>
									
					<!-- Chart -->
					<div id=\"gantt-chart-div\">
						<canvas id=\"gantt-chart-canvas\" style=\"background-color: lightblue\"></canvas>
						<script type=\"text/javascript\">
							DisplayGantt();
						</script>
					</div>
						
					<script type=\"text/javascript\">
						AddHSlider( \"table\" );
						AddHSlider( \"gantt\" );
						AddVSlider( \"table\" );
					</script>
			
						
				</body>	
			</html>
		";
		}
	}
	
?>