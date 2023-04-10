function showAlert(message, type) {
	var alertElement = new AlertElement(message, type);
	alertElement.showAndClose($('#alerts'), AlertElement.ShowDelayed);
}

function ajax(url, data, method, succesFunc) {
	if (method == 'GET') {
		return $.get(url, data, response => { console.log(response); succesFunc(response); })
			.fail(jqXHR => showAlert(jqXHR.responseJSON.err, AlertElement.TypeError));
	} else if (method == 'POST') {
		return $.post(url, data, response => { console.log(response); succesFunc(response); })
			.fail(jqXHR => showAlert(jqXHR.responseJSON.err, AlertElement.TypeError));
	}
	throw new Error('Undefined method');
}

function viewAjax(url, data, method, succesFunc = null, message = "") {
	ajax(url, data, method, response => {
		if (response.err != null) {
			showAlert(response.err, AlertElement.TypeError);
			return;
		}

		if (succesFunc != null) {
			succesFunc(Object.assign(data, response.data));
		}


		showAlert(message, AlertElement.TypeSuccess);
	});
}

function viewPost(url, data, succesFunc = null, message = "") {
	viewAjax(url, data, 'POST', succesFunc, message);
}

function viewGet(url, data, succesFunc = null, message = "") {
	viewAjax(url, data, 'GET', succesFunc, message);
}

function getFormData(form) {
	return [...$(form).find('input, textarea, select')];
}

function unpackFormData(formData) {
	let data = [];
	formData.forEach((val, i) => data[i] = $(val).val());
	return data;
}

function getUnpackedFormData(form) {
	return unpackFormData(getFormData(form));
}

function assignArrData(obj, arr) {
	let keys = Object.keys(obj);
	arr.forEach((_val, i) => obj[keys[i]] = arr[i]);
	return obj;
}

function openForm(form, data = [], func = null) {
	form = $(form);

	form.css('display', 'flex');
	$('#blur').show();

	let formChildren = $(getFormData(form));
	formChildren.each((i, obj) => $(obj).val(data[i]));

	if (func != null) {
		func([...formChildren, ...form.find('h1, h2, h3, h4')]);
	}
}

function closeForm(form) {
	$(form).hide();
	$('#blur').hide();
}

function addEntity(objData, arrData, name, respFunc, message = "") {
	assignArrData(objData, arrData);

	viewPost(
		`/${name}/add`, objData,
		responseData => respFunc(Object.assign(objData, responseData)), message
	);
}

function deleteEntity(name, id, respFunc, message = "") {
	viewPost(
		`/${name}/remove`, { id : id },
		responseData => respFunc(responseData), message
	);
}

function getEntity(name, id, respFunc, message = "") {
	viewPost(
		`/${name}/get`, { id : id },
		responseData => respFunc(responseData), message
	);
}

function editEntity(name, objData, arrData, respFunc, message = "") {
	assignArrData(objData, arrData);

	viewPost(
		`/${name}/edit`, objData,
		responseData => respFunc(Object.assign(objData, responseData)), message
	);
}