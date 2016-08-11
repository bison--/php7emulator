<?php
/**
 * Created by PhpStorm.
 * User: bison
 * Date: 05.08.2016
 * Time: 09:17
 */

/*

## install:
* php-apcu
* php-xml
* php-mbstring / php7.0-mbstring

# serverspecs.php

if (!function_exists('mysql_connect'))
{
    require_once(RAWBASEDIR.'/application/helper/php7emulator.php');
}

*/

class php7emulatorMemory {
    public static $lastMysqlLink = NULL;
}

//$link = mysql_connect('example.com:3307', 'mysql_user', 'mysql_password'); new_link
function mysql_connect($server, $username='', $password='')
{
    //print $server;
    php7emulatorMemory::$lastMysqlLink = mysqli_connect($server, $username, $password) or die('NO CONNECTION! "'.$server.'" "'.$username.'"');
    return php7emulatorMemory::$lastMysqlLink;
}

//bool mysql_close ([ resource $link_identifier = NULL ] )
function mysql_close($link = NULL)
{
    if ($link == NULL)
    {
        return mysqli_close(php7emulatorMemory::$lastMysqlLink);
    }
    else
    {
        //bool mysqli_close ( mysqli $link )
        return mysqli_close($link);
    }
}

//bool mysql_free_result ( resource $result )
function mysql_free_result($result)
{
    mysqli_free_result($result);
}

//mysql_select_db ( string $database_name [, resource $link_identifier = NULL ] )
function mysql_select_db($database_name, $link_identifier = NULL)
{
    if ($link_identifier == NULL)
    {
        return mysqli_select_db(php7emulatorMemory::$lastMysqlLink, $database_name) or die(mysql_error());
    }
    else
    {
        return mysqli_select_db($link_identifier, $database_name);
    }
}


//mixed mysql_query ( string $query [, resource $link_identifier = NULL ] )
function mysql_query($query, $link_identifier = NULL)
{
    if ($link_identifier == NULL)
    {
        return mysqli_query(php7emulatorMemory::$lastMysqlLink, $query); //or die(mysql_error());
    }
    else
    {
        return mysqli_query($link_identifier, $query);
    }
}

//array mysql_fetch_array ( resource $result [, int $result_type = MYSQL_BOTH ] )
function mysql_fetch_array($result, $result_type = MYSQLI_BOTH)
{
    if ($result_type == 'MYSQL_ASSOC')
    {
        $result_type = MYSQLI_ASSOC;
    }
    elseif ($result_type != MYSQLI_BOTH)
    {
        die('UNKNOWN mysql_fetch_array: '.$result_type);
    }
    //mixed mysqli_fetch_array ( mysqli_result $result [, int $resulttype = MYSQLI_BOTH ] )
    return mysqli_fetch_array($result, $result_type);
}

//object mysql_fetch_object ( resource $result [, string $class_name [, array $params ]] )
function mysql_fetch_object($result, $class_name='', $params=array())
{
    //object mysqli_fetch_object ( mysqli_result $result [, string $class_name = "stdClass" [, array $params ]] )
    if ($class_name != '')
    {
        return mysqli_fetch_object($result, $class_name, $params);
    }
    else
    {
        return mysqli_fetch_object($result);
    }
}

//string mysql_real_escape_string ( string $unescaped_string [, resource $link_identifier = NULL ] )
function mysql_real_escape_string($string, $link_identifier = NULL)
{
    if ($link_identifier == NULL)
    {
        return mysqli_real_escape_string(php7emulatorMemory::$lastMysqlLink, $string);
    }
    else
    {
        return mysqli_real_escape_string($link_identifier, $string);
    }
}


function mysql_escape_string($string, $link_identifier = NULL)
{
    return mysql_real_escape_string($string, $link_identifier);
}

function mysql_error($link_identifier = NULL)
{
    if ($link_identifier == NULL)
    {
        return mysqli_error(php7emulatorMemory::$lastMysqlLink);
    }
    else
    {
        return mysqli_error($link_identifier);
    }
}

//int mysql_num_rows ( resource $result )
function mysql_num_rows($result)
{
    return mysqli_num_rows($result);
}

//bool mysql_set_charset ( string $charset [, resource $link_identifier = NULL ] )
function mysql_set_charset($charset, $link_identifier = NULL)
{
    //bool mysqli_set_charset ( mysqli $link , string $charset )
    if ($link_identifier == NULL)
    {
        return mysqli_set_charset(php7emulatorMemory::$lastMysqlLink, $charset);
    }
    else
    {
        return mysqli_set_charset($link_identifier, $charset);
    }
}

//apc_fetch ( mixed $key [, bool &$success ] )
function apc_fetch($key, &$success = NULL)
{
    return apcu_fetch($key, $success);
}

//bool apc_store ( string $key , mixed $var [, int $ttl = 0 ] )
//array apc_store ( array $values [, mixed $unused = NULL [, int $ttl = 0 ]] )
function apc_store($key, $var, $ttl=0)
{
    return apcu_store($key, $var, $ttl);
}

//array apc_cache_info ([ string $cache_type = "" [, bool $limited = false ]] )
function apc_cache_info($cache_type, $limited)
{
    //return apcu_cache_info($cache_type, $limited);
    return apcu_cache_info($limited);
}

//bool apc_add ( string $key , mixed $var [, int $ttl = 0 ] )
//array apc_add ( array $values [, mixed $unused = NULL [, int $ttl = 0 ]] )
function apc_add($key, $var, $ttl=0)
{
    return apcu_add($key, $var, $ttl);
}

//mixed apc_delete ( string $key )
function apc_delete($key)
{
    //mixed apcu_delete
    return apcu_delete($key);
}
