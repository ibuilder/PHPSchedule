<?php
	class GenericTaskContainer
	{
		public $m_Tasks;
		public $m_WBSs;
		
		public function Init()
		{
			$this->m_Tasks = array();
			$this->m_WBSs  = array();
		}

		public function AddWBS( $wbs )
		{
			array_push( $this->m_WBSs, $wbs );
		}
		
		public function SetWBSs( $WBSs )
		{
			$this->m_WBSs = $WBSs;
		}
				
		public function AddTask( $task )
		{
			array_push( $this->m_Tasks, $task );
		}
		
		/* Dates */
		public function GetFirstStartDate()
		{
			$dates = array();
			foreach( $this->m_Tasks as $task )
			{
				array_push( $dates, $task->GetStartDate() );
				if( strlen( $task->GetActStartDate() ) > 0 )
					array_push( $dates, $task->GetActStartDate() );
			}
			
			sort( $dates );
			return $dates[0];
		}
	
		public function GetLastEndDate()
		{
			$dates = array();
			foreach( $this->m_Tasks as $task )
			{
				array_push( $dates, $task->GetEndDate() );
				if( strlen( $task->GetActEndDate() ) > 0 )					
					array_push( $dates, $task->GetActEndDate() );
			}
			rsort( $dates );
			return $dates[0];		
		}

		public function GetFirstStartYear()
		{
			$date = $this->GetFirstStartDate();
			$parsedDate = explode( "-", $date );
			return $parsedDate[0];
		}
		
		public function GetFirstStartMonth()
		{
			$date = $this->GetFirstStartDate();
			$parsedDate = explode( "-", $date );
			return $parsedDate[1];
		}

		public function GetFirstStartDay()
		{
			$date = $this->GetFirstStartDate();
			$parsedDate = explode( "-", $date );
			$parsedDate = explode( " ", $parsedDate[2] );			
			return $parsedDate[0];
		}
		
		public function GetLastEndMonth()
		{
			$date = $this->GetLastEndDate();
			$parsedDate = explode( "-", $date );
			return $parsedDate[1];
		}

		public function GetLastEndYear()
		{
			$date = $this->GetLastEndDate();
			$parsedDate = explode( "-", $date );
			return $parsedDate[0];
		}
		
	}
?>