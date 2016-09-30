<?PHP
namespace RockstarCode\Streamline;

use Redis;

CONST RET = '
';

class Stream {

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

        if ($localConnection instanceof \Closure) {

            $this->connection =  call_user_func($localConnection);

        } else {

            $this->connection = Stream::connect();

        }

        $this->channel = $channel;
    }


    private static function connect(){
        if (self::$connectionHandler instanceof \Closure){
            return call_user_func(self::$connectionHandler);
        }
        else {
            return self::defaultConnection();
        }
    }


    private static function defaultConnection(){
        $client = new Redis();
        $client->connect('127.0.0.1');
        return $client;
    }

    public static function setConnectionHandler($handler){
        self::$connectionHandler = $handler;
    }

}






