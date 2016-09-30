<?PHP
namespace RockstarCode\Streamline;

use RedisException;
use RockstarCode\Streamline\Stream;

CONST RET = '
';

class Subscribe extends Stream {

    /**
     * @var holds current response handler if provided
     */
    protected $handle;

    public function setHandler($handler){
        $this->handle = $handler;
        return $this;
    }

    protected function bubble($message){
        if (isset($this->handle) && $this->handle instanceof \Closure){
            try {
                call_user_func($this->handle, $message);
            }
            catch(\Exception $e){
                throw new \Exception($e->getMessage());
            }
        }
        else {
            $this->feedback($message);
        }
        return $this;

    }

    public function feedback($message){

        echo str_repeat(" ", 4096).RET;
        echo $message.RET;
        echo str_repeat(" ", 4096).RET;
        ob_flush();
        flush();
        return $this;

    }

    public function subscribe($channel){

        $this->bubble(json_encode(['status'=>'ready']));
        try {
            $this->connection->subscribe(array($channel), function($redis, $activeChannel, $message){
                error_log($message);
                $this->bubble($message);
            });
        }
        catch (RedisException $e){
            return $e->getMessage().RET;
        }

        return true;

    }

    public function unsubscribe(){
        $this->connection->close();
    }

    public function stream($handler = false) {

        header('Content-Encoding', 'chunked');
        header('Transfer-Encoding', 'chunked');
        header('Connection', 'keep-alive');

        if ($handler instanceof \Closure){
            $this->setHandler($handler);
        }
        $this->subscribe($this->channel);
    }


}






