<?PHP
require (dirname(__FILE__)."/../../vendor/autoload.php");
use RockstarCode\Streamline\Subscribe;

$stream = new Subscribe($argv[1]);
$stream->stream(function($message) use ($stream){
    if ($message !== 'ready'){
        die($message);
    }
});