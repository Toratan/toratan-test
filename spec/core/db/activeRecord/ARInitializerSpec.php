<?php
namespace spec\core\db\activeRecord;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\db\activeRecord\ARInitializer
 * @filesource src\core\db\activeRecord\ARInitializer.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class ARInitializerSpec extends \PhpSpec\ObjectBehavior
{
    /**
     * it tests initializations
     */
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\activeRecord\ARInitializer');
    }
    /**
     * it checkes if PHPAR has been invoked or not
     * @throws \PhpSpec\Exception\Example\FailureException if not invoked
     */
    function it_should_invoke_PHPAR()
    {
        $this->shouldNotThrow("\Exception")->duringExecute();
       if(!\in_array("ActiveRecord\Model", \get_declared_classes()))
           throw new \PhpSpec\Exception\Example\FailureException("expected to find `\ActiveRecord\Model` but didn't!");
    }
}
