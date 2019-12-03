<?php	
	require_once( "./XERCalendar.php" );
	require_once( "./XERCalendarToken.php" );

        error_reporting(E_ALL);
        ini_set('display_errors', 'On');

	class XERString
	{
		public static function SafeExplode( $delimiter, &$string )
		{	
			$newString = "";			
			
			for( $i = 0; $i < strlen( $string ); $i++ )
				if( ord( $string[$i] ) == 127 )
				{
					while( $i < strlen( $string ) && (ord( $string[$i] ) == 127  || $string[$i] == '\t' ))
						$i++;		

					$i--;
				}
				else
					$newString .= $string[$i];
							
			$result = explode( $delimiter, $newString );			
			return $result;
		}	

		public static function RemoveToken( $token, &$string )
		{			
			return str_replace( $token."\t", "", $string ); 
		}	

		public static function FormatTableHeader( &$header )
		{
			echo "<tr>\n";					
			foreach( $header as $cell )
				echo "\t<td>".$cell."</td>\n";
			echo "</tr>\n";
		}
		
		public static function FormatTableRow( &$row, &$header )
		{
			echo "<tr>\n";
		
			for( $i = 0; $i < count( $row ); $i++ )
			{
				echo "\t<td>";
				
				if( rtrim($header[$i]) == "clndr_data" )
				{	
					$calendar = new XERCalendar();
					$calendar->Init( $row[$i] );
					$calendar->Debug();
				}
				else
					echo $row[$i];

				echo "</td>\n";				
			}
			echo "</tr>\n";
		}

		public static function FormatTableRows( &$rows, &$header )
		{
			foreach( $rows as $row )
				echo self::FormatTableRow( $row, $header );			
		}
		
		public static function ReadCalendarToken( &$body, $i )
		{
			$token = new XERCalendarToken();
			
			// Skip initial 0|| block
			while( $i < strlen( $body ) && !XERString::Compare( "||", $body, $i ) )
				$i++;				
			$i += strlen("||");

			
			// Read token name (or number, in case of days)
			$tokenName = "";
			while( $i < strlen( $body ) && $body[$i] != '(' )
				$tokenName .= $body[$i++];
						
			$token->SetName( $tokenName );		
							
			// Read token options
			$tokenOptions = "";			
			$i++; // skip opening parenthesis
			while( $i < strlen( $body ) && $body[$i] != ')' )
				$tokenOptions .= $body[$i++];
			
			$token->SetOptions( $tokenOptions );
			$i++; // Skip ')'
			
			// Read token body
			$endOffset = XERString::SkipParens( $body, $i );
			$tokenBody = XERString::GetFromTo( $body, $i + 1, $endOffset -1 );
			
			$token->SetBody( $tokenBody );
			return $token;
		}			
				
		public static function Compare( $key, &$body, $offset )
		{
			if( $offset + strlen( $key ) > strlen( $body ) )
				return false;
		 
			for( $i = 0; $i < strlen( $key ) && (($offset + $i) < strlen( $body )) ; $i++ )
				if( $key[ $i ] != $body[ $offset + $i ] )
					return false;       
			return true;				
		}		
		
		public static function SkipParens( &$body, $offset )
		{
			return XERString::Skip( $body, '(', ')', $offset );
		}		
		
		public static function Skip( &$body, $startDelimiter, $endDelimiter, $offset )
		{
			if( $offset >= strlen( $body ) )
				return strlen( $body );
					
			if( $body[ $offset ] != $startDelimiter )
			  return $offset;
			  
			$offset = XERString::Next( $body, ++$offset );
				
			$nests = 1;
			$i;
					
			for( $i = $offset; $nests > 0 && $i < strlen( $body ); )
			{  		
				if( true /* Not in a quote */ )
				{
					if( $body[ $i ] == $startDelimiter )  
						$nests++;
					   
					else if( $body[ $i ] == $endDelimiter ) 
						$nests--;
				}			
				$i++;
			}		
		   return $i;				
		} 
		
		// Skip whitespace.	
		public static function Next( &$body, $offset)
		{
			while( $offset < strlen( $body ) && ctype_space( $body[ $offset ] ) )
				$offset++;	
			return $offset;
		}
		
		public static function GetFromTo( &$body, $startOffset, $endOffset )
		{
			return substr( $body, $startOffset, $endOffset - $startOffset );
		}			
	} // XERString
?>