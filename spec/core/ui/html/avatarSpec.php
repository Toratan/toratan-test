<?php
namespace spec\core\ui\html;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\ui\html\avatar
 * @filesource src\core\ui\html\avatar.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class avatarSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\ui\html\avatar');
    }
}
