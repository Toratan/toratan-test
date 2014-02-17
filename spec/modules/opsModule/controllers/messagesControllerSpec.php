<?php
namespace spec\modules\opsModule\controllers;
require_once "spec/bootstrap.php";
/**
 * A specification for \modules\opsModule\controllers\messagesController
 * @filesource src\modules\opsModule\controllers\messagesController.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class messagesControllerSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\modules\opsModule\controllers\messagesController');
    }
}
