"use strict";

angular
    .module('app.controllers')
    .controller("mailCtrl", mailController);

function mailController($scope, $state, BasicService, $rootScope, $location, siteFunctions) 
{
    var mail = this;
    mail.sendnewsLetter = newsLetter;
    mail.createNewsletter = createNewsletter;
    mail.deleteNewsletter = deleteNewsletter;
    mail.updateNewsletter = updateNewsletter;
    mail.getMessage = getMessage;
    siteFunctions.wysihtml5();
    mail.removeSubscribers = unSubscribe;
    mail.addNew = function(){
        mail.title = mail.message = mail.subject = null;
    };

    (function initController() {
        mail.author = $rootScope.globals.currentUser.username;
        
        switch($state.current.name)
        {
            case "Newsletter":
            {
                $location.path('Newsletter/Subscribers');
                break;
            }
            case "Subscribers":
            {
                getAllSubscribers();
                break;
            }
            case "Newsletters":
            {
                getAllMessages();
                break;
            }
        }
    })();

    function getAllMessages()
    {
        $rootScope.dataLoading = true;
        BasicService.GetData("Email/Newsletter", "/Messages", function(response)
        {
            if (response.Status == 200) {
                $scope.messages = response.Output;
                $rootScope.dataLoading = false;
            } else {
                $rootScope.dataLoading = false;
                responseNotify('Error', 'Newsletters', response.Output);
            }
        });
    };

    function getMessage(id)
    {
        $rootScope.dataLoading = true;
        BasicService.GetData("Email/Newsletter", "/Messages/id/"+id, function(response)
        {
            if (response.Status == 200) {
                $rootScope.dataLoading = false;
                $('a.btn-update').click();
                mail.title = response.Output[0].title;
                mail.subject = response.Output[0].subject;
                mail.message = response.Output[0].message;
                mail.author = response.Output[0].by;
                mail.created = response.Output[0].created;
                $('textarea').html(response.Output[0].message);
                $('form.row').attr('ng-submit', 'mail.updateNewsletter()');
            } else {
                $rootScope.dataLoading = false;
                responseNotify('Error', 'Newsletters', response.Output);
            }
        });
    };

    function createNewsletter()
    {
        $rootScope.dataLoading = true;
        BasicService.serverRequest
        (
            "Email/Newsletter/Messages", 
            { 
                title: mail.title, 
                subject: mail.subject, 
                message: $('textarea').val(), 
                by: mail.author, 
                created: null
            }, 
            "POST",
            function(response)
            {
                if (response.Status == 200) {
                    $rootScope.dataLoading = false;
                    responseNotify("Success", "Create Newsletter", response.Output);
                    getAllMessages();
                } else {
                    $scope.Error = response.Output;
                    $rootScope.dataLoading = false;
                    responseNotify("Error", "Create Newsletter", response.Output);
                }
            }
        );
    };

    function updateNewsletter()
    {
        $rootScope.dataLoading = true;
        BasicService.serverRequest
        (
            "Email/Newsletter/Messages", 
            { 
                title: mail.title, 
                subject: mail.subject, 
                message: $('textarea').val(), 
                by: mail.author, 
                created: null
            }, 
            "PUT",
            function(response)
            {
                if (response.Status == 200) {
                    $rootScope.dataLoading = false;
                    responseNotify("Success", "Update Newsletter", response.Output);
                    getAllMessages();
                    $('a.btn--fixed').click();
                } else {
                    $scope.Error = response.Output;
                    $rootScope.dataLoading = false;
                    responseNotify("Error", "Update Newsletter", response.Output);
                }
            }
        );
    };

    function deleteNewsletter(id)
    {
        $rootScope.dataLoading = true;
        BasicService.serverRequest
        (
            "Email/Newsletter/Messages/id/"+id, 
            {
                id: id
            }, 
            "DELETE",
            function(response)
            {
                if (response.Status == 200) {
                    $rootScope.dataLoading = false;
                    responseNotify("Success", "Delete Newsletter", response.Output);
                    getAllMessages();
                } else {
                    $scope.Error = response.Output;
                    $rootScope.dataLoading = false;
                    responseNotify("Error", "Create Newsletter", response.Output);
                }
            }
        );
    };

    function getAllSubscribers()
    {
        $rootScope.dataLoading = true;
        BasicService.GetData("/Email", "/Subscribers", function(response)
        {
            if (response.Status == 200) {
                $scope.Subscribers = response.Output;
                $rootScope.dataLoading = false;
            } else {
                $rootScope.dataLoading = false;
                responseNotify('Error', 'Subscribers', response.Output);
            }
        });
    };

    function newsLetter()
    {
        $rootScope.dataLoading = true;
        BasicService.PostData
        (
            "/Mail/Newsletter", 
            { 
                subject: mail.subject, 
                message: mail.message, 
            }, 
            function(response)
            {
                if (response.Status == 200) {
                    $scope.Error = null;
                    $scope.newsLetterResponse = response.Output;
                    $rootScope.dataLoading = false;
                } else {
                    $scope.Error = response.Output;
                    $rootScope.dataLoading = false;
                }
            }
        );
    };

    function unSubscribe(email)
    {
        $rootScope.dataLoading = true;
        BasicService.GetData
        (
            "/Email/Unsubscribe/" + Mail, 
            "", 
            function(response)
            {
                if (response.Status == 200) {
                    $scope.Error = null;
                    $scope.response = response.Output;
                    getAllSubscribers();
                    $rootScope.dataLoading =false;
                } else {
                    $scope.response = null;
                    $scope.Error = response.Output;
                    $rootScope.dataLoading = false;
                }
            }
        );
    };
};