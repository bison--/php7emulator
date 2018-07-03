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
    public static $connectFlags = NULL;
    
    
    public function enableCompression()
    {
        self::$connectFlags = MYSQLI_CLIENT_COMPRESS;
    }

    public function disableCompression()
    {
        self::$connectFlags = NULL;
    }
}

//$link = mysql_connect('example.com:3307', 'mysql_user', 'mysql_password'); new_link
function mysql_connect($server, $username='', $password='')
{
    //print $server;
    php7emulatorMemory::$lastMysqlLink = mysqli_init();
    if (!mysqli_real_connect(php7emulatorMemory::$lastMysqlLink, $server, $username, $password, NULL, NULL, NULL, php7emulatorMemory::$connectFlags))
    {
         die('NO CONNECTION! "'.$server.'" "'.$username.'"');
    }


    //php7emulatorMemory::$lastMysqlLink = mysqli_connect($server, $username, $password) or die('NO CONNECTION! "'.$server.'" "'.$username.'"');
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

//bool mysql_select_db ( string $database_name [, resource $link_identifier = NULL ] )
function mysql_select_db($database_name, $link_identifier = NULL)
{
    if ($link_identifier == NULL)
    {
        //.debug_print_backtrace()
        return mysqli_select_db(php7emulatorMemory::$lastMysqlLink, $database_name) or die('mysql_select_db: "'.$database_name.'" -> "'.mysql_error().'"');
    }
    else
    {
        return mysqli_select_db($link_identifier, $database_name) or die('mysql_select_db: "'.$database_name.'" -> "'.mysql_error().'"');
    }
}

//mixed mysql_query ( string $query [, resource $link_identifier = NULL ] )
function mysql_query($query, $link_identifier = NULL)
{
    if (is_null($link_identifier))
    {
        $link_identifier = php7emulatorMemory::$lastMysqlLink;
    }

    $res = mysqli_query($link_identifier, $query);
    if ($res === false && function_exists('_Log')){
        _Log('php7emulator mysql_query ERROR return false at query: '.$query.' Error: '.$link_identifier->error);
    }
    return $res; //or die(mysql_error());
}

//resource mysql_db_query ( string $database , string $query [, resource $link_identifier = NULL ] )
function mysql_db_query($database, $query, $link_identifier = null) 
{
    $selectOk = mysql_select_db($database, $link_identifier);
    if ($selectOk === false) {
        return false;
    }
    
    return mysql_query($query, $link_identifier);
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

//array mysql_fetch_assoc ( resource $result )
function mysql_fetch_assoc($result)
{
    return mysqli_fetch_assoc($result);
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

//int mysql_num_fields ( resource $result )
function mysql_num_fields($result)
{
    return mysqli_num_fields($result);
}

//object mysql_fetch_field ( resource $result [, int $field_offset = 0 ] )
function mysql_fetch_field($result, $field_offset = 0)
{
    //return mysqli_fetch_field($result);  // had to ignore field-offset, if iterated it SHOULD work
    // better!
    return mysqli_fetch_field_direct($result, $field_offset);
}

//int mysql_field_len ( resource $result , int $field_offset )
function mysql_field_len($result, $field_offset)
{
    return mysqli_fetch_field_direct($result, $field_offset)->length;
}

//int mysql_affected_rows ([ resource $link_identifier = NULL ] )
function mysql_affected_rows($link_identifier = NULL)
{
    //int mysqli_affected_rows ( mysqli $link )
    if ($link_identifier == NULL)
    {
        return mysqli_affected_rows(php7emulatorMemory::$lastMysqlLink);
    }
    else
    {
        return mysqli_affected_rows($link_identifier);
    }
}

//int mysql_insert_id ([ resource $link_identifier = NULL ] )
function mysql_insert_id($link_identifier = NULL)
{
    //mixed mysqli_insert_id ( mysqli $link )
    if ($link_identifier == NULL)
    {
        return mysqli_insert_id(php7emulatorMemory::$lastMysqlLink);
    }
    else
    {
        return mysqli_insert_id($link_identifier);
    }
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

//bool mysql_data_seek ( resource $result , int $row_number )
function mysql_data_seek($result, $row_number)
{
    //bool mysqli_data_seek ( mysqli_result $result , int $offset )
    return mysqli_data_seek($result, $row_number);
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
function apc_cache_info($cache_type, $limited = false)
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

//array split ( string $pattern , string $string [, int $limit = -1 ] )
function split($pattern, $string, $limit=-1)
{
    //array preg_split ( string $pattern , string $subject [, int $limit = -1 [, int $flags = 0 ]] )
    
    $string = $string.''; // make sure its a string!
    if ($pattern[0] != '/')
    {
       $pattern = '/'.$pattern; 
    }
    
    if ($pattern[ strlen($pattern) -1 ] != '/')
    {
       $pattern = $pattern.'/'; 
    }
    
    return preg_split($pattern, $string, $limit);
}

// Fix for removed Session functions
// via: http://php.net/manual/de/function.session-register.php#96241
//bool session_register ( mixed $name [, mixed $... ] )
function session_register(){
    $args = func_get_args();
    foreach ($args as $key){
        $_SESSION[$key]=$GLOBALS[$key];
    }
}
function session_is_registered($key){
    return isset($_SESSION[$key]);
}
function session_unregister($key){
    unset($_SESSION[$key]);
}

