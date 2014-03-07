<?php
namespace spec\core\db\models;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\db\models\message
 * @filesource src\core\db\models\message.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class messageSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\models\message');
        # no initial message
        $this->count()->shouldBeLike(0);
    }
    function it_sends_messages()
    {
        $faker = \Faker\Factory::create();
        $faker->seed(time());
        $this->shouldThrow(new \zinux\kernel\exceptions\dbException("Message can't be blank"))->duringSend(\spec\core\db\models\userSpec::getUser()->user_id, \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id, NULL);
        $this->shouldNotThrow(new \zinux\kernel\exceptions\dbException("Message can't be blank"))->duringSend(\spec\core\db\models\userSpec::getUser()->user_id, \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id, implode(PHP_EOL, $faker->paragraphs));
        $this->count()->shouldBeLike(1);
    }
}
