<?php
	require_once( "./XERString.php" );
	require_once( "./XERCalendarToken.php" );

	class XERCalendar
	{	
		protected $m_CalendarToken;
		protected $m_Exceptions;
	
		protected $m_CalendarData;
		protected $m_DayNames;
		
		public function Init( $calendarData )
		{
			$this->m_CalendarData = $calendarData;		
			$this->Parse();
		}
		
		public function Parse()
		{			
			$this->m_CalendarToken = XERString::ReadCalendarToken( $this->m_CalendarData, 0 );
			$this->m_CalendarToken->ParseSubTokens();
		}	
		
		public function Debug()
		{
			$this->m_CalendarToken->Debug();
		}			
	}
?>