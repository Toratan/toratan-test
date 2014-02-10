<?php
namespace spec\modules\authModule;
require_once "spec/bootstrap.php";
/**
 * A specification for \modules\authModule\authBootstrap
 * @filesource src\modules\authModule\authBootstrap.php
 * @author user <b.g.dariush@gmail.com>
 */
class authBootstrapSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\modules\authModule\authBootstrap');
    }
}
