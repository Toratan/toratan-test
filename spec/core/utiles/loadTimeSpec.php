<?php
namespace spec\core\utiles;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\utiles\loadTime
 * @filesource src\core\utiles\loadTime.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class loadTimeSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\utiles\loadTime');
    }
}
