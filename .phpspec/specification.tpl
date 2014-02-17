<?php
namespace %namespace%;
require_once "spec/bootstrap.php";
/**
 * A specification for \%subject%
 * @filesource src\%subject%.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class %name% extends \PhpSpec\ObjectBehavior
{

    /**
     * it tests initializations
     */
    function it_is_initializable()
    {
        $this->shouldHaveType('\%subject%');
    }
}
