/*** Generic ***/
function popupclose() {
	$.each(BootstrapDialog.dialogs, function(id, dialog){
		dialog.close();
	});
}

function logout() {
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_logout.php",
		success: function(msg) {
			document.location.href="/";
		}
	});
}

/*** Users ***/
function popupUserDelete(user) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_userdelete.php",
		data: "user="+user,
		success: function(msg) {			
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formUserDelete(user) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_userdelete.php",
		data: "user="+user,
		success: function(msg) {
			document.location.href="/user";
		}
	});
}

function popupUserAdd() {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_useradd.php",
		success: function(msg) {			
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formUserAdd() {
	var name = $("#name").val();
	var mail = $("#mail").val();
	var password = $("#password").val();
	
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_useradd.php",
		data: "name="+encodeURI(name)+"&mail="+encodeURI(mail)+"&password="+encodeURI(password),
		success: function(msg) {
			document.location.href="/user";
		}
	});
}

/*** VPS ***/

function check_vps_list() {
	var nb = 0;
	$(".vps_list").each(function() {
		if($(this).is(':checked')) {
			nb = nb + 1
		}
	});
	
	if(nb == 0) {
		$("#vpsAll").hide();
	}
	else {
		$("#vpsAll").show();
	}
}

function popupVpsStartAll() {
	$("input.vps_list:checked").each(function() {
		popupVpsStart($(this).val());
	});
}

function popupVpsStart(vps) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_vpsstart.php",
		data: "vps="+vps,
		beforeSend: function(msg) {
			$.ajax({
				type: "GET",
				url: "/templates/basic/load_vpsstart.php",
				success: function(msg) {			
					BootstrapDialog.show({
						title: '<div id="popupTitle"></div>',
						message: msg
					});
				}
			});
		},
		success: function(msg) {
			setTimeout(function(){ popupVpsReload(vps); }, 1000);
		}		
	});
}

function popupVpsStopAll() {
	$("input.vps_list:checked").each(function() {
		popupVpsStop($(this).val());
	});
}

function popupVpsStop(vps) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_vpsstop.php",
		data: "vps="+vps,
		beforeSend: function(msg) {
			$.ajax({
				type: "GET",
				url: "/templates/basic/load_vpsstop.php",
				success: function(msg) {			
					BootstrapDialog.show({
						title: '<div id="popupTitle"></div>',
						message: msg
					});
				}
			});
		},
		success: function(msg) {
			setTimeout(function(){ popupVpsReload(vps); }, 2000);
		}		
	});
}

function popupVpsRestartAll() {
	$("input.vps_list:checked").each(function() {
		popupVpsRestart($(this).val());
	});
}

function popupVpsRestart(vps) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_vpsrestart.php",
		data: "vps="+vps,
		beforeSend: function(msg) {
			$.ajax({
				type: "GET",
				url: "/templates/basic/load_vpsrestart.php",
				success: function(msg) {			
					BootstrapDialog.show({
						title: '<div id="popupTitle"></div>',
						message: msg
					});
				}
			});
		},
		success: function(msg) {
			setTimeout(function(){ popupVpsReload(vps); }, 1000);
		}		
	});
}

function popupVpsDelete(vps) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_vpsdelete.php",
		data: "vps="+vps,
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formVpsDelete(server, vps) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_vpsdelete.php",
		data: "vps="+vps,
		success: function(msg) {
			document.location.href="/vpslist/"+server;
		}
	});
}

