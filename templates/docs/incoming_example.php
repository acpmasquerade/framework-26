

<!--incoming example -->
<div class="section">
    <h4><a name="incoming_example"></a>Incoming Example</h4>
    <div class="well">
        <pre class="prettyprint linenums">
&lt;?php

    // STEP 1
    // incoming request parameters
    $keyword = $_GET["keyword"]; // first word
    $from = $_GET["from"]; // the sms sender
    $text = $_GET["text"]; // the remaining text after the first word
    $timestamp = time();


    // STEP 2
    // build your logic on how to respond the incoming request
    switch($keyword){
        case "one":
            $reply = "You (". $from .") have been subscribed to campaign one";
        break;
        case "two":
            $reply = "You (". $from .") have been subscribed to campaign two";
        break;
        default:
            $reply = "Invalid campaign name";
        break;
    }
    

    // STEP 3
    // may be you need to save the request to your own database
    // @optional
    mysql_connect("localhost", "username", "password");
    mysql_select_db("sparrow_sms");
    mysql_query("insert into logs_incoming
                    (`from`, `keyword`, `text`, `timestamp`)
                    values ('".mysql_real_escape_string($from)."',
                            '".mysql_real_escape_string($keyword)."',
                            '".mysql_real_escape_string($text)."',
                            '".$timestamp."'
                            )");


    // STEP 4
    // send reply back to Sparrow SMS
    // This has to be STRICTLY 160 chars (max)
    die($reply);
?&gt;
        </pre>
    </div>
</div>