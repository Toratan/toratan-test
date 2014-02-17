<?php
namespace spec\modules\opsModule\controllers;
require_once "spec/bootstrap.php";
/**
 * A specification for \modules\opsModule\controllers\notificationsController
 * @filesource src\modules\opsModule\controllers\notificationsController.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class notificationsControllerSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\modules\opsModule\controllers\notificationsController');
    }
}
