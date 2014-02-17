<?php
namespace spec\application;
require_once "spec/bootstrap.php";
/**
 * A specification for \application\appRoutes
 * @filesource src\application\appRoutes.php
 * @author grumpy <b.g.dariush@gmail.com>
 */
class appRoutesSpec extends \PhpSpec\ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\application\appRoutes');
    }
    /**
     * test fetches for passed uries
     * @param array $uries the mixed array( URI => [ACTION] ) which will binds every action for each uries if no action name provided the uri'es will considered as action's name
     * @param string $expected_module the expected module which Fetch() should bind uries to it
     * @param type $expected_controller the expected controller which Fetch() should bind uries to it
     * @throws \PhpSpec\Exception\Example\ExampleException if any mis-match happens
     */
    function fetch_tester(array $uries, $expected_module = "defaultModule", $expected_controller = "indexController")
    {
        /**
         * Normalize the expected values
         */
        $expected_module = \strtolower(\preg_replace("#Module$#i", "", $expected_module));
        $expected_controller = \strtolower(\preg_replace("#Controller$#i", "", $expected_controller));
        # do fetch, and no exception expected
        $this->shouldNotThrow('\Exception')->duringFetch();
        # create a request instance
        $r = new \zinux\kernel\routing\request();
        foreach($uries as $uri => $action)
        {
            # if no `action` provided for `uri` consider the `action` as `uri` too.
            if(\is_numeric($uri)) $uri = $action;
            # set the uri
            $r->SetURI("/$uri");
            # process the uri
            $r->Process();
            # process the request due to {$this->Fetch()}
            $this->shouldNotThrow('\Exception')->duringProcess($r);
            # re-process the request
            $r->Process();
            /**
             * validate the processed request
             */
            if(\strtolower($r->module->full_name) !== "{$expected_module}module")
                throw new \PhpSpec\Exception\Example\MatcherException("Expected `{$expected_module}Module` during processing uri `{$r->GetURI()}` but `{$r->module->full_name}` given!");
            if(\strtolower($r->controller->full_name) !== "{$expected_controller}controller")
                throw new \PhpSpec\Exception\Example\MatcherException("Expected `{$expected_controller}Controller` during processing uri `{$r->GetURI()}` but `{$r->controller->full_name}` given!");
            if(\strtolower($r->action->full_name) !== "{$action}action")
                throw new \PhpSpec\Exception\Example\MatcherException("Expected `{$action}Action` during processing uri `{$r->GetURI()}` but `{$r->action->full_name}` given!");      
        }
    }
    /**
     * tests maps to auth modules
     */
    function it_tests_auth_module_fetches()
    {
        $this->fetch_tester(array("signup", "signin", "signout", "recovery"), "auth", "index");
    }
    /**
     * tests maps to default modules
     */
    function it_tests_default_module_fetches()
    {
        $this->fetch_tester(array("archives", "shared", "trashes"));
    }
    /**
     * tests maps to ops modules
     */
    function it_tests_ops_module_fetches()
    {
        # /ops/index/*
        $this->fetch_tester(array("new", "edit", "view", "delete", "archive", "share", "subscribe", "unsubscribe"), "ops", "index");
        # /ops/messages/*
        $this->fetch_tester(array("messages" => "index"), "ops", "messages");
        # /ops/profile/*
        $this->fetch_tester(array("profile" => "index", "/profile/avatar/crop" => "avatar_crop", "/profile/avatar/view" => "avatar_view"), "ops", "profile");
        # /ops/notifications/*
        $this->fetch_tester(array("notifications" => "index"), "ops", "notifications");
    }
}
