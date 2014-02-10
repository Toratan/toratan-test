<?php
namespace spec\modules\defaultModule\controllers;
require_once "spec/bootstrap.php";
/**
 * A specification for \modules\defaultModule\controllers\indexController
 * @filesource src\modules\defaultModule\controllers\indexController.php
 * @author user <b.g.dariush@gmail.com>
 */
class indexControllerSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\modules\defaultModule\controllers\indexController');
    }
}