function popupVpsAdd(server) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_vpsadd.php",
		data: "server="+server,
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function popupVpsEdit(vps) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_vpsedit.php",
		data: "vps="+vps,
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formVpsEdit(vps) {
	var vps_name     = $('#vps_name').val();
	
	if($('#onboot').is(':checked')) {
		var onboot   = 1;
	}
	else {
		var onboot   = 0;
	}
	
	if($('#protected').is(':checked')) {
		var vps_protected   = 1;
	}
	else {
		var vps_protected   = 0;
	}
	
	var vps_ipv4     = $('#vps_ipv4').val();
	var ram          = $('#ram').val();
	var swap         = $('#swap').val();
	var diskspace    = $('#diskspace').val();
	var diskinodes   = $('#diskinodes').val();
	var vps_cpus     = $('#vps_cpus').val();
	var vps_cpulimit = $('#vps_cpulimit').val();
	var vps_cpuunits = $('#vps_cpuunits').val();
	var backup_limit = $('#backup_limit').val();
	var owner        = $('#owner').val();
	
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_vpsedit.php",
		data: "vps="+vps+"&name="+vps_name+"&onboot="+onboot+
		      "&ipv4="+vps_ipv4+"&ram="+ram+"&swap="+swap+
		      "&diskspace="+diskspace+"&diskinodes="+diskinodes+
		      "&cpus="+vps_cpus+"&cpulimit="+vps_cpulimit+
		      "&cpuunits="+vps_cpuunits+"&backup_limit="+backup_limit+
		      "&protected="+vps_protected+"&owner="+owner,
		success: function(msg) {
			popupVpsReload(vps);
		}
	});
}

function popupVpsReinstall(vps) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_vpsreinstall.php",
		data: "&vps="+vps,
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formVpsReinstall(vps) {
	var os = $("#os").val();
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_vpsreinstall.php",
		data: "vps="+vps+"&os="+os,
		beforeSend: function(msg) {
			$.ajax({
				type: "GET",
				url: "/templates/basic/load_vpsreinstall.php",
				success: function(msg) {			
					BootstrapDialog.show({
						title: '<div id="popupTitle"></div>',
						message: msg
					});
				}
			});
		},
		success: function(msg) {
			setTimeout(function(){ popupVpsReload(vps); }, 1000);
		}		
	});
}

function formVpsAdd(server) {
	var vps_name     = $('#vps_name').val();
	
	if($('#onboot').is(':checked')) {
		var onboot   = 1;
	}
	else {
		var onboot   = 0;
	}
 
	var vps_ipv4     = $('#vps_ipv4').val();
	var ram          = $('#ram').val();
	var swap         = $('#swap').val();
	var diskspace    = $('#diskspace').val();
	var vps_cpus     = $('#vps_cpus').val();
	var vps_cpulimit = $('#vps_cpulimit').val();
	var os           = $('#os').val();
	
	if(vps_name == '' || vps_name == 'undefined') {
		return null;
	}
	
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_vpsadd.php",
		data: "server="+server+"&name="+vps_name+"&onboot="+onboot+
		      "&ipv4="+vps_ipv4+"&ram="+ram+"&swap="+swap+
		      "&diskspace="+diskspace+"&os="+os+
		      "&cpus="+vps_cpus+"&cpulimit="+vps_cpulimit,
		beforeSend: function(msg) {
			$.ajax({
				type: "GET",
				url: "/templates/basic/load_vpsadd.php",
				success: function(msg) {			
					BootstrapDialog.show({
						title: '<div id="popupTitle"></div>',
						message: msg
					});
				}
			});
		},
		success: function(msg) {
			popupServerReload(server);
		}
	});
}

/*** Server ***/
function popupServerAdd() {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_serveradd.php",
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formServerAdd() {
	var name = $("#name").val();
	var address = $("#address").val();
	var port = $("#port").val();
	var key = $("#key").val();
	var description = $("#description").val();
	
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_serveradd.php",
		data: "name="+name+"&address="+address+"&port="+port+"&key="+key+"&description="+description,
		beforeSend: function(msg) {
			$.ajax({
				type: "GET",
				url: "/templates/basic/load_serveradd.php",
				success: function(msg) {			
					BootstrapDialog.show({
						title: '<div id="popupTitle"></div>',
						message: msg
					});
				}
			});
		},
		success: function(msg) {
			location.reload();
		}
	});
}

