function DaysOfMonth(themonth,theyear){
    if (themonth==2){
        if (theyear % 100==0)if (theyear % 400==0) return 29;
            else return 28;
        else if (theyear % 4==0) return 29;
        else return 28;
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
    for (i=1;i<=(themonth-1);i++)
        a+=DaysOfMonth(i,theyear);
    return (a % 7)+1;
}

var CalendarTable;
var WeekNameArray = new Array("\u65e5","\u4e00","\u4e8c","\u4e09","\u56db","\u4e94","\u516d");
var nd,dot,m,y,dom,fdom;
var a,b,c=0,d=0;
nd = new Date();
dot = nd.getDate();
m = nd.getMonth()+1;
y = nd.getFullYear();
dom = DaysOfMonth(m,y);
fdom = FirstDayOfMonth(m,y);

CalendarTable="<table width=100% height=100% cellspacing=2><tr><td colspan=7 align=center class=calendar_text4>"+y+"\u5e74"+m+"\u6708"+dot+"\u65e5</td></tr><tr>";
for (a=0;a<=6;a++){
    if (a==0||a==6) CalendarTable+="<td align=center class=calendar_text2>"+WeekNameArray[a]+"</td>"; else CalendarTable+="<td align=center class=calendar_text1>"+WeekNameArray[a]+"</td>";
}
CalendarTable+="</tr>";

for (a=1;a<=6;a++){
    CalendarTable+="<tr>";
    for (b=0;b<=6;b++){
        c++;
        if (c>=fdom){
            d++;
            if (d<=dom){
                if ((b==0||b==6)&&d!=dot)
                    CalendarTable+="<td align=center class=calendar_text2>";
                else if (d==dot)
                    CalendarTable+="<td align=center class=calendar_text3>";
                else
                    CalendarTable+="<td align=center class=calendar_text1>";
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

