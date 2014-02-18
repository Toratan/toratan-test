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
    private static $users = array();
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\models\user');
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
                self::USER_ZERO => array("USER0", "NOMAIL@MAIL0.COM", "00"),
                self::USER_ONE   => array("USER1", "NOMAIL@MAIL1.COM", "01"),
                self::USER_TWO  => array("USER2", "NOMAIL@MAIL2.COM", "02"),
        ) as $key => $user)
        {
            try
            {
                $u = new \core\db\models\user;
                $u->Signup($user[0], $user[1], $user[2]);
                self::$users[$key] = $u;
            }
            # ingnore already existed users
            catch(\core\db\exceptions\alreadyExistsException $aee) { unset($aee); }
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
}