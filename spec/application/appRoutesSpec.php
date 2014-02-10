<?php
namespace spec\application;
require_once "spec/bootstrap.php";
/**
 * A specification for \application\appRoutes
 * @filesource src\application\appRoutes.php
 * @author user <b.g.dariush@gmail.com>
 */
class appRoutesSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\application\appRoutes');
    }
}
