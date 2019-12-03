<?php
	class GenericWBS
	{
		protected $m_Id;
		protected $m_Name;
		protected $m_SequenceNumber;
		protected $m_ParentId;
		
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

		public function SetParentId( $parentId )
		{
			$this->m_ParentId = $parentId;
		}

		public function GetParentId()
		{
			return $this->m_ParentId;
		}

		public function SetChildWBSs( $ChildWBSs )
		{
			$this->m_ChildWBSs = $ChildWBSs;
		}

		public function GetChildWBSs()
		{
			return $this->m_ChildWBSs;
		}
		
		public function SetSequenceNumber( $sequenceNumber )
		{
			$this->m_SequenceNumber = $sequenceNumber;
		}

		public function GetSequenceNumber()
		{
			return $this->m_SequenceNumber;
		}		
	}

?>