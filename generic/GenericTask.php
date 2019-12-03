<?php
	class GenericTask
	{
		protected $m_Name;
		protected $m_WBSId;
		protected $m_Code;		
		protected $m_Id;
		protected $m_PctComplete;		
		protected $m_StartDate;
		protected $m_EndDate;
		protected $m_ActStartDate;
		protected $m_ActEndDate;
		protected $m_User;
		
		public function SetId( $id ) 
		{
			$this->m_Id = $id;
		}
		
		public function GetId() 
		{
			return $this->m_Id;
		}

		public function SetName( $name ) 
		{
			$this->m_Name = $name;
		}
		
		public function GetName() 
		{
			return $this->m_Name;
		}

		public function SetPctComplete( $pctComplete ) 
		{
			$this->m_PctComplete = $pctComplete;
		}
		
		public function GetPctComplete() 
		{
			return $this->m_PctComplete;
		}
				
		public function SetCode( $code ) 
		{
			$this->m_Code = $code;
		}
		
		public function GetCode() 
		{
			return $this->m_Code;
		}
				
		public function SetWBSId( $WBSId ) 
		{
			$this->m_WBSId = $WBSId;
		}
		
		public function GetWBSId() 
		{
			return $this->m_WBSId;
		}

		// Date accessors
		
		public function SetStartDate( $startDate ) 
		{
			$this->m_StartDate = $startDate;
		}
		
		public function GetStartDate() 
		{
			return $this->m_StartDate;
		}

		public function SetEndDate( $endDate ) 
		{
			$this->m_EndDate = $endDate;
		}
		
		public function GetEndDate() 
		{
			return $this->m_EndDate;
		}

		public function SetActStartDate( $actStartDate ) 
		{
			$this->m_ActStartDate = $actStartDate;
		}
		
		public function GetActStartDate() 
		{
			return $this->m_ActStartDate;
		}

		public function SetActEndDate( $actEndDate ) 
		{
			$this->m_ActEndDate = $actEndDate;
		}
		
		public function GetActEndDate() 
		{
			return $this->m_ActEndDate;
		}
		
		public function SetUser( $user ) 
		{
			$this->m_User = $user;
		}
		
		public function GetUser() 
		{
			return $this->m_User;
		}
				
		// Date calculations
		
		public function GetStartYear()
		{
			$date = $this->GetStartDate();
			$parsedDate = explode( "-", $date );
			return $parsedDate[0];
		}
		
		public function GetStartMonth()
		{
			$date = $this->GetStartDate();
			$parsedDate = explode( "-", $date );
			return $parsedDate[1];
		}

		public function GetStartDay()
		{
			$date = $this->GetStartDate();
			$parsedDate = explode( "-", $date );
			$parsedDate = explode( " ", $parsedDate[2] );			
			return $parsedDate[0];
		}
		
		public function GetEndMonth()
		{
			$date = $this->GetEndDate();
			$parsedDate = explode( "-", $date );
			return $parsedDate[1];
		}

		public function GetEndYear()
		{
			$date = $this->GetEndDate();
			$parsedDate = explode( "-", $date );
			return $parsedDate[0];
		}
		
		public function GetEndDay()
		{
			$date = $this->GetEndDate();
			$parsedDate = explode( "-", $date );
			$parsedDate = explode( " ", $parsedDate[2] );			
			return $parsedDate[0];
		}	
		
		public function GetActStartYear()
		{
			$date = $this->GetActStartDate();
			$parsedDate = explode( "-", $date );
			return $parsedDate[0];
		}
		
		public function GetActStartMonth()
		{
			$date = $this->GetActStartDate();
			if( $date == "" )
				return "";
			
			$parsedDate = explode( "-", $date );
			return $parsedDate[1];
		}

		public function GetActStartDay()
		{
			$date = $this->GetActStartDate();
			if( $date == "" )
				return "";

			$parsedDate = explode( "-", $date );
			$parsedDate = explode( " ", $parsedDate[2] );			
			return $parsedDate[0];
		}
		
		public function GetActEndMonth()
		{
			$date = $this->GetActEndDate();
			if( $date == "" )
				return "";
			$parsedDate = explode( "-", $date );	
			return $parsedDate[1];
		}

		public function GetActEndYear()
		{
			$date = $this->GetActEndDate();
			if( $date == "" )
				return "";

			$parsedDate = explode( "-", $date );
			return $parsedDate[0];
		}
		
		public function GetActEndDay()
		{
			$date = $this->GetActEndDate();
			if( $date == "" )
				return "";

			$parsedDate = explode( "-", $date );
			$parsedDate = explode( " ", $parsedDate[2] );			
			return $parsedDate[0];
		}		
	}
?>