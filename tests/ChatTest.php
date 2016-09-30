<?php
require ('vendor/autoload.php');

use Icicle\Loop;
use Icicle\Coroutine;
use Icicle\Concurrent\Forking\Fork;

use \PHPUnit\Framework\TestCase;
use RockstarCode\Streamline\Publish;
use RockstarCode\Streamline\Subscribe;

class ChatTest extends TestCase
{

    public function testChatUsers()
    {

        $test_input='this is my great message';

        $tester = $this;

        $path = realpath(dirname(__FILE__).'/helpers/publish.php');

        shell_exec("php $path testing '$test_input' > /dev/null 2>/dev/null &");

        /**
         * Initiate User Instances
         */
        $userA = new Subscribe('testing');
        $userB = new Subscribe('testing');

        $response = [];

        /**
         * @param bool $message  Final assertions to run
         */

        $finalTests = function($message=false) use(&$response){
            if (count($response) == 2){
                $this->assertEquals($response['userA'],$message);
                $this->assertEquals($response['userB'],$message);
                $this->assertEquals($response['userA'],$response['userB']);
            }
        };

        /**
         * Forked Instances
         */

        $waitable = Coroutine\create(function () use ($userA){

            $user1 = Fork::spawn(function () use ($userA){

                $outbound = null;
                $userA->setHandler(function($message)   use ($userA,&$outbound){
                    if ($message != 'ready'){
                        $userA->unsubscribe();
                        $outbound = $message;
                    }
                });

                $userA->subscribe('testing');
                return $outbound;
            });

            yield $user1->join();

        });


        $waitable->done(function($message) use(&$response,&$finalTests){
            $response['userA'] = $message;
            $finalTests($message);
        });


        $waitable2 = Coroutine\create(function () use ($userB){

            $user2 = Fork::spawn(function () use ($userB){

                $outbound = null;
                $userB->setHandler(function($message)   use ($userB,&$outbound){
                    if ($message != 'ready'){
                        $userB->unsubscribe();
                        $outbound = $message;
                    }
                });

                $userB->subscribe('testing');
                return $outbound;
            });

            yield $user2->join();

        });


        $waitable2->done(function($message) use(&$response,&$finalTests){
                $response['userB'] = $message;
            $finalTests($message);
        });


        Loop\run();

    }

}
