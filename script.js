function startvoice(e){
        var recognition = new webkitSpeechRecognition();
        recognition.lang = e.getAttribute('data-lang');
		var target;
		if(e.id == 'primary'){
			target = 'secondary';
		}
		else{
			target = 'primary';
		}
recognition.onresult = function(event) {
	  console.log(event['results'][0][0]['transcript']);
	  e.nextSibling.nextSibling.innerHTML = '"'+event['results'][0][0]['transcript']+'"';

	  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var res = this.responseText.substring(4,200);
        var index = res.indexOf('"');
        var translated = res.substring(0,index);
        document.getElementById(target).nextSibling.nextSibling.innerHTML = '"'+translated+'"';
        //responsiveVoice.speak(translated,document.getElementById(target).getAttribute("data-voice"));
		var msg = new SpeechSynthesisUtterance(translated);
		msg.lang = document.getElementById(target).getAttribute("data-lang");
		speechUtteranceChunker(msg, {
    chunkLength: 1000
}, function () {
    console.log('done');
});

    }
  };
  xhttp.open("GET", 'https://translate.googleapis.com/translate_a/single?client=gtx&sl='+e.getAttribute('data-lang')+'&tl='+document.getElementById(target).getAttribute("data-lang")+'&dt=t&q='+event['results'][0][0]['transcript'], true);
  xhttp.send();
}

recognition.onaudioend = function() {
console.log('audioend');
  e.className = 'speak-btn';
}
recognition.onaudiostart = function() {
console.log('audiostart');
  e.className = 'speak-btn beat';
}
recognition.onnomatch = function() {
  console.log('Speech not recognised');
}
recognition.start();
}




var speechUtteranceChunker = function (utt, settings, callback) {
    settings = settings || {};
    var newUtt;
    var txt = (settings && settings.offset !== undefined ? utt.text.substring(settings.offset) : utt.text);
    if (utt.voice && utt.voice.voiceURI === 'native') {
        newUtt = utt;
        newUtt.text = txt;
        newUtt.addEventListener('end', function () {
            if (speechUtteranceChunker.cancel) {
                speechUtteranceChunker.cancel = false;
            }
            if (callback !== undefined) {
                callback();
            }
        });
    }
    else {
        var chunkLength = (settings && settings.chunkLength) || 160;
        var pattRegex = new RegExp('^[\\s\\S]{' + Math.floor(chunkLength / 2) + ',' + chunkLength + '}[.!?,]{1}|^[\\s\\S]{1,' + chunkLength + '}$|^[\\s\\S]{1,' + chunkLength + '} ');
        var chunkArr = txt.match(pattRegex);

        if (chunkArr[0] === undefined || chunkArr[0].length <= 2) {
            //call once all text has been spoken...
            if (callback !== undefined) {
                callback();
            }
            return;
        }
        var chunk = chunkArr[0];
        newUtt = new SpeechSynthesisUtterance(chunk);
		newUtt.lang = utt.lang;
        var x;
        for (x in utt) {
            if (utt.hasOwnProperty(x) && x !== 'text') {
                newUtt[x] = utt[x];
            }
        }
        newUtt.addEventListener('end', function () {
            if (speechUtteranceChunker.cancel) {
                speechUtteranceChunker.cancel = false;
                return;
            }
            settings.offset = settings.offset || 0;
            settings.offset += chunk.length - 1;
            speechUtteranceChunker(utt, settings, callback);
        });
    }

    if (settings.modifier) {
        settings.modifier(newUtt);
    }
    console.log(newUtt); //IMPORTANT!! Do not remove: Logging the object out fixes some onend firing issues.
    //placing the speak invocation inside a callback fixes ordering and onend issues.
    setTimeout(function () {
        speechSynthesis.speak(newUtt);
    }, 0);
};
