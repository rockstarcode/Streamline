<?PHP
require (dirname(__FILE__)."/../../vendor/autoload.php");
use RockstarCode\Streamline\Publish;

sleep(2);
$stream = new Publish($argv[1]);
$stream->send($argv[2]);