<?php
    $argc = 0;
    if(!isset($argv))
    {
        $argv = array("terminal");
        $argc = count($argv);
    }
    # validate the server PHP version with development PHP version
    if(version_compare(\PHP_VERSION, "5.5.8", "<"))
    {
        echo ("<center>The <b>minimal</b> PHP version <b>required is 5.5.8</b>!<br />");
        echo ("Your PHP version is: <b>".\PHP_VERSION);
        die ("</b>.<br />Upgrade your server php.</center>");
    }
    # this is a initiation, we do not need any outputs! 
    ob_start();
try
{
    # we will work with UTC standard timezone 
    date_default_timezone_set("UTC");
    # opening a session socket
    session_start();
    # if we access by shell 
    # set HTTP_HOST to the an dummy name
    @$_SERVER['HTTP_HOST'] || $_SERVER['HTTP_HOST'] = "spec-terminal.test";
    # set a dummy REQUEST URI to ROOT
    @$_SERVER['REQUEST_URI'] || $_SERVER['REQUEST_URI'] = "/";
    # define the RUNNING_ENV with TEST mode
    defined('RUNNING_ENV') || define('RUNNING_ENV', 'TEST');
    # locate the PUBLIC_HTML to the REAL public_html 
    defined('PUBLIC_HTML') || define('PUBLIC_HTML', dirname(dirname(__FILE__))."/src/public_html");
    # define a unique cache directory for testing environment
    defined("CACHE_PATH") || define("CACHE_PATH", PUBLIC_HTML."/../cache");
    # define the toratan path
    defined('TORATAN_PATH') || define('TORATAN_PATH', PUBLIC_HTML."/../");
	# define the sever name
    defined("__SERVER_NAME__") || define("__SERVER_NAME__", $_SERVER['HTTP_HOST']);
    # invoke the zinux framework
    require_once PUBLIC_HTML.'/../zinux/baseZinux.php';
    
    # suppress E_STRICT error reporting
    error_reporting(E_ALL & ~E_STRICT);

    # suppress zinux autoloading system
    \zinux\suppress_zinux_autoloader_caching();
    
    # create an application with given module directory
    $app = new \zinux\kernel\application\application(PUBLIC_HTML.'/../modules');
    # process the application instance
    $app 
            # setting cache directory
            ->SetCacheDirectory(\CACHE_PATH)
            
            # setting router's bootstrap which will route /note/:id:/edit => /note/edit/:id:
            ->SetRouterBootstrap(new \application\appRoutes)
            
            # set application's bootstrap 
            ->SetBootstrap(new \application\dbBootstrap)
            
            # init activerecord as db handler
            ->SetInitializer(new \core\db\activeRecord\ARInitializer())
            
            # load project basic config initializer
            ->SetConfigIniliazer(new \zinux\kernel\utilities\iniParser(PROJECT_ROOT."/config/default.cfg", RUNNING_ENV))
            # register php faker
            ->registerPlugin("TEST-FAKER", PROJECT_ROOT."../vendor/Faker/src")
            # init the application's optz.
            ->Startup()
            # run the application 
            ->Run()
            # shutdown the application
            ->Shutdown();
}
catch(Exception $e)
{
        \ob_get_clean();
        \ob_start();
        echo "\033[31m";
        echo "<legend>Oops!</legend>".PHP_EOL;
        echo "<p>Error happened ...</p>".PHP_EOL;
        echo "<p><b>Message: </b></p><p>".PHP_EOL;
        require_once PROJECT_ROOT.'zinux/kernel/utilities/debug.php';
        zinux\kernel\utilities\debug::_var($e->getMessage());
        echo "</p>".PHP_EOL.PHP_EOL;
        echo "<p><b>Stack Trace: </b></p><pre>".$e->getTraceAsString()."</pre>".PHP_EOL.PHP_EOL;
        #zinux\kernel\utilities\debug::_var($e->getTrace());
        echo "\033[m";
	# outbut the 
        echo \strip_tags(\zinux\kernel\utilities\string::inverse_preg_quote(\preg_replace("#(<br\s*(/)?>)#i", "", \preg_quote(\ob_get_clean(), "#")), "#"));
	exit;
}
    # the end of stream and clean the buffer
    ob_end_clean();
    \opt_out("Bootstraping....");
    # truncate db's tables
    \opt_out("Truncating Tables....", "\\truncate_db");
    # creates users
    \opt_out("Creating new test users....", array("\spec\core\db\models\userSpec", "createUsers"));
    # return from bootstrap
    return;
    function opt_out($init_txt, $opt_delegate = NULL, $args = array())
    {
        echo "\033[33m> $init_txt\033[m";
        if($opt_delegate)
            \call_user_func($opt_delegate, $args);
        echo "\033[43G\033[32m[ DONE ]\033[m", PHP_EOL;
    }
    /**
     * Truncates db
     */
    function truncate_db()
    {
        # validate the RUNNING_ENV 
        if(RUNNING_ENV !== "TEST")
            throw new \PhpSpec\Exception\Example\FailureException("`".__FUNCTION__."` at `".__FILE__."`ONLY WORKS AT TESTING ENV.");
        # delete all execution time
        $exec = new \core\db\models\execution;
        $exec->query("truncate table ".\ActiveRecord\Inflector::instance()->tableize("execution"));
        # delete all users except the ROOT user
        $user = new \core\db\models\user;
        $user->delete_all(array("conditions"=>array("user_id <> ?", \core\db\models\user::ROOT_USER_ID)));
    }