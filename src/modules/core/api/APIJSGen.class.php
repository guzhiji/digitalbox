<?php

class APIJSGen {
    
}
?>
<script>
    var interbox={
        getXHR:function(){
            //XMLHttpRequest
            if(window.XMLHttpRequest){
                return new XMLHttpRequest();
            }else{
                return new ActiveXObject("Microsoft.XMLHTTP");
            }
        },
        parseJSON:function(data){
            //JSON parser
            if (!window.JSON) {
                window.JSON = {
                    parse: function (sJSON) {
                        return eval("(" + sJSON + ")");
                    }
                };
            }
            return JSON.parse(data);
        },
        encodeParams:function(params){
            var pstr="";
            if(params!=null){
                for(var p in params){
                    if(pstr!="") pstr+="&";
                    pstr+=p.toString()+"="+encodeURIComponent(params[p]);
                }
            }
            return pstr;
        },
        send:function(xhr,method,url,async,params){
            var pstr=this.encodeParams(params);
            if(method.toLowerCase()=="get"){
                if(pstr!="") pstr="&"+pstr;//TODO http://.../xxx&a=a&b=b
                xhr.open(method,url+pstr,async);
                xhr.setRequestHeader("Content-type","");
                xhr.send();
            }else{
                xhr.open(method,url,async);
                xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                xhr.send(pstr);
            }
        },
        request:function(method,url,params,callback){
            var xhr=this.getXHR();
            if(xhr){
                xhr.callback=callback;
                xhr.onreadystatechange=function(){
                    if(this.readyState==4 && this.status==200){
                        var r=interbox.parseJSON(this.responseText);
                        if(this.callback)
                            this.callback(r);
                        this.callback=null;
                    }
                };
                this.send(xhr,method,url,true,params);
            }
        },
        fetch:function(method,url,params){
            var xhr=this.getXHR();
            if(xhr){
                this.send(xhr,method,url,false,params);
                if(xhr.readyState==4 && xhr.status==200){
                    return interbox.parseJSON(xhr.responseText);
                }
            }
            return null;
        },
        api:{
            func_var:'f',
            func_src:'get',
            param_src:'post',
            call:function(fname,fparams){
                interbox.fetch(this.func_src, url, fparams)
            },
            hello_world:function(){
                
            }
        }
    };
</script>