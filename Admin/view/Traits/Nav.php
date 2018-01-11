

            <aside class="sidebar bg-gray-light" ng-class="(page == '/Dashboard') ? 'sidebar--hidden' : 'sidebar-shadow'" ng-if="sidebar !== false">
                <div class="scrollbar-inner">
                    <ul class="navigation" style="margin-top: -10px">
                        <li class=""><a href="Dashboard"><i class="zmdi zmdi-home"></i> Dashboard</a></li>
                        <li class="navigation__sub">
                            <a href="#"><i class="zmdi zmdi-chart"></i>Site Analytics</a>
                            <ul>
                                <!-- <li class="#"><a href="Analytics/Status">Status</a></li> -->
                                <li class="#"><a href="Analytics/Logs">Logs</a></li>
                                <li class="#"><a href="Analytics/Seo">Seo</a></li>
                            </ul>
                        </li>

                        <hr style="margin-top: 5px !important; margin-bottom: 5px !important" />

                        <li class="navigation__sub">
                            <a href="#"><i class="zmdi zmdi-view-web"></i>Interfaces</a>
                            <ul>
                                <li class="#"><a href="Faqs">Frequently Asked Questions</a></li>
                                <li class="#"><a href="Pages">Pages</a></li>
                            </ul>
                        </li>

                        <hr style="margin-top: 5px !important; margin-bottom: 5px !important" />

                        <li class=""><a href="Posts"><i class="zmdi zmdi-picture-in-picture"></i> Posts</a></li>
                        <li class=""><a href="Posts/Category"><i class="zmdi zmdi-collection-text"></i> Category</a></li>
                        <li class=""><a href="Comments"><i class="zmdi zmdi-comment-alt-text"></i> Comments</a></li>

                        <hr style="margin-top: 5px !important; margin-bottom: 5px !important" />

                        <li class=""><a href="Accounts/Users"><i class="zmdi zmdi-account-circle"></i> Users</a></li>
                        <li class=""><a href="Accounts/Managers"><i class="zmdi zmdi-accounts-list-alt"></i> Managers</a></li>

                        <hr style="margin-top: 5px !important; margin-bottom: 5px !important" />

                        <li class=""><a href="Newsletter/Subscribers"><i class="zmdi zmdi-account-box-mail"></i>Subscribers</a></li>
                        <li class=""><a href="Newsletter/Messages"><i class="zmdi zmdi-comment"></i>Messages</a></li>
                        <!-- <hr style="margin-top: 0px !important; margin-bottom: 0px !important" />
                        <li><a href="Settings"><i class="zmdi zmdi-wrench"></i>Settings</a></li> -->
                    </ul>
                </div>
            </aside>

            <aside class="chat" ng-if="sidebar !== false">
                <div class="chat__header">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active no-padding padding-10 bold" data-toggle="tab" data-target="#general-tab" role="tab">General</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link no-padding padding-10" data-toggle="tab" data-target="#mail-tab" role="tab">Mail</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link no-padding padding-10" data-toggle="tab" data-target="#social-tab" role="tab">Social</a>
                        </li>
                    </ul>
                </div>
                <div class="listview listview--hover chat__buddies scrollbar-inner" ng-controller="settingCtrl">
                    <div class="row">
                        <div class="col-sm-12">                    
                            <div class="tab-content padding-20">
                                <div class="tab-pane fade active show m-b-100" id="general-tab">
                                    <h5 class="fs-14 bold hint-text text-default">General Site Configuration </h5>
                                    <form role="form" ng-submit="setting.saveSiteAnalyticsConfig()" class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Site Url</label>
                                                <input type="url" placeholder="Enter site url" class="form-control" name="siteurl" ng-model="setting.siteurl" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Favicon</label>
                                                <input type="url" placeholder="Enter site favicon" class="form-control" name="favicon" ng-model="setting.favicon" required>
                                            </div>
                                            <div class="m-t-10 form-group">
                                                <label>Short Icon</label>
                                                <input type="url" placeholder="Enter site shorticon" class="form-control" name="shorticon" ng-model="setting.shorticon" required>
                                            </div>
                                            <div class="m-t-10 form-group">
                                                <label>Upload</label>
                                                <input type="text" placeholder="Enter Uploads information" class="form-control" name="siteupload" ng-model="setting.siteupload" required>
                                            </div>
                                            <div class="m-t-10 form-group hidden">
                                                <label>Minify Fiie</label>
                                                <input type="hidden" placeholder="Enter Copyrights information" class="form-control" value="1" name="minify" ng-model="setting.minify" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Site Name</label>
                                                <input type="text" placeholder="Enter site name" class="form-control" name="sitename" ng-model="setting.sitename" required>
                                            </div>
                                            <div class="m-t-10 form-group">
                                                <label>Author</label>
                                                <input type="text" placeholder="Enter Author" class="form-control" name="siteauthor" ng-model="setting.siteauthor" required>
                                            </div>
                                            <div class="m-t-10 form-group">
                                                <label>Copyrights</label>
                                                <input type="text" placeholder="Enter Copyrights information" class="form-control" name="sitecopyrights" ng-model="setting.sitecopyrights" required>
                                            </div>
                                            <div class="m-t-10 form-group">
                                                <label>Site Index</label>
                                                <input type="text" placeholder="Enter site indexing key words" class="form-control" name="siteindex" ng-model="setting.siteindex" required>
                                            </div>
                                            <div class="m-t-10 form-group">
                                                <label>Site Server URL</label>
                                                <input type="url" placeholder="Enter site server url" class="form-control" name="siteapi" ng-model="setting.siteapi" required>
                                            </div>
                                            <div class="m-t-10 form-group">
                                                <label>Site Content Deliver Network</label>
                                                <input type="url" placeholder="Enter site cdn" class="form-control" name="sitecdn" ng-model="setting.sitecdn" required>
                                            </div>
                                            <div class="m-t-10 form-group">
                                                <label>Site Search Keywords</label>
                                                <input type="text" placeholder="Enter site search key words" class="form-control" name="sitekeywords" ng-model="setting.sitekeywords" required>
                                            </div>
                                            <div class="form-action">
                                                <button type="submit" class="btn btn--action btn--fixed bg-indigo text-white"><i class="zmdi zmdi-google-play"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade row" id="mail-tab">
                                    <form role="form" ng-submit="setting.saveMailConfiguration()" class="col-12">
                                        <p class="text-success" ng-bind='mailConfig'></p>
                                        <p class="text-danger" ng-bind='mailerror'></p>
                                        <div class="form-group">
                                            <label>Mail Host</label>
                                            <input type="text" placeholder="Enter mail server host" class="form-control" name="host" ng-model="setting.host">
                                        </div>
                                        <div class="m-t-10 form-group">
                                            <label>Mail Server Name</label>
                                            <input type="text" placeholder="Enter mail server name" class="form-control" name="name" ng-model="setting.name">
                                        </div>
                                        <div class="m-t-10 form-group">
                                            <label>Mail Server User</label>
                                            <input type="email" placeholder="Enter mail server email" class="form-control" name="user" ng-model="setting.user">
                                        </div>
                                        <div class="m-t-10 form-group">
                                            <label>Mail Server Password</label>
                                            <input type="password" placeholder="Enter mail server password" class="form-control" name="password" ng-model="setting.password">
                                        </div>
                                        <div class="m-t-10 form-group">
                                            <label>Response Email</label>
                                            <input type="email" placeholder="Enter email for user response" class="form-control" name="email" ng-model="setting.email">
                                        </div>
                                        <div class="form-action">
                                            <button type="submit" class="btn btn--action btn--fixed bg-info text-white"><i class="zmdi zmdi-google-play"></i></button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade m-b-100" id="social-tab">
                                    <form class="row">
                                        <div class="col-12"> 
                                            <h5 class="fs-14 bold hint-text text-default">Facebook Configuration Information </h5>
                                            <br />
                                            <div class="form-group">
                                                <label>Facebook Page Name</label>
                                                <input type="text" placeholder="Enter Facebook Page Name" class="form-control" name="fbsitename" ng-model="fbsitename" required/>
                                            </div>
                                            <div class="form-group">
                                                <label>Facebook Type</label>
                                                <input type="text" placeholder="Enter Facebook Type" class="form-control" name="fbtype" ng-model="fbtype" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Facebook Admin</label>
                                                <input type="text" placeholder="Enter Facebook Admin" class="form-control" name="fbadmin" ng-model="fbadmin" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Facebook Page Url</label>
                                                <input type="url" placeholder="Enter Facebook Page url" class="form-control" name="fbpageurl" ng-model="fbpageurl" required>
                                            </div>
                                            <h5 class="fs-14 bold hint-text text-default">Google Configuration Information </h5>
                                            <br />
                                            <div class="form-group">
                                                <label>Google Business Name</label>
                                                <input type="text" placeholder="Enter your google business name" class="form-control" name="gpbname" ng-model="gpbname" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Google Author Name</label>
                                                <input type="text" placeholder="Enter your google business author name" class="form-control" name="gpbaname" ng-model="gpbaname" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Google Page Name</label>
                                                <input type="text" placeholder="Enter your google page name" class="form-control" name="gppagename" ng-model="gppagename" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Google Publisher Url</label>
                                                <input type="url" placeholder="Enter your google publisher link" class="form-control" name="gppageurl" ng-model="gppageurl" required>
                                            </div>
                                            <h5 class="fs-14 bold hint-text text-default">Site Analytics Information </h5>
                                            <br />
                                            <div class="form-group">
                                                <label>Google Analytics</label>
                                                <input type="text" placeholder="Enter Google Analytics code" class="form-control" name="googleanalytics" ng-model="googleanalytics" required/>
                                            </div>
                                            <div class="form-group">
                                                <label>Bing Analytics</label>
                                                <input type="text" placeholder="Enter Bing Analytics code" class="form-control" name="binganalytics" ng-model="binganalytics" required/>
                                            </div>
                                            <div class="form-group">
                                                <label>Yandex Analytics</label>
                                                <input type="text" placeholder="Enter Yandex Analytics code" class="form-control" name="yandexanalytics" ng-model="yandexanalytics" required/>
                                            </div>
                                            <div class="form-action">
                                                <button type="submit" class="btn btn--action btn--fixed bg-danger text-white"><i class="zmdi zmdi-google-play"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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