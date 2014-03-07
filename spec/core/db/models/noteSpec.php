<?php
namespace spec\core\db\models;
require_once "spec/bootstrap.php";
/**
 * A specification for \core\db\models\note
 * @filesource src\core\db\models\note.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class noteSpec extends \PhpSpec\ObjectBehavior
{
    /**
     * @var \core\db\models\note
     */
    public $o;
    public static $notes = array();
    
    public function __construct()
    {
        $this->o = new \core\db\models\note;
    }
    /**
     * tests the initializations
     */
    function it_is_initializable()
    {
        $this->shouldHaveType('\core\db\models\note');
        # at initial only the root note exists
        $this->count()->shouldBeLike(0);
    }
    /**
     * tests note add
     */
    function it_adds_note_to_root()
    {
        $faker = \Faker\Factory::create();
        $faker->seed(time());
        # adding some subnotes
        for($root_id = 0; $root_id <= 2; $root_id++)
        {
            for($note_id = 1; $note_id <= 2; $note_id++)
            {
                # gen. a name for note
                $name = $faker->sentence(3);
                # gen. a paragraph for note
                $body = implode(PHP_EOL, $faker->paragraphs);
                # add a note
                $this->newItem($name, $body, $root_id, \spec\core\db\models\userSpec::getUser()->user_id)->shouldReturnAnInstanceOf(new \core\db\models\note);
                # record the added item into cache
                self::$notes[] = $this->o->last();
                # add the same note again
                $this->shouldNotThrow()->duringNewItem($name, $body, $root_id, \spec\core\db\models\userSpec::getUser()->user_id);
                # record the added item into cache
                self::$notes[] = $this->o->last();
            }
        }
        # the count of notes should be 3*2*2
        $this->count()->shouldBeLike(count(self::$notes));
    }
    /**
     * tests fetches
     */
    function it_fetches_notes()
    {
        $faker = \Faker\Factory::create();
        $faker->seed(time());
        $count = 0;
        foreach(self::$notes as $note)
        {
            $root_id = $note->parent_id;
            $count++;
            # fetch the item# note_id see it fetches right
            $this->fetch($note->note_id, \spec\core\db\models\userSpec::getUser()->user_id)->shouldReturnAnInstanceOf(new \core\db\models\note);
            # should throw for some invalid/non-existance id# fetch
            $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($faker->randomNumber(-10,-1));
            # no public note yet
            $this->fetchItems(\spec\core\db\models\userSpec::getUser()->user_id, $root_id, \core\db\models\note::FLAG_SET)->shouldHaveCount(0);
            # no trashed note yet
            $this->fetchItems(\spec\core\db\models\userSpec::getUser()->user_id, $root_id, \core\db\models\note::WHATEVER, \core\db\models\note::FLAG_SET)->shouldHaveCount(0);
            # no archive note yet
            $this->fetchItems(\spec\core\db\models\userSpec::getUser()->user_id, $root_id, \core\db\models\note::WHATEVER, \core\db\models\note::WHATEVER, \core\db\models\note::FLAG_SET)->shouldHaveCount(0);
            
        }
        # the count of notes should be 3*2*2
        $this->count()->shouldBeLike($count);
        # for other user no note has been created yet
        $this->fetchItems(\spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id, $root_id)->shouldHaveCount(0);
        $this->fetchItems(\spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)->user_id, $root_id)->shouldHaveCount(0);
    }
    function it_edits_notes()
    {
        $faker = \Faker\Factory::create();
        $faker->seed(time());
        foreach(self::$notes as $note)
        {
            $root_id = $note->parent_id;
            $old_name = $this->o->fetch($note->note_id)->note_title;
            $old_body = $this->o->fetch($note->note_id)->note_body;
            $name = $old_name."++";
            $body = $old_body."++";
            $this->edit($note->note_id, \spec\core\db\models\userSpec::getUser()->user_id, $name, $body)->shouldReturnAnInstanceOf('\core\db\models\note');
            $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringEdit("-1".($note->note_id), \spec\core\db\models\userSpec::getUser()->user_id, $name, $body);
            if($old_name === $this->o->fetch($note->note_id)->note_title)
                throw new \PhpSpec\Exception\Example\FailureException("expected that `note_title` change, but didn't!");
            if($this->o->fetch($note->note_id)->note_title !== $name)
                throw new \PhpSpec\Exception\Example\FailureException("expected that `note_title` to be `$name`, but got `{$this->o->fetch($note->note_id)->note_title}`!");
            if($old_body === $this->o->fetch($note->note_id)->note_body)
                throw new \PhpSpec\Exception\Example\FailureException("expected that `note_body` change, but didn't!");
            if($this->o->fetch($note->note_id)->note_body !== $body)
                throw new \PhpSpec\Exception\Example\FailureException("expected that `note_body` to be `$body`, but got `{$this->o->fetch($note->note_id)->note_body}`!");
            # no public note yet
            $this->fetchItems(\spec\core\db\models\userSpec::getUser()->user_id, $root_id, \core\db\models\note::FLAG_SET)->shouldHaveCount(0);
            # no trashed note yet
            $this->fetchItems(\spec\core\db\models\userSpec::getUser()->user_id, $root_id, \core\db\models\note::WHATEVER, \core\db\models\note::FLAG_SET)->shouldHaveCount(0);
            # no archive note yet
            $this->fetchItems(\spec\core\db\models\userSpec::getUser()->user_id, $root_id, \core\db\models\note::WHATEVER, \core\db\models\note::WHATEVER, \core\db\models\note::FLAG_SET)->shouldHaveCount(0);
        }
        # for other user no note has been created yet
        $this->fetchItems(\spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE)->user_id, $root_id)->shouldHaveCount(0);
        $this->fetchItems(\spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)->user_id, $root_id)->shouldHaveCount(0);
    }
    function it_deletes_notes()
    {
        foreach(self::$notes as $note)
        {
            # first logically delete notes
            $this->delete($note->note_id, \spec\core\db\models\userSpec::getUser()->user_id, \core\db\models\note::DELETE_PUT_TARSH)->shouldReturnAnInstanceOf('\core\db\models\note');
            # check trashed note
            $this->fetch($note->note_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_trash = 1")))->shouldReturnAnInstanceOf('\core\db\models\note');
            # check exception accurance of deleted note
            $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($note->note_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_trash = 0")));
        }
        $this->fetchTrashes(\spec\core\db\models\userSpec::getUser()->user_id)->shouldHaveCount(count(self::$notes));
        /**
         * Un-trash the trashes
         */
        foreach(self::$notes as $note)
        {
            # first logically delete notes
            $this->delete($note->note_id, \spec\core\db\models\userSpec::getUser()->user_id, \core\db\models\note::DELETE_RESTORE)->shouldReturnAnInstanceOf('\core\db\models\note');
            # check trashed note
            $this->fetch($note->note_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_trash = 0")))->shouldReturnAnInstanceOf('\core\db\models\note');
            # check exception accurance of deleted note
            $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($note->note_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_trash = 1")));
        }
        $this->fetchTrashes(\spec\core\db\models\userSpec::getUser()->user_id)->shouldHaveCount(0);
    }
    function it_archives_notes()
    {
        foreach(self::$notes as $note)
        {
            # first logically delete notes
            $this->archive($note->note_id, \spec\core\db\models\userSpec::getUser()->user_id, \core\db\models\note::FLAG_SET)->shouldReturnAnInstanceOf('\core\db\models\note');
            # check trashed note
            $this->fetch($note->note_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_archive = 1")))->shouldReturnAnInstanceOf('\core\db\models\note');
            # check exception accurance of deleted note
            $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($note->note_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_archive = 0")));
        }
        $this->fetchArchives(\spec\core\db\models\userSpec::getUser()->user_id)->shouldHaveCount(count(self::$notes));
        /**
         * Un-trash the trashes
         */
        foreach(self::$notes as $note)
        {
            # first logically delete notes
            $this->archive($note->note_id, \spec\core\db\models\userSpec::getUser()->user_id, \core\db\models\note::FLAG_UNSET)->shouldReturnAnInstanceOf('\core\db\models\note');
            # check trashed note
            $this->fetch($note->note_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_archive = 0")))->shouldReturnAnInstanceOf('\core\db\models\note');
            # check exception accurance of deleted note
            $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($note->note_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_archive = 1")));
        }
        $this->fetchArchives(\spec\core\db\models\userSpec::getUser()->user_id)->shouldHaveCount(0);
    }
    function it_tests_sharing_and_access_ops()
    {
        foreach(self::$notes as $note)
        {
            # every user has an access route to its note
            $this->fetch($note->note_id, \spec\core\db\models\userSpec::getUser()->user_id)->shouldReturnAnInstanceOf(new \core\db\models\note);
            foreach (
                array(
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE),
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)
                ) as $external_user)
            {
                # no other users can access to non-public notes
                $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($note->note_id, $external_user->user_id);
            }
            # the user decides to make the note public
            $this->share($note->note_id, \spec\core\db\models\userSpec::getUser()->user_id)->shouldReturnAnInstanceOf('\core\db\models\note');
            # every user still has an access route to its note
            $this->fetch($note->note_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_public = 1")))->shouldReturnAnInstanceOf(new \core\db\models\note);
            foreach (
                array(
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE),
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)
                ) as $external_user)
            {
                # other users can access to public notes
                $this->shouldNotThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($note->note_id, $external_user->user_id);
                $this->fetch($note->note_id, $external_user->user_id)->shouldReturnAnInstanceOf('\core\db\models\note');
            }
        }
        $this->fetchShared(\spec\core\db\models\userSpec::getUser()->user_id)->shouldHaveCount(count(self::$notes));
        /**
         * Unshare things
         */
        foreach(self::$notes as $note)
        {
            # every user has an access route to its note
            $this->fetch($note->note_id, \spec\core\db\models\userSpec::getUser()->user_id)->shouldReturnAnInstanceOf(new \core\db\models\note);
            foreach (
                array(
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE),
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)
                ) as $external_user)
            {
                # other users can access to public notes
                $this->shouldNotThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($note->note_id, $external_user->user_id);
                $this->fetch($note->note_id, $external_user->user_id)->shouldReturnAnInstanceOf('\core\db\models\note');
            }
            # the user decides to make the note public
            $this->share($note->note_id, \spec\core\db\models\userSpec::getUser()->user_id, \core\db\models\note::FLAG_UNSET)->shouldReturnAnInstanceOf('\core\db\models\note');
            # every user still has an access route to its note
            $this->fetch($note->note_id, \spec\core\db\models\userSpec::getUser()->user_id, array("conditions"=>array("is_public = 0")))->shouldReturnAnInstanceOf(new \core\db\models\note);
            foreach (
                array(
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_ONE),
                \spec\core\db\models\userSpec::getUser(\spec\core\db\models\userSpec::USER_TWO)
                ) as $external_user)
            {
                # other users cannot access to non-public notes
                $this->shouldThrow('\core\db\exceptions\dbNotFoundException')->duringFetch($note->note_id, $external_user->user_id);
            }
        }
        $this->fetchShared(\spec\core\db\models\userSpec::getUser()->user_id)->shouldHaveCount(0);
    }
    function it_moves_notes()
    {
        $folder = new \core\db\models\folder;
        $folder = $folder->newItem("MOVE_FOLDER", \core\db\models\user::ROOT_USER_ID, \spec\core\db\models\userSpec::getUser()->user_id);
        for($index = 0; $index<count(self::$notes); $index+=2)
        {
            $this->fetch(self::$notes[$index]->note_id, \spec\core\db\models\userSpec::getUser()->user_id)->shouldReturnAnInstanceOf(new \core\db\models\note);
            $this->move(self::$notes[$index]->note_id, \spec\core\db\models\userSpec::getUser()->user_id, self::$notes[$index]->parent_id, $folder->folder_id)->shouldReturnAnInstanceOf('\core\db\models\note');
            $note = $this->o->fetch(self::$notes[$index]->note_id, \spec\core\db\models\userSpec::getUser()->user_id);
            if($note->parent_id == self::$notes[$index]->parent_id)
                throw new \PhpSpec\Exception\Example\FailureException("expected to parent change, but didn't!");
            if($note->parent_id != $folder->folder_id)
                throw new \PhpSpec\Exception\Example\FailureException("parent didn't change as expected!");
        }
        $this->fetchItems(\spec\core\db\models\userSpec::getUser()->user_id, $folder->folder_id)->shouldHaveCount(count(self::$notes)/2);
    }
}
/** noteSpec is done **/
