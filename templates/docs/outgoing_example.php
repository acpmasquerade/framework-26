 <!-- outgoing example-->
            <div>
                <h4><a name="outgoing_example"></a>Outgoing Example</h4>
                <div class="well">
                    <pre class="prettyprint linenums">
&lt;?php
    // STEP 1
    // prepare necessary parameters
    $client_id = 'demo';
    $username = 'demo';
    $password = 'demo';

    $from = '5001';
    $to = '9841000000';
    $text = 'I am trying the Sparrow SMS API using a Demo account';

    // STEP 2
    // build the url
    $api_url =  "http://api.sparrowsms.com/call_in.php?" . 
                http_build_query(array(
                    "client_id" => $client_id,
                    "username" => $username,
                    "password" => $password,
                    "from" => $from,
                    "to" => $to,
                    "text" => $text
                ));

    // STEP 2
    // put the request to server
    $response = file_get_contents($api_url);
    // check the response and verify
    print_r($response);

?&gt;
                    </pre>
                </div>
            </div>
