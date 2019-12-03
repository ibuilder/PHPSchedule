<?php

	class XERTable
	{
		protected $m_Name;
		protected $m_Header;
		protected $m_Rows;		
		protected $m_RowCounter;
		
		protected $m_HeaderFieldNames;
		
		public function Init()
		{
			$this->m_RowCounter = 0;
		}

		public function SetName( $name )
		{
			$this->m_Name = $name;
		}

		public function GetName()
		{
			return $this->m_Name;
		}
		
		public function ParseHeader( $line )
		{
			$this->m_HeaderFieldNames = explode( "\t", $line );						
		}	

		public function GetHeader()
		{
			return $this->m_HeaderFieldNames;						
		}
		
		public function ParseRow( $line )
		{
			$this->m_Rows[$this->m_RowCounter++] = XERString::SafeExplode( "\t", $line );
		}	

		public function GetRows()
		{
			return $this->m_Rows;
		}	

		
		public function Debug()
		{
			echo "<hr>";
			echo "<b>Table: ".$this->m_Name."</b><br>\n";
			echo "<table border=\"1\">\n";
			XERString::FormatTableHeader( $this->m_HeaderFieldNames );	
			XERString::FormatTableRows( $this->m_Rows, $this->m_HeaderFieldNames );	
			echo "</table>\n";
		}	
	}
?>