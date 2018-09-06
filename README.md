# StoryChief.io API Hook Stub Handler

StoryChief.io Api Hook Handler to allow stubbing the API for control over the canonical url.

I use this as my main website api handler for StoryChief.io

To use it I have to setup two custom fields for the blog:

- id
- url

And when I create a blog post I assign an id and I add the canonical url that I want to use.

When I publish on StoryChief.io I make the api the `primary` and in this way I have control over the canonical url.

This helps me publish to:

- static sites
- blogger

Even though StoryChief.io does not support these as the primary.

## Config

To use this.

- rename `hookconfig_default.php` to `hookconfig.php` and add in the values from your StoryChief.io setup.
- Copy the scripts somewhere in your server.

If you want to 'log' the messages then either write your logging code in `emptyhooklogger.php` or create a new hooklogger class and import this into the `hookhandler.php`

My hooklogger sends me an email with the contents of the message that StoryChief sends me, but I could have written it to a log file etc.

## Coding

I've tried to make the code simple enough that you don't have to change anything, but if you do then you can add whatever you need in your hooklogger.

When I was writing my hooklogger I added the ability to test in production by amending the `getTestModeFromConfig` function.

If this returns true then you enter test mode and the payload is not validated, so I don't recommend having this as default in production.

I used it as follows:

~~~~~~~~
$LIVE_TEST_SECRETCODE='asecretcode';


function getTestModeFromConfig(){

    global $body, $CUSTOMKEY_ID, $CUSTOMKEY_URL, $LIVE_TEST_SECRETCODE;
    $testmode = false;

    // recommend you remove this code prior to deploy, it is really here to support testing
    if(isset($_GET['test']) && strlen($LIVE_TEST_SECRETCODE)>0 && strcmp($_GET['test'], $LIVE_TEST_SECRETCODE)===0) {
        $testmode=true;
        //$data = array('id' => "testcustomkey", 'permalink' => "http://testurl.com");
        $body = '{"data":{"custom_fields":[{"key":"'.$CUSTOMKEY_ID.'","value":"testcustomkey"},{"key":"'.$CUSTOMKEY_URL.'","value":"http://testurl.com"}]}}';
    }

    return $testmode;
}
~~~~~~~~

The above basically says:

- if I was called with a GET
- and the url has a parameter `test`
- and I have configured the `$LIVE_TEST_SECRETCODE` to have a string
- and the supplied test paramater matches the config
- then put us into test mode and use the hard coded data as the message

then I can see if the logger is working by entering the URL from a browser e.g.

`http://mydeployeddomain.com/storychief/hookhandler.php?test=asecretcode`

Note:

- I don't own or use `mydeployeddomain.com`
- when I do have this configured on my site, I don't have a secret code with the value `asecretcode`

## Related Blog Posts

- https://www.talotics.com/post/tooling/storychief-blogger/
- https://www.talotics.com/post/tooling/storychief/


## Written by

Alan Richardson

* http://talotics.com
* http://www.compendiumdev.co.uk
* http://www.eviltester.com


