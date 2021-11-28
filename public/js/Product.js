import {
	addToCart,
	checkProduct,
	getQtyInCart,
	getQtyAvailable,
} from './HandleCart.js';
import { cartMessage } from './Toast.js';

// Tạo slide
// const swiper2 = new Swiper('.image-list', {
// 	direction: 'horizontal',
// 	slidesPerView: 4,
// 	slideToClickedSlide: true,
// 	centeredSlides: true,
// 	watchOverflow: true,
// 	freeMode: true,
// 	spaceBetween: 10,
// 	on: {
// 		slideChange: function () {
// 			console.log(this.realIndex);
// 		},
// 		click: function () {
// 			console.log(this.realIndex);
// 			this.realIndex = 7;
// 		},
// 	},
// });
let thumbs = new Swiper('.thumbs', {
	spaceBetween: 10,
	slidesPerView: 4,
	// slideToClickedSlide: true,
});
let swiper = new Swiper('.image-list', {
	slidesPerView: 1,
	spaceBetween: 10,
	thumbs: {
		swiper: thumbs,
	},
});
/**
 * TAB
 */
const tabs = document.querySelectorAll('.tab');
const tabPanels = document.querySelectorAll('.tab-panel');
tabs.forEach((tab, index) => {
	tab.addEventListener('click', function (e) {
		document.querySelector('.tab.active').classList.remove('active');
		document.querySelector('.tab-panel.active').classList.remove('active');
		tabPanels[index].classList.add('active');
		this.classList.add('active');
	});
});

/**
 * Thực hiện thêm vào giỏ hàng khi ở trang chi tiết
 */
const btnAddCart = document.querySelector('.btn-add-cart');
const qtyInput = document.querySelector('.quantity-input');
const cart = document.querySelector('.cart');
let cartAmountEl = document.querySelector('.cart-amount');

(async () => {
	const productCode = getProductCode();

	// Lấy số lượng trong kho và trong giỏ hàng
	let [qtyInCart, qtyAvailable] = await Promise.all([
		getQtyInCart(productCode),
		getQtyAvailable(productCode),
	]);

	spinnerInput({
		container: '.quantity-control',
		btnDecrease: '.quantity-decrease',
		btnIncrease: '.quantity-increase',
		input: '.quantity-input',
		min: 1, // Giá trị min không thay đổi
		onMax: () => qtyAvailable - qtyInCart, // giá trị max có thể thay đổi
	});

	btnAddCart?.addEventListener('click', function (e) {
		e.stopPropagation(); // Stop event cho nút đã được kích hoạt ở file Main.js (Tại nút cùng tên class - Lười đặt tên khác)
		const quantity = parseInt(qtyInput.value);
		const product_code = getProductCode();

		addToCart(product_code, quantity).then((data) => {
			btnAddCart.innerHTML = '<div class="loader"></div>';
			setTimeout(() => {
				const { html, product } = data;
				// cập nhật lại giỏ hàng và số lượng
				cartMessage(product, quantity);
				cart.innerHTML = html;
				cartAmountEl.innerHTML = 1 * cartAmountEl.innerHTML + quantity;
				// cập nhật lại số lượng đã có trong giỏ hàng
				qtyAvailable = product['quantity'];
				qtyInCart += quantity;
				qtyInput.value = 1; // reset input về 1
				// Kiểm tra
				if (product['quantity'] - qtyInCart > 0) {
					e.target.innerHTML = 'Add to cart';
				} else {
					e.target.insertAdjacentHTML(
						'afterend',
						'<button class="btn-disabled">Limit Exceeded</button>',
					);
					e.target.remove();
					qtyInput.parentElement.parentElement.remove();
				}
			}, 1500);
		});
	});
})();

/**
 * Lấy mã sản phẩm dựa vào url
 * @returns mã sản phẩm
 */
function getProductCode() {
	const url = window.location.pathname;
	return url.match(/[^-]+$/g);
}
