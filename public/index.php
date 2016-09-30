<?PHP
require('../vendor/autoload.php');

if (preg_match('/^\/$/',$_SERVER['REQUEST_URI'])) {
    require('frontend.php');
} elseif (preg_match('/^\/subscribe/',$_SERVER['REQUEST_URI'])) {
    require('subscribe.php');
} elseif (preg_match('/^\/publish/',$_SERVER['REQUEST_URI'])) {
    require('publish.php');
}

