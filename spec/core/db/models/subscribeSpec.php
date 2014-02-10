<?php
namespace spec\core\db\models;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\db\models\subscribe
 * @filesource src\core\db\models\subscribe.php
 * @author user <b.g.dariush@gmail.com>
 */
class subscribeSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\models\subscribe');
    }
}
