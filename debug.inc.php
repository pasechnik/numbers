<?php

require_once( 'Console.class.php' );

function myprint_r( &$var, $return )
{
    static $console;
    static $spaces = "";
    static $id = 0;

    if ( !isset( $console ) )
    {
        if ( Console::detectConsole() )
        {
            $console = true;
        }
        else
        {
            $console = false;
            $str = <<<EOD
<script language="javascript">
	function toggledisplay( elid )
	{
		el = document.getElementById( elid );
		if ( el === null ) return false;

		back = document.getElementById( elid + '_back' );
		if ( back === null ) return false;

		col = document.getElementById( elid + '_col' );
		if ( col === null ) return false;

		if ( el.style.display == 'none' )
		{
			back.style.display = 'none';
			el.style.display = '';
			col.style.display = '';
		}
		else
		{
			col.style.display = 'none';
			el.style.display = 'none';
			back.style.display = '';
		}
		return false;
	}
</script>
EOD;

            echo $str;
        }
    }

    $spaces0 = $spaces;
    $result = '';
    $elid = md5( $id++ . time() );
    if ( is_object( $var ) )
    {
        $result .= get_class( $var );
        if ( !$console )
        {
            $result .= ' <a href="#" onclick="return toggledisplay(\'' . $elid . '\')">Object</a>';
            $result .= '<span id="' . $elid . '_back" style="margin: 0px 4px 0px 4px; display:;">{ <span style="background-color:#CCC; cursor: pointer; border: 1px outset; display:;" onclick="return toggledisplay(\'' . $elid . '\')">...</span> }</span>';
            $result .= '<span id="' . $elid . '_col" style="margin: 0px 4px 0px 4px; display:none;">&nbsp;&nbsp;<span style="background-color:#CCC; cursor: pointer; border: 1px inset; display:;" onclick="return toggledisplay(\'' . $elid . '\')">^^^</span></span>';
            $result .= '<div id="' . $elid . '" style="border:1px solid darkgray; margin: 0px 10px -10px 10px; padding: 0px 10px; display:none;">';
        }
        else
        {
            $result .= ' Object ' . "\n";
        }
        $result .= $spaces0 . '(';
        $result .= "\n";
        $spaces = $spaces0 . "\t";
        foreach ( get_object_vars( $var ) as $classvar => $value )
        {
            $result .= $spaces . '[ ' . $classvar . ' ] => ' . myprint_r( $value, $return ) . "\n";
        }
        $result .= $spaces;
        $funcsid = md5( $id++ . time() );
        if ( !$console )
        {
            $result .= '[ <a href="#" onclick="return toggledisplay(\'' . $funcsid . '\')">Methods</a> ] => ';
            $result .= '<span id="' . $funcsid . '_back" style="margin: 0px 4px 0px 4px; display:;">{ <span style="background-color:#CCC; cursor: pointer; border: 1px outset; display:;" onclick="return toggledisplay(\'' . $funcsid . '\')">...</span> }</span>';
            $result .= '<span id="' . $funcsid . '_col" style="margin: 0px 4px 0px 4px; display:none;">&nbsp;&nbsp;<span style="background-color:#CCC; cursor: pointer; border: 1px inset; display:;" onclick="return toggledisplay(\'' . $funcsid . '\')">^^^</span></span>';
            $result .= '<div id="' . $funcsid . '" style="border:1px solid darkgray; margin: 0px 10px -10px 10px; padding: 0px 10px; display:none;">';
        }
        else
        {
            $result .= "[Methods]:\n";
        }
        $spaces = $spaces . "\t";
        foreach ( get_class_methods( get_class( $var ) ) as $classvar => $value )
        {
            $result .= $spaces . '[ ' . $classvar . ' ] => ' . myprint_r( $value, $return ) . "\n";
        }
        if ( !$console )
        {
            $result .= '</div>' . "\n";
        }
        else
        {
            $result .= "\n";
        }

        $spaces = $spaces0;

        $result .= $spaces0 . ')';
        if ( !$console )
        {
            $result .= '</div>';
        }
        else
        {
            $result .= "\n";
        }
    }
    elseif ( is_array( $var ) )
    {
//		$result .= get_class($var);
        if ( !$console )
        {
            $result .= ' <a href="#" onclick="return toggledisplay(\'' . $elid . '\')">Array</a> [ ' . count( $var ) . ' ] ';
            $result .= '<span id="' . $elid . '_back" style="margin: 0px 4px 0px 4px; display:;">{ <span style="background-color:#CCC; cursor: pointer; border: 1px outset; display:;" onclick="return toggledisplay(\'' . $elid . '\')">...</span> }</span>';
            $result .= '<span id="' . $elid . '_col" style="margin: 0px 4px 0px 4px; display:none;">&nbsp;&nbsp;<span style="background-color:#CCC; cursor: pointer; border: 1px inset; display:;" onclick="return toggledisplay(\'' . $elid . '\')">^^^</span></span>';
            $result .= '<div id="' . $elid . '" style="border:1px solid darkgray; margin: 0px 10px -10px 10px; padding: 0px 10px; display:none;">';
        }
        else
        {
            $result .= ' Array [ ' . count( $var ) . ' ]' . "\n";
        }
        $result .= $spaces0 . '(';
        $result .= "\n";
        //		$result .= print_r( get_object_vars( $var) );
        $spaces = $spaces0 . "\t";
        foreach ( $var as $classvar => $value )
        {
            $result .= $spaces . '[ ' . $classvar . ' ] => ' . myprint_r( $value, $return ) . "\n";
        }
        $spaces = $spaces0;
        $result .= $spaces0 . ')';
        if ( !$console )
        {
            $result .= '</div>';
        }
        else
        {
            $result .= "\n";
        }
    }
    elseif ( $var === null )
    {
        $result = 'null';
    }
    elseif ( $var === false )
    {
        $result = 'false';
    }
    elseif ( $var === true )
    {
        $result = 'true';
    }
    elseif ( is_string( '' . $var ) && strlen( '' . $var ) == 0 )
    {
        $result = "''";
    }
    elseif ( is_int( $var ) )
    {
        $result = $var;
    }
    elseif ( is_string( '' . $var ) && !strcmp( intval( '' . $var ), '' . $var ) )
    {
        $result = $var;
    }
    else
    {
        $value = "'" . print_r( $var, $return ) . "'";
        if ( !$console )
        {
            $value = htmlspecialchars( $value );
        }
        $result = $value;
    }
    if ( $return )
    {
        return $result;
    }
    else
    {
        echo $result;
    }
}

