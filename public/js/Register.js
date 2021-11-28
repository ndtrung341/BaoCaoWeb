import { Validator } from './Validator.js';

Validator({
	form: '#register',
	groupSelector: '.form-group',
	messageSelector: '.form-message',
	rules: [
		Validator.required('#username', 'Tên đăng nhập là bắt buộc'),
		Validator.ajax('#username', 'Tên đăng nhập đã tồn tại', uniqueUsername),
		Validator.required('#email', 'Email là bắt buộc'),
		Validator.regex(
			'#email',
			'^\\w+([\\.-]?\\w+)*@\\w+([\\.-]?\\w+)*(\\.\\w{2,3})+$',
			'Email không hợp lệ',
		),
		Validator.ajax('#email', 'Email đã được sử dụng', uniqueEmail),
		Validator.required('#password', 'Mật khẩu là bắt buộc'),
		Validator.custom(
			'#password-confirm',
			'Mật khẩu nhập lại không chính xác',
			confirmPassword,
		),
	],
	onSubmit: async function (formData) {
		try {
			const res = await fetch(window.location.href, {
				method: 'POST',
				body: formData,
			});
			if (!res.ok) throw Error(res.statusText);
			Swal.fire({
				title: 'Đăng kí thành công',
				icon: 'success',
				showCancelButton: true,
				cancelButtonColor: '#d33',
				confirmButtonText: '<a href="/mvc/user/login">Đăng nhập ngay</a>',
			});
		} catch (error) {
			console.error(error);
		}
	},
});

async function uniqueUsername(username) {
	const res = await fetch('/mvc/user/check/', {
		method: 'POST',
		body: new URLSearchParams({ column: 'username', value: username }),
	});
	const data = await res.text();
	return data == 0;
}

async function uniqueEmail(email) {
	const res = await fetch('/mvc/user/check/', {
		method: 'POST',
		body: new URLSearchParams({ column: 'email', value: email }),
	});
	const data = await res.text();
	return data == 0;
}

function confirmPassword(value) {
	const password = document.querySelector('#password').value;
	return password && password == value;
}
