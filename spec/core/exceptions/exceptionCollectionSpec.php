<?php
namespace spec\core\exceptions;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\exceptions\exceptionCollection
 * @filesource src\core\exceptions\exceptionCollection.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class exceptionCollectionSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\exceptions\exceptionCollection');
    }
}
