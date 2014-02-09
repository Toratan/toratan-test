#!/usr/bin/env php
<?php
    $script_name = array_shift($argv);
    $includes = array(
            "./application/*",
            "./core/*",
            "./modules/*"
    );
    /*
     * './zinux/*' -not -path '' -not -path '' -not -path '' -not -path '' -not -path ''
     */
    $excludes = array(
            "./src/*",
            "./zinux/*",
            "./vendor/*",
            "*$script_name",
            "./public_html/*",
            "./core/vendors/socket*",
            "./core/db/activeRecord/lib/*",
            "*/view/profile/profile-steps/*",
            "./core/ui/markdown/*"
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
    require 'zinux/kernel/utilities/string.php';
    $matches = \array_filter(\explode(PHP_EOL, \zinux\kernel\utilities\string::inverse_preg_quote(\preg_replace(array("#(\./)(.*)(.php)".PHP_EOL."#i", "#(".PHP_EOL."\\\\)(.*)#i", "#(.*)\\\\\n".PHP_EOL."#i"), array(PHP_EOL."bin/phpspec desc -n \"$2\"".PHP_EOL, "$2", "$1".PHP_EOL),  \preg_quote(\shell_exec($query),"#")), "#")), "strlen");
    \array_shift($matches);
    echo \preg_replace("#\\\\\"(".PHP_EOL."|$)#i", "\"".PHP_EOL, \implode(PHP_EOL, $matches));