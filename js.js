var xmpt_ajax_prg_flag = new Array();
var xhanch_my_prayer_time_ajax_sct = new Array();
var xhanch_my_prayer_time_url = "";

function xmpt_in_array(hook, stack){
	for(tmp in stack){
		if(tmp == hook)
			return true;
	}
	return false;
}

function xhanch_my_prayer_time_ajax_sct_reg(xmpt_nm){
	if(!xmpt_in_array(xmpt_nm, xhanch_my_prayer_time_ajax_sct))
		xhanch_my_prayer_time_ajax_sct[xhanch_my_prayer_time_ajax_sct.length] = xmpt_nm;
}

function xmpt_ajax_hnd_err(xmpt_sct, e){
	xmpt_ajax_disp_msg(xmpt_sct, "error", xmpt_get_error(e));
	xmpt_ajax_disp_prg(xmpt_sct, false);
	if(xmpt_get_error(e).toLowerCase() == "session timeout")
		redirect_parent(xmpt_url(""));
}

function xmpt_ajax_disp_msg(xmpt_sct, xmpt_type, xmpt_msg){
	xmpt_ajax_hide_all_msg();
	var xmpt_wrn = xhanch_my_prayer_time_get_obj("sct_ajax_" + xmpt_sct + "_msg");
	if(xmpt_wrn == null)
		return;
	xmpt_wrn.className = "ajax_message " + xmpt_type;
	xmpt_wrn.style.display = "";
	xmpt_wrn.innerHTML = xmpt_msg;
}

function xmpt_ajax_hide_msg(xmpt_sct){
	var xmpt_msg = xhanch_my_prayer_time_get_obj("sct_ajax_" + xmpt_sct + "_msg");	
	if(xmpt_msg == null)
		return;
	xmpt_msg.style.display = "none";
	xmpt_msg.innerHTML = xmpt_msg;
}

function xmpt_ajax_hide_all_msg(){
	for(xmpt_sct in xhanch_my_prayer_time_ajax_sct){
		xmpt_ajax_hide_msg(xhanch_my_prayer_time_ajax_sct[xmpt_sct]);
	}
}

function xmpt_ajax_disp_prg(xmpt_sct, xmpt_prg){
	if(typeof xmpt_prg == "undefined")
		xmpt_prg = true;
	if(typeof xmpt_ajax_prg_flag[xmpt_sct] == "undefined")
		xmpt_ajax_prg_flag[xmpt_sct] = false;
	var xmpt_frm = xhanch_my_prayer_time_get_obj("sct_ajax_" + xmpt_sct);
	var xmpt_trn = xhanch_my_prayer_time_get_obj("sct_ajax_" + xmpt_sct + "_prg");
	
	if(xmpt_prg){
		if(xmpt_ajax_prg_flag[xmpt_sct])
			return;		
			
		xmpt_ajax_hide_msg(xmpt_sct);
		
		if(xmpt_to_int(xmpt_frm.offsetHeight) <= 20)
			xmpt_trn.style.height = "20px";
		else
			xmpt_trn.style.height = xmpt_frm.offsetHeight + "px";
		
		xmpt_trn.style.width = xmpt_frm.offsetWidth + "px";
		
		xmpt_frm.style.display = "none";
		xmpt_trn.style.display = "";
		
		xmpt_ajax_prg_flag[xmpt_sct] = true;
	}else{
		if(!xmpt_ajax_prg_flag[xmpt_sct])
			return;	
			
		xmpt_frm.style.display = "";
		xmpt_trn.style.display = "none";
		xmpt_ajax_prg_flag[xmpt_sct] = false;
	}
}
	
function xmpt_create_http_req() {
	var xmpt_request;
	if(window.ActiveXObject){
		try{
			xmpt_request = new ActiveXObject("Microsoft.XMLHTTP");
		}catch (e){
			xmpt_request = false;
		}
	}else{
		try{
			xmpt_request = new XMLHttpRequest();
		}catch (e){
			xmpt_request = false;
		}
	}
	if (!xmpt_request)
		xmpt_catch_error("Your browser does not support AJAX!");
	else
		return xmpt_request;
}

