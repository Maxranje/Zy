
$(function(){
    $('.zy-side-tree a').bind("click",function(){
        var title = $(this).text();
        var href = $(this).attr('data-link');
        var iconCls = $(this).attr('data-icon');
        var iframe = $(this).attr('iframe')==1?true:false;
        addTab(title,href,iconCls,iframe);
    });
});

/**
* Name 添加菜单选项
* Param title 名称
* Param href 链接
* Param iconCls 图标样式
* Param iframe 链接跳转方式（true为iframe，false为href）
*/
function addTab(title, href, iconCls, iframe){
    var tabPanel = $('#zy-tabs');
    if(!tabPanel.tabs('exists',title)){
        var content = "";
        $.ajax({ url:href, type:"GET", dataType:"html", success:function(content){
            tabPanel.tabs('add',{title:title,content:content,iconCls:iconCls,fit:true,cls:'pd3',closable:true});            
        }});
    }else{
        tabPanel.tabs('select',title);
    }
}



/**
* Name 移除菜单选项
*/
function removeTab(){
    var tabPanel = $('#zy-tabs');
    var tab = tabPanel.tabs('getSelected');
    if (tab){
        var index = tabPanel.tabs('getTabIndex', tab);
        tabPanel.tabs('close', index);
    }
}

/**
 * 添加信息事件
 */
function openAdd (){
    $('#zy-form').form('reset');
    $('#zy-dialog').dialog({
        closed: false,
        modal:true,
        title: "添加信息",
        buttons: [{
            text: '确定',
            iconCls: 'icon-ok',
            handler: baseAdd
        }, {
            text: '取消',
            iconCls: 'icon-cancel',
            handler: function () {
                $('#zy-dialog').dialog('close');
            }
        }]
    });
}

/**
 * 修改信息事件
 */
function openEdit(){
    $('#zy-form').form('reset');
    if (makeEditInfo() == false) {
        return ;
    }
    $('#zy-dialog').dialog({
        closed: false,
        modal:true,
        title: "修改信息",
        buttons: [{
            text: '确定',
            iconCls: 'icon-ok',
            handler: baseEdit
        }, {
            text: '取消',
            iconCls: 'icon-cancel',
            handler: function () {
                $('#zy-dialog').dialog('close');
            }
        }]
    });
}

function pagerFilter(data){
    if (data) {
        if (data.errNo > 0 ) {
            $.messager.alert('信息提示', '请求失败,'+data.errstr, 'error');
        }else {
            return data.data
        }
    }else {
        $.messager.alert('信息提示', '请求失败, 未知错误', 'error');    
    }
    return {"rows":[], "total":0};
}


function baseSearch () {
    $('#zy-datagrid').datagrid('load', $("#zy-search-form").serializeObject());   
}


//    表单序列化插件
(function($){
　　$.fn.serializeObject=function(){
　　　　var o={};
　　　　var a=this.serializeArray();
　　　　$.each(a, function() {
　　　　　　if(o[this.name]){
　　　　　　　　if(!o[this.name].push){
　　　　　　　　　　o[this.name]=[o[this.name]];
　　　　　　　　}
　　　　　　　　o[this.name].push(this.value||'');
　　　　　　}else{
　　　　　　　　o[this.name]=this.value||'';
　　　　　　}
　　　　});
　　return o;
　　};
})(jQuery);
