<?php 
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/services/AMF.php';

use Illuminate\Database\Capsule\Manager as Capsule;

//--- import for oracle -----
use yajra\Oci8\Connectors\OracleConnector;
use yajra\Oci8\Oci8Connection;
//--- import for Monogodb -----
// use Jenssegers\Mongodb\Connection as Connection;


$capsule = new Capsule;

/*
$manager = $capsule->getDatabaseManager()->extend('mongodb', function($config){
    return new Connection($config);
});
*/

// for Oracle connection  --- start ------
$manager = $capsule->getDatabaseManager();
$manager->extend('oracle', function($config)
{
    $connector = new OracleConnector();
    $connection = $connector->connect($config);
    $db = new Oci8Connection($connection, $config["database"], $config["prefix"]);
    // set oracle date format to match PHP's date
    $db->setDateFormat('YYYY-MM-DD HH24:MI:SS');
    return $db;
});
// for Oracle connection  --- end ------



$capsule->addConnection(

       // driver for oracle   
      //  [  'driver'   => 'oracle',
      //   'host'     => 'localhost',
      //   'database' => 'xe',
      //   'username' => 'system',
      //   'password' => 'roottoor',
      //   'prefix'   => '',
      //   'port'  => 1521
      // ]

        // [
        //     'driver'   => 'pgsql',
        //     'host'     => 'localhost',
        //     'database' => 'test',
        //     'username' => 'openpg',
        //     'password' => 'openpgpwd',
        //     'charset'  => 'utf8',
        //     'prefix'   => '',
        //     'schema'   => 'public',
        // ]

    // TOMATO_COM1 // Windows Auth
    [
    'driver'   => 'sqlsrv',
    'host'     => '127.0.0.1', 
    'database' => 'test',
    'username' => NULL,
    'password' => NULL,
    'prefix'   => '',
    ]

    // [
    // 'driver'   => 'sqlsrv',
    // 'host'     => '127.0.0.1', 
    // 'database' => 'test',
    // 'username' => 'sa',
    // 'password' => 'roottoor',
    // 'prefix'   => '',

    // ]


//     [
//     'driver'    => 'mysql',
//     'host'      => 'localhost',
//     'database'  => 'fieldlogger',
//     'username'  => 'root',
//     'password'  => '',
//     'charset'   => 'utf8',
//     'collation' => 'utf8_unicode_ci',
//     'prefix'    => '',
// ]

// [    'driver'   => 'sqlite',
//      'database' => __DIR__.'/services/fieldlogger.db',
//     'prefix'   => '',
// ]


);




// Set the event dispatcher used by Eloquent models... (optional)
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

$capsule->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

// class Info extends Model {
//     protected $table = 'field_infoservice';
// }
// $a = "select * from  field_infoservice ";
// $rs = Capsule::raw($a);//->get();
// var_dump($rs);

// $rs = Info::all();
// $rs = Info::where('serviceNo','=',2)->get()->first();
// var_dump($rs->toArray());
// var_dump($rs->toJson());
// echo json_encode($rs);

function consolelog($status = 200) {
        $lists = func_get_args();
        $status = '';
        foreach ($lists as $list) {
          $status .= json_encode(json_decode(json_encode($list,true)));
        }

       if(isset($_SERVER["REMOTE_ADDR"]) && !empty($_SERVER["REMOTE_ADDR"])){
          $raddr =$_SERVER["REMOTE_ADDR"];
       } else {
          $raddr = '127.0.0.1';
       }

       if(isset($_SERVER["REMOTE_PORT"]) && !empty($_SERVER["REMOTE_PORT"])){
          $rport = $_SERVER["REMOTE_PORT"];
       } else {
          $rport = '8000';
       }

       if(isset($_SERVER["REQUEST_URI"]) && !empty($_SERVER["REQUEST_URI"])){
          $ruri = $_SERVER["REQUEST_URI"];
       } else {
          $ruri = '/console';
       }

       file_put_contents("php://stdout",
           sprintf("[%s] %s:%s [%s]:%s \n",
               date("D M j H:i:s Y"),
               $raddr,$rport,
               $status,
               $ruri
               )
           );
       echo '<script>console.log(\''.$status.'\');</script>';
}

 function toObj($var){
    return  json_decode(json_encode($var),FALSE);
 }

 function toArray($var){
    return  json_decode(json_encode($var),TRUE);
 }

function converdate($date){
    if($date == 'Present') return $date;
    $de = new DateTime($date);
    return $de->format('M d, Y');    
}


trait Singleton
{
    private static $instance;
 
    public static function getInstance() {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}
