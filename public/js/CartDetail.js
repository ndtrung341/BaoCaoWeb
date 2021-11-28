import { deleteCart, updateCart, clearCart } from './HandleCart.js';
let cartEl = document.querySelector('.cart-detail');
let totalPriceEl = document.querySelector('.total-price');
let totalAmountEl = document.querySelector('.total-amount');

/**
 * Làm trống giỏ hàng
 */
document.querySelector('.btn-clear')?.addEventListener('click', () => {
	clearCart().then(renderEmptyCart);
});

/**
 * Xóa sản phẩm khỏi giỏ hàng
 */
cartEl?.addEventListener('click', function (e) {
	if (e.target.closest('.cart-delete')) {
		const cartItem = e.target.closest('.cart-item');
		const product_code = cartItem.dataset.id;
		console.log(product_code);

		deleteCart(product_code).then((data) => {
			if (data.data.length == 0) renderEmptyCart();
			else {
				cartItem.remove();
				calcOrder(data.data);
			}
		});
	}
});

/**
 * cập nhật số lượng
 */
(async () => {
	const cartItems = [...document.querySelectorAll('.cart-item')];
	// Số lượng sản phẩm có trong kho
	const qtyInStocks = await Promise.all(
		cartItems.map((item) => getProduct(item.dataset.id)),
	).then((products) => products.map((product) => product.quantity));

	//Lặp qua từng input và xử lý thay đổi số lượng
	cartItems.forEach((item, index) => {
		// lấy mã sp
		let product_code = item.dataset.id;

		let qtyInStock = qtyInStocks[index];
		// thiết lập sự kiện cho input
		spinnerInput({
			container: `.cart-item:nth-child(${index + 1}) .quantity-control`,
			btnDecrease: '.quantity-decrease',
			btnIncrease: '.quantity-increase',
			input: '.quantity-input',
			min: 1,
			max: qtyInStock,
			onChange: function (value) {
				// hàm xử lý sau khi giá trị input thay dôi
				updateCart(product_code, value).then((data) => {
					console.log(data.data);

					// lấy thông tin sản phẩm đã thay doi
					const product = data.data[product_code];

					// tính lại giá cho sản phẩm
					const price = product.quantity * product.price;
					item.querySelector('.product-total-price').innerHTML =
						formatNumber(price);

					// tính lại tổng tiền cho đơn hàng
					calcOrder(data.data);
				});
			},
		});
	});
})();

// Hàm lấy thông tin sản phẩm
async function getProducts() {
	try {
		const res = await fetch('/mvc/product/getAll', {
			method: 'POST',
		});
		if (!res.ok) throw Error(res.statusText);
		const data = await res.json();
		return data.data;
	} catch (error) {
		console.log(error);
	}
}

async function getProduct(product_code) {
	try {
		const res = await fetch('/mvc/product/getOne', {
			method: 'POST',
			body: new URLSearchParams({ product_code: product_code }),
		});
		if (!res.ok) throw Error(res.statusText);
		const data = await res.json();
		return data.data;
	} catch (error) {
		console.log(error);
	}
}
/**
 *  ĐỊNH DẠNG TIỀN
 * @param {number} number
 */
function formatNumber(number) {
	return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// hàm tính đơn hàng
function calcOrder(cart) {
	const total = Object.values(cart).reduce(
		(acc, product) => {
			acc['price'] += product.quantity * product.price;
			acc['amount'] += 1 * product.quantity;
			return acc;
		},
		{ price: 0, amount: 0 },
	);
	totalPriceEl.innerText = formatNumber(total.price);
	totalAmountEl.innerText = total.amount;
}

function renderEmptyCart() {
	let html = `<div class="cart-empty">
                <p class="">You don't have anything in your cart</p>
                <a href="/mvc/" class="btn">Start shopping</a>
              </div>`;
	document.querySelector('.content-inner').innerHTML = html;
}
