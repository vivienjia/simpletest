<?php
    // $Id$
    
    if (!defined("SIMPLE_TEST")) {
        define("SIMPLE_TEST", "./");
    }
    require_once(SIMPLE_TEST . 'runner.php');
    
    /**
     *    Sample minimal test displayer. Generates only
     *    failure messages and a pass count.
     */
    class HtmlReporter extends TestDisplay {
        
        /**
         *    Does nothing yet. The first output will
         *    be sent on the first test start.
         *    @public
         */
        function HtmlReporter() {
            $this->TestDisplay();
        }
        
        /**
         *    Paints the top of the web page setting the
         *    title to the name of the starting test.
         *    @param $test_name        Name class of test.
         *    @public
         */
        function paintHeader($test_name) {
            print "<html>\n<head>\n<title>$test_name</title>\n";
            print "<style type=\"text/css\">\n";
            print $this->_getCss() . "\n";
            print "</style>\n";
            print "</head>\n<body>\n";
            print "<h1>$test_name</h1>\n";
            flush();
        }
        
        /**
         *    Paints the CSS. Add additional styles here.
         *    @return             CSS code as text.
         *    @protected
         */
        function _getCss() {
            return ".fail { color: red; } pre { background-color: lightgray; }";
        }
        
        /**
         *    Paints the end of the test with a summary of
         *    the passes and failures.
         *    @param $test_name        Name class of test.
         *    @public
         */
        function paintFooter($test_name) {
            $colour = ($this->getFailCount() + $this->getExceptionCount() > 0 ? "red" : "green");
            print "<div style=\"";
            print "padding: 8px; margin-top: 1em; background-color: $colour; color: white;";
            print "\">";
            print $this->getTestCaseProgress() . "/" . $this->getTestCaseCount();
            print " test cases complete:\n";
            print "<strong>" . $this->getPassCount() . "</strong> passes, ";
            print "<strong>" . $this->getFailCount() . "</strong> fails and ";
            print "<strong>" . $this->getExceptionCount() . "</strong> exceptions.";
            print "</div>\n";
            print "</body>\n</html>\n";
        }
        
        /**
         *    Paints the test failure with a breadcrumbs
         *    trail of the nesting test suites below the
         *    top level test.
         *    @param $message        Failure message displayed in
         *                           the context of the other tests.
         *    @public
         */
        function paintFail($message) {
            parent::paintFail($message);
            print "<span class=\"fail\">Fail</span>: ";
            $breadcrumb = $this->getTestList();
            array_shift($breadcrumb);
            print implode("-&gt;", $breadcrumb);
            print "-&gt;" . htmlentities($message) . "<br />\n";
        }
        
        /**
         *    Paints a PHP error or exception.
         *    @param $message        Message is ignored.
         *    @public
         *    @abstract
         */
        function paintException($message) {
            parent::paintException($message);
            print "<span class=\"fail\">Exception</span>: ";
            $breadcrumb = $this->getTestList();
            array_shift($breadcrumb);
            print implode("-&gt;", $breadcrumb);
            print "-&gt;<strong>" . htmlentities($message) . "</strong><br />\n";
        }
        
        /**
         *    Paints formatted text such as dumped variables.
         *    @param $message        Text to show.
         *    @public
         */
        function paintFormattedMessage($message) {
            print "<pre>$message</pre>";
        }
    }
    
    /**
     *    @deprecated
     */
    class TestHtmlDisplay extends HtmlReporter {
        function TestHtmlDisplay() {
            $this->HtmlReporter();
        }
    }
?>