function xmpt_ajax_query(prm_cmd, prm_xmpt_dat_post, prm_xmpt_misc){
	var xmpt_xml = xmpt_create_http_req();
	if(typeof prm_dat_get == "undefined")
		var prm_dat_get = {};
	if(typeof prm_xmpt_dat_post == "undefined")
		var prm_xmpt_dat_post = {};
	if(typeof prm_xmpt_misc == "undefined")
		var prm_xmpt_misc = {};
		
	function xmpt_send_query(){
		if(prm_cmd == ""){
			alert("No act to be send!");
			return false;
		}
		
		if (xmpt_xml.readyState == 4 || xmpt_xml.readyState == 0){ 
			req_url = xmpt_url(prm_cmd);	
			dat_str = "";
			for(tmp in prm_xmpt_dat_post){
				if(dat_str != "")
				   	dat_str += "&";
				dat_str += tmp + "=" + prm_xmpt_dat_post[tmp];
			}
			if(dat_str == ""){
				xmpt_xml.open("GET", req_url, true); 			
				xmpt_xml.onreadystatechange = xmpt_response;
				xmpt_xml.setRequestHeader("Accept-Charset","UTF-8");
				xmpt_xml.send(null);
			}else{
				xmpt_xml.open("POST", req_url, true); 			
				xmpt_xml.onreadystatechange = xmpt_response;
				xmpt_xml.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				xmpt_xml.setRequestHeader("Accept-Charset","UTF-8");
				xmpt_xml.send(dat_str); 
			}			
			return true;
		}		
		return false;
	}
	
	function xmpt_response(){
		try{
			if (xmpt_xml.readyState == 4) { 
				if (xmpt_xml.status == 200) {
					try{
						xmpt_res = xmpt_xml.responseXML.documentElement;	
						xmpt_handle_response(xmpt_res);
									
						if(typeof prm_xmpt_misc.exec_cpl != "undefined")
							eval(prm_xmpt_misc.exec_cpl);
						if(typeof prm_xmpt_misc.exec_done != "undefined")
							eval(prm_xmpt_misc.exec_done);
					}catch(err){}
				}else{
					xmpt_catch_error(xmpt_xml.responseText); 
				}
			}
		}catch (e){
			if(typeof prm_xmpt_misc.exec_fail != "undefined")
				eval(prm_xmpt_misc.exec_fail);
			if(typeof prm_xmpt_misc.exec_done != "undefined")
				eval(prm_xmpt_misc.exec_done);
			xmpt_catch_error(e);
		}
	}
	
	function xmpt_handle_response(xmpt_reply){	
		try{
			if(typeof prm_xmpt_misc.exec != "undefined")
				eval(prm_xmpt_misc.exec + "(xmpt_reply, prm_xmpt_misc);");
		}catch (e){
			xmpt_catch_error(e);
		}
	}
	
	return xmpt_send_query();
}

function xmpt_str_replace(str,sr,val){
	return str.replace(sr, val, "g");	
}

function xmpt_parse_html(html){
	var xmpt_res = html;
	
	xmpt_res = xmpt_str_replace(xmpt_res,"&gt;",">");	
	xmpt_res = xmpt_str_replace(xmpt_res,"&lt;","<");	
	xmpt_res = xmpt_str_replace(xmpt_res,"&quot;","\"");
	xmpt_res = xmpt_str_replace(xmpt_res,"&brvbar;","|");
	xmpt_res = xmpt_str_replace(xmpt_res,"&amp;","&");
	return xmpt_res;
}

function xmpt_to_int(str){
	xmpt_res = parseInt(xmpt_to_num(str));
	if(isNaN(xmpt_res))
		xmpt_res = 0;
	return xmpt_res;
}

function xmpt_to_num(str){
	xmpt_res = parseFloat(str);
	if(isNaN(xmpt_res))
		xmpt_res = 0;
	return xmpt_res;
}

function xmpt_is_ie(){
	if(navigator.appName == "Microsoft Internet Explorer")
		return true;
	else
		return false;
}

function xmpt_is_opera(){
	if(navigator.appName == "Opera")
		return true;
	else
		return false;
}

function xmpt_get_error(e){
	return e.message
}

function xhanch_my_prayer_time_get_obj(object_name){
	return document.getElementById(object_name);
}

function xmpt_catch_error(str){
	if(str != "")
		throw new Error(str);
}

/* URL */

function xmpt_url(xmpt_nm){		
	return xhanch_my_prayer_time_url + "/ajax/" + xmpt_nm;
}

/* End of URL */

/* xmpt_xml */

function xmpt_xml_nd_val(xmpt_nd){
	try{
		return xmpt_parse_html(xmpt_nd.firstChild.data);
	}catch(e){
		return "";
	}
}

function xml_nds_by_tag(xmpt_xml, xmpt_tag){
	xmpt_res = new Array();
	xmpt_nds = xmpt_xml.getElementsByTagName(xmpt_tag);
	xmpt_len = xmpt_nds.length;
	for(xmpt_ai=0;xmpt_ai<xmpt_len;xmpt_ai++)
		xmpt_res[xmpt_ai] = xmpt_nds[xmpt_ai];
	return xmpt_res;
}

function xmpt_xml_nd_chd(xmpt_nd){
	xmpt_res = new Array();
	xmpt_nds = xmpt_nd.childNodes;
	xmpt_len = xmpt_nds.length;	
	for(xmpt_bi=0;xmpt_bi<xmpt_len;xmpt_bi++)
		xmpt_res[xmpt_bi] = xmpt_nds[xmpt_bi];
	return xmpt_res;
}

