if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        function (pos) {
            $.ajax({
                type: "GET",
                url: "http://api.openweathermap.org/data/2.5/weather?units=metric&lat=" + pos.coords.latitude + "&lon=" + pos.coords.longitude,
                success: function(msg){
                    console.log(msg.weather[0].main);
                    $('#weather_position').text('今日の天気(' + msg.name + ')');
                    $('#weather_string').text('今日の天気は' + msg.weather[0].main + 'です。');
                    $('#weather_icon').attr({
                        src : "http://openweathermap.org/img/w/" + msg.weather[0].icon + '.png'
                    });

                }
            });
        },
        // （2）位置情報の取得に失敗した場合
        function (error) {
            var message = "";

            switch (error.code) {

                // 位置情報が取得できない場合
                case error.POSITION_UNAVAILABLE:
                    message = "位置情報の取得ができませんでした。";
                    break;

                // Geolocationの使用が許可されない場合
                case error.PERMISSION_DENIED:
                    message = "位置情報取得の使用許可がされませんでした。";
                    break;

                // タイムアウトした場合
                case error.PERMISSION_DENIED_TIMEOUT:
                    message = "位置情報取得中にタイムアウトしました。";
                    break;
            }
            window.alert(message);
        }
    );
}