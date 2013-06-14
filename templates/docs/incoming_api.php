<!-- incoming api -->
            <div>
                <hr />
                <h3><a name="incoming_api"></a><span>Incoming API - (Relay approach)</span></h3>
                <hr />

                <p>
                    <span>Sparrow SMS provides incoming API in a slight different approach. Its an interrupt approach rather than the conventional polling way. When there is an incoming SMS Hit, the URL provided by content-provider / developer is invoked and any output sent via the respective URL is delivered to the SMS sender. However, it is not mandatory for the application to send anything as output. Sparrow SMS can just relay the incoming request so that the application can keep tracking in its own way.</span>
                </p>
                <p>
                    <span>Following are the arguments augmented to the URL on every Incoming SMS</span>
                </p>
                <ol  start="1">
                    <li><span>timestamp</span>

                        <ol  start="1">
                            <li><span>timestamp of the time when the incoming SMS hits the Sparrow SMS Gateway server</span></li>
                        </ol>
                    </li>

                    <li><span>keyword</span>

                        <ol>
                            <li><span>The first word of the incoming SMS text</span></li>
                        </ol>
                    </li>

                    <li><span>text</span>
                        <ol>
                            <li><span>The text after shifting the first word of the SMS content</span></li>
                        </ol>
                    </li>

                    <li><span>from</span>
                        <ol  start="1">
                            <li><span>Phone number of the SMS Sender</span></li>
                        </ol>
                    </li>

                    <li><span>to</span>

                        <ol  start="1">
                            <li><span>The shortcode to which the SMS was received</span></li>
                        </ol>
                    </li>

                </ol>
                <h5><a name="h.u3odvh2b6lif"></a><span>Possible Conditions to relay the Incoming SMS</span></h5>
                <p>
                    <span>Sparrow SMS can handle the conditions / validations for every incoming SMS so as to reduce traffic on to the application server. Following are the conditions one or more than one of which can be </span>
                </p>
                <ol  start="1">
                    <li><span>All Incoming Requests</span><span> ( External Relay )</span></li>
                    <li><span>When a valid keyword was hit </span><span>( Keyword Relay )</span></li>
                    <li><span>When some validation pattern was matched, for example. numbers or alphabets, or any alphanumeric word .etc. </span><span>( Validation Relay )</span></li>
                </ol>