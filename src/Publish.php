<?PHP
namespace RockstarCode\Streamline;

use RockstarCode\Streamline\Stream;

class Publish extends Stream{

    public function send($content){
        return $this->connection->publish($this->channel,$content);
    }

}






