<?php
namespace spec\application;
require_once "spec/bootstrap.php";
/**
 * A specification for \application\dbBootstrap
 * @filesource src\application\dbBootstrap.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class dbBootstrapSpec extends \PhpSpec\ObjectBehavior
{
    /**
     * it tests initializations
     */
    function it_is_initializable()
    {
        $this->shouldHaveType('\application\dbBootstrap');
        # if db configured currectly no exception will arise
        $this->shouldNotThrow('\Exception')->duringPRE_init_db();
    }
    /**
     * It tests db swith mode
     */
    function it_tests_db_switch_mode()
    {
        # setting the db config to a valid one should not throw any exceptions
        $this->shouldNotThrow('\zinux\kernel\exceptions\invalidArgumentException')->duringSwitch_database_mode(\application\dbBootstrap::MODE_TORATAN);
        # any invalid mode will arises exceptions
        $this->shouldThrow('\zinux\kernel\exceptions\invalidArgumentException')->duringSwitch_database_mode("SOME_RANDOM_TXT");
    }
}
