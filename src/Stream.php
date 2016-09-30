<?PHP
namespace RockstarCode\Streamline;

use Redis;

class Stream {

    private static $defaultConnectionValues = [
        'host'=>'127.0.0.1',
        'port'=>6379,
        'timeout'=>-1,
        'passwordl'=>false
    ];
    /***
     * @var handle global connection for any new stream instance
     */
    private static $connectionHandler;

    /***
     * @var handle a connection for this instance
     */
    private $localConnection;

    /**
     * @var holds active connection
     */
    protected $connection;

    /**
     * @var current channel
     */

    protected $channel;

    public function __construct($channel, $localConnection = false){

        $this->channel = $channel;
        $this->loadDefaults();
        $this->createConnection($localConnection);
    }

    private function loadDefaults(){

        $envVars = [];
        if (!empty(getenv('REDIS_HOST'))) $envVars['host'] = getenv('REDIS_HOST');;
        if (!empty(getenv('REDIS_PORT'))) $envVars['port'] = getenv('REDIS_PORT');;
        if (!empty(getenv('REDIS_PASSWORD'))) $envVars['password'] = getenv('REDIS_PASSWORD');;

        Stream::$defaultConnectionValues = array_replace_recursive(Stream::$defaultConnectionValues,$envVars);
    }

    private function createConnection($localConnection){

        /**
         * Find local connection or use global
         */

        if ($localConnection instanceof \Closure) {

            $this->connection =  call_user_func($localConnection);

        } elseif (is_array($localConnection)) {

            $params = array_replace_recursive(Stream::$defaultConnectionValues, $localConnection);

            $this->connection = Stream::defaultConnection($params);

        } else {

            /**
             * Global Connection
             */

            $this->connection = Stream::connect();

        }
    }

    private static function connect(){

        /**
         * Use global custom, or global default connection
         */

        if (self::$connectionHandler instanceof \Closure){
            return call_user_func(self::$connectionHandler);
        }
        else {
            return self::defaultConnection(Stream::$defaultConnectionValues);
        }
    }


    private static function defaultConnection($params){
        extract($params);

        $client = new Redis();
        $client->connect($host, $port);

        if (!empty($password)){
            $client->auth($password);
        }

        return $client;
    }

    public static function setConnectionHandler($handler){
        self::$connectionHandler = $handler;
    }


}






