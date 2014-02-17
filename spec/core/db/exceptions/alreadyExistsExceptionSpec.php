<?php
namespace spec\core\db\exceptions;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\db\exceptions\alreadyExistsException
 * @filesource src\core\db\exceptions\alreadyExistsException.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class alreadyExistsExceptionSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\exceptions\alreadyExistsException');
    }
}
