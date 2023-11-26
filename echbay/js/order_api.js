// lấy vị trí của khách hàng dựa theo IP
function get_user_location_by_ip() {
	let a = $(".order_ip-to-location").attr("data-ip") || "";
	if (a == "") {
		return false;
	}

	//
	jQuery.ajax({
		type: "POST",
		url: "https://cloud.echbay.com/plains/city_ip",
		dataType: "json",
		//crossDomain: true,
		data: {
			ip: a,
		},
		timeout: 33 * 1000,
		error: function (jqXHR, textStatus, errorThrown) {
			console.log(jqXHR);
			console.log(textStatus);
			console.log(errorThrown);
		},
		success: function (data) {
			console.log(data);
			//console.log(data.length);

			//
			if (typeof data.data != "undefined") {
				let str = [];

				//
				if (typeof data.data.city != "undefined") {
					str.push(data.data.city.names.en);
				}

				//
				if (typeof data.data.subdivisions != "undefined") {
					str.push(data.data.subdivisions.names.en);
				}

				//
				if (typeof data.data.country != "undefined") {
					str.push(data.data.country.names.en);
				}

				//
				if (typeof data.data.continent != "undefined") {
					str.push(data.data.continent.names.en);
				}

				//
				str = str.join(", ");

				//
				if (typeof data.data.location != "undefined") {
					let map_url =
						"https://www.google.com/maps/@" +
						data.data.location.latitude +
						"," +
						data.data.location.longitude +
						",15z?hl=vi-VN&entry=ttu";

					//
					str =
						'<a href="' +
						map_url +
						'" target="_blank" rel="nofollow">' +
						str +
						"</a>";

					//
					$(".set-map-url").attr({
						href: map_url,
					});
				}

				//
				// console.log(str);
				$(".order_ip-to-location").html(str + " (" + data.last_updated + ")");
			}
		},
	});
}
get_user_location_by_ip();

// TEST API tạo đơn hàng tới GHN
function WGR_GHN_get_json(datas, uri, callBack) {
	jQuery
		.ajax({
			type: "POST",
			processData: false,
			data: JSON.stringify(datas),
			dataType: "json",
			contentType: "application/json; charset=utf-8",
			crossDomain: false,
			//		url: 'https://console.ghn.vn/api/v1/apiv3/" + uri
			url: "http://api.serverapi.host/api/v1/apiv3/" + uri,
		})
		.done(function (data) {
			if (typeof data.msg != "undefined" && data.msg == "Success") {
				// danh sách quận huyện
				if (uri == "GetDistricts") {
					let a = data.data,
						j = 0,
						k = 0;
					console.log(a.length);

					//
					for (let i = 0; i < a.length; i++) {
						// tỉnh/ thành phố
						if (a[i].Code == "") {
							console.log(a[i]);
							j++;
						}
						// quận/ huyện
						else {
							k++;
						}
					}
					console.log(j);
					console.log(k);
				}
				// kết quả mặc định
				else {
					console.log(data);
				}
			} else {
				console.log("ERROR");
				console.log(data);
			}
		});
}

function WGR_GHN_API() {
	console.log("TEST GHN");

	//
	WGR_GHN_get_json(
		{
			token: "TokenStaging",
		},
		"GetDistricts"
	);

	//
	WGR_GHN_get_json(
		{
			token: "TokenStaging",
			PaymentTypeID: 1,
			FromDistrictID: 1455,
			FromWardCode: "21402",
			ToDistrictID: 1462,
			ToWardCode: "21609",
			Note: "Tạo ĐH qua API",
			SealCode: "tem niêm phong",
			ExternalCode: "",
			ClientContactName: "client name",
			ClientContactPhone: "0987654321",
			ClientAddress: "140 Lê Trọng Tấn",
			CustomerName: "Nguyễn Văn A",
			CustomerPhone: "01666666666",
			ShippingAddress: "137 Lê Quang Định",
			CoDAmount: 1500000,
			NoteCode: "CHOXEMHANGKHONGTHU",
			InsuranceFee: 0,
			ClientHubID: 0,
			ServiceID: 53319,
			ToLatitude: 1.2343322,
			ToLongitude: 10.54324322,
			FromLat: 1.2343322,
			FromLng: 10.54324322,
			Content: "Test nội dung",
			CouponCode: "",
			Weight: 10200,
			Length: 10,
			Width: 10,
			Height: 10,
			CheckMainBankAccount: false,
			ShippingOrderCosts: [
				{
					ServiceID: 53337,
				},
			],
			ReturnContactName: "",
			ReturnContactPhone: "",
			ReturnAddress: "",
			ReturnDistrictCode: "",
			ExternalReturnCode: "",
			IsCreditCreate: true,
		},
		"CreateOrder"
	);
}

var xhr = new XMLHttpRequest();

function setHeader(xhr) {
	xhr.setRequestHeader("Token", "F9DAcA16456C59aA9FcA7ddf9420d0A62BAd515a");
}

function WGR_GHTK_API(datas, uri) {
	jQuery
		.ajax({
			type: "POST",
			processData: false,
			data: JSON.stringify(datas),
			//		Token: "F9DAcA16456C59aA9FcA7ddf9420d0A62BAd515a",
			dataType: "json",
			//		contentType: "application/json; charset=utf-8",
			contentType: "application/json",
			crossDomain: false,
			url: "https://dev.ghtk.vn/" + uri,
			beforeSend: setHeader,
		})
		.done(function (data) {});
}

// https://api.ghn.vn/home/docs/detail?id=28
//if ( window.location.href.split('localhost:8888').length > 1 || window.location.href.split('webgiare.org/').length > 1 ) {
//if ( window.location.href.split('localhost:8888').length > 1 ) {
if (window.location.href.split("webgiare.org/test").length > 1) {
	//	WGR_GHN_API();
	WGR_GHTK_API(
		{
			field1: "value1",
			field2: "value2",
		},
		"request-sample"
	);
}
