<?PHP
namespace RockstarCode\Streamline;

use Redis;

CONST RET = '
';

class Stream {

    protected $handle;
    protected $channel;

    public function __construct($channel){
        $this->handle = new Redis();
        $this->handle->connect('127.0.0.1');
        $this->channel = $channel;
    }

    protected function broadcast($message){

        echo str_repeat(" ", 4096).RET;
        echo $message.RET;

        ob_flush();
        flush();
    }


}






