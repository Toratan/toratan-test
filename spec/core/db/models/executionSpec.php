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
    /**
     * it tests initializations
     */
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\models\execution');
    }
    /**
     * tests recordings
     */
    function it_should_record()
    {
        # create a loatime instance
        $lt = new \core\utiles\loadTime;
        # record the load time
        $this->shouldNotThrow('\Exception')->duringRecord($lt);
        # the count of executions should be greater than 0
        $this->count()->shouldBeGreaterThan(0);
        # fetch the count of execution after the new record
        $count = \core\db\models\execution::count();
        # add an other record and also checking that the result should be the end time of load time and it's numeric
        $this->record($lt)->shouldBeNumeric();
        # the difference between current count of execution should one unit ahead of previously count
        $this->count()->shouldBeLike($count + 1);
    }
    /**
     * test getting average load
     */
    function it_should_get_average_load_time()
    {
        # no exception expected during getting average load time
        $this->shouldNotThrow("\Exception")->duringGet_average_load_time();
        # also the result should be always numberic
        $this->get_average_load_time()->shouldBeNumeric();
    }
    /**
     * defines new matchers
     * @return array
     */
    public function getMatchers()
    {
        return [
                'beGreaterThan' => 
                    function($subject, $value) { return $subject > $value;}
        ];
    }
}
