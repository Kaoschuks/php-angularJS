
var templateURL = 'http://127.0.0.1:8080/simple/CDN/Admin/';
var uploadURL = 'http://127.0.0.1:8080/simple/CDN/uploads/';
var lock = true;

(function() {
    if (!sessionStorage.length) {
        // Ask other tabs for session storage
        localStorage.setItem('getSessionStorage', Date.now());
    };
    window.addEventListener('storage', function(event) {
        if (event.key == 'getSessionStorage') {
            localStorage.setItem('sessionStorage', JSON.stringify(sessionStorage));
            localStorage.removeItem('sessionStorage');

        } else if (event.key == 'sessionStorage' && !sessionStorage.length) {
            var data = JSON.parse(event.newValue),
                        value;

            for (key in data) {
                sessionStorage.setItem(key, data[key]);
            }
        }
    });
    window.onbeforeunload = function() {
        //sessionStorage.clear();
    };

})();

function cutString(text, len)
{    
    text = $($.parseHTML(text)).text();
    var i = 0;
    var wordsToCut = len;
    var wordsArray = text.split(" ");
    if(wordsArray.length>wordsToCut){
        var strShort = "";
        for(i = 0; i < wordsToCut; i++){
            strShort += wordsArray[i] + " ";
        }   
        return strShort+"...";
    }else{
        return text;
    }
 }

function collapselock()
{
    if(sessionStorage.getItem('collapselock') === false)
    {
        sessionStorage.setItem('collapselock', true);
    }
    return sessionStorage.getItem('collapselock');
}

function filesManager(module, BasicService, $rootScope)
{
    $('#selectImage').on('click', function(){
        BasicService.GetData(module+"/Files", "", function(response)
        {
            if (response.Status == 200) {
                $rootScope.Files = response.Output;
                $('#galleryBtn').click();
            } else {
                $scope.Error = response.Output;
            }
        });
    });
}

function saveImageUrl(url)
{
    sessionStorage.setItem('image', url);
    $('button.close').click();
    $('img#featuredImage').attr('src', url);
    $('img#featuredImage').removeClass('hidden');
}

// this transforms any form data into proper encoding for ajax communication
function transformRequest(obj)
{
    var $res = [];
    for(var key in obj)
    {
        $res.push(key + '=' + encodeURIComponent(obj[key]));
    }
    return $res.join('&');
}

function clickUpload()
{
    $('input#file').click();
}

function clickErrPopup()
{
    $('button#errorBtn').click();
}

function responseNotify(type, title, msg)
{
    switch(type)
    {
        case "Error":
        {
            $.notify({
                title: title, 
                type: 'danger', 
                message: '<strong>Oops</strong> '+msg+'. Contact tech support', 
                progress: 20 
            });
            break;
        }
        case "Success":
        {
            $.notify({ 
                title: title,
                type: 'success', 
                message: '<strong>Success</strong> '+msg+'.', 
                progress: 20 
            });
            break;
        }
    }
}

function clickOkPopup()
{
    $('button#okBtn').click();
}

function checkVariable(elem) {
    var type = typeof elem;
    switch (type) {
        case "string":
            {
                return "isString";
                break;
            }
        case "number":
            {
                return "isNumber";
                break;
            }
        case "object":
            {
                return "isObject";
                break;
            }
    }
}

function aContainsB(a, b) {
    return a.indexOf(b) >= 0;
}

function settingsController($scope, BasicService) 
{
    var setting = this;
    setting.saveSiteAnalyticsConfig = saveSiteAnalyticsConfig;
    setting.saveMailConfiguration = saveMailConfiguration;
    setting.getMailConfiguration = getMailConfiguration;
    
    getConfig();

    function getConfig()
    {
        BasicService.GetData("Config", "", function(response)
        {
            if (response.Status == 200) {
                //console.log(response.Output);
            } else {
                setting.Error = response.Output;
            }
        });
    }

    function getSiteAnalyticsConfig()
    {
        BasicService.GetData("Config/", 'Site/Website', function(response)
        {
            if (response.Status == 200) {
                setting.sitename = response.Output.AppName;
                setting.favicon = response.Output.Favicon;
                setting.siteurl = response.Output.SITE;
                setting.shorticon = response.Output.ShortIcon;
                setting.sitecdn = response.Output.CDN;
                setting.siteapi = response.Output.API;
                setting.siteauthor = response.Output.Author;
                setting.sitecopyrights = response.Output.Copyrights;
                setting.siteindex = response.Output.Robots;
                setting.sitekeywords = response.Output.Keywords;
                setting.minify = response.Output.Minify;
                setting.uploads = response.Output.Uploads;
            } else {
                setting.Error = response.Output;
            }
        });
    }

    function saveSiteAnalyticsConfig()
    {
        BasicService.PostData
        (
            "/SaveSiteConfigurations", 
            { 
                AppName: setting.sitename,
                Favicon: setting.favicon,
                SITE: setting.siteurl,
                ShortIcon: setting.shorticon,
                CDN: setting.sitecdn,
                API: setting.siteapi,
                Author: setting.siteauthor,
                Copyrights: setting.sitecopyrights,
                Robots: setting.siteindex,
                Keywords: setting.sitekeywords,
                Minify: setting.minify,
                Uploads: setting.uploads,
            }, 
            function(response)
            {
                if (response.Status == 200) {
                    setting.Error = null;
                    setting.saveSocial = response.Output;
                    setting.dataLoading = false;
                    alert(setting.saveSocial);
                    getSiteAnalyticsConfig();
                } else {
                    setting.Error = response.Output;
                    setting.dataLoading = false;
                    alert(response.Output);
                }
            }
        );
    }

    function getMailConfiguration()
    {
        BasicService.GetData("/Config", "/Mail", function(response)
        {
            if (response.Status == 200) {
                setting.host = response.Output.Host;
                setting.name = response.Output.FromName;
                setting.user = response.Output.Username;
                setting.email = response.Output.From;
                setting.password = response.Output.Password;
            } else {
                setting.mailerror = response.Output;
            }
        });
    };

    function saveMailConfiguration()
    {
        setting.dataLoading = true;
        BasicService.PostData
        (
            "/Configuratons/SaveMailConfigurations", 
            { 
                Host: setting.host, 
                Username: setting.user, 
                Password: setting.password, 
                FromName: setting.name, 
                From: setting.email, 
            }, 
            function(response)
            {
                if (response.Status == 200) {
                    setting.Error = null;
                    setting.mailConfig = response.Output;
                    setting.dataLoading = false;
                    getMailConfiguration();
                } else {
                    setting.mailerror = response.Output;
                    setting.dataLoading = false;
                }
            }
        );
    };
};