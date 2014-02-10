<?php
namespace spec\modules\opsModule\controllers;
require_once "spec/bootstrap.php";
/**
 * A specification for \modules\opsModule\controllers\profileController
 * @filesource src\modules\opsModule\controllers\profileController.php
 * @author user <b.g.dariush@gmail.com>
 */
class profileControllerSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\modules\opsModule\controllers\profileController');
    }
}
