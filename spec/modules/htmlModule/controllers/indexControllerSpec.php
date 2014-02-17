<?php
namespace spec\modules\htmlModule\controllers;
require_once "spec/bootstrap.php";
/**
 * A specification for \modules\htmlModule\controllers\indexController
 * @filesource src\modules\htmlModule\controllers\indexController.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class indexControllerSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\modules\htmlModule\controllers\indexController');
    }
}
