import { addToCart, checkProduct } from './HandleCart.js';
import { cartMessage } from './Toast.js';

window.addEventListener('DOMContentLoaded', (e) => {
	const toggleMenu = document.querySelector('.icon__bars');
	const toggleCart = document.querySelector('.icon__cart');
	const menuMobile = document.querySelector('.mobile-menu');
	const cart = document.querySelector('.cart');
	const overlay = document.querySelector('.overlay');
	const filter = document.querySelector('.filter');
	let cartAmountEl = document.querySelector('.cart-amount');
	const iconSeach = document.querySelector('.icon-search');
	const searchForm = document.querySelector('.search-form');

	const openElement = function (element) {
		element.classList.add('active');
		overlay.classList.add('active');
		document.body.style.overflow = 'hidden';
	};

	const closeElement = function (e) {
		if (e.target.closest('.btn-close')) overlay.click();
	};

	overlay.onclick = () => {
		overlay.classList.remove('active');
		menuMobile.classList.remove('active');
		cart?.classList.remove('active');
		searchForm.classList.remove('active');
		document.body.removeAttribute('style');
	};

	toggleMenu.onclick = () => openElement(menuMobile);
	toggleCart?.addEventListener('click', () => openElement(cart));

	iconSeach.onclick = () => searchForm.classList.add('active');
	searchForm.addEventListener('click', closeElement);

	cart?.addEventListener('click', closeElement);
	menuMobile.addEventListener('click', closeElement);

	window.addEventListener('scroll', function (e) {
		const header = document.querySelector('.header');
		header.classList.toggle('header-sticky', window.pageYOffset >= 100);
	});

	window.onclick = function (e) {
		if (e.target.classList.contains('btn-add-cart')) {
			// Xử lý nút thêm vào giỏ hàng
			const product_code = e.target.dataset.id;
			handleBtnAddCart(product_code, e);
		} else if (e.target.closest('.modal')) {
			// Xử lý các nút tắt modal thông báo đã thêm vào giỏ hàng
			const modal = document.querySelector('.modal');
			if (
				e.target.contains(modal) ||
				e.target.closest('.modal-close') ||
				e.target.closest('.btn-continue')
			) {
				modal.remove();
			}
		} else if (e.target.closest('.btn-filter')) {
			// Xử lý nút mở filter (Responsive)
			filter.style.opacity = 1;
			filter.style.visibility = 'visible';
		} else if (e.target.closest('.btn-finish')) {
			filter.removeAttribute('style');
		}
	};

	function handleBtnAddCart(product_code, e) {
		addToCart(product_code).then((data) => {
			e.target.innerHTML = '<div class="loader"></div>';
			// Đợi 1.5s
			setTimeout(() => {
				const { html, product, qtyInCart, qtyAddCart } = data;
				// Hiện thông báo
				cartMessage(product, qtyAddCart);
				// Render lại giỏ hàng và cập nhật số lượng có trong giỏ
				cart.innerHTML = html;
				cartAmountEl.innerText = 1 * cartAmountEl.innerText + qtyAddCart;
				// Kiểm tra số lượng còn lại
				console.log(product['quantity'], qtyInCart);
				if (product['quantity'] - qtyInCart > 0) {
					e.target.innerHTML = 'Add to cart';
				} else {
					e.target.insertAdjacentHTML(
						'afterend',
						'<button class="btn-disabled">Limit Exceeded</button>',
					);
					e.target.remove();
				}
			}, 1500);
			// console.log(html, product, qtyInCart);
		});
	}
});
