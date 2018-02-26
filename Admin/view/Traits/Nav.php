

            <aside class="sidebar bg-gray" ng-class="(page == '/Dashboard') ? 'sidebar--hidden' : 'sidebar-shadow'" ng-if="sidebar !== false">
                <div class="scrollbar-inner">
                    <ul class="navigation" style="margin-top: -10px">
                        <li class=""><a href="Dashboard"><i class="zmdi zmdi-home"></i> Dashboard</a></li>

                        <hr style="margin-top: 5px !important; margin-bottom: 5px !important" />

                        <li class="#"><a href="Faqs">Frequently Asked Questions</a></li>
                        
                        <hr style="margin-top: 5px !important; margin-bottom: 5px !important" />

                        <li class=""><a href="Posts"><i class="zmdi zmdi-picture-in-picture"></i> Posts</a></li>
                        <li class=""><a href="Posts/Category"><i class="zmdi zmdi-collection-text"></i> Category</a></li>
                        <li class=""><a href="Comments"><i class="zmdi zmdi-comment-alt-text"></i> Comments</a></li>

                        <hr style="margin-top: 5px !important; margin-bottom: 5px !important" />

                        <li class=""><a href="Accounts/Users"><i class="zmdi zmdi-account-circle"></i> Users</a></li>
                        <li class=""><a href="Accounts/Managers"><i class="zmdi zmdi-accounts-list-alt"></i> Managers</a></li>
                    </ul>
                </div>
            </aside>
    
            <ui-view></ui-view>

            <button class="hidden" id="galleryBtn" data-target="#gallery" data-toggle="modal">Select Image</button>
            <!-- Gallery -->
            <div class="modal fade" id="gallery" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body no-padding padding-10">
                            <div class="no-padding">
                                <ul class="nav nav-tabs simple-nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" data-target="#upload" role="tab">Upload</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="listBtn" data-toggle="tab" data-target="#listinggallery" role="tab">Gallery</a>
                                    </li>
                                </ul>
                                <div class="tab-content no-padding">
                                    <div class="tab-pane active fade show" id="upload" role="tabpanel">
                                        <div class="row">
                                            <div class="col-4 offset-4 m-t-100 m-b-100" align="center">
                                                <button class="btn btn-cons btn-secondary btn-lg" ng-click="clickUpload()">Upload file</button>
                                                <div class="clearfix"></div>
                                                Upload file to server 
                                            </div>
                                        </div>
                                        <form method="POST" enctype="multipart/form-data">
                                            <input type="file" ng-file-model="files" id="file" class="form-control hidden" required multiple>
                                        </form>
                                        <!-- <div class="row" ng-repeat="(index, file) in files">
                                            <div class="col-2">
                                                <img ng-src="{{convertUrl(file.url)}}" style="height: 100px !important; width: 100px !important" />
                                            </div>
                                            <div class="col-6">
                                                <p class="fs-14 bold" ng-bind="file.name"></p>
                                            </div>
                                        </div> -->
                                    </div>
                                    <div class="tab-pane fade h-80" id="listinggallery" role="tabpanel">
                                        <div class="row p-l-10 p-r-10">
                                            <div class="col-sm-2 p-l-5 p-r-5 img-wrap" style="margin-top: 10px" ng-if="index !== 'count'" ng-repeat='(index, images) in Files'>
                                                <span class="btn btn-danger btn-sm close text-white btn-circle" ng-click="deleteImage(images)">&times;</span>
                                                <span ng-bind="index" class="hidden"></span>
                                                <img ng-click="saveImageUrl(images)" class="img-responsive gallery-img" ng-src="{{images}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer hidden">
                        <button type="button" class="btn btn-link close" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <button class="hidden" id="okBtn" data-target="#okay" data-toggle="modal">confirm</button>
            <!-- Confirm Modal -->
            <div class="modal fade" id="okay" tabindex="-1">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title pull-left">Notification</h5>
                        </div>
                        <div class="modal-body">
                            <h3 class="text-black fs-20" ng-bind="msg"></h3>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info btn-link" data-dismiss="modal">Ok</button>
                        </div>
                    </div>
                </div>
            </div>

            <button class="hidden" id="errorBtn" data-target="#error" data-toggle="modal">error</button>
            <!-- Confirm Modal -->
            <div class="modal fade" id="error" tabindex="-1">
                <div class="modal-dialog modal-sm bg-red">
                    <div class="modal-content bg-danger">
                        <div class="modal-header">
                            <h5 class="modal-title text-white pull-left">Error Notification</h5>
                        </div>
                        <div class="modal-body text-white" ng-bind="Error"></div>
                        <div class="modal-footer text-center">
                            <button type="button" class="btn bg-white text-danger" data-dismiss="modal">DImiss</button>
                        </div>
                    </div>
                </div>
            </div>