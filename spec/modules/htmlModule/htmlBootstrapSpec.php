<?php
namespace spec\modules\htmlModule;
require_once "spec/bootstrap.php";
/**
 * A specification for \modules\htmlModule\htmlBootstrap
 * @filesource src\modules\htmlModule\htmlBootstrap.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class htmlBootstrapSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\modules\htmlModule\htmlBootstrap');
    }
}
