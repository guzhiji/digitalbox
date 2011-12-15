/*
 * HttpRequest
 * 
 * @author GuZhiji <gu_zhiji@163.com>
 * @copyright 2011 DigitalBox Ver 2.6, GuZhijiStudio
 */

/**
 *
 * @version 0.2.20110301
 */
/**
 * @constructor
 * @returns {HttpRequest}
 */
digitalbox.HttpRequest = function() {
    if (window.XMLHttpRequest) {
        this._request = new XMLHttpRequest();
    // return true;
    } else if (window.ActiveXObject) {
        var objlist = [ "MSXML2.XMLHttp.4.0", "MSXML2.XMLHttp.3.0",
        "MSXML2.XMLHttp", "Microsoft.XMLHttp" ];
        for ( var i = 0; i < objlist.length; i++) {
            try {
                this._request = new ActiveXObject(objlist[i]);
            // return true;
            } catch (e) {
            }
        }
    }
// return false;
};
digitalbox.HttpRequest.prototype = {
    _request : null,
    _params : "",
    supported : function() {
        return (this._request != null);
    },
    sendGET : function(action, handler, async, username, password) {
        if (this.supported()) {
            url = (this._params == "") ? action : action + "?" + this._params;
            this._request.open("GET", url, async, username, password);
            this._request.onreadystatechange = handler;
            this._request.setRequestHeader("Content-Type", "");
            this._request.send(null);
        }
    },
    sendPOST : function(action, handler, async, username, password) {
        if (this.supported()) {
            this._request.open("POST", action, async, username, password);
            this._request.onreadystatechange = handler;
            this._request.setRequestHeader("Content-Type",
                "application/x-www-form-urlencoded");
            this._request.send(this._params);
        }
    },
    setParameter : function(key, value) {
        if (this._params != "")
            this._params += "&";
        this._params += key + "=" + value;
    },
    clearParameters : function() {
        this._params = "";
    },
    getReadyState : function() {
        return this._request.readyState;
    },
    getStatus : function() {
        return this._request.status;
    },
    getStatusText : function() {
        return this._request.statusText;
    },
    getText : function() {
        if (this._request.readyState == 4) {
            return this._request.responseText;
        }
        return "";
    },
    getDOM : function() {
        if (this._request.readyState == 4) {
            return new digitalbox.dom.XMLNode(this._request.responseXML);
        //return new digitalbox.dom.XMLDom(this._request.responseXML);
        // not
        // XMLNode()
        }
        return null;
    }
};
// test Request
/*
 * var r = new HttpRequest(); r.setParameter("id", "1");
 * r.sendGET("http://localhost/digitalboxv2/article.php", handler); function
 * handler() { document.write(r.getText()); }
 */