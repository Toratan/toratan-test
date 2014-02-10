<?php
namespace spec\modules\opsModule;
require_once "spec/bootstrap.php";
/**
 * A specification for \modules\opsModule\opsBootstrap
 * @filesource src\modules\opsModule\opsBootstrap.php
 * @author user <b.g.dariush@gmail.com>
 */
class opsBootstrapSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\modules\opsModule\opsBootstrap');
    }
}
