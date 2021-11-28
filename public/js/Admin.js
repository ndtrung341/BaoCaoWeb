/// <reference path="./jquery-3.6.0.js" />

/**
 * TẠO DANH SÁCH GỢI Ý TÌM KIẾM
 */
$('.form-search input').on('input', (e) => {
	const keyword = $(e.currentTarget).val();
	$('.search-list').html('');
	if (keyword.length < 3) return;
	$.ajax({
		url: '/mvc/admin/searchProduct/' + keyword,
		type: 'GET',
		success: (data) => {
			const html = data.data.map(
				(product) =>
					`<div class="search-item px-3 py-2">${product['product_code']} - ${product['name']}</div>`,
			);
			$('.search-list').html(html);
		},
	});
});

/**
 * XỬ LÝ KHI NHẤN RA NGOÀI Ô SEARCH
 */
$('.form-search input').on('blur', () => {
	$('.search-list').html('');
});

/**
 * XỬ LÝ KHI CHỌN GỢI Ý
 */
$('.search-list').on('click', function (e) {
	$('.form-search input').val($(e.target).text());
	$('input[name=product_code]').val($(e.target).text().split(' - ')[0]);
	$(this).html('');
});

/**
 * XỬ LÝ TÌM KIẾM
 */
$('.form-search').on('submit', function (e) {
	e.preventDefault();
	const product_code = $('input[name=product_code]').val();
	$.ajax({
		url: '/mvc/admin/findProduct',
		type: 'POST',
		data: { product_code: product_code },
		success: (data) => {
			if (!data.data) {
				Swal.fire({
					icon: 'error',
					title: 'Không có dữ liệu',
				});
			} else {
				console.log(data.data);
				renderTable(data.data);
				$('.pagination').remove();
			}
		},
	});
});

/**
 * CẬP NHẬT TRẠNG THÁI SẢN PHẨM
 */
function changeStatus() {
	const product_code = $(this).closest('tr').attr('data-id');
	const status = $(this).prev()[0].checked;
	$.ajax({
		url: '/mvc/product/changeStatus',
		type: 'POST',
		cache: false,
		data: {
			product_code: product_code,
			status: status ? 0 : 1,
		},
		success: (data) => {
			setTimeout(
				() =>
					Swal.fire({
						icon: data['status'] ? 'success' : 'error',
						title: data.message,
					}),
				500,
			);
		},
	});
}

/**
 * XÓA SẢN PHẨM
 */
function deleteProduct() {
	const product_code = $(this).closest('tr').attr('data-id');
	$.ajax({
		url: '/mvc/product/delete',
		type: 'POST',
		data: { product_code: product_code },
		success: function (data) {
			// console.log(data);
			setTimeout(
				() =>
					Swal.fire({
						icon: 'success',
						title: data.message,
					}).then(() => {
						window.location.reload();
					}),
				500,
			);
		},
		error: function (xhr) {
			console.error(xhr.statusText);
		},
	});
}

/**
 *
 */
$('.table-products').on('click', (e) => {
	if (e.target.closest('.toggle')) {
		const toggle = e.target.closest('.toggle');
		changeStatus.bind(toggle)();
	} else if (e.target.closest('.action-delete')) {
		deleteProduct.bind(e.target.closest('.action-delete'))();
	}
});

