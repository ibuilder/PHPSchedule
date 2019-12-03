<?php 
	require_once( "./XERString.php" );
	require_once( "./XERTable.php" );
	require_once( "../generic/GenericTaskContainer.php" );
	require_once( "../generic/GenericWBS.php" );

	class XERPRoject
	{
		protected $m_TableCounter;
		protected $m_HeaderFieldNames;
		protected $m_HeaderFieldValues;
	
		public function Init()
		{
			$this->m_TableCounter = 0;
			
			$this->m_HeaderFieldNames = array(
				"MagicNumber",
				"Version",
				"Date",
				"Type",
				"Created by",
				"Updated by",
				"Database Name",
				"Category",
				"Currency"
				);		
		} // Init
		
		public function Parse( $fp )
		{
			$bufferSize = 10000;
			$line = stream_get_line( $fp, $bufferSize, "\n" );
			while( strlen( $line ) > 0 )
			{
				$firstWord = explode( "\t", $line )[0];
				
				switch( true) 
				{
					// Project header
					case $firstWord == "ERMHDR":
						$this->ParseHeader( $line );
						break;
				
					// Table start
					case $firstWord == "%T":
						$this->m_Tables[ $this->m_TableCounter ] = new XERTable();
						$this->m_Tables[ $this->m_TableCounter ]->Init();
						
						$tableName = ltrim(rtrim( XERString::RemoveToken( "%T", $line ) ));
						$this->m_Tables[ $this->m_TableCounter ]->SetName( $tableName );
						$this->m_TableCounter++;

						break;

					// Table header
					case $firstWord == "%F":
						$this->m_Tables[ $this->m_TableCounter-1 ]->ParseHeader( XERString::RemoveToken( "%F", $line ) );
						break;
						
					// Table row
					case $firstWord == "%R":
						$this->m_Tables[ $this->m_TableCounter-1 ]->ParseRow( XERString::RemoveToken( "%R", $line ) );
						break;

					// Project end
					case $firstWord == "%E":
						break;				
				}
						
				$line = stream_get_line( $fp, $bufferSize, "\n" );			
			}
			
		} // ParseHeader
		
		
		public function ParseHeader( $line )
		{
			$this->m_HeaderFieldValues = explode( "\t", $line );
		} // ParseHeader			
		
		/*
			Generic tasks
			*/
		
		// This generates a generic task object from a row in the task table in this project.
		public function ToGenericTask( $header, $row ) 
		{
			$task = new GenericTask();
			
			$task->SetId( $row[ array_search( 'task_id', $header ) ] );			
			$task->SetCode( $row[ array_search( 'task_code', $header ) ] );			
			$task->SetPctComplete( $row[ array_search( 'phys_complete_pct', $header ) ] );			
		
			$task->SetName( $row[ array_search('task_name', $header ) ] );
			$task->SetWBSId( $row[ array_search('wbs_id', $header ) ] );
			
			$task->SetStartDate( $row[ array_search( 'target_start_date', $header ) ] );
			$task->SetEndDate( $row[ array_search( 'target_end_date', $header ) ] );

			$task->SetActStartDate( $row[ array_search( 'act_start_date', $header ) ] );
			$task->SetActEndDate( $row[ array_search( 'act_end_date', $header ) ] );
		
			$task->SetUser( $row[ array_search( 'create_user', $header ) ] );	
			
			return $task;
		}
		
		// This returns an array of generic tasks
		public function ToGenericTasks() 
		{		
			// Retrieve task table.
			$taskTable = null;
					
			foreach( $this->m_Tables as $table )
				if( $table->GetName() == "TASK" )
					$taskTable = $table;
					
			if( $taskTable == null )
				die( "Could not find the task table." );
					
			$genericTasks = New GenericTaskContainer();
			$genericTasks->Init();
						
			$taskTableHeader = $taskTable->GetHeader();
			
			foreach( $taskTable->GetRows() as $row )
			{
				$genericTask = $this->ToGenericTask( $taskTableHeader, $row );
				$genericTasks->AddTask( $genericTask );
			}
			
			// Add WBS array to generic task container.			
			$genericWBSs = $this->ToGenericWBSs();
			$genericTasks->SetWBSs( $genericWBSs );
			
			return $genericTasks;
		}

		/*
			Generic WBSs 
			*/

		public function ToGenericWBS( $header, $row ) 
		{
			$wbs = new GenericWBS();
			
			$wbs->SetId( $row[ array_search( 'wbs_id', $header ) ] );			
			$wbs->SetName( $row[ array_search('wbs_name', $header ) ] );
			
			$wbs->SetParentId( $row[ array_search( 'parent_wbs_id', $header ) ] );
			
			$wbs->SetSequenceNumber( $row[ array_search( 'seq_num', $header ) ] );	
			
			return $wbs;
		}
			
		// This returns an array of generic tasks
		public function ToGenericWBSs() 
		{		
			// Retrieve WBS table.
			$WBSTable = null;
					
			foreach( $this->m_Tables as $table )
				if( $table->GetName() == "PROJWBS" )
					$WBSTable = $table;
					
			if( $WBSTable == null )
				die( "Could not find the WBS table." );
					
			$genericWBSs = array();
						
			$WBSTableHeader = $WBSTable->GetHeader();
			
			foreach( $WBSTable->GetRows() as $row )
			{
				$genericWBS = $this->ToGenericWBS( $WBSTableHeader, $row );
				array_push( $genericWBSs, $genericWBS );
			}
			
			return $genericWBSs;
		}
					
		public function Debug()
		{
			echo "\n<table border=\"1\">\n";
			XERString::FormatTableHeader( $this->m_HeaderFieldNames );	
			XERString::FormatTableHeader( $this->m_HeaderFieldValues );	
			echo "</table>\n";
			
			foreach( $this->m_Tables as $table )
				$table->Debug();
		}		
	}
?>	