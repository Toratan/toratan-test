<?php
namespace spec\application;
require_once "spec/bootstrap.php";
/**
 * A specification for \application\dbBootstrap
 * @filesource src\application\dbBootstrap.php
 * @author user <b.g.dariush@gmail.com>
 */
class dbBootstrapSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\application\dbBootstrap');
    }
}
