"use strict";

angular
    .module('app.controllers')
    .controller("AccountsCtrl", usersController)
    .controller("authCtrl", authController);

function usersController($scope, $state, BasicService, $rootScope, $location) 
{
    var accounts = this;
    accounts.submit = 'Add';
    accounts.getUserData = getUserData;
    accounts.Update = updateUser;
    accounts.Add= createUser;
    accounts.deleteUser = deleteUser;
    accounts.reset = function(){
        accounts.submit = 'Add';
        $('button').html('Add Manager Account');
        accounts.uname = accounts.status = accounts.type = accounts.email = accounts.fname = accounts.lname = accounts.mname = accounts.mobile = accounts.address = accounts.sex = accounts.access = null;
    };

    (function initController() {
        //filesManager("Accounts/", BasicService, $rootScope);
        // get all Posts data
        switch($state.current.name)
        {
            case "Accounts":
            {
                $location.path('Accounts/Users');
                break;
            }
            case "Managers":
            {
                getAllAccounts();
                break;
            }
            case "Users":
            {
                break;
            }
        }
    })();

    function getAllAccounts()
    {
        $rootScope.dataLoading = true;
        BasicService.GetData("/Accounts", "", function(response)
        {
            if (response.Status == 200) {
                accounts.Managers = response.Output;
                accounts.status = '';
                $rootScope.dataLoading = false;
            } else {
                accounts.Error = response.Output;
                responseNotify('Error', 'Account Managers', response.Output);
                $rootScope.dataLoading = false;
            }
        });
    };

    function getUserData(uname)
    {
        sessionStorage.setItem('currentAcct', uname);
        $rootScope.dataLoading = true;
        BasicService.GetData("Accounts/authid/"+ uname, "", function(response)
        {
            if (response.Status == 200) {
                accounts.status = response.Output[0].status;
                accounts.type = response.Output[0].type;
                accounts.email = response.Output[0].email;
                accounts.authid = response.Output[0].authid;
                accounts.fname = response.Output[0].fname;
                accounts.lname = response.Output[0].lname;
                accounts.mname = response.Output[0].mname;
                accounts.mobile = response.Output[0].mobile;
                accounts.address = response.Output[0].address;
                accounts.sex = response.Output[0].sex;
                accounts.access = response.Output[0].access;
                $('button').html('Update Manager Account');
                BasicService.GetData("Accounts/Auth/authid/"+ uname, "", function(response)
                {
                    if (response.Status == 200) {
                        accounts.uname = response.Output[0].username;
                        accounts.password = response.Output[0].password;
                        accounts.submit = 'Update';
                        $('a.btn-update').click();
                    }
                });
            } else {
                responseNotify('Error', 'Account Authentication', response.Output);
                $rootScope.dataLoading = false;
            }
        });
    };

    function updateUser()
    {
        var authid = sessionStorage.getItem('currentAcct');
        accounts.type = document.getElementById('type').value;
        accounts.sex = document.getElementById('sex').value;
        accounts.status = document.getElementById('status').value;
        $rootScope.dataLoading = true;
        BasicService.serverRequest
        (
            "/Accounts/authid/"+authid, 
            {  
                access: accounts.type,
                fname: accounts.fname,
                lname: accounts.lname,
                mname: accounts.mname,
                email: accounts.email,
                sex: accounts.sex,
                authid: authid,
                mobile: accounts.mobile,
                address: accounts.address,
                status: accounts.status,
            }, 
            "PUT",
            function(response)
            {
                if (response.Status == 200) {
                    responseNotify('Success', 'Account Managers', response.Output);                    
                    BasicService.serverRequest
                    (
                        "/Accounts/Forgot-Password/authid/"+authid, 
                        {  
                            username: accounts.uname, 
                            password: accounts.password, 
                            access: accounts.type,
                            authid: authid,
                            status: accounts.status,
                        }, 
                        "PUT",
                        function(response)
                        {
                            if (response.Status == 200) {
                                responseNotify('Success', 'Account Authentication', response.Output);
                                $rootScope.dataLoading = false;
                                getAllAccounts();
                                getUserData();
                                $('a.btn-update').click();
                            } else {
                                responseNotify('Error', 'Account Authentication', response.Output);
                                $rootScope.dataLoading = false;
                            }
                        }
                    );
                    $rootScope.dataLoading = false;
                } else {
                    responseNotify('Error', 'Account Managers', response.Output);
                    $rootScope.dataLoading = false;
                }
            }
        );
    };

    function createUser()
    {
        var authid = btoa(accounts.username+accounts.password);
        accounts.type = document.getElementById('type').value;
        accounts.sex = document.getElementById('sex').value;
        accounts.status = document.getElementById('status').value;
        $rootScope.dataLoading = true;
        BasicService.serverRequest
        (
            "/Accounts", 
            {  
                access: accounts.type,
                fname: accounts.fname,
                lname: accounts.lname,
                mname: accounts.mname,
                uname: accounts.uname,
                email: accounts.email,
                sex: accounts.sex,
                authid: authid,
                mobile: accounts.mobile,
                address: accounts.address,
                status: "pending",
            }, 
            "POST",
            function(response)
            {
                if (response.Status == 200) {
                    responseNotify('Success', 'Account Managers', response.Output);                    
                    BasicService.serverRequest
                    (
                        "/Accounts/Register", 
                        {  
                            username: accounts.uname, 
                            password: accounts.password, 
                            access: accounts.type,
                            authid: authid,
                            status: "pending",
                        }, 
                        "POST",
                        function(response)
                        {
                            if (response.Status == 200) {
                                responseNotify('Success', 'Account Authentication', response.Output);
                                $rootScope.dataLoading = false;
                                getAllAccounts();
                                $('a.btn-update').click();
                            } else {
                                responseNotify('Error', 'Account Authentication', response.Output);
                                $rootScope.dataLoading = false;
                            }
                        }
                    );
                } else {
                    responseNotify('Error', 'Account Managers', response.Output);
                    $rootScope.dataLoading = false;
                }
            }
        );
    };

    function deleteUser(authid)
    {
        $rootScope.dataLoading = true;
        BasicService.serverRequest
        (
            "Accounts/authid/"+authid, 
            "", 
            "DELETE",
            function(response)
            {
                if (response.Status == 200) {   
                    responseNotify('Success', 'Managers Information', response.Output);
                    BasicService.serverRequest
                    (
                        "Accounts/Remove/authid/"+authid, 
                        "", 
                        "DELETE",
                        function(response)
                        {
                            if (response.Status == 200) {   
                                getAllAccounts();
                                $rootScope.dataLoading = false;
                                responseNotify('Success', 'AUthentication Information', response.Output);
                            } else {
                                $rootScope.dataLoading = false;
                                responseNotify('Error', 'Authentication nformation', response.Output);
                            }
                        }
                    );
                } else {
                    $rootScope.dataLoading = false;
                    responseNotify('Error', 'Managers information', response.Output);
                }
            }
        );
    };
};

function authController($scope, $state, $rootScope, AuthenticationService) 
{
    $rootScope.page = "/Login";
    var vm = this; 
    vm.login = login; 
    (function initController() {
        // reset login status
        switch($state.current.name)
        {
            case "Lockscreen":
            {
                $rootScope.globals.currentUser.authData = null;
                lockscreen();
                break;
            }
            case "Logout":
            {
                logout();
                break;
            }
        }
    })();

    function lockscreen()
    {
        vm.username = $rootScope.globals.currentUser.username;
    }
 
    function login() 
    {
        AuthenticationService.ClearCredentials();
        $rootScope.dataLoading = true;
        AuthenticationService.Login(vm.username, vm.password, function (response) 
        {
            if (response.Status == 200) {
                AuthenticationService.SetCredentials(vm.username, vm.password, response.Output['token'], response.Output['data']);
                window.location = "Dashboard";
            } else {
                $rootScope.dataLoading = false;
                vm.Error = response.Output;
                vm.username = null;
                vm.password = null;
            }
        });
    };

    function logout()
    {
        AuthenticationService.ClearCredentials();
        // $state.go('Login');
        window.location.href = 'Login';
    };
}