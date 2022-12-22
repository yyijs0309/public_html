// JavaScript Document
 var smartPhones = [
            'iphone', 'ipod',
            'windows ce',
            'android', 'blackberry',
            'nokia', 'webos',
            'opera mini', 'sonyerricsson',
            'opera mobi', 'iemobile'
        ];

        for (var i in smartPhones) {
            // 스마트폰 확인
            if (navigator.userAgent.toLowerCase().match(new RegExp(smartPhones[i]))) {
                document.location = 'http://sonena.dothome.co.kr/m';
            }
        }

