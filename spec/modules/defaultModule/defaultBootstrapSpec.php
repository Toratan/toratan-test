<?php
namespace spec\modules\defaultModule;
require_once "spec/bootstrap.php";
/**
 * A specification for \modules\defaultModule\defaultBootstrap
 * @filesource src\modules\defaultModule\defaultBootstrap.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class defaultBootstrapSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\modules\defaultModule\defaultBootstrap');
    }
}
