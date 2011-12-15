/*
 * the Main JavaScript Library for DigitalBoxCMS3
 * 
 * @author GuZhiji <gu_zhiji@163.com>
 * @copyright (c)2011 DigitalBoxCMS3, GuZhijiStudio
 */
/**
 * namespace for DigitalBox JavaScript library with version and copyright
 * information
 */
var digitalbox = {
    version : {
        major : 3,//DigitalBoxCMS's version
        minor : 0.2,//highest version for all parts
        update : 20110227//when the library last updated
    },
    author : "GuZhiji <gu_zhiji@163.com>",
    copyright : "(c)2011 DigitalBoxCMS3, GuZhijiStudio"
};
/*
var interbox = {
    js : {
        net : {},
        ui : {},
        storage : {},
        security : {}
    }
};
*/
/**
 * namespace for recognizing concurrent main-stream browsers
 */
digitalbox.browser = {
    // isie = window.ActiveXObject != null;
    isIE : navigator.userAgent.indexOf("MSIE") > -1,
    isFireFox : navigator.userAgent.indexOf("FireFox") > -1,
    isOpera : document.opera != null,
    isChrome : document.chrome != null
};
/**
 * extend the subject prototype with new members capsuled in an object
 * 
 * @version 0.2
 * @param subject
 * @param newmembers
 * @returns the subject prototype
 */
digitalbox.extend = function(subject, newmembers) {
    for ( var item in newmembers) {
        var n = newmembers[item];
        if(item in subject){
            var newname="_"+item;
            while(newname in subject)
                newname="_"+newname;
            subject[newname]=subject[item];
        }
        subject[item] = n;
    }
    return subject;
};
/**
 * load a module of this JavaScript library
 * 
 * @version 0.1
 * @param module
 */
digitalbox.loadModule = function(module) {
    var s = document.createElement("script");
    s.type = "text/javascript";
    s.src = module + ".js";
    document.getElementsByTagName("head")[0].appendChild(s);
// TODO: version 0.2
// var r=new digitalbox.HttpRequest();
// r.sendGET(module,null,true);
};

/**
 * List
 * 
 * @constructor
 * @version 0.1
 * @returns {List}
 */
digitalbox.List = function() {
    };
digitalbox.List.prototype = {
    /**
	 * internal storage for all items in the list
	 */
    list : [],
    /**
	 * counter of the number of items
	 */
    count : 0,
    /**
	 * add a new item into the list
	 * 
	 * @param item
	 * @returns
	 */
    add : function(item) {
        this.list[this.count] = item;
        this.count++;
    },
    isEmpty : function() {
        return this.list.length == 0;
    },
    clear : function() {
        this.list = [];
        this.count = 0;
    },
    concat : function(list) {
        for ( var i = 0; i < list.count; i++) {
            var item = list.list[i];
            this.list[this.count] = item;
            this.count++;
        }
    }
};
/**
 * Errors
 * 
 * @version 0.2
 * @returns {Errors}
 */
digitalbox.Errors = function() {
    };
digitalbox.Errors.prototype = new digitalbox.List();
digitalbox.Errors.prototype._add = digitalbox.Errors.prototype.add;
digitalbox.extend(digitalbox.Errors.prototype, {
    add : function(source, text) {
        // this._add(new Array(source, text));
        this._add( {
            "source" : source,
            "text" : text
        });
    },
    loadFromXML : function(xmldom) {
        /**
		 * read error info from xml
		 */
        var root = xmldom.getFirstChild();
        if (root != null && root.getName() == "errors") {
            var e = root.getFirstChild();
            var source, text;
            if (this.count == null)
                this.count = 0;
            while (e) {
                if (e.getName() == "error") {
                    var ee = e.getFirstChild();
                    source = text = "";
                    while (ee) {
                        if (ee.getName() == "source")
                            source = ee.getText();
                        else if (ee.getName() == "text")
                            text = ee.getText();
                        ee = ee.getNext();
                    }
                    e = e.getNext();
                    this.list[this.count] = new Array(source, text);
                    this.count++;
                }
            }
        }
    }
});

