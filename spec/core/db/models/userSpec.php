<?php
namespace spec\core\db\models;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\db\models\user
 * @filesource src\core\db\models\user.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class userSpec extends \PhpSpec\ObjectBehavior
{
    const USER_ZERO = 0;
    const USER_ONE = 1;
    const USER_TWO = 2;
    /**
     * the internal users cache
     * @var array
     */
    private static $users = array();
    
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\models\user');
        $this->createUsers();
    }
   /**
    * @param integer $uid the user ID#
    * @return \core\db\models\user
    * @throws \PhpSpec\Exception\Example\FailureException if no initialization launched
    */
    public static function getUser($uid = self::USER_ZERO)
    {
        switch($uid)
        {
            case self::USER_ZERO:
            case self::USER_ONE:
            case self::USER_TWO:
                if(!isset(self::$users[$uid]))
                    throw new \PhpSpec\Exception\Example\FailureException("User# $uid is no initialized!");
                return self::$users[$uid];
                break;
            default:
                throw new \PhpSpec\Exception\Example\FailureException("No user# $uid exists!");
        }
    }
    /**
     * adds three <b>\spec\core\db\models\userSpec::USER_ZERO</b>,
     * <b>\spec\core\db\models\userSpec::USER_ONE</b>,
     * <b>\spec\core\db\models\userSpec::USER_TWO</b>,
     */
    public static function createUsers()
    {
        # define three sample user info
        foreach(array(
                self::USER_ZERO => array("USER0", "USER0@HOST0.COM", "PASS0"),
                self::USER_ONE   => array("USER1", "USER1@HOST1.COM", "PASS1"),
                self::USER_TWO  => array("USER2", "USER2@HOST2.COM", "PASS2"),
        ) as $uid => $user)
        {
            try
            {
                $u = new \core\db\models\user;
                $u->Signup($user[0], $user[1], $user[2]);
                self::$users[$uid] = $u;
            }
            # ingnore already existed users
            catch(\core\db\exceptions\alreadyExistsException $aee) { unset($aee); self::$users[$uid] = \core\db\models\user::Fetch($user[0]); }
            # if any other exceptions get thrown
            catch(\core\exceptions\exceptionCollection $ce)
            {
                foreach($ce->getCollection() as $exception)
                    # throw it too
                    throw $exception;
            }
        }
    }
    /**
     * deletes all created users
     */
    public static function deleteUsers()
    {
        foreach(array(self::USER_ZERO, self::USER_ONE, self::USER_TWO) as $uid)
        {
            self::getUser($uid)->delete();
            unset(self::$users[$uid]);
        }
    }
    
    function it_tests_fetch()
    {
        # we assume we have 3 users already inserted into db in initialization()
        # and also we have a ROOT user too
        $this->count()->shouldBeLike(3 + 1);
        foreach(
            array(
                    self::USER_ZERO => self::getUser(self::USER_ZERO),
                    self::USER_ONE => self::getUser(self::USER_ONE),
                    self::USER_TWO => self::getUser(self::USER_TWO)
            ) as $index => $user)
        {
            $this->fetch($user->user_id, "PASS$index.WRONG")->shouldBeNull();
            $this->fetch($user->user_id)->shouldBeAnInstanceOf('\core\db\models\user');
            $this->fetch($user->user_id, "PASS$index")->shouldBeAnInstanceOf('\core\db\models\user');
            $u = new \core\db\models\user;
            if ($u->Fetch($user->user_id) != self::getUser($index))
                throw new \PhpSpec\Exception\Example\FailureException("Expecting the 2 user be equal but are not!!");
        }
        # a fail safe test, nothing has been touched!!
        $this->count()->shouldBeLike(3 + 1);
    }
    function it_test_signups()
    {
        # we assume we have 3 users already inserted into db in initialization()
        # and also we have a ROOT user too
        $this->count()->shouldBeLike(3 + 1);
        # get an already existed user
        $user = self::getUser();
        # we'll try to sign up an existed user, but we expect to get blocked
        $this->shouldThrow(new \core\db\exceptions\alreadyExistsException("Entity already exists!"))->duringSignup($user->username, $user->email, "PASS0");
        $this->shouldThrow(new \core\db\exceptions\alreadyExistsException("Entity already exists!"))->duringSignup($user->username, "SOME_EMAIL@mail.com", "PASS0");
        $this->shouldThrow(new \core\db\exceptions\alreadyExistsException("Entity already exists!"))->duringSignup("SOMEUSERNAME", $user->email, "PASS0");
        # we'll try to add invalid username and mail, but we expect to get block each time
        $u = new \core\db\models\user;
        try
        {
            # try to signup an invalid user
            # make sure you 100% get currect exception everytime
            $this->shouldThrow('\core\exceptions\exceptionCollection')->duringSignup("SPECIAL_CHAR_USERNAME@", "INVALID_MAIL", "S0W3_bA55/W*RCI");
            # make a signup to analyze the exception's details
            $u->Signup("SPECIAL_CHAR_USERNAME@", "INVALID_MAIL", "S0W3_bA55/W*RCI");
        }
        catch(\core\exceptions\exceptionCollection $ec)
        {
            $eCollection = $ec->getCollection();
            if (count($eCollection) !== 2)
                throw new \PhpSpec\Exception\Example\FailureException("Expect to get 2 sub-expception, but got ".count($eCollection));
            if (
                $eCollection[0]->getMessage()
                !==
                "Username 'SPECIAL_CHAR_USERNAME@' contains <a href='http://en.wikipedia.org/wiki/Special_characters' title='See wikipedia' target='__blank'>special characters</a>!"
                )
                    throw new \PhpSpec\Exception\Example\FailureException("Didn't get the expected exception value insted got '{$eCollection[0]->getMessage()}");
            if (
                $eCollection[1]->getMessage()
                !==
                "Email 'INVALID_MAIL' is not a valid email address!"
                )
                    throw new \PhpSpec\Exception\Example\FailureException("Didn't get the expected exception value insted got '{$eCollection[0]->getMessage()}");
        }
        # sign up a valid user
        $this->shouldNotThrow()->duringSignup("SOMEUSERNAME", "OK@EMAIL.com", "RANDOM_PASS");
        # test if did OK?
        $this->fetch("SOMEUSERNAME")->shouldBeAnInstanceOf('\core\db\models\user');
        # we should have a new member now
        $this->count()->shouldBeLike(3 + 1 + 1);
    }
}