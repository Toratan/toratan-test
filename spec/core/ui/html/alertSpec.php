<?php
namespace spec\core\ui\html;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\ui\html\alert
 * @filesource src\core\ui\html\alert.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class alertSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\ui\html\alert');
    }
}
