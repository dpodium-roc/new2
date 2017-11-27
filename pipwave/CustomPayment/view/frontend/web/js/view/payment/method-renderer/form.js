
	
	var pwconfig = getApiData();
	(function (_, p, w, s, d, k) {
		var a = _.createElement("script");
		a.setAttribute('src', w + d);
		a.setAttribute('id', k);
		setTimeout(function() {
			var reqPwInit = (typeof reqPipwave != 'undefined');
			if (reqPwInit) {
				reqPipwave.require(['pw'], function(pw) {
					pw.setOpt(pwconfig);
					pw.startLoad();
				});
			} else {
				_.getElementById(k).parentNode.replaceChild(a, _.getElementById(k));
			}
		}, 800);
	})(document, 'script', getSdkUrl(), "pw.sdk.min.js", "pw.sdk.min.js", "pwscript");