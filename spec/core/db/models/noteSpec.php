<?php
namespace spec\core\db\models;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\db\models\note
 * @filesource src\core\db\models\note.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class noteSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\models\note');
    }
}
