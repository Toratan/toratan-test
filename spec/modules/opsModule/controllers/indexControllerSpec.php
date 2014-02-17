<?php
namespace spec\modules\opsModule\controllers;
require_once "spec/bootstrap.php";
/**
 * A specification for \modules\opsModule\controllers\indexController
 * @filesource src\modules\opsModule\controllers\indexController.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class indexControllerSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\modules\opsModule\controllers\indexController');
    }
}
