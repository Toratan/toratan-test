<?php
namespace spec\core\db\models;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\db\models\notification
 * @filesource src\core\db\models\notification.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class notificationSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\models\notification');
    }
}
