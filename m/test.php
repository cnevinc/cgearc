<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN">
<html>
    <head>
        <meta content="text/html; charset=Big5" http-equiv="content-type">
        <style type="text/css">
            div {
                color: #ffffff;
                background-color: #ff0000;
                border-width: 1px;
                border-color: black;
                border-style: solid;
                position: absolute;
            }        
        </style>
       
        <script type="text/javascript">
            window.onload = function() {
                function offset(element) {
                    var x = 0;
                    var y = 0;
                    while(element) {
                        x += element.offsetLeft;
                        y += element.offsetTop;
                        element = element.offsetParent;
                    }
                    return { 
                        x: x, 
                        y: y, 
                        toString: function() {
                            return '(' + this.x + ', ' + this.y + ')';
                        }
                    };
                }
                var xhr = window.XMLHttpRequest && 
                      (window.location.protocol !== 'file:' 
                          || !window.ActiveXObject) ?
                       function() {
                           return new XMLHttpRequest();
                       } :
                       function() {
                          try {
                             return new ActiveXObject('Microsoft.XMLHTTP');
                          } catch(e) {
                             throw new Error('XMLHttpRequest not supported');
                          }
                       };
                
                function param(obj) {
                    var pairs = [];
                    for(var name in obj) {
                        var pair = encodeURIComponent(name) + '=' + 
                                   encodeURIComponent(obj[name]);
                        pairs.push(pair.replace('/%20/g', '+'));
                    }
                    return pairs.join('&');
                }
                
                function ajax(option) {
                    option.type = option.type || 'GET';
                    option.header = option.header || {
                      'Content-Type':'application/x-www-form-urlencoded'};
                    option.callback = option.callback || function() {};
                    
                    if(!option.url) {
                        return;
                    }
                    
                    var request = xhr();
                    request.onreadystatechange = function() {
                        option.callback.call(request, request);
                    };
                    
                    var body = null;
                    var url = option.url;
                    if(option.data) {
                        if(option.type === 'POST') {
                            body = param(option.data);
                        }
                        else {
                            url = option.url + '?' + param(option.data) 
                                     + '&time=' + new Date().getTime();
                        }
                    }
                    
                    request.open(option.type, url);
                    for(var name in option.header) {
                        request.setRequestHeader(
                                name, option.header[name]);
                    }
                    request.send(body);
                }

                var search = document.getElementById('search');
                search.onkeyup = function() {
                    // 移除選項的<div>容器
                    var divs = document.getElementsByTagName('div');
                    for(var i = 0; i < divs.length; i++) {
                        document.body.removeChild(divs[i]);
                    }
                    // 沒有輸入值，直接結束
                    if(search.value === '') {
                        return;
                    }
                    // 發出非同步請求，取得可能的選項，以JSON的字串陣列格式傳回
                    ajax({
                        url     : 'test.php',
                        data    : {keyword : search.value},
                        callback: function(request) {
                            if(request.readyState === 4) {
                                if(request.status === 200) {
                                    // 剖析JSON
                                    var keywords = JSON.parse(
                                            request.responseText);
                                    // 字串陣列長度不為0時加以處理
                                    if(keywords.length !== 0) {
                                        var innerHTML = '';
                                        for(var i = 0; 
                                              i < keywords.length; i++) {
                                            innerHTML += 
                                                (keywords[i] + '<br>');
                                        }
                                        // 建立容納選項的<div>
                                        var div = 
                                              document.createElement('div');
                                        div.innerHTML = innerHTML;
                                        // 設定<div>的位置
                                        var xy = offset(search);
                                        div.style.left = xy.x + 'px';
                                        div.style.top = 
                                             xy.y + search.offsetHeight + 'px';
                                        div.style.width = 
                                             search.offsetWidth + 'px';
                                        // 附加至DOM樹
                                        document.body.appendChild(div);
                                    }
                                }
                            }
                        }
                    });
                };
            };
        </script>        
    </head>
    <body>
        <hr>
        搜尋：<input id="search" type="text">
    </body>
</html>