#!/usr/bin/env php
<?php
    $script_name = array_shift($argv);
    $includes = array(
            "./src/*",
            "./src/application/*",
            "./src/core/*",
            "./src/modules/*"
    );
    /*
     * './src/zinux/*' -not -path '' -not -path '' -not -path '' -not -path '' -not -path ''
     */
    $excludes = array(
			"./src/zinux/*",
            "./src/vendor/*",
            "*$script_name",
            "./src/public_html/*",
            "./src/core/ui/markdown/*",
            "./src/core/vendors/socket*",
            "./src/core/db/activeRecord/lib/*",
            "*/view/profile/profile-steps/*"
    );
    $query = "find ";
    foreach($includes as $include)
    {
        $query .= " $include";
    }
    $query .= " -type f -name '*.php'";
    foreach($excludes as $exclude)
    {
        $query .= " -not -path '$exclude'";
    }
    require 'src/zinux/kernel/utilities/string.php';
    $matches = \array_filter(\explode(PHP_EOL, \zinux\kernel\utilities\string::inverse_preg_quote(\preg_replace(array("#(\./src/)(.*)(.php)".PHP_EOL."#i", "#(".PHP_EOL."\\\\)(.*)#i", "#(.*)\\\\\n".PHP_EOL."#i"), array(PHP_EOL."bin/phpspec desc -n \"$2\"".PHP_EOL, "$2", "$1".PHP_EOL),  \preg_quote(\shell_exec($query),"#")), "#")), "strlen");
    \array_shift($matches);
    echo \preg_replace("#\\\\\"(".PHP_EOL."|$)#i", "\"".PHP_EOL, \implode(PHP_EOL, $matches));