function popupServerEdit(server) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_serveredit.php",
		data: "server="+server,
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formServerEdit(server) {
	var name = $("#name").val();
	var address = $("#address").val();
	var port = $("#port").val();
	var key = $("#key").val();
	var description = $("#description").val();
	
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_serveredit.php",
		data: "server="+server+"&name="+name+"&address="+address+"&port="+port+"&key="+key+"&description="+description,
		beforeSend: function(msg) {
			$.ajax({
				type: "GET",
				url: "/templates/basic/load_serveradd.php",
				success: function(msg) {			
					BootstrapDialog.show({
						title: '<div id="popupTitle"></div>',
						message: msg
					});
				}
			});
		},
		success: function(msg) {
			location.reload();
		}
	});
}

function popupServerReload(server) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_serverreload.php",
		data: "server="+server,
		beforeSend: function(msg) {
			$.ajax({
				type: "GET",
				url: "/templates/basic/load_serverreload.php",
				success: function(msg) {			
					BootstrapDialog.show({
						title: '<div id="popupTitle"></div>',
						message: msg
					});
				}
			});
		},
		success: function(msg) {
			location.reload();
		}		
	});
}

function popupVpsReload(vps) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_vpsreload.php",
		data: "vps="+vps,
		beforeSend: function(msg) {
			$.ajax({
				type: "GET",
				url: "/templates/basic/load_serverreload.php",
				success: function(msg) {			
					BootstrapDialog.show({
						title: '<div id="popupTitle"></div>',
						message: msg
					});
				}
			});
		},
		success: function(msg) {
			location.reload();
		}		
	});
}

function popupServerRestart(server) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_serverrestart.php",
		data: "server="+server,
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formServerRestart(server) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_serverrestart.php",
		data: "server="+server,
		success: function(msg) {
			popupclose();
		}		
	});
}

function popupServerRemove(server) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_serverremove.php",
		data: "server="+server,
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formServerRemove(server) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_serverremove.php",
		data: "server="+server,
		success: function(msg) {
			location.reload();
		}		
	});
}

function popupTemplateAdd(server) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_templateadd.php",
		data: "server="+server,
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formTemplateAdd(server, template) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_templateadd.php",
		data: "server="+server+"&template="+template,
		beforeSend: function(msg) {
			$.ajax({
				type: "GET",
				url: "/templates/basic/load_serverreload.php",
				success: function(msg) {			
					BootstrapDialog.show({
						title: '<div id="popupTitle"></div>',
						message: msg
					});
				}
			});
		},
		success: function(msg) {
			location.reload();
		}
	});
}

function popupTemplateEdit(server, template) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_templateedit.php",
		data: "server="+server+"&template="+template,
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formTemplateEdit(server, old) {
	var name = $('#name').val();
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_templateedit.php",
		data: "server="+server+"&old="+old+"&name="+name,
		success: function(msg) {
			location.reload();
		}		
	});
}

function popupTemplateDelete(server, template) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_templatedelete.php",
		data: "server="+server+"&template="+template,
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formTemplateDelete(server, name) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_templatedelete.php",
		data: "server="+server+"&name="+name,
		success: function(msg) {
			location.reload();
		}		
	});
}

function popupIpAdd() {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_ipadd.php",
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formIpAdd(ip) {
	var ip = $("#ip").val();
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_ipadd.php",
		data: "ip="+ip,
		success: function(msg) {
			location.reload();
		}		
	});
}

function popupVpsClone(server, vps) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_vpsclone.php",
		data: "server="+server+"&vps="+vps,
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formVpsClone(server, vps) {
	var vps_name     = $('#vps_name').val();
	var vps_ipv4     = $('#vps_ipv4').val();
		
	if(vps_name == '' || vps_name == 'undefined') {
		return null;
	}
	
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_vpsclone.php",
		data: "vps="+vps+"&name="+vps_name+"&ipv4="+vps_ipv4,
		beforeSend: function(msg) {
			$.ajax({
				type: "GET",
				url: "/templates/basic/load_vpsclone.php",
				success: function(msg) {			
					BootstrapDialog.show({
						title: '<div id="popupTitle"></div>',
						message: msg
					});
				}
			});
		},
		success: function(msg) {
			setTimeout(function(){ popupServerReload(server); }, 2000);
		}
	});
}