const storeImages = (() => {
	let images = []; // biến lưu tạm giá trị các file
	const fileInput = $('#image')[0]; // Thẻ input

	/**
	 * XỬ LÝ KHI NHẤN NÚT INPUT
	 */
	$(fileInput).on('change', function () {
		const files = fileInput.files;
		if (files) addImages(files);
	});

	/**
	 * XỬ LÝ KHI NHẤN CÁC NÚT XÓA HÌNH
	 */
	$('.image-list').on('click', function (e) {
		if (e.target.closest('.image-remove__btn')) {
			const imageEl = e.target.closest('.image-wrapper');
			const index = [...e.currentTarget.children]
				.slice(1)
				.findIndex((item) => item == imageEl);
			console.log(index, imageEl);
			deleteImage(index);
			imageEl.remove();
		}
	});

	/**
	 * LƯU CÁC FILE VÀO INPUT
	 * @param {Array} imgs danh sách các file hình
	 * @param {Boolean} isChange cho phép lưu thêm file vào biến chứa danh sách hay không
	 */
	const setToFile = function (imgs, isChange = false) {
		if (isChange) images.push(...imgs);
		const dataTransfer = new DataTransfer();
		for (const img of images) dataTransfer.items.add(img);
		fileInput.files = dataTransfer.files;
		console.log(fileInput.files);
	};

	/**
	 * XÓA HÌNH VỚI VỊ TRÍ CHỈ ĐỊNH
	 * @param {Number} index vị trí cần xóa
	 */
	const deleteImage = (index) => {
		images.splice(index, 1); // Thực hiện xóa
		setToFile(images); // lưu hình vào INPUT
	};

	// XÓA TẤT CẢ
	const deleteAll = () => (images.length = 0);

	/**
	 * HOÁN ĐỔI VỊ TRÍ FILE (Dùng cho khi kéo thả)
	 * @param {Number} from Vị trí nguồn
	 * @param {Number} to Vị trí đích
	 */
	const swapImage = (from, to) => {
		[images[from], images[to]] = [images[to], images[from]];
		setToFile(images); // lưu hình vào INPUT
	};

	/**
	 * THỰC HIỆN THÊM, LOẠI BỎ NHỮNG FILE TRÙNG LẶP,
	 * TẢI HÌNH RA GIAO DIỆN
	 * @param {Array} imgs Danh sách các file hình
	 */
	const addImages = (imgs) => {
		// Loại bỏ những ảnh trùng lặp (theo tên)
		const filter = [...imgs].filter(
			(img) => !images.map((img) => img.name).includes(img.name),
		);
		// lưu hình vào INPUT
		setToFile(filter, true);
		// tải hình ra giao diện
		for (const image of filter) {
			const url = URL.createObjectURL(image);
			renderImage(url);
		}
	};

	return {
		deleteImage,
		deleteAll,
		setToFile,
		addImages,
		swapImage,
	};
})();

function renderImage(imageURL) {
	const html = `<div class="image-wrapper shadow">
                     <div class="image-item rounded" draggable="true">
                        <img src="${imageURL}" class="rounded">
                        <span class="image-remove__btn"><i class="fas fa-trash-alt"></i></span>
                        <span class="image-overlay"></span>
                     </div>
                  </div>`;
	$('.image-list')[0].insertAdjacentHTML('beforeend', html);
}

// Từ src fetch sang FILE
// Dùng cho khi xem thông tin sản phẩm, lưu giá trị vào input
(async () => {
	const imagesEl = document.querySelectorAll('.image-list .image-item > img');
	if (imagesEl.length == 0) return;
	// fetch và ép sang blob
	const blobs = await Promise.all(
		[...imagesEl].map((image) => fetch(image.src).then((res) => res.blob())),
	);
	// chuyển blob sang file
	const images = blobs.map((blob, idx) => {
		const fileName = imagesEl[idx].src.match(/[^/]+$/g)[0]; // lấy tên file
		const file = new File([blob], fileName, { type: blob.type });
		return file;
	});
	storeImages.setToFile(images, true);
})();

// UPLOAD HÌNH BẰNG URL (BỎ TÍNH NĂNG NÀY CŨNG ĐƯỢC,DÙNG ĐỂ TẢI HÌNH VỀ VÀ TỰ LƯU VÀO THƯ MỤC CHO TIỆN)
$('.url').on('click', async () => {
	const url = prompt('Nhập URL', '');
	if (!url) return;
	const filename = url.match(/[\w]+\.(jpg|png)/g)[0];
	console.log(filename);
	const res = await fetch(url);
	const blob = await res.blob();
	const file = new File([blob], filename, {
		type: 'image/png',
	});
	storeImages.addImages([file]);
});

