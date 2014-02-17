<?php
namespace spec\core\db\models;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\db\models\folder
 * @filesource src\core\db\models\folder.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class folderSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\models\folder');
    }
}
