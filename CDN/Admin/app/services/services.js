"use strict";

angular
    .module('app.services')
    .factory("protectData", protectData)
    .factory("serverCall", serverCall)
    .factory("siteFunctions", siteFunctions)
    .factory("dashboardFunctions", dashboardData);
    dashboardData.$inject = ['appURL', "twitterHandle", "facebookHandle"];
    serverCall.$inject = ['appURL', 'localStorageService'];
         
    // Base64 encoding service used by AuthenticationService
    var Base64 = {
 
        keyStr: 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=',
 
        encode: function (input) {
            var output = "";
            var chr1, chr2, chr3 = "";
            var enc1, enc2, enc3, enc4 = "";
            var i = 0;
 
            do {
                chr1 = input.charCodeAt(i++);
                chr2 = input.charCodeAt(i++);
                chr3 = input.charCodeAt(i++);
 
                enc1 = chr1 >> 2;
                enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                enc4 = chr3 & 63;
 
                if (isNaN(chr2)) {
                    enc3 = enc4 = 64;
                } else if (isNaN(chr3)) {
                    enc4 = 64;
                }
 
                output = output +
                    this.keyStr.charAt(enc1) +
                    this.keyStr.charAt(enc2) +
                    this.keyStr.charAt(enc3) +
                    this.keyStr.charAt(enc4);
                chr1 = chr2 = chr3 = "";
                enc1 = enc2 = enc3 = enc4 = "";
            } while (i < input.length);
 
            return output;
        },
 
        decode: function (input) {
            var output = "";
            var chr1, chr2, chr3 = "";
            var enc1, enc2, enc3, enc4 = "";
            var i = 0;
 
            // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
            var base64test = /[^A-Za-z0-9\+\/\=]/g;
            if (base64test.exec(input)) {
                window.alert("There were invalid base64 characters in the input text.\n" +
                    "Valid base64 characters are A-Z, a-z, 0-9, '+', '/',and '='\n" +
                    "Expect errors in decoding.");
            }
            input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
 
            do {
                enc1 = this.keyStr.indexOf(input.charAt(i++));
                enc2 = this.keyStr.indexOf(input.charAt(i++));
                enc3 = this.keyStr.indexOf(input.charAt(i++));
                enc4 = this.keyStr.indexOf(input.charAt(i++));
 
                chr1 = (enc1 << 2) | (enc2 >> 4);
                chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                chr3 = ((enc3 & 3) << 6) | enc4;
 
                output = output + String.fromCharCode(chr1);
 
                if (enc3 != 64) {
                    output = output + String.fromCharCode(chr2);
                }
                if (enc4 != 64) {
                    output = output + String.fromCharCode(chr3);
                }
 
                chr1 = chr2 = chr3 = "";
                enc1 = enc2 = enc3 = enc4 = "";
 
            } while (i < input.length);
 
            return output;
        }
    };

    // decimal to hexadecinal
    function dec2hex (dec) 
    {
        return ('0' + dec.toString(16)).substr(-2)
    }

    // random csrf generator
    function generateId (len) 
    {
        var arr = new Uint8Array((len || 40) / 2)
        window.crypto.getRandomValues(arr)
        return Array.from(arr).map(dec2hex).join('')
    }

    function serverCall(appURL, localStorageService)
    {
        return {
            websockets: function(route, action, data, token, xsrf, callback) 
            {
                callback(response);
            }
        };
    }

    function protectData()
    {
        return {
            encryptData: function(rawData) 
            {
                return chats;
            },
            decryptData: function(encryptedData) 
            {
                return chats;
            }
        };
    }

    function siteFunctions()
    {
        return {
            chartJS: function(id)
            {
                var myChart = new Chart(document.getElementById(id), {
                type: 'bar',
                data: {
                    labels: ['jan','feb','mar','apr','may','jun','jul','aug','sep','oct'],
                    datasets: [{ 
                        data: [86,114,106,106,107,111,133,221,783,478],
                        label: "Page View",
                        backgroundColor: "#FFEB3B",
                        borderColor: "#FFEB3B",
                        fill: true
                    }, { 
                        data: [282,350,411,502,635,809,947,402,700,567],
                        label: "Google Likes",
                        backgroundColor: "#ff6b68",
                        borderColor: "#ff6b68",
                        fill: true
                    }, { 
                        data: [168,170,178,190,203,276,408,547,675,734],
                        label: "Visitors",
                        backgroundColor: "#3cba9f",
                        borderColor: "#3cba9f",
                        fill: true
                    }, { 
                        data: [740,520,810,616,624,38,74,167,508,784],
                        label: "Facebook Likes",
                        backgroundColor: "#673AB7",
                        borderColor: "#673AB7",
                        fill: true
                    }, { 
                        data: [6,3,2,2,7,26,82,172,312,433],
                        label: "Twitter Likes",
                        backgroundColor: "#2196F3",
                        borderColor: "#2196F3",
                        fill: true
                    }
                    ]
                },
                options: {
                    legendCallback: function (chart) {
                        var text = [];
                        text.push('<ul class="' + chart.id + '-legend" style="list-style:none">');
                        for (var i = 0; i < chart.legend.legendItems.length; i++) {
                            text.push('<li><div style="margin:5px !important;width:10px !important;height:10px !important;display:inline-block;background:' + chart.legend.legendItems[i].strokeStyle + '" />&nbsp;');
                            if (chart.legend.legendItems[i].text) {
                                text.push(chart.legend.legendItems[i].text);
                            }
                            text.push('</li>');
                        }
                        text.push('</ul>');
                
                        return text.join('');
                    },
                    legend: {display: false},
                    title: {
                        display: true,
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    responsive: true,
                    scales: {
                        xAxes: [{
                            stacked: true,
                        }],
                        yAxes: [{
                            stacked: true
                        }]
                    }
                }
                });
                $("#myChartLegend").html(myChart.generateLegend()); 
            },
            dataTable: function(tableName)
            {
                $(tableName).DataTable({
                    responsive: true
                });
            },
            wysihtml5: function()
            {
                $('textarea').wysihtml5();
            },
            readURL: function(input, displayId) 
            {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    
                    reader.onload = function(e) 
                    {
                        $('#'+ displayId).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            },
            uploadFiles: function($rootScope)
            {
                $('button.btn-upload').removeClass('btn-upload');
                $('button.btn-upload').css('margin: 20px !important');
            }
        }
    }

    function dashboardData(appURL, twitterHandle, facebookHandle)
    {
        return {
            socialLikes: function(callback)
            {
                var $likes = {};
                 $.getJSON('http://twitter.com/users/'+twitterHandle+'.json?callback=?',function(data) {
                    if ('followers_count' in data) {
                        $likes['twitter'] = format_number(data.followers_count,0,'',',');
                    }
                });
                
                $.getJSON("https://graph.facebook.com/"+facebookHandle+"?callback=?", function(data) { 
                    if ('likes' in data) {
                        $likes['facebook'] = format_number(data.likes,0,'',',');
                    }
                });
                callback($likes);
            },
            socialShares: function()
            {

            },
            serverLogs: function(callback)
            {   
                $.get(appURL+"../engine/logs/services.json", function(logs){
                    callback($.extend({}, logs));
                });
            },
            errorLogs: function(callback)
            {   
                $.get(appURL+"../engine/logs/error.log", function(logs){
                    callback($.extend({}, logs.split('\n\n').reverse()));
                });
            },
            firewallLogs: function(callback)
            {   
                $.get(appURL+"../engine/logs/firewall.log", function(logs){
                    callback($.extend({}, logs.split('\n\n')));
                });
            }
        }
    }