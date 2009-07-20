<?php
require('config.inc');
$dbh = get_connection();
$sth = $dbh->query('select cat_id, cat_name, cat_desc from cat');
$cats = $sth->fetchAll(PDO::FETCH_ASSOC);
?>
<html><head>

	
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link midia="screen" type="text/css" href="http://yubo.ued.taobao.net/assets/tbra/dev/reset-grids.css" rel="stylesheet">
		<link midia="screen" type="text/css" href="http://assets.taobaocdn.com/app/top/isv.css" rel="stylesheet">
		<style type="text/css">
          body, div, input, textarea, table { font-size: 12px; }
			a {color:#4D4D4D;text-decoration:none}
			a:hover {color:#EA4106;text-decoration:underline;}
			td {padding:6px}
			.point-red {width:20px;float:left;text-align:center;color:red;line-height:21px}
			.point-blue {width:20px;float:left;text-align:center;color:blue;line-height:21px}
			.explain:link {font-size: 12px;line-height:21px;color: #4D4D4D;text-decoration: none;}
			.explain:visited {font-size: 12px;line-height:21px;color: #4D4D4D;text-decoration: none;}
			.explain:hover {font-size: 12px;line-height:21px;color: #4D4D4D;text-decoration: none;}
		</style>
		<title>API测试工具</title>
</head><body>

		<div id="page">
  			<div style="background: rgb(249, 249, 249) none repeat scroll 0% 0%; width: 100%; float: left; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous;">
			<form id="frmApitest" name="frmApitest" method="post" action="">
			<input id="api_url" name="api_url" value="sandbox" type="hidden">
			<table style="margin: 0pt auto;" border="0" cellpadding="0" cellspacing="0" width="950">
				<tbody><tr>
                	<td colspan="2">
                		<span style="float: left; margin-top: 11px;"><a href="http://wiki.open.taobao.com/index.php/API%E6%96%87%E6%A1%A3#API.E6.B5.8B.E8.AF.95.E5.B7.A5.E5.85.B7.E4.BD.BF.E7.94.A8.E6.8C.87.E5.8D.97" target="_blank" style="color: blue;">API测试工具使用指南</a></span>

						<span style="float: left; margin-top: 11px; padding-left: 20px;"><a href="http://wiki.open.taobao.com/index.php/API%E6%96%87%E6%A1%A3" target="_blank" style="color: blue;">API文档</a> </span>
						<span style="float: left; margin-top: 11px; padding-left: 20px;"><a href="http://open.taobao.com/api_tool/props" target="_blank" style="color: blue;">API属性工具</a></span>
  						<span style="float: left; margin-top: 11px; padding-left: 20px;">
<?php if ( isset($_SESSION['user']) ) : ?>
                           <span id="session_user"><?php echo $_SESSION['user'] ?><a onclick="return unbindSessionUser();" style="color: blue;">退出会话</a></span>
<?php else : ?>
                           <span id="session_user"><a onclick="return bindSessionUser();" style="color: blue;">绑定会话</a></span>
<?php endif; ?>
                        </span>
                		<span style="width: 122px; float: right; margin-top: 11px; text-align: left; padding-left: 5px;"><a href="taobaoPubAccount.html" target="_blank" style="text-decoration: underline;"> 查看测试环境公用账号</a></span>
                		<span style="float: right; margin-top: 10px;"><img src="http://isv.alisoft.com/isv/images/dev/taobaoapi.gif"></span>
                	</td>
                </tr>

  				<tr>
    				<td width="40%" valign="top">
                    	<table border="0" cellpadding="0" cellspacing="0">
                        	<tbody><tr>
                            	<td>
                                    <table border="0" cellpadding="4" cellspacing="0" width="500">
                                        <tbody><tr>
                                            <td align="right" width="160">返回结果：</td>

                                            <td width="340">
                                                <select id="format" name="format" style="width: 195px;">
                                                    <option value="php">PHP</option>
                                                    <option value="xml">XML</option>
                                                    <option value="json">json</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>

                                            <td align="right">API类目：</td>
                                            <td>
                                            	<select name="apiCategoryId" id="apiCategoryId" style="width: 195px;" onchange="callgetApiListByCategory(this);">
                                                    <option value="">--请选择API类目--</option>
                                                    <?php foreach ( $cats as $cat ) : ?>
                                                    <option value="<?php echo $cat['cat_id'] ?>"><?php echo $cat['cat_desc'] ?></option>
                                                    <?php endforeach; ?>
                                                     </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right">API：</td>
                                            <td>
                                            	<span id="SipApinameDiv"><select id="sip_apiname" name="sip_apiname"><option value="">--请选择API--</option></select></span>&nbsp;</td>

                                        </tr>
                                        <tr style="display:none;">
                                            <td align="right">数据环境：</td>
                                            <td><input id="restId" name="restId" onclick="javascript:sanboxUrl()" checked="checked" type="radio"> 测试 <input id="restId" name="restId" onclick="javascript:apiUrl();" type="radio"> 正式</td>
                                        </tr>
                                        <tr>

                                            <td align="right">提交方式：</td>
                                            <td><input name="sip_http_method" value="2" checked="checked" type="radio"> POST　<input name="sip_http_method" value="1" type="radio"> GET</td>
                                        </tr>
                                        <tr style="display:none;">
                                            <td align="right">app key：</td>
                                            <td><input id="app_key" name="app_key" value="系统分配" style="width: 190px;" readonly="true" type="text">&nbsp;<a href="javascript:void(0)" onclick="javascript:readonlySelect();this.blur();"><span id="automaticSpan">自定义</span></a></td>

                                        </tr>
	                                    <tr style="display:none;">
	                                        <td align="right">app secret：</td>
	                                        <td><input id="app_secret" name="app_secret" value="系统分配" style="width: 190px;" readonly="true" type="text"></td>
	                                    </tr>
										<tr id="getSessionSpan">
											<td align="right">获取session key：</td>
											<td>

												<select id="sandboxUsers" name="sandboxUsers" style="width: 140px;">
													<option value="">请选择测试环境账号</option>
												</select>&nbsp;<input style="width: 50px; height: 22px; line-height: 16px;" onclick="getSandboxSession();" value="提交" type="button">
											</td>
										</tr>
										<tr id="sessionSapn">
	                                        <td align="right">session key：</td>

	                                        <td><input id="session" name="session" value="" style="width: 190px;" type="text">&nbsp;<a href="javascript:void(0)" title="当API的访问级别为‘公开’时，session key不需要填写；当API的访问级别为‘须用户登录’时，session key必须填写；当API的访问级别为‘隐私数据须用户登录’时，session key可填可不填；如何获取session key，请看API测试工具使用指南" onclick="alert('当API的访问级别为‘公开’时，session key不需要填写；\r\n当API的访问级别为‘须用户登录’时，session key必须填写；\r\n当API的访问级别为‘隐私数据须用户登录’时，session key可填可不填；\r\n如何获取session key，请看API测试工具使用指南');">说明</a></td>
	                                    </tr>
	                                    <input name="api_soure" id="api_soure" value="1" type="hidden">
	                                    <input name="hid_app_key" id="hid_app_key" value="10011201" type="hidden">
                                	</tbody></table>
                            	</td>
                            </tr>
                            <tr>
                            	<td> <span id="ParamDiv"></span></td>
                            </tr>
                         	<tr>
                             	<td>
                                	<table border="0" cellpadding="4" cellspacing="0" width="500">
                                        <tbody><tr>
                                            <td width="160">&nbsp;</td>
                                            <td align="left" width="340">
                                                <input value="提交测试" onclick="checkForm();this.blur();" style="border: 1px solid rgb(102, 102, 102); width: 60px; height: 24px; cursor: pointer;" type="button">
                                                <span id="bindUrlSpan" style="display: none;"><input value="绑定用户" onclick="bindUser();" onfocus="blur();" style="border: 1px solid rgb(102, 102, 102); width: 60px; height: 24px; cursor: pointer;" type="button"></span>

                                            </td>
                                        </tr>
                                    </tbody></table>
                               	</td>
                        	</tr>
                  		</tbody></table>
				  	</td>
    				<td width="60%" valign="top">
                       	提交参数：<br>
						<textarea name="param" id="param" cols="72" rows="8" style="overflow-x: scroll;" readonly="readonly"></textarea><br><br>
                        PHP 代码 (<a href="http://code.google.com/p/ywb-codes/wiki/TopPhpApiManual">查看 php api 文档</a>)：<br>
						<textarea name="param" id="phpcode" cols="72" rows="8" style="overflow-x: scroll;" readonly="readonly"></textarea><br><br>
                    	返回结果：<br>
						<textarea name="resultShow" id="resultShow" cols="72" rows="18" style="overflow-x: scroll;" readonly="readonly"></textarea>
			  		</td>
				</tr>
                <tr>
                	<td colspan="2" height="20">&nbsp;</td>

                </tr>
      		</tbody></table>
			</form>
			</div>
		</div>
		
<script language="javascript" type="text/javascript">
var u=window.location.toString();
u=u.split('?');
var wikiApi = '';
if(typeof(u[1]) == 'string') {
	u=u[1].split('=');
	wikiApi = u[1];
	wikiApi = wikiApi.toLowerCase();
}

//API数组
var apiArr = new Array();
var response = '';

if(window.ActiveXObject) {
	xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
} else {
	xmlHttp = new XMLHttpRequest();
}
var url = 'api_list.php?pAction=catList';
xmlHttp.open('GET', url, false);
xmlHttp.send(null);
if(xmlHttp.readyState == 4) {
	if(xmlHttp.status == 200) {
		response = xmlHttp.responseText;
		apiArr = eval("(" + response + ")");
	}
}

//API参数数组
var apiParam = new Array();
getApiParam();

function getApiParam () {
    var response = '';
    var url = 'api_list.php?pAction=catProperty';
    xmlHttp.open('GET', url, false);
    xmlHttp.send(null);
    if(xmlHttp.readyState == 4){
    	if(xmlHttp.status == 200){
    		response = xmlHttp.responseText;
    		apiParam = eval("(" + response + ")");
    	}
    }
}

//获得测试环境下session
function getSandboxSession() {
	if ('' == document.getElementById('sandboxUsers').value) {
		alert('请选择测试环境账号');
		return false;
	}
	if(window.ActiveXObject) {
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		xmlHttp = new XMLHttpRequest();
	}
	var url = 'getSessionByAuthcode.php?nick=' + document.getElementById('sandboxUsers').value;
	xmlHttp.open('GET', url, false);
	xmlHttp.send(null);
	if(xmlHttp.readyState == 4) {
		if(xmlHttp.status == 200) {
			response = xmlHttp.responseText;
			document.getElementById('session').value = response;
		}
	}
}

//根据不同的API类目来显现不同的API
function callgetApiListByCategory(o) {
	var sel = document.createElement('SELECT');
	sel.setAttribute('name','sip_apiname');
    sel.setAttribute('id','sip_apiname');
	var op = document.createElement('OPTION');
	op.setAttribute('value', '');
	op.innerHTML = '--请选择API--';
	sel.appendChild(op);
	if ('' != o.value) {
		var i;
		for (i in apiArr[o.value]) {
			op = document.createElement('OPTION');
			op.setAttribute('value', i);
			op.innerHTML = apiArr[o.value][i];
			sel.appendChild(op);
		}
	}
	document.getElementById('SipApinameDiv').innerHTML = '';
	document.getElementById('SipApinameDiv').appendChild(sel);
	document.getElementById('ParamDiv').innerHTML = '';

	if ('' == o.value) { document.getElementById('sip_apiname').style.width = '195px'; }

	document.getElementById('sip_apiname').onchange = function(){callgetParamListByApi(this);};
}

//根据不同的API来显示该API需要传递的参数
function callgetParamListByApi(o) {
	if ('undefined' != typeof(apiParam[o.value])) {
		var sip_category_id = document.getElementById('apiCategoryId').value;
		var sip_apiname = apiArr[sip_category_id][o.value];
		var str = '<table border=0 cellPadding=4 cellSpacing=0>';
		str += '<tr><td colspan="2">将鼠标移至说明上，查看参数介绍；<font color="red">*</font> 表示必填，<font color="blue">*</font> 表示几个参数中必填一个；查看<a href="http://wiki.open.taobao.com/index.php/' + sip_apiname + '" target="_blank" style="color:blue">API详情</a></td>';
		str += '</tr>';
		for (var i=0; i<apiParam[o.value].length; i++) {
			str += '<tr><td align="right" width="160">' + apiParam[o.value][i].name + '：</td>';
			str += '<td width="340">';
			if (apiParam[o.value][i].type == 'file') {
				str += '<span class="l"><iframe id="apirightframe" name="apirightframe" width="275px" height="22px" frameborder=0  scrolling="no" src="upload_img.php?imgName=' + apiParam[o.value][i].name + '"></iframe></span>';
				str += '<input type="hidden" name="' + apiParam[o.value][i].name + '" id="' + apiParam[o.value][i].name + '" value="">';
			} else {
				str += '<span class="l"><input type="' + apiParam[o.value][i].type + '" id="' + apiParam[o.value][i].name + '" name="' + apiParam[o.value][i].name + '" value="' + apiParam[o.value][i].value + '" style="width:190px;" /></span>';
			}
			if ('isMust' == apiParam[o.value][i].classname){
				str += '<span class="point-red">*</span>';
			}else if('mSelect' == apiParam[o.value][i].classname){
				str += '<span class="point-blue">*</span>';
			} else {
				str += '<span class="point-blue">&nbsp</span>';
			}
			str += '<span class="l"><a href="javascript:void(0);" title="' + apiParam[o.value][i].desc + '" class="explain">说明</a></span>';
			str += '</td>';
			str += '</tr>';
		}
		str += '</table>';
		document.getElementById('ParamDiv').innerHTML = str;
	} else {
		document.getElementById('ParamDiv').innerHTML = '';
	}
}

//提交测试
function checkForm() {
	var sip_apiname_id = document.getElementById('sip_apiname').value;
	
	if ('' == document.getElementById('apiCategoryId').value) {
		alert('请选择API类目');
		return false;
	}
	if ('' == sip_apiname_id) {
		alert('请选择API');
		return false;
	}
	
	if ('' == document.getElementById('app_key').value) {
		alert('请输入app key');
		document.getElementById('app_key').focus()
		return false;
	} else if ('系统分配' != document.getElementById('app_key').value) {
		document.getElementById('hid_app_key').value = document.getElementById('app_key').value;
	}
	
	if ('' == document.getElementById('app_secret').value) {
		alert('请输入app secret');
		document.getElementById('app_secret').focus()
		return false;
	}
	
	if (document.getElementsByName('sip_http_method')[0].checked) {
		ajaxRequest('POST', sip_apiname_id);
	} else {
		ajaxRequest('GET', sip_apiname_id);
	}
}

function bindSessionUser()
{
    var user = prompt('用户名');
    if ( user ) {
        if(window.ActiveXObject) {
        	xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        } else {
        	xmlHttp = new XMLHttpRequest();
        }
        var url = 'api_list.php?pAction=bindUser&user=' + encodeURIComponent(user);
        xmlHttp.open('GET', url, false);
        xmlHttp.send(null);
        if(xmlHttp.readyState == 4) {
        	if(xmlHttp.status == 200) {
        		var response = xmlHttp.responseText;
                response = eval("(" + response + ")");
                if ( response.status == 200 ) {
                    getApiParam();
                    document.getElementById('session_user').innerHTML = user + '<a onclick="return unbindSessionUser();" style="color: blue;">退出会话</a>';
                } else {
                    alert(response.msg);
                }
        	}
        }
    }
    return false;
}
function unbindSessionUser()
{
    if(window.ActiveXObject) {
    	xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
    	xmlHttp = new XMLHttpRequest();
    }
    var url = 'api_list.php?pAction=unbindUser';
    xmlHttp.open('GET', url, false);
    xmlHttp.send(null);
    if(xmlHttp.readyState == 4) {
    	if(xmlHttp.status == 200) {
    		var response = xmlHttp.responseText;
            response = eval("(" + response + ")");
            if ( response.status == 200 ) {
                getApiParam();
                document.getElementById('session_user').innerHTML = '<a onclick="return bindSessionUser();" style="color: blue;">绑定会话</a>';
            } else {
                alert(response.msg);
            }
    	}
    }
    return false;
}

//绑定用户
function bindUser() {
	if ('' == document.getElementById('app_key').value) {
		alert('请输入app key');
		document.getElementById('app_key').focus()
		return false;
	} else if('系统分配' != document.getElementById('app_key').value) {
		document.getElementById('hid_app_key').value = document.getElementById('app_key').value;
	}
	//window.open ('http://member1.daily.taobao.net/member/login.jhtml?redirect_url=http://container.daily.taobao.net/container?appkey=' + document.getElementById('hid_app_key').value);
	window.open ('http://member1.taobao.com/member/login.jhtml?redirect_url=http://container.api.taobao.com/container?appkey=' + document.getElementById('hid_app_key').value);
}

//ajax向应用服务发送请求
function ajaxRequest(sip_http_method, sip_apiname_id) {
  	if(window.ActiveXObject) {
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		xmlHttp = new XMLHttpRequest();
	}

	//参数设置
	var sip_category_id = document.getElementById('apiCategoryId').value;
	var sip_apiname = apiArr[sip_category_id][sip_apiname_id];
	var api_url = document.getElementById('api_url').value;
	var api_soure = document.getElementById('api_soure').value;
	var app_key = document.getElementById('app_key').value;
	var app_secret = document.getElementById('app_secret').value;
	var session = document.getElementById('session').value;
	
	var paramString =  'format=' + document.getElementById('format').value +
						'&method=' + sip_apiname +
						'&api_url=' + api_url +
						'&api_soure=' + api_soure +
						'&app_key=' + app_key +
						'&app_secret=' + app_secret +
						'&session=' + session +
						'&sip_http_method=' +  sip_http_method;

	if ('undefined' != typeof(apiParam[sip_apiname_id])) {
		for (var i = 0; i < apiParam[sip_apiname_id].length; i++) {
            apiParam[sip_apiname_id][i].value = document.getElementById(apiParam[sip_apiname_id][i].name).value;
			paramString += '&' + apiParam[sip_apiname_id][i].name + '="' + document.getElementById(apiParam[sip_apiname_id][i].name).value + '"';
		}
	}
	
	//IE兼容中文
	paramString = encodeURI(paramString);

	//异步调用时，IE下的GET提交时，onreadystatechange声明必须在send()之前，否则无效
	//同步调用时，onreadystatechange函数会失效
	//回调函数,显示传递的参数和返回结果
	xmlHttp.onreadystatechange = function() {
		if (4 == xmlHttp.readyState) {
			if (200 == xmlHttp.status) {
				var response = xmlHttp.responseText;
				response = eval('(' + response + ')');
                if ( 200 == response.status ) {
    				document.getElementById('param').value = response.param;
    				document.getElementById('phpcode').value = response.phpcode;
    				//如果格式为xml，IE下缩进xml
    				if ('xml' == document.getElementById('format').value && window.ActiveXObject) {
    					var rdr = new ActiveXObject("MSXML2.SAXXMLReader");
    					var wrt = new ActiveXObject("MSXML2.MXXMLWriter");
    					wrt.indent = true;
    					rdr.contentHandler = wrt;
    					rdr.parse(response.content);
    					document.getElementById('resultShow').value = wrt.output;
    				} else {
    					document.getElementById('resultShow').value = response.content;
    				}
    				if (document.getElementById('image') !=	null) {
    					document.getElementById('image').value = '';
    				}
                } else {
                    alert(response.msg);
                }
				xmlHttp = null;
			}
		}
	}
	
	//发送请求
	if ('POST' == sip_http_method) {
		xmlHttp.open('POST', 'api_test.php', true);
		//FF兼容中文
		xmlHttp.setRequestHeader("Content-Length",paramString.length);
		xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlHttp.send(paramString);
	} else {
		var url = 'api_test.php?'+paramString;
		xmlHttp.open('GET', url, true);
		xmlHttp.send(null);
	}
}

function readonlySelect(){
	if (document.getElementById("api_soure").value == 0) {
		document.getElementById("app_key").value = "";
		document.getElementById("app_key").readOnly = false;
		document.getElementById("app_secret").value = "";
		document.getElementById("app_secret").readOnly = false;
		document.getElementById("automaticSpan").innerHTML = "系统分配";
		document.getElementById("api_soure").value = 1;

		document.getElementById("sessionSapn").style.display='';
		document.getElementById("bindUrlSpan").style.display = 'none';
		document.getElementById("getSessionSpan").style.display = 'none';
		
	} else {
		document.getElementById("app_key").value = "系统分配";
		document.getElementById("app_key").readOnly = true;
		document.getElementById("app_secret").value = "系统分配";
		document.getElementById("app_secret").readOnly = true;
		document.getElementById("automaticSpan").innerHTML = "自定义";
		document.getElementById('hid_app_key').value = '10011201';
		document.getElementById("api_soure").value = 0;

		if (document.getElementById("api_url").value == "yield") {
			document.getElementById("sessionSapn").style.display='none';
			document.getElementById("bindUrlSpan").style.display = '';
			document.getElementById("getSessionSpan").style.display = 'none';
		} else {
			document.getElementById("sessionSapn").style.display='';
			document.getElementById("bindUrlSpan").style.display = 'none';
			document.getElementById("getSessionSpan").style.display = '';
		}
	}
}

function sanboxUrl(){
	document.getElementById("api_url").value = "sandbox";
	document.getElementById("bindUrlSpan").style.display = 'none';
	document.getElementById("sessionSapn").style.display='';
	
	if (document.getElementById("api_soure").value == 1) {
		document.getElementById("getSessionSpan").style.display = 'none';
	} else {
		document.getElementById("getSessionSpan").style.display = '';
	}
}

function apiUrl(){
	document.getElementById("api_url").value = "yield";
	document.getElementById("getSessionSpan").style.display = 'none';
	
	if (document.getElementById("api_soure").value == 1) {
		document.getElementById("sessionSapn").style.display='';
		document.getElementById("bindUrlSpan").style.display = 'none';
	} else {
		document.getElementById("sessionSapn").style.display='none';
		document.getElementById("bindUrlSpan").style.display = '';
	}
}

if ('' != wikiApi) {
	var j,k,m,n;
	for (j in apiArr) {
		for (k in apiArr[j]) {
			if (wikiApi == apiArr[j][k]) {
				m = j;
				n = k;
			}
		}
	}
	document.getElementById('apiCategoryId').selectedIndex = m;
	callgetApiListByCategory(document.getElementById('apiCategoryId'));
	var sipE = document.getElementById('sip_apiname');
	for (j=0;j<sipE.length;j++) {
		if (n == sipE[j].value) {
			document.getElementById('sip_apiname').selectedIndex = j;
			callgetParamListByApi(document.getElementById('sip_apiname'));
		}
	}
}
</script>
</body>
</html>
