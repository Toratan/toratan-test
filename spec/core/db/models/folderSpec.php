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
    /**
     * @var \core\db\models\folder
     */
    public $o;
    public static $folders = array();
    
    public function __construct()
    {
        $this->o = new \core\db\models\folder;
    }
    /**
     * tests the initializations
     */
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\models\folder');
        # at initial only the root folder exists
        $this->count()->shouldBeLike(1);
        # in case of deleteing the ROOT folder it should throw exception
        $this->shouldThrow("\ActiveRecord\DatabaseException")->duringDelete_all(array("conditions" => array("1")));
    }
    /**
     * tests folder add
     */
    function it_adds_folders_to_root()
    {
        $faker = \Faker\Factory::create();
        $faker->seed(time());
        # adding some subfolders
        for($root_id = 0; $root_id <= 2; $root_id++)
        {
            for($folder_id = 1; $folder_id <= 2; $folder_id++)
            {
                # gen. a name for folder
                $name = $faker->sentence(3);
                # add a folder
                $this->newItem($name, $root_id, \spec\core\db\models\userSpec::getUser()->user_id)->shouldReturnAnInstanceOf(new \core\db\models\folder);
                # record the added item into cache
                self::$folders[] = $this->o->last();
                # add the same folder again
                $this->shouldNotThrow()->duringNewItem($name, $root_id, \spec\core\db\models\userSpec::getUser()->user_id);
                # record the added item into cache
                self::$folders[] = $this->o->last();
            }
        }
        # the count of folders should be 3*2*2 + ROOT_FOLDER
        $this->count()->shouldBeLike(count(self::$folders)+1);
    }
    /**
     * tests fetches
     */
    function it_fetches_folders()
    {
        $faker = \Faker\Factory::create();
        $faker->seed(time());
        $count = 0;
        foreach(self::$folders as $folder)
        {
            $root_id = $folder->parent_id;
            $count++;
            # fetch the item# folder_id see it fetches right
            $this->fetch($folder->folder_id, \spec\core\db\models\userSpec::getUser()->user_id)->shouldReturnAnInstanceOf(new \core\db\models\folder);
            # should throw for some invalid/non-existance id# fetch
            $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($faker->randomNumber(-10,-1));
            # no public folder yet
            $this->fetchItems(\spec\core\db\models\userSpec::getUser()->user_id, $root_id, \core\db\models\folder::FLAG_SET)->shouldHaveCount(0);
            # no trashed folder yet
            $this->fetchItems(\spec\core\db\models\userSpec::getUser()->user_id, $root_id, \core\db\models\folder::WHATEVER, \core\db\models\folder::FLAG_SET)->shouldHaveCount(0);
            # no archive folder yet
            $this->fetchItems(\spec\core\db\models\userSpec::getUser()->user_id, $root_id, \core\db\models\folder::WHATEVER, \core\db\models\folder::WHATEVER, \core\db\models\folder::FLAG_SET)->shouldHaveCount(0);
            
        }
        # the count of folders should be 3*2*2 + ROOT_FOLDER
        $this->count()->shouldBeLike($count+1);
        # for other user no folder has been created yet
        $this->fetchItems(\spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id, $root_id)->shouldHaveCount(0);
        $this->fetchItems(\spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)->user_id, $root_id)->shouldHaveCount(0);
    }
    function it_edits_folders()
    {
        $faker = \Faker\Factory::create();
        $faker->seed(time());
        foreach(self::$folders as $folder)
        {
            $root_id = $folder->parent_id;
            $old_name = $this->o->fetch($folder->folder_id)->folder_title;
            $name = $old_name."++";
            $this->edit($folder->folder_id, \spec\core\db\models\userSpec::getUser()->user_id, $name)->shouldReturnAnInstanceOf('\core\db\models\folder');
            $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringEdit("-1".($folder->folder_id), \spec\core\db\models\userSpec::getUser()->user_id, $name);
            if($old_name === $this->o->fetch($folder->folder_id)->folder_title)
                throw new \PhpSpec\Exception\Example\FailureException("expected that `folder_title` change, but didn't!");
            if($this->o->fetch($folder->folder_id)->folder_title !== $name)
                throw new \PhpSpec\Exception\Example\FailureException("expected that `folder_title` to be `$name`, but got `{$this->o->fetch($folder->folder_id)->folder_title}`!");
            # no public folder yet
            $this->fetchItems(\spec\core\db\models\userSpec::getUser()->user_id, $root_id, \core\db\models\folder::FLAG_SET)->shouldHaveCount(0);
            # no trashed folder yet
            $this->fetchItems(\spec\core\db\models\userSpec::getUser()->user_id, $root_id, \core\db\models\folder::WHATEVER, \core\db\models\folder::FLAG_SET)->shouldHaveCount(0);
            # no archive folder yet
            $this->fetchItems(\spec\core\db\models\userSpec::getUser()->user_id, $root_id, \core\db\models\folder::WHATEVER, \core\db\models\folder::WHATEVER, \core\db\models\folder::FLAG_SET)->shouldHaveCount(0);
        }
        # for other user no folder has been created yet
        $this->fetchItems(\spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id, $root_id)->shouldHaveCount(0);
        $this->fetchItems(\spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)->user_id, $root_id)->shouldHaveCount(0);
    }
    function it_deletes_folders()
    {
        foreach(self::$folders as $folder)
        {
            # first logically delete folders
            $this->delete($folder->folder_id, \spec\core\db\models\userSpec::getUser()->user_id, \core\db\models\folder::DELETE_PUT_TARSH)->shouldReturnAnInstanceOf('\core\db\models\folder');
            # check trashed folder
            $this->fetch($folder->folder_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_trash = 1")))->shouldReturnAnInstanceOf('\core\db\models\folder');
            # check exception accurance of deleted folder
            $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($folder->folder_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_trash = 0")));
        }
        $this->fetchTrashes(\spec\core\db\models\userSpec::getUser()->user_id)->shouldHaveCount(count(self::$folders));
        /**
         * Un-trash the trashes
         */
        foreach(self::$folders as $folder)
        {
            # first logically delete folders
            $this->delete($folder->folder_id, \spec\core\db\models\userSpec::getUser()->user_id, \core\db\models\folder::DELETE_RESTORE)->shouldReturnAnInstanceOf('\core\db\models\folder');
            # check trashed folder
            $this->fetch($folder->folder_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_trash = 0")))->shouldReturnAnInstanceOf('\core\db\models\folder');
            # check exception accurance of deleted folder
            $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($folder->folder_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_trash = 1")));
        }
        $this->fetchTrashes(\spec\core\db\models\userSpec::getUser()->user_id)->shouldHaveCount(0);
    }
    function it_archives_folders()
    {
        foreach(self::$folders as $folder)
        {
            # first logically delete folders
            $this->archive($folder->folder_id, \spec\core\db\models\userSpec::getUser()->user_id, \core\db\models\folder::FLAG_SET)->shouldReturnAnInstanceOf('\core\db\models\folder');
            # check trashed folder
            $this->fetch($folder->folder_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_archive = 1")))->shouldReturnAnInstanceOf('\core\db\models\folder');
            # check exception accurance of deleted folder
            $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($folder->folder_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_archive = 0")));
        }
        $this->fetchArchives(\spec\core\db\models\userSpec::getUser()->user_id)->shouldHaveCount(count(self::$folders));
        /**
         * Un-trash the trashes
         */
        foreach(self::$folders as $folder)
        {
            # first logically delete folders
            $this->archive($folder->folder_id, \spec\core\db\models\userSpec::getUser()->user_id, \core\db\models\folder::FLAG_UNSET)->shouldReturnAnInstanceOf('\core\db\models\folder');
            # check trashed folder
            $this->fetch($folder->folder_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_archive = 0")))->shouldReturnAnInstanceOf('\core\db\models\folder');
            # check exception accurance of deleted folder
            $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($folder->folder_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_archive = 1")));
        }
        $this->fetchArchives(\spec\core\db\models\userSpec::getUser()->user_id)->shouldHaveCount(0);
    }
    function it_tests_sharing_and_access_ops()
    {
        foreach(self::$folders as $folder)
        {
            # we only do it for high-level folders which should affect the entire file system ops.
            if($folder->parent_id) continue;
            # every user has an access route to its folder
            $this->fetch($folder->folder_id, \spec\core\db\models\userSpec::getUser()->user_id)->shouldReturnAnInstanceOf(new \core\db\models\folder);
            foreach (
                array(
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE),
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)
                ) as $external_user)
            {
                # no other users can access to non-public folders
                $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($folder->folder_id, $external_user->user_id);
            }
            # the user decides to make the folder public
            $this->share($folder->folder_id, \spec\core\db\models\userSpec::getUser()->user_id)->shouldReturnAnInstanceOf('\core\db\models\folder');
            # every user still has an access route to its folder
            $this->fetch($folder->folder_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_public = 1")))->shouldReturnAnInstanceOf(new \core\db\models\folder);
            foreach (
                array(
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE),
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)
                ) as $external_user)
            {
                # other users can access to public folders
                $this->shouldNotThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($folder->folder_id, $external_user->user_id);
                $this->fetch($folder->folder_id, $external_user->user_id)->shouldReturnAnInstanceOf('\core\db\models\folder');
            }
        }
        echo PHP_EOL, "\033[33m$ Remmeber to set `defined('RUNNING_ENV') || define('RUNNING_ENV', 'TEST');` in `bin/init.d/deepSharer.php` and restart the the script to make this script work!\033[m", PHP_EOL;
        /** Sleep for a while to give deepSharer to do its things **/
        sleep(2);
        foreach(self::$folders as $folder)
        {
            foreach (
                array(
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ZERO),
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE),
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)
                ) as $user)
            {
                if($folder->parent_id > 0)
                {                    
                    # in testing folder containing subfolders should have exactly 4 subfolders
                    $this->fetchItems($user->user_id, $folder->parent_id)->shouldHaveCount(4);
                }
            }
        }
    }
}