<?php
namespace spec\core\db\models;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\db\models\message
 * @filesource src\core\db\models\message.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class messageSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\models\message');
    }
}
