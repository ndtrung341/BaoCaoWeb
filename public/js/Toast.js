import { formatNumber } from "./HandleCart.js";

const success = function (msg) {
  Swal.fire({
    toast: true,
    icon: "success",
    showConfirmButton: false,
    title: msg,
    position: "top",
    timer: 2000,
    timerProgressBar: true,
    customClass: {
      container: "success",
    },
  });
};

const error = function (msg) {
  Swal.fire({
    toast: true,
    icon: "error",
    showConfirmButton: false,
    title: msg,
    position: "top",
    timer: 2000,
    timerProgressBar: true,
    customClass: {
      container: "error",
    },
  });
};

const warning = function (msg) {
  Swal.fire({
    toast: true,
    icon: "warning",
    showConfirmButton: false,
    title: msg,
    position: "top",
    timer: 2000,
    timerProgressBar: true,
    customClass: {
      container: "error",
    },
  });
};

const cartMessage = function (product, qtyAddCart) {
  let html = `<div class="modal">
					<div class="modal-container">
						<div class="modal-close"><i class="fas fa-times"></i></div>
						<div class="modal-content">
						<div class="modal-heading">
							<i class="fas fa-check-circle"></i>
							<p>Đã thêm vào giỏ hàng</p>
						</div>
						<div class="modal-body">
							<div>
								<div class="img"><img src="${product.image}" alt=""></div>
								<div class="">
								<p>${product.name}</p>
								<p>${formatNumber(product.price)}</p>
								<p>Qty: ${qtyAddCart}</p>
								</div>
							</div>
							<div class="">
								<button class="modal-button btn-continue">Continue Shopping</button>
								<a href="/mvc/cart/detail" class="modal-button">View Cart</a>
							</div>
						</div>
						</div>
					</div>
				</div>`;
  document.body.insertAdjacentHTML("beforeend", html);
};
export { success, error, warning, cartMessage };
