<?php
/**
 * @var \App\ViewModels\DocumentViewModel $item
 */
?>
        <!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="mobile-web-app-capable" content="yes"/>
    <!--
    *
    * (c) Copyright Ascensio System SIA 2024
    *
    * Licensed under the Apache License, Version 2.0 (the "License");
    * you may not use this file except in compliance with the License.
    * You may obtain a copy of the License at
    *
    *     http://www.apache.org/licenses/LICENSE-2.0
    *
    * Unless required by applicable law or agreed to in writing, software
    * distributed under the License is distributed on an "AS IS" BASIS,
    * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    * See the License for the specific language governing permissions and
    * limitations under the License.
    *
    -->
    <title>{{ $item->fileName }}</title>
    <style>
        html {
            height: 100%;
            width: 100%;
        }

        body {
            background: #fff;
            color: #333;
            font-family: Arial, Tahoma, sans-serif;
            font-size: 12px;
            font-weight: normal;
            height: 100%;
            margin: 0;
            overflow-y: hidden;
            padding: 0;
            text-decoration: none;
        }

        .form {
            height: 100%;
        }

        div {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
<div class="form">
    <div id="iframeEditor">
    </div>
</div>
<script type="text/javascript" src="{{ config('office.public_url_office') }}/web-apps/apps/api/documents/api.js"></script>
{{--<script type="text/javascript" language="javascript">
    var innerAlert = function (message, inEditor) {
        if (console && console.log)
            console.log(message);
        if (inEditor && docEditor)
            docEditor.showMessage(message);
    };

    // the application is loaded into the browser
    var onAppReady = function () {
        innerAlert("Document editor ready");
    };

    // an error or some other specific event occurs
    var onError = function (event) {
        if (event)
            innerAlert(event.data);
    };

    // the document is opened for editing with the old document.key value
    var onOutdatedVersion = function (event) {
        location.reload(true);
    };

    var requestReference = function (data, callback) {
        innerAlert(data);

        data.directUrl = !!config.document.directUrl;
        let xhr        = new XMLHttpRequest();
        xhr.open("POST", "reference");
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.send(JSON.stringify(data));
        xhr.onload = function () {
            innerAlert(xhr.responseText);
            console.log(JSON.parse(xhr.responseText));
            callback(JSON.parse(xhr.responseText));
        }
    };

    var onRequestOpen = function (event) {  // user open external data source
        innerAlert("onRequestOpen");
        var windowName = event.data.windowName;

        requestReference(event.data, function (data) {
            if (data.error) {
                var winEditor = window.open("", windowName);
                winEditor.close();
                innerAlert(data.error, true);
                return;
            }

            var link = data.link;
            window.open(link, windowName);
        });
    };

    // the document is modified
    var onDocumentStateChange = function (event) {
        var title      = document.title.replace(/\*$/g, "");
        document.title = title + (event.data ? "*" : "");
    };

    // the user is trying to switch the document from the viewing into the editing mode
    var onRequestEditRights = function () {
        location.href = location.href.replace(RegExp("action=\\w+\&?", "i"), "") + "&action=edit";
    };

    // the meta information of the document is changed via the meta command
    var onMetaChange = function (event) {
        if (event.data.favorite !== undefined) {
            var favorite   = !!event.data.favorite;
            var title      = document.title.replace(/^\☆/g, "");
            document.title = (favorite ? "☆" : "") + title;
            docEditor.setFavorite(favorite);  // change the Favorite icon state
        }

        innerAlert("onMetaChange: " + JSON.stringify(event.data));
    };

    // the user is trying to get link for opening the document which contains a bookmark,
    // scrolling to the bookmark position
    var onMakeActionLink = function (event) {
        var actionData = event.data;
        var linkParam  = JSON.stringify(actionData);
        // set the link to the document which contains a bookmark
        docEditor.setActionLink(replaceActionLink(location.href, linkParam));
    };

    // the user is trying to insert an image by clicking the Image from Storage button
    var onRequestInsertImage = function (event) {
        docEditor.insertImage({  // insert an image into the file
            "c": event.data.c
        })
    };

    var onRequestReferenceData = function (event) {  // user refresh external data source
        innerAlert("onRequestReferenceData");

        requestReference(event.data, function (data) {
            docEditor.setReferenceData(data);
        });
    };

    // the user is trying to select recipients data by clicking the Mail merge button
    var onRequestSelectSpreadsheet = function (event) {
        // insert recipient data for mail merge into the file
        var data = {dataSpreadsheet};
        data.c   = event.data.c;
        docEditor.setRequestedSpreadsheet(data);
    };


    // the user is trying to select document for comparing by clicking the Document from Storage button
    var onRequestSelectDocument = function (event) {
        var data = {dataDocument};
        data.c   = event.data.c;
        docEditor.setRequestedDocument(data);  // select a document for comparing
    };

    var сonnectEditor = function () {
        config        = @json($item->config);
        config.width  = "100%";
        config.height = "100%";
        config.events = {
            'onAppReady'                : onAppReady,
            'onDocumentStateChange'     : onDocumentStateChange,
            'onError'                   : onError,
            'onOutdatedVersion'         : onOutdatedVersion,
            'onMakeActionLink'          : onMakeActionLink,
            'onMetaChange'              : onMetaChange,
            'onRequestInsertImage'      : onRequestInsertImage,
            'onRequestSelectDocument'   : onRequestSelectDocument,
            'onRequestSelectSpreadsheet': onRequestSelectSpreadsheet,
            'onRequestReferenceData'    : onRequestReferenceData,
            "onRequestOpen"             : onRequestOpen,
        };

        docEditor = new DocsAPI.DocEditor("iframeEditor", config);
    };

    if (window.addEventListener) {
        window.addEventListener("load", сonnectEditor);
    } else if (window.attachEvent) {
        window.attachEvent("load", сonnectEditor);
    }
</script>--}}


<script type="text/javascript">

    var docEditor;
    var config;
    let history;

    var innerAlert = function (message, inEditor) {
        if (console && console.log)
            console.log(message);
        if (inEditor && docEditor)
            docEditor.showMessage(message);
    };

    // the application is loaded into the browser
    var onAppReady = function () {
        innerAlert("Document editor ready");
    };

    // the document is modified
    var onDocumentStateChange = function (event) {
        var title      = document.title.replace(/\*$/g, "");
        document.title = title + (event.data ? "*" : "");
    };

    // the user is trying to switch the document from the viewing into the editing mode
    var onRequestEditRights = function () {
        location.href = location.href.replace(RegExp("action=\\w+\&?", "i"), "") + "&action=edit";
    };

    // an error or some other specific event occurs
    var onError = function (event) {
        if (event)
            innerAlert(event.data);
    };

    // the document is opened for editing with the old document.key value
    var onOutdatedVersion = function (event) {
        location.reload(true);
    };

    // replace the link to the document which contains a bookmark
    var replaceActionLink = function (href, linkParam) {
        var link;
        var actionIndex = href.indexOf("&actionLink=");
        if (actionIndex != -1) {
            var endIndex = href.indexOf("&", actionIndex + "&actionLink=".length);
            if (endIndex != -1) {
                link = href.substring(0, actionIndex) + href.substring(endIndex) +
                    "&actionLink=" + encodeURIComponent(linkParam);
            } else {
                link = href.substring(0, actionIndex) + "&actionLink=" + encodeURIComponent(linkParam);
            }
        } else {
            link = href + "&actionLink=" + encodeURIComponent(linkParam);
        }
        return link;
    };

    var onRequestOpen = function (event) {  // user open external data source
        innerAlert("onRequestOpen");
        var windowName = event.data.windowName;

        requestReference(event.data, function (data) {
            if (data.error) {
                var winEditor = window.open("", windowName);
                winEditor.close();
                innerAlert(data.error, true);
                return;
            }

            var link = data.link;
            window.open(link, windowName);
        });
    };

    var onRequestReferenceData = function (event) {  // user refresh external data source
        innerAlert("onRequestReferenceData");

        requestReference(event.data, function (data) {
            docEditor.setReferenceData(data);
        });
    };

    var requestReference = function (data, callback) {
        innerAlert(data);

        data.directUrl = !!config.document.directUrl;
        let xhr        = new XMLHttpRequest();
        xhr.open("POST", "reference");
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.send(JSON.stringify(data));
        xhr.onload = function () {
            innerAlert(xhr.responseText);
            console.log(JSON.parse(xhr.responseText));
            callback(JSON.parse(xhr.responseText));
        }
    };

    // the user is trying to get link for opening the document which contains a bookmark,
    // scrolling to the bookmark position
    var onMakeActionLink = function (event) {
        var actionData = event.data;
        var linkParam  = JSON.stringify(actionData);
        // set the link to the document which contains a bookmark
        docEditor.setActionLink(replaceActionLink(location.href, linkParam));
    };

    // the meta information of the document is changed via the meta command
    var onMetaChange = function (event) {
        if (event.data.favorite !== undefined) {
            var favorite   = !!event.data.favorite;
            var title      = document.title.replace(/^\☆/g, "");
            document.title = (favorite ? "☆" : "") + title;
            docEditor.setFavorite(favorite);  // change the Favorite icon state
        }

        innerAlert("onMetaChange: " + JSON.stringify(event.data));
    };

    // the user is trying to insert an image by clicking the Image from Storage button
    var onRequestInsertImage = function (event) {
        docEditor.insertImage({  // insert an image into the file
            "c": event.data.c
        })
    };

    // the user is trying to select document for comparing by clicking the Document from Storage button
    var onRequestSelectDocument = function (event) {
        var data = [];
        data.c   = event.data.c;
        docEditor.setRequestedDocument(data);  // select a document for comparing
    };

    // // the user is trying to select recipients data by clicking the Mail merge button
    // var onRequestSelectSpreadsheet = function (event) {
    //     // insert recipient data for mail merge into the file
    //     var data = {dataSpreadsheet};
    //     data.c   = event.data.c;
    //     docEditor.setRequestedSpreadsheet(data);
    // };

    var onRequestSaveAs = function (event) {  //  the user is trying to save file by clicking Save Copy as... button
        var title = event.data.title;
        var url   = event.data.url;
        var data  = {
            title: title,
            url  : url
        };
        let xhr   = new XMLHttpRequest();
        xhr.open("POST", "saveas");
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(JSON.stringify(data));
        xhr.onload = function () {
            innerAlert(xhr.responseText);
            innerAlert(JSON.parse(xhr.responseText.substring(xhr.responseText.indexOf('{'))).file, true);
        }
    };

    var onRequestRename = function (event) { //  the user is trying to rename file by clicking Rename... button
        innerAlert("onRequestRename: " + JSON.stringify(event.data));
        let newfilename = event.data;
        let data        = {
            newFileName: newfilename,
            documentId : config.document.key,
            ext        : config.document.fileType,
            _token     : "{{ csrf_token() }}"
        };

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "{{ route('document.rename', ['id' =>$item->id ]) }}");
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(JSON.stringify(data));
        xhr.onload = function () {
            innerAlert(xhr.responseText);
        }
    };

    function onRequestHistory() {
        const query = new URLSearchParams(window.location.search)
        const data  = {
            fileName: query.get('fileID'),
            _token  : "{{ csrf_token() }}"
        }
        const req   = new XMLHttpRequest()
        req.open("POST", "{{ route('document.historyObj', ['id' => $item->id ]) }}")
        req.setRequestHeader('Content-Type', 'application/json')
        req.send(JSON.stringify(data))
        req.onload = function () {
            if (req.status != 200) {
                response = JSON.parse(req.response)
                innerAlert(response.error)
                return
            }
            history = JSON.parse(req.response)
            docEditor.refreshHistory({
                currentVersion: history[0].currentVersion,
                history       : history[0].history
            })
        }
    }

    function onRequestHistoryData(event) {
        var ver      = event.data;
        var histData = history[1]
        docEditor.setHistoryData(histData[ver - 1])
    }

    function onRequestHistoryClose() {
        document.location.reload()
    }

    function onRequestRestore(event) {
        const query   = new URLSearchParams(window.location.search)
        const config  = {config}
        const payload = {
            fileName: query.get('fileID'),
            version : event.data.version,
            userId  : query.get('user') || config.editorConfig.user.id
        }
        const request = new XMLHttpRequest()
        request.open("PUT", 'restore')
        request.send(JSON.stringify(payload))
        request.onload = function () {
            if (request.status != 200) {
                response = JSON.parse(request.response)
                innerAlert(response.error)
                return
            }
            onRequestHistory()
        }
    }

    // add mentions for not anonymous users
    var onRequestUsers = function (event) {
        if (event && event.data) {
            var c = event.data.c;
        }

        switch (c) {
            case "info":
                users        = [];
                var allUsers = {usersInfo};
                for (var i = 0; i < event.data.id.length; i++) {
                    for (var j = 0; j < allUsers.length; j++) {
                        if (allUsers[j].id == event.data.id[i]) {
                            users.push(allUsers[j]);
                            break;
                        }
                    }
                }
                break;
            case "protect":
                var users = null;
                break;
            default:
                users = [];
        }

        docEditor.setUsers({
            "c"    : c,
            "users": users,
        });
    };

    // var onRequestClose = function (event) {
    //     if (window.opener) {
    //         window.close()
    //         return
    //     }
    //     docEditor.destroyEditor()
    // };

    var onRequestSendNotify = function (event) {
        event.data.actionLink = replaceActionLink(location.href, JSON.stringify(event.data.actionLink));
        var data              = JSON.stringify(event.data);
        innerAlert("onRequestSendNotify: " + data);
    };

    var сonnectEditor = function () {
        // {fileNotFoundAlert}

        config        = @json($item->config);
        config.width  = "100%";
        config.height = "100%";

        config.events = {
            'onAppReady'             : onAppReady,
            'onDocumentStateChange'  : onDocumentStateChange,
            'onError'                : onError,
            'onOutdatedVersion'      : onOutdatedVersion,
            'onMakeActionLink'       : onMakeActionLink,
            'onMetaChange'           : onMetaChange,
            'onRequestInsertImage'   : onRequestInsertImage,
            'onRequestSelectDocument': onRequestSelectDocument,
            // 'onRequestSelectSpreadsheet': onRequestSelectSpreadsheet,
            'onRequestReferenceData': onRequestReferenceData,
            "onRequestOpen"         : onRequestOpen,

            "onRequestRename"    : onRequestRename,
            "onRequestSendNotify": onRequestSendNotify,
            "onRequestRestore"   : onRequestRestore,
            // "onRequestSaveAs"     : onRequestSaveAs,

            "onRequestHistory"     : onRequestHistory,
            "onRequestHistoryClose": onRequestHistoryClose,
            "onRequestHistoryData" : onRequestHistoryData,
        };

        // {history}

        docEditor = new DocsAPI.DocEditor("iframeEditor", config);
    };

    if (window.addEventListener) {
        window.addEventListener("load", сonnectEditor);
    } else if (window.attachEvent) {
        window.attachEvent("load", сonnectEditor);
    }

</script>
</body>
</html>
