"use strict";

angular
    .module('app.controllers')
    .controller("postCtrl", postController);

function postController($scope, $state, BasicService, $rootScope, $location, DTOptionsBuilder, siteFunctions) 
{
    $scope.dtOptions = DTOptionsBuilder.newOptions();
    var posts = this; 
    posts.getPostCategories = getPostCategories;
    posts.getAllPost = getAllPost;
    posts.updatePost = updatePost;
    posts.createPost = createPost;
    posts.deletePost = deletePost;
    posts.createCategory = createCategory;
    posts.deleteCategory = deleteCategory;
    posts.getAllComments = getAllComments;
    posts.deleteComments = deleteComments;
    posts.posted_on = new Date().toUTCString();
    posts.post = function(title){
        $rootScope.post = title;
        $location.path('Posts/Update');
    };
    (function initController() {
        filesManager("Posts/", BasicService, $rootScope);
        // get all Posts data
        switch($state.current.name)
        {
            case "Posts":
            {
                getAllPost();
                break;
            }
            case 'Comments':
            {
                getAllComments();
                break;
            }
            case "addPost":
            {
                posts.author = $rootScope.globals.currentUser.username;
                getPostCategories();
                siteFunctions.wysihtml5();
                siteFunctions.uploadFiles($rootScope);
                break;
            }
            case "updatePost":
            {
                if($rootScope.post){
                    processPost();
                    getPostCategories();
                }
                else{
                    $location.path('Posts');
                }
                break;
            }
            case "categoryPost":
            {
                getPostCategories();
                break;
            }
        }
    })();

    // Posts Posts
    function getAllPost(title)
    {
        $rootScope.dataLoading = true;
        BasicService.GetData("Posts", "", function(response)
        {
            if (response.Status == 200) {
                
                $scope.Posts = response.Output;
                $rootScope.dataLoading = false;
            } else {
                $rootScope.dataLoading = false;
                responseNotify("Error", "Posts", response.Output);
            }
        });
    };

    function processPost()
    {
        $rootScope.dataLoading = true;
        BasicService.GetData("Posts/title/", $rootScope.post.split(' ').join("-"), function(response)
        {
            if (response.Status == 200) {
                posts.id = response.Output[0].id;
                posts.title = response.Output[0].title;
                $('textarea').html(response.Output[0].description);
                posts.image = response.Output[0].image;
                posts.author = response.Output[0].author;
                posts.category = response.Output[0].category;
                $rootScope.post = null;
                $rootScope.dataLoading = false;
                siteFunctions.wysihtml5();
            } else {
                $rootScope.dataLoading = false;
                responseNotify("Error", "Posts", response.Output);
            }
        });
    };

    function updatePost()
    {
        var image = (sessionStorage.getItem('image')) ? sessionStorage.getItem('image') : posts.image;
        if(image == null)
        {
            alert('Select an image');
        }
        $rootScope.dataLoading = true;
        BasicService.serverRequest
        (
            "Posts/ID/"+posts.id, 
            { 
                title: posts.title,
                description: $('textarea').val(),
                author: posts.author,
                posted_on: posts.posted_on,
                image: encodeURI(image),
                category: document.getElementById('category').value,
            }, 
            "PUT",
            function(response)
            {
                if (response.Status == 200) {
                    $rootScope.dataLoading = false;
                    responseNotify("Success", "Update Posts", response.Output);
                } else {
                    $rootScope.dataLoading = false;
                    responseNotify("Error", "Update Posts", response.Output);
                }
            }
        );
    };

    function createPost()
    {
        var image = sessionStorage.getItem('image');
        if(image == null)
        {
            alert('Select an image');
        }
        else{
            $rootScope.dataLoading = true;
            BasicService.serverRequest
            (
                "Posts", 
                { 
                    title: posts.title,
                    description: $('textarea').val(),
                    author: posts.author,
                    posted_on: new Date().toLocaleTimeString(),
                    image: encodeURI(image),
                    category: document.getElementById('category').value,
                }, 
                "POST",
                function(response)
                {
                    if (response.Status == 200) {
                        $rootScope.dataLoading = false;
                        sessionStorage.removeItem('image');
                        responseNotify("Success", "Add Posts", response.Output);
                    } else {
                        $rootScope.dataLoading = false;
                        responseNotify("Error", "Posts", response.Output);
                    }
                }
            );
        }
    };

    function deletePost(title)
    {
        $rootScope.dataLoading = true;
        BasicService.serverRequest
        (
            "Posts/id/"+title, 
            "", 
            'DELETE',
            function(response)
            {
                if (response.Status == 200) {
                    $scope.Error = null;
                    $scope.response = response.Output;
                    $rootScope.dataLoading = false;
                    getAllPost();
                } else {
                    responseNotify("Error", "Posts", response.Output);
                    $rootScope.dataLoading = false;
                }
            }
        );
    };

    // Posts Comments
    function getAllComments()
    {
        $rootScope.dataLoading = true;
        BasicService.GetData("Posts/Comments", "", function(response)
        {
            if (response.Status == 200) {
                $scope.Error = null;
                $scope.Comments = response.Output;
                $rootScope.dataLoading = false;
            } else {
                responseNotify("Error", "Posts", response.Output);
                $rootScope.dataLoading = false;
            }
        });
    };

    function deleteComments(id)
    {
        $rootScope.dataLoading = true;
        BasicService.serverRequest
        (
            "Posts/Comments/id/"+id, 
            "", 
            "DELETE",
            function(response)
            {
                if (response.Status == 200) {
                    $rootScope.msg = response.Output;
                    getAllComments();
                    $rootScope.dataLoading = false;
                } else {
                    responseNotify("Error", "Comments", response.Output);
                    $rootScope.dataLoading = false;
                }
            }
        );
    };

    // Posts Categories
    function getPostCategories()
    {
        $rootScope.dataLoading = true;
        BasicService.GetData("Posts", "/Category", function(response)
        {
            if (response.Status == 200) {
                $scope.categories = response.Output;
                $rootScope.dataLoading = false;
            } else {
                responseNotify("Error", "Posts Categories", response.Output);
                $rootScope.dataLoading = false;
            }
        });
    };

    function createCategory()
    {
        $rootScope.dataLoading = true;
        BasicService.serverRequest
        (
            "Posts/Category", 
            { 
                name: posts.category,
                description: posts.description,
                sub: document.getElementById('category').value,
                image: posts.category+"Posts.jpg",
            }, 
            "POST",
            function(response)
            {
                if (response.Status == 200) {
                    responseNotify("Success", "Add Category", response.Output);
                    $rootScope.dataLoading = false;
                    getPostCategories();
                } else {
                    responseNotify("Error", "Add Category", response.Output);
                    $rootScope.dataLoading = false;
                }
            }
        );
    };

    function deleteCategory(category)
    {
        $rootScope.dataLoading = true;
        BasicService.serverRequest
        (
            "Posts/Category/id/"+category, 
            "", 
            "DELETE",
            function(response)
            {
                if (response.Status == 200) {
                    $rootScope.dataLoading = false;
                    responseNotify("Success", "Delete Category", response.Output);
                    getPostCategories();
                } else {
                    responseNotify("Error", "Delete Category", response.Output);
                    $rootScope.dataLoading = false;
                }
            }
        );
    };
};