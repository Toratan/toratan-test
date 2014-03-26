<?php
namespace spec\core\db\models;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\db\models\subscribe
 * @filesource src\core\db\models\subscribe.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class subscribeSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\models\subscribe');
    }
    function it_is_makeing_subscription()
    {
        # firstly if we subscribe with any invalid user ID it should throw exception
        $this->shouldThrow('\ActiveRecord\DatabaseException')->duringSubscribe("FAKE UID 1", "FAKE UID 2");
        $this->shouldThrow('\ActiveRecord\DatabaseException')->duringSubscribe(\spec\core\db\models\userSpec::getUser()->user_id, "FAKE UID 2");
        $this->shouldNotThrow('\ActiveRecord\DatabaseException')->duringSubscribe(
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ZERO)->user_id,
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id);
        $this->shouldNotThrow('\ActiveRecord\DatabaseException')->duringSubscribe(
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id,
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ZERO)->user_id);
    }
    function it_validate_subscription()
    {
        $this->has_subscribed("FAKE UID 1", "FAKE UID 2")->shouldBeLike(FALSE);
        $this->has_subscribed(
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id,
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)->user_id)->shouldBeLike(FALSE);
        $this->has_subscribed(
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)->user_id,
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id)->shouldBeLike(FALSE);
        $this->has_subscribed(
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)->user_id,
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ZERO)->user_id)->shouldBeLike(FALSE);
        $this->has_subscribed(
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ZERO)->user_id,
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)->user_id)->shouldBeLike(FALSE);
        $this->has_subscribed(
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ZERO)->user_id,
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id)->shouldBeLike(TRUE);
        $this->has_subscribed(
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id,
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ZERO)->user_id)->shouldBeLike(TRUE);
        # we have 2 subscription till now!
        $this->count()->shouldBeLike(2);
    }
    function it_tests_subscribers()
    {
        $this->fetch_subscribed("FAKE UID 1")->shouldHaveCount(0);
        $this->fetch_subscribed(\spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ZERO)->user_id)->shouldHaveCount(1);
        $this->fetch_subscribed(\spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id)->shouldHaveCount(1);
        $this->fetch_subscribed(\spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)->user_id)->shouldHaveCount(0);
        
        $s = new \core\db\models\subscribe;
        
        $uZERO = \array_shift($s->fetch_subscribed(\spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ZERO)->user_id));
        if ($uZERO->followed
            !== 
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id)
            throw new \PhpSpec\Exception\Example\FailureException("Expecting USER_ONE following USER_ZERO, but expectation didn't satisfied!!");
        
        $uONE = \array_shift($s->fetch_subscribed(\spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id));
        if ($uONE->followed
            !== 
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ZERO)->user_id)
            throw new \PhpSpec\Exception\Example\FailureException("Expecting USER_ZERO following USER_ONE, but expectation didn't satisfied!!");
    }
    function it_tests_unsubscription()
    {
        # we have 2 subscription till now!
        $this->count()->shouldBeLike(2);
        $this->unsubscribe("FAKE UID 1", "FAKE UID 2")->shouldBeLike(0);
        
        $this->has_subscribed(
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ZERO)->user_id,
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id)->shouldBeLike(TRUE);
        $this->has_subscribed(
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id,
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ZERO)->user_id)->shouldBeLike(TRUE);
        
        $this->unsubscribe(
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id,
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ZERO)->user_id)->shouldBeLike(1);
        
        $this->unsubscribe(
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ZERO)->user_id,
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id)->shouldBeLike(1);
        
        $this->has_subscribed(
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ZERO)->user_id,
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id)->shouldBeLike(FALSE);
        $this->has_subscribed(
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id,
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ZERO)->user_id)->shouldBeLike(FALSE);
        
        $this->unsubscribe(
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id,
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ZERO)->user_id)->shouldBeLike(0);
        $this->unsubscribe(
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ZERO)->user_id,
            \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id)->shouldBeLike(0);        
        # we don't have any subscription now!
        $this->count()->shouldBeLike(0);
    }
}
