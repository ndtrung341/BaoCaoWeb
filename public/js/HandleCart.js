import * as Toast from './Toast.js';
let cartEl = document.querySelector('.cart');
let cartAmountEl = document.querySelector('.cart-amount');

/**
 * THÊM VÀO GIỎ HÀNG
 * @param {string} product_code mã sản phẩm
 * @param {number} quantity số lượng
 */
export async function addToCart(product_code, quantity = 1) {
	const body = {
		product_code: product_code,
		quantity: quantity,
		action: 'add',
	};

	try {
		const res = await fetch('/mvc/cart/add/', {
			method: 'POST',
			body: new URLSearchParams(body),
		});

		if (!res.ok) throw Error(res.statusText);
		// Toast.success("Đã thêm vào giỏ hàng");

		// Render lại giỏ hàng và số lượng
		const data = await res.json();
		return { ...data.data, qtyAddCart: quantity };
		// console.log(data);
		// cartEl.innerHTML = data["data"]["html"];
		// cartAmountEl.innerText = parseInt(cartAmountEl.innerText) + 1;
	} catch (err) {
		console.error(err);
	}
}
/**
 * CẬP NHẬT GIỎ HÀNG
 * @param {string} product_code mã sản phẩm
 * @param {number} quantity số lượng
 */
export async function updateCart(product_code, quantity) {
	const body = {
		product_code: product_code,
		quantity: quantity,
		action: 'update',
	};

	try {
		const res = await fetch('/mvc/cart/update/', {
			method: 'POST',
			body: new URLSearchParams(body),
		});

		if (!res.ok) throw Error(res.statusText);
		const data = await res.json();
		return data;
	} catch (err) {
		console.error(err);
	}
}
/**
 * LÀM TRỐNG GIỎ HÀNG
 */
export async function clearCart() {
	try {
		const res = await fetch('/mvc/cart/clear/', {
			method: 'POST',
			body: new URLSearchParams({ action: 'clear' }),
		});

		if (!res.ok) throw Error(res.statusText);
		const data = await res.json();
		return true;
	} catch (err) {
		console.error(err);
	}
}

/**
 * XÓA SẢN PHẨM KHỎI GIỎ HÀNG
 * @param {string} product_code ma san pham
 */
export async function deleteCart(product_code) {
	try {
		const res = await fetch('/mvc/cart/delete/' + product_code, {
			method: 'POST',
			body: new URLSearchParams({ action: 'delete' }),
		});

		if (!res.ok) throw Error(res.statusText);
		const data = await res.json();
		return data;
	} catch (err) {
		console.error(err);
	}
}

/**
 * Lấy số lượng sản phẩm có trong kho
 * @param {string} product_code mã sản phẩm
 * @returns số lượng sp có trong kho
 */
export async function getQtyAvailable(product_code) {
	try {
		const res = await fetch('/mvc/product/getOne', {
			method: 'POST',
			body: new URLSearchParams({ product_code: product_code }),
		});

		if (!res.ok) throw Error(res.statusText);
		const data = await res.json();
		return data.data.quantity;
	} catch (error) {
		console.log(error);
	}
}

/**
 * Lấy số lượng sản phẩm trong giỏ hàng
 * @param {string} product_code mã sản phẩm
 * @returns số lượng sp có trong giỏ hàng
 */
export async function getQtyInCart(product_code) {
	try {
		const res = await fetch('/mvc/cart/', {
			method: 'POST',
		});
		if (!res.ok) throw Error(res.statusText);
		const data = await res.json();
		// console.log(data);
		const product = data.data[product_code];
		return product?.quantity || 0;
	} catch (error) {
		console.log(error);
	}
}

/**
 * Kiểm tra số lượng sản phẩm có bị giới hạn hay không \
 * Ví dụ kho có 3 cái mà trong giỏ hàng đã có 3 thì không cho thêm vào giỏ hàng nữa
 * @param {*} product_code mã sản phẩm
 * @returns {boolean}
 */
export async function checkProduct(product_code) {
	let [qtyInCart, qtyAvailable] = await Promise.all([
		getQtyInCart(product_code),
		getQtyAvailable(product_code),
	]);
	console.log(qtyAvailable, qtyInCart);
	return qtyAvailable - qtyInCart > 0;
}

export function formatNumber(number) {
	return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}
