function monioring(){
	var mark = $("title").html();
	var url   = encodeURI(encodeURI("http://poster.issmart.com.cn/poster/monitoring.php?mark="+mark));

	$.ajax({ 
		type: "GET", 	
		url: url,
		cache:false,
		dataType: "text",
		success: function(data) {
			//alert(data.success);
			},
		error: function(jqXHR){     
		   //alert("发生错误：" + jqXHR.status);  
		}    
	});
}

function monioringapp(){
	var markapp = $("title").html();
	var url   = encodeURI(encodeURI("http://poster.issmart.com.cn/poster/monitoring.php?markapp="+markapp));

	$.ajax({ 
		type: "GET", 	
		url: url,
		cache:false,
		dataType: "text",
		success: function(data) {
			//alert(data.success);
			},
		error: function(jqXHR){     
		   //alert("发生错误：" + jqXHR.status);  
		}    
	});
}