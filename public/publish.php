<?PHP

use RockstarCode\Streamline\Publish;

$stream = new Publish('tester');
$stream->send($_POST['message']);