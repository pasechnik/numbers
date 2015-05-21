<?php


/**
 * Description of Console
 *
 * @author vladx
 */
class Console
{
    static function detectConsole()
	{
		$console = false;

		if ( in_array( php_sapi_name(), array( 'cli', 'cgi' ) ) && empty( $_SERVER['REMOTE_ADDR'] ) )
		{
			$console = true;
		}

		return $console;
	}
    
    static function println( $in_string )
	{
		static $linefeed;

		if (!isset($linefeed))
		{
			if ( Console::detectConsole() )
			{
				$linefeed = "\n";
			}
			else
			{
				$linefeed = '<br />';
			}
		}

		echo $in_string . $linefeed;
	}
}

?>
