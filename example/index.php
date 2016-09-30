<?PHP
require('../vendor/autoload.php');
use RockstarCode\Streamline\Publish;
use RockstarCode\Streamline\Subscribe;


if (preg_match('/^\/$/',$_SERVER['REQUEST_URI'])) {
    $base = $_SERVER['HTTP_HOST'];
    include('frontend.php');

} elseif (preg_match('/^\/streamline.js/',$_SERVER['REQUEST_URI'])) {

    header("Content-Type","text/javascript");
    echo readfile("../dist/streamline.js");

} elseif (preg_match('/^\/subscribe/',$_SERVER['REQUEST_URI'])) {

    $stream = new Subscribe('tester');
    $stream->setHandler(function($message) use($stream) {
        if ($message == ':::end:::') {
            $stream->unsubscribe();
        }
        else {
            $stream->feedback($message);
        }
    });
    $stream->stream();

} elseif (preg_match('/^\/publish/',$_SERVER['REQUEST_URI'])) {

    $stream = new Publish('tester');
    $stream->send(json_encode(['user'=>$_POST['user'],'message'=>$_POST['message']]));

}

