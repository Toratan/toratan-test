<?php
namespace spec\core\db\models;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\db\models\profile
 * @filesource src\core\db\models\profile.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class profileSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\models\profile');
    }
}
