<?php
namespace spec\core\db\activeRecord;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\db\activeRecord\ARInitializer
 * @filesource src\core\db\activeRecord\ARInitializer.php
 * @author user <b.g.dariush@gmail.com>
 */
class ARInitializerSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\activeRecord\ARInitializer');
    }
}
