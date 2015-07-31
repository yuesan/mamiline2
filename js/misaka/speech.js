var recognition;
var nowRecognition = false;

function start () {
    $('#speech_modal').modal('show');
    recognition = new webkitSpeechRecognition();
    recognition.lang = 'ja-JP'; // en-US or ja-JP
    recognition.onresult = function (e) {
        if (e.results.length > 0) {
            var value = e.results[0][0].transcript;
            document.querySelector('#misaka_speech_area').textContent = 'あなた:' + value;
        }
        $('#speech_modal').modal('hide');
    };
    recognition.start();
    nowRecognition = true;
};

function stop () {
    recognition.stop();
    nowRecognition = false;
    $('#speech_modal').modal('hide');
}

document.querySelector('#misaka_shiromu').onclick = function () {
    if (!'webkitSpeechRecognition' in window) {
        alert('Web Speech API には未対応です.');
        return;
    }

    if (nowRecognition) {
        stop();
    } else {
        start();
    }
}

$('#speech_modal').on('hidden', function () {
    stop();
    nowRecognition = false;
});

$('#speech_finish_btn').on('click', function () {
    stop();
    $('#speech_modal').modal('hide');
});