/**
 * namespace for user interface
 */
digitalbox.ui = {};
/**
 * 
 * @version 0.1
 * @returns {StaticUI}
 */
digitalbox.ui.StaticUI = function() {
    };
digitalbox.ui.StaticUI.prototype = {
    _id : null,
    show : function(container, appending) {
        if (container) {
            if (appending)
                container.innerHTML += this.getHTML();
            else
                container.innerHTML = this.getHTML();
        }
    },
    getElement : function(part) {
        if (this._id) {
            if (part == null) {
                // alert(this._id);
                return document.getElementById(this._id);
            }

            else
                return document.getElementById(this._id + "_" + part);
        }
        return null;
    }
};

// StaticUI.prototype.getHTML(){}

/**
 * @version 0.1
 * @returns {DynamicUI}
 */
digitalbox.ui.DynamicUI = function() {
    };
digitalbox.ui.DynamicUI.prototype = {
    _id : null,
    _obj : null,
    _parent : null,
    initialize : function(id, parent, html) {
        if (!parent)
            parent = document.body;
        if (parent.innerHTML != null) {
            this._obj = document.getElementById(id);
            if (!this._obj) {
                parent.innerHTML += html;
                this._obj = document.getElementById(id);
            }
            if (this._obj) {
                this._id = id;
                this._parent = parent;
                return true;
            }
        }
        return false;
    },
    isInitialized : function() {
        return (this._obj != null);
    }
};

/**
 * construct a button object
 * 
 * @version 0.2
 * @constructor
 * @param id
 * @param text
 * @returns {ButtonUI}
 */
digitalbox.ui.ButtonUI = function(id, text) {
    if (id) {
        if (text != null) {
            this._id = id;
            this._text = text;
        }
    }
};
digitalbox.ui.ButtonUI.prototype = new digitalbox.ui.StaticUI();
digitalbox.extend(digitalbox.ui.ButtonUI.prototype, {
    setTip : function(tip) {
        this._tip = tip;
    },
    setStyle : function(style) {
        this._style = style;
    },
    setActionOnClick : function(action) {
        this._onclick = action;
    },
    getHTML : function() {
        var html = "<div><input type=\"button\" id=\"" + this._id + "\" ";
        if (this._style != null) {
            html += "class=\"" + this._style + "\" ";
        }
        if (this._onclick != null)
            html += "onClick=\"" + this._onclick + "\" ";
        if (this._tip != null)
            html += "title=\"" + this._tip + "\" ";
        html += "value=\"" + this._text + "\" /></div>";
        return html;
    }
});

/**
 * construct a progress bar object
 * 
 * @version 0.2
 * @constructor
 * @param id
 * @param text
 * @param max
 * @param current
 * @param container
 * @returns {ProgressUI}
 */