function xmpt_xml_msg(xmpt_xml, xmpt_tag){
	if(typeof xmpt_tag == "undefined")
		var xmpt_tag = "error";
	
	lst_msg = new Array();
	nds_msg = xml_nds_by_tag(xmpt_xml, xmpt_tag);
	if(nds_msg.length == 0)
		return "";
	for(nd_msg in nds_msg)
		lst_msg[lst_msg.length] = xmpt_xml_nd_val(nds_msg[nd_msg]);	
	return lst_msg.join("<br/>");
}

/* End of xmpt_xml*/

/* Table */

function tbl_rst_row(xmpt_nm, skip_first){
	if(typeof(skip_first) == "undefined")
		skip_first = false;

	var tbl = xhanch_my_prayer_time_get_obj(xmpt_nm);
	tbl = tbl.getElementsByTagName("tbody")[0];
	var xmpt_nds = xmpt_xml_nd_chd(tbl);
	var is_first = true;
	for(xmpt_nd in xmpt_nds){
		if(typeof xmpt_nds[xmpt_nd].nodeName != "undefined"){
			if(xmpt_nds[xmpt_nd].nodeName.toLowerCase() == "tr"){	
				if(skip_first == true && is_first == true){
					is_first = false;
					continue;
				}else{
					tbl.removeChild(xmpt_nds[xmpt_nd]);		
				}
			}
		}
	}
}

function tbl_add_row(xmpt_nm, col_def, atr){
	if(typeof atr == "undefined")
		atr = new Array();
	
	var tbl = xhanch_my_prayer_time_get_obj(xmpt_nm);
	tbl = tbl.getElementsByTagName("tbody")[0];
	
	var row = document.createElement("tr");
	
	try{
		for(tmp_atr in atr){		
			if(tmp_atr == "class")
				row.className = atr[tmp_atr];
			else
				row.setAttribute(tmp_atr, atr[tmp_atr]);
		}
	}catch(err){}
	
	for(xmpt_ti=0;xmpt_ti<col_def.length;xmpt_ti++){
		col = document.createElement("td");
		tmp_col_ctt = col_def[xmpt_ti];
		
		if(typeof tmp_col_ctt["atr"] == "undefined")
			tmp_col_ctt_atr = new Array();
		else
			tmp_col_ctt_atr = tmp_col_ctt["atr"];
		for(tmp_atr in tmp_col_ctt_atr){
			if(tmp_atr == "class")
				col.className = tmp_col_ctt_atr[tmp_atr];
			else
				col.setAttribute(tmp_atr, tmp_col_ctt_atr[tmp_atr]);
		}
		
		col.innerHTML = tmp_col_ctt["val"];		
		row.appendChild(col);
	}	
	
	tbl.appendChild(row);
}

/* End of Table*/

/* Element */
function xhanch_my_prayer_time_cbo_reset(id)
{
	xhanch_my_prayer_time_get_obj(id).options.length = 0;
}

function xhanch_my_prayer_time_cbo_add(id, prm_text, prm_val) {
	var optn = document.createElement("OPTION");
	optn.text = prm_text;
	optn.value = prm_val;
	xhanch_my_prayer_time_get_obj(id).options.add(optn);
}
/* End of Element */

/* Widget Load Prayer Time */
function xhanch_my_prayer_time_widget_time_load(){
	try{
		xmpt_ajax_disp_prg("xhanch_my_prayer_time_widget_time");
		
		xmpt_misc = {
			"exec": "xhanch_my_prayer_time_widget_time_load_exec",
			"exec_done": "xmpt_ajax_disp_prg('xhanch_my_prayer_time_widget_time', false);"
		};
		
		xmpt_dat_post = {
			"date_dd": encodeURIComponent(xhanch_my_prayer_time_get_obj("xhanch_my_prayer_time_widget_date_dd").value),
			"date_mm": encodeURIComponent(xhanch_my_prayer_time_get_obj("xhanch_my_prayer_time_widget_date_mm").value),
			"date_yy": encodeURIComponent(xhanch_my_prayer_time_get_obj("xhanch_my_prayer_time_widget_date_yy").value)
		};

		if(!xmpt_ajax_query("get_daily_time.php", xmpt_dat_post, xmpt_misc))
			setTimeout("xhanch_my_prayer_time_widget_time_load()", 1000);
	}catch(e){
		xmpt_ajax_hnd_err("xhanch_my_prayer_time_widget_time", e);
	}
}

