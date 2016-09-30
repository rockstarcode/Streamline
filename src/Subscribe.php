<?PHP
namespace RockstarCode\Streamline;

use RedisException;
use RockstarCode\Streamline\Stream;

class Subscribe extends Stream {


    public function subscribe($stream){

        $this->broadcast('ready');
        try {
            $this->handle->subscribe(array($stream), function($redis, $channel, $message){
                if ($message == '.end.'){
                    die('unsubscribe');
                }
                $this->broadcast($message);
            });
        }
        catch (RedisException $e){
            return $e->getMessage().PHP_EOL;
        }

        return true;

    }

    public function stream() { /// basically , if there is data in CRUD, send the message, if just a get, subscribe to path

        header('Content-Encoding', 'chunked');
        header('Transfer-Encoding', 'chunked');
        header('Connection', 'keep-alive');
        $this->subscribe($this->channel);
    }


}






