<?php
namespace spec\core\utiles;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\utiles\messagePipe
 * @filesource src\core\utiles\messagePipe.php
 * @author user <b.g.dariush@gmail.com>
 */
class messagePipeSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\utiles\messagePipe');
    }
}
