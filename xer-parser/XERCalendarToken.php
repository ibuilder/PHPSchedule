<?php
	class XERCalendarToken
	{	
		protected $m_Name;
		protected $m_Options;
		protected $m_Body;
		protected $m_Parent;
		
		protected $m_Tokens;
		protected $m_TokenCounter;

		public function SetName( $name )
		{
			$this->m_Name = $name;
		}

		public function SetParent( $parent )
		{
			$this->m_Parent = $parent;
		}

		public function SetOptions( $options )
		{
			$this->m_Options = $options;
		}

		
		public function SetBody( $body )
		{
			$this->m_Body = $body;
		}
		
		public function ParseSubTokens()
		{
			for( $i = 0; $i < strlen( $this->m_Body ); $i++ )
			{
				$i = XERString::Next( $this->m_Body, $i );
								
				$this->m_Tokens[ $this->m_TokenCounter ] = XERString::ReadCalendarToken( $this->m_Body, $i );
				$this->m_Tokens[ $this->m_TokenCounter ]->SetParent( $this->m_Name );								
				$this->m_Tokens[ $this->m_TokenCounter ]->ParseSubTokens();				
				$this->m_TokenCounter++;
				
				$i = XERString::SkipParens( $this->m_Body, $i );				
			}		
		}
		
		public function Debug()
		{
			echo "<ul>\n";		
			
			echo "<li><b>";		
			if( $this->m_Parent == "DaysOfWeek" )
			{
				$dowMap = array( 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' );
				echo $dowMap[ $this->m_Name - 1 ];		
			}
			else 
				echo $this->m_Name;			
			
			echo "</b></li>\n";
			
			if( strlen( $this->m_Options ) > 0 ) 
			{
				$formattedOptions = str_replace(array("d|", "s|", "|f|") , array("Day: ", "Start: ", ", Finish: "), $this->m_Options);
				
				echo "<ul><li>".$formattedOptions."</li></ul>\n";
			}
			
			
			if( $this->m_Tokens!= null )
				foreach( $this->m_Tokens as $subToken )
					$subToken->Debug();
				
			echo "</ul>\n";	
		}			
	}
?>