// DRAG DROP IMAGE
(function dragDropImages() {
	let currentTarget, currentCoord; // đối tượng cần kéo thả và vị trí của đối tượng
	let destTarget, destCoord; // đối tượng đích và vị trí

	const imageList = $('.image-list')[0];
	const dragEl = '.image-wrapper > .image-item'; // class của đối tượng cho phép kéo thả

	/**
	 * SỰ KIỆN XẢY KHI BẮT ĐẦU KÉO THẢ
	 */
	imageList.addEventListener('dragstart', function (e) {
		if (!e.target.closest(dragEl)) return;
		// Lấy đối tượng đã kích hoạt sk
		currentTarget = e.target.closest(dragEl);
		currentCoord = currentTarget.getBoundingClientRect();
		// Lưu src image vào dataTransfer
		e.dataTransfer.setData('text/plain', currentTarget.firstElementChild.src);
		// Làm cho đối tượng trống
		setTimeout(() => {
			currentTarget.classList.add('empty');
		}, 0);
	});

	/**
	 * SỰ KIỆN XẢY ra KHI KÉO THẢ KẾT THÚC
	 */
	imageList.ondragend = () => currentTarget.classList.remove('empty');

	imageList.ondragover = (e) => e.preventDefault();

	/**
	 * SỰ KIỆN XẢY KHI KÉO THẢ VÀO VÙNG CHỈ ĐỊNH
	 * lúc này chưa chắc đổi vị trí file trong INPUT,
	 */
	imageList.ondragenter = (e) => {
		e.preventDefault();
		destTarget = e.target.closest(dragEl);
		if (!destTarget || currentTarget == destTarget) return;
		destCoord = destTarget.getBoundingClientRect();
		// Tính toán vị trí để di chuyển hình ảnh
		const x = destCoord.x - currentCoord.x;
		const y = destCoord.y - currentCoord.y;
		// thực hiện di chuyển
		destTarget.firstElementChild.style.transform = `translate(${-x}px,${-y}px)`;
		destTarget.firstElementChild.style.transition = `transform 0.2s`;
	};

	/**
	 * SỰ KIỆN XẢY RA KHI KÉO THẢ RA KHỎI VÙNG CHỈ ĐỊNH
	 */
	imageList.ondragleave = (e) => {
		destTarget = e.target.closest(dragEl);
		if (!destTarget) return;
		// cho hình đích trở về vị trí cũ
		destTarget.firstElementChild.style.transform = `translate(0px,0px)`;
	};

	/**
	 * SỰ KIỆN XẢY KHI NGƯỜI DÙNG KÉO THẢ VÀO VÙNG CHỈ ĐỊNH VÀ THẢ CHUỘT RA
	 * lúc này chắc chắn phải đổi vị trí hình
	 */
	imageList.ondrop = (e) => {
		destTarget = e.target.closest(dragEl);
		if (!destTarget) return;
		destTarget.firstElementChild.removeAttribute('style');
		// hoán đổi src trong giao diện
		currentTarget.firstElementChild.src = destTarget.firstElementChild.src;
		destTarget.firstElementChild.src = e.dataTransfer.getData('text');
		// hoán đổi file hình trong INPUT
		const imagesEl = [...imageList.children].slice(1);
		const from = imagesEl.findIndex((item) => item.contains(currentTarget));
		const to = imagesEl.findIndex((item) => item.contains(destTarget));
		storeImages.swapImage(from, to);
		// xóa data drag
		e.dataTransfer.clearData();
	};
})();

// FORM SUBMIT
$('.form-product').on('submit', function (e) {
	e.preventDefault();
	const formData = new FormData(e.currentTarget);
	console.log(...formData);
	const action = $(this).attr('action');

	$.ajax({
		url: '/mvc/product/' + action,
		type: 'POST',
		data: formData,
		processData: false,
		contentType: false,
		success: (data) => {
			setTimeout(
				() =>
					Swal.fire({
						icon: 'success',
						title: data.message,
					}).then(() => {
						window.location.reload();
					}),
				500,
			);
		},
		error: function (xhr) {
			console.error(xhr.statusText);
		},
	});
});
import { formatNumber } from './HandleCart.js';

function renderTable(product) {
	const html = `<tr data-id=${product['product_code']}>
               <td class="">
                  <div class="d-flex align-items-center">
                     <img src="${
								product['image']
							}" style="width: 80px;height: 80px;object-fit: contain;"></img>
                     <div class="ms-2">
                        <p class="fw-bold mb-0">${product['name']}</p>
                        <p class="fst-italic text-secondary">${
									product['product_code']
								}</p>
                     </div>
                  </div>
               </td>
               <td class="align-middle text-center">
                  <label class="toggle-wrapper">
                     <input type="checkbox" hidden ${
								product['status'] ? 'checked' : ''
							}>
                     <div class="toggle rounded-pill" onClick=changeStatus()>
                        <div class="rounded-circle bg-white"></div>
                     </div>
                  </label>
               </td>
               <td class="align-middle">${product['theme']}</td>
               <td class="text-center align-middle">${product['quantity']}</td>
               <td class="text-end align-middle">${formatNumber(
						product['price'],
					)}</td>
               <td class="text-center align-middle text-light">
                  <div class="table-action d-flex justify-content-center">
                     <a href="/mvc/admin/editProduct/${
								product['product_code']
							}" class="action-edit bg-warning text-light text-decoration-none">
                        <i class="fas fa-pencil-alt"></i>
                     </a>
                     <div class="action-delete ms-2 bg-danger">
                        <i class="fas fa-trash-alt"></i>
                     </div>
                  </div>
               </td>
            </tr>`;
	$('.table-products tbody').html(html);
}
