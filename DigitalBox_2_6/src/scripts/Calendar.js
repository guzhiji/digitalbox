/*
 * calendar
 * 
 * @version 0.2
 * @author GuZhiji <gu_zhiji@163.com>
 * @copyright 2011 DigitalBoxCMS3, GuZhijiStudio
 */
/**
 * construct a calendar object
 * 
 * @version 0.2
 * @constructor
 * @param id
 * @returns {CalendarUI}
 */
digitalbox.ui.CalendarUI = function(id) {
    this._id = id;// TODO:getElement() using _id
}
digitalbox.ui.CalendarUI.getDaysOfMonth = function(themonth, theyear) {
    if (themonth == 2) {
        if (theyear % 100 == 0)
            if (theyear % 400 == 0)
                return 29;
            else
                return 28;
        else if (theyear % 4 == 0)
            return 29;
        else
            return 28;
    } else if (themonth <= 7) {
        if (themonth % 2 == 0 && themonth != 2)
            return 30;
        return 31;
    } else {
        if (themonth % 2 == 0)
            return 31;
        return 30;
    }
};
digitalbox.ui.CalendarUI.getFirstDayOfYear = function(theyear) {
    if (theyear % 4 == 0)
        return (theyear / 4 * (365 * 4 + 1) - 366) % 7 + 1;
    else
        return ((theyear - theyear % 4) / 4 * (365 * 4 + 1) + 365 * (theyear % 4 - 1)) % 7 + 1;
};
digitalbox.ui.CalendarUI.getFirstDayOfMonth = function(themonth, theyear) {
    var a = digitalbox.ui.CalendarUI.getFirstDayOfYear(theyear) - 1;
    for ( var i = 1; i <= (themonth - 1); i++)
        a += digitalbox.ui.CalendarUI.getDaysOfMonth(i, theyear);
    return (a % 7) + 1;
};
digitalbox.ui.CalendarUI._getHTML = function(y, m, dot) {
    if (dot == null) {
        var nd = new Date();
        dot = nd.getDate();
        m = nd.getMonth() + 1;
        y = nd.getFullYear();
    }
    var WeekNameArray = new Array("\u65e5", "\u4e00", "\u4e8c", "\u4e09", "\u56db", "\u4e94", "\u516d");
    var dom = digitalbox.ui.CalendarUI.getDaysOfMonth(m, y);
    var fdom = digitalbox.ui.CalendarUI.getFirstDayOfMonth(m, y);
    var CalendarTable = "<table width=\"100%\" height=\"100%\" cellspacing=2 id=\""
    + this._id + "\">";
    CalendarTable += "<tr><td colspan=7 align=\"center\" class=\"calendar_text4\">"
    + y + "\u5e74" + m + "\u6708" + dot + "\u65e5</td></tr><tr>";
    var a;
    for ( a = 0; a <= 6; a++) {
        if (a == 0 || a == 6)
            CalendarTable += "<td align=\"center\" class=\"calendar_text2\">"
            + WeekNameArray[a] + "</td>";
        else
            CalendarTable += "<td align=\"center\" class=\"calendar_text1\">"
            + WeekNameArray[a] + "</td>";
    }
    CalendarTable += "</tr>";
    var c = 0;
    var d = 0;
    for ( a = 1; a <= 6; a++) {
        CalendarTable += "<tr>";
        for ( var b = 0; b <= 6; b++) {
            c++;
            if (c >= fdom) {
                d++;
                if (d <= dom) {
                    if ((b == 0 || b == 6) && d != dot)
                        CalendarTable += "<td align=\"center\" class=\"calendar_text2\">";
                    else if (d == dot)
                        CalendarTable += "<td align=\"center\" class=\"calendar_text3\">";
                    else
                        CalendarTable += "<td align=\"center\" class=\"calendar_text1\">";
                    CalendarTable += d + "</td>";
                } else {
                    CalendarTable += "<td></td>";
                }
            } else {
                CalendarTable += "<td></td>";
            }
        }
        CalendarTable += "</tr>";
    }
    CalendarTable += "</table>";
    return CalendarTable;
};
digitalbox.ui.CalendarUI.prototype = new digitalbox.ui.StaticUI();
digitalbox.extend(digitalbox.ui.CalendarUI.prototype, {
    getHTML : function(y, m, dot) {
        return digitalbox.ui.CalendarUI._getHTML(y, m, dot);
    },
    show : function(container, appending, y, m, dot) {
        if (container) {
            if (appending)
                container.innerHTML += this.getHTML(y, m, dot);
            else
                container.innerHTML = this.getHTML(y, m, dot);
        }
    }
});