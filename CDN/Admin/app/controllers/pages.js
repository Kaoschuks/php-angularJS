"use strict";

angular
    .module('app.controllers')
    .controller("pagesCtrl", pagesController);

function pagesController($scope, modules, $state, localStorageService, $rootScope, BasicService, $location, siteFunctions, dashboardFunctions) 
{
    var pages = this; 
    pages.saveAboutUs = saveAboutUs;
    pages.saveTerm = saveTerm;
    pages.saveFaq = saveFaq;
    pages.getSingleFaq = getSingleFaq;
    pages.deleteFaq = deleteFaq;

    (function initController() {
        // get all user data
        switch($state.current.name)
        {
            case "Faqs":
            {
                getFaq();
                break;
            }
        }
    })();

    function getSingleFaq(title)
    {
        title = encodeURI(title);
        $rootScope.dataLoading = true;
        BasicService.GetData("/Interfaces", "?action=readSingleFaq&title="+title, function(response)
        {
            if (response.Status == 200) {
                pages.updateTitle = response.Output.title;
                pages.updateFaq = response.Output.response,
                $rootScope.dataLoading = false;
            } else {
                $scope.Error = response.Output;
                $rootScope.dataLoading = false;
            }
        });
    }

    function getFaq()
    {
        $rootScope.dataLoading = true;
        BasicService.GetData("/Pages", "/Faq", function(response)
        {
            if (response.Status == 200) {
                pages.faqs = response.Output;
                $rootScope.dataLoading = false;
            } else {
                $scope.Error = response.Output;
                $rootScope.dataLoading = false;
            }
        });
    }

    function updateFaq()
    {
        $rootScope.dataLoading = true;
        BasicService.serverRequest
        (
            "/Interfaces", 
            { 
                action: "updateFaq", 
                title: pages.Title,
                response: pages.Faq,
                previous: pages.previous,
            }, 
            function(response)
            {
                if (response.Status == 200) {
                    $scope.Error = null;
                    alert(response.Output);
                    getFaq();
                    $rootScope.dataLoading = false;
                } else {
                    $scope.Error = response.Output;
                    $rootScope.dataLoading = false;
                }
            }
        );
    }

    function saveFaq()
    {
        $rootScope.dataLoading = true;
        BasicService.serverRequest
        (
            "/Pages/Faq", 
            { 
                question: pages.question,
                answer: pages.title,
            }, 
            'POST',
            function(response)
            {
                if (response.Status == 200) {
                    $rootScope.dataLoading = false;
                    responseNotify('Success', 'Faqs', response.Output);
                    getFaq();
                } else {
                    $rootScope.dataLoading = false;
                    responseNotify('Error', 'Faqs', response.Output);
                }
            }
        );
    }

    function deleteFaq(title)
    {
        $rootScope.dataLoading = true;
        BasicService.serverRequest
        (
            "/Pages/Faq/id/"+title, 
            "",
            "DELETE", 
            function(response)
            {
                if (response.Status == 200) {
                    $rootScope.dataLoading = false;
                    responseNotify('Success', 'Faqs', response.Output);
                    getFaq();
                } else {
                    $rootScope.dataLoading = false;
                    responseNotify('Error', 'Faqs', response.Output);
                }
            }
        );
    }

};