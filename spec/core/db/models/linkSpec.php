<?php
namespace spec\core\db\models;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\db\models\link
 * @filesource src\core\db\models\link.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class linkSpec extends \PhpSpec\ObjectBehavior
{
    /**
     * @var \core\db\models\link
     */
    public $o;
    public static $links = array();
    
    public function __construct()
    {
        $this->o = new \core\db\models\link;
    }
    /**
     * tests the initializations
     */
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\models\link');
        # at initial only the root link exists
        $this->count()->shouldBeLike(0);
    }
    /**
     * tests link add
     */
    function it_adds_link_to_root()
    {
        $faker = \Faker\Factory::create();
        $faker->seed(time());
        # adding some sublinks
        for($root_id = 0; $root_id <= 2; $root_id++)
        {
            for($link_id = 1; $link_id <= 2; $link_id++)
            {
                # gen. a name for link
                $name = $faker->sentence(3);
                # gen. a uri for link
                $email = $faker->url;
                # add a link
                $this->newItem($name, $email, $root_id, \spec\core\db\models\userSpec::getUser()->user_id)->shouldReturnAnInstanceOf(new \core\db\models\link);
                # record the added item into cache
                self::$links[] = $this->o->last();
                # add the same link again
                $this->shouldNotThrow()->duringNewItem($name, $email, $root_id, \spec\core\db\models\userSpec::getUser()->user_id);
                # record the added item into cache
                self::$links[] = $this->o->last();
            }
        }
        # the count of links should be 3*2*2
        $this->count()->shouldBeLike(count(self::$links));
    }
    /**
     * tests fetches
     */
    function it_fetches_links()
    {
        $faker = \Faker\Factory::create();
        $faker->seed(time());
        $count = 0;
        foreach(self::$links as $link)
        {
            $root_id = $link->parent_id;
            $count++;
            # fetch the item# link_id see it fetches right
            $this->fetch($link->link_id, \spec\core\db\models\userSpec::getUser()->user_id)->shouldReturnAnInstanceOf(new \core\db\models\link);
            # should throw for some invalid/non-existance id# fetch
            $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($faker->randomNumber(-10,-1));
            # no public link yet
            $this->fetchItems(\spec\core\db\models\userSpec::getUser()->user_id, $root_id, \core\db\models\link::FLAG_SET)->shouldHaveCount(0);
            # no trashed link yet
            $this->fetchItems(\spec\core\db\models\userSpec::getUser()->user_id, $root_id, \core\db\models\link::WHATEVER, \core\db\models\link::FLAG_SET)->shouldHaveCount(0);
            # no archive link yet
            $this->fetchItems(\spec\core\db\models\userSpec::getUser()->user_id, $root_id, \core\db\models\link::WHATEVER, \core\db\models\link::WHATEVER, \core\db\models\link::FLAG_SET)->shouldHaveCount(0);
            
        }
        # the count of links should be 3*2*2
        $this->count()->shouldBeLike($count);
        # for other user no link has been created yet
        $this->fetchItems(\spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id, $root_id)->shouldHaveCount(0);
        $this->fetchItems(\spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)->user_id, $root_id)->shouldHaveCount(0);
    }
    function it_edits_links()
    {
        $faker = \Faker\Factory::create();
        $faker->seed(time());
        foreach(self::$links as $link)
        {
            $root_id = $link->parent_id;
            $old_name = $this->o->fetch($link->link_id)->link_title;
            $old_uri = $this->o->fetch($link->link_id)->link_body;
            $name = $old_name."++";
            $uri = $old_uri."++";
            $this->edit($link->link_id, \spec\core\db\models\userSpec::getUser()->user_id, $name, $uri)->shouldReturnAnInstanceOf('\core\db\models\link');
            $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringEdit("-1".($link->link_id), \spec\core\db\models\userSpec::getUser()->user_id, $name, $uri);
            if($old_name === $this->o->fetch($link->link_id)->link_title)
                throw new \PhpSpec\Exception\Example\FailureException("expected that `link_title` change, but didn't!");
            if($this->o->fetch($link->link_id)->link_title !== $name)
                throw new \PhpSpec\Exception\Example\FailureException("expected that `link_title` to be `$name`, but got `{$this->o->fetch($link->link_id)->link_title}`!");
            if($old_uri === $this->o->fetch($link->link_id)->link_body)
                throw new \PhpSpec\Exception\Example\FailureException("expected that `link_body` change, but didn't!");
            if($this->o->fetch($link->link_id)->link_body !== $uri)
                throw new \PhpSpec\Exception\Example\FailureException("expected that `link_body` to be `$uri`, but got `{$this->o->fetch($link->link_id)->link_body}`!");
            # no public link yet
            $this->fetchItems(\spec\core\db\models\userSpec::getUser()->user_id, $root_id, \core\db\models\link::FLAG_SET)->shouldHaveCount(0);
            # no trashed link yet
            $this->fetchItems(\spec\core\db\models\userSpec::getUser()->user_id, $root_id, \core\db\models\link::WHATEVER, \core\db\models\link::FLAG_SET)->shouldHaveCount(0);
            # no archive link yet
            $this->fetchItems(\spec\core\db\models\userSpec::getUser()->user_id, $root_id, \core\db\models\link::WHATEVER, \core\db\models\link::WHATEVER, \core\db\models\link::FLAG_SET)->shouldHaveCount(0);
        }
        # for other user no link has been created yet
        $this->fetchItems(\spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id, $root_id)->shouldHaveCount(0);
        $this->fetchItems(\spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)->user_id, $root_id)->shouldHaveCount(0);
    }
    function it_deletes_links()
    {
        foreach(self::$links as $link)
        {
            # first logically delete links
            $this->delete($link->link_id, \spec\core\db\models\userSpec::getUser()->user_id, \core\db\models\link::DELETE_PUT_TARSH)->shouldReturnAnInstanceOf('\core\db\models\link');
            # check trashed link
            $this->fetch($link->link_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_trash = 1")))->shouldReturnAnInstanceOf('\core\db\models\link');
            # check exception accurance of deleted link
            $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($link->link_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_trash = 0")));
        }
        $this->fetchTrashes(\spec\core\db\models\userSpec::getUser()->user_id)->shouldHaveCount(count(self::$links));
        /**
         * Un-trash the trashes
         */
        foreach(self::$links as $link)
        {
            # first logically delete links
            $this->delete($link->link_id, \spec\core\db\models\userSpec::getUser()->user_id, \core\db\models\link::DELETE_RESTORE)->shouldReturnAnInstanceOf('\core\db\models\link');
            # check trashed link
            $this->fetch($link->link_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_trash = 0")))->shouldReturnAnInstanceOf('\core\db\models\link');
            # check exception accurance of deleted link
            $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($link->link_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_trash = 1")));
        }
        $this->fetchTrashes(\spec\core\db\models\userSpec::getUser()->user_id)->shouldHaveCount(0);
    }
    function it_archives_links()
    {
        foreach(self::$links as $link)
        {
            # first logically delete links
            $this->archive($link->link_id, \spec\core\db\models\userSpec::getUser()->user_id, \core\db\models\link::FLAG_SET)->shouldReturnAnInstanceOf('\core\db\models\link');
            # check trashed link
            $this->fetch($link->link_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_archive = 1")))->shouldReturnAnInstanceOf('\core\db\models\link');
            # check exception accurance of deleted link
            $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($link->link_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_archive = 0")));
        }
        $this->fetchArchives(\spec\core\db\models\userSpec::getUser()->user_id)->shouldHaveCount(count(self::$links));
        /**
         * Un-trash the trashes
         */
        foreach(self::$links as $link)
        {
            # first logically delete links
            $this->archive($link->link_id, \spec\core\db\models\userSpec::getUser()->user_id, \core\db\models\link::FLAG_UNSET)->shouldReturnAnInstanceOf('\core\db\models\link');
            # check trashed link
            $this->fetch($link->link_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_archive = 0")))->shouldReturnAnInstanceOf('\core\db\models\link');
            # check exception accurance of deleted link
            $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($link->link_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_archive = 1")));
        }
        $this->fetchArchives(\spec\core\db\models\userSpec::getUser()->user_id)->shouldHaveCount(0);
    }
    function it_tests_sharing_and_access_ops()
    {
        foreach(self::$links as $link)
        {
            # every user has an access route to its link
            $this->fetch($link->link_id, \spec\core\db\models\userSpec::getUser()->user_id)->shouldReturnAnInstanceOf(new \core\db\models\link);
            foreach (
                array(
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE),
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)
                ) as $external_user)
            {
                # no other users can access to non-public links
                $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($link->link_id, $external_user->user_id);
            }
            # the user decides to make the link public
            $this->share($link->link_id, \spec\core\db\models\userSpec::getUser()->user_id)->shouldReturnAnInstanceOf('\core\db\models\link');
            # every user still has an access route to its link
            $this->fetch($link->link_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_public = 1")))->shouldReturnAnInstanceOf(new \core\db\models\link);
            foreach (
                array(
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE),
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)
                ) as $external_user)
            {
                # other users can access to public links
                $this->shouldNotThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($link->link_id, $external_user->user_id);
                $this->fetch($link->link_id, $external_user->user_id)->shouldReturnAnInstanceOf('\core\db\models\link');
            }
        }
        $this->fetchShared(\spec\core\db\models\userSpec::getUser()->user_id)->shouldHaveCount(count(self::$links));
        /**
         * Unshare things
         */
        foreach(self::$links as $link)
        {
            # every user has an access route to its link
            $this->fetch($link->link_id, \spec\core\db\models\userSpec::getUser()->user_id)->shouldReturnAnInstanceOf(new \core\db\models\link);
            foreach (
                array(
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE),
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)
                ) as $external_user)
            {
                # other users can access to public links
                $this->shouldNotThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($link->link_id, $external_user->user_id);
                $this->fetch($link->link_id, $external_user->user_id)->shouldReturnAnInstanceOf('\core\db\models\link');
            }
            # the user decides to make the link public
            $this->share($link->link_id, \spec\core\db\models\userSpec::getUser()->user_id, \core\db\models\link::FLAG_UNSET)->shouldReturnAnInstanceOf('\core\db\models\link');
            # every user still has an access route to its link
            $this->fetch($link->link_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_public = 0")))->shouldReturnAnInstanceOf(new \core\db\models\link);
            foreach (
                array(
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE),
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)
                ) as $external_user)
            {
                # other users cannot access to non-public links
                $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($link->link_id, $external_user->user_id);
            }
        }
        $this->fetchShared(\spec\core\db\models\userSpec::getUser()->user_id)->shouldHaveCount(0);
    }
    function it_moves_links()
    {
        $folder = new \core\db\models\folder;
        $folder = $folder->newItem("MOVE_FOLDER", \core\db\models\user::ROOT_USER_ID, \spec\core\db\models\userSpec::getUser()->user_id);
        for($index = 0; $index<count(self::$links); $index+=2)
        {
            $this->fetch(self::$links[$index]->link_id, \spec\core\db\models\userSpec::getUser()->user_id)->shouldReturnAnInstanceOf(new \core\db\models\link);
            $this->move(self::$links[$index]->link_id, \spec\core\db\models\userSpec::getUser()->user_id, self::$links[$index]->parent_id, $folder->folder_id)->shouldReturnAnInstanceOf('\core\db\models\link');
            $link = $this->o->fetch(self::$links[$index]->link_id, \spec\core\db\models\userSpec::getUser()->user_id);
            if($link->parent_id == self::$links[$index]->parent_id)
                throw new \PhpSpec\Exception\Example\FailureException("expected to parent change, but didn't!");
            if($link->parent_id != $folder->folder_id)
                throw new \PhpSpec\Exception\Example\FailureException("parent didn't change as expected!");
        }
        $this->fetchItems(\spec\core\db\models\userSpec::getUser()->user_id, $folder->folder_id)->shouldHaveCount(count(self::$links)/2);
    }
}
/** linkSpec is done **/