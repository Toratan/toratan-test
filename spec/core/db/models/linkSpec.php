<?php
namespace spec\core\db\models;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\db\models\link
 * @filesource src\core\db\models\link.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class linkSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\models\link');
    }
}
