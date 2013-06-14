 <div>
                <hr />
                <h3><a name="outgoing_api"></a><span>Outgoing (Push) API Parameters and Endpoint</span></h3>
                <hr />
                <div>
                    <ol  start="1">
                        <li>
                            <h5><a name="h.3lpdm2hbe4bg"></a><span>API Endpoint</span></h5>
                            <ol start="1">
                                <li><span>call_in</span><span> http://api.sparrowsms.com/call_in.php</span>
                                    <ol  start="1">
                                        <li><span>Use this endpoint when required to track response to each API calls.</span></li>
                                    </ol>
                                </li>
                            </ol>

                            <!--
                            <ol  start="2">
                                <li><span>call_enqueue</span><span> http://api.sparrowsms.com/call_in.php</span>
                                    <ol  start="1">
                                        <li><span>Use this endpoint to queue any request which will be processed automatically in an interval of every minute.</span></li>
                                    </ol>
                                </li>
                            </ol>
                            -->
                        </li>
                    </ol>
                    <ol  start="2">
                        <li>
                            <h5><a name="h.8jfyzzhkvnyr"></a><span>Parameters</span></h5>
                            <ol  start="3">
                                <li><span>username</span>

                                    <ol  start="1">
                                        <li><span>username provided during the API signup</span></li>
                                    </ol>

                                </li>
                            </ol>

                            <ol start="4">
                                <li><span>password</span><ol start="1">
                                        <li><span>password provided during the API signup</span></li>
                                    </ol></li>
                            </ol>

                            <ol start="5">
                                <li><span>client_id</span>

                                    <ol start="1">
                                        <li><span>client_id provided during the API signup</span></li>
                                    </ol>

                                </li>
                            </ol>

                            <ol start="6">
                                <li><span>from (Don't USE if identity is supplied to your account)</span>

                                    <ol start="1">
                                        <li><span>if multiple senders are allowed, from parameter needs to be supplied at the time of each API request</span></li>
                                    </ol>

                                </li>
                            </ol>

                            <ol start="7">
                                <li><span>identity</span>

                                    <ol start="1">
                                        <li><span>Sending Identity (Don't use, if from is defined for your account) </span></li>
                                    </ol>

                                </li>
                            </ol>

                            <ol start="8">
                                <li><span>to</span>

                                    <ol start="1">
                                        <li><span>the number to send sms to</span></li>
                                        <li><span>must be a urlencoded comma separated list of phone numbers each of the numbers being either of the following formats</span>

                                            <ol>
                                                <li><span>10 digits phone number </span>
                                                    <br />
                                                    <span>eg. 9841000000</span>
                                                </li>

                                                <li><span>10 digits phone number and a &lsquo;+&rsquo; at the beginning </span>

                                                    <br />
                                                    <span>eg. +9841000000</span>
                                                </li>

                                                <li><span>10 digits phone number and country code &lsquo;977&rsquo; or &lsquo;+977&rsquo; at the beginning </span>
                                                    <br />
                                                    <span>eg. 9779841000000, +9779841000000</span>
                                                </li>

                                            </ol>
                                        </li>
                                        <li><span>characters &lsquo;space&rsquo; and &lsquo;hyphens&rsquo; will be removed automatically and the 10 digits mentioned in the point above counts after removing these characters. It means, following numbers are valid too</span>


                                            <ol  start="1">
                                                <li><span>9841-00-00-00</span></li>
                                                <li><span>9841 00 00 00</span></li>
                                                <li><span>+9841 00 00 00</span></li>
                                                <li><span>+9841-00-00-00</span></li>
                                                <li><span>+9779841 00 00 00</span></li>
                                                <li><span>+9779841-00-00-00</span></li>
                                                <li><span>9779841 00 00 00</span></li>
                                                <li><span>9779841-00-00-00</span></li>
                                            </ol>

                                        </li>
                                        <li><span>anything other than these formats in regarded as an Invalid Number.</span></li>
                                    </ol>

                                </li>

                                <li><span>text</span>

                                    <ol  start="1">
                                        <li><span>The text to be sent</span></li>
                                        <li><span>Must be urlencoded</span></li>
                                    </ol>



                                </li>

                                <!--
                                <li><span>call_url</span>
                                    <ol  start="1">
                                        <li><span>available for call_enqueue API only.</span></li>
                                        <li><span>form an url for the normal call_in API, and pass the urlencoded form of the URL to this parameter of the the call_enqueue API</span></li>
                                    </ol>
                                </li>
                                -->

                            </ol>
                        </li>
                    </ol>

                    <ol  start="3">
                        <li><h5><span>Access Limitations</span></h5>

                            <ol  start="11">
                                <li><span>The API usage access is restricted under the following domains</span>

                                    <ol  start="1">
                                        <li><span>Username / password</span>

                                            <p>
                                                <span>username and password must match with the details provided during signup and aren&rsquo;t changeable</span>
                                            </p>

                                        </li>

                                        <li><span>client_id</span>

                                            <p>
                                                <span>provided during the API signup</span>
                                            </p>

                                        </li>

                                        <li><span>IP Address</span>

                                            <p>
                                                <span>IP address limitation is on highest level and the API access is limited to the whitelisted IP addresses only.</span>
                                            </p>
                                        </li>

                                        <li><span>Credits</span>

                                            <p>
                                                <span>SMS is delivered only if there is credit available to send the SMS. Only prepaid credits are available and need to contact Sparrow SMS to add credits to the corresponding API account. A single credits means a single SMS allowed to be sent to any of the available operators</span>
                                            </p>
                                        </li>

                                    </ol>

                                </li>
                            </ol>

                        </li>

                    </ol>
                </div>
            </div>

