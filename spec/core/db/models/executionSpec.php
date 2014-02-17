<?php
namespace spec\core\db\models;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\db\models\execution
 * @filesource src\core\db\models\execution.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class executionSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\models\execution');
    }
}
