<?PHP
require('../vendor/autoload.php');
use RockstarCode\Streamline\Publish;
use RockstarCode\Streamline\Subscribe;


if (preg_match('/^\/$/',$_SERVER['REQUEST_URI'])) {

    require('frontend.php');

} elseif (preg_match('/^\/streamline.js/',$_SERVER['REQUEST_URI'])) {

    header("Content-Type","text/javascript");
    echo readfile("../dist/streamline.js");

} elseif (preg_match('/^\/subscribe/',$_SERVER['REQUEST_URI'])) {

    $stream = new Subscribe('tester');
    $stream->stream();

} elseif (preg_match('/^\/publish/',$_SERVER['REQUEST_URI'])) {

    $stream = new Publish('tester');
    $stream->send($_POST['message']);

}

