<?php
namespace spec\core\utiles;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\utiles\script
 * @filesource src\core\utiles\script.php
 * @author user <b.g.dariush@gmail.com>
 */
class scriptSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\utiles\script');
    }
}
