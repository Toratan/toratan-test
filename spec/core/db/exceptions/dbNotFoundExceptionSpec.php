<?php
namespace spec\core\db\exceptions;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\db\exceptions\dbNotFoundException
 * @filesource src\core\db\exceptions\dbNotFoundException.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class dbNotFoundExceptionSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\exceptions\dbNotFoundException');
    }
}