function xhanch_my_prayer_time_widget_time_load_exec(xmpt_reply){	
	try{
		xmpt_catch_error(xmpt_xml_msg(xmpt_reply, "error"));
		xmpt_nds_res = xmpt_xml_nd_chd(xmpt_reply);

		if(xmpt_nds_res.length > 0){
			for(var i=0;i<xmpt_nds_res.length;i++){	
				xmpt_tmp_name = xmpt_nds_res[i].nodeName;
				xmpt_tmp_time = xmpt_xml_nd_val(xmpt_nds_res[i]);			
				xmpt_tmp_elm = xhanch_my_prayer_time_get_obj("xhanch_my_prayer_time_widget_time_" + xmpt_tmp_name);
				if(xmpt_tmp_elm != null)
					xmpt_tmp_elm.innerHTML = xmpt_tmp_time;
			}
		}
		xmpt_ajax_disp_prg('xhanch_my_prayer_time_widget_time', false);
	}catch(e){		
		xmpt_ajax_hnd_err("xhanch_my_prayer_time_widget_time", e);
	}	
} 

/* End of Widget Load Prayer Time */

/* Page Load Prayer Time */
function xhanch_my_prayer_time_page_time_load(){
	try{
		xmpt_ajax_disp_prg("xhanch_my_prayer_time_page_time");
		
		xmpt_misc = {
			"exec": "xhanch_my_prayer_time_page_time_load_exec",
			"exec_done": "xmpt_ajax_disp_prg('xhanch_my_prayer_time_page_time', false);"
		};
		
		xmpt_dat_post = {
			"date_mm": encodeURIComponent(xhanch_my_prayer_time_get_obj("xhanch_my_prayer_time_page_date_mm").value),
			"date_yy": encodeURIComponent(xhanch_my_prayer_time_get_obj("xhanch_my_prayer_time_page_date_yy").value)
		};

		xhanch_my_prayer_time_get_obj("lnk_xhanch_my_prayer_time_print").href = xhanch_my_prayer_time_url + "/xhanch_my_prayer_time.print.php?date_mm=" + xmpt_dat_post.date_mm + "&date_yy=" + xmpt_dat_post.date_yy;
		
		if(!xmpt_ajax_query("get_monthly_time.php", xmpt_dat_post, xmpt_misc))
			setTimeout("xhanch_my_prayer_time_page_time_load()", 1000);
	}catch(e){
		xmpt_ajax_hnd_err("xhanch_my_prayer_time_page_time", e);
	}
}

function xhanch_my_prayer_time_page_time_load_exec(xmpt_reply){
	try{
		xmpt_catch_error(xmpt_xml_msg(xmpt_reply, "error"));		
		xmpt_nds_res = xml_nds_by_tag(xmpt_reply, "list");
		
		tbl_rst_row("xhanch_my_prayer_time_monthly", true);	
		
		if(xmpt_nds_res.length > 0){		
			for(xmpt_i=0;xmpt_i<xmpt_nds_res.length;xmpt_i++){
				ndc_res = xmpt_xml_nd_chd(xmpt_nds_res[xmpt_i]);
				rw = new Array();
				
				ret_date = xmpt_i.toString();		
				ret_fajr = "";
				ret_sunrise = "";
				ret_zuhr = "";
				ret_asr = "";
				ret_maghrib = "";
				ret_isha = "";
				
				if(ndc_res.length > 0){
					for(xmpt_j=0;xmpt_j<ndc_res.length;xmpt_j++){
						xmpt_nd = ndc_res[xmpt_j];
						val = xmpt_xml_nd_val(xmpt_nd);
						switch(xmpt_nd.nodeName){
							case "date":
								ret_date = val;							
								break;
							case "fajr":
								ret_fajr = val;							
								break;
							case "sunrise":
								ret_sunrise = val;							
								break;
							case "zuhr":
								ret_zuhr = val;							
								break;
							case "asr":
								ret_asr = val;							
								break;
							case "maghrib":
								ret_maghrib = val;							
								break;
							case "isha":
								ret_isha = val;							
								break;
						}
					}							
		
					rw[rw.length] = {"val": ret_date};
					rw[rw.length] = {"val": ret_fajr};
					rw[rw.length] = {"val": ret_sunrise};
					rw[rw.length] = {"val": ret_zuhr};
					rw[rw.length] = {"val": ret_asr};
					rw[rw.length] = {"val": ret_maghrib};
					rw[rw.length] = {"val": ret_isha};
					
					tbl_add_row("xhanch_my_prayer_time_monthly", rw);
				}
			}		
		}else{
			rw = {0:{"val": "Nothing to add"}};
			tbl_add_row("xhanch_my_prayer_time_monthly", rw);
		}
		xmpt_ajax_disp_prg('xhanch_my_prayer_time_page_time', false);
	}catch(e){
		alert(e);
		xmpt_ajax_hnd_err("xhanch_my_prayer_time_page_time", e);
	}	
} 

/* End of Page Load Prayer Time */