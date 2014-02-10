<?php
namespace spec\modules\authModule\controllers;
require_once "spec/bootstrap.php";
/**
 * A specification for \modules\authModule\controllers\indexController
 * @filesource src\modules\authModule\controllers\indexController.php
 * @author user <b.g.dariush@gmail.com>
 */
class indexControllerSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\modules\authModule\controllers\indexController');
    }
}