// ------------------------- print debug info -- begin -------------------------
function prn()
{

    static $console;
    static $debugtrace = 1;
    if ( !headers_sent() )
    {
        header( 'Content-type: text/html; charset=utf-8' );
        header( 'Charset: utf-8;' );
    }
    if ( defined( 'DEBUG' ) )
    {
        if ( !isset( $console ) )
        {
            $console = Console::detectConsole();
        }

        if ( !$console )
        {
            echo '<div style="background-color:white;color:black;"><hr size=1px color=black /><pre>';
        }

        if ( !isset( $debugtrace ) )
        {
            $debugtrace = debug_backtrace();
            prn( 'debugtrace', $debugtrace );
            $debugtrace = 1;
        }
        $messages = func_get_args();
        foreach ( $messages as $k => $it )
        {
            if ( $it === false )
            {
                $it = 'false';
            }
            elseif ( $it === true )
            {
                $it = 'true';
            }
            else
            {
                $it = myprint_r( $it, true );
            }
            if ( $console )
            {
                Console::println( $it );
            }
            else
            {
                //				$it = htmlspecialchars($it);
                echo $k . ':' . $it;
                echo "\n" . '=======================<br>';
            }
        }

        if ( !$console )
        {
            echo '</pre><hr size=1px color=black /></div>';
        }
    }
}

function prn_exit()
{
    prn( $messages = func_get_args() );
    exit();
}

function textarea()
{
    if ( defined( 'DEBUG' ) )
    {
        echo '<hr size=1px color=blue><pre>';
        $messages = func_get_args();
        foreach ( $messages as $k => $it )
        {
            if ( $it === false )
            {
                $it = 'false';
            }
            elseif ( $it === true )
            {
                $it = 'true';
            }
            else
            {
                $it = '<textarea rows=10 cols=100>' . $it . '</textarea>';
            }
            echo $k . ':' . $it;
            echo "\n" . '=======================<br>';
        }
        echo '</pre><hr size=1px color=blue>';
    }
}

// ------------------------- print debug info -- begin -------------------------