digitalbox.ui.ProgressUI = function(id, text, max, current, container) {
    this._id = id;
    this._max = max;

    var html = "<div class=\"" + digitalbox.ui.ProgressUI.cssclasses.text
    + "\" id=\"" + this._id + "_text\">" + text
    + "</div><div><table cellspacing=0 border=0 width="
    + digitalbox.ui.ProgressUI.width + "><tr><td class=\""
    + digitalbox.ui.ProgressUI.cssclasses.container + "\" id=\""
    + this._id + "_bar\"></td>";
    html += "<td class=\"" + digitalbox.ui.ProgressUI.cssclasses.container
    + "\" id=\"" + this._id + "_container\"></td></tr></table></div>";
    this.initialize(id, container, html);
    if (current)
        this.update(current);
};
digitalbox.ui.ProgressUI.width = 550;
digitalbox.ui.ProgressUI.cssclasses = {
    text : "progressui_text",
    container : "progressui_container",
    bar : "progressui_bar"
};
digitalbox.ui.ProgressUI.prototype = new digitalbox.ui.DynamicUI();
digitalbox.extend(digitalbox.ui.ProgressUI.prototype, {
    setText : function(t) {
        var tc = this.getElement("text");
        if (tc)
            tc.innerText = t;
    },
    update : function(value, mode) {
        if (this._current == null)
            this._current = 0;
        if (value > 0) {
            if (mode == "+")
                this._current += value;
            else if (mode == "-")
                this._current -= value;
            else
                this._current = value;

            if (this._current > this._max)
                this._current = this._max;
            else if (this._current < 0)
                this._current = 0;

        } else
            this._current = 0;
        var v;
        if (this._max > 0)
            v = this._current / this._max;
        else
            v = 0;
        var c = document.getElementById(this._id + "_container");
        var b = document.getElementById(this._id + "_bar");
        if (v == 1) {
            c.className = digitalbox.ui.ProgressUI.cssclasses.bar;
            b.className = digitalbox.ui.ProgressUI.cssclasses.bar;
        } else if (v > 0) {
            b.width = v * digitalbox.ui.ProgressUI.width;
            b.className = digitalbox.ui.ProgressUI.cssclasses.bar;
            c.className = digitalbox.ui.ProgressUI.cssclasses.container;
        } else {
            c.className = digitalbox.ui.ProgressUI.cssclasses.container;
            b.className = digitalbox.ui.ProgressUI.cssclasses.container;
        }
    }
});
//----------------------------------------------------------------------------------------------------------
function DaysOfMonth(themonth,theyear){
    if (themonth==2){
        if (theyear % 100==0){
            if (theyear % 400==0) 
                return 29;
            else 
                return 28;
        }else if (theyear % 4==0)
            return 29;
        else 
            return 28;
    }else if (themonth<=7){
        if (themonth % 2==0 && themonth!=2) return 30;
        return 31;
    }else if (themonth % 2==0) return 31;
    return 30;
}
function FirstDayOfYear(theyear){
    if (theyear % 4==0) 
        return (theyear/4*(365*4+1)-366)% 7+1;
    else 
        return ((theyear-theyear % 4)/4*(365*4+1)+365*(theyear % 4-1))% 7+1;
}
function FirstDayOfMonth(themonth,theyear){
    var a;
    a=FirstDayOfYear(theyear)-1;
    for (i=1;i<=(themonth-1);i++) a+=DaysOfMonth(i,theyear);
    return (a % 7)+1;
}
var CalendarTable;
var WeekNameArray = new Array("日","一","二","三","四","五","六");
var nd,dot,m,y,dom,fdom;
var a,b,c=0,d=0;
nd = new Date();
dot = nd.getDate();
m = nd.getMonth()+1;
y = nd.getFullYear();
dom = DaysOfMonth(m,y);
fdom = FirstDayOfMonth(m,y);
CalendarTable="<table width=\"100%\" height=\"100%\" cellspacing=\"2\"><tr><td colspan=\"7\" align=\"center\" class=\"calendar_text4\">"+y+"年"+m+"月"+dot+"日</td></tr><tr>";
for (a=0;a<=6;a++){
    if (a==0||a==6) CalendarTable+="<td align=\"center\" class=\"calendar_text2\">"+WeekNameArray[a]+"</td>"; 
    else CalendarTable+="<td align=\"center\" class=\"calendar_text1\">"+WeekNameArray[a]+"</td>";
}
CalendarTable+="</tr>";
for (a=1;a<=6;a++){
    CalendarTable+="<tr>";
    for (b=0;b<=6;b++){
        c++;
        if (c>=fdom){
            d++;
            if (d<=dom){
                if ((b==0||b==6)&&d!=dot) CalendarTable+="<td align=\"center\" class=\"calendar_text2\">";
                else if (d==dot) CalendarTable+="<td align=\"center\" class=\"calendar_text3\">";
                else CalendarTable+="<td align=\"center\" class=\"calendar_text1\">";
                CalendarTable+=d+"</td>";
            }else{
                CalendarTable+="<td></td>";
            }
        }else{
            CalendarTable+="<td></td>";
        }
    }
    CalendarTable+="</tr>";
}
CalendarTable+="</table>";
document.write(CalendarTable);