function popupIpDelete(ip) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_ipdelete.php",
		data: "ip="+ip,
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formIpDelete(ip) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_ipdelete.php",
		data: "ip="+ip,
		success: function(msg) {
			location.reload();
		}		
	});
}

function popupVpsPassword(vps) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_vpspassword.php",
		data: "vps="+vps,
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formVpsPassword(vps) {
	var password = $("#password").val();
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_vpspassword.php",
		data: "vps="+vps+"&password="+encodeURI(password),
		beforeSend: function(msg) {
			$.ajax({
				type: "GET",
				url: "/templates/basic/load_vpspassword.php",
				success: function(msg) {			
					BootstrapDialog.show({
						title: '<div id="popupTitle"></div>',
						message: msg
					});
				}
			});
		},
		success: function(msg) {
			location.reload();
		}		
	});
}

function formVpsCmd(server, vps) {
	var cmd = $("#shell-input").val();
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_vpscmd.php",
		data: "vps="+vps+"&cmd="+cmd,
		beforeSend: function(msg) {
			$.ajax({
				type: "GET",
				url: "/templates/basic/load_vpscmd.php",
				success: function(msg) {			
					BootstrapDialog.show({
						title: '<div id="popupTitle"></div>',
						message: msg
					});
				}
			});
		},
		success: function(msg) {
			popupclose()
			if(msg != '') {
				$("#shell-result").html($("#shell-result").html()+msg);
				$("#shell-result").scrollTop($("#shell-result").prop('scrollHeight'));
			}
			$("#shell-input").val('');
		}		
	});
}

function popupBackupList(vps) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_backuplist.php",
		data: "vps="+vps,
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formBackupAdd(vps) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_backupadd.php",
		data: "vps="+vps,
		beforeSend: function(msg) {
			$.ajax({
				type: "GET",
				url: "/templates/basic/load_backupadd.php",
				success: function(msg) {			
					BootstrapDialog.show({
						title: '<div id="popupTitle"></div>',
						message: msg
					});
				}
			});
		},
		success: function(msg) {
			popupclose();
			popupBackupList(vps);
		}
	});
}

function popupBackupRestore(vps, name) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_backuprestore.php",
		data: "vps="+vps+"&name="+name,
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formBackupRestore(vps, name) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_backuprestore.php",
		data: "vps="+vps+"&name="+name,
		beforeSend: function(msg) {
			$.ajax({
				type: "GET",
				url: "/templates/basic/load_backuprestore.php",
				success: function(msg) {			
					BootstrapDialog.show({
						title: '<div id="popupTitle"></div>',
						message: msg
					});
				}
			});
		},
		success: function(msg) {
			location.reload()
		}
	});
}

function popupBackupDelete(vps, name) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_backupdelete.php",
		data: "vps="+vps+"&name="+name,
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formBackupDelete(vps, name) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_backupdelete.php",
		data: "vps="+vps+"&name="+name,
		success: function(msg) {
			popupclose();
			popupBackupList(vps);
		}
	});
}

function popupServerBackupDelete(server, vps, name) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_serverbackupdelete.php",
		data: "server="+server+"&vps="+vps+"&name="+name,
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}
function formServerBackupDelete(server, vps, name) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_serverbackupdelete.php",
		data: "server="+server+"&vps="+vps+"&name="+name,
		success: function(msg) {
			location.reload();
		}
	});
}

function popupProfile() {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_profile.php",
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formProfile() {
	var name = $("#name").val();
	var mail = $("#mail").val();
	var language = $("#language").val();
	
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_profile.php",
		data: "name="+name+"&mail="+mail+"&language="+language,
		success: function(msg) {
			location.reload();
		}
	});
}

