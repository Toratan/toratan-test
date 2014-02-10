<?php
namespace spec\core\db\models;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\db\models\user
 * @filesource src\core\db\models\user.php
 * @author user <b.g.dariush@gmail.com>
 */
class userSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\models\user');
    }
}
