(function(t){function e(e){for(var i,r,o=e[0],c=e[1],u=e[2],d=0,f=[];d<o.length;d++)r=o[d],Object.prototype.hasOwnProperty.call(s,r)&&s[r]&&f.push(s[r][0]),s[r]=0;for(i in c)Object.prototype.hasOwnProperty.call(c,i)&&(t[i]=c[i]);l&&l(e);while(f.length)f.shift()();return n.push.apply(n,u||[]),a()}function a(){for(var t,e=0;e<n.length;e++){for(var a=n[e],i=!0,o=1;o<a.length;o++){var c=a[o];0!==s[c]&&(i=!1)}i&&(n.splice(e--,1),t=r(r.s=a[0]))}return t}var i={},s={detail:0},n=[];function r(e){if(i[e])return i[e].exports;var a=i[e]={i:e,l:!1,exports:{}};return t[e].call(a.exports,a,a.exports,r),a.l=!0,a.exports}r.m=t,r.c=i,r.d=function(t,e,a){r.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:a})},r.r=function(t){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},r.t=function(t,e){if(1&e&&(t=r(t)),8&e)return t;if(4&e&&"object"===typeof t&&t&&t.__esModule)return t;var a=Object.create(null);if(r.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var i in t)r.d(a,i,function(e){return t[e]}.bind(null,i));return a},r.n=function(t){var e=t&&t.__esModule?function(){return t["default"]}:function(){return t};return r.d(e,"a",e),e},r.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},r.p="/napi/public/";var o=window["webpackJsonp"]=window["webpackJsonp"]||[],c=o.push.bind(o);o.push=e,o=o.slice();for(var u=0;u<o.length;u++)e(o[u]);var l=c;n.push([4,"chunk-vendors","chunk-common"]),a()})({"044f":function(t,e,a){"use strict";a.r(e);a("cadf"),a("551c"),a("f751"),a("097d");var i=a("2b0e"),s=(a("b132"),function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticStyle:{"padding-bottom":"100px",background:"#ffffff"}},[i("HeaderTop",{attrs:{userinfo:t.basedata.userinfo,current:2}}),i("div",{staticClass:"secNav"},[i("ul",{staticClass:"secNavCont"},t._l(t.basedata.coursetype,(function(e,a){return i("li",{key:a,class:e.active?"on":""},[t._v(t._s(e.name))])})),0)]),i("div",{staticClass:"campus-content"},[i("div",{staticClass:"campusCont"},[i("div",{staticClass:"top"},[i("p",[t._v(t._s(t.basedata.details.coursename))]),i("span",[t._v("班号 #s777777")])]),i("div",{staticClass:"content"},[i("h6",[t._v("简介")]),i("div",{domProps:{innerHTML:t._s(t.basedata.details.coursedetails)}})]),i("TeacherCont",{attrs:{teadata:t.basedata.details.teachers}})],1)]),i("div",{staticClass:"fix_bar"},[i("div",[i("dl",[i("dd",[i("p",{staticClass:"buyBtn",on:{click:t.buycourse}},[t._v("\n            立即报名\n            "),i("img",{attrs:{src:a("6dfa"),alt:""}})])]),i("dt",[t._v(t._s(t.basedata.details.coursename))])])])]),i("div",{directives:[{name:"show",rawName:"v-show",value:t.isShowDialog,expression:"isShowDialog"}],staticClass:"shadow",on:{click:function(e){return e.target!==e.currentTarget?null:t.hideShadow(e)}}},[i("div",{staticClass:"payment"},[i("div",{staticClass:"pay-weixin"},[i("div",{staticClass:"p-w-hd"},[t._v("微信支付")]),i("div",{staticClass:"p-w-bd",staticStyle:{position:"relative"}},[i("div",{staticClass:"j_weixinInfo",staticStyle:{position:"absolute",top:"-36px",left:"130px"}},[t._v("距离二维码过期还剩"),i("span",{staticClass:"j_qrCodeCountdown font-bold font-red"},[t._v(t._s(t.timenum))]),t._v("秒，过期后请刷新页面重新获取二维码。")]),t._m(0),i("div",{staticClass:"p-w-sidebar"})])])])]),i("Footer"),i("div",{ref:"tipCont",staticClass:"common-tip-shadow",staticStyle:{display:"none"}})],1)}),n=[function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"p-w-box"},[a("div",{staticClass:"pw-box-hd"},[a("img",{attrs:{id:"weixinImageUrl",src:"//misc.360buyimg.com/lib/img/e/blank.gif",width:"298",height:"298"}})]),a("div",{staticClass:"pw-retry j_weixiRetry",staticStyle:{display:"none"}},[a("a",{staticClass:"ui-button ui-button-gray j_weixiRetryButton",attrs:{href:"javascript:getWeixinImage2();"}},[t._v("获取失败 点击重新获取二维码  ")])]),a("div",{staticClass:"pw-error j_weixiError"}),a("div",{staticClass:"pw-box-ft"},[a("p",[t._v("请使用微信扫一扫")]),a("p",[t._v("扫描二维码支付")])])])}],r=a("3c63"),o=a("076e"),c=a("b775"),u=a("3de1"),l=a("d2cf");a("d090"),a("455b"),a("5720");window.location.href.indexOf("test-");var d={name:"campus",data:function(){return{basedata:{details:{}},courseid:Object(u["a"])("courseid"),qrcode:"",isShowDialog:!1,timer:"",timenum:60,token:"",qrurl:"",tracecode:"",timer1:""}},components:{HeaderTop:r["a"],TeacherCont:l["a"],Footer:o["a"]},computed:{clsName:function(){return{"save-can-click":!0}}},watch:{},mounted:function(){document.title="课程详情",console.log(this.basedata),this.init()},destroyed:function(){},methods:{hideShadow:function(){this.isShowDialog=!1},buycourse:function(){var t=this;this.$request({url:"buycourse",data:{courseid:this.courseid,paytype:"wx"}}).then((function(e){0==e.ec?(t.qrcode=e.data,qrurl,t.token=e.data.token,t.tracecode=e.data.tracecode,t.isShowDialog=!0,t.setTimer()):t.showMessage(e.em)}))},initteacher:function(){for(var t=[],e=0;e<this.basedata.details.teachers.length;e+=4)t.push(this.basedata.details.teachers.slice(e,e+4));this.basedata.details.teachers=t},setTimer:function(){var t=this;this.timer=setInterval((function(){t.timenum-=1}),1e3),this.timer1=setInterval((function(){t.checkstatus()}),3e3)},checkstatus:function(){var t=this;this.$request({url:"checkorder",token:this.token,tracecode:this.tracecode,qrurl:this.qrurl}).then((function(e){0==e.ec&&(t.showMessage(e.em),t.isShowDialog=!1,clearInterval(t.timer1))}))},showMessage:function(t){var e=this;this.$refs.tipCont.innerHTML=t,this.$refs.tipCont.style.display="block",setTimeout((function(){e.$refs.tipCont.style.display="none"}),2e3)},init:function(){var t=this;this.$request({url:"getCourseDetails",data:{courseid:this.courseid}}).then((function(e){0==e.ec&&(t.basedata=e.data,t.initteacher())}))}}},f=d,p=(a("ccda"),a("2877")),h=Object(p["a"])(f,s,n,!1,null,null,null),v=h.exports,b=(a("41d0"),a("f6bf"));i["a"].use({install:function(t){t.prototype.$request=c["a"]}}),i["a"].component("pagination",b["a"]),i["a"].config.productionTip=!1,new i["a"]({render:function(t){return t(v)}}).$mount("#app")},4:function(t,e,a){t.exports=a("044f")},"8b2f":function(t,e,a){},ccda:function(t,e,a){"use strict";var i=a("8b2f"),s=a.n(i);s.a}});