function popupUserPassword() {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_userpassword.php",
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formUserPassword() {
	var current         = $("#current").val();
	var newpassword     = $("#newpassword").val();
	var confirmpassword = $("#confirmpassword").val();
	
	if(current != '' && newpassword != '' && confirmpassword != '') {
		$.ajax({
			type: "GET",
			url: "/templates/basic/form_userpassword.php",
			data: "current="+encodeURI(current)+"&new="+encodeURI(newpassword)+"&confirm="+encodeURI(confirmpassword),
			success: function(msg) {
				if(msg != "") {
					$("#error_userpassword").html(msg);
					$("#alert_userpassword").show();
				}
				else {
					location.reload();
				}
			}
		});
	}
}

function popupRequestAdd() {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_requestadd.php",
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function formRequestAdd() {
	var subject = $("#subject").val();
	var message = $("#message").val();
	
	$.ajax({
		type: "POST",
		url: "/templates/basic/form_requestadd.php",
		data: "subject="+subject+"&message="+message,
		success: function(msg) {
			location.reload();
		}		
	});
}

function formRequestAnswer(request) {
	var message = $("#message").val();
	
	$.ajax({
		type: "POST",
		url: "/templates/basic/form_requestanswer.php",
		data: "request="+request+"&message="+message,
		success: function(msg) {
			$("#message").val('');
			location.reload();
		}		
	});
}

function popupRequestClose(request) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_requestclose.php",
		data: "request="+request,
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}		
	});
}

function formRequestClose(request) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_requestclose.php",
		data: "request="+request,
		success: function(msg) {
			location.reload();
		}		
	});
}

function popupBackupSchedule(vps, saveId) {
	popupclose();
	
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_backupschedule.php",
		data: "vps="+vps+"&save="+saveId,
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle"></div>',
				message: msg
			});
		}
	});
}

function displayScheduleList() {
	$("#panel-backupschedule-add").hide('slow');
	$("#panel-backupschedule-list").show('slow');
}

function displayScheduleAdd() {
	$("#panel-backupschedule-list").hide('slow');
	$("#panel-backupschedule-add").show('slow');
}

function formScheduleAdd(vps, saveId) {
	var name = $("#name").val();
	if(name == '') {
		return '';
	}

	var minute = $("#minute").val();
	
	if(!(minute >= 0 && minute <= 59)) {
		minute = 0;
	}
	
	var hour = $("#hour").val();
	
	if(!(hour >= 0 && hour <= 23)) {
		hour = 0;
	}
	
	var dayw = 0;
	
	if($("#sunday").is(':checked')) { dayw += 64; }
	if($("#monday").is(':checked')) { dayw += 32; }
	if($("#tuesday").is(':checked')) { dayw += 16; }
	if($("#wednesday").is(':checked')) { dayw += 8; }
	if($("#thursday").is(':checked')) { dayw += 4; }
	if($("#friday").is(':checked')) { dayw += 2; }
	if($("#saturday").is(':checked')) { dayw += 1; }
	
	var dayn   = 0;
	var i;
	
	for(i=1;i<=31;i++) {
		if($("#day_"+i).is(':checked')) {
			dayn += Math.pow(2, 31-i);
		}
	}
	
	var month  = 0;
	for(i=1;i<=12;i++) {
		if($("#month_"+i).is(':checked')) {
			month += Math.pow(2, 12-i);
		}
	}
	
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_backupschedule.php",
		data: "vps="+vps+"&save="+saveId+"&name="+name+"&minute="+minute+"&hour="+hour+"&dayw="+dayw+"&dayn="+dayn+"&month="+month,
		success: function(msg) {
			popupclose();
			popupBackupSchedule(vps, saveId);
		}
	});
}

function popupBackupScheduleDelete(vps, saveId) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/popup_backupscheduledelete.php",
		data: "vps="+vps+"&saveId="+saveId,
		success: function(msg) {
			BootstrapDialog.show({
				title: '<div id="popupTitle2"></div>',
				message: msg
			});
		}
	});
}

function formBackupScheduleDelete(vps, saveId) {
	$.ajax({
		type: "GET",
		url: "/templates/basic/form_backupscheduledelete.php",
		data: "vps="+vps+"&saveId="+saveId,
		success: function(msg) {
			popupclose();
			popupBackupSchedule(vps, 0);
		}
	});
}
