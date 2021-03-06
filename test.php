<div id="transcript">&nbsp;</div>
<br>
<div id="instructions">&nbsp;</div>
<div id="controls">
<button id="start_button">Click to Start</button>
</div>


<style>
    #controls {
        text-align: center;
    }
    #start_button {
        font-size: 16pt;
    }
    #transcript {
        color: darkred;
        font-size: 16pt;
        border: 1px solid #ccc;
        padding: 5px;
        text-align: center;
    }
    #instructions {
        color: darkblue;
        font-size: 14pt;
        text-align: center;
    }
</style>
<script
  src="https://code.jquery.com/jquery-3.1.1.min.js"
  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  crossorigin="anonymous"></script>

<script type="text/javascript">
    var finalTranscript = '';
    var recognizing = false;
    $(document).ready(function() {
        // check that your browser supports the API
        if (!('webkitSpeechRecognition' in window)) {
            alert("Sorry, your Browser does not support the Speech API");
        } else {
            // Create the recognition object and define the event handlers
            var recognition = new webkitSpeechRecognition();
            recognition.continuous = true;         // keep processing input until stopped
            recognition.interimResults = true;     // show interim results
            recognition.lang = 'en-GB';           // specify the language
            recognition.onstart = function() {
                recognizing = true;
                $('#instructions').html('Speak slowly and clearly');
                $('#start_button').html('Click to Stop');
            };
            recognition.onerror = function(event) {
                console.log("There was a recognition error...");
            };
            recognition.onend = function() {
                recognizing = false;
                $('#instructions').html('&nbsp;');
            };
            recognition.onresult = function(event) {
                var interimTranscript = event.results[0][0].transcript;
                // Assemble the transcript from the array of results
                for (var i = event.resultIndex; i < event.results.length; ++i) {
                    if (event.results[i].isFinal) {
                        finalTranscript += event.results[i][0].transcript;
                    } else {
                        interimTranscript += event.results[i][0].transcript;
                    }
                }
                console.log("interim:  " + interimTranscript);
                console.log("final:    " + finalTranscript);
                // update the page
                if(finalTranscript.length > 0) {
                    $('#transcript').html(event.results[0][0].transcript);
                    recognition.stop();
                    $('#start_button').html('Click to Start Again');
                    recognizing = false;
                }
            };
            $("#start_button").click(function(e) {
                e.preventDefault();
                if (recognizing) {
                    recognition.stop();
                    $('#start_button').html('Click to Start Again');
                    recognizing = false;
                } else {
                    finalTranscript = '';
                    // Request access to the User's microphone and Start recognizing voice input
                    recognition.start();
                    $('#instructions').html('Allow the browser to use your Microphone');
                    $('#start_button').html('waiting');
                    $('#transcript').html('&nbsp;');
                }
            });
        }
    });
</script>